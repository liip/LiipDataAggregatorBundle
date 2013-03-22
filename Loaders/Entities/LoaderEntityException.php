<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

class LoaderEntityException extends \Exception
{
    const EMPTY_CONFIGURATION = 1;
    const EMPTY_DATASET = 2;
    const INVALID_COLLECTION_TYPE = 3;

    const EMPTY_CONFIGURATION_MESSAGE = "Configuration must not be empty!";
    const EMPTY_DATASET_MESSAGE = "Configuration must not be empty!";
    const INVALID_COLLECTION_TYPE_MESSAGE = 'Given argument (%s) is not a valid collection';
}
