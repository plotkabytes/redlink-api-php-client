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
use Plotkabytes\RedlinkApi\Utils\ResponseError;
use Plotkabytes\RedlinkApi\Utils\ResponseMeta;
use Plotkabytes\RedlinkApi\Utils\RedlinkResponse;

class RedlinkResponseTest extends TestCase
{
    public function testGetMeta()
    {
        $r = new RedlinkResponse(null, null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals('a', $r->getMeta()->getUniqId());
        $this->assertEquals(0, $r->getMeta()->getNumberOfErrors());
        $this->assertEquals(0, $r->getMeta()->getNumberOfData());
    }

    public function testGetData()
    {
        $r = new RedlinkResponse(null, null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals(null, $r->getData());

        $r = new RedlinkResponse([], null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals([], $r->getData());

        $r = new RedlinkResponse(['t' => 'x'], null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals(['t' => 'x'], $r->getData());
    }

    public function testConstruct()
    {
        $r = new RedlinkResponse(null, null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertInstanceOf(RedlinkResponse::class, $r);
    }

    public function testGetErrors()
    {
        $r = new RedlinkResponse(null, null, new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals(null, $r->getErrors());

        $r = new RedlinkResponse(null, [], new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals([], $r->getErrors());

        $r = new RedlinkResponse(null, [new ResponseError('a', 'b', 0)], new ResponseMeta(0, 0, 0, 'a'));
        $this->assertEquals(0, $r->getErrors()[0]->getCode());
        $this->assertEquals('a', $r->getErrors()[0]->getTitle());
        $this->assertEquals('b', $r->getErrors()[0]->getMessage());
    }
}
