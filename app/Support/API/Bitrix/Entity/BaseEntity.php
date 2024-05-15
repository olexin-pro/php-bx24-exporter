<?php

namespace App\Support\API\Bitrix\Entity;

use App\Support\API\ApiRequest;
use App\Support\API\Bitrix\BitrixApiClient;

abstract class BaseEntity
{

    protected function send(ApiRequest $request)
    {
        return $this->getClient()->send($request);
    }
    private function getClient(): BitrixApiClient
    {
        return new BitrixApiClient();
    }
}