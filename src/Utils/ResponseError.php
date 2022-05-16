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

class ResponseError
{
    /**
     * @var string some error title
     */
    private $title;

    /**
     * @var string descriptive information about error
     */
    private $message;

    /**
     * @var string internal code used by API
     */
    private $code;

    /**
     * Customized errors constructor.
     */
    public function __construct(string $title, string $message, string $code)
    {
        $this->title = $title;
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * Returns error title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns descriptive information about error.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Returns internal code used by API.
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
