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

/**
 * @author Artem Komarov <i.linker@gmail.com>
 */
interface ResponseTransformerInterface
{
    /**
     * @param mixed  $response
     * @param string $path     Discogs API URN (e.g. '/database/search')
     * @return mixed
     */
    public function transform($response, $path = '');
}
