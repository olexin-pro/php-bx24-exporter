<?php

namespace App\Support\File;

use App\Support\Singleton;

class CSV extends Singleton
{

    public static string $path = 'export.csv';
    private static mixed $file;

    public function __destruct()
    {
        if(isset(self::$file)){
            fclose(self::$file);
            self::$file = false;
        }
    }

    public static function getInstance(): static
    {

        if(!isset(self::$file)){
            self::$file = fopen(self::$path, 'w');
        }

        return parent::getInstance();
    }

    public function writeToCsv(array $fields): void
    {
        fputcsv(self::$file, $fields);
    }

    public static function deleteFile(string $filePath = null): void
    {
        $file = is_null($filePath)
            ? self::$path
            : $filePath;

        if(file_exists($file)){
            unlink($file);
        }
    }
}