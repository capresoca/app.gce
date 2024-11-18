<?php


namespace App\Imports;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\CuentasMedicas\CmCenso;
use App\Models\CuentasMedicas\CmPacientesHospitalario;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class CensoImport implements ToCollection
{
    const RULES = [
        '0' => 'required',
        '1' => 'required',
        '2' => 'required',
        '3' => 'required|date',
    ];

    protected $censo;
    protected $invalido;
    protected $guardado;
    protected $pacientes;

    public function __construct()
    {
        $this->invalido = [];
        $this->guardado = [];
        $this->pacientes = [];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $collection->pull(0);

        foreach ($collection as $key => $row) {

            if(!$this->validar($row->toArray(), $key)) continue;

            if($this->pacienteExiste($row)->count()) continue;

            array_push($this->pacientes,
                [
                    'as_afiliado_id'        => $this->getAfiliadoId($row),
                    'identificacion'        => $row[1],
                    'nombre_paciente'       => $row[2],
                    'consecutivo_entidad'   => $row[6],
                    'cama_servicio'         => $row[0],
                    'fecha_ingreso'         => $row[3],
                    'cod_dx'                => $row[4],
                    'nombre_diagnostico'    => $row[5],
                    'estado'                => 'Sin Asignar'
                ]
            );

        }

    }


    public function getInvalido()
    {
        return $this->invalido;
    }

    public function getPacientes()
    {
        return $this->pacientes;
    }



    private function validar($row, $fila)
    {
        $validator = Validator::make($row, self::RULES);

        if($validator->fails()){
            array_push($this->invalido,
                [
                    'fila'=> $fila + 1,
                    'row' => $row,
                    'errors' => $validator->errors()->toArray()
                ]);
            return false;
        }

        return true;

    }

    private function getAfiliado($identificacion)
    {
        return AsAfiliado::whereIdentificacion($identificacion)->first();
    }

    /**
     * @param $row
     */
    private function pacienteExiste($row)
    {
        $paciente = CmPacientesHospitalario::where([
            'identificacion' => $row[1],
            'fecha_ingreso' => $row[3]
        ])->where('estado', '<>', 'Cerrado')->get();

        return $paciente;
    }

    /**
     * @param $row
     * @return |null
     */
    private function getAfiliadoId($row)
    {
        $afiliado_id = null;

        $afiliado = $this->getAfiliado($row[1]);

        if ($afiliado) {
            $afiliado_id = $afiliado->id;
        }
        return $afiliado_id;
    }

}