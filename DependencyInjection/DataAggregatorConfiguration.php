<?php
namespace Liip\DataAggregatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class DataAggregatorConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('data_aggregator');

        $this->addBossSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add default configuration for BOSS.
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode
     */
    public function addBossSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('boss')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('loader')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('STUFE')->defaultValue('Stufe')->end()
                                ->scalarNode('BOSS_ID')->defaultValue('BossId')->end()
                                ->scalarNode('BW_ID')->defaultValue('Bw')->end()
                                ->scalarNode('BB_ID')->defaultValue('Bb')->end()
                                ->scalarNode('VS_ID')->defaultValue('Vs')->end()
                                ->scalarNode('TH_ID')->defaultValue('Th')->end()
                                ->scalarNode('FA_ID')->defaultValue('Fa')->end()
                                ->scalarNode('BEZ_D')->defaultValue('TitleDe')->end()
                                ->scalarNode('BEZ_F')->defaultValue('TitleFr')->end()
                                ->scalarNode('BEZ_I')->defaultValue('TitleIt')->end()
                                ->scalarNode('BEZ_E')->defaultValue('TitleEn')->end()
                                ->scalarNode('AKTUELL_STATUS')->defaultValue('Status')->end()
                                ->scalarNode('AKTUELL_GUELTIG_AB')->defaultValue('ActiveFromDate')->end()
                                ->scalarNode('VERANTWORTLICH')->defaultValue('Responsible')->end()
                                ->scalarNode('SPARTE')->defaultValue('Category')->end()
                                ->scalarNode('FOLGE_STATUS')->defaultValue('PostStatus')->end()
                                ->scalarNode('FOLGE_GUELTIG_AB')->defaultValue('PostActiveFromDate')->end()
                                ->scalarNode('RPA_FLAG')->defaultValue('Rpa')->end()
                            ->end()
                        ->end()
                        ->arrayNode('persistor')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('Stufe')->defaultValue('Stufe')->end()
                                ->scalarNode('BossId')->defaultValue('BossId')->end()
                                ->scalarNode('Bw')->defaultValue('Bw')->end()
                                ->scalarNode('Bb')->defaultValue('Bb')->end()
                                ->scalarNode('Vs')->defaultValue('Vs')->end()
                                ->scalarNode('Th')->defaultValue('Th')->end()
                                ->scalarNode('Fa')->defaultValue('Fa')->end()
                                ->scalarNode('TitleDe')->defaultValue('TitleDe')->end()
                                ->scalarNode('TitleFr')->defaultValue('TitleFr')->end()
                                ->scalarNode('TitleIt')->defaultValue('TitleIt')->end()
                                ->scalarNode('TitleEn')->defaultValue('TitleEn')->end()
                                ->scalarNode('Status')->defaultValue('Status')->end()
                                ->scalarNode('ActiveFromDate')->defaultValue('ActiveFromDate')->end()
                                ->scalarNode('Responsible')->defaultValue('Responsible')->end()
                                ->scalarNode('Category')->defaultValue('Category')->end()
                                ->scalarNode('PostStatus')->defaultValue('PostStatus')->end()
                                ->scalarNode('PostActiveFromDate')->defaultValue('PostActiveFromDate')->end()
                                ->scalarNode('Rpa')->defaultValue('Rpa')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
