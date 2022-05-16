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
use Plotkabytes\RedlinkApi\DefaultClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class EmailsTest extends TestCase
{
    public function testSendMissingFields()
    {
        $this->expectException(MissingOptionsException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->emails()->send([]);
    }

    public function testSendNestedInvalid()
    {
        $this->expectException(MissingOptionsException::class);

        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClient = $this->createMock(HttpMethodsClientInterface::class);
        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->emails()->send([
            'subject' => 'Test email subject',
            'smtpAccount' => '1.test.smtp',
            'tags' => [
                'test-tag',
            ],
            'content' => [
                'html' => '<h1>Hello world</h1>',
                'text' => 'Hello world',
                'templateId' => 'as2sCwq',
            ],
            'bcc' => [
                [
                    'email' => 'string',
                    'name' => 'string',
                ],
            ],
            'cc' => [
                [
                    'email' => 'string',
                    'name' => 'string',
                ],
            ],
            'from' => [
                'email' => 'string',
                'name' => 'string',
            ],
            'replyTo' => [
                'email' => 'string',
                'name' => 'string',
            ],
            'headers' => [
                'X-TEST-HEADER' => 'val',
            ],
            'to' => [
                [
                    'email' => 'test@domena.pl',
                    'vars' => [
                        'test-var' => 'var-value',
                    ],
                ],
            ],
        ]);
    }

    public function testSendValid()
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClient = $this->createMock(HttpMethodsClientInterface::class);
        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->emails()->send([
            'subject' => 'Test email subject',
            'smtpAccount' => '1.test.smtp',
            'tags' => [
                'test-tag',
            ],
            'content' => [
                'html' => '<h1>Hello world</h1>',
                'text' => 'Hello world',
                'templateId' => 'as2sCwq',
            ],
            'bcc' => [
                [
                    'email' => 'string',
                    'name' => 'string',
                ],
            ],
            'cc' => [
                [
                    'email' => 'string',
                    'name' => 'string',
                ],
            ],
            'from' => [
                'email' => 'string',
                'name' => 'string',
            ],
            'replyTo' => [
                'email' => 'string',
                'name' => 'string',
            ],
            'headers' => [
                'X-TEST-HEADER' => 'val',
            ],
            'to' => [
                [
                    'email' => 'test@domena.pl',
                    'name' => 'Test sender',
                    'messageId' => 'test0001@domena.pl',
                    'vars' => [
                        'test-var' => 'var-value',
                    ],
                ],
            ],
        ]);

        $this->assertTrue(true);
    }
}
