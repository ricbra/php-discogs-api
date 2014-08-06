<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Discogs\Subscriber;

use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\SubscriberInterface;

class ThrottleSubscriber implements SubscriberInterface
{
    private $throttle;
    private static $previousTimestamp;

    public function __construct($throttle = 1000000)
    {
        $this->throttle = (int) $throttle;
    }

    public function getEvents()
    {
        return [
            'complete' => ['onComplete']
        ];
    }

    public function onComplete(CompleteEvent $event)
    {
        $now = microtime(true);
        $wait = self::$previousTimestamp + $this->throttle - $now;

        if ($wait > 0) {
            usleep($wait);
        }

        self::$previousTimestamp = microtime(true);
    }
}
