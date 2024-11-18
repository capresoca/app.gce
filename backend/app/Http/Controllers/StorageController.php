<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function getOpenApiJson(){
        $json = Storage::disk('local')->get('api-docs/api-docs.json');
        return $json;
    }
}
