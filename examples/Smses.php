<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Plotkabytes\RedlinkApi\Api\SMSes;
use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

require __DIR__ . '/../vendor/autoload.php';

$client = new DefaultClient();
$client->setAuthentication("ENTER_API_KEY_HERE", "ENTER_APPLICATION_KEY_HERE");

$responseTransformer = new ResponseTransformer();

// Allows to get information's (IMSI, network, networkPorted) about phone number.
$response = $client->SMSes()->verifyNumber("+48123123123");

// Allows to get list of senders assigned to the API key.
$response = $client->SMSes()->listSenders();

// Allows to list all SMSes using pagination.
$response = $client->SMSes()->list();

// Allows to list all SMSes statuses using pagination.
$response = $client->SMSes()->listStatuses();

// Allows to send SMSes.
$response = $client->SMSes()->send([
    'sender' => 'TEST',
    'message' => 'Hello world!',
    'phoneNumbers' => [
        '+48XXXXXXXXX'
    ],
    'validity' => 4320,
    'scheduleTime' => 0,
    'type' => SMSes::REGULAR_SMS,
    'shortLink' => true,
    'webhookUrl' => 'string',
    'externalId' => 'xxxx-xxxx-xxxx',
]);

$parsedResponse = $responseTransformer->createFromJson($response->getBody());