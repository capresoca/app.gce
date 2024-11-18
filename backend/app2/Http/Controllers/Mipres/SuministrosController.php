<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Requests\Mipres\SuministroRequest;
use App\Jobs\Mipres\Direccionamiento;
use App\Jobs\Mipres\Suministro;
use App\Models\Mipres\MpSuministro;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class SuministrosController extends Controller
{
    public function store(SuministroRequest $suministroRequest)
    {
        DB::beginTransaction();

        $suministro = MpSuministro::create($suministroRequest->all());

        try {
            $suministroMipres = new Suministro($suministro);
            $response = $suministroMipres->send();

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

        $suministro->update([
            'id' => $response[0]['IdSuministro'],
        ]);

        $suministro->load('direccionamiento');

        DB::commit();

        return new Resource($suministro);
    }
}
