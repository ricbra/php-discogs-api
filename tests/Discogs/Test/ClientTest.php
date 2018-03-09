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

        $this->assertSame('https://api.discogs.com/artists/45', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
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

        $this->assertSame('https://api.discogs.com/artists/45/releases?per_page=50&page=1', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testSearch()
    {
        $history = new History();
        $client = $this->createClient('search', $history);

        $response = $client->search([
            'q' => 'prodigy',
            'type' => 'release',
            'title' => 'the fat of the land'
        ]);
        $this->assertCount(50, $response['results']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertSame('https://api.discogs.com/database/search?q=prodigy&type=release&title=the%20fat%20of%20the%20land', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetRelease()
    {
        $history = new History();
        $client = $this->createClient('get_release', $history);
        $response = $client->getRelease([
            'id' => 1,
            'curr_abbr' => 'USD'
        ]);

        $this->assertLessThanOrEqual(8.077169493076712, $response['lowest_price']);
        $this->assertSame('Accepted', $response['status']);
        $this->assertArrayHasKey('videos', $response);
        $this->assertCount(6, $response['videos']);
        $this->assertSame('https://api.discogs.com/releases/1?curr_abbr=USD', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetMaster()
    {
        $history = new History();
        $client = $this->createClient('get_master', $history);

        $response = $client->getMaster([
            'id' => 33687
        ]);
        $this->assertSame('O Fortuna', $response['title']);
        $this->assertArrayHasKey('tracklist', $response);
        $this->assertCount(2, $response['tracklist']);
        $this->assertSame('https://api.discogs.com/masters/33687', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetMasterVersions()
    {
        $history = new History();
        $client = $this->createClient('get_master_versions', $history);

        $response = $client->getMasterVersions([
            'id' => 33687,
            'per_page' => 4,
            'page' => 2
        ]);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('versions', $response);
        $this->assertCount(4, $response['versions']);
        $this->assertSame('https://api.discogs.com/masters/33687/versions?per_page=4&page=2', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetLabel()
    {
        $history = new History();
        $client = $this->createClient('get_label', $history);
        $response = $client->getLabel([
            'id' => 1
        ]);
        $this->assertArrayHasKey('releases_url', $response);
        $this->assertSame('https://api.discogs.com/labels/1/releases', $response['releases_url']);
        $this->assertArrayHasKey('sublabels', $response);
        $this->assertCount(6, $response['sublabels']);
        $this->assertSame('https://api.discogs.com/labels/1', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetLabelReleases()
    {
        $history = new History();
        $client = $this->createClient('get_label_releases', $history);
        $response = $client->getLabelReleases([
            'id' => 1,
            'per_page' => 2,
            'page' => 1
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('releases', $response);
        $this->assertCount(2, $response['releases']);
        $this->assertSame('https://api.discogs.com/labels/1/releases?per_page=2&page=1', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetOAuthIdentity()
    {
        $history = new History();
        $client = $this->createClient('get_oauth_identity', $history);
        $response = $client->getOAuthIdentity();

        $this->assertSame($response['username'], 'R-Search');
        $this->assertSame($response['resource_url'], 'https://api.discogs.com/users/R-Search');
        $this->assertSame($response['consumer_name'], 'RicbraDiscogsBundle');
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetInventory()
    {
        $client = $this->createClient('get_inventory', $history = new History());
        $response = $client->getInventory([
            'username'      => '360vinyl',
            'sort'          => 'price',
            'sort_order'    => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('listings', $response);
        $this->assertCount(50, $response['listings']);
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/users/360vinyl/inventory?sort=price&sort_order=asc', $history->getLastRequest()->getUrl());
    }

    public function testGetOrders()
    {
        $client = $this->createClient('get_orders', $history = new History());
        $response = $client->getOrders([
            'status'      => 'New Order',
            'sort'        => 'price',
            'sort_order'  => 'asc'
        ]);

        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('orders', $response);
        $this->assertCount(1, $response['orders']);
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders?status=New%20Order&sort=price&sort_order=asc', $history->getLastRequest()->getUrl());
    }

    public function testGetOrder()
    {
        $client = $this->createClient('get_order', $history = new History());
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
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', $history->getLastRequest()->getUrl());
    }

    public function testChangeOrder()
    {
        $client = $this->createClient('change_order', $history = new History());
        $response = $client->changeOrder([
            'order_id'  => '1-1',
            'shipping'  => 5.0
        ]);

        $this->assertSame('POST', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/orders/1-1', $history->getLastRequest()->getUrl());
    }

    public function testCreateListingValidation(){
        $this->setExpectedException('GuzzleHttp\Command\Exception\CommandException', 'Validation errors: [status] is a required string');
        $client = $this->createClient('create_listing', $history = new History());
        $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'price' => 5.90
        ]);
    }

    public function testCreateListing()
    {
        $client = $this->createClient('create_listing', $history = new History());
        $response = $client->createListing([
            'release_id' => '1',
            'condition' => 'Mint (M)',
            'status' => 'For Sale',
            'price' => 5.90
        ]);

        $this->assertSame('POST', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings', $history->getLastRequest()->getUrl());
    }

    public function testDeleteListing()
    {
        $client = $this->createClient('delete_listing', $history = new History());
        $response = $client->deleteListing([
            'listing_id' => '129242581'
        ]);

        $this->assertSame('DELETE', $history->getLastRequest()->getMethod());
        $this->assertSame('https://api.discogs.com/marketplace/listings/129242581', $history->getLastRequest()->getUrl());
    }

    public function testGetCollectionFolders()
    {
        $history = new History();
        $client = $this->createClient('get_collection_folders', $history);
        $response = $client->getCollectionFolders([
            'username' => 'example'
        ]);

        $this->assertInternalType('array', $response['folders']);
        $this->assertCount(2, $response['folders']);

        $this->assertSame('https://api.discogs.com/users/example/collection/folders', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetCollectionFolder()
    {
        $history = new History();
        $client = $this->createClient('get_collection_folder', $history);
        $response = $client->getCollectionFolder([
            'username' => 'example',
            'folder_id' => 1
        ]);

        $this->assertSame($response['id'], 1);
        $this->assertSame($response['count'], 20);
        $this->assertSame($response['name'], 'Uncategorized');
        $this->assertSame($response['resource_url'], "https://api.discogs.com/users/example/collection/folders/1");

        $this->assertSame('https://api.discogs.com/users/example/collection/folders/1', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
    }

    public function testGetCollectionItemsByFolder()
    {
        $history = new History();
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

        $this->assertSame('https://api.discogs.com/users/rodneyfool/collection/folders/3/releases?per_page=50&page=1', $history->getLastRequest()->getUrl());
        $this->assertSame('GET', $history->getLastRequest()->getMethod());
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
