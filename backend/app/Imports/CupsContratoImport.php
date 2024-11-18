<?php

namespace App\Imports;

use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupscontratados;
use App\Models\RedServicios\RsCupsentidade;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsSalminimo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class CupsContratoImport implements ToCollection
{
    protected $contrato;
    protected $guardado;
    protected $invalido;
    protected $rules;
    protected $messages;
    protected $headers;

    public function __construct(RsPlanescobertura $contrato)
    {
        $this->contrato = $contrato;
        $this->guardado = [];
        $this->invalido = [];
        $this->messages = $this->messages();
    }

    public function collection(Collection $collection)
    {
        $this->headers = [$collection[0][0],$collection[0][1],$collection[0][2],$collection[0][3],$collection[0][4]];

        if(!$this->validarHeaders()){
            return false;
        }

        $collection->pull(0);

        foreach ($collection as $key => $row) {
            $this->rules = $this->rules($row);
            if(!$row[0]&&!$row[1]) continue;

            if(!$this->validar($row->toArray(), $key)) continue;

            $cup = RsCups::whereCodigo($row[0])->first();
            if(strtoupper($row[1]) === 'SOAT' && !$cup->cm_mansoat_id){
                $this->push_error($key,$row,['Este CUP no tiene tarifa SOAT, puede incluirlo como tarifa institucional u homologar a codigo soat']);
                continue;
            }

            if(strtoupper($row[1]) === 'ISS' && !$cup->cm_maniss_id){
                $this->push_error($key,$row,['Este CUP no tiene tarifa ISS, puede incluirlo como tarifa institucional u homologar a codigo ISS']);
                continue;
            }

            $salminimo = $salminimo = RsSalminimo::where('anio',$row[2])->first();

            $cupentidad = RsCupsentidade::firstOrCreate(
                [
                    'rs_cups_id' => $cup->id,
                    'rs_entidad_id' => $this->contrato->rs_entidad_id
                ],
                [
                    'tarifa' => $cup->calcularValor($row[1],$salminimo,$row[3])
                ]
            );

            if(!$cupentidad){
                $this->push_error($key,$row,['Este CUP aun no se ha asociado a esta entidad']);
                continue;
            }


            $cupcontratado = RsCupscontratados::updateOrCreate([
                'rs_contrato_id' => $this->contrato->id,
                'rs_cups_id' => $cup->id
            ],
            [
                'tarifa'                => $cup->calcularValor($row[1],$salminimo,$row[3],$row[4]),
                'tarifa_entidad'        => $cupentidad->tarifa,
                'codigo'                => $cup->codigo,
                'descripcion'           => $cup->descripcion,
                'tipo_valor'            => $cup->tipo_valor,
                'nivel_autorizacion'    => $cup->nivel_autorizacion,
                'rs_salminimo_id'       => $salminimo->id,
                'tipo_manual'           => strtoupper($row[1]),
                'porcentaje'            => $row[3]
            ]);

            array_push($this->guardado,$cupcontratado);

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

    private function rules($row)
    {
        $rules =  [
            '0' => 'required|exists:rs_cupss,codigo',
            '1' => 'required|in:soat,iss,institucional'
        ];

        if($row[1] != 'institucional'){
            $rules['2'] = 'required|numeric|min:-100';
        }

        if($row[1] === 'soat'){
            $rules['3'] = 'required|date_format:Y';
        }

        return $rules;
    }

    private function validar($row, $fila)
    {
        $validator = Validator::make($row, $this->rules($row), $this->messages);

        if($validator->fails()){
            array_push($this->invalido,
                [
                    'fila'=> $fila + 1,
                    'row' => [
                        $this->headers[0] => $row[0],
                        $this->headers[1] => $row[1],
                        $this->headers[2] => $row[2],
                        $this->headers[3] => $row[3],
                        $this->headers[4] => $row[4],
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
        if($this->headers[0] != 'codigo_cup'
            ||  $this->headers[0] != 'codigo_cup'
            ||  $this->headers[1] != 'tipo_manual'
            ||  $this->headers[2] != 'año'
            ||  $this->headers[3] != 'porcentaje'
            ||	$this->headers[4] != 'tarifa_intitucional'){

            $this->estado = false;
            return false;
        }
        $this->estado = true;
        return true;
    }

    private function push_error($key,$row, $messages){
        array_push($this->invalido,
            [
                'fila'=> $key + 1,
                'row' => [
                    $this->headers[0] => $row[0],
                    $this->headers[1] => $row[1],
                    $this->headers[2] => $row[2],
                    $this->headers[3] => $row[3],
                    $this->headers[4] => $row[4]
                ],
                'errors' => [
                    $messages
                ]
            ]
        );
    }
}
