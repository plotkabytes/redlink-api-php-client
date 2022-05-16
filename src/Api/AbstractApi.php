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
use Plotkabytes\RedlinkApi\DefaultClient;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Validation;

abstract class AbstractApi
{
    /**
     * The URI prefix.
     *
     * @var string
     */
    protected $uriPrefix = '/v2.1/';

    /**
     * The client instance.
     *
     * @var DefaultClient
     */
    protected $client;

    /**
     * Create a new API instance.
     *
     * @return void
     */
    public function __construct(DefaultClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri     uri to the method that should be called
     * @param array  $params  uri params that should be added
     * @param array  $headers headers for the request
     *
     * @throws Exception
     */
    protected function get(
        string $uri,
        array $params = [],
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        return $this->client->getHttpClient()->get($uri, $headers);
    }

    /**
     * Binding query params (after ? sign ;)).
     */
    private function bindUriParams(
        string $uri,
        array $params): string
    {
        $urlEncodedParams = http_build_query($params);

        if ('' != $urlEncodedParams) {
            return $this->uriPrefix.$uri.'?'.$urlEncodedParams;
        }

        return $this->uriPrefix.$uri;
    }

    /**
     * @param string     $uri     uri to the method that should be called
     * @param array      $params  uri params that should be added
     * @param array|null $body    body for the request
     * @param array      $headers headers for the request
     *
     * @throws Exception
     */
    protected function put(
        string $uri,
        array $params = [],
        ?array $body = null,
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        $preparedBody = $this->encodeBody($body);

        return $this->client->getHttpClient()->put($uri, $headers, $preparedBody);
    }

    /**
     * Prepare body for request.
     *
     * @return string|null
     *
     * @throws RuntimeException
     */
    protected function encodeBody(?array $body)
    {
        if (null == $body) {
            return null;
        }

        if (($preparedBody = json_encode($body)) == false) {
            throw new RuntimeException('Error while encoding request body. Function json_encode returned false.');
        }

        return $preparedBody;
    }

    /**
     * @param string     $uri     uri to the method that should be called
     * @param array      $params  uri params that should be added
     * @param array|null $body    body for the request
     * @param array      $headers headers for the request
     *
     * @throws Exception
     */
    protected function post(
        string $uri,
        array $params = [],
        ?array $body = null,
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        $preparedBody = json_encode($body);

        if (false === $preparedBody) {
            throw new RuntimeException('Error while encoding request body. Function json_encode returned false.');
        }

        return $this->client->getHttpClient()->post($uri, $headers, $preparedBody);
    }

    /**
     * @param string     $uri     uri to the method that should be called
     * @param array      $params  uri params that should be added
     * @param array|null $body    body for the request
     * @param array      $headers headers for the request
     *
     * @throws Exception
     */
    protected function patch(
        string $uri,
        array $params = [],
        ?array $body = null,
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        $preparedBody = $this->encodeBody($body);

        return $this->client->getHttpClient()->patch($uri, $headers, $preparedBody);
    }

    /**
     * @param string     $uri     uri to the method that should be called
     * @param array      $params  uri params that should be added
     * @param array|null $body    body for the request
     * @param array      $headers headers for the request
     *
     * @throws Exception
     */
    protected function delete(
        string $uri,
        array $params = [],
        array $body = null,
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        $preparedBody = json_encode($body);

        if (false === $preparedBody) {
            throw new RuntimeException('Error while encoding request body. Function json_encode returned false.');
        }

        return $this->client->getHttpClient()->delete($uri, $headers, $preparedBody);
    }

    /**
     * @param string     $uri     uri to the method that should be called
     * @param array      $params  uri params that should be added
     * @param array|null $body    body for the request
     * @param array      $headers headers for the request
     *
     * @throws Exception
     */
    protected function options(
        string $uri,
        array $params = [],
        array $body = null,
        array $headers = []): ResponseInterface
    {
        $uri = $this->bindUriParams($uri, $params);

        $preparedBody = $this->encodeBody($body);

        return $this->client->getHttpClient()->options($uri, $headers, $preparedBody);
    }

    /**
     * If uri contains placeholders this function replaces them with given values.
     * For example:
     * We have uri /group/{groupId} (we treat {groupId} as placeholder) and
     * we pass in $params array ['groupId' => 12] then this function will return /group/12.
     *
     * @param string $uri    Uri with placeholders
     * @param array  $params Placeholders values
     *
     * @return string Fulfilled Uri
     */
    protected function replacePlaceHolders(
        string $uri,
        array $params): string
    {
        $tempUri = $uri;

        foreach ($params as $paramKey => $paramValue) {
            $tempUri = str_replace('{'.$paramKey.'}', (string) $paramValue, $uri);
        }

        return $tempUri;
    }

    /**
     * Removes null values from array.
     */
    protected function removeNullValues(array $body): array
    {
        $array = array_map(function ($item) {
            return is_array($item) ? $this->removeNullValues($item) : $item;
        }, $body);

        return array_filter($array, function ($item): bool {
            return '' !== $item && null !== $item && (!is_array($item) || count($item) > 0);
        });
    }

    /**
     * Used to validate datetime constraints.
     */
    protected function validateDateTimeConstraints(
        ?DateTime $dateFrom,
        ?DateTime $dateTo): void
    {
        if (null == $dateFrom && null == $dateTo) {
            return;
        }

        if ((null != $dateTo && null == $dateFrom) || (null == $dateTo && null != $dateFrom)) {
            throw new RuntimeException("Invalid 'dateFrom' or 'dateTo' parameter value! One of this values is equal to null!");
        }

        if ($dateFrom > new DateTime()) {
            throw new RuntimeException("Invalid 'dateFrom' parameter value! Cannot be greater than 'now'!");
        }

        if ($dateTo > new DateTime()) {
            throw new RuntimeException("Invalid 'dateTo' parameter value! Cannot be greater than 'now'!");
        }

        if ($dateFrom > $dateTo) {
            throw new RuntimeException("Invalid 'dateTo' and 'dateFrom' parameter values! DateFrom cannot be greater than dateTo!");
        }
    }

    /**
     * Used to validate pagination constraints.
     *
     * @throws RuntimeException
     */
    protected function validatePaginationConstraints(
        ?int $limit = null,
        ?int $offset = null,
        ?string $orderDirection = null): void
    {
        $validator = Validation::createValidator();

        if (null != $limit) {
            $violations = $validator->validate($limit, [
                new GreaterThan(0),
            ]);

            if (0 !== count($violations)) {
                throw new RuntimeException("Invalid 'limit' parameter value! Must be greater than 0!");
            }
        }

        if (null != $offset) {
            $violations = $validator->validate($offset, [
                new GreaterThanOrEqual(0),
            ]);

            if (0 !== count($violations)) {
                throw new RuntimeException("Invalid 'offset' parameter value! Must be greater or equal 0!");
            }
        }

        if (null != $orderDirection) {
            $violations = $validator->validate($orderDirection, [
                new AtLeastOneOf([
                    new EqualTo('DESC'),
                    new EqualTo('ASC'),
                ]),
            ]);

            if (0 !== count($violations)) {
                throw new RuntimeException("Invalid 'orderDirection' parameter value! Allowed 'DESC' and 'ASC'!");
            }
        }
    }
}
