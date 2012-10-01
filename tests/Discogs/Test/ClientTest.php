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

use Discogs\Client;
use Buzz\Browser;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = new Client();
    }

    /**
     * @dataProvider dataProviderGetUrl
     */
    public function testGetUrl($path, $expected)
    {
        $url = $this->client->getUrl($path);
        $this->assertEquals($expected, $url);
    }

    public function dataProviderGetUrl()
    {
        return array(
            array('/database/search', 'http://api.discogs.com/database/search'),
            array('http://api.discogs.com/my/path', 'http://api.discogs.com/my/path'),
        );
    }

    /**
     * @expectedException \Discogs\InvalidResponseException
     * @expectedExceptionMessage Unknown data received from server
     * @expectedExceptionCode 0
     */
    public function testConvertResponse()
    {
        $this->client->convertResponse('this is not JSON');
    }
}
