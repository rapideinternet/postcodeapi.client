<?php

namespace RapideInternet\PostcodeAPI\Models;

class Address implements \RapideInternet\PostcodeAPI\Interfaces\Address {

    public string $street_name;
    public string $street_number;
    public ?string $street_number_addition;
    public ?string $street_number_letter;
    public string $postal_code;
    public string $city;
    public string $province;
    public string $municipality;
    public float $latitude;
    public float $longitude;

    /**
     * @param array $data
     */
    public function __construct(array $data) {
        $this->postal_code = $data['postcode'];
        $this->street_number = $data['number'];
        $this->street_name = $data['street'];
        $this->street_number_addition = null;
        $this->street_number_letter = null;
        $this->city = $data['city'];
        $this->municipality = $data['municipality'];
        $this->province = $data['province'];
        $this->latitude = $data['location']['coordinates'][1];
        $this->longitude = $data['location']['coordinates'][0];
    }
}
