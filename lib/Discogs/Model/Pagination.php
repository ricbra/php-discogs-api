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

class Pagination implements \Countable
{
    protected $perPage;
    protected $items;
    protected $page;
    protected $urls;
    protected $pages;

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    public function getUrls()
    {
        return $this->urls;
    }

    public function count()
    {
        return (int) $this->pages;
    }
}
