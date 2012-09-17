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

class Label extends AbstractModel
{
    protected $profile;
    protected $releasesUrl;
    protected $name;
    protected $contactInfo;
    protected $uri;
    protected $sublabels;
    protected $urls;
    protected $images;
    protected $resourceUrl;
    protected $id;
    protected $dataQuality;

    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
    }

    public function getContactInfo()
    {
        return $this->contactInfo;
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

    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    public function getProfile()
    {
        return $this->profile;
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

    public function setSublabels($sublabels)
    {
        $this->sublabels = $sublabels;
    }

    public function getSublabels()
    {
        return $this->sublabels;
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
