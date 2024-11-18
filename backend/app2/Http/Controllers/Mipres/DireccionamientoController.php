<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Requests\Mipres\DireccionamientoRequest;
use App\Jobs\Mipres\Direccionamiento;
use App\Models\Mipres\MpDireccionamiento;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class DireccionamientoController extends Controller
{

    public function index()
    {
        if(Input::get('per_page') || Input::get('all')){
            return Resource::collection(
                MpDireccionamiento::withAll()->pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            MpDireccionamiento::withAll()->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );
    }

    public function store(DireccionamientoRequest $request)
    {
        DB::beginTransaction();

        $direccionamiento = MpDireccionamiento::create($request->all());

        if(!$direccionamiento->DirPaciente){
            $direccionamiento->DirPaciente = 'Sin direccion';
            $direccionamiento->save();
        }

        try {
            $direccionamientoMipres = new Direccionamiento($direccionamiento);
            $response = $direccionamientoMipres->send();

        } catch (ClientException $e) {
            DB::rollBack();
            $error = json_decode($e->getResponse()->getBody()->getContents(),true);
            return response()->json([
                'message' => $error
            ],422);
        } catch (GuzzleException $e) {
            DB::rollBack();
            return response()->json([
                $e->getMessage()
            ],500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                $e->getMessage()
            ],500);
        }

        $direccionamiento->update([
            'IdDireccionamiento' => $response[0]['IdDireccionamiento'],
            'suministro_id' => $response[0]['Id']
        ]);

        $direccionamiento->load('entidad.municipio');

        DB::commit();

        return new Resource($direccionamiento);
    }

    public function destroy(MpDireccionamiento $direccionamiento)
    {
        try{
            $direccionamientoMipres = new Direccionamiento($direccionamiento);
            $response = $direccionamientoMipres->anular();
            $direccionamiento->deleted_by = Auth::user()->id;
            $direccionamiento->save();
            $direccionamiento->delete();
            return response()->json()->setStatusCode(202);

        } catch (ClientException $e) {
            return response()->json([
                'message' => json_decode($e->getResponse()->getBody()->getContents(),true)
            ],422);
        } catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        } catch (GuzzleException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

}
