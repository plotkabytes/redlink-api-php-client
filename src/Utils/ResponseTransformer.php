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

use Plotkabytes\RedlinkApi\Exception\DeserializationException;

class ResponseTransformer implements ResponseTransformerInterface
{
    private const DECODING_DEPTH = 10;

    public function createFromJson(string $json): RedlinkResponse
    {
        /**
         * @var array
         */
        $decoded = json_decode($json, true, ResponseTransformer::DECODING_DEPTH);

        if (null == $decoded) {
            throw new DeserializationException('Invalid response format! Could not deserialize given json.', 0, null, $json);
        }

        try {
            $errors = [];
            if (isset($decoded['errors'])) {
                foreach ($decoded['errors'] as $error) {
                    $errors[] = new ResponseError($error['title'], $error['message'], $error['code']);
                }
            }
        } catch (\Exception $e) {
            throw new DeserializationException('Could not parse errors while constructing response from JSON.', 0, $e, $json);
        }

        return new RedlinkResponse(
            $decoded['data'] ?? [],
            $errors,
            new ResponseMeta(
                $decoded['meta']['numberOfErrors'],
                $decoded['meta']['numberOfData'],
                $decoded['meta']['status'],
                $decoded['meta']['uniqId']
            )
        );
    }
}
