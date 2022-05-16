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
use Symfony\Component\OptionsResolver\OptionsResolver;

class SMSes extends AbstractApi
{
    public const REGULAR_SMS = 0;
    public const FLASH_SMS = 1;

    /**
     * Allows to get list of all SMS statuses.
     *
     * @see http://docs2.redlink.pl/#operation/statuses
     *
     * @throws Exception|RuntimeException
     */
    public function listStatuses(
        int $limit = 100,
        int $offset = 0,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        array $senders = null
    ): ResponseInterface {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
            'sender' => null == $senders ? null : join(',', $senders),
        ];

        return $this->get('sms/statuses', $this->removeNullValues($query));
    }

    /**
     * Allows to get list of senders assigned to the API key.
     *
     * @see https://docs.redlink.pl/#operation/GetSendersId
     *
     * @throws Exception|RuntimeException
     */
    public function listSenders(): ResponseInterface
    {
        return $this->options('sms/senders');
    }

    /**
     * List all SMSes using pagination.
     *
     * @see https://docs.redlink.pl/#operation/GetSmsList
     *
     * @throws Exception|RuntimeException
     */
    public function list(
        ?string $sender = null,
        ?string $phoneNumber = null,
        ?int $status = null,
        ?DateTime $dateTo = null,
        ?DateTime $dateFrom = null,
        int $limit = 100,
        int $offset = 0,
        string $orderBy = 'id',
        string $orderDirection = 'DESC'
    ): ResponseInterface {
        $this->validatePaginationConstraints($limit, $offset, $orderDirection);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
            'status' => $status,
            'sender' => $sender,
            'phoneNumber' => $phoneNumber,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('sms', $this->removeNullValues($query));
    }

    /**
     * Allows to get information's (IMSI, network, networkPorted) about phone number.
     *
     * @see http://docs2.redlink.pl/#operation/Hlr
     *
     * @throws Exception|RuntimeException
     */
    public function verifyNumber(string $phoneNumber): ResponseInterface
    {
        return $this->post('sms/hlr', [], ['phone' => $phoneNumber]);
    }

    /**
     * Allows to send SMSes.
     *
     * @see http://docs2.redlink.pl/#operation/SendSms
     *
     * @throws Exception|RuntimeException
     */
    public function send(array $data): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('sender')
            ->setRequired('sender')
            ->setAllowedTypes('sender', 'string')
            ->setDefined('message')
            ->setRequired('message')
            ->setAllowedTypes('message', 'string')
            ->setDefined('phoneNumbers')
            ->setRequired('phoneNumbers')
            ->setAllowedTypes('phoneNumbers', 'array')
            ->setDefined('validity')
            ->setAllowedTypes('validity', 'integer')
            ->setAllowedValues('validity', function ($value): bool {
                return $value >= 0 && $value <= 4320;
            })
            ->setDefined('scheduleTime')
            ->setAllowedTypes('scheduleTime', 'integer')
            ->setDefined('type')
            ->setAllowedTypes('type', 'integer')
            ->setAllowedValues('type', [
                self::FLASH_SMS,
                self::REGULAR_SMS,
            ])
            ->setDefined('shortLink')
            ->setAllowedTypes('shortLink', 'boolean')
            ->setDefined('webhookUrl')
            ->setAllowedTypes('webhookUrl', 'string')
            ->setDefined('externalId')
            ->setAllowedTypes('externalId', 'string');

        $optionsResolver->resolve($data);

        return $this->post('sms', [], $data);
    }
}
