<?php

namespace App\Support\API;

trait Helpers
{

    private function fieldIsMultiply(array $field)
    {
        return $field['isMultiple'] ?? false;
    }

    private function getOptimizedFields(array $fieldsList): array
    {
        $fields = [];
        foreach ($fieldsList as $key => $fieldParams){
            $fData = [
                'key' => $key,
                'isMultiple' => (bool)$fieldParams['isMultiple'],
                'type' => $fieldParams['type'] == 'product_property'
                    ? $fieldParams['propertyType']
                    : $fieldParams['type'],
                'title' => $fieldParams['title'],
            ];
            $fields[] = $fData;
        }

        return $fields;
    }
    private function getFieldData(mixed $data, array $field)
    {
        if($this->fieldIsMultiply($field)){
            return $this->getArrayedFieldData($data, $field['type']);
        }else{
            return $this->getSingleFieldData($data, $field['type']);
        }
    }

    private function getArrayedFieldData(mixed $data, string $type): string
    {
        $allValues = [];

        if (is_null($data)) return "";

        if($type == 'product_file' || $type == 'F'){
            foreach ($data as $value){
                if(array_key_exists('id', $value)){
                    $allValues[] = $value['id'];
                }else{
                    $allValues[] = "ERROR";
                }
            }
        }else{
            foreach ($data as $value){
                if(is_array($value)){
                    if(array_key_exists('value', $value)){
                        $allValues[] = $value['value'];
                    }else{
                        $allValues[] = "ERROR";
                    }
                }else{
                    $allValues[] = $value;
                }
            }
        }

        return implode('|', $allValues);
    }

    private function getSingleFieldData(mixed $data, string $type)
    {

        if($type == 'product_file' && $type == 'F'){
            return $data['id'];
        }else{
            if(is_array($data)){
                if(array_key_exists('value', $data)){
                    return $data['value'];
                }else{
                    return "ERROR";
                }
            }else{
                return (string)$data;
            }
        }
    }
}