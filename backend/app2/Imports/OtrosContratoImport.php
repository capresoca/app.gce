<?php

namespace App\Imports;

use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsOtroscontratado;
use App\Models\RedServicios\RsOtrosentidade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class OtrosContratoImport implements ToCollection
{
    protected $contrato;
    protected $guardado;
    protected $invalido;
    protected $rules;
    protected $messages;
    protected $headers;
    protected $estado;

    public function __construct(RsPlanescobertura $contrato)
    {

        $this->contrato = $contrato;
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

            $otroentidad = RsOtrosentidade::where([
                'codigo' => $row[0],
                'rs_entidades_id' => $this->contrato->rs_entidad_id
            ])->first();


            if(!$otroentidad){
                array_push($this->invalido,
                    [
                        'fila'=> $key + 1,
                        'row' => [
                            $this->headers[0] => $row[0],
                            $this->headers[1] => $row[1],
                        ],
                        'errors' => [
                            ['Este otro servicio aun no se ha asociado a esta entidad']
                        ]
                    ]
                );
                continue;
            }


            $cumcontratado = RsOtroscontratado::updateOrCreate(
                [
                    'rs_contratos_id' => $this->contrato->id,
                    'rs_otrosentidad_id' => $otroentidad->id
                ],
                [
                    'codigo'                => $otroentidad->codigo,
                    'tarifa_entidad'        => $otroentidad->tarifa,
                    'descripcion'           => $otroentidad->nombre,
                    'tarifa'                => $row[1]
                ]
            );

            array_push($this->guardado,$cumcontratado);

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
            '0' => 'required',
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
            '0.required' => 'El codigo de este otro servicio es obligatorio',
            '1.required' => 'La tarifa es requerida'
        ];
    }

    private function validarHeaders()
    {
        if($this->headers[0] != 'codigo_otro' || $this->headers[1] != 'tarifa_contratada'){
            $this->estado = false;
            return false;
        }
        $this->estado = true;
        return true;
    }

}
