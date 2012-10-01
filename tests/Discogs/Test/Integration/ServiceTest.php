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

use Discogs\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    protected $service;

    public function setUp()
    {
        $this->service = new Service;
    }

    /**
     * @expectedException \Discogs\NoResultException
     * @expectedExceptionMessage Artist not found.
     * @expectedExceptionCode 0
     */
    public function testInvalidArtist()
    {
        $this->service->getArtist(0);
    }

    /**
     * @expectedException \Discogs\NoResultException
     * @expectedExceptionMessage Label not found
     * @expectedExceptionCode 0
     */
    public function testInvalidLabel()
    {
        $this->service->getLabel(0);
    }

    /**
     * @expectedException \Discogs\NoResultException
     * @expectedExceptionMessage Release not found
     * @expectedExceptionCode 0
     */
    public function testInvalidRelease()
    {
        $this->service->getRelease(0);
    }

    /**
     * @expectedException \Discogs\NoResultException
     * @expectedExceptionMessage Master Release not found.
     * @expectedExceptionCode 0
     */
    public function testInvalidMaster()
    {
        $this->service->getMaster(0);
    }

    public function testMaster()
    {
        $response = $this->service->getMaster(8471);
        $this->assertEquals($response->getId(), 8471);
    }

    public function testRelease()
    {
        $response = $this->service->getRelease(1);
        $this->assertEquals($response->getId(), 1);
    }

    public function testArtist()
    {
        $response = $this->service->getArtist(45);
        $this->assertEquals($response->getId(), 45);
    }

    public function testLabel()
    {
        $response = $this->service->getLabel(1);
        $this->assertEquals($response->getId(), 1);
    }

    public function testNext()
    {
        $response = $this->service->search(array('q' => 'vibrasphere'));
        $response = $this->service->next($response);
        $this->assertEquals(2, $this->service->getResponseTransformer()->get('pagination/page', $response));
    }
}
