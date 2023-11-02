<?php

namespace RapideInternet\PostcodeAPI;

interface ResponseInterface {

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return mixed
     */
    public function getBody(): mixed;

    /**
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return array
     */
    public function getMessages(): array;

    /**
     * @return string
     */
    public function toJson(): string;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return bool
     */
    public function isRedirect(): bool;

    /**
     * @return bool
     */
    public function isInvalid(): bool;

    /**
     * @return bool
     */
    public function isServerError(): bool;
}
