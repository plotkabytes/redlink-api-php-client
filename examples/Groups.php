<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

require __DIR__ . '/../vendor/autoload.php';

$client = new DefaultClient();
$client->setAuthentication("ENTER_API_KEY_HERE", "ENTER_APPLICATION_KEY_HERE");

$responseTransformer = new ResponseTransformer();

// Create group
$response = $client->groups()->create("testxx");

// List contacts in group
$response = $client->groups()->listContacts(1);

// Add contacts to group
$response = $client->groups()->addContacts(1, [1111]);

// Remove contacts from group
$response = $client->groups()->removeContacts(1, [1111]);

// Update group
$response = $client->groups()->update(1, "test", "test");

// Remove group
$response = $client->groups()->remove(1);

// Remove multiple groups
$response = $client->groups()->batchRemove([1, 2, 3]);

// Create multiple groups
$response = $client->groups()->batchCreate([
        [
            'name' => 'test1',
            'description' => 'test1',
            'externalId' => 'test1'
        ],
        [
            'name' => 'test2',
            'description' => 'test2',
            'externalId' => 'test2'
        ]
    ]
);

// Batch update
$response = $client->groups()->batchUpdate([
        [
            'id' => 1,
            'name' => 'test1',
            'description' => 'test1'
        ],
        [
            'id' => 2,
            'name' => 'test2',
            'description' => 'test2'
        ]
    ]
);

$parsedResponse = $responseTransformer->createFromJson($response->getBody());