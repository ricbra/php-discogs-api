<?php

namespace Discogs;

class Service
{
    private $client;
    private $host;

    public function __construct(Client $client)
    {
        $this->client  = $client;
    }

    public function search($q, $type)
    {
        if (! in_array($type, array('release', 'master', 'artist', 'label'))) {
            throw new InvalidArgumentException(sprintf('Invalid type given: "%s"', $type));
        }
    }

    public function getArtist($artistId)
    {
        $this->call(sprintf('artist/%d', $artistId));
    }

    public function getRelease($releaseId)
    {
        $this->call(sprintf('release/%d', $releaseId));
    }



    protected function call($path, array $parameters = array())
    {
        $rawData = $this->client->call($path, $parameters);

        if (isset($rawData->error)) {
            throw new NoResultException($rawData->error);
        }
        $transformer = new ResponseTransformer();
        $transformer->transform('artist', $rawData);

    }


}