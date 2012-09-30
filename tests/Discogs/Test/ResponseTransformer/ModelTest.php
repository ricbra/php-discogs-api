<?php
/*
* This file is part of the php-discogs-api.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Discogs\Test\ResponseTransformer;
require_once 'AbstractTestCase.php';

use Discogs\ResponseTransformer\Model;
use Discogs\Client;

class ModelTest extends AbstractTestCase
{

    /**
     * @expectedException \Discogs\InvalidArgumentException
     * @expectedExceptionMessage $response expected to be object
     * @expectedExceptionCode 0
     */
    public function testTransform_invalidResponse()
    {
        $this->transformer->transform('invalid input');
    }

    /**
     * @expectedException \Discogs\InvalidArgumentException
     * @expectedExceptionMessage $path expected to be string
     * @expectedExceptionCode 0
     */
    public function testTransform_invalidPath()
    {
        $this->transformer->transform(new \stdClass(), new \stdClass());
    }

    protected function getTestCaseId()
    {
        return 'model';
    }

    protected function getTransformer()
    {
        return new Model;
    }
}
