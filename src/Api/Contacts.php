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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

class Contacts extends AbstractApi
{
    public const UPDATE_BY_EXTERNAL_ID = 1;
    public const UPDATE_BY_ID = 2;

    /**
     * List contacts using pagination.
     *
     * @see https://docs.redlink.pl/#operation/ContactsListing
     *
     * @throws Exception|RuntimeException
     */
    public function list(
        ?int $groupId = null,
        ?int $contactId = null,
        ?string $externalId = null,
        ?string $phoneNumber = null,
        ?string $email = null,
        ?bool $inArchive = null,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        $query = [
            'groupId' => $groupId,
            'contactId' => $contactId,
            'externalId' => $externalId,
            'phoneNumber' => $phoneNumber,
            'email' => $email,
            'inArchive' => $inArchive,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'limit' => $limit,
        ];

        return $this->get('contact', $this->removeNullValues($query));
    }

    /**
     * List contacts additional fields using pagination.
     *
     * @see https://docs.redlink.pl/#operation/AdditionalFieldListing
     *
     * @throws Exception|RuntimeException
     */
    public function listAdditionalFields(
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        return $this->get('contact/field', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * List segments using pagination.
     *
     * @see https://docs.redlink.pl/#operation/SegmentsListing
     *
     * @throws Exception|RuntimeException
     */
    public function listSegments(
        int $limit = 100,
        int $offset = 0): ResponseInterface
    {
        $this->validatePaginationConstraints($limit, $offset);

        return $this->get('contact/segment', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Get single segment.
     *
     * @see https://docs.redlink.pl/#operation/GetSingleSegment
     *
     * @throws Exception|RuntimeException
     */
    public function getSingleSegments(int $id): ResponseInterface
    {
        if ($id < 1) {
            throw new RuntimeException('Segment ID must be greater than 0!');
        }

        $uri = $this->replacePlaceHolders('contact/segment/{id}', ['id' => $id]);

        return $this->get($uri);
    }

    /**
     * Get single segment.
     *
     * @see https://docs.redlink.pl/#operation/ShowGroupsOfContact
     *
     * @throws Exception|RuntimeException
     */
    public function listGroups(int $contactId): ResponseInterface
    {
        if ($contactId < 1) {
            throw new RuntimeException('Contacts ID must be greater than 0!');
        }

        $uri = $this->replacePlaceHolders('contact/{id}/group', ['id' => $contactId]);

        return $this->get($uri);
    }

    /**
     * Remove assigned groups from contact.
     *
     * @see https://docs.redlink.pl/#operation/DeleteGroupsFromContact
     *
     * @throws Exception
     */
    public function removeGroup(
        int $contactId,
        int $groupId): ResponseInterface
    {
        if ($contactId < 1) {
            throw new RuntimeException('Contact ID must be greater than 0!');
        }

        if ($groupId < 1) {
            throw new RuntimeException('Group ID must be greater than 0!');
        }

        return $this->batchRemoveGroup($contactId, [$groupId]);
    }

    /**
     * Remove assigned groups from contact.
     *
     * @see https://docs.redlink.pl/#operation/DeleteGroupsFromContact
     *
     * @throws Exception|RuntimeException
     */
    public function batchRemoveGroup(
        int $contactId,
        array $groupIds): ResponseInterface
    {
        if ($contactId < 1) {
            throw new RuntimeException('Contact ID must be greater than 0!');
        }

        if (0 == sizeof($groupIds)) {
            throw new RuntimeException('Groups Ids array cannot be empty!');
        }

        $uri = $this->replacePlaceHolders('contact/{id}/group', ['id' => $contactId]);

        return $this->delete($uri, [], ['id' => $groupIds]);
    }

    /**
     * Remove single contact.
     *
     * @see https://docs.redlink.pl/#operation/DeleteContact
     *
     * @throws Exception|RuntimeException
     */
    public function remove(int $contactId): ResponseInterface
    {
        if ($contactId < 1) {
            throw new RuntimeException('Contacts ID must be greater than 0!');
        }

        return $this->delete('contact', [], ['id' => [$contactId]]);
    }

    /**
     * Assign group to the contact.
     *
     * @see https://docs.redlink.pl/#operation/AddGroupsToContact
     *
     * @throws Exception|RuntimeException
     */
    public function addGroup(
        int $contactId,
        int $groupId): ResponseInterface
    {
        if ($contactId < 1 || $groupId < 1) {
            throw new RuntimeException('Contacts ID must be greater than 0!');
        }

        $url = $this->replacePlaceHolders('contact/{id}/group', ['id' => $contactId]);

        return $this->post($url, [], ['id' => [$groupId]]);
    }

    /**
     * Assign group to the contact.
     *
     * @see https://docs.redlink.pl/#operation/AddGroupsToContact
     *
     * @throws Exception|RuntimeException
     */
    public function batchAddGroup(
        int $contactId,
        array $groupIds): ResponseInterface
    {
        if (0 == sizeof($groupIds)) {
            throw new RuntimeException('Group Ids array cannot be empty!');
        }

        $url = $this->replacePlaceHolders('contact/{id}/group', ['id' => $contactId]);

        return $this->post($url, [], ['id' => $groupIds]);
    }

    /**
     * Remove multiple contacts at once.
     *
     * @see https://docs.redlink.pl/#operation/DeleteContact
     *
     * @throws Exception|RuntimeException
     */
    public function batchRemove(array $contactIds): ResponseInterface
    {
        if (0 == sizeof($contactIds)) {
            throw new RuntimeException('Contacts Ids array cannot be empty!');
        }

        return $this->delete('contact', [], ['id' => $contactIds]);
    }

    /**
     * Unsubscribe multiple contacts at once.
     *
     * @see https://docs.redlink.pl/#operation/DeleteContact
     *
     * @throws Exception|RuntimeException
     */
    public function unsubscribe(array $contactIds): ResponseInterface
    {
        if (0 == sizeof($contactIds)) {
            throw new RuntimeException('Contacts Ids array cannot be empty!');
        }

        return $this->post('contact/unsubscribe', [], ['id' => $contactIds]);
    }

    /**
     * Resubscribe multiple contacts at once.
     *
     * @see https://docs.redlink.pl/#operation/DeleteContact
     *
     * @throws Exception|RuntimeException
     */
    public function resubscribe(array $contactIds): ResponseInterface
    {
        if (0 == sizeof($contactIds)) {
            throw new RuntimeException('Contacts Ids array cannot be empty!');
        }

        return $this->post('contact/resubscribe', [], ['id' => $contactIds]);
    }

    /**
     * Create batch contacts.
     *
     * @see https://docs.redlink.pl/#operation/AddingContacts
     *
     * @throws Exception|RuntimeException
     */
    public function batchCreate(array $body): ResponseInterface
    {
        $optionsResolver = $this->getOptionsResolverForContactCreation();

        foreach ($body as $singleContact) {
            $optionsResolver->resolve($singleContact);
        }

        return $this->post('contact', [], $body);
    }

    /**
     * Helper function.
     */
    private function getOptionsResolverForContactCreation(): OptionsResolver
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('email')
            ->setAllowedTypes('email', 'string')
            ->setAllowedValues('email', $this->createIsValidCallable(
                new Email([
                    'mode' => 'loose',
                    'message' => 'The email "{{ value }}" is not a valid email.',
                ])
            ))
            ->setDefined('externalId')
            ->setAllowedTypes('externalId', 'string')
            ->setAllowedValues('externalId', $this->createIsValidCallable(
                new Length(['max' => 150])
            ))
            ->setDefined('firstName')
            ->setAllowedTypes('firstName', 'string')
            ->setAllowedValues('firstName', $this->createIsValidCallable(
                new Length(['max' => 150])
            ))
            ->setDefined('lastName')
            ->setAllowedTypes('lastName', 'string')
            ->setAllowedValues('lastName', $this->createIsValidCallable(
                new Length(['max' => 150])
            ))
            ->setDefined('phoneNumber')
            ->setAllowedTypes('phoneNumber', 'string')
            ->setAllowedValues('phoneNumber', $this->createIsValidCallable(
                new Regex(['pattern' => '/^\+\d*/'])
            ))
            ->setDefined('companyName')
            ->setAllowedTypes('companyName', 'string')
            ->setAllowedValues('companyName', $this->createIsValidCallable(
                new Length(['max' => 150])
            ))
            ->setDefined('createdAt')
            ->setAllowedTypes('createdAt', 'string')
            ->setAllowedValues('createdAt', $this->createIsValidCallable(
                new Regex(['pattern' => '/^\d{4}-\d{2}-\d{2}\s*(?:\d{2}:\d{2}(?::\d{2})?)?$/'])
            ))
            ->setDefined('addToGroup')
            ->setAllowedTypes('addToGroup', 'array')
            ->setDefined('externalData')
            ->setAllowedTypes('externalData', 'array');

        return $optionsResolver;
    }

    /**
     * See https://github.com/symfony/validator/blob/5.4/Validation.php#L51.
     *
     * @param Constraint ...$constraints
     */
    private function createIsValidCallable(Constraint ...$constraints): callable
    {
        $validator = Validation::createValidator();

        $constraints = \func_get_args();

        return static function ($value, &$violations = null) use ($constraints, $validator) {
            $violations = $validator->validate($value, $constraints);

            return 0 === $violations->count();
        };
    }

    /**
     * Create single contact.
     *
     * @see https://docs.redlink.pl/#operation/AddingContacts
     *
     * @throws Exception|RuntimeException
     */
    public function create(array $body): ResponseInterface
    {
        $optionsResolver = $this->getOptionsResolverForContactCreation();

        $optionsResolver->resolve($body);

        return $this->post('contact', [], [$body]);
    }

    /**
     * Update multiple contacts at single request.
     *
     * @throws Exception|RuntimeException
     */
    public function batchUpdate(array $body): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined(['id', 'externalId'])
            ->setNormalizer('id', function (Options $options, $value) {
                if (null === $value xor null === $options['externalId']) {
                    return $value;
                }

                throw new \InvalidArgumentException('Either "externalId" and "id" are null or both are provided!');
            })
            ->setDefined('data')
            ->setNormalizer('data', function (Options $options, $value): void {
                $dataOptionsResolver = $this->getOptionsResolverForContactCreation();
                $dataOptionsResolver->resolve($value);
            });

        foreach ($body as $singleUpdate) {
            $optionsResolver->resolve($singleUpdate);
        }

        return $this->put('contact', [], $body);
    }

    /**
     * Updates single contact.
     *
     * @see https://docs.redlink.pl/#operation/UpdatingContacts
     *
     * @throws Exception|RuntimeException
     */
    public function update(
        int $updateType,
        string $contactIdOrExternalId,
        array $body): ResponseInterface
    {
        $optionsResolver = $this->getOptionsResolverForContactCreation();

        $optionsResolver->resolve($body);

        switch ($updateType) {
            case self::UPDATE_BY_EXTERNAL_ID:
                $body = [
                    [
                        'externalId' => $contactIdOrExternalId,
                        'data' => $body,
                    ],
                ];
                break;
            case self::UPDATE_BY_ID:
                $body = [
                    [
                        'id' => $contactIdOrExternalId,
                        'data' => $body,
                    ],
                ];
                break;
            default:
                throw new RuntimeException('Invalid update type!');
        }

        return $this->put('contact', [], $body);
    }
}
