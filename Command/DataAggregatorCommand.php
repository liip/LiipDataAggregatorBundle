<?php
namespace Liip\DataAggregatorBundle\Command;

use Assert\Assertion;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Liip\DataAggregatorBundle\DataAggregator;
use Liip\DataAggregatorBundle\Loaders\LoaderBoss;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss;
use Liip\DataAggregatorBundle\Persistors\PersistorBoss;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataAggregatorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dataaggregator:import')
            ->setDescription('Import from all datasources')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo("Setting PHP memory_limit to 2000M\n");
        ini_set('memory_limit', '2000M');

        echo("Fetching BOSS data from oracle database ... ");

        // get DBAL and entity manager
        $dbal = $this->getContainer()->get("doctrine")->getConnection('boss');
        $em = $this->getContainer()->get("doctrine")->getManager();

        // get assertion instance
        $assertion = new Assertion();

        list ($loader, $persistor) = $this->getBossLoaderAndPersistor($assertion, $dbal, $em);

        // get data aggregator
        $da = new DataAggregator();
        $da->attachLoader($loader);
        $da->attachPersistor($persistor);
        $da->run();

        echo("done!\n");
    }

    protected function getBossLoaderAndPersistor(Assertion $assertion, Connection $dbal, EntityManager $em)
    {
        // get BOSS config
        $bossConfig = $this->getContainer()->getParameter('data_aggregator.boss');
        $loaderBossConfig = $bossConfig['loader'];
        $persistorBossConfig = $bossConfig['persistor'];

        // get entity
        $entity = new LoaderEntityBoss($assertion, $loaderBossConfig);

        // define loader
        $loader = new LoaderBoss($dbal, $entity);

        // define persistor
        $persistor = new PersistorBoss($em, $persistorBossConfig);

        return array($loader, $persistor);
    }
}
