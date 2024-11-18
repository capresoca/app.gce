<?php

namespace App\Imports;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class ComprobanteDiarioImport implements ToCollection
{

    private $errores;
    private $datos_validos;
    private $nombre_tabla;

    private const MESES = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    private const RULES = [
        '0' => 'required',
        '1' => 'required|date_format:Y-m-d',
        '2' => 'required',
        '3' => 'required|numeric',
        '4' => 'required|numeric',
        '5' => 'required|numeric',
        '6' => 'required|numeric',
        '7' => 'required|numeric',
        '8' => 'required|numeric',
        '9' => 'required',
        '10' => '',
        '11' => 'required|numeric',
        '12' => 'required|numeric',
        '13' => 'required|numeric'
    ];

    private const ERROR_MESSAGES = [
        'date_format' => 'La columna :attribute no corresponde con el formato de fecha AAAA-MM-DD. (Ej: 2019-12-24)'
    ];

    private const COLUMN_TITLES = [
        'TCCODIGO',
        'COMFECCOM',
        'COMDETALLE',
        'COMNUMDOCU',
        'CUECODIGO',
        'TERNUMDOC',
        'CCCODIGO',
        'CMMVALDEB',
        'CMMVALCRE',
        'COMDETALLED',
        'CRCODIGO',
        'CTCRPORCEN',
        'CTCRVALBAS',
        'CTCRVALFAC'
    ];

    public function __construct()
    {
        $this->errores = [];
        $this->datos_validos = [];
        $this->nombre_tabla = 'nf_comdiariosimporter_' . Str::random(10);
    }

    public function collection(Collection $collection)
    {
        $this->validarEncabezados($collection);

        if (count($this->errores)) return;

        $collection->pull(0);

        foreach ($collection as $key => $fila) {
            if ($fila[0] == null or $fila[0] === '') break;
            $this->validarFila($fila, $key);
        }

        if (!count($this->errores)) {
            $this->crearTablaTemporal();
            $this->insertarDatosValidos();

            //VALIDA CODIGO TIPO COMPROBANTE
            $this->validarCodigoTipoComprobante();

            //VALIDA COLUMNAS VALOR_DEBITO Y VALOR_CREDITO
            $this->validarSumatoriaEntreDosColumnas(strtolower(self::COLUMN_TITLES[7]), strtolower(self::COLUMN_TITLES[8]), $this->datos_validos);

            //VALIDA MES Y AÑO CONTABLE ABIERTO
            $this->validarFechaContable();

            //VALIDA QUE TERNUMDOC EXISTA REGISTRADO EN LA BASE DE DATOS
            $this->validarNumeroDocumentoTerceros();

            //VALIDA CODIGO DE RETENCION (SI TRAE)
            $this->validarCodigoDeRetencion();

            //VALIDA CODIGOS DE CENTRO DE COSTOS
            $this->validarCodigoCentroDeCostos();

            //VALIDA CODIGO CUENTA (QUE ESTÉ COMO AUXILIAR)
            $this->validarCodigoCuenta();

            Schema::dropIfExists($this->nombre_tabla);

        } else {
            return;
        }
    }

    private function validarCodigoCuenta()
    {
        $codigo_nivel_cuenta = DB::select("SELECT a.codigo FROM nf_nivcuentas AS a WHERE a.nombre = 'Auxiliar' LIMIT 1");

        $consulta = "SELECT t.* FROM nf_niifs AS c RIGHT JOIN " . $this->nombre_tabla . " AS t
            ON c.codigo = t.cuecodigo AND c.nf_nivcuenta_id = " . $codigo_nivel_cuenta[0]->codigo . " WHERE c.id IS NULL";

        $filasConCodigoCuentaAuxiliar = DB::select($consulta);

        foreach ($filasConCodigoCuentaAuxiliar as $fila) {
            array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene un código de cuenta no válido o que no está registrado como Auxiliar en el sistema.');
        }
    }

    private function validarCodigoCentroDeCostos()
    {
        $consulta = "SELECT t.* FROM nf_cencostos AS c RIGHT JOIN " . $this->nombre_tabla . " AS t
            ON c . codigo = t . cccodigo WHERE c . id IS NULL";

        $filasConCodigoCentroCostosNoValidos = DB::select($consulta);

        foreach ($filasConCodigoCentroCostosNoValidos as $fila) {
            array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene un código de centro de costos (CCCODIGO) no válido o no registrado en el sistema.');
        }
    }

    private function validarCodigoDeRetencion()
    {
        $consulta = "SELECT t .* FROM nf_conrtfs AS c RIGHT JOIN " . $this->nombre_tabla . " AS t
            ON c . codigo = t . crcodigo WHERE c . id IS NULL";

        $filasConCodigoRetencionNoValido = DB::select($consulta);

        foreach ($filasConCodigoRetencionNoValido as $fila) {
            if ($fila->crcodigo != null) {
                array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene un código de retención (CRCODIGO) no válido o no registrado en el sistema.');
            }
        }
    }

    private function validarNumeroDocumentoTerceros()
    {
        $consulta = "SELECT t .* FROM gn_terceros AS c RIGHT JOIN " . $this->nombre_tabla . " AS t
            ON CAST(c . identificacion AS SIGNED) = t . ternumdoc
            WHERE c . id IS NULL";

        $filasConTerNumDocNoValidos = DB::select($consulta);

        foreach ($filasConTerNumDocNoValidos as $fila) {
            array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene un número de documento de terceros (TERNUMDOC) que no está registrado en el sistema.');
        }
    }

