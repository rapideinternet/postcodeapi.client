<?php

namespace RapideInternet\PostcodeAPI;

use Psr\Http\Message\ResponseInterface;
use RapideInternet\PostcodeAPI\Clients\AddressClient;
use RapideInternet\PostcodeAPI\Contracts\BaseClient;

/**
 * @property AddressClient $address
 */
class PostcodeAPIClient extends BaseClient {

    protected string $url = 'https://api.postcodeapi.nu/v3';
    protected ?string $api_key = null;
    protected array $clients = [
        'address' => AddressClient::class
    ];

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function parseBody(ResponseInterface $response): array {
        return ['data' => json_decode($response->getBody(), true)];
    }

    /**
     * @param string $api_key
     * @return void
     */
    public function setApiKey(string $api_key): void {
        $this->api_key = $api_key;
    }

    /**
     * @return string|null
     */
    protected function getApiKey(): ?string {
        return $this->api_key;
    }

    /**
     * @return string
     */
    public function getURL(): string {
        return $this->url;
    }

    /**
     * @return void
     */
    protected function beforeRequest(): void {
        // Nothing
    }

    /**
     * @return array
     */
    public function getHeaders(): array {
        return [
            'X-Api-Key' => $this->getApiKey(),
            'Content-Type' => 'application/json'
        ];
    }
}
