<?php
namespace Liip\DataAggregatorBundle\Persistors;

use Doctrine\ORM\TransactionRequiredException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManager;
use Liip\DataAggregator\Persistors\PersistorException;
use Liip\DataAggregator\Persistors\PersistorInterface;
use Liip\DataAggregatorBundle\Entity\EntityBoss AS PersistorBossEntity;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityInterface;

class PersistorBoss implements PersistorInterface
{
    /**
     * Contains the instance of a database abstraction.
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Set of configuration values.
     * @var array
     */
    protected $configuration = array();

    /**
     * Switch to determine if the current entity was found in the repository or newly created.
     * @var bool
     */
    protected $isNewEntity = false;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param array $configuration
     */
    public function __construct(EntityManager $entityManager, array $configuration)
    {
        $this->em = $entityManager;
        $this->configuration = $configuration;
    }

    /**
     * Processes the given data.
     *
     * @param array $data
     */
    public function persist(array $data)
    {
        try {
            foreach ($data as $item) {
                $entity = $this->getEntity($item->BossId);
                $this->persistDataInEntity($item, $entity);
            }

        } catch (PersistorException $e) {
            //todo log here
        } catch (TransactionRequiredException $e) {
            // todo log here
        } catch (ORMException $e) {
            // todo log here
        } catch (OptimisticLockException $e) {
            // todo log here
        }

        $this->em->flush();
    }

    /**
     * Provides an instance of the PersistorBoss doctrine entity.
     *
     * If the entity could not be found in the repository an new instance will be
     * instantiated.
     *
     * @param string $entityId
     *
     * @return \Liip\DataAggregatorBundle\Entity\EntityBoss
     */
    protected function getEntity($entityId)
    {
        $boss = $this->em
            ->getRepository('DataAggregatorBundle:EntityBoss')
            ->findOneBy(array('boss_id' => $entityId));

        if (empty($boss)) {
            $this->isNewEntity = true;
            $boss = new PersistorBossEntity();
        }

        return $boss;
    }

    /**
     * Initiates the entity and triggers the storage process.
     *
     * @param \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityInterface $entityData
     * @param \Liip\DataAggregatorBundle\Entity\EntityBoss|\stdClass $entity
     *
     * @throws \Liip\DataAggregator\Persistors\PersistorException
     */
    protected function persistDataInEntity(LoaderEntityInterface $entityData, PersistorBossEntity $entity)
    {
        if (!$entityData->isEmpty()) {

            $validKeys = array_values($this->configuration);

            foreach ($entityData as $key => $value) {
                if (in_array($key, $validKeys)) {
                    $method = 'set' . $key;
                    $entity->$method($value);
                }
            }

            if ($this->isNewEntity) {
                $this->em->persist($entity);
            }
        } else {
            throw new PersistorException('Nothing to persist.', PersistorException::NO_ENTITY_DATA_TO_PERSIST);
        }
    }
}
