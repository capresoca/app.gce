<?php

namespace App\Http\Controllers\Niif;

use App\Exceptions\ComdiarioException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Http\Requests\Niif\ComdiarioRequest;
use App\Http\Resources\Niif\ComdiarioResource;
use App\Http\Resources\Niif\ComdiariosResource;
use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfMese;
use App\Traits\ToolsTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ComdiarioController extends Controller
{
    public function __construct(ComdiarioRespository $repoComdiario)
    {
        $this->repoComdiario = $repoComdiario;
        //PermiteTrait::checkPermisos($this,23);
    }

    public function index()
    {
        if (Input::get('per_page')) {
            $comdiarios = NfComdiario::pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));
            return ComdiariosResource::collection($comdiarios);
        }
        return ComdiariosResource::collection(NfComdiario::pimp()->orderBy('consecutivo', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $comdiarioRequest = $request->toArray();
            $this->repoComdiario->validar($comdiarioRequest);
            $comdiario = $this->repoComdiario->guardar($comdiarioRequest);
            DB::commit();
            return response()->json([
                'message' => 'El comprobante de diario fue creado con exito',
                'comdiario' => new ComdiariosResource($comdiario),
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator,
            ], 422);
        } catch (ComdiarioException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el comprobante',
                'errors' => $e->getMessage(),
            ], 428);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'Error en el servidor.',
            ], 500);
        }
    }

    public function show(NfComdiario $comdiario)
    {
        return new ComdiarioResource($comdiario->load(['detalles', 'detalles.niif', 'detalles.tercero', 'detalles.cencosto', 'detalles.conrtf']));
    }

    //este metodo solo edita la cabecera para la edicion
    // completa con sus detalles se debe usar el metodo store.
    public function update(ComdiarioRequest $request, NfComdiario $comdiario)
    {
        try {
            DB::beginTransaction();
            $comdiario->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'El comprobante de diario fue actualizado con éxito',
                'data' => new ComdiariosResource($comdiario),
            ], 202);
        } catch (ValidationException $validationException) {
            DB::rollBack();
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $validationException->validator,
            ], 422);
        } catch (ComdiarioException $comdiarioException) {
            DB::rollBack();
            return response()->json([
                'message' => $comdiarioException->getMessage()
            ], 428);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e,
                'message' => 'Error en el servidor :' . $e->getMessage(),
            ], 500);
        }
    }

    public function getPDF(NfComdiario $comdiario)
    {
        try {

            $comdiario->load(['detalles.tercero', 'detalles.cencosto', 'detalles.conrtf', 'detalles.niif', 'tipo', 'usuario']);
            if (Input::get('html')) {
                return view('dompdf.comdiario');
            }

            $pdf = PDF::loadView('dompdf.comdiario', ['comdiario' => $comdiario]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Comprobante de diario', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function firmarComdiario()
    {
        return URL::temporarySignedRoute('comdiario', now()->addMinute(30), [Input::get('id')]);
    }

    function repetirComprobante(Request $request)
    {
        try {
            $comdiariorigen = NfComdiario::with('detalles')->where('id', $request->nf_comdiario_origen_id)->first();
            foreach ($comdiariorigen->detalles as $detalle) {
                $detalle->id = NULL;
            }
            $comdiariorigen->id = NULL;
            $comdiariorigen->fecha = $request->fecha;
            $comdiariorigen->estado = 'Registrado';
            $comdiariorigen->nf_tipcomdiario_id = $request->nf_tipcomdiario_id;
            $comdiarioRequest = $comdiariorigen->toArray();

            DB::beginTransaction();
            $comdiario = $this->repoComdiario->guardar($comdiarioRequest);
            DB::commit();

            return response()->json([
                'state' => 'ok',
                'comdiario' => new ComdiariosResource($comdiario),
            ]);
        } catch (ComdiarioException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el comprobante',
                'errors' => $e->getMessage(),
            ], 428);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    function consecutivoActual()
    {
        return response()->json(['consecutivo' => NfComdiario::max('consecutivo') + 1]);
    }

    function getMeses()
    {
        try {
            $datos = NfMese::all();
            if (count($datos) == 0) {
                $hoy = Carbon::now();
                $mesCreate = new NfMese();
                $mesCreate->mes = ToolsTrait::monthTraslateSpanish($hoy->month);
                $mesCreate->year = $hoy->year;
                $mesCreate->estado = "Abierto";
                $mesCreate->save();
                $datos = NfMese::all();
            }
            $nfMeses = new NfMese();
            if ($nfMeses->poblarMeses()) {
                $datos = NfMese::all();
            }
            return response()->json([
                'datos' => $datos,
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    function cerarMes(Request $request)
    {
        try {
            $mes = NfMese::where('id', $request->id)->first();
            $mes->estado = 'Cerrado';
            $mes->save();
            return response()->json([
                'state' => 'ok',
                'datos' => NfMese::all(),
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    function abrirMes(Request $request)
    {
        $mes = NfMese::where('id', $request->id)->first();
        $mes->estado = 'Abierto';
        $mes->save();
        return response()->json([
            'state' => 'ok',
            'datos' => NfMese::all(),
        ]);
    }
}
