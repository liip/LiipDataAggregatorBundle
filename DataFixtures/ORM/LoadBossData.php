<?php
namespace Liip\DataAggregatorBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Liip\DataAggregatorBundle\Entity\EntityBoss;

class LoadBossData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadAndProcessFixturesFromFile('bossstruktur.csv', $manager);
    }

    protected function getDataPath()
    {
        return realpath(__DIR__ . "/../CSV/");
    }

    /**
     * Removes single apostrophe characters from given string.
     * @param $str
     * @return mixed
     */
    protected function removeApostrophe($str)
    {
        return str_replace("'", "", $str);
    }
    /**
     * Return the fixtures for the current model.
     *
     * @return Array
     */
    protected function loadAndProcessFixturesFromFile($name, $manager)
    {
        $path = $this->getDataPath() . '/' . $name;
        $handle = @fopen($path, "r");
        if ($handle) {
            while (($row = fgets($handle, 4096)) !== false) {
                list($bossnr, $bw, $bb, $vs, $th, $fa, $titlede, $titlefr, $titleit, $titleen, $stufe, $status, $sparte, $verantwortlich, $rpa, $aktuelldate, $folgestatus, $folgedatum) = explode(",", $row);
                $boss = new EntityBoss();
                $boss->setBossId($this->removeApostrophe($bossnr));
                $boss->setStufe($this->removeApostrophe($stufe));
                $boss->setBw($this->removeApostrophe($bw));
                $boss->setBb($this->removeApostrophe($bb));
                $boss->setVs($this->removeApostrophe($vs));
                $boss->setTh($this->removeApostrophe($th));
                $boss->setFa($this->removeApostrophe($fa));
                $boss->setTitleDe($this->removeApostrophe($titlede));
                $boss->setTitleFr($this->removeApostrophe($titlefr));
                $boss->setTitleIt($this->removeApostrophe($titleit));
                $boss->setTitleEn($this->removeApostrophe($titleen));
                $boss->setStatus($this->removeApostrophe($status));
                $boss->setCategory($this->removeApostrophe($sparte));
                $boss->setResponsible($this->removeApostrophe($verantwortlich));
                $boss->setRpa($this->removeApostrophe($rpa));
                $boss->setActiveFromDate(new \DateTime($this->removeApostrophe($aktuelldate)));
                $boss->setPostStatus($this->removeApostrophe($folgestatus));
                $boss->setPostActiveFromDate(new \DateTime($this->removeApostrophe($folgedatum)));
                $manager->persist($boss);
            }
            if (!feof($handle)) {
                echo "Fehler: unerwarteter fgets() Fehlschlag\n";
            }
            fclose($handle);
        }
        $manager->flush();
    }

    protected function getPersistorBossModel($data)
    {
        $boss = new EntityBoss();
        return $boss;
    }

}
