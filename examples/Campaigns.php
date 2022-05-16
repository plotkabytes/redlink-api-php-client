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

$response = $client->campaigns()->getSingleEmail("XXXX");
$response = $client->campaigns()->listEmail();
$response = $client->campaigns()->listEmailRecipients();
$response = $client->campaigns()->listEmailOpens();
$response = $client->campaigns()->listEmailClicks();

$response = $client->campaigns()->getSingleSms("XXXX");
$response = $client->campaigns()->listSMS();
$response = $client->campaigns()->listSmsRecipients("XXXX");
$response = $client->campaigns()->listSmsClicks();

$response = $client->campaigns()->listPush();
$response = $client->campaigns()->listPushRecipients("XXXX");

$parsedResponse = $responseTransformer->createFromJson($response->getBody());