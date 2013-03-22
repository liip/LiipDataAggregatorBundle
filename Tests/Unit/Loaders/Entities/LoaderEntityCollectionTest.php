<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;

class LoaderEntityCollectionTest extends DataAggregatorBundleTestCase
{

    protected function getCollection() {
        $collection = new LoaderEntityCollection();
        $collection[] = 'tux';
        $collection[] = 'beastie';
        $collection['mascott'] = 'tux';
        return $collection;
    }

    /**
     * @dataProvider isCollectionDataprovider
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::isCollection
     */
    public function testIsCollection($expected, $arg) {
        $collection = new LoaderEntityCollection();

        $this->assertEquals($expected, $collection->isCollection($arg));
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::merge
     */
    public function testMergeExpectingOnlyOneArgumentGiven() {
        $collection = new LoaderEntityCollection();
        $this->assertSame($collection, $collection->merge($collection));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::merge
     */
    public function testMergeExpectingInvalidArgumentExceptionBecauseInvalidArgumentsGiven() {
        $collection = new LoaderEntityCollection();
        $collection->merge(new LoaderEntityCollection(), 'not a collection at all');
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::merge
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::doMerge
     */
    public function testMerge() {
        $c1 = new LoaderEntityCollection();
        $c1['mascott'] = 'tux';
        $c1[] = 'tux';
        $c2 = new LoaderEntityCollection();
        $c2['mascott'] = 'Beastie';
        $c2[] = 'tux';

        $collection = new LoaderEntityCollection();
        $c3 = $collection->merge($c1, $c2);

        $this->assertEquals('Beastie', $c3['mascott']);
        $this->assertCount(3, $c3);
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::merge
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityCollection::doMerge
     */
    public function testMergeMoreThanTwoCollections() {
        $c1 = new LoaderEntityCollection();
        $c1['mascott'] = 'tux';
        $c1[] = 'tux';
        $c2 = new LoaderEntityCollection();
        $c2['mascott'] = 'Beastie';
        $c2[] = 'tux';
        $c3 = new LoaderEntityCollection();
        $c3['os'] = 'Linux';
        $c3[] = 'gnu';

        $collection = new LoaderEntityCollection();
        $c = $collection->merge($c1, $c2, $c3);

        $this->assertEquals('Beastie', $c['mascott']);
        $this->assertCount(5, $c);
    }

    public static function isCollectionDataprovider() {
        return array(
            'a valid collection » true' => array(true, new LoaderEntityCollection()),
            'not a collection at all » false' => array(false, 'not a collection at all'),
        );
    }

}
