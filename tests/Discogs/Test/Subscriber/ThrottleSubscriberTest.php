<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Discogs\Test\Subscriber;

use Discogs\Subscriber\ThrottleSubscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class ThrottleSubscriberTest extends \PHPUnit\Framework\TestCase
{

    public function testInstantiation(): void
    {
        $throttle = new ThrottleSubscriber();
        $this->assertInstanceOf(ThrottleSubscriber::class, $throttle);
    }

    public function testWithThrottle()
    {
        $throttle = 2000; // milliseconds == 2 sec
        $subscriber = new ThrottleSubscriber($throttle);

        $before = microtime(true);

        $handler = HandlerStack::create(new MockHandler([
            new Response(429),
            new Response(200)
        ]));
        $handler->push(Middleware::retry($subscriber->decider(), $subscriber->delay()));
        $client = new Client(['handler' => $handler]);
        $client->request('GET', '/');

        $after = microtime(true);

        $difference = $after - $before;
        // Should be at least 2 seconds
        $this->assertEqualsWithDelta($difference, 2, 0.1);
    }

    public function testWithoutThrottle()
    {
        $throttle = 0;
        $subscriber = new ThrottleSubscriber($throttle);

        $before = microtime(true);

        $handler = HandlerStack::create(new MockHandler([
            new Response(429),
            new Response(200)
        ]));
        $handler->push(Middleware::retry($subscriber->decider(), $subscriber->delay()));
        $client = new Client(['handler' => $handler]);
        $client->request('GET', '/');

        $after = microtime(true);

        $difference = $after - $before;
        // Should be at max 0.5 seconds on a very slow system, tricky to test
        $this->assertTrue($difference < 0.5);
    }

    public function testMaxRetries()
    {
        $throttle = 500;
        $max_retries = 2;
        $subscriber = new ThrottleSubscriber($throttle, $max_retries);

        $before = microtime(true);

        $handler = HandlerStack::create(new MockHandler([
            new Response(429),
            new Response(429),
            new Response(429),
        ]));
        $handler->push(Middleware::retry($subscriber->decider(), $subscriber->delay()));
        $client = new Client(['handler' => $handler]);
        try {
            $client->request('GET', '/');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ClientException::class, $e);
            $this->assertEquals($e->getCode(), 429);
        }

        $after = microtime(true);
        $difference = $after - $before;

        $this->assertEqualsWithDelta($difference, 1.5, 0.1);
    }
}
