<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Requests\Cartera\SaldosinicialesRequest;
use App\Http\Resources\Cartera\SaldosinicialesResource;
use App\Models\Cartera\CrSaldosiniciale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class SaldosInicialesController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return SaldosinicialesResource::collection(
                CrSaldosiniciale::with('cliente.tercero','cliente.niif','cliente.vendedor','vendedor.tercero','tercero','niif')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return SaldosinicialesResource::collection(CrSaldosiniciale::with('cliente.tercero','cliente.niif','cliente.vendedor','vendedor.tercero','tercero','niif')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(SaldosinicialesRequest $request)
    {
        try {
            $saldosIniciales = CrSaldosiniciale::create($request->all());
            return response()->json([
                    'message' => 'El cliente fue creado con exito',
                    'data' => CrSaldosiniciale::where('id',$saldosIniciales->id)->with('cliente.tercero','cliente.niif','cliente.vendedor','vendedor.tercero','tercero','niif')->first()
                ]);
        } catch (\Exception $e) {
            return response()->json([
                    'message' => $e->getMessage()                    
                ]);
        }
    }


    public function show(CrSaldosiniciale $saldosIniciales)
    {
        return new SaldosinicialesResource($saldosIniciales);
    }


    public function update(SaldosinicialesRequest $request, $id)
    {
        try {
        $saldo = CrSaldosiniciale::find($id);
        $saldo->update($request->all());
        $saldo=$saldo->where('id',$id)->with('cliente.tercero','cliente.niif','cliente.vendedor','vendedor.tercero','tercero','niif')->first();
        return response()->json([
                'message' => 'El cliente fue editado con exito',
                'data' => $saldo
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function getConsecutivo()
    {
        $saldo = CrSaldosiniciale::orderBy('id','DESC')->count();
        return response()->json([
                'exist' => true,
                'data' => $saldo+1
            ]);
    }
    public function findById($id)
    {
        try {
            $saldo=CrSaldosiniciale::where('id',$id)->with('cliente.tercero','cliente.niif','cliente.vendedor','vendedor.tercero','tercero','niif')->first();
            if ($saldo) {
                return response()->json([
                        'exists' => true,
                        'message' => 'El saldo inicial existe',
                        'data' => $saldo
                    ]);
            }
            return response()->json([
                'exists' => false,
                'message' => 'El saldo inicial no existe'
            ],204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se presento un error en el servidor',
                'errors' => $e->getMessage()
            ], 500);
        }
    } 
}