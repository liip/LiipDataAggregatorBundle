<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

use Assert\Assertion;

class LoaderEntityFactory
{
    /**
     * @var \Assert\Assertion
     */
    protected $assertion;

    /**
     * @var array
     */
    protected $instances = array();


    /**
     * @param \Assert\Assertion $assertion
     */
    public function __construct(Assertion $assertion)
    {
        $this->assertion = $assertion;
    }

    /**
     * Provides an instance of the class identified by it's name.
     * Note:
     * The class to be instantiates has to be in the same namespace as this factory class.
     *
     * @param string $className
     * @param array $configuration
     * @param bool $forceNew
     *
     * @return \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityInterface
     */
    public function getInstanceOf($className, array $configuration, $forceNew = false)
    {
        if ($forceNew || empty($this->instances[$className])) {

            $className =  '\\'. __NAMESPACE__ . '\\' . $className;

            $this->assertion->implementsInterface(
                $className,
                '\\Liip\\DataAggregatorBundle\\Loaders\\Entities\\LoaderEntityInterface'
            );

            $this->instances[$className] = new $className($this->assertion, $configuration);
        }

        return $this->instances[$className];
    }
}
