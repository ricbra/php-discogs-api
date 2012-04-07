<?php

namespace Discogs;

class ResponseTransformer
{
    public function tranformResponse(\stdClass $response)
    {
        $reflection = new \ReflectionObject($response);
        $results    = array();

        foreach ($reflection->getProperties() as $property) {
            $key   = $property->getName();
            $class = $this->getClass($key);

            if (class_exists($class)) {
                $results[] = $this->transform($key, $response->$key);
            }
        }
        print_r($results);die;
        return $results;
    }

    /**
     * Transform a stdClass object in the corresponding Model class
     *
     * @param $key
     * @param \stdClass $source
     * @return mixed
     */
    public function transform($key, \stdClass $source)
    {
        $class      = $this->getClass($key);
        $reflection = new \ReflectionObject($source);
        $instance   = new $class;

        foreach($reflection->getProperties() as $property) {
            $setter = 'set'.$this->getCamelCase($property->getName());
            if (method_exists($instance, $setter)) {
                $prop  = $property->getName();
                $value = $source->$prop;

                if (is_object($value) && class_exists($this->getClass($prop))) {
                    $value = $this->transform($prop, $value);
                }
                // This automatically converts images, releases, aliases
                if (is_array($value)) {
                    $value = $this->transformArray($prop, $value);
                }

                $instance->$setter($value);
            } else {
                echo 'private $'.lcfirst($this->getCamelCase($property->getName())).';<br />';
            }
        }
        return $instance;
    }

    protected function transformArray($property, array $values)
    {
        // Converts plural form to singular
        if (! $property = $this->getSingular($property)) {
            return $values;
        }
        if (! class_exists($this->getClass($property))) {
            return $values;
        }

        $return = array();
        foreach ($values as $value) {
            $return[] = $this->transform($property, $value);
        }

        return $return;
    }

    /**
     * Converts property into classname
     *
     * @param $var
     * @return string
     */
    protected function getClass($var)
    {
        return sprintf('Discogs\\Model\\%s', $this->getCamelCase($var));
    }

    /**
     * Creates a camelcase string from a property
     *
     * @param $var
     * @return string
     */
    protected function getCamelCase($var)
    {
        return implode('', array_map(function($var) {
            return ucfirst($var);
        }, explode('_', $var)));
    }

    protected function getSingular($name)
    {
        switch($name) {
            case 'images':
            case 'urls':
            case 'labels':
            case 'artists':
            case 'results':
                return substr($name, 0, strlen($name)-1);
            case 'aliases':
                return substr($name, 0, strlen($name)-2);
            case 'companies':
                return 'company';
        }
        return false;
    }
}