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

class ResponseErrorTest extends TestCase
{
    public function testAll()
    {
        $error = new ResponseError('a', 'b', 'c');
        $this->assertEquals('a', $error->getTitle());
        $this->assertEquals('b', $error->getMessage());
        $this->assertEquals('c', $error->getCode());
    }
}
