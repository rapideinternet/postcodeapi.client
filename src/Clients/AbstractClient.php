<?php

namespace RapideInternet\PostcodeAPI\Clients;

use RapideInternet\PostcodeAPI\PostcodeAPIClient;
use RapideInternet\PostcodeAPI\Contracts\BaseClient;

abstract class AbstractClient extends BaseClient {

    protected PostcodeAPIClient $postcodeAPI;

    /**
     * @param PostcodeAPIClient $client
     */
    public function __construct(PostcodeAPIClient $client) {
        parent::__construct();
        $this->postcodeAPI = $client;
    }

    /**
     * @return string
     */
    public function getURL(): string {
        return $this->postcodeAPI->getURL();
    }

    /**
     * @return void
     */
    protected function beforeRequest(): void {
        $this->postcodeAPI->beforeRequest();
    }

    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->postcodeAPI->getHeaders();
    }
}
