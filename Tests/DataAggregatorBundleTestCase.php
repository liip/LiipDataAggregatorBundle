<?php

namespace Liip\DataAggregatorBundle\Tests;

use lapistano\ProxyObject\ProxyBuilder;

abstract class DataAggregatorBundleTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides an instance of the ProxyBuilder
     *
     * @param string $className
     *
     * @return \lapistano\ProxyObject\ProxyBuilder
     */
    protected function getProxyBuilder($className)
    {
        return new ProxyBuilder($className);
    }

    /**
     * Provides a stub of the LoaderInterface.
     *
     * @return \Liip\DataAggregator\Loaders\LoaderInterface
     */
    protected function getDataLoaderStub()
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Loaders\\LoaderInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Provides a mock of the LoaderInterface.
     *
     * @return \Liip\DataAggregator\Loaders\LoaderInterface
     */
    protected function getDataLoaderMock(array $methods = array())
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Loaders\\LoaderInterface'
        )
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a database service mock object.
     *
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getDbServiceMock(array $methods = array())
    {
        return $this->getMockBuilder("\\Doctrine\\DBAL\\Connection")
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a value object mock.
     *
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getLoaderEntityMock(array $methods = array())
    {
        return $this
            ->getMockBuilder("\\Liip\\DataAggregatorBundle\\Loaders\\Entities\\LoaderEntityInterface")
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

    /**
     * Provides a configuration array.
     *
     * @return array
     */
    protected function getLoaderBossConfigurationFixture()
    {
        return array(
            'BOSS_ID'            => 'BossId',
            'STUFE'              => 'Stufe',
            'BW_ID'              => 'Bw',
            'BB_ID'              => 'Bb',
            'VS_ID'              => 'Vs',
            'TH_ID'              => 'Th',
            'FA_ID'              => 'Fa',
            'BEZ_D'              => 'TitleDe',
            'BEZ_F'              => 'TitleFr',
            'BEZ_I'              => 'TitleIt',
            'BEZ_E'              => 'TitleEn',
            'AKTUELL_STATUS'     => 'Status',
            'AKTUELL_GUELTIG_AB' => 'ActiveFromDate',
            'VERANTWORTLICH'     => 'Responsible',
            'SPARTE'             => 'Category',
            'FOLGE_STATUS'       => 'PostStatus',
            'FOLGE_GUELTIG_AB'   => 'PostActiveFromDate',
            'RPA_FLAG'           => 'Rpa',
        );
    }

    /**
     * Provides a configuration array.
     *
     * @return array
     */
    protected function getPersistorBossConfigurationFixture()
    {
        return array(
            'BossId'             => 'BossId',
            'Stufe'              => 'Stufe',
            'Bw'                 => 'Bw',
            'Bb'                 => 'Bb',
            'Vs'                 => 'Vs',
            'Th'                 => 'Th',
            'Fa'                 => 'Fa',
            'TitleDe'            => 'TitleDe',
            'TitleFr'            => 'TitleFr',
            'TitleIt'            => 'TitleIt',
            'TitleEn'            => 'TitleEn',
            'Status'             => 'Status',
            'ActiveFromDate'     => 'ActiveFromDate',
            'Responsible'        => 'Responsible',
            'Category'           => 'Category',
            'PostStatus'         => 'PostStatus',
            'PostActiveFromDate' => 'PostActiveFromDate',
            'Rpa'                => 'Rpa',
        );
    }

    /**
     * Provides a fixture of the PersistorBoss entity.
     *
     * @param array $configuration
     * @param array $data
     *
     * @return \Liip\DataAggregatorBundle\Entity\EntityBoss
     */
    protected function getLoaderEntityBossFixture(array $configuration, array $data)
    {
        $assertion = $this->getAssertionMock(array('notEmpty'));
        $assertion
            ->expects($this->any())
            ->method('notEmpty')
            ->will($this->returnValue(true));

        $entity = $this
            ->getProxyBuilder('\Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss')
            ->setProperties(array('hasProperties'))
            ->setConstructorArgs(array($assertion, $configuration))
            ->getProxy();
        $entity->hasProperties = true;

        foreach ($data as $key => $value) {
            if (!empty($configuration[$key])) {
                $memberName          = $configuration[$key];
                $entity->$memberName = $value;
            }
        }

        return $entity;
    }

    /**
     * Provides a test double for the assertion library
     *
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getAssertionMock(array $methods = array())
    {
        return $this->getMockBuilder('\Assert\Assertion')
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a Mock object of the Doctrine\ORM\EntityManger
     *
     * @param array $methods
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManagerMock(array $methods = array())
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a mock object of an entity repository
     *
     * @param array $methods
     *
     * @return \stdClass
     */
    protected function getEntityRepositoryMock(array $methods = array())
    {
        return $this->getMockBuilder('\stdClass')
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * No operation function.
     *
     * Used to specifically exclude a method and it's supersedes from the coverage report.
     * Shall be used for integration and functional tests.
     *
     */
    protected function noop()
    {
        return;
    }

    /**
     * Provides a dataset which the request to the oracle database returns.
     *
     * @param string $file       Relative path the to fixture file. (base is the DataFixtures dir in the bundle root)
     * @param array  $fieldNames List of Names to be used as fieldnames.
     *
     * @return array
     */
    protected function getBossDatabaseResultFixture($file = 'CSV/bossstruktur.csv', array $fieldNames = array())
    {
        $fixture       = array();
        $recordCounter = 0;

        if (empty($fieldNames)) {
            $fieldNames = array(
                'BOSS_ID',
                'STUFE',
                'BW_ID',
                'BB_ID',
                'VS_ID',
                'TH_ID',
                'FA_ID',
                'BEZ_D',
                'BEZ_F',
                'BEZ_I',
                'BEZ_E',
                'AKTUELL_STATUS',
                'AKTUELL_GUELTIG_AB',
                'VERANTWORTLICH',
                'SPARTE',
                'FOLGE_STATUS',
                'FOLGE_GUELTIG_AB',
                'RPA_FLAG',
            );
        }

        $data = file(__DIR__ . '/../DataFixtures/'. $file);

        foreach ($data as $row) {
            $fieldCounter = 0;
            $record       = str_getcsv($row, ',', "'");

            foreach ($record as $field) {
                $fixture[$recordCounter][$fieldNames[$fieldCounter]] = $field;
                ++$fieldCounter;
            }

            ++$recordCounter;
        }

        return $fixture;
    }
}
