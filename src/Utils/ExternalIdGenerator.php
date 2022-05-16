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

use Plotkabytes\RedlinkApi\Exception\RuntimeException;

class ExternalIdGenerator implements ExternalIdGeneratorInterface
{
    /**
     * Generate external ID with given length.
     *
     * @param int<1, 100> $length
     */
    public function generate(int $length): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (\Exception $e) {
            throw new RuntimeException('Could not generate external id.', 0, $e);
        }
    }
}
