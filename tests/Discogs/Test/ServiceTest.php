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
require_once 'AbstractTestCase.php';

use Discogs\Service;
use Discogs\Client;
use Discogs\ResponseTransformer\TransformException;

class ServiceTest extends AbstractTestCase
{
    protected $clientMockReturnValue;

    const TRANSFORMER_EXPECTED_RESULT = 'ok';
    const SEARCH_FIXTURE = 'database_search__q_vibrasphere.json';
    const SEARCH_CACHE_PATH = '/database/search?q=vibrasphere&per_page=50';

    public function testNext()
    {
        $convertedResponse = 'converted_response';
        $transformedResponse = 'transformed_response';
        $nextUrl = 'http://api.discogs.com/database/search?q=vibrasphere&per_page=50&page=2';

        $transformerMock = $this->getMock('\\Discogs\\ResponseTransformer\\Model');
        $transformerMock
            ->expects($this->once())
            ->method('get')
            ->with('pagination/urls/next', $transformedResponse)
            ->will($this->returnValue($nextUrl));
        $transformerMock
            ->expects($this->once())
            ->method('transform')
            ->with($convertedResponse, $nextUrl)
            ->will($this->returnValue(static::TRANSFORMER_EXPECTED_RESULT));

        $service = $this->getServiceWithMocks($this->getClientMock(1, false, $convertedResponse), $transformerMock);
        $result = $service->next($transformedResponse);
        $this->assertEquals(static::TRANSFORMER_EXPECTED_RESULT, $result);
    }

    public function testNext_invalid()
    {
        $transformedResponse = 'invalid_transformed_response';

        $transformerMock = $this->getMock('\\Discogs\\ResponseTransformer\\Model');
        $transformerMock
            ->expects($this->once())
            ->method('get')
            ->with('pagination/urls/next', $transformedResponse)
            ->will($this->throwException(new TransformException));

        $service = $this->getServiceWithMocks($this->getMock('\\Discogs\\Client'), $transformerMock);
        $result = $service->next($transformedResponse);
        $this->assertEquals(false, $result);
    }

    public function testSearch()
    {
        $result = $this->getServiceWithMocks()->search(array('q' => 'vibrasphere'));
        $this->assertEquals(static::TRANSFORMER_EXPECTED_RESULT, $result);
    }

    /**
     * @expectedException \Discogs\InvalidArgumentException
     * @expectedExceptionMessage Invalid options given: "invalid"
     * @expectedExceptionCode 0
     */
    public function testSearch_invalidOption()
    {
        $service = new Service;
        $service->search(array('invalid' => 'value'));
    }

    /**
     * @expectedException \Discogs\InvalidArgumentException
     * @expectedExceptionMessage Invalid type given: "invalid"
     * @expectedExceptionCode 0
     */
    public function testSearch_invalidType()
    {
        $service = new Service;
        $service->search(array('type' => 'invalid'));
    }

    public function testSearch_cacheMiss()
    {
        $clientMock = $this->getClientMock();
        $clientMock
            ->expects($this->once())
            ->method('getRawResponse')
            ->will($this->returnValue($this->loadFixture(static::SEARCH_FIXTURE)));

        $service = $this->getServiceWithMocks($clientMock, null, $this->getCacherMock(true, false));
        $result = $service->search(array('q' => 'vibrasphere'));
        $this->assertEquals(static::TRANSFORMER_EXPECTED_RESULT, $result);
    }

    public function testSearch_cacheHit()
    {
        $convertedResponse = 'cache_hit';

        $service = $this->getServiceWithMocks(
            $this->getClientMock(1, true, $convertedResponse),
            $this->getTransformerMock($convertedResponse),
            $this->getCacherMock()
        );

        $result = $service->search(array('q' => 'vibrasphere'));
        $this->assertEquals(static::TRANSFORMER_EXPECTED_RESULT, $result);
    }

    public function testSearch_cacheNotOperational()
    {
        $service = $this->getServiceWithMocks(null, null, $this->getCacherMock(false));
        $service->search(array('q' => 'vibrasphere'));
    }

