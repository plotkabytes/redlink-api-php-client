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
use Plotkabytes\RedlinkApi\Api\Pushes;
use Plotkabytes\RedlinkApi\DefaultClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class PushesTest extends TestCase
{
    public function testSendMissingFields()
    {
        $this->expectException(MissingOptionsException::class);

        try {
            $responseMock = $this->createMock(ResponseInterface::class);

            $httpClient = $this->createMock(HttpMethodsClientInterface::class);
            $httpClient->method('post')->willReturn($responseMock);

            $client = new DefaultClient($httpClient);
            $client->pushes()->send([]);
        } catch (MissingOptionsException $e) {
            if (!str_contains($e->getMessage(), 'The required options "applications", "body", "defaultLanguage", "title", "to" are missing')) {
                $this->fail('One of previously defined required fields is missing');
            }
            throw $e;
        }
    }

    public function testSendNestedInvalid()
    {
        $this->expectException(MissingOptionsException::class);

        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClient = $this->createMock(HttpMethodsClientInterface::class);
        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->pushes()->send([
            'applications' => [
                'XXX-XXX-XXX',
            ],
            'to' => [
                [
                    'receiver' => 'test@test.pl',
                ],
            ],
            'title' => [
                'pl' => 'test',
            ],
            'body' => [
                'pl' => 'test',
            ],
            'defaultLanguage' => 'pl',
            'image' => 'test.png',
            'silent' => true,
            'sound' => 'test.mp3',
            'externalData' => [
                'a' => 'b',
            ],
            'advanced' => [
                'subtitle' => 'xxx',
                'lockscreenVisibility' => Pushes::LOCKSCREEN_VISIBILITY_PUBLIC,
                'icon' => [
                    'small' => 'icon.png',
                    'large' => 'icon.png',
                ],
            ],
            'action' => [
                'url' => 'https://web.test',
                'type' => Pushes::ACTION_NONE,
            ],
            'ttl' => 1540377351,
            'scheduleTime' => '2020-01-01 12:12:12',
            'actionButtons' => [
                [
                    'button' => Pushes::BUTTON_YES,
                    'action' => [
                        'url' => 'https://web.test',
                        'type' => Pushes::ACTION_NONE,
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
        $client->pushes()->send([
            'applications' => [
                'XXX-XXX-XXX',
            ],
            'to' => [
                [
                    'receiver' => 'test@test.pl',
                    'externalId' => 'aaa-aaa-aaa',
                    'type' => Pushes::EMAIL_RECEIVER,
                ],
            ],
            'title' => [
                'pl' => 'test',
            ],
            'body' => [
                'pl' => 'test',
            ],
            'defaultLanguage' => 'pl',
            'image' => 'test.png',
            'silent' => true,
            'sound' => 'test.mp3',
            'externalData' => [
                'a' => 'b',
            ],
            'advanced' => [
                'subtitle' => 'xxx',
                'lockscreenVisibility' => Pushes::LOCKSCREEN_VISIBILITY_PUBLIC,
                'icon' => [
                    'small' => 'icon.png',
                    'large' => 'icon.png',
                ],
            ],
            'action' => [
                'url' => 'https://web.test',
                'type' => Pushes::ACTION_NONE,
            ],
            'ttl' => 1540377351,
            'scheduleTime' => '2020-01-01 12:12:12',
            'actionButtons' => [
                [
                    'button' => Pushes::BUTTON_YES,
                    'action' => [
                        'url' => 'https://web.test',
                        'type' => Pushes::ACTION_NONE,
                    ],
                ],
            ],
        ]);

        $this->assertTrue(true);
    }
}
