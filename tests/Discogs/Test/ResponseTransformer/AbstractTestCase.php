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
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'AbstractTestCase.php';

use Discogs\ResponseTransformer\ResponseTransformerInterface;
use Discogs\Client;

abstract class AbstractTestCase extends \Discogs\Test\AbstractTestCase
{
    /**
     * @var ResponseTransformerInterface
     */
    protected $transformer;

    abstract protected function getTransformer();

    public function setUp()
    {
        $this->transformer = $this->getTransformer();
    }

    /**
     * @dataProvider dataProviderTestTransform
     */
    public function testTransform($response, $path, $expectedObject)
    {
        $transformed = $this->transformer->transform($response, $path);
        $this->assertEquals($transformed, $expectedObject);
//        file_put_contents(
//            sprintf(
//                '%s%sfixture%s%s%s%s-result.serialized',
//                dirname(dirname(dirname(dirname(__FILE__)))),
//                DIRECTORY_SEPARATOR,
//                DIRECTORY_SEPARATOR,
//                $this->getTestCaseId(),
//                DIRECTORY_SEPARATOR,
//                $expectedObject
//            ),
//            serialize($transformed)
//        );
    }

    public function dataProviderTestTransform()
    {
        return array(
            $this->getTransformArguments('artists_13'),
            $this->getTransformArguments('artists_13_releases'),
            $this->getTransformArguments('database_search__q_vibrasphere'),
            $this->getTransformArguments('labels_1518'),
            $this->getTransformArguments('masters_896'),
            $this->getTransformArguments('releases_146'),
        );
    }

    protected function getTransformArguments($fixture)
    {
        $client = new Client;
        $path = '/' . str_replace('_', '/', substr($fixture, 0, strpos($fixture, '__') ?: strlen($fixture)));

        return array(
            $client->convertResponse($this->loadFixture($fixture . '.json')),
            $path,
//            $fixture,
            $this->loadResponse($fixture),
        );
    }

    /**
     * @dataProvider dataProviderTestGet
     */
    public function testGet($path, $transformed, $expected)
    {
        $value = $this->transformer->get($path, $transformed);
        $this->assertEquals($value, $expected);
    }

    public function dataProviderTestGet()
    {
        return array(
            array(
                'namevariations/3',
                $this->loadResponse('artists_13'),
                'Blaze Production Presents James Toney Jr. Project'
            ),
            array(
                'pagination/urls/next',
                $this->loadResponse('artists_13_releases'),
                'http://api.discogs.com/artists/13/releases?per_page=50&page=2'
            ),
            array(
                'results/1/style/0',
                $this->loadResponse('database_search__q_vibrasphere'),
                'Progressive Trance'
            ),
            array(
                'name',
                $this->loadResponse('labels_1518'),
                'Profan'
            )
        );
    }

    /**
     * @expectedException \Discogs\ResponseTransformer\TransformException
     * @expectedExceptionMessage Node does not exist: this/node/does/not/exist
     * @expectedExceptionCode 0
     */
    public function testGet_invalid()
    {
        $this->transformer->get('this/node/does/not/exist', $this->loadResponse('artists_13'));
    }
}
