<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\CensoRequest;
use App\Imports\CensoImport;
use App\Models\CuentasMedicas\CmCenso;
use App\Models\CuentasMedicas\CmPacientesHospitalario;
use App\Models\General\GnArchivo;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CensoController extends Controller
{
    use ArchivoTrait;
    public function index()
    {
        if(Input::get('per_page') ){
            return Resource::collection(
                CmCenso::with('usuario','archivo','ips.tercero')->pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            CmCenso::with('usuario','archivo','ips.tercero')
                ->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );
    }



    public function store(CensoRequest $request)
    {
        $import = new CensoImport();

        $archivo_censo = $this->subirStorage($request->archivo);

        Excel::import($import, $request->archivo);

        if(count($import->getInvalido())){
            return response($import->getInvalido(),422);
        }

        $censo = CmCenso::create(array_merge($request->all(), ['archivo_id' => $archivo_censo->id]));

        $censo->pacientes()->createMany($import->getPacientes());

        return new Resource($censo);
    }

    public function show(CmCenso $censo)
    {
        return new Resource($censo->load('pacientes','usuario','archivo','ips.tercero'));
    }

    private function subirStorage($file)
    {
        $ruta = $file->storeAs('CuentasMedicas/Censos', $file->getClientOriginalName());

        $archivo = GnArchivo::create([
            'ruta' => $ruta,
            'nombre' => $file->getClientOriginalName(),
            'size' => $file->getClientSize(),
            'extension' => $file->extension()
        ]);

        return $archivo;
    }

    public function getPacientes ($censo) {
        $query =  CmPacientesHospitalario::with('censo.ips','afiliado','diagnostico','concurrencia')->where('cm_censo_id',$censo)->pimp();
        $per_page = Input::get('per_page');
        if($per_page){
            return Resource::collection($query->orderBy('orden')->paginate($per_page));
        }

        return Resource::collection($query->limit(1000)->orderBy('orden')->get());
    }

}
