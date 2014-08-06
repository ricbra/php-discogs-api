<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Discogs\Test\Subscriber;

use Discogs\Subscriber\ThrottleSubscriber;
use GuzzleHttp\Event\CompleteEvent;

class ThrottleSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testWithThrottle()
    {
        $throttle = 2000000; // 2 sec
        $subscriber = new ThrottleSubscriber($throttle);

        $mock = $this->getMock('GuzzleHttp\Event\CompleteEvent', [], [], '', false);

        $before = microtime(true);
        $subscriber->onComplete($mock);
        $subscriber->onComplete($mock);
        $after = microtime(true);

        $difference = $after - $before;
        // Should be at least 2 seconds
        $this->assertTrue($difference > 2);
    }

    public function testWithoutThrottle()
    {
        $throttle = 0;
        $subscriber = new ThrottleSubscriber($throttle);

        $mock = $this->getMock('GuzzleHttp\Event\CompleteEvent', [], [], '', false);

        $before = microtime(true);
        $subscriber->onComplete($mock);
        $subscriber->onComplete($mock);
        $after = microtime(true);


        $difference = $after - $before;
        // Should be at max 0.5 seconds on a very slow system, tricky to test
        $this->assertTrue($difference < 0.5);
    }
}
