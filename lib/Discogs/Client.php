<?php

namespace Discogs;

use Buzz\Browser;

class Client
{
    private $browser;
    private $host;

    public function __construct(Browser $browser, $host = 'http://api.discogs.com')
    {
        $this->browser  = $browser;
        $this->host     = $host;
    }

    public function search($q, $type)
    {
        if (! in_array($type, array('release', 'master', 'artist', 'label'))) {
            throw new InvalidArgumentException(sprintf('Invalid type given: "%s"', $type));
        }
    }

    public function getArtist($artistId)
    {

    }

    protected function call($path, array $parameters = array())
    {

    }


}