<?php

namespace RapideInternet\PostcodeAPI\Repositories;

use RapideInternet\PostcodeAPI\Models\Address;

class AddressRepository extends BaseRepository {

    /**
     * @param string $postal_code
     * @param string $house_number
     * @return Address|null
     */
    public function lookup(string $postal_code, string $house_number): ?Address {
        $response = $this->client->address->get('/lookup/'.$postal_code.'/'.$house_number);
        return $response->isValid() ? new Address($response->getData()) : null;
    }
}
