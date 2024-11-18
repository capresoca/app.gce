<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\RecaudosSOI\PersistirPlanilla;
use App\Models\Aseguramiento\AsCargasRecaudo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use ZipArchive;

class CargadorRecaudosController extends Controller
{
    public function index(){
        if(Input::get('per_page') ){
            return Resource::collection(
                AsCargasRecaudo::with('usuario')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            AsCargasRecaudo::with('usuario')
                ->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );
    }

    public function store(Request $request)
    {
        try{
            if (!$request->hasFile('files')) throw new \Exception('Cargue archivos validos');
            $files = $request->hasFile('files') ? $request->file('files') : [];
            $info = $this->guardarRegistro($files);
            $this->browserFiles($files,$info);
            return response()->json([
                'msg' =>'Se han cargado correctamente los archivos.',
                'data' => $info
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function guardarRegistro ($files) {
        $as_carga = new AsCargasRecaudo();
        $as_carga->numero_archivos_zip = count($files);
        $zips = $this->obtenerAntesDeProcesar($files);
        if ($zips === true) {
            throw new \Exception("Exite uno o varios archivos que ya fueron procesados, por favor revisar y volver a cargar.");
        }
        $convertJson = json_encode((array) $zips['originalnameszips']);
        $as_carga->nombre_archivos_zip = (string) $convertJson;
        $as_carga->numero_archivos_txt = (integer) $zips['texts'];
        $as_carga->save();
        return $as_carga;
    }

    public function obtenerAntesDeProcesar ($files) {
        $data = ['originalnameszips' => [], 'texts' => 0];
        $zip = new ZipArchive;
        foreach ($files as  $file) {
            $originalName = $file->getClientOriginalName();
            array_push($data['originalnameszips'], $originalName);
            if ($gnArchivo = $this->siArchiveExist($originalName)) return $gnArchivo;
            if ($zip->open($file) === true) {
                $data['texts']+=$zip->numFiles;
            }
        }
        return $data;
    }

    public function browserFiles ($files,$info) {
        try {
            foreach ($files as $key => $file) {
                $originalName = $file->getClientOriginalName();
                $path =  "Aseguramiento/Planillas/$originalName";
                $this->openZips($file, $path, $info);
                $this->delete_directory($path);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function delete_directory ($dirPath) {
        if (! is_dir($dirPath)) {
            throw new \Exception("$dirPath debe ser un directorio");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                mkdir($file);
//                self::delete_directory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function siArchiveExist ($name) {
        $existen = [];
        foreach (AsCargasRecaudo::all('nombre_archivos_zip') as $as_cargado) {
            foreach (json_decode($as_cargado->nombre_archivos_zip) as $item) {
                if($item === $name) array_push($existen, $item);
            }
        }
        return count($existen) > 0;
    }

    public function openZips($file, $path, $info) {
        $archive = is_dir($path) ? null : (new ZipArchive);
        if (($this->esZip($file, $archive)) || (is_null($archive))) {
            is_null($archive) ? (null) : $archive->extractTo($path);
            $dir = opendir($path);
            while ($filePath = readdir($dir)) {
                if ($filePath !== '.' && $filePath !== '..') {
                    if (is_dir($path."/$filePath")) {
                        $this->openZips(null,$path."/$filePath/",$info);
                    } else {
                        $this->processArchives($filePath, $path, $info);
                    }
                }
            }
            is_null($archive) ? (null) : $archive->close();
        }
    }

    private function processArchives($filePath, $path, $info) {
        $explodeCurrent = explode('_',$filePath,9);
        if ($explodeCurrent[7] === 'I' || $explodeCurrent[7] === 'IR') {
            $pila = new PersistirPlanilla("$path/$filePath",$info->id);
            $pila->handle();
        }
    }

    /**
     * @param $file
     * @param ZipArchive|null $archive
     * @return bool|null
     */
    private function esZip($file, ?ZipArchive $archive)
    {
        return $archive !== null ? ($archive->open($file) === true) : null;
    }
}
