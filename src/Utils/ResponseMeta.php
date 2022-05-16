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

namespace Plotkabytes\RedlinkApi\Utils;

class ResponseMeta
{
    /**
     * @var int number of elements in 'errors' property
     */
    private $numberOfErrors;

    /**
     * @var int number of elements in 'data' property
     */
    private $numberOfData;

    /**
     * @var int HTTP status code
     */
    private $status;

    /**
     * @var string Request unique identifier. When submitting error to BOK please attach this value.
     */
    private $uniqId;

    public function __construct(int $numberOfErrors, int $numberOfData, int $status, string $uniqId)
    {
        $this->numberOfErrors = $numberOfErrors;
        $this->numberOfData = $numberOfData;
        $this->status = $status;
        $this->uniqId = $uniqId;
    }

    /**
     * Used to get number of elements in 'errors' property.
     */
    public function getNumberOfErrors(): int
    {
        return $this->numberOfErrors;
    }

    /**
     * Used to get number of elements in 'data' property.
     */
    public function getNumberOfData(): int
    {
        return $this->numberOfData;
    }

    /**
     * Used to get HTTP status code of the response.
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Used to get Request Unique Identifier.
     * This value can be useful when submitting problems with usage of API.
     */
    public function getUniqId(): string
    {
        return $this->uniqId;
    }
}
