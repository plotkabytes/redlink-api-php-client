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

class RedlinkResponse
{
    /**
     * @var array[]|null Contains returned data
     */
    private $data;

    /**
     * @var ResponseError[]|null Contains returned errors
     */
    private $errors;

    /**
     * @var ResponseMeta contains general information's about response
     */
    private $meta;

    /**
     * Constructor.
     *
     * @param ResponseError[]|null $errors
     */
    public function __construct(?array $data, ?array $errors, ResponseMeta $meta)
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->meta = $meta;
    }

    /**
     * Used to get all elements in 'data' property. When you want to get number of elements in this property use meta->numberOfData.
     *
     * @return array[]|null Array with response data
     *
     * @see ResponseMeta::getNumberOfData()
     * @see RedlinkResponse::getMeta()
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Used to get all elements in 'errors' property. When you want to get number of elements in this property use meta->numberOfErrors.
     *
     * @return ResponseError[]|null Array with response errors
     *
     * @see RedlinkResponse::getMeta()
     * @see ResponseMeta::getNumberOfErrors()
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * Used to get response meta.
     *
     * @see ResponseMeta::getNumberOfData()
     * @see ResponseMeta::getNumberOfErrors()
     * @see ResponseMeta::getStatus()
     * @see ResponseMeta::getUniqId()
     */
    public function getMeta(): ResponseMeta
    {
        return $this->meta;
    }
}
