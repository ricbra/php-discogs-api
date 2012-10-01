<?php
/*
* This file is part of the php-discogs-api.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Discogs;

use Buzz\Browser;

class Client
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var \Buzz\Browser
     */
    protected $browser;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @param \Buzz\Browser $browser
     * @param string $host
     */
    public function __construct(Browser $browser = null, $host = 'api.discogs.com', $identifier = 'DiscogsApi/0.1 +https://github.com/ricbra/DiscogsApi')
    {
        $this->browser          = $browser?: new Browser();
        $this->host             = $host;
        $this->identifier       = $identifier;
    }

    /**
     * Performs a call to the API
     *
     * @param $path
     * @param array $parameters
     * @return mixed
     * @throws ConnectionException|InvalidResponseException
     */
    public function call($path, array $parameters = array())
    {
        $url     = $this->getUrl($path, $parameters);
        $headers = array(sprintf('User-Agent: %s', $this->identifier));

        try {
            $response = $this->browser->get($url, $headers);
        } catch (\Exception $e) {
            throw new ConnectionException('Could not connect to Discogs', null, $e);
        }

        // Save to a field to avoid unnecessary decoding later in Service::call() (if caching is enabled)
        $this->rawResponse = $response->getContent();
        $stdClass = $this->convertResponse($this->rawResponse);

        return $stdClass;
    }

    /**
     * @param $path
     * @param array $parameters
     * @return string
     */
    public function getUrl($path, array $parameters = array())
    {
        if (substr($path, 0, 7) == 'http://') {
            $url = $path;
        } else {
            $url = sprintf('http://%s%s', $this->host, $path);

            if ($parameters) {
                $url .= '?'.http_build_query($parameters);
            }
        }

        return $url;
    }

    /**
     * Ping API
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->call('/');
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param \Buzz\Browser $browser
     */
    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return \Buzz\Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Returns original JSON response
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Converts JSON response got from Discogs API into StdClass for further transformation into model
     *
     * @param string $rawResponse
     * @return \stdClass
     * @throws InvalidResponseException
     */
    public function convertResponse($rawResponse)
    {
        $stdClass = json_decode($rawResponse);

        if (! $stdClass instanceof \stdClass) {
            throw new InvalidResponseException('Unknown data received from server');
        }

        return $stdClass;
    }
}
