<?php
/*
* This file is part of the DiscogsAPI PHP SDK.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Discogs\Model;

class Resultset implements \Countable, \IteratorAggregate
{
    protected $pagination;
    protected $results;

    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination()
    {
        return $this->pagination;
    }

    public function setResults($results)
    {
        $this->results = $results;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->getPagination()->getItems();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->results);
    }
}
