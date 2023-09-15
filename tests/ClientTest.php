<?php
/*
 * This file is part of the php-discogs-api.
 *
 * (c) Richard van den Brand <richard@vandenbrand.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chrobane\Discogs\Test;

use BenTools\GuzzleHttp\Middleware\Storage\Adapter\ArrayAdapter;
use BenTools\GuzzleHttp\Middleware\ThrottleConfiguration;
use BenTools\GuzzleHttp\Middleware\ThrottleMiddleware;
use BenTools\Psr7\RequestMatcherInterface;
use Chrobane\Discogs\ClientFactory;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Command\Exception\CommandException;
use Psr\Http\Message\RequestInterface;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testGetArtist()
    {
        $container = [];
        $client = $this->createClient('get_artist', $container);
        $response = $client->getArtist([
            'id' => 45
        ]);
        $this->assertSame($response['id'], 45);
        $this->assertSame($response['name'], 'Aphex Twin');
        $this->assertSame($response['realname'], 'Richard David James');
        $this->assertIsArray($response['images']);
        $this->assertCount(9, $response['images']);

        $this->assertSame('https://api.discogs.com/artists/45', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetArtistReleases()
    {
        $container = [];
        $client = $this->createClient('get_artist_releases', $container);

        $response = $client->getArtistReleases([
            'id' => 45,
            'per_page' => 50,
            'page' => 1
        ]);
        $this->assertCount(50, $response['releases']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);

        $this->assertSame('https://api.discogs.com/artists/45/releases?per_page=50&page=1', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testSearch()
    {
        $container = [];
        $client = $this->createClient('search', $container);

        $response = $client->search([
            'q' => 'prodigy',
            'type' => 'release',
            'title' => 'the fat of the land'
        ]);
        $this->assertCount(50, $response['results']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertSame('https://api.discogs.com/database/search?q=prodigy&type=release&title=the%20fat%20of%20the%20land', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetRelease()
    {
        $container = [];
        $client = $this->createClient('get_release', $container);
        $response = $client->getRelease([
            'id' => 1,
            'curr_abbr' => 'USD'
        ]);

        $this->assertLessThanOrEqual(8.077169493076712, $response['lowest_price']);
        $this->assertSame('Accepted', $response['status']);
        $this->assertArrayHasKey('videos', $response);
        $this->assertCount(6, $response['videos']);
        $this->assertSame('https://api.discogs.com/releases/1?curr_abbr=USD', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetMaster()
    {
        $container = [];
        $client = $this->createClient('get_master', $container);

        $response = $client->getMaster([
            'id' => 33687
        ]);
        $this->assertSame('O Fortuna', $response['title']);
        $this->assertArrayHasKey('tracklist', $response);
        $this->assertCount(2, $response['tracklist']);
        $this->assertSame('https://api.discogs.com/masters/33687', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetMasterVersions()
    {
        $container = [];
        $client = $this->createClient('get_master_versions', $container);

        $response = $client->getMasterVersions([
            'id' => 33687,
            'per_page' => 4,
            'page' => 2
        ]);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('versions', $response);
        $this->assertCount(4, $response['versions']);
        $this->assertSame('https://api.discogs.com/masters/33687/versions?per_page=4&page=2', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetLabel()
    {
        $container = [];
        $client = $this->createClient('get_label', $container);
        $response = $client->getLabel([
            'id' => 1
        ]);
        $this->assertArrayHasKey('releases_url', $response);
        $this->assertSame('https://api.discogs.com/labels/1/releases', $response['releases_url']);
        $this->assertArrayHasKey('sublabels', $response);
        $this->assertCount(6, $response['sublabels']);
        $this->assertSame('https://api.discogs.com/labels/1', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetLabelReleases()
    {
        $container = [];
        $client = $this->createClient('get_label_releases', $container);
        $response = $client->getLabelReleases([
            'id' => 1,
            'per_page' => 2,
            'page' => 1
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('releases', $response);
        $this->assertCount(2, $response['releases']);
        $this->assertSame('https://api.discogs.com/labels/1/releases?per_page=2&page=1', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetOAuthIdentity()
    {
        $container = [];
        $client = $this->createClient('get_oauth_identity', $container);
        $response = $client->getOAuthIdentity();

        $this->assertSame($response['username'], 'R-Search');
        $this->assertSame($response['resource_url'], 'https://api.discogs.com/users/R-Search');
        $this->assertSame($response['consumer_name'], 'RicbraDiscogsBundle');
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetProfile()
    {
        $container = [];
        $client = $this->createClient('get_profile', $container);
        $response = $client->getProfile([
            'username' => 'maxperei'
        ]);

        $this->assertEquals(200, end($container)['response']->getStatusCode());
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('avatar_url', $response);
        $this->assertArrayHasKey('home_page', $response);
        $this->assertArrayNotHasKey('email', $response);
        $this->assertSame($response['name'], '∴');
        $this->assertSame($response['avatar_url'], 'https://img.discogs.com/mDaw_OUjHspYLj77C_tcobr2eXc=/500x500/filters:strip_icc():format(jpeg):quality(40)/discogs-avatars/U-1861520-1498224434.jpeg.jpg');
        $this->assertSame($response['home_page'], 'http://maxperei.info');
        $this->assertSame('https://api.discogs.com/users/maxperei', (string)end($container)['request']->getUri());
    }

    public function testGetInventory()
    {
        $container = [];
        $client = $this->createClient('get_inventory', $container);
        $response = $client->getInventory([
            'username'      => '360vinyl',
            'sort'          => 'price',
            'sort_order'    => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('listings', $response);
        $this->assertCount(50, $response['listings']);
        $this->assertSame('GET', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/users/360vinyl/inventory?sort=price&sort_order=asc', (string)end($container)['request']->getUri());
    }

    public function testGetOrders()
    {
        $container = [];
        $client = $this->createClient('get_orders', $container);
        $response = $client->getOrders([
            'status'      => 'New Order',
            'sort'        => 'price',
            'sort_order'  => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('orders', $response);
        $this->assertCount(1, $response['orders']);
        $this->assertSame('GET', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders?status=New%20Order&sort=price&sort_order=asc', (string)end($container)['request']->getUri());
    }

    public function testGetOrder()
    {
        $container = [];
        $client = $this->createClient('get_order', $container);
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
        $this->assertSame('GET', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', (string)end($container)['request']->getUri());
    }

    public function testChangeOrder()
    {
        $container = [];
        $client = $this->createClient('change_order', $container);
        $response = $client->changeOrder([
            'order_id'  => '1-1',
            'shipping'  => 5.0
        ]);

        $this->assertSame('POST', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', (string)end($container)['request']->getUri());
    }

    public function testCreateListingValidation()
    {
        $container = [];
        $this->expectException(CommandException::class, 'Validation errors: [status] is a required string');
        $client = $this->createClient('create_listing', $container);
        $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'price' => 5.90
        ]);
    }

    public function testCreateListing()
    {
        $container = [];
        $client = $this->createClient('create_listing', $container);
        $response = $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'status' => 'For Sale',
            'price' => 5.90
        ]);

        $this->assertSame('POST', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings', (string)end($container)['request']->getUri());
    }

    public function testDeleteListing()
    {
        $container = [];
        $client = $this->createClient('delete_listing', $container);
        $response = $client->deleteListing([
            'listing_id' => '129242581'
        ]);

        $this->assertSame('DELETE', end($container)['request']->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings/129242581', (string)end($container)['request']->getUri());
    }

    public function testGetCollectionFolders()
    {
        $container = [];
        $client = $this->createClient('get_collection_folders', $container);
        $response = $client->getCollectionFolders([
            'username' => 'example'
        ]);

        $this->assertIsArray($response['folders']);
        $this->assertCount(2, $response['folders']);

        $this->assertSame('https://api.discogs.com/users/example/collection/folders', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetCollectionFolder()
    {
        $container = [];
        $client = $this->createClient('get_collection_folder', $container);
        $response = $client->getCollectionFolder([
            'username' => 'example',
            'folder_id' => 1
        ]);

        $this->assertSame($response['id'], 1);
        $this->assertSame($response['count'], 20);
        $this->assertSame($response['name'], 'Uncategorized');
        $this->assertSame($response['resource_url'], "https://api.discogs.com/users/example/collection/folders/1");

        $this->assertSame('https://api.discogs.com/users/example/collection/folders/1', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testGetCollectionItemsByFolder()
    {
        $container = [];
        $client = $this->createClient('get_collection_items_by_folder', $container);
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

        $this->assertSame('https://api.discogs.com/users/rodneyfool/collection/folders/3/releases?per_page=50&page=1', (string)end($container)['request']->getUri());
        $this->assertSame('GET', end($container)['request']->getMethod());
    }

    public function testWithThrottle()
    {
        $before = microtime(true);
        $container = [];
        $client = $this->createClient('get_artist', $container, true);
        $response = $client->getArtist(['id' => 45]);
        $container = [];
        $client = $this->createClient('get_artist', $container, true);
        $response = $client->getArtist(['id' => 45]);
        $after = microtime(true);

        $difference = $after - $before;
        // Should be at least 2 seconds
        $this->assertTrue($difference > 2);
    }

    protected function createClient(string $service, &$container, $throttle = false)
    {
        $history = Middleware::history($container);
        $params = $this->fromString(
            file_get_contents(sprintf('%s/fixtures/%s', __DIR__, $service))
        );
        $mock = new MockHandler([new Response(
            $params['code'],
            $params['headers'],
            $params['body']
        )]);
        $handler_stack = HandlerStack::create($mock);
        $handler_stack->push($history);

        $config = ['handler' => $handler_stack];
        if ($throttle) {
            $config['throttle_max_requests'] = 1;
            $config['throttle_duration'] = 2;
        }

        return ClientFactory::factory($config);
    }

    protected function fromString(string $message): array
    {
        static $parser;

        if (!$parser) {
            $parser = new MessageParser();
        }

        if (strtoupper(substr($message, 0, 4)) == 'HTTP') {
            return $parser->parseResponse($message);
        }

        throw new \InvalidArgumentException('Unable to parse request');
    }
}
