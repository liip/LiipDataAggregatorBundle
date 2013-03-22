<?php

namespace Liip\DataAggregatorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DataAggregatorExtension extends Extension
{
    /**
     * Loads the current configuration into the DIC parameter bag.
     *
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // get configuration tree
        $configuration = $this->getConfiguration(array(), $container);

        // merge configurations
        $config = $this->processConfiguration($configuration, $configs);

        // set parameters
        foreach ($config as $dataSource => $parameters) {
            if (isset($parameters)) {
                $container->setParameter($this->getAlias() . '.' . $dataSource, $parameters);
            }
        }
    }

    /**
     * Provides the alias of the bundle to be used in the configuration as prefix.
     *
     * @return string
     */
    public function getAlias()
    {
        return 'data_aggregator';
    }

    /**
     * Point framework to non standard named configuration class.
     *
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \MigrosApi\DataAggregatorBundle\DependencyInjection\DataAggregatorConfiguration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new DataAggregatorConfiguration();
    }
}
