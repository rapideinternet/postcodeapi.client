<?php

namespace RapideInternet\PostcodeAPI\Clients;

use RapideInternet\PostcodeAPI\Response;

class AddressClient extends AbstractClient {

    /**
     * @param string $postal_code
     * @param string $house_number
     * @return Response
     */
    public function lookup(string $postal_code, string $house_number): Response {
        return $this->postcodeAPI->get('/lookup/'.$postal_code.'/'.$house_number);
    }
}
