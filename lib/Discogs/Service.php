<?php

namespace Discogs;

class Service
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client  = $client;
    }

    public function search($q, $type, array $options = array())
    {
        if (! in_array($type, array('release', 'master', 'artist', 'label'))) {
            throw new InvalidArgumentException(sprintf('Invalid type given: "%s"', $type));
        }

        return $this->call('/database/search', 'resultset', array(
            'q'     => $q,
            'type'  => $type
        ));
    }

    public function getArtist($artistId)
    {
        return $this->call(sprintf('/artists/%d', $artistId), 'artist');
    }

    public function getRelease($releaseId)
    {
        return $this->call(sprintf('/releases/%d', $releaseId), 'release');
    }

    public function getMaster($masterId)
    {
        return $this->call(sprintf('/masters/%d', $masterId), 'master');
    }

    protected function call($path, $responseKey, array $parameters = array())
    {
        $rawData = $this->client->call($path, $parameters);

        if (isset($rawData->message)) {
            throw new NoResultException($rawData->message);
        }
        $transformer = new ResponseTransformer();

        return $transformer->transform($responseKey, $rawData);

    }


}