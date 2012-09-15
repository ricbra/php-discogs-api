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

use Discogs\Model\AbstractModel;

class Urls extends AbstractModel
{
    protected $last;
    protected $next;
    protected $previous;
    protected $first;


    public function setLast($last)
    {
        $this->last = $last;
    }

    public function getLast()
    {
        return $this->last;
    }

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function setFirst($first)
    {
        $this->first = $first;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function setPrevious($previous)
    {
        $this->previous = $previous;
    }

    public function getPrevious()
    {
        return $this->previous;
    }
}
