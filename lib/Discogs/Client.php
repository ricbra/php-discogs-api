<?php

namespace Discogs;

use Buzz\Browser;

class Client
{
    private $host;
    private $browser;
    private $identifier;

    public function __construct(Browser $browser, $host = 'api.discogs.com')
    {
        $this->browser          = $browser;
        $this->host             = $host;
        $this->identifier       = 'DiscogsApi/0.1 +https://github.com/ricbra/DiscogsApi';
    }

    public function call($path, array $parameters = array())
    {
        $url = sprintf('http://%s/%s', $this->host, $path);
        if ($parameters) {
            $url .= '?'.http_build_query($parameters);
        }
        $headers = array(
            sprintf('User-Agent: %s', $this->identifier)
        );
        try {
            $response = $this->browser->get($url, $headers);
        } catch (\Exception $e) {
            throw new ConnectionException('Could not connect to Discogs', null, $e);
        }
        $rawData = json_decode($response->getContent());
        if (! $rawData instanceof \stdClass) {
            throw new InvalidResponseException('Unknow data received from server');
        }
        if (! isset($rawData->resp)) {
            throw new InvalidResponseException('Invalid data received from server (missing "resp")');
        }

        return $rawData->resp;
    }
}