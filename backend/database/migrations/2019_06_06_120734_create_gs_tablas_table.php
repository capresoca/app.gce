<?php

use App\GsCampo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGsTablasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gs_campos');
        Schema::dropIfExists('gs_tablas');
        Schema::create('gs_tablas', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('tabla', 100);
            $table->longText('descripcion')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->timestamps();
        });

        $tablas = DB::table('information_schema.tables')
            ->select(
                DB::raw('TABLE_NAME AS tabla'),
                DB::raw('TABLE_COMMENT AS descripcion'),
                DB::raw('CREATE_TIME AS fecha_creacion'),
                DB::raw('UPDATE_TIME AS fecha_actualizacion')
            )
            ->where('table_schema',env('DB_DATABASE'))
            ->orderBy('TABLE_NAME')
            ->get();

        $tablasInsertar = $tablas->map(function ($tabla){
            return [
                'tabla' => $tabla->tabla,
                'descripcion' => $tabla->descripcion
            ];
        });

        \App\GsTabla::insert($tablasInsertar->toArray());

        Schema::create('gs_campos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('gs_tabla_id')->nullable();
            $table->string('campo', 100);
            $table->string('llave', 5)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->string('tipo_full', 500)->nullable();
            $table->string('permite_vacio', 5)->nullable();
            $table->string('valor_defecto', 50)->nullable();
            $table->longText('descripcion')->nullable();
            $table->timestamps();
            $table->foreign('gs_tabla_id')->references('id')->on('gs_tablas')->onDelete('restrict')->onUpdate('no action');
        });

        $columnas = DB::table('information_schema.columns')
            ->select(
                DB::raw(('(SELECT id FROM gs_tablas WHERE gs_tablas.tabla = TABLE_NAME) AS gs_tabla_id')),
                DB::raw('COLUMN_NAME AS campo'),
                DB::raw('COLUMN_KEY AS llave'),
                DB::raw('DATA_TYPE AS tipo'),
                DB::raw('COLUMN_TYPE AS tipo_full'),
                DB::raw('IS_NULLABLE AS permite_vacio'),
                DB::raw('COLUMN_DEFAULT AS valor_defecto'),
                DB::raw('COLUMN_COMMENT AS descripcion')
            )
            ->where('table_schema',env('DB_DATABASE'))
            ->orderBy('table_name')
            ->orderBy('ordinal_position')
            ->get();


        $columnasInsertar = $columnas->map(function ($columna){
            return [
                'gs_tabla_id' => $columna->gs_tabla_id,
                'campo' => $columna->campo,
                'llave' => $columna->llave,
                'tipo' => $columna->tipo,
                'tipo_full' => $columna->tipo_full,
                'permite_vacio' => $columna->permite_vacio,
                'valor_defecto' => $columna->valor_defecto,
                'descripcion' => $columna->descripcion
            ];
        });


        GsCampo::insert($columnasInsertar->toArray());

        GsCampo::whereNull('gs_tabla_id')->delete();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gs_campos');
        Schema::dropIfExists('gs_tablas');
    }
}
