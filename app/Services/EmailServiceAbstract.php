<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class EmailServiceAbstract
{
    protected array $requestParamsValidationRules = [];
    protected array $requestParams = [];
    protected array $responseValidationRules = ['email' => 'email'];

    function __construct()
    {

    }

    public abstract function doRequest(): array;

    /**
     * @throws ValidationException
     */
    protected function validateRequestParameters(): ValidationException|null
    {
        if (!Validator::make($this->requestParams, $this->requestParamsValidationRules)->passes()) {
            throw new ValidationException('Cannot create request. Request Validation not passed');
        }

        return null;
    }

    public function setRequestParameters(array $parameters): static
    {
        $this->requestParams = $parameters;

        return $this;
    }

    public function normalizeResponse(array $response): array
    {
        $normalizedResponse = [];

        foreach ($response as $key => $data) {
            if (is_string($data)) {
                $normalizedResponse[lcfirst($key)] = $data;
            }

            if (is_array($data)) {
                $normalizedResponse[$key] = $this->normalizeResponse($data);
            }
        }

        return $normalizedResponse;
    }

    public function getResponseValidationRules(): array
    {
        return $this->responseValidationRules;
    }
}
