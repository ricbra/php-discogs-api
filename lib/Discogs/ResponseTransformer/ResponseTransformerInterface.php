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

use Discogs\ResponseTransformer\TransformException;

/**
 * @author Artem Komarov <i.linker@gmail.com>
 */
interface ResponseTransformerInterface
{
    /**
     * @param mixed  $response
     * @param string $path     Discogs API URN (e.g. '/database/search')
     * @throws TransformException
     * @return mixed
     */
    public function transform($response, $path = '');

    /**
     * Unified way of finding value for some field in already transformed response. Implementation should understand
     * input like:
     * ($path value on the left and JSON representation from Discogs API response on the right)
     * 'pagination/urls/prev' - {"pagination": {"urls": {"prev": "..."}}}
     * 'results/0' - {"results": [{}, {}, ...]}
     *
     * @param string $path                Path to the node
     * @param mixed  $transformedResponse Output of the transform() function
     * @throws TransformException
     * @return mixed
     */
    public function get($path, $transformedResponse);
}
