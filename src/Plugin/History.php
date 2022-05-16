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

use Http\Client\Common\Plugin\Journal;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class History implements Journal
{
    /**
     * @var int Specify how many entries should be stored in memory
     */
    private $maxHistorySize = 10;

    /**
     * @var array Successful requests - with code 200
     */
    private $successes = [];

    /**
     * @var array Requests that failed - code different from 200
     */
    private $failures = [];

    /**
     * Add successful request.
     *
     * @return void
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        if (count($this->successes) > $this->maxHistorySize) {
            array_shift($this->successes);
        }

        $this->successes[] = [
            'request' => $request,
            'response' => $response,
        ];
    }

    /**
     * Add failed request.
     *
     * @return void
     */
    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception)
    {
        if (count($this->failures) > $this->maxHistorySize) {
            array_shift($this->failures);
        }

        $this->successes[] = [
            'request' => $request,
            'exception' => $exception,
        ];
    }

    /**
     * Get last N success requests and responses.
     */
    public function getSuccesses(): array
    {
        return $this->successes;
    }

    /**
     * Get last N failures requests and responses.
     */
    public function getFailures(): array
    {
        return $this->failures;
    }
}
