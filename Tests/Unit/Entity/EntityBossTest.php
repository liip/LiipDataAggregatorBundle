<?php
namespace Liip\DataAggregatorBundle\Tests\Unit\Entity;

use DateTime;
use Liip\DataAggregatorBundle\Tests\DataAggregatorBundleTestCase;
use Liip\DataAggregatorBundle\Entity\EntityBoss;

class EntityBossTest extends DataAggregatorBundleTestCase
{
    public static function convertToDateExpectingExceptionDataprovider()
    {
        return array(
            "null value" => array(null),
            "empty string" => array(''),
            "array as value" => array(array()),
            "object as value" => array(new \stdClass),
            "integer value" => array(42)
        );
    }

    /**
     * @dataProvider convertDateDataprovider
     * @covers \Liip\DataAggregatorBundle\Entity\EntityBoss::convertToDate
     */
    public function testConvertToDate($expected,  $string)
    {
        $entity = new EntityBoss();
        $entity->setActiveFromDate($string);

        $this->assertAttributeEquals($expected, 'active_from_date', $entity);
    }

    public static function convertDateDataprovider()
    {
        return array(
            'normal date, no time' => array(new \DateTime('25-Aug-11'), '25-Aug-11'),
            'null' => array(null, null),
            'invalid date' => array(null, 'invalid date'),
            'empty string' => array(null, ''),
            'array as value' => array(null, array()),
            'object as value' => array(null, new \stdClass),
            'integer value' => array(null, 42),
        );
    }

    /**
     * @dataProvider twoCharPrefixedWithZeroDataprovider
     * @covers \Liip\DataAggregatorBundle\Entity\EntityBoss::makeTwoCharsLongPrefixWithZero
     */
    public function testMakeTwoCharsLongPrefixWithZero($expected, $bw)
    {
        $entity = new EntityBoss();
        $entity->setBw($bw);

        $this->assertAttributeEquals($expected, 'bw', $entity);
    }

    public static function twoCharPrefixedWithZeroDataprovider()
    {
        return array(
            'argument one char long' => array('0b', 'b'),
            'argument two char long' => array('be', 'be'),
        );
    }

    /**
     * @dataProvider fourCharPrefixedWithZeroDataprovider
     * @covers \Liip\DataAggregatorBundle\Entity\EntityBoss::makeFourCharsLongPrefixWithZero
     */
    public function testMakeFourCharsLongPrefixWithZero($expected, $string)
    {
        $entity = new EntityBoss();
        $entity->setVs($string);

        $this->assertAttributeEquals($expected, 'vs', $entity);
    }

    public static function fourCharPrefixedWithZeroDataprovider()
    {
        return array(
            'argument one char long' => array('000b', 'b'),
            'argument two char long' => array('00be', 'be'),
            'argument three char long' => array('0ber', 'ber'),
            'argument four char long' => array('bert', 'bert'),
        );
    }

    /**
     * @dataProvider twelveCharPrefixedWithZeroDataprovider
     * @covers \Liip\DataAggregatorBundle\Entity\EntityBoss::makeTwelveCharsLongPrefixWithZero
     */
    public function testMakeTwelveCharsLongPrefixWithZero($expected, $string)
    {
        $entity = new EntityBoss();
        $entity->setBossId($string);

        $this->assertAttributeEquals($expected, 'boss_id', $entity);
    }

    public static function twelveCharPrefixedWithZeroDataprovider()
    {
        return array(
            'argument one char long' => array('00000000000b', 'b'),
            'argument two char long' => array('0000000000be', 'be'),
            'argument three char long' => array('000000000ber', 'ber'),
            'argument four char long' => array('00000000bert', 'bert'),
            'argument five char long' => array('0000000berth', 'berth'),
            'argument six char long' => array('000000bertho', 'bertho'),
            'argument seven char long' => array('00000berthol', 'berthol'),
            'argument eight char long' => array('0000berthold', 'berthold'),
            'argument nine char long' => array('000bertholde', 'bertholde'),
            'argument ten char long' => array('00bertholder', 'bertholder'),
            'argument eleven char long' => array('0bertholderi', 'bertholderi'),
            'argument twelve char long' => array('bertholderis', 'bertholderis'),
        );
    }
}
