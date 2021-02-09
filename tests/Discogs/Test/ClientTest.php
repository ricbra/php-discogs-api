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
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Middleware;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetArtist()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_artist', $history);
        $response = $client->getArtist([
            'id' => 45
        ]);
        $this->assertSame($response['id'], 45);
        $this->assertSame($response['name'], 'Aphex Twin');
        $this->assertSame($response['realname'], 'Richard David James');
        $this->assertIsArray($response['images']);
        $this->assertCount(9, $response['images']);

        $this->assertSame('https://api.discogs.com/artists/45', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetArtistReleases()
    {

        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_artist_releases', $history);

        $response = $client->getArtistReleases([
            'id' => 45,
            'per_page' => 50,
            'page' => 1
        ]);
        $this->assertCount(50, $response['releases']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);

        $this->assertSame('https://api.discogs.com/artists/45/releases?per_page=50&page=1', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testSearch()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('search', $history);

        $response = $client->search([
            'q' => 'prodigy',
            'type' => 'release',
            'title' => 'the fat of the land',
            'per_page' => 100,
            'page' => 3
        ]);
        $this->assertCount(50, $response['results']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertSame('https://api.discogs.com/database/search?q=prodigy&type=release&title=the%20fat%20of%20the%20land&per_page=100&page=3', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetRelease()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_release', $history);
        $response = $client->getRelease([
            'id' => 1,
            'curr_abbr' => 'USD'
        ]);

        $this->assertLessThanOrEqual(8.077169493076712, $response['lowest_price']);
        $this->assertSame('Accepted', $response['status']);
        $this->assertArrayHasKey('videos', $response);
        $this->assertCount(6, $response['videos']);
        $this->assertSame('https://api.discogs.com/releases/1?curr_abbr=USD', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetMaster()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_master', $history);

        $response = $client->getMaster([
            'id' => 33687
        ]);
        $this->assertSame('O Fortuna', $response['title']);
        $this->assertArrayHasKey('tracklist', $response);
        $this->assertCount(2, $response['tracklist']);
        $this->assertSame('https://api.discogs.com/masters/33687', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetMasterVersions()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_master_versions', $history);

        $response = $client->getMasterVersions([
            'id' => 33687,
            'per_page' => 4,
            'page' => 2
        ]);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('versions', $response);
        $this->assertCount(4, $response['versions']);
        $this->assertSame('https://api.discogs.com/masters/33687/versions?per_page=4&page=2', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetLabel()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_label', $history);
        $response = $client->getLabel([
            'id' => 1
        ]);
        $this->assertArrayHasKey('releases_url', $response);
        $this->assertSame('https://api.discogs.com/labels/1/releases', $response['releases_url']);
        $this->assertArrayHasKey('sublabels', $response);
        $this->assertCount(6, $response['sublabels']);
        $this->assertSame('https://api.discogs.com/labels/1', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetLabelReleases()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_label_releases', $history);
        $response = $client->getLabelReleases([
            'id' => 1,
            'per_page' => 2,
            'page' => 1
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('releases', $response);
        $this->assertCount(2, $response['releases']);
        $this->assertSame('https://api.discogs.com/labels/1/releases?per_page=2&page=1', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetOAuthIdentity()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_oauth_identity', $history);
        $response = $client->getOAuthIdentity();

        $this->assertSame($response['username'], 'R-Search');
        $this->assertSame($response['resource_url'], 'https://api.discogs.com/users/R-Search');
        $this->assertSame($response['consumer_name'], 'RicbraDiscogsBundle');
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetProfile()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_profile', $history);
        $response = $client->getProfile([
            'username' => 'maxperei'
        ]);

        $this->assertEquals(200, $container[0]['response']->getStatusCode());
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('avatar_url', $response);
        $this->assertArrayHasKey('home_page', $response);
        $this->assertArrayNotHasKey('email', $response);
        $this->assertSame($response['name'], '∴');
        $this->assertSame($response['avatar_url'], 'https://img.discogs.com/mDaw_OUjHspYLj77C_tcobr2eXc=/500x500/filters:strip_icc():format(jpeg):quality(40)/discogs-avatars/U-1861520-1498224434.jpeg.jpg');
        $this->assertSame($response['home_page'], 'http://maxperei.info');
        $this->assertSame('https://api.discogs.com/users/maxperei', strval($container[0]['request']->getUri()));
    }

    public function testGetInventory()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_inventory', $history);
        $response = $client->getInventory([
            'username'      => '360vinyl',
            'sort'          => 'price',
            'sort_order'    => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('listings', $response);
        $this->assertCount(50, $response['listings']);
        $this->assertSame('GET', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/users/360vinyl/inventory?sort=price&sort_order=asc', strval($container[0]['request']->getUri()));
    }

    public function testGetOrders()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_orders', $history);
        $response = $client->getOrders([
            'status'      => 'New Order',
            'sort'        => 'price',
            'sort_order'  => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('orders', $response);
        $this->assertCount(1, $response['orders']);
        $this->assertSame('GET', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders?status=New%20Order&sort=price&sort_order=asc', strval($container[0]['request']->getUri()));
    }

    public function testGetOrder()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_order', $history);
        $response = $client->getOrder([
            'order_id' => '1-1'
        ]);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('resource_url', $response);
        $this->assertArrayHasKey('messages_url', $response);
        $this->assertArrayHasKey('uri', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('next_status', $response);
        $this->assertArrayHasKey('fee', $response);
        $this->assertArrayHasKey('created', $response);
        $this->assertArrayHasKey('shipping', $response);
        $this->assertArrayHasKey('shipping_address', $response);
        $this->assertArrayHasKey('additional_instructions', $response);
        $this->assertArrayHasKey('seller', $response);
        $this->assertArrayHasKey('last_activity', $response);
        $this->assertArrayHasKey('buyer', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertCount(1, $response['items']);
        $this->assertSame('GET', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', strval($container[0]['request']->getUri()));
    }

    public function testChangeOrder()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('change_order', $history);
        $response = $client->changeOrder([
            'order_id'  => '1-1',
            'shipping'  => 5.0
        ]);

        $this->assertSame('POST', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', strval($container[0]['request']->getUri()));
    }

    public function testCreateListingValidation()
    {
        $container = [];
        $history = Middleware::History($container);
        $this->expectException('GuzzleHttp\Command\Exception\CommandException', 'Validation errors: [status] is a required string');
        $client = $this->createClient('create_listing', $history);
        $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'price' => 5.90
        ]);
    }

    public function testCreateListing()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('create_listing', $history);
        $response = $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'status' => 'For Sale',
            'price' => 5.90
        ]);

        $this->assertSame('POST', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings', strval($container[0]['request']->getUri()));
    }

    public function testDeleteListing()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('delete_listing', $history);
        $response = $client->deleteListing([
            'listing_id' => '129242581'
        ]);

        $this->assertSame('DELETE', $container[0]['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings/129242581', strval($container[0]['request']->getUri()));
    }

    public function testGetCollectionFolders()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_collection_folders', $history);
        $response = $client->getCollectionFolders([
            'username' => 'example'
        ]);

        $this->assertIsArray($response['folders']);
        $this->assertCount(2, $response['folders']);

        $this->assertSame('https://api.discogs.com/users/example/collection/folders', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetCollectionFolder()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_collection_folder', $history);
        $response = $client->getCollectionFolder([
            'username' => 'example',
            'folder_id' => 1
        ]);

        $this->assertSame($response['id'], 1);
        $this->assertSame($response['count'], 20);
        $this->assertSame($response['name'], 'Uncategorized');
        $this->assertSame($response['resource_url'], "https://api.discogs.com/users/example/collection/folders/1");

        $this->assertSame('https://api.discogs.com/users/example/collection/folders/1', strval($container[0]['request']->getUri()));
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    public function testGetCollectionItemsByFolder()
    {
        $container = [];
        $history = Middleware::History($container);
        $client = $this->createClient('get_collection_items_by_folder', $history);
        $response = $client->getCollectionItemsByFolder([
            'username' => 'rodneyfool',
            'folder_id' => 3,
            'sort' => 'artist',
            'sort_order' => 'desc',
            'per_page' => 50,
            'page' => 1
        ]);

        $this->assertCount(1, $response['releases']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);

        $this->assertSame(
            'https://api.discogs.com/users/rodneyfool/collection/folders/3/releases?per_page=50&page=1',
            strval($container[0]['request']->getUri())
        );
        $this->assertSame('GET', $container[0]['request']->getMethod());
    }

    protected function createClient($mock, $history)
    {
        $json = file_get_contents(__DIR__ . "/../../fixtures/$mock.json");
        $data = json_decode($json, true);
        $data['body'] = json_encode($data['body']);
        $mock = new MockHandler([
            new Response(
                $data['status'],
                $data['headers'],
                $data['body'],
                $data['version'],
                $data['reason']
            )
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = ClientFactory::factory(['handler' => $handler]);

        return $client;
    }
}
