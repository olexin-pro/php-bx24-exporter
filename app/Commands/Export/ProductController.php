<?php

namespace App\Commands\Export;

use App\Commands\BaseCommand;
use App\Support\API\Bitrix\Client;
use App\Support\API\Helpers;
use App\Support\File\CSV;

class ProductController extends BaseCommand
{
    use Helpers;

    public function handle(): void
    {

        CSV::$path = 'products.csv';
        CSV::deleteFile();

        $this->display('Start Load products');

        $productFields = $this->loadProductsFields();
        $fieldsToCsv = [];
        foreach ($productFields as $field){
            $fieldsToCsv[] = $field['title'];
        }

        CSV::getInstance()->writeToCsv($fieldsToCsv);

        $response = Client::product()->list();

        $progressBar = $this->getProgressBar();
        $progressBar->setMaxProgress($response->getByKey('total') ?? count($response->getResult() ?? []));

        foreach (Client::product()->listGen() as $product) {
            $this->writeProduct($product, $productFields);
            $progressBar->advance()->display();
        }

        $progressBar->complete();
        $this->display('Loaded products completed');
    }

    private function loadProductsFields(): array
    {
        $fieldsResponse = Client::product()->fields();
        $fieldsRaw = $fieldsResponse->getResult();

        return $this->getOptimizedFields($fieldsRaw);
    }

    private function writeProduct(array $product, array $fields): void
    {
        $productToCsv = [];

        foreach ($fields as $field){
            if(array_key_exists($field['key'], $product)){
                $productToCsv[] = $this->getFieldData($product[$field['key']], $field);
            }else{
                $productToCsv[] = "";
            }
        }

        CSV::getInstance()->writeToCsv($productToCsv);
    }
}