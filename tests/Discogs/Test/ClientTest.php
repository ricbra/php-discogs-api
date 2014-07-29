<?php
/*
 * This file is part of the php-discogs-api.
 *
 * (c) Richard van den Brand <richard@vandenbrand.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Discogs\Test;

use Discogs\ClientFactory;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArtist()
    {
        $history = new History();
        $client = $this->createClient('get_artist', $history);
        $response = $client->getArtist([
            'id' => 45
        ]);
        $this->assertSame($response['id'], 45);
        $this->assertSame($response['name'], 'Aphex Twin');
        $this->assertSame($response['realname'], 'Richard David James');
        $this->assertInternalType('array', $response['images']);
        $this->assertCount(9, $response['images']);

        $this->assertSame('http://api.discogs.com/artists/45', $history->getLastRequest()->getUrl());
    }

    public function testGetArtistReleases()
    {
        $history = new History();
        $client = $this->createClient('get_artist_releases', $history);

        $response = $client->getArtistReleases([
            'id' => 45,
            'per_page' => 50,
            'page' => 1
        ]);
        $this->assertCount(50, $response['releases']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);

        $this->assertSame('http://api.discogs.com/artists/45/releases?per_page=50&page=1', $history->getLastRequest()->getUrl());
    }

    public function testSearch()
    {
        $history = new History();
        $client = $this->createClient('search', $history);

        $response = $client->search([
            'q' => 'prodigy',
            'type' => 'release',
            'title' => true
        ]);
        $this->assertCount(50, $response['results']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertSame('http://api.discogs.com/database/search?q=prodigy&type=release&title=1', $history->getLastRequest()->getUrl());
    }

    public function testGetRelease()
    {
        $history = new History();
        $client = $this->createClient('get_release', $history);
        $response = $client->getRelease([
            'id' => 1
        ]);

        $this->assertSame('Accepted', $response['status']);
        $this->assertArrayHasKey('videos', $response);
        $this->assertCount(6, $response['videos']);
        $this->assertSame('http://api.discogs.com/releases/1', $history->getLastRequest()->getUrl());
    }

    protected function createClient($mock, History $history)
    {
        $path = sprintf('%s/../../fixtures/%s', __DIR__, $mock);
        $client = ClientFactory::factory();
        $httpClient = $client->getHttpClient();
        $mock = new Mock([
            $path
        ]);
        $httpClient->getEmitter()->attach($mock);
        $httpClient->getEmitter()->attach($history);

        return $client;
    }
}
