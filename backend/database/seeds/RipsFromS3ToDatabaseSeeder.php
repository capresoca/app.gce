<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Riips\RsRipsRadicado;

class RipsFromS3ToDatabaseSeeder extends Seeder
{
    private $tablas = [];
    private $errores = [];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->errores = collect();
        $ripsProcesados = 0;

        $this->tablas = [
            'ac' => 'ripsac',
            'af' => 'ripsaf',
            'ah' => 'ripsah',
            'am' => 'ripsam',
            'an' => 'ripsan',
            'ap' => 'ripsap', 
            'at' => 'ripsat',
            'au' => 'ripsau',
            'ct' => 'ripsct',
            'us' => 'ripsus',
        ];

        $s3 = Storage::disk('s3');

        $ripsRadicados = RsRipsRadicado::orderBy('id', 'asc')->get();
        $ripsTotales = count($ripsRadicados);
		
		DB::beginTransaction();

		
        foreach ($ripsRadicados as $rips) {
			
			
            $ruta = 'RIPS/' . $rips->cod_radicacion . '/archivos/';
            $existeRuta = $s3->exists($ruta);

            if (!$existeRuta) {
                $this->errores->push("En el servidor S3 no existe la ruta del RIPS: " . $rips->cod_radicacion);
            }

            $archivosEnLaRuta = $s3->allFiles($ruta);
			$respuesta = 0;
            foreach ($archivosEnLaRuta as $rutaArchivo) {
                $respuesta = $this->procesarYSubirArchivoDeS3ABaseDeDatos($rutaArchivo, $s3, $rips->id);
				if($respuesta == 0){
					echo "Respuesta ".$respuesta." ".$rutaArchivo."\r\n";
					DB::rollback();
					return;
				}
            }

            $ripsProcesados++;
            $this->mostrarProgreso($ripsProcesados, $ripsTotales);
        }
		
		DB::commit();


        if (!$this->errores->count()) {
            echo 'Proceso completado sin errores.';
        } else {
            echo 'Se registraron los siguientes errores: ' . PHP_EOL;
            foreach ($this->errores as $error) {
                echo $error . PHP_EOL;
            }
        }
    }

    private function procesarYSubirArchivoDeS3ABaseDeDatos($rutaArchivo, $disk, $rips_id)
    {
        $sufijo = $this->obtenerSufijo($rutaArchivo);

        if (!array_key_exists($sufijo, $this->tablas)) return -1;

        $tabla = $this->tablas[$sufijo];
        $rutaArchivoArray = explode('/', $rutaArchivo);
        $nombreArchivo = end($rutaArchivoArray);

        $archivo = $disk->get($rutaArchivo);

        $lineas = explode(PHP_EOL, $archivo);

        foreach ($lineas as $key => $linea) {
            $valoresDeLinea = explode(',', $linea);
            foreach ($valoresDeLinea as $key2 => $dato) {
                $valoresDeLinea[$key2] = $this->validarYProcesarFecha($dato);
            }
            array_push($valoresDeLinea, $rips_id);
            $lineas[$key] = $valoresDeLinea;
        }

//        $consulta = $this->generarConsultaDeInsercion($tabla, $lineas);
//        $consulta = str_replace('"', '', $consulta);

//        $this->insertarDatos($tabla, $lineas, $nombreArchivo);
        $consulta = $this->generarConsultaDeInsercion($tabla, $lineas);

        try {
            DB::statement($consulta);
			
			return 1;
        } catch (Exception $e) {
            $this->errores->push('Error al realizar inserción de datos: ' . $e->getMessage());
            echo $e->getMessage();
			return 0;
        }
    }

    private function obtenerSufijo($rutaArchivo)
    {
        $sufijo = explode('/', $rutaArchivo);
        $sufijo = end($sufijo);
        $sufijo = substr($sufijo, 0, 2);
        $sufijo = strtolower($sufijo);
        return $sufijo;
    }

    private function validarYProcesarFecha($dato)
    {
        if (!$dato || $dato === '') {
            return $dato;
        }

        $fecha = explode('/', $dato);
        if (count($fecha) === 3) {
            $dato = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
        }

        return $dato;
    }

    private function generarConsultaDeInsercion($tabla, $arregloDeValores)
    {
        $consulta = 'INSERT IGNORE `' . $tabla . '` VALUES';
        foreach ($arregloDeValores as $key => $valores) {
            $datos = '(';
            foreach ($valores as $key2 => $valor) {
                if (empty($valor)) {
                    $datos .= "''";
                } else {
                    if (is_numeric($valor)) {
                        $datos .= $valor;
                    } else {
                        $datos .= "'" . str_replace("'", "''", $valor) . "'";
                    }
                }

                if ((count($valores) - 1) != $key2) {
                    $datos .= ', ';
                }
            }
            $datos .= ')';
            if ((count($arregloDeValores) - 1) != $key) {
                $datos .= ', ';
            }

            $consulta .= $datos;
        }
		
								

        return $consulta;
    }

    private function generarConsultaDeInsercion2($tabla, $arregloDeValores)
    {
        $consultaCompleta = '';
        foreach ($arregloDeValores as $valores) {
            $cabecera = 'INSERT INTO `' . $tabla . '` VALUES(';
            $cuerpo = '';
            foreach ($valores as $key => $valor) {
                if (empty($valor)) {
                    $cuerpo .= "''";
                } else {
                    if (is_numeric($valor)) {
                        $cuerpo .= $valor;
                    } else {
                        $cuerpo .= "'" . $valor . "'";
                    }
                }

                if ((count($valores) - 1) != $key) {
                    $cuerpo .= ', ';
                }
            }
            $footer = ');';

            $consultaPorLinea = $cabecera . $cuerpo . $footer . PHP_EOL;
            $consultaCompleta .= $consultaPorLinea;
//            DB::statement($consultaPorLinea);
        }
        return $consultaCompleta;
    }

    private function insertarDatos($tabla, $arregloDeValores, $nombreArchivo)
    {
        $consulta = null;
        $resultado = null;
        foreach ($arregloDeValores as $valores) {
            $cabecera = 'INSERT INTO ' . $tabla . ' VALUES(';
            $cuerpo = '';
            foreach ($valores as $key => $valor) {
                $cuerpo .= "?";
                if ((count($valores) - 1) != $key) {
                    $cuerpo .= ',';
                }
            }
            $footer = ')';

            $consulta .= $cabecera . $cuerpo . $footer;

            try {
                DB::beginTransaction();
                DB::insert($consulta, $valores);
                DB::commit();
            } catch (Exception $ex) {
                DB::rollBack();
                $this->errores->push('Se presentó el siguiente error inseperado procesando el archivo (' . $nombreArchivo . '): ' . $ex->getMessage());
            }
        }
    }

    private function mostrarProgreso($procesados, $totales)
    {
        system('clear');
        echo 'RIPS Procesado: ' . $procesados . ' de ' . $totales;
    }
}
