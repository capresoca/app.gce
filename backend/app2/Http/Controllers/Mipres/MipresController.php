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

}
