<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Resources\Mipres\PrescripcionResource;
use App\Mail\PrescripcionAprobada;
use App\Models\Mipres\MpPrescripcione;
use App\Http\Controllers\Controller;
use App\Models\RedServicios\RsEntidade;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Models\Mipres\MpComplementario;
use App\Models\Mipres\MpDireccionamiento;
use App\Models\Mipres\MpDispositivo;
use App\Models\Mipres\MpEntrega;
use App\Models\Mipres\MpJuntaProfesional;
use App\Models\Mipres\MpMedicamento;
use App\Models\Mipres\MpNovedade;
use App\Models\Mipres\MpNutricional;
use App\Models\Mipres\MpProcedimiento;
use App\Models\Mipres\MpSuministro;

class PrescripcionController extends Controller
{
    public function index(){

        if(Input::get('per_page') || Input::get('all')){
            return PrescripcionResource::collection(
                MpPrescripcione::pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            MpPrescripcione::withAll()
                ->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );

    }

    public function show(MpPrescripcione $prescripcione)
    {
        $prescripcione->load($prescripcione->allRelations());
        return new Resource($prescripcione);
    }

    public function update(MpPrescripcione $prescripcione, Request $request)
    {
        $rules = [
            'estado' => 'required',
            'farmacia_id' => $request->estado === 'Aprobado' ? 'required' :''
        ];

        $this->validate($request,$rules);

        if($request->estado === 'Aprobado' && $prescripcione->estado != 'Aprobado'){

            foreach ($request->entregas as $entrega) {

            }
        }

        $prescripcione->update($request->all());

        return new Resource($prescripcione);

    }

    public function mail(MpPrescripcione $prescripcione)
    {
        return view('mail.prescripcion_aprobada',['prescripcion' => $prescripcione]);
    }
    
    public function destroy($id)
    {
        try {
          
            $d1 = MpComplementario::where('mp_prescripcion_id', $id)->delete();
            $d2 = MpDireccionamiento::where('mp_prescripcion_id', $id)->delete();
            $d3 = MpDispositivo::where('mp_prescripcion_id', $id)->delete();
            $d5 = MpJuntaProfesional::where('mp_prescripcion_id', $id)->delete();
            $d6 = MpMedicamento::where('mp_prescripcion_id', $id)->delete();
            $d7 = MpNovedade::where('mp_prescripcion_id', $id)->delete();
            $d8 = MpNutricional::where('mp_prescripcion_id', $id)->delete();
            $d9 = MpProcedimiento::where('mp_prescripcion_id', $id)->delete();
            $d10 = MpPrescripcione::where('id', $id)->delete();
            
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
