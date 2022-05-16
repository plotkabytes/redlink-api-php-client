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

// Add domain to blacklist for given smtp account.
$response = $client->blacklists()->addDomain('1.test.smtp', 'test.com');

$response = $client->blacklists()->addEmail('test@test.pl', '1.test.smtp', 'reason from listReasons() method', 'comment');

$response = $client->blacklists()->removeEmail('1.test.smtp', 'test@test.pl');

$response = $client->blacklists()->listDomains('1.test.smtp');

$response = $client->blacklists()->listEmails('1.test.smtp');

$response = $client->blacklists()->listEmails('1.test.smtp', 100, 10);

$response = $client->blacklists()->listReasons();

$response = $client->blacklists()->batchRemoveDomains('1.test.smtp', ['test.com', 'test1.com']);

$response = $client->blacklists()->removeDomain('1.test.smtp', 'test.com');

$response = $client->blacklists()->batchRemoveEmails('1.test.smtp', ['test@test.pl']);

$response = $client->blacklists()->batchAddDomains('1.test.smtp', ['test.com', 'test1.com']);

$parsedResponse = $responseTransformer->createFromJson($response->getBody());