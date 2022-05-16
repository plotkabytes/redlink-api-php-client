<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plotkabytes\RedlinkApi\Tests\Api;

use PHPUnit\Framework\TestCase;
use Plotkabytes\RedlinkApi\Api\AbstractApi;
use Plotkabytes\RedlinkApi\DefaultClient;

class AbstractApiTest extends TestCase
{
    public function testConstructVersion21()
    {
        $mocked = $this->createMock(DefaultClient::class);
        $mocked->method('getAuthorizationType')->willReturn(DefaultClient::AUTHORIZATION_USING_APPKEY);

        $class = new class($mocked) extends AbstractApi {
            public function __construct(DefaultClient $client)
            {
                parent::__construct($client);
            }

            public function getUriPrefix()
            {
                return $this->uriPrefix;
            }
        };

        $this->assertEquals('/v2.1/', $class->getUriPrefix());
    }

    public function testRemoveNullValues()
    {
        $mocked = $this->createMock(DefaultClient::class);

        $class = new class($mocked) extends AbstractApi {
            public function __construct(DefaultClient $client)
            {
                parent::__construct($client);
            }

            public function removeNullValuesExposed($body)
            {
                return $this->removeNullValues($body);
            }
        };

        $this->assertEquals([], $class->removeNullValuesExposed(['test' => null, 'test1' => ['test' => null]]));
        $this->assertEquals(['test' => 'a'], $class->removeNullValuesExposed(['test' => 'a']));
        $this->assertEquals(['test' => 'a'], $class->removeNullValuesExposed(['test' => 'a', 'test1' => null]));
    }

    public function testReplacePlaceHolders()
    {
        $mocked = $this->createMock(DefaultClient::class);

        $class = new class($mocked) extends AbstractApi {
            public function __construct(DefaultClient $client)
            {
                parent::__construct($client);
            }

            public function replacePlaceHoldersExposed($uri, $params)
            {
                return $this->replacePlaceHolders($uri, $params);
            }
        };

        $this->assertEquals('/test/', $class->replacePlaceHoldersExposed('/test/', []));
        $this->assertEquals('/test/', $class->replacePlaceHoldersExposed('/test/', ['test' => 'x']));
        $this->assertEquals('/x/', $class->replacePlaceHoldersExposed('/{test}/', ['test' => 'x']));
        $this->assertEquals('/x/x', $class->replacePlaceHoldersExposed('/{test}/{test}', ['test' => 'x']));
        $this->assertEquals('/1/1', $class->replacePlaceHoldersExposed('/{test}/{test}', ['test' => 1]));
    }
}
