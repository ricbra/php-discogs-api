<?php
/*
* This file is part of the php-discogs-api.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Discogs\Test\Integration;

use Discogs\Client;
use Buzz\Browser;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client;
    }

    public function testPing()
    {
        $response = $this->client->ping();
        $this->assertEquals($response->hello, "Welcome to the Discogs API.");
    }

    /**
     * @depends testPing
     * @expectedException \Discogs\ConnectionException
     * @expectedExceptionMessage Could not connect to Discogs
     * @expectedExceptionCode 0
     */
    public function testConnection()
    {
        $client = new Client(null, 'http://thishostdoesnotexistsyet.nlo');
        $client->call('/fake-path');
    }
}
