<?php

namespace App\Imports;

use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsCumcontratados;
use App\Models\RedServicios\RsCumentidade;
use App\Models\RedServicios\RsCupscontratados;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class CumsContratoImport implements ToCollection
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
            $explodeConsecutivo = explode('-',$row[0]);

            if(isset($explodeConsecutivo[1])){
                $row[0] = (int)$explodeConsecutivo[0].'-'.(int)$explodeConsecutivo[1];
            }

            if(!$this->validar($row->toArray(), $key)) continue;

            $cum = RsCum::whereCodigo($row[0])->first();

            $cumentidad = RsCumentidade::where([
                'rs_cum_id' => $cum->id,
                'rs_entidad_id' => $this->contrato->rs_entidad_id
            ])->first();


            if(!$cumentidad){
                array_push($this->invalido,
                    [
                        'fila'=> $key + 1,
                        'row' => [
                            $this->headers[0] => $row[0],
                            $this->headers[1] => $row[1],
                        ],
                        'errors' => [
                            ['Este CUM aun no se ha asociado a esta entidad']
                        ]
                    ]
                );
                continue;
            }


            $cumcontratado = RsCumcontratados::updateOrCreate(
                [
                    'rs_contratos_id' => $this->contrato->id,
                    'rs_cum_id' => $cum->id
                ],
                [
                    'tarifa'                => $row[1],
                    'tarifa_entidad'        => $cumentidad->tarifa,
                    'codigo'                => $cum->codigo,
                    'descripcion'           => $cum->producto.' - '.$cum->titular,
                    'nombre'            =>  $cum->descripcion_atc.' - '.$cum->principio_activo.' - ADMINISTRACIÓN '.$cum->via_administracion. ' - '.$cum->descripcion_comercial,
                    'tipo_valor'        =>  $cum->tipo_valor
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
            '0' => 'required|exists:rs_cums,codigo',
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
            '0.exists' => 'El codigo cum no existe',
            '0.required' => 'El codigo cum es obligatorio',
            '1.required' => 'La tarifa es requerida'
        ];
    }

    private function validarHeaders()
    {
        if($this->headers[0] != 'codigo_cum' || $this->headers[1] != 'tarifa_contratada'){
            $this->estado = false;
            return false;
        }
        $this->estado = true;
        return true;
    }
}