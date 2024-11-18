<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Http\Requests\Autorizaciones\AutsolicitudRequest;
use App\Http\Resources\Autorizaciones\AutsolicitudResource;
use App\Models\Autorizaciones\AuAutaprobada;
use App\Models\Autorizaciones\AuAutdetalle;
use App\Models\Autorizaciones\AuAutsolicitude;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class AutsolicitudController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                AuAutsolicitude::with('autorizacion.servicio')->pimp()
                    ->orderBy('au_autsolicitudes.updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return AutsolicitudResource::collection(
            AuAutsolicitude::with('autorizacion')->pimp()->get()
        );
    }


    public function show(AuAutsolicitude $autsolicitude)
    {
        $autsolicitude->load([
        'autorizacion.afiliado.regimen',
        'autorizacion.entidad_origen.municipio',
        'autorizacion.servicio.servicio',
        'autorizacion.contrato.entidad',
        'autorizacion.cie10_principal',
        'autorizacion.cie10_rel1',
        'autorizacion.cie10_rel2',
        'autorizacion.historia_clinica',
        'autorizacion.orden_medica',
        'autorizacion.contrato.servicios_contratados.servicio',
        'detalles'
    ]);

        return new Resource($autsolicitude);
    }

    public function update(AutsolicitudRequest $request, AuAutsolicitude $autsolicitude)
    {
        $estadoAnterior = $autsolicitude->estado;

        $autsolicitude->update($request->except('detalles','afiliado','contrato','entidad_origen','historia_clinica','orden_medica'));

        foreach ($request->detalles as $detalle) {
            $item = AuAutdetalle::find($detalle['id']);
            $item->fill($detalle);
            $item->save();
        }

        if($this->autorizando($request->estado,$estadoAnterior)){
            $this->autorizar($autsolicitude,$request);
        }

        return new Resource($autsolicitude);
    }

    private function autorizar(AuAutsolicitude $autsolicitude, $request)
    {
        $items = collect($request->detalles);

        $aprobacion = AuAutaprobada::create([
            'au_autorizacion_id'    => $autsolicitude->au_autorizacion_id,
            'au_autsolicitud_id'    => $autsolicitude->id,
            'rs_contrato_id'        => $autsolicitude->autorizacion->rs_contrato_id,
            'destino'               => $autsolicitude->autorizacion->tipo_destino,
            'valor_total'           => $items->sum('valorTotal'),
            'valor_usuario'         => $items->sum('valor_usuario'),
            'estado'                => 'Activa'
        ]);

        foreach ($request->detalles as $detalle) {
            $item = AuAutdetalle::find($detalle['id']);
            $item->au_autaprobada_id = $aprobacion->id;
            $item->save();
        }
    }

    private function autorizando($estadoNuevo ,$estadoAnterior)
    {
        return ($estadoAnterior != 'Autorizada' || $estadoAnterior != 'Autorizada Parcialmente') &&
            ($estadoNuevo === 'Autorizada' || $estadoNuevo === 'Autorizada Parcialmente');
    }
}
