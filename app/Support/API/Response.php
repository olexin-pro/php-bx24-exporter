<?php

namespace App\Support\API;

/**
 * @method int getStatusCode()
*/

class Response
{

    private ?string $string_data = null;
    private ?array $data = null;

    public function __construct(
        protected \GuzzleHttp\Psr7\Response $response,
    )
    {
    }

    public function isSuccess(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 226;
    }

    public function toString(): string
    {
        if(is_null($this->string_data)){
            $this->string_data = $this->decodeToString();
        }

        return $this->string_data;
    }

    public function toArray()
    {
        return $this->getData();
    }

    public function getByKey(string $key = null, mixed $default = null)
    {
        $data = $this->getData();

        if(array_key_exists($key, $data)){
            return $data[$key];
        }

        return $default;
    }

    public function getResult($default = [])
    {
        return $this->getByKey('result') ?? $default;
    }

    protected function getData(string $key = null)
    {
        if (is_null($this->data)){
            $this->data = $this->decodeToArray();
        }

        return $this->data;
    }

    private function decodeToString(): string
    {
        return (string) $this->response->getBody();
    }

    private function decodeToArray()
    {
        return json_decode($this->toString(), true);
    }

    public function __call(string $name, array $arguments)
    {
        if(method_exists($this->response, $name)){
            call_user_func_array([$this->response, $name], $arguments);
        }
        throw new \BadMethodCallException("Method $name is missing!");
    }


}