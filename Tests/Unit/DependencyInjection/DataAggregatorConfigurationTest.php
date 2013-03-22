<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\DependencyInjection;

use Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorConfiguration;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DataAggregatorConfigurationTest extends DataAggregatorBundleTestCase
{

    /**
     * Tests the return value has correct type and that the name is as configured.
     * Also check that the tree has children.
     *
     * @covers \Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorConfiguration::getConfigTreeBuilder
     */
    public function testGetConfigTreeBuilder()
    {
        $configuration = new DataAggregatorConfiguration();

        $treeBuilder = $configuration->getConfigTreeBuilder();
        $tree = $treeBuilder->buildTree();

        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\Builder\TreeBuilder',
            $treeBuilder
        );

        $this->assertAttributeEquals('data_aggregator', 'name', $tree);
        $this->assertAttributeNotEmpty('children', $tree);
    }


    /**
     * Tests that addBossSection returns Boss default configuration with 18 children.
     *
     * @covers \Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorConfiguration::addBossSection
     */
    public function testAddBossSection()
    {
        $configuration = new DataAggregatorConfiguration();
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('data_aggregator');

        $configuration->addBossSection($rootNode);

        $bossConfig = $this->readAttribute($rootNode, 'children');
        $bossLoader = $this->readAttribute($bossConfig['boss'], 'children');

        // sections loader and persistor exist
        $this->assertAttributeCount(2, 'children', $bossConfig['boss']);
        $this->assertAttributeCount(18, 'children', $bossLoader['loader']);
        $this->assertAttributeCount(18, 'children', $bossLoader['persistor']);
    }
}
