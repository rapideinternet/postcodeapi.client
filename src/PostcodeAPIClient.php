<?php

namespace RapideInternet\PostcodeAPI;

use Psr\Http\Message\ResponseInterface;
use RapideInternet\PostcodeAPI\Clients\AddressClient;
use RapideInternet\PostcodeAPI\Contracts\BaseClient;
use Exception;

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

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $arguments) {
        if(!isset($this->clients[$method]) && !method_exists($this, $method)) {
            throw new Exception("Unknown method [$method]");
        }
        elseif(method_exists($this, $method)) {
            return call_user_func([$this, $method], $arguments);
        }
        elseif(isset($this->clients[$method])) {
            return new $this->clients[$method]($this);
        }
        throw new Exception("Unknown method [$method]");
    }

    /**
     * @param $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property){
        if(property_exists($this, $property)) {
            return $this->{$property};
        }
        elseif(isset($this->clients[$property])) {
            return new $this->clients[$property]($this);
        }
        throw new Exception("Unknown property [$property]");
    }
}