    public function testSearch_throttle()
    {
        $invocationCnt = 3;

        $service = $this->getServiceWithMocks(
            $this->getClientMock($invocationCnt), $this->getTransformerMock(null, null, $invocationCnt)
        );

        $startTime = time();

        for ($i = 0; $i < $invocationCnt; $i++) {
            $service->search(array('q' => 'vibrasphere'));
        }

        $this->assertGreaterThanOrEqual($invocationCnt - 1, time() - $startTime);
    }

    public function testSearch_throttleWithCache()
    {
        $invocationCnt = 3;

        $service = $this->getServiceWithMocks(
            $this->getClientMock($invocationCnt, true, $this->getClientMockReturnValue()),
            $this->getTransformerMock(null, null, $invocationCnt),
            $this->getCacherMock(true, true, $invocationCnt)
        );

        $startTime = time();

        for ($i = 0; $i < $invocationCnt; $i++) {
            $service->search(array('q' => 'vibrasphere'));
        }

        $endTime = time();

        $this->assertLessThanOrEqual($invocationCnt - 1, $endTime - $startTime);
    }

    protected function getCacherMock($isOperational = true, $isHit = true, $invocationCnt = 1)
    {
        $cacherMock = $this->getMock('\\Discogs\\CacherInterface');
        $cacherMock
            ->expects($this->once())
            ->method('isOperational')
            ->will($this->returnValue($isOperational));


        if ($isOperational) {
            $cacherMock
                ->expects($this->exactly($invocationCnt))
                ->method('retrieve')
                ->with(static::SEARCH_CACHE_PATH)
                ->will($this->returnValue($isHit ? $this->loadFixture(static::SEARCH_FIXTURE) : false));
        } else {
            $cacherMock
                ->expects($this->never())
                ->method('retrieve');
        }

        if ($isOperational && !$isHit) {
            $cacherMock
                ->expects($this->exactly($invocationCnt))
                ->method('persist')
                ->with(static::SEARCH_CACHE_PATH, $this->loadFixture(static::SEARCH_FIXTURE));
        } else {
            $cacherMock
                ->expects($this->never())
                ->method('persist');
        }


        return $cacherMock;
    }

    protected function getClientMockReturnValue()
    {
        if (!$this->clientMockReturnValue) {
            $client = new Client;
            $this->clientMockReturnValue = $client->convertResponse(
                $this->loadFixture(static::SEARCH_FIXTURE)
            );
        }

        return $this->clientMockReturnValue;
    }

    protected function getClientMock($invocationCnt = 1, $isCacheHit = false, $convertedResponse = '')
    {
        $clientMock = $this->getMock('\\Discogs\\Client');

        if ($isCacheHit) {
            $clientMock
                ->expects($this->never())
                ->method('call');

            $clientMock
                ->expects($this->exactly($invocationCnt))
                ->method('convertResponse')
                ->with($this->loadFixture(static::SEARCH_FIXTURE))
                ->will($this->returnValue($convertedResponse));
        } else {
            $clientMock
                ->expects($this->exactly($invocationCnt))
                ->method('call')
                ->will($this->returnValue($convertedResponse ?: $this->getClientMockReturnValue()));
        }

        return $clientMock;
    }

    protected function getTransformerMock($input = null, $returnValue = null, $invocationCnt = 1)
    {
        $transformerMock = $this->getMock('\\Discogs\\ResponseTransformer\\Model');
        $transformerMock
            ->expects($this->exactly($invocationCnt))
            ->method('transform')
            ->with($input ?: $this->getClientMockReturnValue(), '/database/search')
            ->will($this->returnValue($returnValue ?: static::TRANSFORMER_EXPECTED_RESULT));

        return $transformerMock;
    }

    protected function getServiceWithMocks($clientMock = null, $transformerMock = null, $cacherMock = null)
    {
        $service = new Service($clientMock ?: $this->getClientMock());
        $service->setResponseTransformer($transformerMock ?: $this->getTransformerMock());

        if ($cacherMock) {
            $service->setCacher($cacherMock);
        }

        return $service;
    }

    protected function getTestCaseId()
    {
        return 'model';
    }
}
