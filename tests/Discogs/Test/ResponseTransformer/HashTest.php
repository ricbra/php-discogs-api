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

use Discogs\ResponseTransformer\Hash;

class HashTest extends AbstractTestCase
{
    protected function getTestCaseId()
    {
        return 'hash';
    }

    protected function getTransformer()
    {
        return new Hash;
    }
}
