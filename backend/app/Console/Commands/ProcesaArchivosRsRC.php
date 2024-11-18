<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\Util;
use App\Libraries\File;
use App\Models\Enums\ETipoArchivoSoporte7Tablas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RsRc\RecAportante;
use App\Models\RsRc\RecAfiliado;
use App\Models\RsRc\RecHistoriaAfiliacion;
use App\Models\RsRc\RecHistoriaCondicionAfiliacion;
use App\Models\RsRc\RecHistoriaCotizanteAportante;
use App\Models\RsRc\RecHistoriaGrupoFamiliar;
use App\Models\RsRc\RecHistoriaIdentificacion;
use Illuminate\Support\Facades\Storage;
use App\Models\Enums\ETipoArchivoSoporte5TablasSubsidiado;
use App\Libraries\Mapa;
use App\Models\RsRc\ResAfiliacion;
use App\Models\RsRc\ResAfiliado;
use App\Models\RsRc\ResCondicionAfiliacion;
use App\Models\RsRc\ResHistoricoAfiliacion;
use App\Models\RsRc\ResCondicionAfiliacionSubsidiado;

class ProcesaArchivosRsRC extends Command
{
    const NUMERO_FLUSH = 5000;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:procesa_archivo_rs_rc';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar los traslados';
    
