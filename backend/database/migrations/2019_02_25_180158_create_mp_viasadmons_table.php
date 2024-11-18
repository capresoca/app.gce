<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpViasadmonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('mp_viasadmons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',3)->index();
            $table->string('descripcion');
        });

        $sql = "INSERT INTO mp_viasadmons(codigo,descripcion) VALUES 
                ('001','AURICULAR (OTICA)'),
                ('002','BUCAL'),
                ('003','CUTANEA'),
                ('004','DENTAL'),
                ('005','ENDOCERVICAL'),
                ('006','ENDOSINUSIAL'),
                ('007','ENDOTRAQUEAL'),
                ('008','EPIDURAL'),
                ('009','EXTRA-AMNIOTICO'),
                ('010','VIA A TRAVES DE HEMODIALISIS'),
                ('011','INTRA CORPUS CAVERNOSO'),
                ('012','INTRAAMNIOTICA'),
                ('013','INTRAARTERIAL'),
                ('014','INTRAARTICULAR'),
                ('015','INTRAUTERINA'),
                ('016','INTRACARDIACA'),
                ('017','INTRACAVERNOSA'),
                ('018','INTRACEREBRAL'),
                ('019','INTRACERVICAL'),
                ('020','INTRACISTERNAL (CEREBELOMEDULAR)'),
                ('021','INTRACORNEAL'),
                ('022','INTRACORONARIA'),
                ('023','INTRADERMICA'),
                ('024','INTRADISCAL'),
                ('025','INTRAHEPATICA'),
                ('026','USO INTRALESIONAL'),
                ('027','USO INTRALINFATICO'),
                ('028','INTRAMEDULAR'),
                ('029','INTRAMENINGEA'),
                ('030','INTRAMUSCULAR'),
                ('031','INTRAOCULAR'),
                ('032','INTRAPERICARDIAL'),
                ('033','INTRAPERITONEAL'),
                ('034','INTRAPLEURAL'),
                ('035','INTRASINOVIAL'),
                ('036','INTRATECAL'),
                ('037','INTRATORAXICA'),
                ('038','INTRATRAQUEAL'),
                ('039','INTRATUMORAL'),
                ('040','BOLO INTRAVENOSO'),
                ('041','GOTEO INTRAVENOSO'),
                ('042','INTRAVENOSA'),
                ('043','INTRAVESICAL'),
                ('044','IONTOFORESIS'),
                ('045','NASAL'),
                ('046','TECNICA DE VENDAJE OCLUSIVO'),
                ('047','OFTALMICA'),
                ('048','ORAL'),
                ('049','OROFARINGEA'),
                ('050','OTRA'),
                ('051','PARENTERAL*'),
                ('052','PERIARTICULAR'),
                ('053','PERINEURAL'),
                ('054','RECTAL'),
                ('055','INHALATORIA'),
                ('056','RETROBULBAL'),
                ('057','SUBCONJUNTIVAL'),
                ('058','SUBCUTANEA'),
                ('060','SUBLINGUAL'),
                ('061','TOPICA'),
                ('062','TRANSDERMICA'),
                ('063','TRANSMAMARIA'),
                ('064','TRANSPLACENTARIA'),
                ('066','URETRAL'),
                ('067','VAGINAL'),
                ('068','CONJUNTIVAL'),
                ('069','ELECTRO-OSMOSIS'),
                ('070','ENTERAL'),
                ('071','GASTROENTERAL'),
                ('072','INTRAGINGIVAL'),
                ('075','IN VITRO'),
                ('076','INFILTRACION'),
                ('077','INTERSTICIAL'),
                ('078','INTRABDOMINAL'),
                ('079','INTRABILIAR'),
                ('080','INTRABRONQUIAL'),
                ('081','INTRABURSAL'),
                ('082','INTRACARTILAGINOSO'),
                ('083','INTRACAUDAL'),
                ('084','INTRACAVITARIA'),
                ('085','INTRACORONARIO, DENTAL'),
                ('086','INTRADUCTAL'),
                ('087','INTRADUODENAL'),
                ('088','INTRADURAL'),
                ('089','INTRAEPIDERMAL'),
                ('090','INTRAESOFAGICA'),
                ('091','INTRAGASTRICA'),
                ('092','INTRAILEAL'),
                ('093','INTRAOVARICA'),
                ('094','INTRAPROSTATICA'),
                ('095','INTRAPULMONAR'),
                ('096','INTRASINUSAL (SENOSPARANASALES)'),
                ('097','INTRAESTERNAL'),
                ('098','INTRATENDINOSA'),
                ('099','INTRATESTICULAR'),
                ('100','INTRATUBULAR'),
                ('101','INTRATIMPANICA'),
                ('102','INTRAVASCULAR'),
                ('103','INTRAVENTRICULAR'),
                ('104','INTRAVITREA'),
                ('105','IRRIGACION'),
                ('106','LARINGEO'),
                ('107','LARINGOFARINGEAL'),
                ('108','SONDA NASOGASTRICA'),
                ('110','USO OROMUCOSA'),
                ('111','PERCUTANEA'),
                ('112','PERIDURAL'),
                ('113','PERIODONTAL'),
                ('114','TEJIDO BLANDO'),
                ('115','SUBARACNOIDEA'),
                ('116','SUBMUCOSA'),
                ('117','TRANSMUCOSA'),
                ('118','TRANSTRAQUEAL'),
                ('119','TRANSTIMPANICA'),
                ('120','URETERAL'),
                ('500','INTRADETRUSOR'),
                ('501','USO EPILESIONAL'),
                ('502','INHALATORIA NASAL'),
                ('503','INHALATORIA BUCAL')";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_viasadmons');
    }
}
