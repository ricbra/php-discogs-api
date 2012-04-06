<?php

namespace Discogs;

class ResponseTransformer
{
    public function transform($key, \stdClass $object)
    {
        $source     = $object->$key;
        $class      = sprintf('Discogs\\Model\\%s', $this->getCamelCase($key));
        $reflection = new \ReflectionObject($source);
        $instance   = new $class;

        foreach($reflection->getProperties() as $property) {
            $setter = 'set'.$this->getCamelCase($property->getName());
            if (method_exists($instance, $setter)) {
                $prop = $property->getName();
                $instance->$setter($source->$prop);
            }
        }
        return $instance;
    }

    protected function getCamelCase($var)
    {
        return implode('', array_map(function($var) {
            return ucfirst($var);
        }, explode('_', $var)));
    }
}