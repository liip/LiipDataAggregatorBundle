<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

use Assert\Assertion;

class LoaderEntityBoss implements LoaderEntityInterface
{

    /**
     * Instance of Assertion library.
     * @var \Assert\Assertion
     */
    protected $assertion;

    /**
     * Contains configuration options.
     * @var array
     */
    protected $configuration = array();

    /**
     * Indicates if the current entity has any custom properties.
     * @var bool
     */
    protected $hasProperties = false;

    /**
     * @param \Assert\Assertion $assertion
     * @param array $config
     */
    public function __construct(Assertion $assertion, array $config)
    {
        $this->assertion = $assertion;
        $this->setConfiguration($config);
    }

    /**
     * Verifies that the config is not empty
     *
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->assertion->notEmpty($config);
        $this->configuration = $config;
    }

    /**
     * Initializes the current class.
     *
     * @param array $data
     *
     * @return $this
     */
    public function init(array $data)
    {
        $propertyCounter = 0;

        $this->reset();
        $this->assertion->notEmpty($data);

        foreach ($data as $property => $value) {
            if (!empty($this->configuration[$property])) {
                $member = $this->configuration[$property];
                $this->$member = $value;
                ++$propertyCounter;
            }
        }

        $this->hasProperties = $propertyCounter > 0;

        return $this;
    }

    /**
     * Sets an instance of this class to it's »uninitialized« state.
     * This will remove every property defined by the currently set configuration
     * but will preserve the configuration set by the constructor.
     */
    public function reset()
    {
        foreach ($this->configuration as $key => $property) {
            unset($this->$property);
        }

        $this->hasProperties = false;
    }

    /**
     * Indicates if the current entity has members or not.
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->hasProperties;
    }
}
