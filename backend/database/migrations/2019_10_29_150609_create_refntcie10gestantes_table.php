<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefntcie10gestantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refntcie10gestantes', function (Blueprint $table) {
            $table->string('codigo')->primary()->default('');
            $table->timestamps();
        });

        $sql = "INSERT INTO refntcie10gestantes(`codigo`) VALUES
                ('N643'),
                ('O000'),
                ('O001'),
                ('O002'),
                ('O008'),
                ('O009'),
                ('O021'),
                ('O030'),
                ('O031'),
                ('O032'),
                ('O033'),
                ('O034'),
                ('O035'),
                ('O036'),
                ('O037'),
                ('O038'),
                ('O039'),
                ('O040'),
                ('O041'),
                ('O042'),
                ('O043'),
                ('O044'),
                ('O045'),
                ('O046'),
                ('O047'),
                ('O048'),
                ('O049'),
                ('O050'),
                ('O051'),
                ('O052'),
                ('O053'),
                ('O054'),
                ('O055'),
                ('O056'),
                ('O057'),
                ('O058'),
                ('O059'),
                ('O060'),
                ('O061'),
                ('O062'),
                ('O063'),
                ('O064'),
                ('O065'),
                ('O066'),
                ('O067'),
                ('O068'),
                ('O069'),
                ('O070'),
                ('O071'),
                ('O072'),
                ('O073'),
                ('O074'),
                ('O075'),
                ('O076'),
                ('O077'),
                ('O078'),
                ('O079'),
                ('O080'),
                ('O081'),
                ('O082'),
                ('O083'),
                ('O084'),
                ('O085'),
                ('O086'),
                ('O087'),
                ('O088'),
                ('O089'),
                ('O100'),
                ('O101'),
                ('O102'),
                ('O103'),
                ('O104'),
                ('O109'),
                ('O13X'),
                ('O150'),
                ('O151'),
                ('O200'),
                ('O208'),
                ('O209'),
                ('O218'),
                ('O219'),
                ('O220'),
                ('O221'),
                ('O222'),
                ('O223'),
                ('O224'),
                ('O225'),
                ('O228'),
                ('O229'),
                ('O230'),
                ('O232'),
                ('O233'),
                ('O234'),
                ('O235'),
                ('O239'),
                ('O240'),
                ('O241'),
                ('O242'),
                ('O243'),
                ('O244'),
                ('O249'),
                ('O25X'),
                ('O260'),
                ('O261'),
                ('O262'),
                ('O263'),
                ('O266'),
                ('O267'),
                ('O268'),
                ('O269'),
                ('O290'),
                ('O291'),
                ('O292'),
                ('O293'),
                ('O294'),
                ('O295'),
                ('O296'),
                ('O298'),
                ('O299'),
                ('O300'),
                ('O301'),
                ('O302'),
                ('O308'),
                ('O309'),
                ('O311'),
                ('O312'),
                ('O318'),
                ('O325'),
                ('O367'),
                ('O420'),
                ('O421'),
                ('O422'),
                ('O460'),
                ('O468'),
                ('O469'),
                ('O470'),
                ('O471'),
                ('O479'),
                ('O48X'),
                ('O610'),
                ('O611'),
                ('O618'),
                ('O619'),
                ('O623'),
                ('O628'),
                ('O629'),
                ('O630'),
                ('O631'),
                ('O639'),
                ('O640'),
                ('O641'),
                ('O642'),
                ('O643'),
                ('O644'),
                ('O645'),
                ('O648'),
                ('O649'),
                ('O650'),
                ('O651'),
                ('O652'),
                ('O653'),
                ('O654'),
                ('O655'),
                ('O658'),
                ('O659'),
                ('O660'),
                ('O661'),
                ('O662'),
                ('O663'),
                ('O664'),
                ('O668'),
                ('O669'),
                ('O670'),
                ('O678'),
                ('O679'),
                ('O680'),
                ('O681'),
                ('O682'),
                ('O683'),
                ('O688'),
                ('O689'),
                ('O690'),
                ('O691'),
                ('O692'),
                ('O693'),
                ('O694'),
                ('O695'),
                ('O698'),
                ('O699'),
                ('O700'),
                ('O701'),
                ('O702'),
                ('O703'),
                ('O709'),
                ('O710'),
                ('O711'),
                ('O712'),
                ('O720'),
                ('O721'),
                ('O722'),
                ('O723'),
                ('O740'),
                ('O741'),
                ('O742'),
                ('O743'),
                ('O744'),
                ('O745'),
                ('O746'),
                ('O747'),
                ('O748'),
                ('O749'),
                ('O750'),
                ('O751'),
                ('O752'),
                ('O753'),
                ('O755'),
                ('O756'),
                ('O757'),
                ('O758'),
                ('O759'),
                ('O800'),
                ('O801'),
                ('O808'),
                ('O809'),
                ('O810'),
                ('O811'),
                ('O812'),
                ('O813'),
                ('O814'),
                ('O815'),
                ('O820'),
                ('O821'),
                ('O822'),
                ('O828'),
                ('O829'),
                ('O831'),
                ('O832'),
                ('O833'),
                ('O834'),
                ('O838'),
                ('O839'),
                ('O840'),
                ('O841'),
                ('O842'),
                ('O848'),
                ('O849'),
                ('O861'),
                ('O862'),
                ('O863'),
                ('O864'),
                 ('O904'),
                    ('O905'),
                    ('O910'),
                    ('O911'),
                    ('O912'),
                    ('O920'),
                    ('O921'),
                    ('O922'),
                    ('O96X'),
                    ('O980'),
                    ('O981'),
                    ('O982'),
                    ('O983'),
                    ('O984'),
                    ('O985'),
                    ('O986'),
                    ('O988'),
                    ('O989'),
                    ('O990'),
                    ('O991'),
                    ('O992'),
                    ('O993'),
                    ('O994'),
                    ('O995'),
                    ('O996'),
                    ('O997'),
                    ('O998'),
                    ('P014'),
                    ('P015'),
                    ('P017'),
                    ('P018'),
                    ('P019'),
                    ('P030'),
                    ('P031'),
                    ('P032'),
                    ('P033'),
                    ('P034'),
                    ('P035'),
                    ('P038'),
                    ('P039'),
                    ('P040'),
                    ('P200'),
                    ('P201'),
                    ('P590'),
                    ('P964'),
                    ('Z320'),
                    ('Z321'),
                    ('Z33X'),
                    ('Z340'),
                    ('Z348'),
                    ('Z349'),
                    ('Z350'),
                    ('Z351'),
                    ('Z352'),
                    ('Z353'),
                    ('Z354'),
                    ('Z357'),
                    ('Z358'),
                    ('Z359'),
                    ('Z379'),
                    ('Z390'),
                    ('Z392'),
                    ('Z640'),
                    ('Z875')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refntcie10gestantes');
    }
}