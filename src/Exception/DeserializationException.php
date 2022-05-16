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

namespace Plotkabytes\RedlinkApi\Exception;

use Throwable;

class DeserializationException extends \RuntimeException
{
    /**
     * Raw string that could not be deserialized.
     *
     * @var string|null
     */
    private $rawString;

    /**
     * Default constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null, string $rawString = null)
    {
        parent::__construct($message, $code, $previous);
        $this->rawString = $rawString;
    }

    /**
     * Used to get raw string that could not be deserialized.
     */
    public function getRawString(): ?string
    {
        return $this->rawString;
    }
}
