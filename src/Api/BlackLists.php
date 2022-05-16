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

use Http\Client\Exception;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

class BlackLists extends AbstractApi
{
    /**
     * List blacklist domains for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/GetDomainsFromBlacklist
     *
     * @throws Exception|RuntimeException
     */
    public function listDomains(string $smtp): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/domain/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->get($uri);
    }

    /**
     * Add domain to blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/AddDomainToBlacklist
     *
     * @throws Exception|RuntimeException
     */
    public function addDomain(
        string $smtp,
        string $domain): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/domain/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->post($uri, [], ['domain' => $domain]);
    }

    /**
     * Add domains to blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/AddDomainToBlacklist
     *
     * @throws Exception|RuntimeException
     */
    public function batchAddDomains(
        string $smtp,
        array $domains): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/domain/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->post($uri, [], $domains);
    }

    /**
     * Remove domain from blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/DeleteBlacklistDomain
     *
     * @throws Exception|RuntimeException
     */
    public function removeDomain(
        string $smtp,
        string $domain): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/domain/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->delete($uri, [], ['id' => [$domain]]);
    }

    /**
     * Remove domains from blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/DeleteBlacklistDomain
     *
     * @throws Exception|RuntimeException
     */
    public function batchRemoveDomains(
        string $smtp,
        array $domains): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/domain/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->delete($uri, [], ['id' => $domains]);
    }

    /**
     * Get list of blacklist reasons.
     *
     * @see https://docs.redlink.pl/#operation/BlacklistReasonsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listReasons(): ResponseInterface
    {
        return $this->options('email/blacklist/reason');
    }

    /**
     * List all blacklisted emails using pagination.
     *
     * @see https://docs.redlink.pl/#operation/BlacklistListing
     *
     * @throws Exception|RuntimeException
     */
    public function listEmails(
        string $smtp,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        return $this->get('email/blacklist', [
            'limit' => $limit,
            'offset' => $offset,
            'smtpAccount' => $smtp,
        ]);
    }

    /**
     * Add email to the blacklist.
     *
     * @see https://docs.redlink.pl/#operation/AddToBlacklist
     *
     * @throws Exception|RuntimeException
     */
    public function addEmail(
        string $email,
        string $smtp,
        string $reason,
        string $comment): ResponseInterface
    {
        $body = [
            'email' => $email,
            'smtpAccount' => $smtp,
            'reason' => $reason,
            'comment' => $comment,
        ];

        $this->removeNullValues($body);

        return $this->post('email/blacklist', [], $body);
    }

    /**
     * Remove emails from blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/DeleteBlacklistDomain
     *
     * @throws Exception|RuntimeException
     */
    public function batchRemoveEmails(
        string $smtp,
        array $emails): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->delete($uri, [], ['id' => $emails]);
    }

    /**
     * Remove emails from blacklist for given smtp account.
     *
     * @see https://docs.redlink.pl/#operation/DeleteBlacklistDomain
     *
     * @throws Exception|RuntimeException
     */
    public function removeEmail(
        string $smtp,
        string $emails): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('email/blacklist/{smtp}', ['smtp' => $smtp]);

        return $this->delete($uri, [], ['id' => [$emails]]);
    }
}
