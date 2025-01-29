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

namespace Plotkabytes\RedlinkApi\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\RequestInterface;

/**
 * Add authentication to the request.
 *
 * @internal
 */
final class Authentication implements Plugin
{
    /**
     * Api key that can be obtained from redlink panel.
     *
     * @var string API key
     */
    private $apikey;

    /**
     * Application key that can be obtained from redlink panel.
     *
     * @var string|null Application key
     */
    private $appkey;

    /**
     * Constructor.
     *
     * @param string      $apiKey         API key
     * @param string|null $applicationKey Application key
     * @psalm-suppress PossiblyNullArgument
     *
     * @return void
     */
    public function __construct(string $apiKey, ?string $applicationKey = null)
    {
        if (null == $apiKey) {
            throw new RuntimeException('API key cannot be null. Please check provided value');
        }

        if (ctype_space($apiKey)) {
            throw new RuntimeException('API key cannot be empty. Please check provided value');
        }

        $this->apikey = $apiKey;
        $this->appkey = $applicationKey;
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @psalm-suppress PossiblyNullArgument
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $request = $request->withHeader('Authorization', $this->apikey);

        if (null != $this->appkey) {
            if (ctype_alnum($this->appkey)) {
                $request = $request->withHeader('Application-Key', $this->appkey);
            }
        }

        return $next($request);
    }
}
