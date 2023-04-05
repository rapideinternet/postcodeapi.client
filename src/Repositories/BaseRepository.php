<?php

namespace RapideInternet\PostcodeAPI\Repositories;

use RapideInternet\PostcodeAPI\PostcodeAPIClient;

abstract class BaseRepository {

    protected PostcodeAPIClient $client;

    /**
     * @param PostcodeAPIClient $postcodeAPIClient
     */
    public function __construct(PostcodeAPIClient $postcodeAPIClient) {
        $this->client = $postcodeAPIClient;
    }

    /**
     * @param string $api_key
     * @return void
     */
    public function setApiKey(string $api_key): void {
        $this->client->setApiKey($api_key);
    }
}
