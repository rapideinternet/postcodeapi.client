<?php

namespace RapideInternet\PostcodeAPI\Exceptions;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class NotImplementedException extends Exception {

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return HttpResponse::HTTP_NOT_IMPLEMENTED;
    }
}
