<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/08/2018
 * Time: 5:14 PM
 */

namespace App\Capresoca;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

trait LeerCsv
{
    protected function leer($path){

        $reader = Reader::createFromPath($path);
        return $reader->getRecords();

    }

    protected function leerFromUrl($url){
        $contents = Storage::disk('local')->get($url);
        Log::info($contents);
        $reader = Reader::createFromString($contents,'r');
        $reader->setOutputBOM(Reader::BOM_UTF8);
        $reader->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
        return $reader;
    }

    protected function leerFromUrlS3($url){
        $contents = Storage::get($url);
        Log::info($contents);
        $reader = Reader::createFromString($contents,'r');
        $reader->setOutputBOM(Reader::BOM_UTF8);
        $reader->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
        return $reader;
    }

}