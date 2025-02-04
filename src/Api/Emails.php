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

class Emails extends AbstractApi
{
    /**
     * List all templates using pagination.
     *
     * @see https://docs.redlink.pl/#operation/GetTemplatesList
     *
     * @throws Exception|RuntimeException
     */
    public function listTemplates(
        string $smtp,
        ?string $externalId = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'externalId' => $externalId,
            'smtpAccount' => $smtp,
        ];

        return $this->get('email/template', $this->removeNullValues($query));
    }

    /**
     * Add template.
     *
     * @see https://docs.redlink.pl/#operation/AddingTemplates
     *
     * @throws Exception|RuntimeException
     */
    public function addTemplate(
        string $html,
        string $text,
        string $name,
        string $externalId,
        string $smtpAccount): ResponseInterface
    {
        return $this->post('email/template', [], [
            'html' => $html,
            'text' => $text,
            'name' => $name,
            'externalId' => $externalId,
            'smtpAccount' => $smtpAccount,
        ]);
    }

    /**
     * Remove template.
     *
     * @see https://docs.redlink.pl/#operation/DeleteTemplates
     *
     * @throws Exception|RuntimeException
     */
    public function removeTemplate(string $externalId): ResponseInterface
    {
        return $this->delete('email/template', [], [
            'externalId' => [$externalId],
        ]);
    }

    /**
     * List all smtp accounts.
     *
     * @see https://docs.redlink.pl/#operation/GetSmtpAccounts
     *
     * @throws Exception
     * @throws Exception|RuntimeException
     */
    public function listSmtp(): ResponseInterface
    {
        return $this->options('email/smtpAccount');
    }

    /**
     * List clicks.
     *
     * @see https://docs.redlink.pl/#operation/EmailClicks
     *
     * @throws Exception|RuntimeException
     */
    public function listClicks(
        string $smtp,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        ?string $messageId = null,
        ?int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'messageId' => $messageId,
            'smtpAccount' => $smtp,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('email/click', $this->removeNullValues($query));
    }

    /**
     * List opens.
     *
     * @see https://docs.redlink.pl/#operation/GetOpens
     *
     * @throws Exception|RuntimeException
     */
    public function listOpens(
        string $smtp,
        DateTime $dateFrom = null,
        DateTime $dateTo = null,
        ?string $messageId = null,
        ?int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);
        $this->validateDateTimeConstraints($dateFrom, $dateTo);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'messageId' => $messageId,
            'smtpAccount' => $smtp,
            'dateFrom' => null == $dateFrom ? null : $dateFrom->format('YYYY-MM-DD hh:mm:ii'),
            'dateTo' => null == $dateTo ? null : $dateTo->format('YYYY-MM-DD hh:mm:ii'),
        ];

        return $this->get('email/open', $this->removeNullValues($query));
    }

    /**
     * List statuses.
     *
     * @see https://docs.redlink.pl/#operation/GetEmailStatuses
     *
     * @throws Exception|RuntimeException
     */
    public function listStatuses(
        string $smtp,
        ?string $to = null,
        ?string $messageId = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        $query = [
            'limit' => $limit,
            'offset' => $offset,
            'messageId' => $messageId,
            'smtpAccount' => $smtp,
            'to' => $to,
        ];

        return $this->get('email', $this->removeNullValues($query));
    }

    /**
     * Send email.
     *
     * @see https://docs.redlink.pl/#operation/SendEmails
     *
     * @throws Exception|RuntimeException
     */
    public function send(array $data): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('subject')
            ->setAllowedTypes('subject', 'string')
            ->setRequired('subject')
            ->setDefined('smtpAccount')
            ->setAllowedTypes('smtpAccount', 'string')
            ->setRequired('smtpAccount')
            ->setDefined('tags')
            ->setAllowedTypes('tags', 'array')
            ->setDefined('content')
            ->setAllowedTypes('content', 'array')
            ->setRequired('content')
            ->setDefault('content', function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefined('html')
                    ->setAllowedTypes('html', 'string')
                    ->setDefined('text')
                    ->setAllowedTypes('text', 'string')
                    ->setDefined('templateId')
                    ->setAllowedTypes('templateId', 'string');
            })
            ->setDefined('bcc')
            ->setAllowedTypes('bcc', 'array[]')
            ->setDefined('cc')
            ->setAllowedTypes('cc', 'array[]')
            ->setDefined('from')
            ->setRequired('from')
            ->setAllowedTypes('from', 'array')
            ->setDefault('from', function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefined('email')
                    ->setAllowedTypes('email', 'string')
                    ->setDefined('name')
                    ->setAllowedTypes('name', 'string');
            })
            ->setDefined('replyTo')
            ->setAllowedTypes('replyTo', 'array')
            ->setDefault('replyTo', function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefined('email')
                    ->setAllowedTypes('email', 'string')
                    ->setDefined('name')
                    ->setAllowedTypes('name', 'string');
            })
            ->setDefined('headers')
            ->setAllowedTypes('headers', 'array')
            ->setDefined('to')
            ->setRequired('to')
            ->setAllowedValues('to', function (array &$elements): bool {
                $subResolver = new OptionsResolver();
                $subResolver
                    ->setDefined('email')
                    ->setAllowedTypes('email', 'string')
                    ->setRequired('email')
                    ->setDefined('name')
                    ->setAllowedTypes('name', 'string')
                    ->setDefined('messageId')
                    ->setAllowedTypes('messageId', 'string')
                    ->setRequired('messageId')
                    ->setDefined('vars')
                    ->setAllowedTypes('vars', 'array');

                $elements = array_map([$subResolver, 'resolve'], $elements);

                return true;
            })
          ->setDefined('attachments');

        $optionsResolver->resolve($data);

        return $this->post('email', [], $data);
    }
}
