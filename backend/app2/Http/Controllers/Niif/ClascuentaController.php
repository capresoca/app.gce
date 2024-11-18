<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\ClascuentaRequest;
use App\Http\Resources\Niif\ClascuentasResource;
use App\Models\Niif\NfClascuenta;
use App\Models\Niif\NfNiif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClascuentaController extends Controller
{

    public function index()
    {
        return ClascuentasResource::collection(NfClascuenta::all());
    }


    public function store(ClascuentaRequest $request)
    {
        try{
            DB::BeginTransaction();
            $clascuenta = NfClascuenta::create($request->all());
            $this->addNiif($clascuenta);
            DB::commit();
            return new ClascuentasResource($clascuenta);
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }


    public function show(NfClascuenta $clascuenta)
    {
        return new ClascuentasResource($clascuenta);
    }


    public function update(ClascuentaRequest $request, NfClascuenta $clascuenta)
    {
        $clascuenta->update($request->all());
        return new ClascuentasResource($clascuenta);
    }

    protected function addNiif($clascuenta){
        $niif = new NfNiif();
        $niif->nombre = $clascuenta->nombre;
        $niif->codigo = $clascuenta->codigo;
        $niif->clascuenta_id = $clascuenta->id;
        $niif->nf_nivcuenta_id = 1;
        $niif->save();
        Cache::forget('puc');
    }

}
