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

use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;
use Plotkabytes\RedlinkApi\Api\SMSes;
use Plotkabytes\RedlinkApi\DefaultClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class SMSesTest extends TestCase
{
    public function testSendMissingFields()
    {
        $this->expectException(MissingOptionsException::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClient = $this->createMock(HttpMethodsClientInterface::class);
        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->SMSes()->send([]);
    }

    public function testSendValid()
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClient = $this->createMock(HttpMethodsClientInterface::class);
        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->SMSes()->send([
            'sender' => 'TEST',
            'message' => 'Hello world!',
            'phoneNumbers' => [
                '+48XXXXXXXXX',
            ],
            'validity' => 4320,
            'scheduleTime' => 0,
            'type' => SMSes::REGULAR_SMS,
            'shortLink' => true,
            'webhookUrl' => 'string',
            'externalId' => 'xxxx-xxxx-xxxx',
        ]);

        $this->assertTrue(true);
    }
}
