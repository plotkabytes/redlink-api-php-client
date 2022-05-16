<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Plotkabytes\RedlinkApi\Api\Pushes;
use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

require __DIR__ . '/../vendor/autoload.php';

$client = new DefaultClient();
$client->setAuthentication("ENTER_API_KEY_HERE", "ENTER_APPLICATION_KEY_HERE");

$responseTransformer = new ResponseTransformer();

$response = $client->pushes()->send([
    'applications' => [
        "XXX-XXX-XXX"
    ],
    'to' => [
        [
            'receiver' => 'test@test.pl',
            'externalId' => 'aaa-aaa-aaa',
            'type' => Pushes::EMAIL_RECEIVER
        ]
    ],
    'title' => [
        'pl' => 'test'
    ],
    'body' => [
        'pl' => 'test'
    ],
    'defaultLanguage' => 'pl',
    'image' => 'test.png',
    'silent' => true,
    'sound' => 'test.mp3',
    'externalData' => [
        'a' => 'b'
    ],
    'advanced' => [
        'subtitle' => 'xxx',
        'lockscreenVisibility' => Pushes::LOCKSCREEN_VISIBILITY_PUBLIC,
        'icon' => [
            'small' => 'icon.png',
            'large' => 'icon.png'
        ]
    ],
    'action' => [
        'url' => 'https://web.test',
        'type' => Pushes::ACTION_NONE
    ],
    'ttl' => 1540377351,
    'scheduleTime' => "2020-01-01 12:12:12",
    'actionButtons' => [
        [
            'button' => Pushes::BUTTON_YES,
            'action' => [
                'url' => 'https://web.test',
                'type' => Pushes::ACTION_NONE
            ]
        ]
    ]
]);

$parsedResponse = $responseTransformer->createFromJson($response->getBody());