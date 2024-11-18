<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/07/2018
 * Time: 2:57 PM
 */

namespace App\Traits;


use App\Models\General\GnArchivo;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ArchivoTrait
{
    public static function download ($ruta, $nombre, $disposition='inline'){
        try {
            $s3Client = Storage::cloud()->getAdapter()->getClient();
            $stream = $s3Client->getObject([
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key'    => $ruta
            ]);

            return response($stream['Body'], 200)->withHeaders([
                'Content-Type'        => $stream['ContentType'],
                'Content-Length'      => $stream['ContentLength'],
                'Content-Disposition' => $disposition.'; filename="'.$nombre.'"'
            ]);

        }  catch (S3Exception $e) {
            return response()->json(
                [
                    'message' => 'The requested file not exists'
                ],
                404
            );
        } catch (AwsException $e) {
            return response()->json(
                [
                    'message' => 'An error as ocurred'
                ],
                401
            );
        }
    }

    /**
     * @param UploadedFile $file
     * @param $alojamiento
     * @param array $isMimeAccepts
     * @return mixed
     * @throws \Exception
     */
    public static function subirArchivo (UploadedFile $file, $alojamiento, $isMimeAccepts = ['application/pdf']) {

        $mime = $file->getClientMimeType();

        if(!in_array($mime,$isMimeAccepts)) throw new \Exception('Tipo de archivo no aceptado');


        $ruta = $file->storeAs($alojamiento, $file->getClientOriginalName());

        do
        {
            $file_name = Str::slug($file->getClientOriginalName()).Str::random(6).'.'.File::extension($file->getClientOriginalName());
        }
        while (File::exists($alojamiento.'/'.$file_name));


        $archivo = GnArchivo::create([
            'ruta' => $ruta,
            'nombre' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $file->extension()
        ]);

        return $archivo;
    }

    public static function eliminarArchivo ($idArchivo) {

        // Function Optional For Delete
        $archivo = GnArchivo::find($idArchivo);
        Storage::delete($archivo->ruta);
        $archivo->delete();

    }
}
