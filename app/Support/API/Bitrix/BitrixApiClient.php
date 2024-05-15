<?php

namespace App\Support\API\Bitrix;

use App\Support\API\ApiClient;

class BitrixApiClient extends ApiClient
{

    protected function baseUrl(): string
    {
        return 'https://sunservice.bitrix24.kz/rest/9/8wupbmpal3vc5juu/';
    }
}