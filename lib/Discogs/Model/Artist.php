<?php
/**
 * @author Richard van den Brand <richard@netvlies.nl>
 * @copyright Netvlies Internetdiensten
 * @package ${Package}
 */
 
namespace Discogs\Model;

class Artist
{
    private $id;

    private $name;

    private $resourceUrl;

    private $releasesUrl;

    private $uri;

    private $realname;

    private $profile;

    private $dataQuality;

    private $namevariatons;

    private $aliases;

    private $urls;

    private $images;

    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function setDataQuality($dataQuality)
    {
        $this->dataQuality = $dataQuality;
    }

    public function getDataQuality()
    {
        return $this->dataQuality;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setNamevariatons($namevariatons)
    {
        $this->namevariatons = $namevariatons;
    }

    public function getNamevariatons()
    {
        return $this->namevariatons;
    }

    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setRealname($realname)
    {
        $this->realname = $realname;
    }

    public function getRealname()
    {
        return $this->realname;
    }

    public function setReleasesUrl($releasesUrl)
    {
        $this->releasesUrl = $releasesUrl;
    }

    public function getReleasesUrl()
    {
        return $this->releasesUrl;
    }

    public function setResourceUrl($resourceUrl)
    {
        $this->resourceUrl = $resourceUrl;
    }

    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    public function getUrls()
    {
        return $this->urls;
    }
}