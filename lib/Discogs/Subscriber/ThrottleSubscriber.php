<?php

namespace Discogs\Subscriber;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ThrottleSubscriber
{
    private $throttle;
    private $max_retries;

    /**
     * @param int $throttle wait time between retries, in milliseconds
     */
    public function __construct($throttle = 1000, $max_retries = 5)
    {
        $this->throttle = (int) $throttle;
        $this->max_retries = (int) $max_retries;
    }

    public function decider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= $this->max_retries) return false;

            // Retry on connection exceptions
            if ($exception instanceof ConnectException) return true;

            if ($response) {
                if ($response->getStatusCode() == 429) return true;
                // Retry on server errors
                if ($response->getStatusCode() >= 500) return true;
            }

            return false;
        };
    }

    public function delay()
    {
        return function ($retries) {
            return $this->throttle * $retries;
        };
    }
}
