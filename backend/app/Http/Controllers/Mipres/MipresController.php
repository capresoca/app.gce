<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Resources\Mipres\PrescripcionResource;
use App\Jobs\Mipres\HistoricoJuntas;
use App\Jobs\Mipres\JuntasMedicasXFecha;
use App\Jobs\Mipres\PrescripcionesPorFecha;
use App\Jobs\Mipres\ReporteEntregaXFecha;
use App\Jobs\Mipres\TutelasFecha;
use App\Models\Mipres\MpPrescripcione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Mipres\MpFalloAdicional;
use App\Models\Mipres\MpComplementario;
use App\Models\Mipres\MpDireccionamiento;
use App\Models\Mipres\MpDispositivo;
use App\Models\Mipres\MpJuntaProfesional;
use App\Models\Mipres\MpMedicamento;
use App\Models\Mipres\MpNovedade;
use App\Models\Mipres\MpNutricional;
use App\Models\Mipres\MpProcedimiento;
use App\Models\Mipres\MpTutela;
use App\Models\Mipres\MpNovetutela;
use App\Models\Mipres\MpEntrega;
use Illuminate\Support\Facades\DB;

class MipresController extends Controller
{
    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function prescripciones_fecha(Request $request)
    {
       $this->validate($request,[
            'fecha' => 'required|date',
            'regimen' => 'required|in:Subsidiado,subsidiado,Contributivo,contributivo'
        ]);

        $prescipciones = new PrescripcionesPorFecha($request->fecha,$request->regimen);
        $prescipciones->store();

        return PrescripcionResource::collection(
            MpPrescripcione::where([
                'FPrescripcion' => $request->fecha,
                'regimen' => $request->regimen
            ])->orderBy('id','desc')
                ->paginate(Input::get('per_page'))
        );
    }

    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function juntas_fecha(Request $request)
    {
        $this->validate($request,[
            'fecha' => 'required|date',
            'regimen' => 'required|in:Subsidiado,subsidiado,Contributivo,contributivo'
        ]);

        $juntas = new JuntasMedicasXFecha($request->fecha,$request->regimen);
        $juntas->store();
        return Response()->json($juntas->getJuntas(),200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function tutelas_fecha(Request $request)
    {
        $this->validate($request,[
            'fecha' => 'required|date',
            'regimen' => 'required|in:Subsidiado,subsidiado,Contributivo,contributivo'
        ]);

        $juntas = new TutelasFecha($request->fecha,$request->regimen);
        $juntas->store();
        return Response()->json($juntas->getTutelas(),200);
    }

    public function historico_juntas(Request $request)
    {
        $this->validate($request,[
            'desde' => 'required|date'
        ]);

        $juntas = new HistoricoJuntas();
        $juntas->descargar();
        return Response()->json('Juntas Actualizadas',200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reportes_fecha(Request $request)
    {
        $this->validate($request,[
            'fecha' => 'required|date',
            'regimen' => 'required|in:Subsidiado,subsidiado,Contributivo,contributivo'
        ]);

        $reportes = new ReporteEntregaXFecha($request->fecha,$request->regimen);
        $reportes->store();
        return Response()->json($reportes->getReportes(),200);
    }

    public function destroy($id)
    {
        try {
            $nrd = DB::delete('delete from mp_complementarios WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_direccionamientos WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_dispositivos WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_entregas WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_medicamentos WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_nutricionals WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_procedimientos WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_novetutelas WHERE mp_tutela_id='.$id);
            $nrd = DB::delete('delete from mp_nutricionals WHERE mp_tutela_id='.$id);
            /*$d2     = MpDireccionamiento::where('mp_tutela_id', '=' , $id)->delete();
            $d1     = MpComplementario::where('mp_tutela_id', '=', $id)->delete();
            $d3     = MpDispositivo::where('mp_tutela_id', '=', $id)->delete();
            $d4     = MpFalloAdicional::where('mp_tutela_id', '=', $id)->delete();
            $d6     = MpMedicamento::where('mp_tutela_id', '=', $id)->delete();
            $d8     = MpNutricional::where('mp_tutela_id', '=', $id)->delete();
            $d9     = MpProcedimiento::where('mp_tutela_id', '=', $id)->delete();
            $d10    = MpNovetutela::where('mp_tutela_id', '=', $id)->delete();*/
            //$d11    = MpNovetutela::where('mp_tutelafinal_id', $id)->delete();
            $d12    = MpTutela::where('id', $id)->delete();
            
            
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
