<?php
/*
* This file is part of the DiscogsAPI PHP SDK.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Discogs;

class ResponseTransformer
{
    /**
     * Transforms a response into useable objects
     *
     * @param \stdClass $response
     * @return array
     */
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
        $class              = $this->getClass($key);
        $reflection         = new \ReflectionObject($source);
        $instance           = new $class;

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
            }
        }
        return $instance;
    }

    /**
     * Transforms an array into its correct classes, based on the key
     *
     * @param $property
     * @param array $values
     * @return array
     */
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

    /**
     * Returns singular for plural, ie company for companies
     *
     * @param $name
     * @return bool|string
     */
    protected function getSingular($name)
    {
        switch($name) {
            case 'images':
            case 'urls':
            case 'labels':
            case 'artists':
            case 'results':
            case 'videos':
            case 'sublabels':
                return substr($name, 0, strlen($name)-1);
            case 'aliases':
                return substr($name, 0, strlen($name)-2);
            case 'companies':
                return 'company';
            case 'tracklist':
                return 'track';
        }
        return false;
    }
}