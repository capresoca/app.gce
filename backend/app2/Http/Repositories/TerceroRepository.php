<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/05/2018
 * Time: 2:45 PM
 */

namespace App\Http\Repositories;


use App\Models\General\GnTipdocidentidade;
use App\Traits\EnumsTrait;
use App\Traits\ToolsTrait;
use App\Models\Niif\GnTercero;
use http\Env\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TerceroRepository implements Repository
{
    /**
     * @param $terceroArray
     * @return GnTercero
     * @throws \Exception
     */
    public function guardar($terceroArray)
    {
        try {
            if (isset($terceroArray['id'])) {
                $terceroAEditar = GnTercero::find($terceroArray['id']);
                $terceroAEditar->fill($terceroArray);
                $terceroAEditar->nombre_completo = '';
                $terceroAEditar->save();
                return $terceroAEditar;
            }
            $tercero = new GnTercero();
            $tercero->fill($terceroArray);
            $tercero->nombre_completo = '';
            $tercero->save();
            return $tercero;

        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * @param $tercero
     * @throws ValidationException
     */
    public function validar($tercero)
    {

        $rules = [
            'gn_tipdocidentidad_id' => 'required|numeric|max:15',
            'identificacion' => 'required|unique:gn_terceros,identificacion',
            'nombre1' => 'required|string|max:45',
            'apellido1' => 'required|string|max:45',
            'direccion' => 'required|string|max:200',
            'gn_municipio_id' => 'required',
            'tipo_retencion' => 'required',
            'tipo_contribuyente' => 'required',
            'tipo_persona' => 'required',
            'nf_ciiu_id' => 'required|exists:nf_ciius,id',
            'ciudadania' => 'required'
        ];

        if ($tercero['correo_electronico']) {
            $rules['correo_electronico'] = 'email';
        }

        $tipos_identidad = GnTipdocidentidade::all()->pluck('id');
        if (isset($tercero['gn_tipdocidentidad_id'])) {

            $tipo_doc = GnTipdocidentidade::find($tercero['gn_tipdocidentidad_id']);

            if ($tipo_doc->count() > 0) {

                $rules['identificacion'] = 'required|unique:gn_terceros|max:' . $tipo_doc->longitud;

                if (isset($tercero['id'])) {
                    $rules['identificacion'] = [
                        'required',
                        'max:' . $tipo_doc->longitud,
                        Rule::unique('gn_terceros', 'identificacion')->ignore($tercero['id'])
                    ];


                }

                if ($tipo_doc->abreviatura === 'NIT') {
                    $rules['nombre1'] = 'max:45';
                    $rules['apellido1'] = 'max:45';
                }

            } else {
                $rules['gn_tipdocidentidad_id'] = 'required|in:' . $tipos_identidad;
            }
        }


        $tipos_reten = EnumsTrait::getEnumValues('gn_terceros', 'tipo_retencion');
        $tipos_contri = implode(',', EnumsTrait::getEnumValues('gn_terceros', 'tipo_contribuyente'));
        $tipos_persona = implode(',', EnumsTrait::getEnumValues('gn_terceros', 'tipo_persona'));

        $rules['tipo_contribuyente'] = 'required|in:' . $tipos_contri;
        $rules['tipo_persona'] = 'required|in:' . $tipos_persona;
        if ($tercero['tipo_contribuyente'] == 'No Responsables de IVA') {
            $tipos_reten = ToolsTrait::removerElement('Autorretenedor', $tipos_reten);
        }

        $tipos_reten = implode(',', $tipos_reten);

        $rules['tipo_retencion'] = 'required|in:' . $tipos_reten;

        if ($tercero['ica']) {
            $rules['porcentaje_ica'] = 'required|max:100|min:0';
        }
        if ($tercero['tipo_retencion'] === 'Autorretenedor') {
            $autorretenedores = implode(',', EnumsTrait::getEnumValues('gn_terceros', 'autorretenedor'));
            $rules['autorretenedor'] = 'required';
            $rules['autorretenedor.*'] = 'in:' . $autorretenedores;
        }
        $tipos_tercero = implode(',', EnumsTrait::getEnumValues('gn_terceros', 'tipo_tercero'));
        $rules['tipo_tercero'] = 'required';
        $rules['tipo_tercero.*'] = 'in:' . $tipos_tercero;

        $validator = Validator::make($tercero, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }
}