<?php
/*
 * This file is part of the php-discogs-api.
 *
 * (c) Richard van den Brand <richard@vandenbrand.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chrobane\Discogs;

use BenTools\GuzzleHttp\Middleware\Storage\Adapter\ArrayAdapter;
use BenTools\GuzzleHttp\Middleware\Storage\ThrottleStorageInterface;
use BenTools\GuzzleHttp\Middleware\ThrottleConfiguration;
use BenTools\GuzzleHttp\Middleware\ThrottleMiddleware;
use BenTools\Psr7\RequestMatcherInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class ClientFactory
{
    static ThrottleStorageInterface $storage;

    public static function factory(array $config = [])
    {
        $defaultConfig = [
            'headers' => ['User-Agent' => 'php-discogs-api/1.0.0 +https://github.com/ricbra/php-discogs-api'],
            'auth' => 'oauth',
        ];

        $handler = $config['handler'] ?? HandlerStack::create();
        $storage = $config['storage'] ?? static::$storage ?? new ArrayAdapter();

        static::$storage = $storage;

        if (isset($config['throttle_max_requests']) && isset($config['throttle_duration'])) {
            $middleware = new ThrottleMiddleware($storage);
            $middleware->registerConfiguration(
                new ThrottleConfiguration(
                    new class implements RequestMatcherInterface {
                        public function matchRequest(RequestInterface $request)
                        {
                            return true;
                        }
                    },
                    (int)$config['throttle_max_requests'],
                    (int)$config['throttle_duration'],
                    'discogs_api'
                )
            );

            $handler->push($middleware, 'throttle');
        }

        $service = include __DIR__ . '/../../../resources/service.php';
        $description = new Description($service);

        $client = new Client(self::mergeRecursive($defaultConfig, $config));

        $guzzle_client = new GuzzleClient(
            $client,
            $description
        );

        return $guzzle_client;
    }

    public static function &mergeRecursive(array &$array1, &$array2 = null)
    {
        $merged = $array1;

        if (is_array($array2)) {
            foreach ($array2 as $key => $val) {
                if (is_array($array2[$key])) {
                    $merged[$key] = isset($merged[$key]) && is_array($merged[$key]) ? self::mergeRecursive($merged[$key], $array2[$key]) : $array2[$key];
                } else {
                    $merged[$key] = $val;
                }
            }
        }

        return $merged;
    }
}
