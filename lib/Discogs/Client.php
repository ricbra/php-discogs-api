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
    private $host;

    /**
     * @var \Buzz\Browser
     */
    private $browser;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @param \Buzz\Browser $browser
     * @param string $host
     */
    public function __construct(Browser $browser = null, $host = 'api.discogs.com', $identifier = 'DiscogsApi/0.1 +https://github.com/ricbra/php-discogs-api')
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

        $rawData = json_decode($response->getContent());
        if (! $rawData instanceof \stdClass) {
            throw new InvalidResponseException('Unknow data received from server');
        }

        return $rawData;
    }

    /**
     * @param $path
     * @param array $parameters
     * @return string
     */
    public function getUrl($path, array $parameters = array())
    {
        $url = sprintf('http://%s%s', $this->host, $path);

        if ($parameters) {
            $url .= '?'.http_build_query($parameters);
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
}