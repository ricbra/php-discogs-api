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
use Discogs\ResponseTransformer\TransformException;

/**
 * @author Artem Komarov <i.linker@gmail.com>
 */
class Hash implements ResponseTransformerInterface
{
    /**
     * Transform response into array
     *
     * @param mixed  $response
     * @param string $path
     * @return array
     */
    public function transform($response, $path = '')
    {
        $result = array();

        if (is_object($response)) {
            $reflection = new \ReflectionObject($response);

            foreach ($reflection->getProperties() as $property) {
                $result[$property->getName()] = $this->transform($response->{$property->getName()});
            }
        } else if (is_array($response)) {
            foreach ($response as $item) {
                $result[] = $this->transform($item);
            }
        } else {
            $result = $response;
        }

        return $result;
    }

    /**
     * Get value for some node in transformed response
     *
     * @param string $path
     * @param array  $transformed
     * @return mixed
     * @throws TransformException
     */
    public function get($path, $transformed)
    {
        $keys = explode('/', $path);
        $currentNode = $transformed;

        foreach ($keys as $key) {
            if (!is_array($currentNode) || !isset($currentNode[$key])) {
                throw new TransformException(sprintf('Node does not exist: %s', $path));
            }

            $currentNode = $currentNode[$key];
        }

        return $currentNode;
    }
}
