<?php

namespace App\Commands\Export;

use App\Commands\BaseCommand;
use App\Support\API\Bitrix\Client;
use App\Support\API\Helpers;
use App\Support\File\CSV;

class SectionController extends BaseCommand
{
    use Helpers;

    public function handle(): void
    {

        CSV::$path = 'sections.csv';
        CSV::deleteFile();

        $this->display('Start Load sections');

        $sectionFields = $this->loadFields();
        $fieldsToCsv = [];
        foreach ($sectionFields as $field){
            $fieldsToCsv[] = $field['title'];
        }

        CSV::getInstance()->writeToCsv($fieldsToCsv);

        $response = Client::section()->list();

        $progressBar = $this->getProgressBar();
        $progressBar->setMaxProgress($response->getByKey('total') ?? count($response->getResult() ?? []));

        foreach (Client::section()->listGen() as $sectionItem) {
            $this->write($sectionItem, $sectionFields);
            $progressBar->advance()->display();
        }

        $progressBar->complete();
        $this->display('Loaded products completed');
    }

    public function write(array $sectionData, array $fields)
    {
        $sectionToCsv = [];

        foreach ($fields as $field){
            if(array_key_exists($field['key'], $sectionData)){
                $sectionToCsv[] = $this->getFieldData($sectionData[$field['key']], $field);
            }else{
                $sectionToCsv[] = "";
            }
        }

        CSV::getInstance()->writeToCsv($sectionToCsv);
    }

    private function loadFields(): array
    {
        $fieldsResponse = Client::section()->fields();
        $fieldsRaw = $fieldsResponse->getResult();

        return $this->getOptimizedFields($fieldsRaw);
    }
}