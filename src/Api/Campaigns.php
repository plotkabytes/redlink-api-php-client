<?php

/*
 * This file is part of the Redlink PHP API Client.
 *
 * (c) Mateusz Żyła <mateusz.zylaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Plotkabytes\RedlinkApi\Api;

use DateTime;
use Http\Client\Exception;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

class Campaigns extends AbstractApi
{
    public const CAMPAIGN_STATE_SENDABLE = 'sendable';
    public const CAMPAIGN_STATE_CANCELED = 'canceled';

    /**
     * Allows downloading single email campaign.
     *
     * @see https://docs.redlink.pl/#operation/GettingSingleEmailCampaign
     *
     * @throws Exception|RuntimeException
     */
    public function getSingleEmail(string $campaignId): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('campaign/email/{id}', ['id' => $campaignId]);

        return $this->get($uri);
    }

    /**
     * Allows downloading single sms campaign.
     *
     * @see https://docs.redlink.pl/#operation/GettingSingleSmsCampaign
     *
     * @throws Exception|RuntimeException
     */
    public function getSingleSms(string $campaignId): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('campaign/sms/{id}', ['id' => $campaignId]);

        return $this->get($uri);
    }

    /**
     * List email campaigns using pagination.
     *
     * @see https://docs.redlink.pl/#operation/EmailCampaignListing
     *
     * @throws Exception|RuntimeException
     */
    public function listEmail(
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('campaign/email', $this->removeNullValues($query));
    }

    /**
     * List sms campaigns using pagination.
     *
     * @see https://docs.redlink.pl/#operation/SmsCampaignRecipientsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listSMS(
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('campaign/sms', $this->removeNullValues($query));
    }

    /**
     * List push campaigns using pagination.
     *
     * @see https://docs.redlink.pl/#operation/PushCampaignListing
     *
     * @throws Exception|RuntimeException
     */
    public function listPush(
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('campaign/push', $this->removeNullValues($query));
    }

    /**
     * List SMS campaign clicks.
     *
     * @see https://docs.redlink.pl/#operation/SmsCampaignClickListing
     *
     * @throws Exception|RuntimeException
     */
    public function listSmsClicks(
        ?string $campaignId = null,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        $uri = $this->replacePlaceHolders('campaign/sms/{campaignId}/report/click', ['campaignId' => $campaignId]);

        return $this->get($uri, $this->removeNullValues($query));
    }

    /**
     * List email campaign clicks.
     *
     * @see https://docs.redlink.pl/#operation/EmailCampaignClickListing
     *
     * @throws Exception|RuntimeException
     */
    public function listEmailClicks(
        ?string $campaignId = null,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        $uri = $this->replacePlaceHolders('campaign/email/{campaignId}/report/click', ['campaignId' => $campaignId]);

        return $this->get($uri, $this->removeNullValues($query));
    }

    /**
     * List email campaign opens.
     *
     * @see https://docs.redlink.pl/#operation/EmailCampaignOrListing
     *
     * @throws Exception|RuntimeException
     */
    public function listEmailOpens(
        ?string $campaignId = null,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        $uri = $this->replacePlaceHolders('campaign/email/{campaignId}/report/open', ['campaignId' => $campaignId]);

        return $this->get($uri, $this->removeNullValues($query));
    }

    /**
     * List email campaign recipients.
     *
     * @see https://docs.redlink.pl/#operation/EmailCampaignRecipientsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listEmailRecipients(
        ?string $campaignId = null,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0,
        ?bool $bouncesOnly = null): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
            'campaignId' => $campaignId,
            'bouncesOnly' => $bouncesOnly,
        ];

        return $this->get('campaign/email/report/recipient', $this->removeNullValues($query));
    }

    /**
     * List sms campaign recipients.
     *
     * @see https://docs.redlink.pl/#operation/SmsCampaignRecipientsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listSmsRecipients(
        string $campaignId,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        $uri = $this->replacePlaceHolders('campaign/sms/{campaignId}/report/recipient', ['campaignId' => $campaignId]);

        return $this->get($uri, $this->removeNullValues($query));
    }

    /**
     * List push campaign recipients.
     *
     * @see https://docs.redlink.pl/#operation/PushCampaignRecipientsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listPushRecipients(
        string $externalId,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        $uri = $this->replacePlaceHolders('campaign/push/{externalId}/report/recipient', ['externalId' => $externalId]);

        return $this->get($uri, $this->removeNullValues($query));
    }

    /**
     * List push campaign recipients.
     *
     * @see http://docs.redlink.pl/#operation/ChangeStateOfEmailCampaign
     *
     * @throws Exception|RuntimeException
     */
    public function updateEmailCampaignState(
        string $campaignId,
        ?string $state = null,
        ?DateTime $scheduleTime = null,
        ?array $testAddresses = null): ResponseInterface
    {
        // TODO test

        if (0 === strlen($campaignId)) {
            throw new RuntimeException('Parameter \'campaignId\' cannot be empty!');
        }

        if (!in_array($state, [self::CAMPAIGN_STATE_CANCELED, self::CAMPAIGN_STATE_SENDABLE], true)) {
            throw new RuntimeException('Parameter \'state\' must be either self::CAMPAIGN_STATE_CANCELED or self::CAMPAIGN_STATE_SENDABLE!');
        }

        $uri = $this->replacePlaceHolders('campaign/mail/{campaignId}', ['campaignId' => $campaignId]);

        return $this->patch($uri, [], $this->removeNullValues([
            'scheduleTime' => null == $scheduleTime ? null : $scheduleTime->format('YYYY-MM-DD hh:mm:ii'),
            'testAddresses' => $testAddresses,
        ]));
    }

    // TODO implement
    // http://docs.redlink.pl/#operation/EmailCampaignSending
    // http://docs2.redlink.pl/#operation/EmailCampaignUnsubscribeListing
    // http://docs2.redlink.pl/#operation/SmsCampaignStateReport
}
