<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plotkabytes\RedlinkApi\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Plotkabytes\RedlinkApi\Exception\DeserializationException;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

class ResponseTransformerTest extends TestCase
{
    public function testCreateFromJsonValidResponseInvalidJson()
    {
        $this->expectException(DeserializationException::class);

        $responseAsString = '{';
        $transformer = new ResponseTransformer();
        $transformer->createFromJson($responseAsString);
    }

    public function testCreateFromJsonValidResponseOnlyErrors()
    {
        $responseAsString = '{"errors": [{"title": "TestTitle","message": "TestMessage","code": "E-4-004"}],"meta": {"numberOfErrors": 1,"numberOfData": 0,"status": 422,"uniqId": "085fa5dabf"}}';

        $transformer = new ResponseTransformer();
        $redlinkResponse = $transformer->createFromJson($responseAsString);

        $this->assertEquals('085fa5dabf', $redlinkResponse->getMeta()->getUniqId());
        $this->assertEquals(422, $redlinkResponse->getMeta()->getStatus());
        $this->assertEquals(0, $redlinkResponse->getMeta()->getNumberOfData());
        $this->assertEquals(1, $redlinkResponse->getMeta()->getNumberOfErrors());

        $this->assertCount(0, $redlinkResponse->getData());
        $this->assertCount(1, $redlinkResponse->getErrors());

        $this->assertEquals('TestTitle', $redlinkResponse->getErrors()[0]->getTitle());
        $this->assertEquals('TestMessage', $redlinkResponse->getErrors()[0]->getMessage());
        $this->assertEquals('E-4-004', $redlinkResponse->getErrors()[0]->getCode());
    }

    public function testCreateFromJsonValidResponseZeroErrors()
    {
        $responseAsString = '{
          "data": [
            {
              "id": 3380,
              "name": "Wszystkie Kontakty",
              "description": null,
              "externalId": "ALLCONTACTS",
              "createdAt": 1551110868
            }
          ],
          "meta": {
            "numberOfErrors": 0,
            "numberOfData": 1,
            "status": 200,
            "uniqId": "085fa5dabf"
          }
        }';

        $transformer = new ResponseTransformer();
        $redlinkResponse = $transformer->createFromJson($responseAsString);

        $this->assertEquals('085fa5dabf', $redlinkResponse->getMeta()->getUniqId());
        $this->assertEquals(200, $redlinkResponse->getMeta()->getStatus());
        $this->assertEquals(1, $redlinkResponse->getMeta()->getNumberOfData());
        $this->assertEquals(0, $redlinkResponse->getMeta()->getNumberOfErrors());

        $this->assertCount(1, $redlinkResponse->getData());
        $this->assertCount(0, $redlinkResponse->getErrors());
    }
}
