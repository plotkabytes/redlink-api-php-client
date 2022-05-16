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
use Plotkabytes\RedlinkApi\Utils\ResponseMeta;

class ResponseMetaTest extends TestCase
{
    public function testConstruct()
    {
        $meta = new ResponseMeta(1, 1, 200, 'a');
        $this->assertInstanceOf(ResponseMeta::class, $meta);
    }

    /**
     * @covers \Redlink\Utils\ResponseMeta::getUniqId()
     * @covers \Redlink\Utils\ResponseMeta::getStatus()
     * @covers \Redlink\Utils\ResponseMeta::getNumberOfErrors()
     * @covers \Redlink\Utils\ResponseMeta::getNumberOfData()
     */
    public function testAll()
    {
        $meta = new ResponseMeta(1, 1, 200, 'a');
        $this->assertEquals(1, $meta->getNumberOfErrors());
        $this->assertEquals(1, $meta->getNumberOfData());
        $this->assertEquals(200, $meta->getStatus());
        $this->assertEquals('a', $meta->getUniqId());
    }
}
