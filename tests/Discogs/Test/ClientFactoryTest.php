<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Discogs\Test;

use Discogs\ClientFactory;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $client = ClientFactory::factory();
        $default = ['User-Agent' => 'php-discogs-api/1.0.0 +https://github.com/ricbra/php-discogs-api'];
        $this->assertSame($default, $client->getHttpClient()->getDefaultOption('headers'));
    }

    public function testFactoryWithCustomUserAgent()
    {
        $client = ClientFactory::factory([
            'defaults' => [
                'headers' => ['User-Agent' => 'test']
            ]
        ]);
        $default = ['User-Agent' => 'test'];
        $this->assertSame($default, $client->getHttpClient()->getDefaultOption('headers'));
    }


    public function testFactoryWithCustomDefaultNotInClassDefaults()
    {
        $client = ClientFactory::factory([
            'defaults' => [
                'headers' => ['User-Agent' => 'test'],
                'query' => [
                    'key' => 'my-key',
                    'secret' => 'my-secret',
                ],
            ]
        ]);
        $default_headers = ['User-Agent' => 'test'];
        $default_query = [
                'key' => 'my-key',
                'secret' => 'my-secret'
        ];
        $this->assertSame($default_headers, $client->getHttpClient()->getDefaultOption('headers'));
        $this->assertSame($default_query, $client->getHttpClient()->getDefaultOption('query'));
    }

}
