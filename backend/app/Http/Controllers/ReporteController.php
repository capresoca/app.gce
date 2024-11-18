<?php

namespace App\Http\Controllers;

use App\Exports\ReporteExport;
use App\Models\Reportes\Reporte;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Laracsv\Export;
use Maatwebsite\Excel\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(
                Reporte::with('variables', 'roles')->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return Resource::collection(
            Reporte::with('variables', 'roles')->pimp()->orderBy('updated_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->getValidationRules(), $this->getValidationRules());
            $this->probarSentecia($request);

            //Agregar o remplazar limit de la sentencia para probar.
            $reporte = Reporte::create($request->all());
            $reporte->variables()->createMany($request->variables);
            $reporte->roles()->attach($request->roles);

            return new Resource($reporte);

        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'Error de sintaxis sql',
                'errors' => [
                    'sentencia' => $exception->getMessage()
                ]
            ], 422);
        }
    }

    public function update(Request $request, Reporte $reporte)
    {
        $rules = $this->getValidationRules();
        $rules['id'] = 'required';
        $this->validate($request, $rules, $this->getValidationRules());

        $reporte->update($request->all());

        $reporte->variables()->delete();
        $reporte->variables()->createMany($request->variables);

        $reporte->roles()->detach();
        $reporte->roles()->attach($request->roles);

        return new Resource($reporte);
    }

    public function show(Reporte $reporte)
    {
        $reporte->load('variables', 'roles');
        return new Resource($reporte);
    }

    public function probarSQL(Request $request)
    {
        try {

            $this->validate($request, $this->getValidationRules(), $this->getValidationMessages());

            return DB::select($request->consulta);

        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'Error de sintaxis sql',
                'errors' => [
                    'consulta' => $exception->getMessage()
                ]
            ], 422);
        }
    }

    public function ejecutarSQL(Request $request)
    {
        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '300');

            $this->validate($request, $this->getValidationRules(), $this->getValidationMessages());

            $result = DB::select($request->sentencia);

            if (!$result)
                abort(202, 'La consulta no tiene resultados');

            $result = collect($result)->map(function ($row) {
                return get_object_vars($row);
            });

            $columns = array_keys($result->first());

            $txtContent = $this->getPlain($columns, $result, '|');

            $fileName = 'Reporte-' . date('dmY-hisa') . '.txt';
//            $exporter = new ReporteExport($result, $columns, $fileName);

            $localDisk = Storage::disk('local');

            if (!($localDisk->put('public/tmp/' . $fileName, $txtContent))) {
                abort(500, 'No se pudo crear el archivo a descargar');
            }

            return response()->download(storage_path('app/public/tmp/' . $fileName))->deleteFileAfterSend();


//            $exporter->store('public/tmp/' . $fileName, 'local', Excel::CSV, null, [
//                'visibility' => 'public'
//            ]);

//            return $exporter->download($fileName, Excel::CSV);

        } catch (QueryException $exception) {
            abort(422, 'Error de sintaxis SQL: ' . $exception->getMessage());
        } catch (\Exception $ex) {
            abort(422, $ex->getMessage());
        }
    }

    private function getValidationRules()
    {

        return [
            'sentencia' => [
                'not_regex:/(DELETE | CREATE | ALTER | DROP | RENAME | TRUNCATE | CALL | IMPORT | INSERT | REPLACE | LOAD | UPDATE | GRANT) / i'
            ]
        ];
    }

    private function getValidationMessages()
    {
        return [
            'sentencia.not_regex' => 'La consulta no es permitida',
        ];
    }

    private function probarSentecia($request)
    {
        $limit_regex = ' / (LIMIT) .*\d / i';
        $variable_regex = '((:\S +)\s *)';

        if (preg_match($limit_regex, $request->sentencia)) {
            $sentencia_prueba = preg_replace($limit_regex, 'limit 1', $request->sentencia);
        } else {
            $sentencia_prueba = $request->sentencia . ' limit 1';
        };
        $sentencia_prueba = preg_replace($variable_regex, '1 ', $sentencia_prueba);

        DB::select($sentencia_prueba);

        try {
            DB::select($sentencia_prueba);
        } catch (QueryException $exception) {
            return $exception;
        }
    }

    public function disponibles()
    {
        $roles = Auth::user()->roles;

        $roles->load('reportes.variables', 'reportes.modulo');

        return new Resource($roles);
    }

    public function getPlain($headers = [], $rows = [], $separator)
    {
        $plainContent = '';

        foreach ($headers as $key => $header) {
            $headers[$key] = strtoupper($header);
        }

        foreach ($rows as $key => $cell) {
            $rows[$key] = preg_replace('/[\r\n|\n|\r]+/', ' ', $cell);
        }

        $plainContent .= implode($separator, $headers);
        foreach ($rows as $key => $row) {
            $plainContent .= PHP_EOL . implode($separator, $row);
        }

        return $plainContent;
    }

}
