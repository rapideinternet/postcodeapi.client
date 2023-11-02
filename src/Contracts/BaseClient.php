<?php

namespace RapideInternet\PostcodeAPI\Contracts;

use GuzzleHttp\Client;
use RapideInternet\PostcodeAPI\Response;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use RapideInternet\PostcodeAPI\Exceptions\Exception;
use RapideInternet\PostcodeAPI\Exceptions\NotFoundException;
use RapideInternet\PostcodeAPI\Exceptions\BadGateWayException;
use RapideInternet\PostcodeAPI\Exceptions\ForbiddenException;
use RapideInternet\PostcodeAPI\Exceptions\TooManyRequests;
use RapideInternet\PostcodeAPI\Exceptions\BadRequestException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use RapideInternet\PostcodeAPI\Exceptions\UnauthorizedException;
use RapideInternet\PostcodeAPI\Exceptions\NotImplementedException;
use RapideInternet\PostcodeAPI\Exceptions\ServiceUnavailableException;
use RapideInternet\PostcodeAPI\Exceptions\UnprocessableEntityException;
use RapideInternet\PostcodeAPI\Exceptions\InternalServerErrorException;

abstract class BaseClient {

    private Client $client;
    protected array $clients = [];

    /**
     * Constructor
     */
    public function __construct() {
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return Response
     */
    public function get(string $url, array $parameters = []): Response {
        $this->beforeRequest();
        $options['headers'] = $this->getHeaders();
        $options['query'] = $parameters;
        try {
            $response = $this->client->get($this->getURL().$url, $options);
        }
        catch(GuzzleException $e) {
            return $this->parseError($e);
        }
        return $this->parseResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     */
    protected function parseResponse(ResponseInterface $response): Response {
        return new Response([
            'status_code' => $response->getStatusCode(),
            'body' => $this->parseBody($response),
            'message' => 'Query was successful'
        ]);
    }

    /**
     * @param GuzzleException $e
     * @return Response
     */
    protected function parseError(GuzzleException $e): Response {
        $exception = $this->parseException($e);
        return new Response([
            'status_code' => $exception->getStatusCode(),
            'body' => [],
            'message' => $exception->getMessage()
        ]);
    }

    /**
     * @param GuzzleException $e
     * @return Exception
     */
    protected function parseException(GuzzleException $e): Exception {
        if($e instanceof ConnectException) {
            return new ServiceUnavailableException($e);
        }
        elseif($e instanceof RequestException) {
            return ($response = $e->getResponse()) === null
                ? new InternalServerErrorException($e)
                : match($response->getStatusCode()) {
                    HttpResponse::HTTP_BAD_REQUEST => new BadRequestException($e),
                    HttpResponse::HTTP_UNAUTHORIZED => new UnauthorizedException($e),
                    HttpResponse::HTTP_FORBIDDEN => new ForbiddenException($e),
                    HttpResponse::HTTP_NOT_FOUND => new NotFoundException($e),
                    HttpResponse::HTTP_TOO_MANY_REQUESTS => new TooManyRequests($e),
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY => new UnprocessableEntityException($e),
                    HttpResponse::HTTP_INTERNAL_SERVER_ERROR => new InternalServerErrorException($e),
                    HttpResponse::HTTP_BAD_GATEWAY => new BadGateWayException($e),
                    HttpResponse::HTTP_SERVICE_UNAVAILABLE => new ServiceUnavailableException($e),
                    default => new NotImplementedException($e)
            };
        }
        return new NotImplementedException($e);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function parseBody(ResponseInterface $response): array {
        return json_decode($response->getBody(), true);
    }

    /**
     * @return string
     */
    public abstract function getURL(): string;

    /**
     * @return void
     */
    protected abstract function beforeRequest(): void;

    /**
     * @return array
     */
    public abstract function getHeaders(): array;

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $arguments) {
        if(!isset($this->clients[$method]) && !method_exists($this, $method)) {
            throw new \Exception("Unknown method [$method]");
        }
        elseif(method_exists($this, $method)) {
            return call_user_func([$this, $method], $arguments);
        }
        elseif(isset($this->clients[$method])) {
            return new $this->clients[$method]($this);
        }
        throw new \Exception("Unknown method [$method]");
    }

    /**
     * @param $property
     * @return mixed
     * @throws \Exception
     */
    public function __get($property){
        if(property_exists($this, $property)) {
            return $this->{$property};
        }
        elseif(isset($this->clients[$property])) {
            return new $this->clients[$property]($this);
        }
        throw new \Exception("Unknown property [$property]");
    }
}
