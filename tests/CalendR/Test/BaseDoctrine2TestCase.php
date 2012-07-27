<?php

namespace CalendR\Test;

use CalendR\Test\Stubs\Event;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;

class BaseDoctrine2TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CachedReader
     */
    protected $reader;

    /**
     * @var EntityManager
     */
    protected $em;

    public function setUpDoctrine()
    {
        $this->reader = new AnnotationReader();
        $this->reader = new CachedReader($this->reader, new ArrayCache());

        $this->em = $em = EntityManager::create(
            array('driver' => 'pdo_sqlite', 'memory' => true),
            $this->getMockAnnotatedConfig()
        );

        $schema = array($em->getClassMetadata('CalendR\\Test\\Stubs\\Event'));

        $st = new SchemaTool($this->em);
        $st->dropSchema(array());
        $st->createSchema($schema);

        $this->em->createQueryBuilder()
            ->delete('CalendR\\Test\\Stubs\\Event', 'e')
            ->getQuery()
            ->execute()
        ;
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
     *
     * @return \Doctrine\ORM\Mapping\Driver\Driver
     */
    protected function getMetadataDriverImplementation()
    {
        return new AnnotationDriver($this->reader);
    }

    /**
     * Return a Mock config. Come from the Gedmo's Doctrine Extensions test suite
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockAnnotatedConfig()
    {
        // We need to mock every method except the ones which
        // handle the filters
        $configurationClass = 'Doctrine\ORM\Configuration';
        $refl = new \ReflectionClass($configurationClass);
        $methods = $refl->getMethods();

        $mockMethods = array();

        foreach ($methods as $method) {
            if ($method->name !== 'addFilter' && $method->name !== 'getFilterClassName') {
                $mockMethods[] = $method->name;
            }
        }

        $config = $this->getMock($configurationClass, $mockMethods);

        $config
            ->expects($this->once())
            ->method('getProxyDir')
            ->will($this->returnValue(__DIR__.'/../../temp'))
        ;

        $config
            ->expects($this->once())
            ->method('getProxyNamespace')
            ->will($this->returnValue('Proxy'))
        ;

        $config
            ->expects($this->once())
            ->method('getAutoGenerateProxyClasses')
            ->will($this->returnValue(true))
        ;

        $config
            ->expects($this->once())
            ->method('getClassMetadataFactoryName')
            ->will($this->returnValue('Doctrine\\ORM\\Mapping\\ClassMetadataFactory'))
        ;

        $mappingDriver = $this->getMetadataDriverImplementation();

        $config
            ->expects($this->any())
            ->method('getMetadataDriverImpl')
            ->will($this->returnValue($mappingDriver))
        ;

        $config
            ->expects($this->any())
            ->method('getDefaultRepositoryClassName')
            ->will($this->returnValue('Doctrine\\ORM\\EntityRepository'))
        ;

        $config
            ->expects($this->any())
            ->method('getQuoteStrategy')
            ->will($this->returnValue(new DefaultQuoteStrategy()))
        ;

        $config
            ->expects($this->any())
            ->method('getNamingStrategy')
            ->will($this->returnValue(new DefaultNamingStrategy()))
        ;

        return $config;
    }

    public static function getStubEvents()
    {
        return array();
    }
}
