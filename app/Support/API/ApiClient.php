<?php

namespace App\Support\API;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

abstract class ApiClient
{
    /**
     * Send an ApiRequest to the API and return the response.
     */
    public function send(ApiRequest $request): \App\Support\API\Response
    {
        $payload = [];

        if($request->getMethod() === HttpMethod::GET){
            $payload['query'] = $request->getQuery();
        }else{
            $payload['form_params'] = $request->getBody();
        }

        $payload['headers'] = $request->getHeaders();

        $response = $this->getBaseRequest()
            ->{$request->getMethod()->value}(
                $request->getUri(),
                $payload
            );

        return new \App\Support\API\Response($response);
    }

    /**
     * Get a base request for the API.
     * This method has some helpful defaults for API requests.
     * The base request is a PendingRequest with JSON acceptance, a content type
     * of 'application/json', and the base URL for the API.
     * It also throws exceptions for non-successful responses.
     */
    protected function getBaseRequest(array $headers = []): Client
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perSecond(2));

        $request = new Client([
            'base_uri' => $this->baseUrl(),
            'timeout'  => 15.0,
            'headers' => [
                'Accept'     => 'application/json',
                'Content-Type'     => 'application/json',
                ...$headers
            ],
            'verify' => false,
            'handler' => $stack,
        ]);

        return $this->authorize($request);
    }

    /**
     * Authorize a request for the API.
     * This method is intended to be overridden by subclasses to provide
     * API-specific authorization.
     * By default, it simply returns the given request.
     */
    protected function authorize(Client $request): Client
    {
        return $request;
    }


    /**
     * Get the base URL for the API.
     * This method must be implemented by subclasses to provide the base URL for
     * the API.
     */
    abstract protected function baseUrl(): string;
}