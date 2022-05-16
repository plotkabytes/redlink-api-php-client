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
use Plotkabytes\RedlinkApi\Api\Contacts;
use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

class ContactsTest extends TestCase
{
    public function testGetSingleSegmentDoNotAllowIdLessThanOne()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('get')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->getSingleSegments(-1);
    }

    public function testBatchRemoveGroupDoNotAllowEmptyInput()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('delete')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->batchRemoveGroup(1, []);
    }

    public function testBatchCreateOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->contacts()->batchCreate([
            [
                'companyName' => 'Example company',
                'createdAt' => '2019-02-01 20:12:12',
                'email' => 'test@test.pl',
                'externalId' => 'XXX-XXX-XXX',
                'firstName' => 'First name',
                'lastName' => 'Last name',
                'phoneNumber' => '+48123123123',
            ],
            [
                'companyName' => 'Example company',
                'createdAt' => '2019-02-01 20:12:12',
                'email' => 'test@test.pl',
                'externalId' => 'XXX-XXX-XXX',
                'firstName' => 'First name',
                'lastName' => 'Last name',
                'phoneNumber' => '+48123123123',
            ],
        ]);

        $this->assertTrue(true);
    }

    public function testCreateOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->contacts()->create([
            'companyName' => 'Example company',
            'createdAt' => '2019-02-01 20:12:12',
            'email' => 'test@test.pl',
            'externalId' => 'XXX-XXX-XXX',
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'phoneNumber' => '+48123123123',
        ]);

        $this->assertTrue(true);
    }

    public function testBatchAddGroupDoNotAllowEmptyInput()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->batchAddGroup(1, []);
    }

    public function testBatchRemoveDoNotAllowIdLessThanOne()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('delete')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->batchRemoveGroup(-1, [1, 2, 3]);
    }

    public function testRemoveDoNotAllowIdLessThanOne()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('delete')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->remove(-1);
    }

    public function testAddGroupDoNotAllowIdLessThanOne()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->addGroup(-1, -1);
    }

    public function testUnsubscribeOk()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->unsubscribe([]);
    }

    public function testResubscribeOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('post')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $client->contacts()->resubscribe([1, 2, 3]);

        $this->assertTrue(true);
    }

    public function testBatchUpdateOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('put')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);

        $response = $client->contacts()->batchUpdate([
            [
                'externalId' => 'bfa0b1b1-e636-b8ab-aba4-78a913be0144',
                'data' => [
                    'companyName' => 'Example company',
                    'createdAt' => '2019-02-01 20:12:12',
                    'email' => 'test@test.pl',
                    'externalId' => 'XXX-XXX-XXX',
                    'firstName' => 'First name',
                    'lastName' => 'Last name',
                    'phoneNumber' => '+48123123123',
                    'externalData' => [
                        'test' => 'test',
                        'createdAt' => '1989-09-28',
                        'isClient' => false,
                    ],
                ],
            ],
            [
                'externalId' => 'bfa0b1b1-e636-b8ab-aba4-78a913be0145',
                'data' => [
                    'companyName' => 'Example company',
                    'createdAt' => '2019-02-01 20:12:12',
                    'email' => 'test1@test.pl',
                    'externalId' => 'XXX-XXX-XXX',
                    'firstName' => 'First name',
                    'lastName' => 'Last name',
                    'phoneNumber' => '+48123123124',
                    'externalData' => [
                        'test' => 'test',
                        'createdAt' => '1989-09-28',
                        'isClient' => false,
                    ],
                ],
            ],
        ]);

        $this->assertTrue(true);
    }

    public function testUpdateMissingOptions()
    {
        $this->expectException(RuntimeException::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('put')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->contacts()->update(10, 'XX-XX', []);
    }

    public function testUpdateByExternalIdOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('put')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->contacts()->update(Contacts::UPDATE_BY_EXTERNAL_ID, 'XX-XX', [
            'companyName' => 'Example company',
            'createdAt' => '2019-02-01 20:12:12',
            'email' => 'test@test.pl',
            'externalId' => 'XXX-XXX-XXX',
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'phoneNumber' => '+48123123123',
        ]);

        $this->assertTrue(true);
    }

    public function testUpdateByIdOk()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $httpClient = $this->createMock(HttpMethodsClientInterface::class);

        $httpClient->method('put')->willReturn($responseMock);

        $client = new DefaultClient($httpClient);
        $client->contacts()->update(Contacts::UPDATE_BY_ID, '1', [
            'companyName' => 'Example company',
            'createdAt' => '2019-02-01 20:12:12',
            'email' => 'test@test.pl',
            'externalId' => 'XXX-XXX-XXX',
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'phoneNumber' => '+48123123123',
        ]);

        $this->assertTrue(true);
    }
}
