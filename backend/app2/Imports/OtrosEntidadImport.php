<?php

namespace App\Imports;

use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsOtrosentidade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class OtrosEntidadImport implements ToCollection
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
        $this->headers = [$collection[0][0],$collection[0][1],$collection[0][2]];
        if(!$this->validarHeaders()){
            return false;
        }
        $collection->pull(0);

        foreach ($collection as $key => $row) {
            if(!$row[0]&&!$row[1]) continue;
            if(!$this->validar($row->toArray(), $key)) continue;


            $otroEntidad = RsOtrosentidade::updateOrCreate([
                'codigo' => $row[0],
                'rs_entidades_id' => $this->entidad->id
            ],[
                'nombre' => $row[1],
                'tarifa' => $row[2]
            ]);


            array_push($this->guardado,$otroEntidad);
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
            '0' => 'required|numeric',
            '1' => 'required|string|max:500',
            '2' => 'required|numeric'
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
                        $this->headers[2] => $row[2]
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
            '0.numeric' => 'El codigo debe ser un string',
            '0.required' => 'El codigo es obligatorio',
            '1.required' => 'El nombre es requerido',
            '1.string' => 'El nombre debe ser un texto',
            '2.required' => 'La tarifa es requerida',
            '2.numeric' => 'La tarifa debe ser un valor'
        ];
    }

    private function validarHeaders()
    {
        if($this->headers[0] != 'codigo_otro' || $this->headers[1] != 'nombre_otro'|| $this->headers[2] != 'tarifa_entidad'){
            $this->estado = false;
            return false;
        }
        $this->estado = true;
        return true;
    }
}
