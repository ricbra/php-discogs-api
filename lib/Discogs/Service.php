<?php
/*
* This file is part of the DiscogsAPI PHP SDK.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Discogs;

use Discogs\Model\Resultset;
use Discogs\CacherInterface;

class Service
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $itemsPerPage;

    /**
     * Whether to enable request throttling (1 request per second)
     * @var bool
     */
    protected $isEnableThrottle;

    /**
     * Microtime of last API request
     * @var int
     */
    protected static $lastApiRequest = 0;

    /**
     * @var CacherInterface
     */
    protected $cacher;

    /**
     * @var bool
     */
    protected $isCacheEnabled = false;

    /**
     * @param Client          $client
     * @param int             $itemsPerPage
     * @param bool            $isEnableThrottle Whether to enable request throttling (1 request per second)
     */
    public function __construct(Client $client = null, $itemsPerPage = 50, $isEnableThrottle = true)
    {
        $this->client       = $client ?: new Client();
        $this->itemsPerPage = $itemsPerPage;
        $this->isEnableThrottle = $isEnableThrottle;
    }

    /**
     * Implements API search method
     *
     * @param array $options
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function search(array $options = array())
    {
        $legal = array(
            'q', 'artist', 'release_title', 'label', 'title', 'catno', 'barcode', 'year', 'type'
        );
        $params = array();
        foreach ($options as $key => $value) {
            if (! in_array($key, $legal)) {
                throw new InvalidArgumentException(sprintf('Invalid options given: "%s"', $key));
            }
            $params[$key] = $value;
        }
        $params['per_page'] = $this->itemsPerPage;

        if (isset($params['type']) && ! in_array($params['type'], array('release', 'master', 'artist', 'label'))) {
            throw new InvalidArgumentException(sprintf('Invalid type given: "%s"', $params['type']));
        }

        return $this->call('/database/search', 'resultset', $params);
    }

    /**
     * Fetch next resultset
     *
     * @param Model\Resultset $resultset
     * @return bool|mixed
     */
    public function next(Resultset $resultset)
    {
        $urls = $resultset->getPagination()->getUrls();

        if ($urls->getNext()) {
            $a      = explode('?', $urls->getNext());
            $path   = '/database/search?' . end($a);

            return $this->call($path, 'resultset');
        }

        return false;
    }

    /**
     * Implements API arists method
     *
     * @param $artistId
     * @return mixed
     */
    public function getArtist($artistId)
    {
        return $this->call(sprintf('/artists/%d', $artistId), 'artist');
    }

    /**
     * Implements API releases method
     *
     * @param $releaseId
     * @return Release
     * @throws NoResultFoundException
     */
    public function getRelease($releaseId)
    {
        return $this->call(sprintf('/releases/%d', $releaseId), 'release');
    }

    /**
     * Implements API masters method
     *
     * @param $masterId
     * @return mixed
     * @throws NoResultFoundException
     */
    public function getMaster($masterId)
    {
        return $this->call(sprintf('/masters/%d', $masterId), 'master');
    }

    /**
     * Implements API labels method
     *
     * @param $labelId
     * @return mixed
     * @throws NoResultFoundException
     */
    public function getLabel($labelId)
    {
        return $this->call(sprintf('/labels/%d', $labelId), 'label');
    }

    /**
     * @param CacherInterface $cacher
     */
    public function setCacher(CacherInterface $cacher)
    {
        $this->cacher = $cacher;
        $this->cacher->isOperational() ? $this->enableCache() : $this->disableCache();
    }

    public function enableCache()
    {
        $this->isCacheEnabled = true;
    }

    public function disableCache()
    {
        $this->isCacheEnabled = false;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->isCacheEnabled;
    }

    /**
     * Calls client and transforms response
     *
     * @param $path
     * @param $responseKey
     * @param array $parameters
     * @return mixed
     * @throws NoResultException
     */
    protected function call($path, $responseKey, array $parameters = array())
    {
        $isFromCache = false;

        if ($this->isCacheEnabled) {
            $json = $this->cacher->retrieve($path);

            if ($json) {
                $isFromCache = true;
            }
        }

        if ($isFromCache) {
            $rawData = $this->client->convertResponse($json);
        } else {
            if ($this->isEnableThrottle) {
                $timestamp = round(microtime(true) * 1000);
                $wait = static::$lastApiRequest + 1000000 - $timestamp;

                if ($wait > 0) {
                    usleep($wait);
                }

                static::$lastApiRequest = round(microtime(true) * 1000);
            }

            $rawData = $this->client->call($path, $parameters);
        }

        if (isset($rawData->message)) {
            throw new NoResultException($rawData->message);
        }

        if ($this->isCacheEnabled && !$isFromCache) {
            $this->cacher->persist($path, $this->client->getRawResponse());
        }

        $transformer = new ResponseTransformer();

        return $transformer->transform($responseKey, $rawData);
    }
}
