<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

interface LoaderEntityInterface
{
    /**
     * Initializes the current class.
     *
     * @param array $data
     */
    public function init(array $data);

    /**
     * Determines if the current entity has members.
     *
     * @return boolean
     */
    public function isEmpty();
}
