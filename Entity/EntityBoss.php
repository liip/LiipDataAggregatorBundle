<?php

namespace Liip\DataAggregatorBundle\Entity;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Liip\DataAggregatorBundle\Entity\EntityBoss
 */
class EntityBoss
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $boss_id
     */
    private $boss_id;

    /**
     * @var string $stufe
     */
    private $stufe;

    /**
     * @var string $bw
     */
    private $bw;

    /**
     * @var string $bb
     */
    private $bb;

    /**
     * @var string $vs
     */
    private $vs;

    /**
     * @var string $th
     */
    private $th;

    /**
     * @var string $fa
     */
    private $fa;

    /**
     * @var string $title_de
     */
    private $title_de;

    /**
     * @var string $title_fr
     */
    private $title_fr;

    /**
     * @var string $title_it
     */
    private $title_it;

    /**
     * @var string $title_en
     */
    private $title_en;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var \DateTime $active_from_date
     */
    private $active_from_date;

    /**
     * @var string $responsible
     */
    private $responsible;

    /**
     * @var string $category
     */
    private $category;

    /**
     * @var integer $post_status
     */
    private $post_status;

    /**
     * @var \DateTime $post_active_from_date
     */
    private $post_active_from_date;

    /**
     * @var string $rpa
     */
    private $rpa;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set boss_id
     *
     * @param integer $bossId
     * @return EntityBoss
     */
    public function setBossId($bossId)
    {
        $this->boss_id = $this->makeTwelveCharsLongPrefixWithZero($bossId);

        return $this;
    }

    /**
     * Get boss_id
     *
     * @return integer
     */
    public function getBossId()
    {
        return $this->boss_id;
    }

    /**
     * Set stufe
     *
     * @param string $stufe
     * @return EntityBoss
     */
    public function setStufe($stufe)
    {
        $this->stufe = $stufe;

        return $this;
    }

    /**
     * Get stufe
     *
     * @return string
     */
    public function getStufe()
    {
        return $this->stufe;
    }

    /**
     * Set bw
     *
     * @param string $bw
     * @return EntityBoss
     */
    public function setBw($bw)
    {
        $this->bw = $this->makeTwoCharsLongPrefixWithZero($bw);

        return $this;
    }

    /**
     * Get bw
     *
     * @return string
     */
    public function getBw()
    {
        return $this->bw;
    }

    /**
     * Set bb
     *
     * @param string $bb
     * @return EntityBoss
     */
    public function setBb($bb)
    {
        $this->bb = $this->makeTwoCharsLongPrefixWithZero($bb);

        return $this;
    }

    /**
     * Get bb
     *
     * @return string
     */
    public function getBb()
    {
        return $this->bb;
    }

    /**
     * Set vs
     *
     * @param string $vs
     * @return EntityBoss
     */
    public function setVs($vs)
    {
        $this->vs = $this->makeFourCharsLongPrefixWithZero($vs);

        return $this;
    }

    /**
     * Get vs
     *
     * @return string
     */
    public function getVs()
    {
        return $this->vs;
    }

    /**
     * Set th
     *
     * @param string $th
     * @return EntityBoss
     */
    public function setTh($th)
    {
        $this->th = $this->makeTwoCharsLongPrefixWithZero($th);

        return $this;
    }

    /**
     * Get th
     *
     * @return string
     */
    public function getTh()
    {
        return $this->th;
    }

    /**
     * Set fa
     *
     * @param string $fa
     * @return EntityBoss
     */
    public function setFa($fa)
    {
        $this->fa = $this->makeTwoCharsLongPrefixWithZero($fa);

        return $this;
    }

    /**
     * Get fa
     *
     * @return string
     */
    public function getFa()
    {
        return $this->fa;
    }

    /**
     * Set title_de
     *
     * @param string $titleDe
     * @return EntityBoss
     */
    public function setTitleDe($titleDe)
    {
        $this->title_de = $titleDe;

        return $this;
    }

    /**
     * Get title_de
     *
     * @return string
     */
    public function getTitleDe()
    {
        return $this->title_de;
    }

    /**
     * Set title_fr
     *
     * @param string $titleFr
     * @return EntityBoss
     */
    public function setTitleFr($titleFr)
    {
        $this->title_fr = $titleFr;

        return $this;
    }

    /**
     * Get title_fr
     *
     * @return string
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * Set title_it
     *
     * @param string $titleIt
     * @return EntityBoss
     */
    public function setTitleIt($titleIt)
    {
        $this->title_it = $titleIt;

        return $this;
    }

    /**
     * Get title_it
     *
     * @return string
     */
    public function getTitleIt()
    {
        return $this->title_it;
    }

    /**
     * Set title_en
     *
     * @param string $titleEn
     * @return EntityBoss
     */
    public function setTitleEn($titleEn)
    {
        $this->title_en = $titleEn;

        return $this;
    }

    /**
     * Get title_en
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->title_en;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return EntityBoss
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set active_from_date
     *
     * @param \DateTime $activeFromDate
     * @return EntityBoss
     */
    public function setActiveFromDate($activeFromDate)
    {
        $this->active_from_date = $this->convertToDate($activeFromDate);

        return $this;
    }

    /**
     * Get active_from_date
     *
     * @return \DateTime
     */
    public function getActiveFromDate()
    {
        return $this->active_from_date;
    }

    /**
     * Set responsible
     *
     * @param string $responsible
     * @return EntityBoss
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * Get responsible
     *
     * @return string
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return EntityBoss
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set post_status
     *
     * @param integer $postStatus
     * @return EntityBoss
     */
    public function setPostStatus($postStatus)
    {
        $this->post_status = $postStatus;

        return $this;
    }

    /**
     * Get post_status
     *
     * @return integer
     */
    public function getPostStatus()
    {
        return $this->post_status;
    }

    /**
     * Set post_active_from_date
     *
     * @param \DateTime $postActiveFromDate
     * @return EntityBoss
     */
    public function setPostActiveFromDate($postActiveFromDate)
    {
        $this->post_active_from_date = $this->convertToDate($postActiveFromDate);

        return $this;
    }

    /**
     * Get post_active_from_date
     *
     * @return \DateTime
     */
    public function getPostActiveFromDate()
    {
        return $this->post_active_from_date;
    }

    /**
     * Set rpa
     *
     * @param string $rpa
     * @return EntityBoss
     */
    public function setRpa($rpa)
    {
        $this->rpa = $rpa;

        return $this;
    }

    /**
     * Get rpa
     *
     * @return string
     */
    public function getRpa()
    {
        return $this->rpa;
    }

    /**
     * Ensures that the field value is always 2 chars long.
     *
     * In case it is shorter a zero will be used as prefix to fill the gap.
     *
     * @param $string
     *
     * @return string
     */
    protected function makeTwoCharsLongPrefixWithZero($string)
    {
        return sprintf('%02s', $string);
    }

    /**
     * Ensures that the field value is always 4 chars long.
     *
     * In case it is shorter a zero will be used as prefix to fill the gap.
     *
     * @param $string
     *
     * @return string
     */
    protected function makeFourCharsLongPrefixWithZero($string)
    {
        return sprintf('%04s', $string);
    }

    /**
     * Ensures that the field value is always 12 chars long.
     *
     * In case it is shorter a zero will be used as prefix to fill the gap.
     *
     * @param string $string
     *
     * @return string
     */
    protected function makeTwelveCharsLongPrefixWithZero($string)
    {
        return sprintf('%012s', $string);
    }

    /**
     * Converts the given string into a format the database should understand.
     *
     * @param string $string
     *
     * @return \DateTime|null
     */
    protected function convertToDate($string)
    {
        try {
            Assertion::string($string);
            Assertion::notEmpty($string);

            return new \DateTime($string);
        } catch (InvalidArgumentException $e) {
            //todo: log here

            return null;
        } catch (\Exception $e) {
            //todo: log here: invalid $string value

            return null;
        }
    }
}
