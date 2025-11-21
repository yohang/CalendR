<?php

declare(strict_types=1);

namespace CalendR\Test;

use CalendR\Test\Stubs\Event;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadataFactory;

/**
 * @test
 */
class BaseDoctrine2TestCase extends TestCase
{
    protected Reader $reader;

    protected EntityManagerInterface $em;

    public function setUpDoctrine(): void
    {
        $this->reader = new AnnotationReader;
        $this->reader = new PsrCachedReader($this->reader, new ArrayAdapter);

        $this->em = $em = EntityManager::create(
            ['driver' => 'pdo_sqlite', 'memory' => true],
            $this->getMockAnnotatedConfig()
        );

        $schema = [$em->getClassMetadata(Event::class)];

        $st = new SchemaTool($this->em);
        $st->dropSchema([]);
        $st->createSchema($schema);

        $this->em->createQueryBuilder()
                 ->delete(Event::class, 'e')
                 ->getQuery()
                 ->execute();

        foreach (static::getStubEvents() as $evt) {
            $event = new Event;
            $event->setId($evt[0]);
            $event->setBegin($evt[1]);
            $event->setEnd($evt[2]);
            $em->persist($event);
        }

        $em->flush();
    }

    /**
     * Creates default mapping driver
     */
    protected function getMetadataDriverImplementation(): MappingDriver
    {
        return new AnnotationDriver($this->reader);
    }

    /**
     * Return a Mock config. Come from the Gedmo's Doctrine Extensions test suite
     */
    protected function getMockAnnotatedConfig(): Configuration
    {
        // We need to mock every method except the ones which
        // handle the filters
        $configurationClass = Configuration::class;
        $refl               = new \ReflectionClass($configurationClass);
        $methods            = $refl->getMethods();

        $mockMethods = [];

        foreach ($methods as $method) {
            if ($method->name !== 'addFilter' && $method->name !== 'getFilterClassName') {
                $mockMethods[] = $method->name;
            }
        }

        $config = $this->getMockBuilder($configurationClass)->onlyMethods($mockMethods)->getMock();

        $config
            ->expects($this->once())
            ->method('getProxyDir')
            ->willReturn(__DIR__ . '/../../temp');

        $config
            ->expects($this->once())
            ->method('getProxyNamespace')
            ->willReturn('Proxy');

        $config
            ->expects($this->once())
            ->method('getAutoGenerateProxyClasses')
            ->willReturn(true);

        $config
            ->expects($this->once())
            ->method('getClassMetadataFactoryName')
            ->willReturn(ClassMetadataFactory::class);

        $config
               ->method('getRepositoryFactory')
               ->willReturn(new DefaultRepositoryFactory);

        $config
               ->method('getDefaultQueryHints')
               ->willReturn([]);

        $mappingDriver = $this->getMetadataDriverImplementation();

        $config
            ->method('getMetadataDriverImpl')
            ->willReturn($mappingDriver);

        $config
            ->method('getDefaultRepositoryClassName')
            ->willReturn(EntityRepository::class);

        $config
            ->method('getQuoteStrategy')
            ->willReturn(new DefaultQuoteStrategy());

        $config
            ->method('getNamingStrategy')
            ->willReturn(new DefaultNamingStrategy());

        return $config;
    }

    public static function getStubEvents(): array
    {
        return [];
    }
}
