<?php

namespace RapideInternet\PostcodeAPI\Exceptions;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TooManyRequests extends Exception {

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return HttpResponse::HTTP_TOO_MANY_REQUESTS;
    }
}
