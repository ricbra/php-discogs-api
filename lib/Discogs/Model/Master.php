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

class Master extends AbstractModel
{
    protected $styles;
    protected $genres;
    protected $title;
    protected $mainRelease;
    protected $mainReleaseUrl;
    protected $year;
    protected $uri;
    protected $versionsUrl;
    protected $artists;
    protected $images;
    protected $resourceUrl;
    protected $tracklist;
    protected $id;
    protected $dataQuality;
    protected $videos;

    public function setArtists($artists)
    {
        $this->artists = $artists;
    }

    public function getArtists()
    {
        return $this->artists;
    }

    public function setDataQuality($dataQuality)
    {
        $this->dataQuality = $dataQuality;
    }

    public function getDataQuality()
    {
        return $this->dataQuality;
    }

    public function setGenres($genres)
    {
        $this->genres = $genres;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setMainRelease($mainRelease)
    {
        $this->mainRelease = $mainRelease;
    }

    public function getMainRelease()
    {
        return $this->mainRelease;
    }

    public function setMainReleaseUrl($mainReleaseUrl)
    {
        $this->mainReleaseUrl = $mainReleaseUrl;
    }

    public function getMainReleaseUrl()
    {
        return $this->mainReleaseUrl;
    }

    public function setResourceUrl($resourceUrl)
    {
        $this->resourceUrl = $resourceUrl;
    }

    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    public function setStyles($styles)
    {
        $this->styles = $styles;
    }

    public function getStyles()
    {
        return $this->styles;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTracklist($tracklist)
    {
        $this->tracklist = $tracklist;
    }

    public function getTracklist()
    {
        return $this->tracklist;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setVersionsUrl($versionsUrl)
    {
        $this->versionsUrl = $versionsUrl;
    }

    public function getVersionsUrl()
    {
        return $this->versionsUrl;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    public function getVideos()
    {
        return $this->videos;
    }
}
