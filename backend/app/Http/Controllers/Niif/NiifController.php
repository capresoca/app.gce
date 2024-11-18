<?php

namespace App\Http\Controllers\Niif;

use App\Exports\Administrativo\NiifExport;
use App\Http\Requests\Niif\NiifRequest;
use App\Http\Resources\Niif\NiifResource;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfAnedeclaracione;
use App\Models\Niif\NfClascuenta;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfNivcuenta;
use Barryvdh\DomPDF\Facade as PDF;
use App\Traits\EnumsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class NiifController extends Controller
{

    public function index()
    {
        if(Input::get('descendencia')){
            return NiifResource::collection(NfNiif::pimp()->orderBy('codigo')->with($this->getDescendencia())->get());
        }
        return NiifResource::collection(NfNiif::pimp()->orderBy('codigo')->get());
    }

    public function puc()
    {
        try{

            if(!Cache::has('puc')){
                $niifs = NfNiif::where('nf_nivcuenta_id',1)->with($this->getDescendencia())->orderBy('codigo')->get();
                Cache::forever('puc', $niifs);
            }
            $niifs = Cache::get('puc');

            return NiifResource::collection($niifs);

        }catch (\Exception $exception){
            return $exception;
        }
    }


    public function store(NiifRequest $request)
    {

        try{

            $niif = NfNiif::create($request->all());
            $niif->load('children');
            Cache::forget('puc');
            return new NiifResource($niif);

        }catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(NfNiif $niif)
    {
        $niif->load('children');
        return new NiifResource($niif);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NiifRequest $request, NfNiif $niif)
    {
        try{
            $newNiif = $request->all();
            array_forget($newNiif, 'children');
            $niif->update($newNiif);
            $niif->load($this->getDescendencia());
            Cache::forget('puc');
            return new NiifResource($niif);

        }catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getDescendencia()
    {
        $nivcuentas = NfNivcuenta::all();
        $descendencia = 'children';
        for($i = 0; $i < $nivcuentas->count() - 2; $i++ ){
            $descendencia = $descendencia.'.children';
        }
        return $descendencia;
    }


    public function getComplementos() {
        $complementos = collect();
        $complementos->put('clasescuentas', NfClascuenta::where('estado', 'Activo')->get());
        $complementos->put('nivelescuentas', NfNivcuenta::where('estado', 'Activo')->get());
        $complementos->put('anexosdeclaracion', NfAnedeclaracione::where('estado', 'Activo')->get());
        $tiposniif = EnumsTrait::getEnumValues('nf_niifs','tipo');
        $complementos->put('tiposniif',$tiposniif);
        $tiposrten = EnumsTrait::getEnumValues('nf_niifs','tipo_retencion');
        $complementos->put('tiposreteniif', $tiposrten);
        $dian = GnTercero::find(1);
        $complementos->put('dian',$dian);
        $alcaldia = GnTercero::find(2);
        $complementos->put('alcaldia', $alcaldia);
        return response()->json([
            'data' => $complementos
        ],200);
    }

    public function searchAuxiliares($search = '') {

        $niifs = NfNiif::where('nf_nivcuenta_id',5)
            ->where(function ($query) use ($search){
                $query->where('codigo',$search)
                    ->orWhere('nombre','like', '%'.$search.'%');
            })->get();
        if($niifs){
            return NiifResource::collection($niifs);
        }
    }

    public function getReporte ()
    {   
        try {
            $mime = Input::get('file');
            $all = Input::get('all');
            $codigoA = Input::get('codigoA');
            $codigoB = Input::get('codigoB');
            if ($all) {
                $niifs = NfNiif::with('padre','nivel')->orderBy('codigo','asc')->get();
            } else {
                $niifs = NfNiif::with('padre','nivel')->whereBetween('codigo',[$codigoA,$codigoB])->orderBy('codigo','asc')->get();
            }

            if ($mime === 'application/pdf') {
                return $this->getPdf($niifs);
            } elseif ($mime === 'application/xls') {
                return $this->getExcel($niifs);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPdf ($data)
    {
        if (Input::get('html')) {return view('dompdf.reporteNiifs');}
        setlocale(LC_ALL, "es_ES");
        $pdf = PDF::loadView('dompdf.reporteNiifs', ['niifs' => $data]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('PLAN DE CUENTAS', ['Attachment' => 0]);
    }

    public function getExcel ($data) {
        return (new NiifExport($data))->download('reporteNiif.xlsx');
    }
}
