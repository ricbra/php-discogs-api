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
        // dynamic throttle
        $remaining = $event->getTransaction()->response->getHeader('X-Discogs-Ratelimit-Remaining');
        if (!$remaining) $remaining = 1;
        $wait = (int)(60 / $remaining * 1000000);

        if ($wait > 0) {
            usleep($wait);
        }
    }
}
