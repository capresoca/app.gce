<?php

namespace App\Http\Controllers\Niif;

use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Imports\ComprobanteDiarioImport;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfComdiadetalle;
use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfTipcomdiario;
use App\Models\Niif\NfComdiarioLote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class InterfazContableController extends Controller
{
    public function __construct(ComdiarioRespository $repoComdiario)
    {
        $this->repoComdiario = $repoComdiario;
    }

    public function IndexComprobanteDiarioLote()
    {
        if (Input::get('per_page')) {

            $response = Resource::collection(NfComdiarioLote::pimp()->orderBy('id', 'desc')->paginate(Input::get('per_page')));

            return $response;

//            return Resource::collection(
//                NfComdiarioLote::all()->pimp()
//                    ->orderBy('id', 'desc')
//                    ->paginate(Input::get('per_page'))
//
//
//
//                    ->map(function ($comdiario_lote) {
//                    $comdiario_lote['nf_comdiarios_id'] = [
//                        $comdiario_lote['nf_comdiarios_id'] => NfComdiario::where('id', '=', $comdiario_lote['nf_comdiarios_id'])->first()->toArray()
//                    ];
//                    return $comdiario_lote;
//                })->pimp()
//                    ->orderBy('id', 'desc')
//                    ->paginate(Input::get('per_page'))
//            );
        }

        $comdiario_lotes = NfComdiarioLote::all()->map(function ($comdiario_lote) {
            $comdiario_lote['nf_comdiarios_id'] = [
                $comdiario_lote['nf_comdiarios_id'] => NfComdiario::where('id', '=', $comdiario_lote['nf_comdiarios_id'])->first()->toArray()
            ];
            return $comdiario_lote;
        })->pimp()->limit(1000)->orderBy('updated_at', 'desc')->get();

        return Resource::collection($comdiario_lotes);

//        return response()->json([
//            'message' => '',
//            'data' => $comdiario_lotes
//        ], 200);
    }

    public function ImportarComprobanteDiarioLote(Request $request)
    {
        $errores = [];
        $file = $request->file('excelComprobante');
        $extension = $file->extension();

        if ($extension != 'xlsx' and $extension != 'xls' and $extension != 'XLS' and $extension != 'XLSX') {
            array_push($errores, 'El archivo tiene una extensión no válida. No es un archivo de Excel.');
            return response($errores, '422');
        }

        $import = new ComprobanteDiarioImport();
        Excel::import($import, $file);

        if (count($import->getErrores())) {
            return response($import->getErrores(), 422);
        } else {
            try {
                $detalles = [];
                $validos = collect($import->getValidos())->map(function ($row) {
                    $row['tccodigo'] = NfTipcomdiario::where('codigo', '=', $row['tccodigo'])->first()->toArray()['id'];
                    $row['cuecodigo'] = NfNiif::where('codigo', '=', $row['cuecodigo'])->first()->toArray()['id'];
                    $row['ternumdoc'] = GnTercero::where('identificacion', '=', $row['ternumdoc'])->first()->toArray()['id'];
                    $row['cccodigo'] = NfCencosto::where('codigo', '=', $row['cccodigo'])->first()->toArray()['id'];
                    $row['crcodigo'] = is_null($row['crcodigo']) ? $row['crcodigo'] : NfConrtf::where('codigo', '=', $row['crcodigo'])->first()->toArray()['id'];
                    return $row;
                });

                foreach ($validos as $row) {
                    array_push($detalles, [
                        'nf_niif_id' => $row['cuecodigo'],
                        'nf_comdiario_id' => null,
                        'gn_tercero_id' => $row['ternumdoc'],
                        'nf_cencosto_id' => $row['cccodigo'],
                        'nf_conrtf_id' => $row['crcodigo'],
                        'debito' => $row['cmmvaldeb'],
                        'credito' => $row['cmmvalcre'],
                        'retencion' => null,
                        'observacion' => $row['comdetalled']
                    ]);
                }

                $comdiarioRequest = [
                    'id' => null,
                    'consecutivo' => null,
                    'nf_tipcomdiario_id' => $validos[0]['tccodigo'],
                    'fecha' => $validos[0]['comfeccom'],
                    'detalle' => $validos[0]['comdetalle'],
                    'documento' => $validos[0]['comnumdocu'],
                    'detalles' => $detalles
                ];

                DB::beginTransaction();
                $comdiario = $this->repoComdiario->guardar($comdiarioRequest);
                $comdiario_lote = new NfComdiarioLote();
                $comdiario_lote->nf_comdiarios_id = $comdiario->id;
                $comdiario_lote->save();
                DB::commit();
                return response()->json([
                    'message' => 'El comprobante diario fue creado con éxito.',
                    'data' => $comdiario
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'El comprobante diario no pudo ser creado con éxito.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function EliminarComprobanteDiarioLote($id)
    {
        try {
            $comdiario_lote = NfComdiarioLote::where('nf_comdiarios_id', '=', $id)->first();
            $comdiario = NfComdiario::where('id', '=', $comdiario_lote['nf_comdiarios_id'])->first();
            $comdiario_detalle = NfComdiadetalle::where('nf_comdiario_id', '=', $comdiario['id']);
            $acciones = [];

            DB::beginTransaction();
            if (!is_null($comdiario_lote)) {
                $comdiario_lote->delete();
                array_push($acciones, 'Un comprobante diario importado fue eliminado con éxito.');
            } else {
                array_push($acciones, 'Ningún comprobante diario importado fue eliminado.');
            }

            if (!is_null($comdiario_detalle)) {
                $totalEliminados = $comdiario_detalle->delete();
                array_push($acciones, $totalEliminados . ' detalles de comprobante diario fueron eliminados con éxito.');
            } else {
                array_push($acciones, 'Ningún detalle de comprobante diario fue eliminado.');
            }

            if (!is_null($comdiario)) {
                array_push($acciones, 'Un comprobante diario fue eliminado con éxito.');
                $comdiario->delete();
            } else {
                array_push($acciones, 'Ningún comprobante diario fue eliminado.');
            }
            DB::commit();

            return response()->json([
                'message' => 'El comprobante fue eliminado con éxito.',
                'acciones' => $acciones
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return \response()->json([
                'message' => 'No se pudo eliminar el Comprobante diario.',
                'error' => $e->getMessage()
            ]);
        }
    }

}
