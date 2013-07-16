<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\Loaders;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DriverManager;
use Liip\DataAggregatorBundle\Loaders\LoaderBoss;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;

class LoaderBossTest extends DataAggregatorBundleTestCase
{
    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::load
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::__construct
     */
    public function testLoad()
    {
        $expected = array();

        $dbServiceMock = $this->getDbServiceMock(array("fetchAll"));
        $dbServiceMock
            ->expects($this->once())
            ->method("fetchAll")
            ->will(
            $this->returnValue(array())
        );

        $valueObjectMock = $this->getLoaderEntityMock();

        $loader = new LoaderBoss($dbServiceMock, $valueObjectMock);

        $this->assertEquals($expected, $loader->load());
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::process
     */
    public function testProcessWithoutData()
    {
        $expected = array();

        $dbServiceMock = $this->getDbServiceMock();

        $valueObjectMock = $this->getLoaderEntityMock(array("init"));

        $loader = $this->getProxyBuilder("\\Liip\\DataAggregatorBundle\\Loaders\\LoaderBoss")
            ->setMethods(array("process"))
            ->setConstructorArgs(array($dbServiceMock, $valueObjectMock))
            ->getProxy();

        $this->assertEquals($expected, $loader->process(array()));
    }


    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::process
     */
    public function testProcessWithDataAndEmptyItem()
    {
        $expected = array();

        $dbServiceMock = $this->getDbServiceMock();

        $valueObjectMock = $this->getLoaderEntityMock(array("init"));

        $valueObjectMock
            ->expects($this->once())
            ->method("init")
            ->will(
            $this->throwException(new InvalidArgumentException("Error Message", 42))
        );

        $loader = $this->getProxyBuilder("\\Liip\\DataAggregatorBundle\\Loaders\\LoaderBoss")
            ->setMethods(array("process"))
            ->setConstructorArgs(array($dbServiceMock, $valueObjectMock))
            ->getProxy();

        $this->assertEquals($expected, $loader->process(array(array())));
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::process
     */
    public function testProcessWithData()
    {
        $item = array(
            'BOSS_ID' => '010101010101',
            'STUFE' => '5',
            'BW_ID' => '12',
            'BB_ID' => '21',
            'VS_ID' => '23',
            'TH_ID' => '42',
            'FA_ID' => '14',
            'BEZ_D' => 'Title de',
            'BEZ_F' => 'Title fr',
            'BEZ_I' => 'Title it',
            'BEZ_E' => 'Title de',
            'AKTUELL_STATUS' => '78',
            'AKTUELL_GUELTIG_AB' => null,
            'VERANTWORTLICH' => 'responsible',
            'SPARTE' => '2',
            'FOLGE_STATUS' => null,
            'FOLGE_GUELTIG_AB' => null,
            'RPA_FLAG' => 'T',
        );

        $dbServiceMock = $this->getDbServiceMock();

        $entity = new LoaderEntityBoss(new Assertion(), $this->getLoaderBossConfigurationFixture());
        $entity->init($item);

        $expected = array($entity);

        $loader = $this->getProxyBuilder("\\Liip\\DataAggregatorBundle\\Loaders\\LoaderBoss")
            ->setMethods(array("process"))
            ->setConstructorArgs(array($dbServiceMock, $entity))
            ->getProxy();

        $this->assertEquals($expected, $loader->process(array($item)));
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::stopPropagation
     */
    public function testStopPropagation()
    {
        $dbServiceMock = $this->getDbServiceMock();

        $valueObjectMock = $this->getLoaderEntityMock();

        $loader = new LoaderBoss($dbServiceMock, $valueObjectMock);

        $this->assertFalse($loader->stopPropagation());
    }


    /**
     * @covers \Liip\DataAggregatorBundle\Loaders\LoaderBoss::process
     */
    public function testProcessWithMultipleDataItegration()
    {
        $data = $this->getBossDatabaseResultFixture();

        $dbServiceMock = $this->getDbServiceMock();

        $loaderEntityBoss = new LoaderEntityBoss(new Assertion(), $this->getLoaderBossConfigurationFixture());

        $loader = $this->getProxyBuilder("\\Liip\\DataAggregatorBundle\\Loaders\\LoaderBoss")
            ->setMethods(array("process"))
            ->setConstructorArgs(array($dbServiceMock, $loaderEntityBoss))
            ->getProxy();

        $processedData = $loader->process($data);

        $this->assertCount(100, $processedData);
    }
}
