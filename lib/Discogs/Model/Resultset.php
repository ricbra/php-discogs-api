<?php

namespace Discogs\Model;

class Resultset
{
    private $pagination;
    private $results;

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
}