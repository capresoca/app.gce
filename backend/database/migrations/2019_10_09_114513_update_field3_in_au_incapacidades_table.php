<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateField3InAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE au_incapacidades as m,
                                (SELECT CONCAT(b.nomenclatura,d.codigo,a.consecutivo) AS consecutivo, a.id as id
                                  FROM au_incapacidades AS a
                                  LEFT JOIN au_tipincapacidades AS e ON e.id= a.au_tipincapacidades_id
                                  LEFT JOIN au_tipprestaciones AS b ON b.id = e.au_tipprestacion_id
                                  LEFT JOIN as_afiliados AS c ON c.id=a.as_afiliados_id
                                  LEFT JOIN gn_municipios AS d ON d.id = c.gn_municipio_id) as n
                            SET m.numeroDocumento = n.consecutivo
                            WHERE m.id = n.id");
    }
}
