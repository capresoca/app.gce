<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbExtraLaboral;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class TbExtraLaboralController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $extra = TbExtraLaboral::pimp()->orderBy('consecutivo_extra_laboral', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($extra);
        }
        return Resource::collection(TbExtraLaboral::pimp()->orderBy('consecutivo_extra_laboral', 'desc')->get());
    }

    public function store(Request $request)
    {
        $extra = new TbExtraLaboral();
        $extra->descripcion = $request->descripcion;
        $extra->tipo_documento_nomina = $this->tipoDocumentoNomina($request->tipo_documento_nomina);
        $extra->tipo_valor_nomina = $this->tipoValorNomina($request->tipo_valor_nomina);
        // $extra->consecutivo_cuenta_debito = $request->consecutivo_cuenta_debito;
        // $extra->consecutivo_cuenta_credito = $request->consecutivo_cuenta_credito;        
        $extra->save();
    }

    public function actualizar (Request $request, $id)
    {
        $extra = TbExtraLaboral::find($id);
        $extra->descripcion = $request->descripcion;
        $extra->tipo_documento_nomina = $this->tipoDocumentoNomina($request->tipo_documento_nomina);
        $extra->tipo_valor_nomina = $this->tipoValorNomina($request->tipo_valor_nomina);
        // $extra->consecutivo_cuenta_debito = $request->consecutivo_cuenta_debito;
        // $extra->consecutivo_cuenta_credito = $request->consecutivo_cuenta_credito;        
        $extra->save();

        return response()->json([
            'message' => 'Se ha actualizado correctamente.',
        ]);
    }

    public function destroy($id)
    {
        try {
            $extra = TbExtraLaboral::find($id);
            $extra->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function tipoDocumentoNomina($valor)
    {
        $tdn = array(
            array("Deducido",1),
            array("Devengado",2),
            array("Empresa",3),
            array("Deducido Constitutivo Salarial",4),
            array("Devengado Constitutivo Salarial",5)
        );
        
        $longitud = count($tdn);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tdn[$i][0]) {
                $idtdn = $tdn[$i][1];
            }
        }

        return $idtdn;
    }

    public function tipoValorNomina ($valor)
    {
        $tvn = array(
            array("Vacaciones",38),
            array("Descuento de Seguro de Vida Colectivo",8),
            array("Hora Extra Diurna",11),
            array("Hora Extra Festiva Nocturna",12),
            array("Hora Extra Nocturna",13),
            array("Hora Festiva Diurna",14),
            array("Hora Festiva Nocturna",15),
            array("Incapacidad General",17),
            array("Licencia de Maternidad",20),
            array("Licencia No Remunerada",21),
            array("Otros Deducidos",22),
            array("Otros Devengados",23),
            array("Pago Mera Liberalidad",25),
            array("Recargo Nocturno",30),
            array("Retención en la Fuente",31),
            array("Auxilio de Rodamiento",39)
        );

        $longitud = count($tvn);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tvn[$i][0]) {
                $idtvn = $tvn[$i][1];
            }
        }

        return $idtvn;
    }
}
