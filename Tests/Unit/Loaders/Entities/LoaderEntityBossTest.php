<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\Loaders\Entities;

use Assert\AssertionFailedException;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityException;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;

class LoaderEntityBossTest extends DataAggregatorBundleTestCase
{
    /**
     * Provides an initiated BOSS object.
     *
     * @return \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss
     */
    protected function getBOSSObjectFixture()
    {
        $data = array_values($this->getLoaderBossConfigurationFixture());

        $assertion = $this->getMockBuilder("\\Assert\\Assertion")
            ->setMethods(array("notEmpty"))
            ->getMock();
        $assertion
            ->staticExpects($this->once())
            ->method("notEmpty")
            ->with(
                $this->isType("array")
            );

        $boss = new LoaderEntityBoss($assertion, array());
        foreach ($data as $property) {
            $boss->$property = '';
        }

        return $boss;
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::init
     */
    public function testInit()
    {
        $data = array(
            'STUFE' => '',
            'RPA_FLAG' => '',
        );

        $expected = $this->getBOSSObjectFixture();

        $assertion = $this->getMockBuilder("\\Assert\\Assertion")
            ->setMethods(array("notEmpty"))
            ->setMockClassName("Assert_Assertion_" . sha1(microtime()))
            ->getMock();
        $assertion
            ->staticExpects($this->exactly(2))
            ->method("notEmpty")
            ->with(
                $this->isType("array")
            );

        $entity = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());
        $entity->init($data);

        $this->assertEquals($expected->Stufe, $entity->Stufe);
        $this->assertEquals($expected->Rpa, $entity->Rpa);
    }

    /**
     * @expectedException \Assert\AssertionFailedException
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::init
     */
    public function testInitWithEmptyData()
    {
        $assertion = new \Assert\Assertion();

        $boss = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());
        $boss->init(array());
    }

    /**
     * @expectedException \Assert\AssertionFailedException
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::setConfiguration
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::__construct
     */
    public function testSetConfigurationWithEmptyConfiguration()
    {
        $assertion = new \Assert\Assertion();

        // calls setConfiguration via constructor
        $boss = new LoaderEntityBoss($assertion, array());
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::setConfiguration
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::__construct
     */
    public function testSetConfiguration()
    {
        $configuration = $this->getLoaderBossConfigurationFixture();
        $assertion = new \Assert\Assertion();

        // calls setConfiguration via constructor
        $boss = new LoaderEntityBoss($assertion, $configuration);

        $this->assertAttributeSame($configuration, 'configuration', $boss);
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::isEmpty
     */
    public function testIsEmpty()
    {
        $assertion = new \Assert\Assertion();

        // calls setConfiguration via constructor
        $boss = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());

        $this->assertTrue($boss->isEmpty());
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::isEmpty
     */
    public function testIsNotEmpty()
    {
        $data = array(
            'BOSS_ID' => '12_CharsLong',
        );

        $assertion = new \Assert\Assertion();

        // calls setConfiguration via constructor
        $boss = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());
        $boss->init($data);

        $this->assertFalse($boss->isEmpty());
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::reset
     */
    public function testReset()
    {
        $data = array(
            'BOSS_ID' => '12_CharsLong',
        );

        $assertion = new \Assert\Assertion();
        $boss = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());
        $boss->init($data);
        $boss->reset();

        $this->assertTrue($boss->isEmpty());
        $this->assertObjectNotHasAttribute('BossId', $boss);
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss::reset
     */
    public function testResetOnNotInitializedEntity()
    {
        $assertion = new \Assert\Assertion();
        $boss = new LoaderEntityBoss($assertion, $this->getLoaderBossConfigurationFixture());
        $boss->reset();

        $this->assertTrue($boss->isEmpty());
        $this->assertObjectNotHasAttribute('BossId', $boss);
    }

}
