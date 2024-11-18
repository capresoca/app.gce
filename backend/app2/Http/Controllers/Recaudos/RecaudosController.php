<?php

namespace App\Http\Controllers\Recaudos;

use App\Capresoca\RecaudosSOI\Historico\ClasificadorArchivoAIUnificado;
use App\Capresoca\RecaudosSOI\Historico\ClasificadorIP;
use App\Http\Requests\Recaudos\RecuadoRequest;
use App\Models\Aseguramiento\AsCargasRecaudo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RecaudosController extends Controller
{

    public function store(RecuadoRequest $request)
    {
        $realName = $this->extraerArchivos($request);

        $files = File::allFiles(Storage::disk('local')->path('recaudos/' . $realName));

        $carga = AsCargasRecaudo::create([
            'nombre_archivos_zip' => $request->file('archivo')->getClientOriginalName(),
            'numero_archivos_zip' => 1,
            'numero_archivos_txt' => count($files),
            'estado_carga' => 'Completa'
        ]);
        //$partInfo = storage_path('app\\recaudos\\'.$realName);
        $partInfo = storage_path("app/recaudos")."/$realName";
        $procesadorI = new ClasificadorArchivoAIUnificado($partInfo,$carga->id);
        $procesadorI->handle();

        return response()->json([
            'msg' =>'Se han cargado correctamente los archivos.',
            'data' => $carga
        ]);

    }


    private function extraerArchivos(Request $request)
    {
        $archivo = $request->file('archivo');
        $nombre = $archivo->getClientOriginalName();
        $realName = explode('.', $nombre)[0];
        $ruta = Storage::disk('local')->put('/recaudos/', $archivo);
        $path = Storage::disk('local')->path($ruta);

        if (File::isDirectory(Storage::disk('local')->path('recaudos/' . $realName))) {
            File::deleteDirectory(Storage::disk('local')->path('recaudos/' . $realName));
        }
        //return 'ok';
        $zip = new \ZipArchive;
        $zip->open($path);
        $zip->extractTo(Storage::disk('local')->path('recaudos/'));
        $zip->close();
        //File::delete(File::get(Storage::disk('local')->path('recaudos/'.$realName)));

        //return $realName;
        $files = File::allFiles(Storage::disk('local')->path('recaudos/' . $realName));

        foreach ($files as $file) {
            if (!strpos($file->getFilename(), 'cnt')) {
                $zip = new \ZipArchive();
                $zip->open($file->getPathname());
                $zip->extractTo($file->getPath());
                $zip->close();
            }
            File::delete($file);
        }
        return $realName;
    }

}
