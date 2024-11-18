<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/05/2018
 * Time: 5:10 PM
 */

namespace App\Http\Repositories\Aseguramiento;

use App\Http\Repositories\Repository;
use App\Http\Repositories\TerceroRepository;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnTipdocidentidade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AfiliadoRepository implements Repository
{
    public function guardar($afiliadoArray)
    {
        $repoTercero = new TerceroRepository();
        $tercero = $repoTercero->guardar($afiliadoArray['tercero']);
        $afiliadoArray['gn_tercero_id'] = $tercero->id;
        array_forget($afiliadoArray, 'tercero');
        if (isset($afiliadoArray['id'])) {
            $afiliadoAEditar = AsAfiliado::findOrFail($afiliadoArray['id']);

            $afiliadoAEditar->update($afiliadoArray);

            return $afiliadoAEditar;
        }
        $afiliado = new AsAfiliado();
        $afiliado->fill($afiliadoArray);
        $afiliado->nombre_completo = '';
        $afiliado->identificacion = '';
        $afiliado->tipo_identificacion = '';
        $afiliado->save();

        return $afiliado;
    }

    public function validar($afiliado)
    {
        $rules = [
            'gn_zona_id' => 'required|in:1,2',
            'gn_sexo_id' => 'required|in:1,2',
            'tercero' => 'required',
        ];

        if ($afiliado['as_pobespeciale_id'] === 4 && $this->as_regimene_id === 2) {
            $rules['ficha_sisben'] = 'required';
            $rules['nivel_sisben'] = 'required';
        }

        if (isset($afiliado['tercero']) && $afiliado['tercero'] !== []) {
            $tipo_doc = GnTipdocidentidade::find($afiliado['tercero']['gn_tipdocidentidad_id']);
            $fecha_nacimiento_min = today()->subMonths($tipo_doc->edad_minima);
            $fecha_nacimiento_max = today()->subMonths($tipo_doc->edad_maxima)->subDay($tipo_doc->plazo_cambio);

            $rules['fecha_nacimiento'] = 'required|date|before:'.$fecha_nacimiento_min.'|after_or_equal:'.$fecha_nacimiento_max;
            $repoTercero = new TerceroRepository();
            $repoTercero->validar($afiliado['tercero']);
        } else {
            $rules['gn_tercero_id'] = 'required|unique:gn_terceros,id';
        }

        if ($afiliado['gn_zona_id'] === 2) {
            $rules['gn_vereda_id'] = 'required';
        } elseif ($afiliado['gn_zona_id'] === 1) {
            $rules['gn_barrio_id'] = 'required';
        }

        $validator = Validator::make($afiliado, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }
}