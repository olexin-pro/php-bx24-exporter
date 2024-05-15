<?php

namespace App\Support\API\Bitrix\Entity;

use App\Support\API\ApiRequest;
use App\Support\API\Bitrix\BitrixMethod;
use App\Support\API\Response;
use Generator;

class Section extends BaseEntity
{

    public function get($id): Response
    {
        $request = ApiRequest::get(BitrixMethod::sectionGet->value)
            ->setQuery([
                'id' => $id
            ]);

        return $this->send($request);
    }
    public function fields(): Response
    {
        $request = ApiRequest::get(BitrixMethod::sectionFields->value);
        return $this->send($request);
    }
    public function list(int $start = 1, array $select = ['*', 'PROPERTY_*'], array $filter = [], array $order = []): Response
    {
        $request = ApiRequest::post(BitrixMethod::sectionList->value)
            ->setBody([
                'order' => $order,
                'filter' => $filter,
                'select' => $select,
                'start' => $start
            ]);
        return $this->send($request);
    }
    public function listGen(array $select = ['*', 'PROPERTY_*'], array $filter = [], array $order = []): Generator
    {
        $start = 1;
        $total = 2;

        do {

            $response = $this->list($start, $select, $filter, $order);
            $total = $response->getByKey('total');

            if (!is_null($response->getByKey('next'))){
                $start = $response->getByKey('next');
            }else{
                $start = $response->getByKey('total');
            }

            foreach ($response->getResult() as $item) {
                yield $item;
            }

        } while ($start < $total);
    }
}