    private function validarSumatoriaEntreDosColumnas($indiceCol1, $indiceCol2, $datos_validos)
    {
        $sumaCol1 = 0;
        $sumaCol2 = 0;

        foreach ($datos_validos as $key => $fila) {
            $sumaCol1 += $fila[$indiceCol1];
            $sumaCol2 += $fila[$indiceCol2];

            if ($fila[$indiceCol1] != 0 and $fila[$indiceCol2] != 0) {
                array_push($this->errores, 'La fila ' . ($key + 2) . ' tiene ambas columnas (' . strtoupper($indiceCol1) . ' y ' . strtoupper($indiceCol2) . ') con valor mayor a 0. Solo una debe tener un valor mayor a cero, la otra debe ser cero.');
            }

            if ($fila[$indiceCol1] == 0 and $fila[$indiceCol2] == 0) {
                array_push($this->errores, 'La fila ' . ($key + 2) . ' tiene ambas columnas (' . strtoupper($indiceCol1) . ' y ' . strtoupper($indiceCol2) . ') con valor 0. Solo una puede tener valor cero, la otra debe ser mayor a cero.');
            }
        }

        if ($sumaCol1 != $sumaCol2) {
            array_push($this->errores, 'La suma total de la columna ' . strtoupper($indiceCol1) . ' y ' . strtoupper($indiceCol2) . ' son diferentes. Deben ser iguales.');
        }
    }

    private function validarFechaContable()
    {
        $mesesAbiertos = DB::select("SELECT * FROM nf_meses AS m WHERE m . estado = 'Abierto' ");

        $consulta = "SELECT * FROM " . $this->nombre_tabla . " AS c WHERE";

        foreach ($mesesAbiertos as $llave => $mes) {
            foreach (self::MESES as $key => $MES) {
                if ($MES == $mes->mes) {
                    if ($key < 10) {
                        $mes->mes_numerico = '0' . $key;
                    } else {
                        $mes->mes_numerico = $key;
                    }
                }
            }

            $consulta .= " c . comfeccom NOT LIKE '" . $mes->year . "-" . $mes->mes_numerico . "-%'";
            if (count($mesesAbiertos) != ($llave + 1)) {
                $consulta .= " AND ";
            }
        }

        $filasConMesesCerrados = DB::select($consulta);

        foreach ($filasConMesesCerrados as $key => $fila) {
            array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene una fecha contable (COMFECCOM) que está cerrada o que no está registrada en el sistema.');
        }
    }

    private function validarCodigoTipoComprobante()
    {
        $filasSinCodigoValido = DB::select('SELECT a.* FROM ' . $this->nombre_tabla . ' AS a
            LEFT JOIN nf_tipcomdiarios AS b ON b.codigo = a.tccodigo
            WHERE b.id IS NULL');

        foreach ($filasSinCodigoValido as $fila) {
            array_push($this->errores, 'La fila ' . ($fila->id + 1) . ' tiene un códido de tipo de comprobante (TCCODIGO) que no está registrado en el sistema.');
        }
    }

    private function insertarDatosValidos()
    {
        DB::table($this->nombre_tabla)->insert($this->datos_validos);
    }

    private function crearTablaTemporal()
    {
        Schema::create($this->nombre_tabla, function (Blueprint $table) {

            $table->increments('id');

            foreach (self::COLUMN_TITLES as $key => $columna) {
                if (strpos(self::RULES[$key], 'numeric') !== FALSE) {
                    $table->integer(strtolower($columna));
                }

                if (strpos(self::RULES[$key], 'date') !== FALSE) {
                    $table->date(strtolower($columna));
                }

                if (strpos(self::RULES[$key], 'numeric') === FAlSE && strpos(self::RULES[$key], 'date') === FALSE) {
                    $table->string(strtolower($columna));
                }
            }

            $table->timestamps();

        });
    }

    private function validarFila($fila, $key)
    {
        foreach ($fila as $id => $celda) {
            if ($fila[$id] != null) $fila[$id] = str_replace(['=', '"'], ['', ''], $fila[$id]);
            if ($id == 1) $fila[$id] = str_replace(' / ', ' - ', $fila[$id]);
        }

        $validator = Validator::make($fila->toArray(), self::RULES, self::ERROR_MESSAGES);

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error) {
                array_push($this->errores, 'En la fila ' . ($key + 1) . ' ' . $error[0]);
            }

//            array_push($this->errores, ''
//                [
//                    'numero_fila' => $key + 1,
//                    'errores' => $validator->errors()->toArray()
//               ]
//        );
        } else {
            $filaConLlaves = [];

            foreach (self::COLUMN_TITLES as $k => $titulo) {
                $filaConLlaves[strtolower(self::COLUMN_TITLES[$k])] = $fila[$k];
            }

            array_push($this->datos_validos, $filaConLlaves);
        }
    }

    private function validarEncabezados(Collection $collection)
    {
        $encabezados = $collection[0];
//        $err = [];

        foreach (self::COLUMN_TITLES as $key => $titulo) {
            if ($titulo != $encabezados[$key])
                array_push($this->errores, 'El encabezado ' . $encabezados[$key] . ' en la columna ' . ($key + 1) . ' debe ser ' . $titulo);
        }

//        if (count($err) > 0) {
//            array_push($this->errores, [
//                'numero_fila' => 1,
//                'datos_fila' => $encabezados,
//                'errores' => $err
//            ]);
//        }

    }

    public function getErrores()
    {
        return $this->errores;
    }

    public function getValidos()
    {
        return $this->datos_validos;
    }
}
