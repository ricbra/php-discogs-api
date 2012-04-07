<?php

namespace Discogs\Model;

class Master
{
    private $styles;
    private $genres;
    private $title;
    private $mainRelease;
    private $mainReleaseUrl;
    private $year;
    private $uri;
    private $versionsUrl;
    private $artists;
    private $images;
    private $resourceUrl;
    private $tracklist;
    private $id;
    private $dataQuality;
    private $join;
    private $anv;
    private $tracks;
    private $role;
    private $videos;

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

    public function setAnv($anv)
    {
        $this->anv = $anv;
    }

    public function getAnv()
    {
        return $this->anv;
    }

    public function setJoin($join)
    {
        $this->join = $join;
    }

    public function getJoin()
    {
        return $this->join;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
    }

    public function getTracks()
    {
        return $this->tracks;
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