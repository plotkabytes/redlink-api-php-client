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

interface ExternalIdGeneratorInterface
{
    /**
     * Generate external ID with given length.
     *
     * @param int<1, 100> $length
     */
    public function generate(int $length): string;
}
