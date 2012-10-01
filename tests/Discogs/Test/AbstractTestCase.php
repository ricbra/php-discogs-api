<?php
/*
* This file is part of the php-discogs-api.
*
* (c) Richard van den Brand <richard@vandenbrand.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Discogs\Test;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getTestCaseId();

    protected function loadResponse($fixture)
    {
        $fixtureName = sprintf('%s%s%s-result.serialized', $this->getTestCaseId(), DIRECTORY_SEPARATOR, $fixture);
        return unserialize($this->loadFixture($fixtureName));
    }

    protected function loadFixture($fileName)
    {
        $fileName = sprintf(
            '%s%sfixture%s%s',
            dirname(dirname(dirname(__FILE__))),
            DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $fileName
        );

        return file_get_contents($fileName);
    }
}
