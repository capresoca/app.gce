<?php

namespace App\Imports;

use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupsentidade;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;

class CupsEntidadImport implements ToCollection
{
    protected $entidad;
    protected $guardado;
    protected $invalido;
    protected $rules;
    protected $messages;
    protected $headers;
    protected $estado;

    public function __construct(RsEntidade $entidad)
    {
        $this->entidad = $entidad;
        $this->guardado = [];
        $this->invalido = [];
        $this->rules = $this->rules();
        $this->messages = $this->messages();

    }


    public function collection(Collection $collection)
    {
        $this->headers = [$collection[0][0],$collection[0][1]];

        if(!$this->validarHeaders()){
            return false;
        }

        $collection->pull(0);

        foreach ($collection as $key => $row) {
            if(!$row[0]&&!$row[1]) continue;
            if(!$this->validar($row->toArray(), $key)) continue;

            $cup = RsCups::whereCodigo($row[0])->first();

            $cupentidad = RsCupsentidade::updateOrCreate([
                'rs_cups_id' => $cup->id,
                'rs_entidad_id' => $this->entidad->id
            ],[

                'tarifa' => $row[1]
            ]);

            $cupentidad->load('cup');

            array_push($this->guardado,$cupentidad);
        }

    }

    public function getInforme()
    {
        return [
            'valido' => $this->estado,
            'guardado' => $this->guardado,
            'invalido' => $this->invalido
        ];
    }

    private function rules()
    {
        return [
            '0' => 'required|exists:rs_cupss,codigo',
            '1' => 'required|numeric'
        ];
    }

    private function validar($row, $fila)
    {
        $validator = Validator::make($row, $this->rules, $this->messages);

        if($validator->fails()){
            array_push($this->invalido,
                [
                    'fila'=> $fila + 1,
                    'row' => [
                        $this->headers[0] => $row[0],
                        $this->headers[1] => $row[1],
                    ],
                    'errors' => $validator->errors()->toArray()
                ]);
            return false;
        }

        return true;

    }

    private function messages()
    {
        return [
            '0.exists' => 'El codigo cup no existe',
            '0.required' => 'El codigo cup es obligatorio',
            '1.required' => 'La tarifa es requerida'
        ];
    }

    private function validarHeaders()
    {
        if($this->headers[0] != 'codigo_cup' || $this->headers[1] != 'tarifa_entidad'){
            $this->estado = false;
            return false;
        }
        $this->estado = true;
        return true;
    }
}
