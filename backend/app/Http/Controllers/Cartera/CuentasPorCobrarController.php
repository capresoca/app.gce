<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Repositories\Cartera\CuentasPorCobrarRepository;
use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Http\Requests\Cartera\CuentasPorCobrarRequest;
use App\Http\Resources\Cartera\CuentaPorCobrarResource;
use App\Http\Resources\Cartera\CuentasPorCobrarResource;
use App\Models\Cartera\CrCuentasxcobrar;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfRetencione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CuentasPorCobrarController extends Controller
{
    public function __construct(CuentasPorCobrarRepository $repoCuenta)
    {
        $this->repoCuenta = $repoCuenta;
    }

    public function index()
    {
        if (Input::get('per_page')){
            return CuentasPorCobrarResource::collection(
                CrCuentasxcobrar::with('cliente.tercero','niif','cencosto')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return CuentasPorCobrarResource::collection(CrCuentasxcobrar::with('cliente.tercero','niif','cencosto')->pimp()->orderBy('consecutivo', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $cuentaXCobrarRequest = $request->toArray();
            $this->repoCuenta->validar($cuentaXCobrarRequest);
            $cr_cuentasxcobrar = $this->repoCuenta->guardar($cuentaXCobrarRequest);
            $comdiarioRepository = new ComdiarioRespository();
            if ($request->estado === 'Confirmado') {
                $comdiarioRepository->createFromCxc($cr_cuentasxcobrar);
            }
            DB::commit();
            return new CuentasPorCobrarResource($cr_cuentasxcobrar);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show(CrCuentasxcobrar $cr_cuentasxcobrar)
    {
        return new CuentaPorCobrarResource($cr_cuentasxcobrar->load('cliente.niif','cliente.tercero','niif','cencosto','detalles.concepto.niif.clascuenta','detalles.conrtf','detalles.tercero','detalles.cencosto','detalles.niif.clascuenta','detalles.conrtf'));
    }

    public function update(CuentasPorCobrarRequest $request, CrCuentasxcobrar $cr_cuentasxcobrar)
    {
        $cr_cuentasxcobrar->update($request->all());
        $comdiarioRepository = new ComdiarioRespository();
        if ($request->estado === 'Confirmado') {
            $comdiarioRepository->createFromCxc($cr_cuentasxcobrar);
        }
        return new CuentasPorCobrarResource($cr_cuentasxcobrar);
    }

    public function destroy($id) {}
}
