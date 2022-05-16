<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plotkabytes\RedlinkApi\Tests\Plugin;

use Http\Client\Common\Plugin;
use PHPUnit\Framework\TestCase;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Plotkabytes\RedlinkApi\Plugin\Authentication;

class AuthenticationTest extends TestCase
{
    public function testConstructTwoKeys()
    {
        $p = new Authentication('test', 'test');
        $this->assertInstanceOf(Plugin::class, $p);
    }

    public function testConstructSingleKey()
    {
        $p = new Authentication('test');
        $this->assertInstanceOf(Plugin::class, $p);
    }

    public function testConstructWhitespaceApiKey()
    {
        $this->expectException(RuntimeException::class);
        $p = new Authentication('            ');
    }
}
