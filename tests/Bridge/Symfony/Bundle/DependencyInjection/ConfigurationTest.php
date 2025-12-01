<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ArrayNode;

final class ConfigurationTest extends TestCase
{
    public function testItGetsConfigTreeBuilder(): void
    {
        $treeBuilder = (new Configuration())->getConfigTreeBuilder();

        /** @var ArrayNode $rootNode */
        $rootNode = $treeBuilder->buildTree();

        $this->assertSame('calendr', $rootNode->getName());
        $this->assertInstanceOf(ArrayNode::class, $rootNode->getChildren()['periods']);
    }
}
