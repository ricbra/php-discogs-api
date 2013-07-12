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

use Discogs\CacherInterface;
use Discogs\ResponseTransformer\ResponseTransformerInterface;
use Discogs\ResponseTransformer\Model as ModelResponseTransformer;
use Discogs\ResponseTransformer\TransformException;
use Discogs\NoResultException;

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
     * @var ResponseTransformerInterface
     */
    protected $transformer;

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
            'q', 'type', 'title', 'release_title', 'credit', 'artist', 'anv', 'label', 'genre', 'style', 'country', 'year', 'format', 'catno', 'barcode', 'track', 'submitter', 'contributor'
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

        return $this->call('/database/search', $params);
    }

    /**
     * Fetch next resultset
     *
     * @param mixed $transformedResponse
     * @return bool|mixed
     */
    public function next($transformedResponse)
    {
        try {
            $next = $this->getResponseTransformer()->get('pagination/urls/next', $transformedResponse);

            return $this->call($next);
        } catch (TransformException $e) {
            return false;
        }
    }

    /**
     * Implements API arists method
     *
     * @param $artistId
     * @return mixed
     */
    public function getArtist($artistId)
    {
        return $this->call(sprintf('/artists/%d', $artistId));
    }

    /**
     * Implements API releases method
     *
     * @param $releaseId
     * @return mixed
     * @throws NoResultException
     */
    public function getRelease($releaseId)
    {
        return $this->call(sprintf('/releases/%d', $releaseId));
    }

    /**
     * Returns releases of the particular artist
     *
     * @param int $artistId
     * @return mixed
     */
    public function getReleases($artistId)
    {
        return $this->call(sprintf('/artists/%d/releases', $artistId));
    }

    /**
     * Implements API masters method
     *
     * @param $masterId
     * @return mixed
     * @throws NoResultException
     */
    public function getMaster($masterId)
    {
        return $this->call(sprintf('/masters/%d', $masterId));
    }

    /**
     * Implements API labels method
     *
     * @param $labelId
     * @return mixed
     * @throws NoResultException
     */
    public function getLabel($labelId)
    {
        return $this->call(sprintf('/labels/%d', $labelId));
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
     * @param ResponseTransformerInterface $transformer
     */
    public function setResponseTransformer(ResponseTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @return ResponseTransformerInterface
     */
    public function getResponseTransformer()
    {
        if (empty($this->transformer)) {
            $this->transformer = new ModelResponseTransformer();
        }

        return $this->transformer;
    }

    /**
     * Calls client and transforms response
     *
     * @param $path
     * @param array $parameters
     * @return mixed
     * @throws NoResultException
     */
    protected function call($path, array $parameters = array())
    {
        $isFromCache = false;

        if ($this->isCacheEnabled) {
            $cachePath = empty($parameters) ? $path : sprintf('%s?%s', $path, http_build_query($parameters));
            $json = $this->cacher->retrieve($cachePath);

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
            $this->cacher->persist($cachePath, $this->client->getRawResponse());
        }

        return $this->getResponseTransformer()->transform($rawData, $path);
    }
}
