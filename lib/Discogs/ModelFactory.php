<?php
namespace Discogs;

class ModelFactory
{
    private $transformer;

    public function __construct(ResponseTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createModel(\stdClass $class)
    {
        if (isset($class->artist)) {
            $model = $this->transformer('artist', array(
                'id',
                'name',
                'resource_url',
                'releases_url',
                'uri',
                'realname',
                'profile',
                'data_quality'
            ), $class);
        }
    }
}