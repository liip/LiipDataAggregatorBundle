<?php
namespace MigrosApi\DataAggregatorBundle\Command;

use Assert\Assertion;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Liip\DataAggregatorBundle\DataAggregator;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss;
use Liip\DataAggregatorBundle\Loaders\LoaderBoss;
use Liip\DataAggregatorBundle\Persistors\PersistorBoss;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataAggregatorCommand extends ContainerAwareCommand
{
    /**
     * @var \Liip\DataAggregatorBundle\Loaders\LoaderBoss
     */
    protected $loader;
    /**
     * @var \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss
     */
    protected $loaderEntityBoss;
    /**
     * @var \Liip\DataAggregatorBundle\Persistors\PersistorBoss
     */
    protected $persistor;

    /**
     * Sets up the command to be used from console.
     */
    protected function configure()
    {
        $this
            ->setName('dataaggregator:import')
            ->setDescription('Import from all datasources');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Setting PHP memory_limit to 2000M\n");
        ini_set('memory_limit', '2000M');

        $output->write("Fetching BOSS data from oracle database ... ");

        // get DBAL and entity manager
        $dbal = $this->getContainer()->get("doctrine")->getConnection('boss');
        $em = $this->getContainer()->get("doctrine")->getManager();

        // get BOSS config
        $config = $this->getContainer()->getParameter('data_aggregator.boss');

        // get data aggregator
        $da = new DataAggregator();
        $da->attachLoader($this->getBossLoader($config['loader'], new Assertion(), $dbal));
        $da->attachPersistor($this->getBossPersistor($config['persistor'], $em));
        $da->run();

        $output->write("done!\n");
    }

    /**
     * @param array $config
     * @param Assertion $assertion
     * @param Connection $dbal
     *
     * @return LoaderBoss
     */
    protected function getBossLoader(array $config, Assertion $assertion, Connection $dbal)
    {
        if (empty($this->loader)) {

            $this->loader = new LoaderBoss($dbal, $this->getLoaderEntityBoss($assertion, $config));
        }

        return $this->loader;
    }

    /**
     * @param $assertion
     * @param $config
     *
     * @return LoaderEntityBoss
     */
    protected function getLoaderEntityBoss($assertion, $config)
    {
        if (empty($this->loaderEntityBoss)) {

            $this->loaderEntityBoss = new LoaderEntityBoss($assertion, $config);
        }

        return $this->loaderEntityBoss;
    }

    /**
     * @param array $config
     * @param EntityManager $em
     *
     * @return PersistorBoss
     */
    protected function getBossPersistor(array $config, EntityManager $em)
    {
        if (empty($this->persistor)) {

            $this->persistor = new PersistorBoss($em, $config);
        }

        return $this->persistor;
    }
}
