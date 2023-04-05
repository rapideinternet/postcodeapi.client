<?php

namespace RapideInternet\PostcodeAPI\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

abstract class Exception extends \Exception {

    /**
     * @param GuzzleException $e
     */
    public function __construct(GuzzleException $e) {
        parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
    }

    /**
     * @return int
     */
    public abstract function getStatusCode(): int;

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->getMessage();
    }
}
