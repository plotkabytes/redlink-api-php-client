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
use Symfony\Component\OptionsResolver\OptionsResolver;

class Groups extends AbstractApi
{
    /**
     * List all groups using pagination.
     *
     * @see https://docs.redlink.pl/#operation/ListGroups
     *
     * @throws Exception|RuntimeException
     */
    public function list(
        int $limit = 100,
        int $offset = 0,
        string $orderBy = 'id',
        string $orderDirection = 'DESC'): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset, $orderDirection);

        return $this->get('group', [
            'limit' => $limit,
            'offset' => $offset,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
    }

    /**
     * List contacts in group using pagination.
     *
     * @see https://docs.redlink.pl/#operation/ListContactsInGroup
     *
     * @throws Exception|RuntimeException
     */
    public function listContacts(
        int $groupId,
        int $limit = 100,
        int $offset = 0,
        string $orderBy = 'id',
        string $orderDirection = 'DESC'): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset, $orderDirection);

        $uri = $this->replacePlaceHolders('group/{groupId}/contact', ['groupId' => $groupId]);

        return $this->get($uri, [
            'limit' => $limit,
            'offset' => $offset,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
    }

    /**
     * Add contacts ids to the group.
     *
     * @see https://docs.redlink.pl/#operation/AddContactToGroup
     *
     * @throws Exception|RuntimeException
     */
    public function addContacts(
        int $groupId,
        array $contactsIds): ResponseInterface
    {
        if (0 == sizeof($contactsIds)) {
            throw new RuntimeException('Contacts Ids array cannot be empty!');
        }

        $uri = $this->replacePlaceHolders('group/{groupId}/contact', ['groupId' => $groupId]);

        return $this->post($uri, [], ['id' => $contactsIds]);
    }

    /**
     * Remove contacts ids from the group.
     *
     * @see https://docs.redlink.pl/#operation/DeleteContactFromGroup
     *
     * @throws Exception|RuntimeException
     */
    public function removeContacts(
        int $groupId,
        array $contactsIds): ResponseInterface
    {
        if (0 == sizeof($contactsIds)) {
            throw new RuntimeException('Contacts Ids array cannot be empty!');
        }

        $uri = $this->replacePlaceHolders('group/{groupId}/contact', ['groupId' => $groupId]);

        return $this->delete($uri, [], ['id' => $contactsIds]);
    }

    /**
     * Get number of contacts in group. Asynchronous method.
     *
     * @see https://docs.redlink.pl/#operation/CountContactsInGroup
     *
     * @throws Exception|RuntimeException
     */
    public function countContacts(int $groupId): ResponseInterface
    {
        $uri = $this->replacePlaceHolders('group/{groupId}/contact', ['groupId' => $groupId]);

        // TODO handle async

        return $this->get($uri);
    }

    /**
     * Create multiple groups at once.
     *
     * @see https://docs.redlink.pl/#operation/CreateGroup
     *
     * @throws Exception|RuntimeException
     */
    public function batchCreate(array $groups): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('name')
            ->setAllowedTypes('name', 'string')
            ->setRequired('name');

        $optionsResolver->setDefined('externalId')
            ->setAllowedTypes('externalId', 'string')
            ->setRequired('externalId');

        $optionsResolver
            ->setDefined('description')
            ->setAllowedTypes('description', 'string');

        foreach ($groups as $group) {
            $optionsResolver->resolve($group);
            $this->removeNullValues($group);
        }

        return $this->post('group', [], $groups);
    }

    /**
     * Create single group.
     *
     * @see https://docs.redlink.pl/#operation/CreateGroup
     *
     * @throws Exception|RuntimeException
     */
    public function create(
        string $name,
        ?string $description = null,
        ?string $externalId = null): ResponseInterface
    {
        if (null == $externalId && null != $this->client->getExternalIdGenerator()) {
            $externalId = $this->client->getExternalIdGenerator()->generate(16);
        }

        $body = [
            'name' => $name,
            'description' => $description,
            'externalId' => $externalId,
        ];

        return $this->post('group', [], $this->removeNullValues($body));
    }

    /**
     * Update single group.
     *
     * @see https://docs.redlink.pl/#operation/UpdateGroup
     *
     * @throws Exception|RuntimeException
     */
    public function update(
        int $id,
        ?string $name = null,
        ?string $description = null): ResponseInterface
    {
        $body = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
        ];

        return $this->put('group', [], $this->removeNullValues($body));
    }

    /**
     * Update multiple groups at once.
     *
     * @see https://docs.redlink.pl/#operation/UpdateGroup
     *
     * @throws Exception|RuntimeException
     */
    public function batchUpdate(array $groups): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('name')
            ->setAllowedTypes('name', 'string');

        $optionsResolver
            ->setDefined('description')
            ->setAllowedTypes('description', 'string');

        foreach ($groups as $group) {
            $optionsResolver->resolve($group);
            $this->removeNullValues($group);
        }

        return $this->put('group', [], $groups);
    }

    /**
     * Remove single group.
     *
     * @see https://docs.redlink.pl/#operation/DeleteGroups
     *
     * @throws Exception|RuntimeException
     */
    public function remove(int $groupId): ResponseInterface
    {
        return $this->delete('group', [], ['id' => [$groupId]]);
    }

    /**
     * Remove group(s).
     *
     * @see https://docs.redlink.pl/#operation/DeleteGroups
     *
     * @param int[] $groupIds
     *
     * @throws Exception|RuntimeException
     */
    public function batchRemove(array $groupIds): ResponseInterface
    {
        return $this->delete('group', [], ['id' => $groupIds]);
    }
}
