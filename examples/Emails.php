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

// List all templates using pagination.
$response = $client->emails()->listTemplates("1.test.smtp");

// List all smtp accounts.
$response = $client->emails()->listSmtp();

// List statuses.
$response = $client->emails()->listStatuses("1.test.smtp");

// List clicks.
$response = $client->emails()->listClicks("1.test.smtp");

// List opens.
$response = $client->emails()->listOpens("1.test.smtp");

// Send email.
$response = $client->emails()->send([
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

$parsedResponse = $responseTransformer->createFromJson($response->getBody());