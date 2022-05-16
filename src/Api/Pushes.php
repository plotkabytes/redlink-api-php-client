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

use Http\Client\Exception;
use Plotkabytes\RedlinkApi\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Pushes extends AbstractApi
{
    public const DEVICE_RECEIVER = 1;
    public const EMAIL_RECEIVER = 2;
    public const NUMBER_RECEIVER = 3;

    public const LOCKSCREEN_VISIBILITY_PUBLIC = 1;
    public const LOCKSCREEN_VISIBILITY_PRIVATE = 2;
    public const LOCKSCREEN_VISIBILITY_SECRET = 3;

    public const ACTION_NONE = 1;
    public const ACTION_BROWSER = 2;
    public const ACTION_WEBVIEW = 3;
    public const ACTION_DEEPLINK = 4;

    public const BUTTON_YES = 1;
    public const BUTTON_NO = 2;
    public const BUTTON_ACCEPT = 3;
    public const BUTTON_DECLINE = 4;
    public const BUTTON_BUY_NOW = 5;
    public const BUTTON_LATER = 6;
    public const BUTTON_ADD_TO_CART = 7;
    public const BUTTON_NO_THANKS = 8;
    public const BUTTON_OPEN = 9;
    public const BUTTON_CLOSE = 10;
    public const BUTTON_LOGIN = 11;
    public const BUTTON_SHARE = 12;
    public const BUTTON_SEE_MORE = 13;

    /**
     * Send push messages.
     *
     * @see https://docs.redlink.pl/#operation/PushCreate
     *
     * @throws Exception|RuntimeException
     */
    public function send(array $body): ResponseInterface
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefined('applications')
            ->setAllowedTypes('applications', 'array')
            ->setRequired('applications')
            ->setDefined('to')
            ->setAllowedTypes('to', 'array[]')
            ->setAllowedValues('to', function (array &$elements): bool {
                $subResolver = new OptionsResolver();
                $subResolver
                    ->setDefined('receiver')
                    ->setAllowedTypes('receiver', 'string')
                    ->setRequired('receiver')
                    ->setDefined('externalId')
                    ->setAllowedTypes('externalId', 'string')
                    ->setRequired('externalId')
                    ->setDefined('type')
                    ->setAllowedTypes('type', 'integer')
                    ->setAllowedValues('type', [
                        self::DEVICE_RECEIVER,
                        self::NUMBER_RECEIVER,
                        self::EMAIL_RECEIVER,
                    ])
                    ->setRequired('type');
                $elements = array_map([$subResolver, 'resolve'], $elements);

                return true;
            })
            ->setRequired('to')
            ->setDefined('title')
            ->setAllowedTypes('title', 'array')
            ->setRequired('title')
            ->setDefined('body')
            ->setAllowedTypes('body', 'array')
            ->setRequired('body')
            ->setDefined('defaultLanguage')
            ->setAllowedTypes('defaultLanguage', 'string')
            ->setRequired('defaultLanguage')
            ->setDefined('image')
            ->setAllowedTypes('image', 'string')
            ->setDefined('silent')
            ->setAllowedTypes('silent', 'bool')
            ->setDefined('sound')
            ->setAllowedTypes('sound', 'string')
            ->setDefined('scheduleTime')
            ->setAllowedTypes('scheduleTime', 'string')
            ->setDefined('ttl')
            ->setAllowedTypes('ttl', 'int')
            ->setDefined('externalData')
            ->setAllowedTypes('externalData', 'array')
            ->setDefined('advanced')
            ->setAllowedTypes('advanced', 'array')
            ->setDefault('advanced', function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefined('subtitle')
                    ->setAllowedTypes('subtitle', 'string')
                    ->setDefined('lockscreenVisibility')
                    ->setAllowedTypes('lockscreenVisibility', 'integer')
                    ->setAllowedValues('lockscreenVisibility', [
                        self::LOCKSCREEN_VISIBILITY_PRIVATE,
                        self::LOCKSCREEN_VISIBILITY_PUBLIC,
                        self::LOCKSCREEN_VISIBILITY_SECRET,
                    ])
                    ->setDefined('icon')
                    ->setAllowedTypes('icon', 'array');
            })
            ->setRequired('action')
            ->setDefined('action')
            ->setAllowedTypes('action', 'array')
            ->setDefault('action', function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefined('url')
                    ->setAllowedTypes('url', 'string')
                    ->setDefined('type')
                    ->setAllowedTypes('type', 'integer')
                    ->setAllowedValues('type', [
                        self::ACTION_NONE,
                        self::ACTION_BROWSER,
                        self::ACTION_DEEPLINK,
                        self::ACTION_WEBVIEW,
                    ]);
            })
            ->setDefined('actionButtons')
            ->setAllowedTypes('actionButtons', 'array[]')
            ->setAllowedValues('actionButtons', function (array &$elements): bool {
                $subResolver = new OptionsResolver();
                $subResolver
                    ->setDefined('button')
                    ->setAllowedTypes('button', 'integer')
                    ->setAllowedValues('button', [
                        self::BUTTON_YES,
                        self::BUTTON_NO,
                        self::BUTTON_ACCEPT,
                        self::BUTTON_DECLINE,
                        self::BUTTON_BUY_NOW,
                        self::BUTTON_LATER,
                        self::BUTTON_ADD_TO_CART,
                        self::BUTTON_NO_THANKS,
                        self::BUTTON_OPEN,
                        self::BUTTON_CLOSE,
                        self::BUTTON_LOGIN,
                        self::BUTTON_SHARE,
                        self::BUTTON_SEE_MORE,
                    ])
                    ->setRequired('button')
                    ->setDefined('icon')
                    ->setAllowedTypes('icon', 'string')
                    ->setDefined('action')
                    ->setAllowedTypes('action', 'array')
                    ->setRequired('action');
                $elements = array_map([$subResolver, 'resolve'], $elements);

                return true;
            });

        $optionsResolver->resolve($body);

        return $this->post('push', [], $body);
    }
}
