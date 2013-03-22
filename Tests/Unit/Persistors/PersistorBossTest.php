<?php

namespace Liip\DataAggregatorBundle\Tests\Unit\Persistors;

use Doctrine\Common\Collections\ArrayCollection;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss;
use Liip\DataAggregatorBundle\Persistors\PersistorBoss;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;

/**
 *
 */
class PersistorBossTest extends DataAggregatorBundleTestCase
{
    public function setUp()
    {
        $this->loaderEntityData = array(
            'BossId' => 'twelveCharLong',
            'Stufe'   => 'someStep',
            'Bw'   => 'bwSomeValue',
            'Bb'   => 'bbSomeValue',
            'Vs'   => 'vsSomeValue',
            'Th'   => 'thSomeValue',
            'Fa'   => 'faSomeValue',
            'TitleDe'   => 'germanTitle',
            'TitleFr'   => 'frenchTitle',
            'TitleIt'   => 'italianTitle',
            'TitleEn'   => 'englishTitle',
            'Status' => 10,
            'ActiveFromDate' => '1970-01-01',
            'Responsible' => 'John Doe',
            'Category' => 'someCategory',
            'PostStatus' => 20,
            'PostActiveFromDate' => '1970-01-01',
            'Rpa' => 'fooo',
        );
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::persist
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::__construct
     */
    public function testPersist()
    {
        $expected = array();

        $configuration = $this->getPersistorBossConfigurationFixture();
        $entity = $this->getLoaderEntityBossFixture($configuration, $this->loaderEntityData);

        $data = new ArrayCollection(
            array(
                $entity,
                $entity,
            )
        );

        $repository = $this->getEntityRepositoryMock(array('findOneBy'));
        $repository
            ->expects($this->atLeastOnce())
            ->method('findOneBy')
            ->with(
                $this->equalTo(array('boss_id' => 'twelveCharLong'))
            )
            ->will(
                $this->returnValue(null)
            );

        $em = $this->getEntityManagerMock(array('getRepository', 'persist', 'flush'));
        $em
            ->expects($this->exactly(2))
            ->method('getRepository')
            ->will(
                $this->returnValue($repository)
            );
        $em
            ->expects($this->exactly(2))
            ->method('persist')
            ->with(
                $this->isInstanceOf('\Liip\DataAggregatorBundle\Entity\EntityBoss')
            );

        $boss = new PersistorBoss($em, $configuration);
        $boss->persist($data);
    }

    /**
     * @dataProvider persistExceptionDataprovider
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::persist
     */
    public function testPersistExpectingException($exception)
    {
        $expected = array();

        $configuration = $this->getPersistorBossConfigurationFixture();
        $entity = $this->getLoaderEntityBossFixture($configuration, $this->loaderEntityData);

        $data = new ArrayCollection(array($entity));

        $em = $this->getEntityManagerMock(array('getRepository', 'flush'));
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->will(
                $this->throwException($exception)
            );

        $em
            ->expects($this->once())
            ->method('flush')
            ->with($this->isNull());

        $boss = new PersistorBoss($em, $configuration);
        $boss->persist($data);
    }

    public static function persistExceptionDataprovider()
    {
        return array(
            '\Doctrine\ORM\ORMException' => array(new \Doctrine\ORM\ORMException('')),
            '\Doctrine\ORM\OptimisticLockException' => array(new \Doctrine\ORM\OptimisticLockException('', '')),
            '\Doctrine\ORM\TransactionRequiredException' => array(new \Doctrine\ORM\TransactionRequiredException('')),
        );
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::persistDataInEntity
     */
    public function testPersistDataInEntity()
    {
        $configuration = $this->getPersistorBossConfigurationFixture();
        $entityData = $this->getLoaderEntityBossFixture($configuration, $this->loaderEntityData);

        $em = $this->getEntityManagerMock(array('persist'));
        $em
            ->expects($this->once())
            ->method('persist')
            ->with(
                $this->isInstanceOf('\Liip\DataAggregatorBundle\Entity\EntityBoss')
            );

        $entity = $this->getMockBuilder('\Liip\DataAggregatorBundle\Entity\EntityBoss')
            ->setMethods(array('setBossId'))
            ->getMock();
        $entity
            ->expects($this->once())
            ->method('setBossId')
            ->with(
                $this->isType('string')
            );

        $boss = $this->getProxyBuilder('\Liip\DataAggregatorBundle\Persistors\PersistorBoss')
            ->setMethods(array('persistDataInEntity'))
            ->setProperties(array('isNewEntity'))
            ->setConstructorArgs(array($em, $configuration))
            ->getProxy();

        $boss->isNewEntity = true;
        $boss->persistDataInEntity($entityData, $entity);
    }

    /**
     * @expectedException \Liip\DataAggregatorBundle\Persistors\PersistorException
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::persistDataInEntity
     */
    public function testPersistDataInEntityNoEntityData()
    {
        $data = array();
        $assertion = $this->getAssertionMock();

        $entityData = new LoaderEntityBoss($assertion, $this->getPersistorBossConfigurationFixture());
        $entity = $this->getMock('\Liip\DataAggregatorBundle\Entity\EntityBoss');

        $boss = $this->getProxyBuilder('\Liip\DataAggregatorBundle\Persistors\PersistorBoss')
            ->setMethods(array('persistDataInEntity'))
            ->disableOriginalConstructor()
            ->getProxy();

        $boss->persistDataInEntity($entityData, $entity);
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::getEntity
     */
    public function testGetEntityFromRepository()
    {
        $entity = new \stdClass();
        $entityId = 'thisIsChar42';  // max 12 chars (database limit)

        $repository = $this->getEntityRepositoryMock(array('findOneBy'));
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo(array('boss_id' => 'thisIsChar42'))
            )
            ->will(
                $this->returnValue($entity)
            );

        $em = $this->getEntityManagerMock(array('getRepository'));
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->will(
                $this->returnValue($repository)
            );

        $boss = $this->getProxyBuilder('\Liip\DataAggregatorBundle\Persistors\PersistorBoss')
            ->setMethods(array('getEntity'))
            ->setConstructorArgs(array($em, $this->getPersistorBossConfigurationFixture()))
            ->getProxy();

        $this->assertSame($entity, $boss->getEntity($entityId));
    }

    /**
     * @covers \Liip\DataAggregatorBundle\Persistors\PersistorBoss::getEntity
     */
    public function testGetEntityNewEntity()
    {
        $entity = new \Liip\DataAggregatorBundle\Entity\EntityBoss();
        $entityId = 'thisIsChar42';  // max 12 chars (database limit)

        $repository = $this->getEntityRepositoryMock(array('findOneBy'));
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo(array('boss_id' => 'thisIsChar42'))
            )
            ->will(
                $this->returnValue(null)
            );

        $em = $this->getEntityManagerMock(array('getRepository'));
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->will(
                $this->returnValue($repository)
            );

        $boss = $this->getProxyBuilder('\Liip\DataAggregatorBundle\Persistors\PersistorBoss')
            ->setMethods(array('getEntity'))
            ->setConstructorArgs(array($em, $this->getPersistorBossConfigurationFixture()))
            ->getProxy();

        $this->assertEquals($entity, $boss->getEntity($entityId));
    }
}
