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

class Release extends AbstractModel
{
    protected $id;
    protected $title;
    protected $resourceUrl;
    protected $uri;
    protected $status;
    protected $dataQuality;
    protected $masterId;
    protected $masterUrl;
    protected $country;
    protected $year;
    protected $released;
    protected $releasesFormatted;
    protected $notes;
    protected $styles;
    protected $genres;
    protected $labels;
    protected $companies;
    protected $extraartists;
    protected $videos;
    protected $artists;
    protected $formats;
    protected $images;
    protected $tracklist;

    public function setArtists($artists)
    {
        $this->artists = $artists;
    }

    public function getArtists()
    {
        return $this->artists;
    }

    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

    public function getCompanies()
    {
        return $this->companies;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setDataQuality($dataQuality)
    {
        $this->dataQuality = $dataQuality;
    }

    public function getDataQuality()
    {
        return $this->dataQuality;
    }

    public function setExtraartists($extraartists)
    {
        $this->extraartists = $extraartists;
    }

    public function getExtraartists()
    {
        return $this->extraartists;
    }

    public function setFormats($formats)
    {
        $this->formats = $formats;
    }

    public function getFormats()
    {
        return $this->formats;
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

    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setMasterId($masterId)
    {
        $this->masterId = $masterId;
    }

    public function getMasterId()
    {
        return $this->masterId;
    }

    public function setMasterUrl($masterUrl)
    {
        $this->masterUrl = $masterUrl;
    }

    public function getMasterUrl()
    {
        return $this->masterUrl;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setReleased($released)
    {
        $this->released = $released;
    }

    public function getReleased()
    {
        return $this->released;
    }

    public function setReleasesFormatted($releasesFormatted)
    {
        $this->releasesFormatted = $releasesFormatted;
    }

    public function getReleasesFormatted()
    {
        return $this->releasesFormatted;
    }

    public function setResourceUrl($resourceUrl)
    {
        $this->resourceUrl = $resourceUrl;
    }

    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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

    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    public function getVideos()
    {
        return $this->videos;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }
}
