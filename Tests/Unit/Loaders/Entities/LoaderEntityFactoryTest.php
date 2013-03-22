<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\Loaders\Entities;

use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityFactory;

class LoaderEntityFactoryTest extends DataAggregatorBundleTestCase
{

    public function testGetInstanceOf()
    {
        $factory = new LoaderEntityFactory(new \Assert\Assertion());

        $this->assertInstanceOf(
            '\Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss',
            $factory->getInstanceOf(
                'LoaderEntityBoss',
                $this->getLoaderBossConfigurationFixture()
            )
        );
    }
}
