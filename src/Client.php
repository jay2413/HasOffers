<?php

/*
 * This file is part of HasOffers PHP Client.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\HasOffers;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class ServiceProvider.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class Client
{
    /**
     * API Base URL - Brand.
     *
     * @var string
     */
    private $apiUrlBrand = 'https://api.hasoffers.com/Apiv3/json?NetworkId=%s&Target=%s&Method=%s&NetworkToken=%s';

    /**
     * API Base URL - Affiliate.
     *
     * @var string
     */
    private $apiUrlAffiliate = 'https://api.hasoffers.com/Apiv3/json?NetworkId=%s&Target=%s_%s&Method=%s&api_key=%s';

    /**
     * API Base URL - YeahMobi.
     *
     * @var string
     */
    private $apiUrlYeahMobi = 'http://yeahmobi.hasoffers.com/%s.json?api_key=%s';

    /**
     * API Base URL - FurtherMobi.
     *
     * @var string
     */
    private $apiUrlFurtherMobi = 'http://aff.furthermobi.com/%s.json?api_key=%s';

    /**
     * HTTP Headers.
     *
     * @var array
     */
    private $headers = ['User-Agent' => 'DraperStudio-HasOffers/1.0.0'];

    /**
     * HTTP Client.
     *
     * @var object
     */
    private $httpClient;

    /**
     * API Key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Network ID.
     *
     * @var string
     */
    private $networkId;

    /**
     * API Type.
     *
     * @var string
     */
    private $apiType;

    /**
     * API Namespace.
     *
     * @var string
     */
    private $apiNamespace;

    /**
     * Constructor.
     *
     * @param string $apiKey
     * @param string $networkId
     */
    public function __construct($apiKey, $networkId = null)
    {
        $this->setApiKey($apiKey);

        $this->setNetworkId($networkId);

        $this->setHttpClient(new GuzzleClient([
            'defaults' => [
                'headers' => $this->getHeaders(),
            ],
        ]));
    }

    /**
     * Initialize the given API class.
     *
     * @param string $class
     *
     * @return object
     */
    public function api($class)
    {
        $class = 'DraperStudio\\HasOffers\\Api\\'.$class;

        return new $class($this);
    }

    /**
     * Send a GET request.
     *
     * @param string $apiMethod
     * @param array  $parameters
     *
     * @return object
     */
    public function get($apiMethod, $parameters = [])
    {
        $requestUrl = $this->buildUrl($apiMethod, $parameters);
        $requestUrl = urldecode($requestUrl);

        $response = $this->getHttpClient()->get($requestUrl);

        return $this->handleResponse($response);
    }

    /**
     * Build the request url.
     *
     * @param string $apiMethod
     * @param array  $parameter
     *
     * @return string
     */
    private function buildUrl($apiMethod, $parameters)
    {
        switch ($this->getApiType()) {
            case 'Brand':
                $url = sprintf($this->apiUrlBrand, $this->getNetworkId(), $this->getApiNamespace(), $apiMethod, $this->getApiKey());
            break;

            case 'Affiliate':
                $url = sprintf($this->apiUrlAffiliate, $this->getNetworkId(), $this->getApiType(), $this->getApiNamespace(), $apiMethod, $this->getApiKey());
            break;

            case 'YeahMobi':
                $url = sprintf($this->apiUrlYeahMobi, $apiMethod, $this->getApiKey());
            break;

            case 'FurtherMobi':
                $url = sprintf($this->apiUrlFurtherMobi, $apiMethod, $this->getApiKey());
            break;
        }

        return $url.'&'.http_build_query($parameters);
    }

    /**
     * Handle the response.
     *
     * @param object $response
     *
     * @return object
     */
    private function handleResponse($response)
    {
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody());

        if ($statusCode >= 200 && $statusCode < 300) {
            return $body;
        }

        throw new \Exception($body->message, $statusCode);
    }

    /**
     * Get the Affiliate API Base URL.
     *
     * @return string
     */
    public function getApiUrlAffiliate()
    {
        return $this->apiUrlAffiliate;
    }

    /**
     * Set the Affiliate API Base URL.
     *
     * @param string $apiUrlAffiliate
     */
    public function setApiUrlAffiliate($apiUrlAffiliate)
    {
        $this->apiUrlAffiliate = $apiUrlAffiliate;
    }

    /**
     * Get the Brand API Base URL.
     *
     * @return string
     */
    public function getApiUrlBrand()
    {
        return $this->apiUrlBrand;
    }

    /**
     * Set the Brand API Base URL.
     *
     * @param string $apiUrlBrand
     */
    public function setApiUrlBrand($apiUrlBrand)
    {
        $this->apiUrlBrand = $apiUrlBrand;
    }

    /**
     * Get the YeahMobi API Base URL.
     *
     * @return string
     */
    public function getApiUrlYeahMobi()
    {
        return $this->apiUrlYeahMobi;
    }

    /**
     * Set the YeahMobi API Base URL.
     *
     * @param string $apiUrlYeahMobi
     */
    public function setApiUrlYeahMobi($apiUrlYeahMobi)
    {
        $this->apiUrlYeahMobi = $apiUrlYeahMobi;
    }

    /**
     * Get the FurtherMobi API Base URL.
     *
     * @return string
     */
    public function getApiUrlFurtherMobi()
    {
        return $this->apiUrlFurtherMobi;
    }

    /**
     * Set the FurtherMobi API Base URL.
     *
     * @param string $apiUrlFurtherMobi
     */
    public function setApiUrlFurtherMobi($apiUrlFurtherMobi)
    {
        $this->apiUrlFurtherMobi = $apiUrlFurtherMobi;
    }

    /**
     * Get the HTTP Headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the HTTP Headers.
     *
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get the API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the API Key.
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get the Network ID.
     *
     * @return string
     */
    public function getNetworkId()
    {
        return $this->networkId;
    }

    /**
     * Set the Network ID.
     *
     * @param string $networkId
     */
    public function setNetworkId($networkId)
    {
        $this->networkId = $networkId;
    }

    /**
     * Get the API Type.
     *
     * @return string
     */
    public function getApiType()
    {
        return $this->apiType;
    }

    /**
     * Set the API Type.
     *
     * @param string $apiType
     */
    public function setApiType($apiType)
    {
        $this->apiType = $apiType;
    }

    /**
     * Get the API Namespace.
     *
     * @return string
     */
    public function getApiNamespace()
    {
        return $this->apiNamespace;
    }

    /**
     * Set the API Namespace.
     *
     * @param string $apiNamespace
     */
    public function setApiNamespace($apiNamespace)
    {
        $this->apiNamespace = $apiNamespace;
    }

    /**
     * Get the HTTP Client.
     *
     * @return object
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the HTTP Client.
     *
     * @param object $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
