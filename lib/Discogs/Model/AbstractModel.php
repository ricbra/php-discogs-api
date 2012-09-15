<?php
/*
* This file is part of the DiscogsAPI PHP SDK.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Discogs\Model;

/**
 * @author Artem Komarov <i.linker@gmail.com>
 */
abstract class AbstractModel
{
    public function toArray($object = '**this**')
    {
        $array = array();

        if ($object == '**this**') {
            $object = $this;
        }

        if (is_object($object)) {
            $reflection = new \ReflectionObject($object);

            foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
                /* @var $property \ReflectionProperty */
                $property->setAccessible(true);
                $array[$property->getName()] = $this->toArray($property->getValue($object));
            }
        } else if (is_array($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $this->toArray($value);
            }
        } else {
            return $object;
        }

        return $array;
    }
}