    protected $path = "/home/system/Documentos/Tareas/tmp/";
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        $this->path = Storage::disk('local')->path("descarga/rsrc");
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $errores        = array();
        //Log::info('Ruta Base: ',array($this->path.DIRECTORY_SEPARATOR.'RC.ZIP'));
        if(file_exists($this->path.DIRECTORY_SEPARATOR.'RC.zip')) {
            $errores        = $this->procesarSoporte7Tablas(NULL, $this->path.DIRECTORY_SEPARATOR.'RC.zip', NULL, TRUE, NULL);
            Util::getBorrarDirectorioCompleto($this->path.DIRECTORY_SEPARATOR.'temporal'.DIRECTORY_SEPARATOR);
            unlink($this->path.DIRECTORY_SEPARATOR.'RC.zip');
        }
        if(file_exists($this->path.DIRECTORY_SEPARATOR.'RS.zip')) {
            //$errores        = array_merge($errores,$this->procesarSoporte5Subsidiado(NULL, $this->path.DIRECTORY_SEPARATOR.'RS.zip', NULL, TRUE, NULL));
            Util::getBorrarDirectorioCompleto($this->path.DIRECTORY_SEPARATOR.'temporal'.DIRECTORY_SEPARATOR);
            unlink($this->path.DIRECTORY_SEPARATOR.'RS.zip');
        }
        Log::info('Errores: ',$errores);
        //Util::getBorrarDirectorioCompleto($this->path.DIRECTORY_SEPARATOR.'temporal'.DIRECTORY_SEPARATOR.'zip/');
    }
    
    /**
     * @return array
     */
    private function procesarSoporte7Tablas($descripcion, $url, $nombreArchivo, $cargueDetallado, $tipoArchivo) {
        $carpetaDescomprimida           = "";
        try {
            DB::beginTransaction();
            $errores                = array();
            $datos                  = array();
            //$in                     = NULL;
            $cvsReader              = NULL;
            $rutaBase               = substr($url, 0, strrpos($url, "/")); //NULL;//.substring(0, url.lastIndexOf("/"));
            
            $rutaZip                        = $rutaBase . DIRECTORY_SEPARATOR. "temporal" . DIRECTORY_SEPARATOR . "zip";
            
            $subCarpetaDescomprimida        = "";
            $carpeta                        = null;
            $subCarpeta                     = null;
            $cantidadArchivo                = 0;
            $cantidadArchivosCargue         = ($cargueDetallado ? 7 : 1);
            $errores                        = array();
            $delimitador                    = ",";
            //$mapa                           = new Mapa();
            $rutaZip                        = $url;
            $contador                       = 0;
            
            if ($cargueDetallado) {
                //Log::info('Carpeta Descomprimida: ', array($carpetaDescomprimida));
                //Log::info('Ruta Base: ', array($rutaBase));
                //Log::info('Ruta ZIP: ', array($rutaZip));
                $carpetaDescomprimida   = Util::getDescomprimirArchivo($rutaZip, "temporal", Util::USUARIO);
                if ($carpetaDescomprimida == null) {
                    $errores[]      = 'Error: Error al descomprimir el archivo.';
                }
                else {
                    //Log::info('entra: ',array($carpetaDescomprimida));
                    $carpeta        = new File($carpetaDescomprimida);
                    $archivos       = $carpeta->listFiles("ZIP");
                    
                    if (count($archivos) < 1) {
                        $errores[]      = "Error: El archivo comprimido no contiene archivos.";
                    }
                    else {
                        foreach ($archivos as $archivo) {
                            if (!is_readable($carpetaDescomprimida.$archivo)) {
                                $errores[]      = 'Error: No es posible leer el archivo.';
                            }
                        }
                    }
                }
            }
            else {
                $archivo = new File($rutaZip);
                if (!$archivo->canRead()) {
                    $errores[]      = 'Error: No es posible leer el archivo.';
                }
            }
            if (empty($errores)) {
                $archivos       = null;
                $carpeta        = new File($carpetaDescomprimida);
                if ($carpeta->isDirectory()) {
                    $archivos = $carpeta->listFiles("ZIP");
                    foreach($archivos as $nombre7Archivos) {
                        // Verifica si es correcto el nombre de los 7 archivos esperados
                        if (strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::AFILIADO)->getDescripcion())===FALSE 
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::APORTANTE)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_AFILIACION)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_CONDICION_AFILIACION)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_COTIZANTE_APORTANTE)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_GRUPO_FAMILIAR)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_IDENTIFICACION)->getDescripcion())===FALSE) {
                                $errores[]          = "Error: El nombre del archivo {$nombre7Archivos} no corresponde a ninguno de los establecidos";
                            }
                    }
                }
                else {
                    $errores[]          = 'Error: No es un directorio';
                }
                
                $cantidadArchivo = count($archivos);
                
                if ($cantidadArchivo != $cantidadArchivosCargue) {
                    $errores[]      = 'Error: El archivo comprimido no contine los .zip exigidos para este proceso.';
                }
                
                if (count($errores) > 0) {
                    return $errores;
                }
                
                if (empty($errores)) {
                    $archivo = new File($carpetaDescomprimida);
                    $listaDeArchivos = $archivo->listFiles("ZIP");
                    
                    foreach ($listaDeArchivos as $files)
                        //files = listaDeArchivos[i].getName();
                        
                        if ($cargueDetallado) {
                            $subCarpetaDescomprimida    = Util::getDescomprimirArchivo($carpetaDescomprimida . $files, "temporal", Util::USUARIO);
                            
                            if ($subCarpetaDescomprimida == null) {
                                $errores[]  = "Error: Error al descomprimir el archivo.";
                            }
                            else {
                                $subCarpeta             = new File($subCarpetaDescomprimida);
                                $archivosSubCarpeta     = $subCarpeta->listFiles("TXT");
                                
                                if (count($archivosSubCarpeta)<1) {
                                    $errores[]      = 'Error: El archivo comprimido no contiene archivos.';
                                }
                                else {
                                    
                                    if ($subCarpeta->isDirectory()) {
                                        $listaARchivos      = $archivosSubCarpeta;//$subCarpeta->listFiles("TXT");
                                        foreach ($listaARchivos as $nombreAsub) {
                                            
                                            //$nombreAsub = archivossc.getName();
                                            
                                            //in = new java.io.FileInputStream($subCarpeta . "/" . $nombreAsub);
                                            //cvsReader = new CsvReader(new java.io.InputStreamReader(in), delimitador.charAt(0));
                                            $cvsReader      = fopen($subCarpetaDescomprimida . $nombreAsub, "r");
                                            $datos          = array();
                                            //$cvsReader.setSafetySwitch(false);
                                            while (!feof($cvsReader)){
                                                $linea      = fgets($cvsReader);
                                                $txt        = str_replace("\u0000", '', $linea); //cvsReader.getRawRecord().replaceAll("\u0000", "");
                                                if(empty($txt)) {
                                                    continue;
                                                }
                                                //datos.add(txt.split(delimitador, -1));
                                                $fila       = explode($delimitador, $txt);
                                                if(empty($fila)) {
                                                    continue;
                                                }
                                                $datos[]    = $fila;
                                                // Valida que los archivos contengan el numero de datos esperados
                                                
                                                if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::AFILIADO)->getDescripcion() && count($fila) != 9) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //Error:
                                                    //                                                errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.AFILIADO.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::APORTANTE)->getDescripcion() && count($fila) != 5) {
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.APORTANTE.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_AFILIACION)->getDescripcion() && count($fila) != 5) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.HISTORIA_AFILIACION.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_CONDICION_AFILIACION)->getDescripcion() && (count($fila) < 9)) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.HISTORIA_CONDICION_AFILIACION.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_COTIZANTE_APORTANTE)->getDescripcion() && count($fila) != 6) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.HISTORIA_COTIZANTE_APORTANTE.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_GRUPO_FAMILIAR)->getDescripcion() && count($fila) != 6) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.HISTORIA_GRUPO_FAMILIAR.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                                
                                                else if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_IDENTIFICACION)->getDescripcion() && count($fila) != 6) {
                                                    $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                                    //errores.add(Mensaje.getMensaje(this.getLocalidad(), "error.soporte7tablas.cantidaddatos", new Object[] { ETipoArchivoSoporte7Tablas.HISTORIA_IDENTIFICACION.getNombre(getLocalidad()), cvsReader.getRawRecord() }));
                                                }
                                            }
                                            Log::info('Array Datos lleno: ',array(count($datos)));
                                            Log::info('Primer archivo: ',array($nombreAsub));
                                            // Procesa los aportantes
                                            //if ($nombreAsub.contains(ETipoArchivoSoporte7Tablas.APORTANTE.getNombre(getLocalidad()))) {
                                            if(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::APORTANTE)->getDescripcion())!==FALSE) {
                                                
                                                $contador = 0;
                                                DB::delete("DELETE FROM rec_aportante");
                                                //desconectada.createQuery("delete from " + RecAportante.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $CONSECUTIVO_APORTANTE      = 0;
                                                $TIPO_IDENTIFICACION        = 1;
                                                $NUMERO_IDENTIFICACION      = 2;
                                                $DIGITO_VERIFICACION        = 3;
                                                $RAZON_SOCIAL               = 4;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosAportante = new RecAportante();
                                                    
                                                    if ($vector[$CONSECUTIVO_APORTANTE] == "" || $vector[$CONSECUTIVO_APORTANTE] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $valoresGuardar[]       = [
                                                            'consecutivo_aportante'      => ($vector[$CONSECUTIVO_APORTANTE]),
                                                            'tipo_identificacion'        => (empty($vector[$TIPO_IDENTIFICACION]) ? null : $vector[$TIPO_IDENTIFICACION]),
                                                            'numero_identificacion'      => (empty($vector[$NUMERO_IDENTIFICACION]) ? null : $vector[$NUMERO_IDENTIFICACION]),
                                                            'digito_verificacion'        => (empty($vector[$DIGITO_VERIFICACION]) ? null : $vector[$DIGITO_VERIFICACION]),
                                                            'razon_social'               => (empty($vector[$RAZON_SOCIAL]) ? null : $vector[$RAZON_SOCIAL]),
                                                        ];
                                                        //$datosAportante->save();
                                                        
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecAportante *******************************************************************" + datos.size());
                                                         }*/
                                                        
                                                        $contador++;
                                                    }
                                                    
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_aportante')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                            // Procesa los afiliados
                                            elseif(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::AFILIADO)->getDescripcion())!==FALSE) {
                                                $contador = 0;
                                                //desconectada.createQuery("delete from " + RecAfiliado.class.getCanonicalName()).executeUpdate();
                                                DB::delete("DELETE FROM rec_afiliado");
                                                //desconectada.flush();
                                                
                                                $CONSECUTIVO_SERIAL         = 0;
                                                $PRIMER_APELLIDO            = 1;
                                                $SEGUNDO_APELLIDO           = 2;
                                                $PRIMER_NOMBRE              = 3;
                                                $SEGUNDO_NOMBRE             = 4;
                                                $FECHA_NACIMIENTO           = 5;
                                                $SEXO                       = 6;
                                                $CODIGO_ENTIDAD             = 7;
                                                $FECHA_AFILIACION           = 8;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosAfiliado = new RecAfiliado();
                                                    
                                                    if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo'; //.add(Mensaje.getMensaje(getLocalidad(), "error.soporte7tablas.error.datononull"));
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $valoresGuardar[]       = [
                                                            'consecutivo_serial'          => ($vector[$CONSECUTIVO_SERIAL]),
                                                            'primer_apellido'             => (empty($vector[$PRIMER_APELLIDO]) ? null : $vector[$PRIMER_APELLIDO]),
                                                            'segundo_apellido'            => (empty($vector[$SEGUNDO_APELLIDO]) ? null : $vector[$SEGUNDO_APELLIDO]),
                                                            'primer_nombre'               => (empty($vector[$PRIMER_NOMBRE]) ? null : $vector[$PRIMER_NOMBRE]),
                                                            'segundo_nombre'              => (empty($vector[$SEGUNDO_NOMBRE]) ? null : $vector[$SEGUNDO_NOMBRE]),
                                                            'sexo'                        => (empty($vector[$SEXO]) ? null : $vector[$SEXO]),
                                                            'codigo_entidad'              => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                            'fecha_afiliacion'            => (empty($vector[$FECHA_AFILIACION]) ? null : $vector[$FECHA_AFILIACION]),
                                                            'fecha_nacimiento'            => (empty($vector[$FECHA_NACIMIENTO]) ? null : $vector[$FECHA_NACIMIENTO]),
                                                        ];
                                                        //$datosAfiliado->save();
                                                        
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecAfiliado *******************************************************************" + datos.size());
                                                         }*/
                                                        
                                                        $contador++;
                                                    }
                                                    
                                                }
                                                Log::info('entra a log',$valoresGuardar);
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_afiliado')->insert($d);
                                                }
                                                
                                                //desconectada.flush();
                                                //desconectada.clear();
                                                
                                            }
                                            // Procesa la historia de afiliacion
                                            elseif(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_AFILIACION)->getDescripcion())!==FALSE) {
                                                
                                                $contador = 0;
                                                DB::delete("delete from rec_historia_afiliacion");
                                                //desconectada.createQuery("delete from " + RecHistoriaAfiliacion.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $ID_HISTORIA_AFILIACION         = 0;
                                                $ID_AFILIADO                    = 1;
                                                $CODIGO_ENTIDAD                 = 2;
                                                $FECHA_INICIAL                  = 3;
                                                $FECHA_FINAL                    = 4;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosHistoriaAfiliacion = new RecHistoriaAfiliacion();
                                                    if ($vector[$ID_AFILIADO] == "" || $vector[$ID_AFILIADO] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $contador++;
                                                        $valoresGuardar[]       = [
                                                            'id_consecutivo'            => ($contador),
                                                            'id_afiliado'               => ($vector[$ID_AFILIADO]),
                                                            'consecutivo_serial'        => (empty($vector[$ID_HISTORIA_AFILIACION]) ? null : ($vector[$ID_HISTORIA_AFILIACION])),
                                                            'codigo_entidad'            => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                            'fecha_final'               => (empty($vector[$FECHA_FINAL]) ? null : (strpos($vector[$FECHA_FINAL],'2999') === FALSE ? NULL : ($vector[$FECHA_FINAL]) )),
                                                            'fecha_inicial'             => (empty($vector[$FECHA_INICIAL]) ? null : $vector[$FECHA_INICIAL]),
                                                        ];
                                                        //$datosHistoriaAfiliacion->save();
                                                        
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecHistoriaAfiliacion *******************************************************************" + datos.size());
                                                         }*/
                                                        
                                                        
                                                    }
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_historia_afiliacion')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                            // Procesa la historia condicion de afiliacion
                                            elseif(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_CONDICION_AFILIACION)->getDescripcion())!==FALSE) {
                                                
                                                $contador = 0;
                                                DB::delete("delete from rec_historia_condicion_afiliacion");
                                                //desconectada.createQuery("delete from " + RecHistoriaCondicionAfiliacion.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $ID_AFILIADO            = 0;
                                                $TIPO_AFILIADO          = 1;
                                                $ZONA_AFILIACION        = 2;
                                                $ESTADO_AFILIACION      = 3;
                                                $ID_DEPARTAMENTO        = 4;
                                                $ID_MUNICIPIO           = 5;
                                                $FECHA_INICIO           = 6;
                                                $FECHA_FIN              = 7;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosHistoriaCondicion = new RecHistoriaCondicionAfiliacion();
                                                    
                                                    if ($vector[$ID_AFILIADO] == "" || $vector[$ID_AFILIADO] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $contador++;
                                                        $valoresGuardar[]       = [
                                                            'id_consecutivo'         => ($contador),
                                                            'id_afiliado'            => ($vector[$ID_AFILIADO]),
                                                            'tipo_afiliado'          => (empty($vector[$TIPO_AFILIADO]) ? null : $vector[$TIPO_AFILIADO]),
                                                            'zona_afiliacion'        => (empty($vector[$ZONA_AFILIACION]) ? null : $vector[$ZONA_AFILIACION]),
                                                            'estado_afiliacion'      => (empty($vector[$ESTADO_AFILIACION]) ? null : $vector[$ESTADO_AFILIACION]),
                                                            'id_departamento'        => (empty($vector[$ID_DEPARTAMENTO]) ? null : $vector[$ID_DEPARTAMENTO]),
                                                            'id_municipio'           => (empty($vector[$ID_MUNICIPIO]) ? null : $vector[$ID_MUNICIPIO]),
                                                            'fecha_inicio'           => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                            'fecha_fin'              => (empty($vector[$FECHA_FIN]) ? null : (strpos($vector[$FECHA_FIN],'2999') === FALSE ? null : $vector[$FECHA_FIN])),
                                                        ];
                                                        //$datosHistoriaCondicion->save();
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecHistoriaCondicionAfiliacion *******************************************************************" + datos.size());
                                                         }*/
                                                    }
                                                    
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_historia_condicion_afiliacion')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                            // Procesa historia cotizante aportante
                                            elseif(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_COTIZANTE_APORTANTE)->getDescripcion())!==FALSE) {
                                                
                                                $contador = 0;
                                                DB::delete("delete from rec_historia_cotizante_aportante");
                                                //desconectada.createQuery("delete from " + RecHistoriaCotizanteAportante.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $CONSECUTIVO_SERIAL = 0;
                                                $ID_APORTANTE = 1;
                                                $ID_COTIZANTE = 2;
                                                $FECHA_INICIO = 3;
                                                $FECHA_FIN = 4;
                                                $CODIGO_ENTIDAD = 5;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosHistoriaCotizante = new RecHistoriaCotizanteAportante();
                                                    
                                                    if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $contador++;
                                                        $valoresGuardar[]       = [
                                                            'id_consecutivo'                 => ($contador),
                                                            'consecutivo_serial'             => (($vector[$CONSECUTIVO_SERIAL])),
                                                            'id_aportante'                   => (empty($vector[$ID_APORTANTE]) ? null : ($vector[$ID_APORTANTE])),
                                                            'id_tipo_cotizante'              => (empty($vector[$ID_COTIZANTE]) ? null : ($vector[$ID_COTIZANTE])),
                                                            'fecha_inicio'                   => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                            'fecha_fin'                      => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN],'2999') === FALSE ? null : $vector[$FECHA_FIN]),
                                                            'codigo_entidad'                 => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                        ];
                                                        //$datosHistoriaCotizante->save();
                                                        
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecHistoriaCotizanteAportante *******************************************************************" + datos.size());
                                                         }*/
                                                    }
                                                    
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_historia_cotizante_aportante')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                            // Procesa historia grupo familiar
                                            elseif(strpos($nombreAsub, ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::HISTORIA_GRUPO_FAMILIAR)->getDescripcion())!==FALSE) {
                                                
                                                $contador = 0;
                                                DB::delete("delete from rec_historia_grupo_familiar");
                                                //desconectada.createQuery("delete from " + RecHistoriaGrupoFamiliar.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $CONSECUTIVO_SERIAL = 0;
                                                $SERIAL_COTIZANTE   = 1;
                                                $ID_PARENTESCO      = 2;
                                                $FECHA_INICIO       = 3;
                                                $FECHA_FIN          = 4;
                                                $CODIGO_ENTIDAD     = 5;
                                                $valoresGuardar     = array();
                                                foreach ($datos as $vector) {
                                                    $datosHistoriaGrupo = new RecHistoriaGrupoFamiliar();
                                                    
                                                    if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $contador++;
                                                        $valoresGuardar[]       = [
                                                            'id_consecutivo'                 => ($contador),
                                                            'consecutivo_serial'             => (($vector[$CONSECUTIVO_SERIAL])),
                                                            'consecutivo_serial_cotizante'   => (empty($vector[$SERIAL_COTIZANTE]) ? null : ($vector[$SERIAL_COTIZANTE])),
                                                            'id_parentesco'                  => (empty($vector[$ID_PARENTESCO]) ? null : ($vector[$ID_PARENTESCO])),
                                                            'fecha_inicio'                   => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                            'fecha_fin'                      => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN],'2999') === FALSE ? null : $vector[$FECHA_FIN]),
                                                            'codigo_entidad'                 => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                        ];
                                                        
                                                        //$datosHistoriaGrupo->save();
                                                        /*if ($contador % self::NUMERO_FLUSH == 0) {
                                                         DB::prepareBindings($bindings)
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecHistoriaGrupoFamiliar *******************************************************************" + datos.size());
                                                        }*/
                                                    }
                                                    
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_historia_grupo_familiar')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                            // Procesa historia de identificacion
                                            else {
                                                
                                                $contador = 0;
                                                DB::delete("delete from rec_historia_identificacion");
                                                //desconectada.createQuery("delete from " + RecHistoriaIdentificacion.class.getCanonicalName()).executeUpdate();
                                                //desconectada.flush();
                                                
                                                $CONSECUTIVO_SERIAL = 0;
                                                $TIPO_IDENTIFICACION = 1;
                                                $DOCUMENTO_IDENTIFICACION = 2;
                                                $FECHA_INICIO = 3;
                                                $FECHA_FIN = 4;
                                                $valoresGuardar     = array();
                                                
                                                foreach($datos as $vector) {
                                                    $datosHistoriaIdentificacion = new RecHistoriaIdentificacion();
                                                    
                                                    if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                        $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                    }
                                                    
                                                    if (count($errores) > 0) {
                                                        return $errores;
                                                    }
                                                    
                                                    if (empty($errores)) {
                                                        $contador++;
                                                        //$datosHistoriaIdentificacion->save();
                                                        
                                                        $valoresGuardar[]       = [
                                                            'id_consecutivo'                => ($contador),
                                                            'consecutivo_serial'            => (($vector[$CONSECUTIVO_SERIAL])),
                                                            'tipo_identificacion'           => (empty($vector[$TIPO_IDENTIFICACION]) ? null : $vector[$TIPO_IDENTIFICACION]),
                                                            'documento_identificacion'      => (empty($vector[$DOCUMENTO_IDENTIFICACION]) ? null : $vector[$DOCUMENTO_IDENTIFICACION]),
                                                            'fecha_inicio'                  => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                            'fecha_fin'                     => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN],'2999') === FALSE ? null : $vector[$FECHA_FIN]),
                                                        ];
                                                        
                                                        /*if (contador % NUMERO_FLUSH == 0) {
                                                         desconectada.flush();
                                                         desconectada.clear();
                                                         LOG.info("FLUSH al Registro Nro " + contador + " RecHistoriaIdentificacion *******************************************************************" + datos.size());
                                                         }*/
                                                        
                                                        //$contador++;
                                                    }
                                                    
                                                }
                                                $dividir        = array_chunk($valoresGuardar, 5000);
                                                foreach ($dividir as $d) {
                                                    DB::table('rec_historia_identificacion')->insert($d);
                                                }
                                                //desconectada.flush();
                                                //desconectada.clear();
                                            }
                                        }
                                    }
                                    else {
                                        $errores[]          = 'Error: No es un directorio';
                                        
                                    }
                                }
                            }
                        }
                }
            } else {
                return $errores;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
        return $errores;
    }

    
    private function procesarSoporte5Subsidiado($descripcion, $url, $nombreArchivo, $cargueDetallado, $tipoArchivo) {
        $errores = array();
        try {
            DB::beginTransaction();
        
            $datos = array();
            //InputStream in = null;
            //CsvReader cvsReader = null;
            $rutaBase               = substr($url, 0, strrpos($url, "/")); //NULL;//.substring(0, url.lastIndexOf("/"));
            
            $rutaZip                        = $rutaBase . DIRECTORY_SEPARATOR. "temporal" . DIRECTORY_SEPARATOR . "zip";
            $rutaZip                        = $url;
            $carpetaDescomprimida           = "";
            $subCarpetaDescomprimida        = "";
            $carpeta                        = null;
            $subCarpeta                     = null;
            // $fichero                        = null;
            // $rutaDescomprimida              = null;
            $cantidadArchivo                = 0;
            $cantidadArchivosCargue         = ($cargueDetallado ? 5 : 1);
            $errores                        = array();
            $delimitador                    = ",";
            // $mapa                           = new Mapa();
            $rutaZip                        = $url;
            $contador                       = 0;
            
            if ($cargueDetallado) {
                //carpetaDescomprimida = Util.descomprimirArchivoSoporte7Tablas(rutaZip, "temporal", Util.USUARIO);
                $carpetaDescomprimida   = Util::getDescomprimirArchivo($rutaZip, "temporal", Util::USUARIO);
                if ($carpetaDescomprimida == null) {
                    $errores[]      = 'Error: Error al descomprimir el archivo.';
                }
                else {
                    $carpeta        = new File($carpetaDescomprimida);
                    $archivos       = $carpeta->listFiles("ZIP");
                    
                    if (count($archivos) < 1) {
                        $errores[]      = "Error: El archivo comprimido no contiene archivos.";
                    }
                    else {
                        foreach ($archivos as $archivo) {
                            if (!is_readable($carpetaDescomprimida.$archivo)) {
                                $errores[]      = 'Error: No es posible leer el archivo.';
                            }
                        }
                    }
                }
            }
            else {
                $archivo = new File($rutaZip);
                if (!$archivo->canRead()) {
                    $errores[]      = 'Error: No es posible leer el archivo.';
                }
            }
        
            if (empty($errores)) {
                $archivos       = null;
                $carpeta        = new File($carpetaDescomprimida);
                if ($carpeta->isDirectory()) {
                    $archivos = $carpeta->listFiles("ZIP");
                    foreach($archivos as $nombre7Archivos) {
                        //$nombre7Archivos = $archivoscp.getName();
                        // Verifica si es correcto el nombre de los 5 archivos esperados
                        if (strpos($nombre7Archivos,ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIADO)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIACIONES)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_CONDICION_AFILIACION)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::CONDICION_AFILIACIONES_SUBSIDIADO)->getDescripcion())===FALSE
                            && strpos($nombre7Archivos,ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_IDENTIFICACION)->getDescripcion())===FALSE) {
                                $errores[]          = "Error: El nombre del archivo {$nombre7Archivos} no corresponde a ninguno de los establecidos";
                            }
                    }
                }
                else {
                    $errores[]          = 'Error: No es un directorio';
                }
                
                $cantidadArchivo = count($archivos);
                
                if ($cantidadArchivo != $cantidadArchivosCargue) {
                    $errores[]      = 'Error: El archivo comprimido no contine los .zip exigidos para este proceso.';
                }
                
                if (count($errores) > 0) {
                    return $errores;
                }
                
                if (empty($errores)) {
                    $archivo = new File($carpetaDescomprimida);
                    $listaDeArchivos = $archivo->listFiles("ZIP");
                    
                    foreach ($listaDeArchivos as $files)
                        //files = listaDeArchivos[i].getName();
                    
                    if ($cargueDetallado) {
                        $subCarpetaDescomprimida    = Util::getDescomprimirArchivo($carpetaDescomprimida . $files, "temporal", Util::USUARIO);
                        if ($subCarpetaDescomprimida == null) {
                            $errores[]  = "Error: Error al descomprimir el archivo.";
                        }
                        else {
                            $subCarpeta             = new File($subCarpetaDescomprimida);
                            $archivosSubCarpeta     = $subCarpeta->listFiles("TXT");
                            
                            if (count($archivosSubCarpeta)<1) {
                                $errores[]      = 'Error: El archivo comprimido no contiene archivos.';
                            }
                            else {
                                if ($subCarpeta->isDirectory()) {
                                    $listaARchivos      = $archivosSubCarpeta;//$subCarpeta->listFiles("TXT");
                                    foreach ($listaARchivos as $nombreAsub) {
                                        
                                        ///String nombreAsub = archivossc.getName();
                                        
                                        $cvsReader      = fopen($subCarpetaDescomprimida . $nombreAsub, "r");
                                        $datos          = array();
                                        //$cvsReader.setSafetySwitch(false);
                                        while (!feof($cvsReader)){
                                            $linea      = fgets($cvsReader);
                                            $txt        = str_replace("\u0000", '', $linea); //cvsReader.getRawRecord().replaceAll("\u0000", "");
                                            if(empty($txt)) {
                                                continue;
                                            }
                                            //datos.add(txt.split(delimitador, -1));
                                            $fila       = explode($delimitador, $txt);
                                            if(empty($fila)) {
                                                continue;
                                            }
                                            $datos[]    = $fila;
                                            // Valida que los archivos contengan el numero de datos esperados
                                            //if ($nombreAsub == ETipoArchivoSoporte7Tablas::getIndice(ETipoArchivoSoporte7Tablas::AFILIADO)->getDescripcion() && count($fila) != 9) {
                                            if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIADO)->getDescripcion().'.TXT' && count($fila) != 8) {
                                                $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                            }
                                            
                                            else if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIACIONES)->getDescripcion().'.TXT' && count($fila) != 6) {
                                                $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                            }
                                            
                                            else if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_CONDICION_AFILIACION)->getDescripcion().'.TXT' && count($fila) != 10) {
                                                $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                            }
                                            
                                            else if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_IDENTIFICACION)->getDescripcion().'.TXT' && count($fila) != 6) {
                                                $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                            }
                                            
                                            else if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::CONDICION_AFILIACIONES_SUBSIDIADO)->getDescripcion().'.TXT' && count($fila) != 7) {
                                                $errores[]      = "El archivo no contiene la cantidad de datos esperados. Archivo {$nombreAsub} Linea {$txt}";
                                            }
                                            
                                        }
                                        
                                        // Procesa los afiliados
                                        
                                        if ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIADO)->getDescripcion().'.TXT') {
                                            
                                            $contador = 0;
                                            DB::delete("DELETE FROM res_afiliado");
                                            //desconectada.createQuery("delete from " + ResAfiliado.class.getCanonicalName()).executeUpdate();
                                            //desconectada.flush();
                                            
                                            $ID_AFILIADO = 0;
                                            $PRIMER_APELLIDO = 1;
                                            $SEGUNDO_APELLIDO = 2;
                                            $PRIMER_NOMBRE = 3;
                                            $SEGUNDO_NOMBRE = 4;
                                            $FECHA_NACIMIENTO = 5;
                                            $SEXO = 6;
                                            $CODIGO_ENTIDAD = 7;
                                            $valoresGuardar     = array();
                                            foreach($datos as $vector) {
                                                
                                                $datosAfiliado = new ResAfiliado();
                                                
                                                if ($vector[$ID_AFILIADO] == "" || $vector[$ID_AFILIADO] == null) {
                                                    $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                }
                                                
                                                if (count($errores) > 0) {
                                                    return $errores;
                                                }
                                                
                                                if (empty($errores)) {
                                                    $contador++;
                                                    $valoresGuardar[]       = [
                                                        'id_consecutivo'          => ($contador),
                                                        'afl_id'                  => (($vector[$ID_AFILIADO])),
                                                        'primer_apellido'         => (empty($vector[$PRIMER_APELLIDO]) ? null : $vector[$PRIMER_APELLIDO]),
                                                        'segundo_apellido'        => (empty($vector[$SEGUNDO_APELLIDO]) ? null : $vector[$SEGUNDO_APELLIDO]),
                                                        'primer_nombre'           => (empty($vector[$PRIMER_NOMBRE]) ? null : $vector[$PRIMER_NOMBRE]),
                                                        'segundo_nombre'          => (empty($vector[$SEGUNDO_NOMBRE]) ? null : $vector[$SEGUNDO_NOMBRE]),
                                                        'genero'                  => (empty($vector[$SEXO]) ? null : $vector[$SEXO]),
                                                        'entidad'                 => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                        'fecha_nacimiento'        => (empty($vector[$FECHA_NACIMIENTO]) ? null : $vector[$FECHA_NACIMIENTO]),
                                                    ];
                                                    //$datosAfiliado->save();
                                                    
                                                    /*if (contador % NUMERO_FLUSH == 0) {
                                                        desconectada.flush();
                                                        desconectada.clear();
                                                        LOG.info("FLUSH al Registro Nro " + contador + " ResAfiliado *******************************************************************" + datos.size());
                                                    }*/
                                                }
                                                
                                            }
                                            $dividir        = array_chunk($valoresGuardar, 5000);
                                            foreach ($dividir as $d) {
                                                DB::table('res_afiliado')->insert($d);
                                            }
                                            //desconectada.flush();
                                            //desconectada.clear();
                                            
                                        }
                                        
                                        // Procesa la historia de afiliaciones
                                        elseif ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::AFILIACIONES)->getDescripcion().'.TXT') {
                                            $contador = 0;
                                            //desconectada.createQuery("delete from " + ResAfiliacion.class.getCanonicalName()).executeUpdate();
                                            //desconectada.flush();
                                            
                                            DB::delete("DELETE FROM res_afiliacion");
                                            
                                            $ID_AFILIADO = 0;
                                            $ID_HISTORIA_AFILIACION = 1;
                                            $FECHA_INICIAL = 2;
                                            $FECHA_FINAL = 3;
                                            $TIPO_REGIMEN = 4;
                                            $CODIGO_ENTIDAD = 5;
                                            $valoresGuardar     = array();
                                            foreach($datos as $vector) {
                                                $datosHistoriaAfiliacion = new ResAfiliacion();
                                                
                                                if ($vector[$ID_AFILIADO] == "" || $vector[$ID_AFILIADO] == null) {
                                                    $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                }
                                                
                                                if (count($errores) > 0) {
                                                    return $errores;
                                                }
                                                
                                                if (empty($errores)) {
                                                    $contador++;
                                                    $valoresGuardar[]       = [
                                                        'id_consecutivo'        => ($contador),
                                                        'afl_id'                => ($vector[$ID_AFILIADO]),
                                                        'afc_id'                => (empty($vector[$ID_HISTORIA_AFILIACION]) ? null : ($vector[$ID_HISTORIA_AFILIACION])),
                                                        'entidad'               => (empty($vector[$CODIGO_ENTIDAD]) ? null : $vector[$CODIGO_ENTIDAD]),
                                                        'fecha_fin'             => (empty($vector[$FECHA_FINAL]) ? null : strpos($vector[$FECHA_FINAL], '2999') === FALSE ? null : $vector[$FECHA_FINAL]),
                                                        'fecha_inicio'          => (empty($vector[$FECHA_INICIAL]) ? null : $vector[$FECHA_INICIAL]),
                                                        'regimen'               => (empty($vector[$TIPO_REGIMEN]) ? null : $vector[$TIPO_REGIMEN]),
                                                    ];
                                                    //$datosHistoriaAfiliacion->save();
                                                    /*if (contador % NUMERO_FLUSH == 0) {
                                                        desconectada.flush();
                                                        desconectada.clear();
                                                        LOG.info("FLUSH al Registro Nro " + contador + " ResAfiliacion *******************************************************************" + datos.size());
                                                    }*/
                                                    //contador++;
                                                }
                                            }
                                            $dividir        = array_chunk($valoresGuardar, 5000);
                                            foreach ($dividir as $d) {
                                                DB::table('res_afiliacion')->insert($d);
                                            }
                                            //desconectada.flush();
                                            //desconectada.clear();
                                            
                                        }
                                        
                                        // Procesa la historia condicion de afiliacion
                                        elseif ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_CONDICION_AFILIACION)->getDescripcion().'.TXT') {
                                            $contador = 0;
                                            //desconectada.createQuery("delete from " + ResCondicionAfiliacion.class.getCanonicalName()).executeUpdate();
                                            //desconectada.flush();
                                            DB::delete("delete from res_condicion_afiliacion");
                                            $ID_AFILIACION = 0;
                                            $FECHA_INICIO = 1;
                                            $FECHA_FIN = 2;
                                            $TIPO_AFILIADO = 3;
                                            $ZONA_AFILIACION = 4;
                                            $ESTADO_AFILIACION = 5;
                                            $ENTIDAD = 6;
                                            $ID_DEPARTAMENTO = 7;
                                            $ID_MUNICIPIO = 8;
                                            $valoresGuardar     = array();
                                            foreach($datos as $vector) {
                                                
                                                $datosHistoriaCondicion = new ResCondicionAfiliacion();
                                                
                                                if ($vector[$ID_AFILIACION] == "" || $vector[$ID_AFILIACION] == null) {
                                                    $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                }
                                                
                                                if (count($errores) > 0) {
                                                    return $errores;
                                                }
                                                
                                                if (empty($errores)) {
                                                    $contador++;
                                                    $valoresGuardar[]       = [
                                                        'id_consecutivo'         => ($contador),
                                                        'afc_id'                 => ($vector[$ID_AFILIACION]),
                                                        'tipo_afiliado'          => (empty($vector[$TIPO_AFILIADO]) ? null : $vector[$TIPO_AFILIADO]),
                                                        'zona'                   => (empty($vector[$ZONA_AFILIACION]) ? null : $vector[$ZONA_AFILIACION]),
                                                        'estado'                 => (empty($vector[$ESTADO_AFILIACION]) ? null : $vector[$ESTADO_AFILIACION]),
                                                        'departamento'           => (empty($vector[$ID_DEPARTAMENTO]) ? null : $vector[$ID_DEPARTAMENTO]),
                                                        'municipio'              => (empty($vector[$ID_MUNICIPIO]) ? null : $vector[$ID_MUNICIPIO]),
                                                        'fecha_inicio'           => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                        'fecha_fin'              => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN], "2999") === FALSE ? null : $vector[$FECHA_FIN]),
                                                        'entidad'                => (empty($vector[$ENTIDAD]) ? null : $vector[$ENTIDAD]),
                                                    ];
                                                    //$datosHistoriaCondicion->save();
                                                    
                                                    /*if (contador % NUMERO_FLUSH == 0) {
                                                        desconectada.flush();
                                                        desconectada.clear();
                                                        LOG.info("FLUSH al Registro Nro " + contador + " ResCondicionAfiliacion *******************************************************************" + datos.size());
                                                    }
                                                    contador++;*/
                                                }
                                                
                                            }
                                            $dividir        = array_chunk($valoresGuardar, 5000);
                                            foreach ($dividir as $d) {
                                                DB::table('res_condicion_afiliacion')->insert($d);
                                            }
                                            //desconectada.flush();
                                            //desconectada.clear();
                                        }
                                        // Procesa historia de identificacion
                                        elseif ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::HISTORIA_IDENTIFICACION)->getDescripcion().'.TXT') {
                                            $contador = 0;
                                            DB::delete("DELETE FROM res_historico_afiliacion");
                                            //desconectada.createQuery("delete from " + ResHistoricoAfiliacion.class.getCanonicalName()).executeUpdate();
                                            //desconectada.flush();
                                            
                                            $CONSECUTIVO_SERIAL = 0;
                                            $TIPO_IDENTIFICACION = 1;
                                            $DOCUMENTO_IDENTIFICACION = 2;
                                            $FECHA_INICIO = 3;
                                            $FECHA_FIN = 4;
                                            $ENTIDAD = 5;
                                            $valoresGuardar     = array();
                                            foreach($datos as $vector) {
                                                
                                                $datosHistoriaIdentificacion = new ResHistoricoAfiliacion();
                                                
                                                if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                    $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                }
                                                
                                                if (count($errores) > 0) {
                                                    return $errores;
                                                }
                                                
                                                if (empty($errores)) {
                                                    $contador++;
                                                    $valoresGuardar[]       = [
                                                        'afl_id'                    => (($vector[$CONSECUTIVO_SERIAL])),
                                                        'consecutivo_id'            => ($contador),
                                                        'tipo_identificacion'       => (empty($vector[$TIPO_IDENTIFICACION]) ? null : $vector[$TIPO_IDENTIFICACION]),
                                                        'numero_identificacion'     => (empty($vector[$DOCUMENTO_IDENTIFICACION]) ? null : $vector[$DOCUMENTO_IDENTIFICACION]),
                                                        'fecha_inicio'              => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                        'fecha_fin'                 => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN], "2999") === FALSE ? null : $vector[$FECHA_FIN]),
                                                        'entidad'                   => (empty($vector[$ENTIDAD]) ? null : $vector[$ENTIDAD]),
                                                    ];
                                                    //$datosHistoriaIdentificacion->save();
                                                    /*if (contador % NUMERO_FLUSH == 0) {
                                                        desconectada.flush();
                                                        desconectada.clear();
                                                        LOG.info("FLUSH al Registro Nro " + contador + " ResHistoricoAfiliacion *******************************************************************" + datos.size());
                                                    }*/
                                                }
                                            }
                                            $dividir        = array_chunk($valoresGuardar, 5000);
                                            foreach ($dividir as $d) {
                                                DB::table('res_historico_afiliacion')->insert($d);
                                            }
                                            //desconectada.flush();
                                            //desconectada.clear();
                                        }
                                        // Procesa historia de identificacion
                                        elseif ($nombreAsub == ETipoArchivoSoporte5TablasSubsidiado::getIndice(ETipoArchivoSoporte5TablasSubsidiado::CONDICION_AFILIACIONES_SUBSIDIADO)->getDescripcion().'.TXT') {
                                            $contador = 0;
                                            DB::delete("delete from res_condicion_afiliacion_subsidiado");
                                            //desconectada.createQuery("delete from " + ResCondicionAfiliacionSubsidiado.class.getCanonicalName()).executeUpdate();
                                            //desconectada.flush();
                                            
                                            $CONSECUTIVO_SERIAL = 0;
                                            $FECHA_INICIO = 1;
                                            $FECHA_FIN = 2;
                                            $TIPO_SUBSIDIO = 3;
                                            $NIVEL = 4;
                                            $GRUPO_POBLACIONAL = 5;
                                            $ENTIDAD = 6;
                                            $valoresGuardar     = array();
                                            foreach($datos as $vector) {
                                                $datosHistoriaIdentificacion = new ResCondicionAfiliacionSubsidiado();
                                                if ($vector[$CONSECUTIVO_SERIAL] == "" || $vector[$CONSECUTIVO_SERIAL] == null) {
                                                    $errores[]      = 'Error: El ID no debe ser un campo Nulo';
                                                }
                                                
                                                if (count($errores) > 0) {
                                                    return $errores;
                                                }
                                                
                                                if (empty($errores)) {
                                                    $contador++;
                                                    $valoresGuardar[]       = [
                                                        'consecutivo_id'            => ($contador),
                                                        'tipo_subsidio'             => (empty($vector[$TIPO_SUBSIDIO]) ? null : $vector[$TIPO_SUBSIDIO]),
                                                        'nivel'                     => (empty($vector[$NIVEL]) ? null : $vector[$NIVEL]),
                                                        'fecha_inicio'              => (empty($vector[$FECHA_INICIO]) ? null : $vector[$FECHA_INICIO]),
                                                        'fecha_fin'                 => (empty($vector[$FECHA_FIN]) ? null : strpos($vector[$FECHA_FIN], "2999") === FALSE ? null : $vector[$FECHA_FIN]),
                                                        'entidad'                   => (empty($vector[$ENTIDAD]) ? null : $vector[$ENTIDAD]),
                                                        'grupo_poblacional'         => (empty($vector[$GRUPO_POBLACIONAL]) ? null : $vector[$GRUPO_POBLACIONAL]),
                                                    ];
                                                    //$datosHistoriaIdentificacion->save();
                                                    
                                                    /*if (contador % NUMERO_FLUSH == 0) {
                                                        desconectada.flush();
                                                        desconectada.clear();
                                                        LOG.info("FLUSH al Registro Nro " + contador + " ResCondicionAfiliacionSubsidiado *******************************************************************" + datos.size());
                                                    }
                                                    contador++;*/
                                                }
                                            }
                                            $dividir        = array_chunk($valoresGuardar, 5000);
                                            foreach ($dividir as $d) {
                                                DB::table('res_condicion_afiliacion_subsidiado')->insert($d);
                                            }
                                            //desconectada.flush();
                                            //desconectada.clear();
                                        }
                                    }
                                    
                                }
                                else {
                                    $errores[]          = 'Error: No es un directorio';
                                }
                            }
                        }
                    }
                    
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
        //Util.getBorrarDirectorioCompleto(Util.path);
        return $errores;
    }
}

