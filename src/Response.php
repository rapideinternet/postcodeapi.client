<?php

namespace RapideInternet\PostcodeAPI;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response implements ResponseInterface {

    protected int $status_code;
    protected ?array $body;
    protected string $message;

    /**
     * @param array $data
     */
    public function __construct(array $data) {
        $this->status_code = (int) $data['status_code'];
        $this->body = $data['body'] ?? null;
        $this->message = $data['title'] ?? '';
    }

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->status_code;
    }

    /**
     * @return array|null
     */
    public function getBody(): ?array {
        return $this->body;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array {
        return $this->body['data'] ?? null;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->isValid() ? 'ok' : 'error';
    }

    /**
     * @return array
     */
    public function getMessages(): array {
        return [['status' => $this->getStatus(), 'text' => $this->message]];
    }

    /**
     * @return string
     */
    public function toJson(): string {
        return json_encode(['status_code' => $this->status_code, 'message' => $this->message, 'body' => $this->body]);
    }

    /**
     * @return bool
     */
    public function isValid(): bool {
        return $this->getStatusCode() >= HttpResponse::HTTP_OK && $this->getStatusCode() <= HttpResponse::HTTP_IM_USED;
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool {
        return $this->getStatusCode() >= HttpResponse::HTTP_MOVED_PERMANENTLY && $this->getStatusCode() <= HttpResponse::HTTP_PERMANENTLY_REDIRECT;
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool {
        return $this->getStatusCode() >= HttpResponse::HTTP_BAD_REQUEST && $this->getStatusCode() <= HttpResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
    }

    /**
     * @return bool
     */
    public function isServerError(): bool {
        return $this->getStatusCode() >= HttpResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
