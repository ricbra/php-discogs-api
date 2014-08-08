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

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

class ClientFactory
{
    public static function factory(array $config = [])
    {
        $defaultConfig = [
            'defaults' => [
                'headers' => ['User-Agent' => 'php-discogs-api/1.0.0 +https://github.com/ricbra/php-discogs-api'],
                'auth' => 'oauth'
            ],
        ];

        $client = new Client(array_merge_recursive($defaultConfig, $config));
        $service = include __DIR__ . '/../../resources/service.php';
        $description = new Description($service);

        return new GuzzleClient($client, $description);
    }
}
