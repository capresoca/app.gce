<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Requests\Cartera\ConfiguracionRequest;
use App\Http\Resources\Cartera\ConfiguracionResource;
use App\Models\Cartera\CrConfiguracione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfiguracionesController extends Controller
{

    public function index()
    {
        $cr_configuracione = CrConfiguracione::with('niifcxc','niifglosas','tipodebito','tipocredito','tipotraslados','tipocxc','tiporglosas','tipocglosas','tipopcartera','tiporesponsabilidades','tipocdr','tiponmp','tiporap')->first();
        if ($cr_configuracione) {
            return new ConfiguracionResource($cr_configuracione);
        }
        return response()->json([
            'message' => 'Aún no se ha realizado la configuración de cartera.'
        ]);
    }


    public function store(ConfiguracionRequest $request)
    {
        $cr_configuracione = CrConfiguracione::create($request->all());
        return response()->json([
            'message' => 'El vendedor fue creado con exito',
            'data' => CrConfiguracione::where('id',$cr_configuracione->id)->with('niifcxc','niifglosas','tipodebito','tipocredito','tipotraslados','tipocxc','tiporglosas','tipocglosas','tipopcartera','tiporesponsabilidades','tipocdr','tiponmp','tiporap')->first()
        ]);
    }


    public function show(CrConfiguracione $cr_configuracione)
    {
        return new ConfiguracionResource($cr_configuracione->load('niifcxc','niifglosas','tipodebito','tipocredito','tipotraslados','tipocxc','tiporglosas','tipocglosas','tipopcartera','tiporesponsabilidades','tipocdr','tiponmp','tiporap'));
    }


    public function update(ConfiguracionRequest $request, $id)
    {
        try {
            $cr_configuracione = CrConfiguracione::find($id);
            $cr_configuracione->update($request->all());
            $cr_configuracione = CrConfiguracione::where('id',$id)->with('niifcxc','niifglosas','tipodebito','tipocredito','tipotraslados','tipocxc','tiporglosas','tipocglosas','tipopcartera','tiporesponsabilidades','tipocdr','tiponmp','tiporap')->first();
        return response()->json([
                'message' => 'El configuracion fue editado con exito',
                'data' => $cr_configuracione
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findInique()
    {
        try {
            $cr_configuracione = CrConfiguracione::with('niifcxc','niifglosas','tipodebito','tipocredito','tipotraslados','tipocxc','tiporglosas','tipocglosas','tipopcartera','tiporesponsabilidades','tipocdr','tiponmp','tiporap')->orderBy('id', 'ASC')->first();
            if($cr_configuracione){
                 return response()->json([
                    'exists' => true,
                    'message'=> 'Configuracion registrada',
                    'data' => $cr_configuracione
                ],200);
            }
            return response()->json([
                'exists' => false,
                'message' => 'El concepto no existe'
            ],204);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}