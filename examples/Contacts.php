<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Plotkabytes\RedlinkApi\Api\Contacts;
use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

require __DIR__ . '/../vendor/autoload.php';

$client = new DefaultClient();
$client->setAuthentication("ENTER_API_KEY_HERE", "ENTER_APPLICATION_KEY_HERE");

$responseTransformer = new ResponseTransformer();

$response = $client->contacts()->list();
$response = $client->contacts()->listAdditionalFields();
$response = $client->contacts()->getSingleSegments(1);
$response = $client->contacts()->listGroups(1);
$response = $client->contacts()->listSegments();

$response = $client->contacts()->remove(1);
$response = $client->contacts()->removeGroup(1, 1);
$response = $client->contacts()->batchRemove([1, 2, 3, 4]);
$response = $client->contacts()->batchRemoveGroup(1, [1, 2, 3, 4]);
$response = $client->contacts()->batchAddGroup(1, [1, 2, 3, 4]);
$response = $client->contacts()->addGroup(1, 1);
$response = $client->contacts()->unsubscribe([1]);
$response = $client->contacts()->resubscribe([1]);

$response = $client->contacts()->update(Contacts::UPDATE_BY_EXTERNAL_ID, "XXX-XXXX-XXXX", [
    'companyName' => 'Example company',
    'createdAt' => '2019-02-01 20:12:12',
    'email' => 'test@test.pl',
    'externalId' => 'XXX-XXX-XXX',
    'firstName' => 'First name',
    'lastName' => 'Last name',
    'phoneNumber' => '123123123',
    'externalData' => [
        'test' => 'test',
        'createdAt' => '1989-09-28',
        'isClient' => false,
    ]
]);

$response = $client->contacts()->update(Contacts::UPDATE_BY_ID, "123", [
    'companyName' => 'Example company',
    'createdAt' => '2019-02-01 20:12:12',
    'email' => 'test@test.pl',
    'externalId' => 'XXX-XXX-XXX',
    'firstName' => 'First name',
    'lastName' => 'Last name',
    'phoneNumber' => '123123123',
    'externalData' => [
        'test' => 'test',
        'createdAt' => '1989-09-28',
        'isClient' => false,
    ]
]);

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
            'phoneNumber' => '123123123',
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
            'phoneNumber' => '123123123',
            'externalData' => [
                'test' => 'test',
                'createdAt' => '1989-09-28',
                'isClient' => false,
            ],
        ],
    ]
]);

$response = $client->contacts()->batchCreate([
    [
        'companyName' => 'Example company',
        'createdAt' => '2019-02-01 20:12:12',
        'email' => 'test@test.pl',
        'externalId' => 'XXX-XXX-XXX',
        'firstName' => 'First name',
        'lastName' => 'Last name',
        'phoneNumber' => '123123123',
        'externalData' => [
            'test' => 'test',
            'createdAt' => '1989-09-28',
            'isClient' => false,
        ],
        'addToGroup' => [
            0
        ]
    ],
    [
        'companyName' => 'Example company',
        'createdAt' => '2019-02-01 20:12:12',
        'email' => 'test@test.pl',
        'externalId' => 'XXX-XXX-XXX',
        'firstName' => 'First name',
        'lastName' => 'Last name',
        'phoneNumber' => '123123123',
        'externalData' => [
            'test' => 'test',
            'createdAt' => '1989-09-28',
            'isClient' => false,
        ],
        'addToGroup' => [
            0
        ]
    ]
]);

$response = $client->contacts()->create([
    'companyName' => 'Example company',
    'createdAt' => '2019-02-01 20:12:12',
    'email' => 'test@test.pl',
    'externalId' => 'XXX-XXX-XXX',
    'firstName' => 'First name',
    'lastName' => 'Last name',
    'phoneNumber' => '123123123',
    'externalData' => [
        'test' => 'test',
        'createdAt' => '1989-09-28',
        'isClient' => false,
    ],
    'addToGroup' => [
        1
    ]
]);

$parsedResponse = $responseTransformer->createFromJson($response->getBody());