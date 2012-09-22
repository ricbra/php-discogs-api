<?php
/*
* This file is part of the DiscogsAPI PHP SDK.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Discogs\ResponseTransformer;

use Discogs\ResponseTransformer\ResponseTransformerInterface;
use Discogs\InvalidArgumentException;

class Model implements ResponseTransformerInterface
{
    /**
     * Transform a stdClass object in the corresponding Model class
     *
     * @param \stdClass $response
     * @param string    $path     Discogs API URN (e.g. '/database/search')
     * @return mixed
     */
    public function transform($response, $path = '')
    {
        $class              = $this->getClass($path);
        $reflection         = new \ReflectionObject($response);
        $instance           = new $class;

        foreach($reflection->getProperties() as $property) {
            $setter = 'set'.$this->getCamelCase($property->getName());
            if (method_exists($instance, $setter)) {
                $prop  = $property->getName();
                $value = $response->$prop;

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
     * Get value for some node in transformed response
     *
     * @param string $path
     * @param mixed  $transformed
     * @return mixed
     * @throws TransformException
     */
    public function get($path, $transformed)
    {
        $currentNode = $transformed;

        foreach (explode('/', $path) as $key) {
            $methodName = 'get' . $this->getCamelCase($key);

            if (!is_object($currentNode) || !method_exists($currentNode, $methodName)) {
                throw new TransformException(sprintf('Node does not exist: %s', $path));
            }

            $currentNode = $currentNode->$methodName();
        }

        return $currentNode;
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
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getClass($path)
    {
        $className = sprintf('Discogs\\Model\\%s', $this->getCamelCase($path));

        if (class_exists($className)) {
            return $className;
        }

        if (preg_match('#^/database/search#', $path)) {
            $key = 'resultset';
        } else if (preg_match('#^/(artists|releases|masters|labels)/#', $path, $matches)) {
            $key = $this->getSingular($matches[1]);
        } else {
            throw new InvalidArgumentException("Can't transform response for path {$path}");
        }

        return sprintf('Discogs\\Model\\%s', $this->getCamelCase($key));
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
