<?php

namespace App\Support\API\Bitrix;

class Client
{
    public static function product(): Entity\Product
    {
        return new Entity\Product();
    }
    public static function section(): Entity\Section
    {
        return new Entity\Section();
    }
}