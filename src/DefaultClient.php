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

namespace Plotkabytes\RedlinkApi;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Plotkabytes\RedlinkApi\Api\BlackLists;
use Plotkabytes\RedlinkApi\Api\Campaigns;
use Plotkabytes\RedlinkApi\Api\Contacts;
use Plotkabytes\RedlinkApi\Api\Emails;
use Plotkabytes\RedlinkApi\Api\Groups;
use Plotkabytes\RedlinkApi\Api\Pushes;
use Plotkabytes\RedlinkApi\Api\SMSes;
use Plotkabytes\RedlinkApi\Plugin\Authentication;
use Plotkabytes\RedlinkApi\Utils\ExternalIdGenerator;
use Plotkabytes\RedlinkApi\Utils\ExternalIdGeneratorInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class DefaultClient
{
    /**
     * Authorization types.
     */
    public const AUTHORIZATION_USING_APIKEY = 1;
    public const AUTHORIZATION_USING_APPKEY = 2;

    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = /*'http://host.docker.internal:8080';*/
        'https://api.redlink.pl/';

    /**
     * The default user agent header.
     *
     * @var string
     */
    private const USER_AGENT = 'redlink-php-api-client/1.0';

    /**
     * Selected authorization type.
     *
     * @var int
     */
    private $authorizationType = self::AUTHORIZATION_USING_APPKEY;

    /**
     * Collection of plugins.
     *
     * @var Plugin[]
     */
    private $plugins = [];

    /**
     * A HTTP client with all our plugins.
     *
     * @var HttpMethodsClientInterface|null
     */
    private $pluginClient;

    /**
     * The object that sends HTTP messages.
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * The HTTP request factory.
     *
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * The HTTP stream factory.
     *
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * The URI factory.
     *
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * External ID generator.
     *
     * @var ExternalIdGeneratorInterface
     */
    private $externalIdGenerator;

    /**
     * Instantiate a new API client.
     *
     * @return void
     */
    public function __construct(
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
        UriFactoryInterface $uriFactory = null,
        ExternalIdGeneratorInterface $externalIdGenerator = null
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();

        if (null == $externalIdGenerator) {
            $this->externalIdGenerator = new ExternalIdGenerator();
        }

        $this->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => self::USER_AGENT,
            'Content-Type' => 'application/json',
        ]));

        $this->setUrl(self::BASE_URL);
    }

    /**
     * Create instance of DefaultClient with HTTP Client implementation.
     *
     * @return DefaultClient
     */
    public static function createWithHttpClient(ClientInterface $httpClient)
    {
        return new self($httpClient);
    }

    /**
     * Adds plugin to the plugin collection.
     */
    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[] = $plugin;
        $this->pluginClient = null;
    }

    /**
     * Replace URL.
     */
    public function setUrl(string $url): void
    {
        $uri = $this->uriFactory->createUri($url);
        $this->removePlugin(AddHostPlugin::class);
        $this->addPlugin(new AddHostPlugin($uri));
    }

    /**
     * Remove a plugin by its fully qualified class name (FQCN).
     */
    public function removePlugin(string $fqcn): void
    {
        $removed = false;

        foreach ($this->plugins as $idx => $plugin) {
            if ($plugin instanceof $fqcn) {
                unset($this->plugins[$idx]);
                $removed = true;
            }
        }

        if ($removed) {
            $this->pluginClient = null;
        }
    }

    /**
     * Get current authorization type.
     */
    public function getAuthorizationType(): int
    {
        return $this->authorizationType;
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        if (null === $this->pluginClient) {
            $plugins = $this->plugins;

            $this->pluginClient = new HttpMethodsClient(
                (new PluginClientFactory())->createClient($this->httpClient, $plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }

    /**
     * Attach authentication for this client.
     *
     * Redlink support two types of authorization:
     *
     * Version => 1.0.0 < 2.0.0 - only apikey
     * Version >= 2.0.0 - pair of apikey and appkey
     *
     * @param string      $apiKey         API key obtained from Redlink panel
     * @param string|null $applicationKey Application key (aka app) obtained from Redlink panel
     */
    public function setAuthentication(string $apiKey, string $applicationKey = null): void
    {
        if (null == $applicationKey) {
            $this->authorizationType = self::AUTHORIZATION_USING_APIKEY;
        } else {
            $this->authorizationType = self::AUTHORIZATION_USING_APPKEY;
        }

        $this->removePlugin(Authentication::class);
        $this->addPlugin(new Authentication($apiKey, $applicationKey));
    }

    /**
     * Contacts management.
     */
    public function contacts(): Contacts
    {
        return new Contacts($this);
    }

    /**
     * Groups management.
     */
    public function groups(): Groups
    {
        return new Groups($this);
    }

    /**
     * SMSes management.
     */
    public function SMSes(): SMSes
    {
        return new SMSes($this);
    }

    /**
     * Pushes management.
     */
    public function pushes(): Pushes
    {
        return new Pushes($this);
    }

    /**
     * Campaigns management.
     */
    public function campaigns(): Campaigns
    {
        return new Campaigns($this);
    }

    /**
     * Black lists management.
     */
    public function blacklists(): BlackLists
    {
        return new BlackLists($this);
    }

    /**
     * Email management.
     */
    public function emails(): Emails
    {
        return new Emails($this);
    }

    public function getExternalIdGenerator(): ExternalIdGeneratorInterface
    {
        return $this->externalIdGenerator;
    }
}
