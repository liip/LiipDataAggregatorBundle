<?php
namespace Liip\DataAggregatorBundle\Loaders\Entities;

use Doctrine\Common\Collections\ArrayCollection;

class LoaderEntityCollection extends ArrayCollection
{
    /**
     * Merges the given collections into a new of the same class where this method was called from.
     *
     * @param ArrayCollection $collection
     *
     * @throws \InvalidArgumentException
     * @return ArrayCollection
     */
    public function merge(ArrayCollection $collection) {
        $args = func_get_args();
        $c = array_shift($args);
        $i = 0;

        foreach ($args as $arg) {
            if (!$this->isCollection($arg)) {
                throw new \InvalidArgumentException(
                    sprintf(LoaderEntityException::INVALID_COLLECTION_TYPE_MESSAGE, $i),
                    LoaderEntityException::INVALID_COLLECTION_TYPE
                );
            }
            $c = $this->doMerge($c, $arg);
            ++$i;
        }
        return $c;
    }

    /**
     * Merges the given collections into one.
     *
     * @param ArrayCollection $to
     * @param ArrayCollection $from
     * @return ArrayCollection
     */
    protected function doMerge(ArrayCollection $to, ArrayCollection $from) {
        foreach ($from as $key => $item) {
            if (is_numeric($key)) {
                $to[] = $item;
                continue;
            }
            $to[$key] = $item;
        }
        return $to;
    }

    /**
     * Verifies if the given object is of type ArrayCollection
     *
     * @param object $collection
     * @return boolean True, if it is of the expected type, else false.
     */
    public function isCollection($collection) {
        return $collection instanceof ArrayCollection;
    }
}
