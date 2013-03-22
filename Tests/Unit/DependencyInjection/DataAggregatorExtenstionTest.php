<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\DependencyInjection;

use Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorExtension;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;

class DataAggregatorExtenstionTest extends DataAggregatorBundleTestCase
{

    /**
     * Test the returned alias is correct.
     *
     * @covers \Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorExtension::getAlias
     */
    public function testGetAlias()
    {
        $extension = new DataAggregatorExtension();

        $this->assertEquals('data_aggregator', $extension->getAlias());
    }

    /**
     * Test the returned value has the right type.
     *
     * @covers \Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorExtension::getConfiguration
     */
    public function testGetConfiguration()
    {
        $extension = new DataAggregatorExtension();

        $config = array();
        $container =$this->getMockBuilder("\\Symfony\\Component\\DependencyInjection\\ContainerBuilder")
            ->getMock();

        $this->assertInstanceOf(
            'Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorConfiguration',
            $extension->getConfiguration($config, $container)
        );
    }

    /**
     * @covers \Liip\DataAggregatorBundle\DependencyInjection\DataAggregatorExtension::load
     */
    public function testLoad()
    {
        $configuration = new DataAggregatorExtension();

        $container =$this->getMockBuilder("\\Symfony\\Component\\DependencyInjection\\ContainerBuilder")
            ->setMethods(array('setParameter'))
            ->getMock();
        $container
            ->expects($this->once())
            ->method('setParameter')
            ->with(
                $this->equalTo('data_aggregator.boss'),
                $this->isType('array')
            );

        $configuration->load(array(), $container);
    }
}
