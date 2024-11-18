<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCmManglosas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_manglosas', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->enum('tipo', ['Facturacion','Tarifas','Soportes','Autorizacion','Cobertura','Pertinencia','Devoluciones', 'Respuestas a glosas o devoluciones','Otra']);
            $table->string('subtipo',2);
            $table->string('glosa',3);
            $table->text('descripcion');
            $table->longText('ayuda')->nullable();
            $table->timestamps();
        });

        $sql = "INSERT INTO cm_manglosas( tipo,subtipo,glosa,descripcion,ayuda) VALUES
                ('Facturacion','01','101','Estancia','Aplica cuando:
                1.   El cargo por estancia, en cualquier tipo de internación, que viene relacionado y/o justificado en los soportes de la factura, presenta diferencia con las cantidades que fueron facturadas.
                2.   El prestador de servicios de salud relaciona excedentes en estancia que  la  entidad  responsable  del  pago  no  tiene  que  asumir  de
                acuerdo con lo pactado por las partes.'),
                ('Facturacion','02','102','Consultas, interconsultas y visitas médicas','Aplica cuando:
                1.El  cargo  por  consulta,  interconsulta  y/o  visita  médica  que  viene relacionado y/o justificado en los soportes de la factura, presenta diferencias con las cantidades que fueron facturadas.
                2.En una factura se registra una interconsulta que originó la práctica de   una   intervención   o   procedimiento   que   realizó   el   mismo prestador.
                3.Se cobran consultas o visitas médicas que se encuentran incluidas en los honorarios médicos post-quirúrgicos.
                4. Se  cobran  consultas  y/o  controles  médicos  que  se  encuentran incluidas en los honorarios médicos del procedimiento del parto, según lo pactado entre las partes.
                5. El   prestador   de   servicios   de   salud   relaciona   excedentes   en consultas,   interconsultas   y   visitas   médicas   que   la   entidad responsable del pago no tiene que asumir.'),
                ('Facturacion','03','103','Honorarios médicos en procedimientos','Aplica cuando:
                1.   Los cargos por honorarios médicos en procedimientos quirúrgicos, de  hemodinamia,  radiología  u  otros  procedimientos  que  vienen relacionados   y/o   justificados   en   los   soportes   de   la   factura, presentan diferencias con las cantidades que fueron facturadas.
                2.   Los  cargos  por  honorarios  de  anestesia  que  vienen  relacionados y/o justificados en los soportes de la factura, presentan diferencias con las cantidades que fueron facturadas.
                3.   El   prestador   de   servicios   de   salud   relaciona   excedentes   en
                honorarios médicos en procedimientos que la entidad responsable del pago no tiene que asumir.'),
                ('Facturacion','04','104','Honorarios otros profesionales asistenciales','Aplica cuando:
                1.   Los cargos por honorarios de los profesionales en salud diferentes a los quirúrgicos y clínicos, que vienen relacionados y/o justificados en  los  soportes  de  la  factura,  presentan  diferencias  con  las cantidades facturadas.
                2.   El   prestador   de   servicios   de   salud   relaciona   excedentes   en honorarios  de  otros  profesionales  asistenciales  que  la  entidad
                responsable del pago no tiene que asumir.'),
                ('Facturacion','05','105','Derechos de sala','Aplica cuando:
                1.   Los  cargos  por  derechos  de  sala  que  vienen  relacionados  y/o justificados en los soportes de la factura, presentan diferencias con las cantidades que fueron facturadas.
                2.   El   prestador   de   servicios   de   salud   relaciona   excedentes   en
                derechos de sala que la entidad responsable del pago no tiene que asumir.'),
                ('Facturacion','06','106','Materiales','Aplica cuando:
                1.   Los cargos por materiales que vienen relacionados en el detalle de cargos y/o los soportes, presentan diferencias con las cantidades que fueron facturadas.
                2.   Se cobran insumos que ya se encuentran incluidos en el ítem de materiales por grupo o atención integral.
                3.   El   prestador   de   servicios   de   salud   relaciona   excedentes   en materiales  que  la  entidad  responsable  del  pago  no  tiene  que
                asumir.'),
                ('Facturacion','07','107','Medicamentos','Aplica cuando:
                1.   Los  cargos  por  medicamentos  que  vienen  relacionados  en  el detalle  de  cargos  y/o  los soportes,  presentan  diferencias  con  las cantidades que fueron facturadas.
                2.   El   prestador   de   servicios   de   salud   relaciona   excedentes   en medicamentos que la entidad responsable del pago no tiene que
                asumir.'),
                ('Facturacion','08','108','Ayudas diagnósticas','Aplica cuando:
                1.   Los   cargos   por   ayudas   diagnósticas   (incluye   procedimientos diagnósticos)  que  vienen  relacionados  y/o  justificados  en  los soportes de la factura presentan diferencias con las cantidades que fueron facturadas.
                2.   De  acuerdo  con  la  tarifa  pactada,  se  facturen  separadamente ayudas diagnósticas incluidas una en otra.'),
                ('Facturacion','09','109','Atención integral (caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico)','Aplica cuando:
                El  prestador  de  servicios  de  salud  registra  en  la  factura  cargos detallados que sobrepasan el valor pactado por caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico.'),
                ('Facturacion','10','110','Servicio o insumo incluido en caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico','Aplica cuando:
                El  prestador  de  servicios  de  salud  registra  en  la  factura  cargos
                adicionales  que  se  encuentran  incluidos  en  un  procedimiento  de atención integral de acuerdo con lo pactado'),
                ('Facturacion','11','111','Servicio o insumo incluido en estancia o derechos de sala','Aplica cuando:
                1.   Se  cobran  consultas,  interconsultas  y/o  visitas  médicas  que están incluidas en las estancias de acuerdo con lo pactado.
                2.   Se cobran insumos que ya se encuentran incluidos en el ítem de derechos de sala o materiales quirúrgicos.
                3.   El  prestador  de  servicios  de  salud  registra  en  la  factura
                actividades,  procedimientos  o  servicios  que  se  encuentran incluidos en la tarifa de la estancia de acuerdo con pactado.'),
                ('Facturacion','12','112','Factura excede topes autorizados','Aplica cuando:
                La   factura   presenta   excedentes   sobre   los   topes   establecidos previamente entre las partes, o excede el saldo disponible del contrato.
                No aplica en caso de atención inicial de urgencias o cuando se haya emitido autorización.'),
                ('Facturacion','13','113','Facturar por separado por tipo de recobro (Comité Técnico Científico, (CTC), accidente de trabajo o enfermedad profesional (ATEP), tutelas)','Aplica  cuando  el  prestador  en  una  misma  factura,  registra  servicios que   previamente   se   ha   pactado   que   se   facturarán   en   forma independiente,  como  facturas  de   recobro  por  reaseguro,  Comité técnico científico o tutelas.'),
                ('Facturacion','14','114','Error en suma de conceptos facturados','Aplica  cuando  la  sumatoria  de  los  ítems  registrados  en  la  factura presenta  diferencias  con  los  subtotales  o  el  total  de  los  servicios facturados, incluyendo los detalles de los soportes comparados con los
                subtotales de la factura.'),
                ('Facturacion','15','115','Datos insuficientes del usuario','Aplica  cuando  el  prestador  del  servicio  no  relaciona  en  la  factura suficiente  información  del  usuario  al  cual  se  le  prestó  el  servicio (nombres,  apellidos,  identificación,  plan  o  programa,  entre  otros) necesarios  para  el  registro  de  información  por  parte  de  la  entidad responsable  del  pago.  Aplica  sólo  cuando  la  factura  incluye  varios pacientes y se puede tramitar parcialmente la factura y se ha acordado
                contractualmente.'),
                ('Facturacion','16','116','Usuario o servicio corresponde a otro plan o responsable','Aplica cuando la factura incluye varios pacientes y es de trámite parcial
                1.   En la factura se relacionan usuarios o servicios de los cuales uno o varios corresponden a otra entidad responsable y/o a otro plan de beneficios.
                NOTA:  No  se  pueden  relacionar  en  una  misma  factura  usuarios  de diferentes planes de beneficio (POS y medicina prepagada o planes complementarios)  así  sea  de  la  misma  entidad  responsable  del
                pago, los cuales deben ser facturados en forma independiente.'),
                ('Facturacion','17','117','Usuario retirado o moroso','Aplica cuando en la factura se relacionan usuarios que en el momento de   la   prestación   del   servicio   no   está   cubierto   por   la   entidad responsable del pago.
                Aplica  sólo  cuando  la  factura  incluye  varios  pacientes  y  se  puede tramitar parcialmente.
                No  aplica  cuando  la  entidad  responsable  del  pago  ha  emitido  la autorización  de  servicios,  o  cuando  el  afiliado  acredite  el  derecho mediante la presentación del comprobante de descuento por parte del
                empleador.'),
                ('Facturacion','19','119','Error en descuento pactado','Aplica  cuando  hay  descuentos  otorgados,  que  fueron  aplicados  de
                manera diferente a lo pactado. Aplica sólo cuando se puede tramitar parcialmente la factura.'),
                ('Facturacion','20','120','Recibo de pago compartido','Aplica cuando
                1.   Los  recaudos  de  bonos,  periodos  de  carencia,  o  vales  que  debe efectuar  el  prestador  de  servicios,  no  se  realizan  o  quedan  mal liquidados.
                2.   Los recaudos de cuotas moderadoras, de recuperación o copagos, que efectuó el prestador de servicios, quedan mal liquidados.'),
                ('Facturacion','22','122','Prescripción dentro de los términos legales o pactados entre las partes','Aplica cuando
                El prestador presenta el cobro de un servicio en fecha posterior a la establecida en la normatividad vigente o incumpliendo los términos de los acuerdos contractuales.'),
                ('Facturacion','23','123','Procedimiento o actividad','Aplica cuando:
                1.   Los   cargos   por   procedimientos   o   actividades   que   vienen relacionados   y/o   justificados   en   los   soportes   de   la   factura presentan diferencias con las cantidades que fueron facturadas. De   acuerdo   con   la   tarifa   pactada,   se   facturen   separadamente procedimientos o actividades una en otra.'),
                ('Facturacion','24','124','Falta firma del prestador de servicios de salud','Aplica cuando
                1.   La factura no tiene la firma del prestador.'),
                ('Facturacion','25','125','Examen o actividad pertenece a detección temprana y protección especifica','Aplica cuando:
                Se   factura   una   actividad   de   detección   temprana   y/o   protección específica  en  una  factura  de  servicios  asistenciales  y  esta  actividad hace  parte  de  un  paquete  de  servicios  de  prevención  o  protección específica.'),
                ('Facturacion','26','126','Usuario o servicio corresponde a capitación','Aplica cuando se factura por evento un servicio prestado a un usuario capitado.  Aplica  sólo  cuando  la  factura  incluye  varios  pacientes  y  se puede tramitar parcialmente.'),
                ('Facturacion','27','127','Servicio o procedimiento incluido en otro','Aplica cuando se cobran servicios o procedimientos que se encuentran incluidos  en  otro  servicio  ya  cobrado  dentro  de  la  misma  u  otra factura.'),
                ('Facturacion','28','128','Orden cancelada al prestador de servicios de salud','Aplica cuando el prestador de servicios de salud factura un servicio ya cancelado  en  la  factura  o  en  otra  anterior  por  parte  de  la  entidad responsable  del  pago.  Aplica  sólo  cuando  la  factura  incluye  varios pacientes y se puede tramitar parcialmente'),
                ('Facturacion','51','151','Recobro en contrato de capitación por servicios prestados por otro prestador','Aplica cuando se realizan descuentos al valor a pagar por concepto de capitación, originados en los pagos de servicios incluidos en el contrato de capitación y que por motivo de atención de urgencias, remisión de la   IPS   contratista   o   imposibilidad   de   prestarlo,   el   servicio   es efectivamente prestado por otro prestador.'),
                ('Facturacion','52','152','Disminución en el número de personas incluidas en la capitación','Aplica  cuando  el  número  de  personas  incluidas  en  la  capitación  es disminuido porque una autoridad competente excluye a algunas de las personas de la base de datos de beneficiarios de subsidios.'),
                ('Facturacion','54','154','Incumplimiento en las metas de cobertura, resolutividad y oportunidad pactadas en el contrato por capitación','Aplica cuando el prestador incumple o no demuestra el cumplimiento en  las  metas  pactadas  en  cobertura,  oportunidad  y  resolutividad pactadas  en  el  contrato  por  capitación.  El  valor  de  la  glosa,  será  la proporción  que  se  haya  acordado,  de  acuerdo  con  los  criterios  de evaluación establecidos en el acuerdo de voluntades.'),
                ('Tarifas','01','201','Estancia','Aplica  cuando  el  cargo  por  estancia,  que  viene  relacionado  y/o justificado  en  los  soportes  de  la  factura,  presenta  diferencia  con  los
                valores que fueron pactados o establecidos normativamente, vigentes al momento de la prestación del servicio.'),
                ('Tarifas','02','202','Consultas, interconsultas y visitas médicas','Aplica  cuando  el  cargo  por  consulta,  interconsulta  y/o  visita  médica
                que  viene  relacionado  y/o  justificado  en  los  soportes  de  la  factura, presenta diferencias con los valores que fueron pactados.'),
                ('Tarifas','03','203','Honorarios médicos en procedimientos','Aplica cuando:
                1.   Los cargos por honorarios médicos en procedimientos quirúrgicos, de  hemodinamia,  radiología  u  otros  procedimientos  que  vienen relacionados   y/o   justificados   en   los   soportes   de   la   factura, presentan diferencias con los valores que fueron pactados.
                2.   Los  cargos  por  honorarios  de  anestesia  que  vienen  relacionados y/o justificados en los soportes de la factura, presentan diferencias con los  valores que  fueron  pactados, vigentes al  momento de  la
                prestación del servicio.'),
                ('Tarifas','04','204','Honorarios otros profesionales asistenciales','Aplica cuando los cargos por honorarios de los profesionales en salud diferentes  a  los  quirúrgicos  y  clínicos,  que  vienen  relacionados  y/o justificados  en  soportes  de  la  factura,  presentan  diferencias  con  los
                valores pactados.'),
                ('Tarifas','05','205','Derechos de sala','Aplica cuando los cargos por derechos de sala que vienen relacionados y/o justificados en los soportes de la factura, presentan diferencias con
                los valores que fueron pactados.'),
                ('Tarifas','06','206','Materiales','Aplica  cuando los cargos  por  materiales que  vienen  relacionados y/o justificados en los soportes de la factura, presentan diferencias con los valores pactados.'),
                ('Tarifas','07','207','Medicamentos','Aplica  cuando los  cargos  por  medicamentos  que  vienen relacionados y/o justificados en los soportes de la factura, presentan diferencias con los valores que fueron pactados.'),
                ('Tarifas','08','208','Ayudas diagnósticas','Aplica   cuando   los   cargos   por   ayudas   diagnósticas   que   vienen
                relacionados  y/o  justificados  en  los  soportes  de  la  factura  presentan diferencias con los valores pactados.'),
                ('Tarifas','09','209','Atención integral (caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico)','Aplica cuando el prestador de servicios de salud registra en la factura un mayor valor en el cobro del caso, conjunto integral de atenciones, paquete  o  grupo  relacionado  por  diagnóstico,  o  presenta  cargos detallados cuya sumatoria final resulta superior a la tarifa pactada.
                No  aplica  cuando  se  haya  pactado,  o  en  la  normatividad  vigente  se encuentre  establecido  el  cobro  adicional  de  servicios  que  exceden  el paquete.'),
                ('Tarifas','23','223','Procedimiento o actividad','Aplica cuando los cargos por procedimientos o actividades que vienen relacionados  y/o  justificados  en  los  soportes  de  la  factura  presentan
                diferencias con los valores pactados.'),
                ('Tarifas','29','229','Recargos no pactados','Aplica  cuando  en  la  factura  se  adicionan  cobros  de  recargos  no pactados  previamente  entre  la  entidad  responsable  del  pago  y  el
                prestador de servicios de salud.'),
                ('Soportes','01','301','Estancia','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian la estancia.'),
                ('Soportes','02','302','Consultas, interconsultas y visitas médicas','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian la consulta, interconsulta y/o visita médica.'),
                ('Soportes','03','303','Honorarios médicos en procedimientos','Aplica cuando:
                1.   Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian los honorarios médicos en procedimientos quirúrgicos, de hemodinamia, radiología u otros procedimientos que vienen relacionados y/o justificados en los soportes de la factura.
                2.   Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian los honorarios de anestesia que vienen relacionados y/o justificados
                en la factura.'),
                ('Soportes','04','304','Honorarios otros profesionales asistenciales','Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian los honorarios
                de los profesionales en salud, diferente a los quirúrgicos y clínicos, que vienen relacionados y/o justificados en la factura.'),
                ('Soportes','07','307','Medicamentos','Existe ausencia total o parcial, enmendaduras o ilegibilidad en la hoja de administración de medicamentos o en el comprobante de recibido de medicamentos por parte de los usuarios
                Existe inconsistencia en los contenidos de la factura o documente equivalente frente a relacionados en la hoja de administración de
                medicamentos o en el comprobante de recibido de medicamentos por parte de los usuarios'),
                ('Soportes','08','308','Ayudas diagnósticas','Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian la práctica de ayudas diagnósticas que vienen relacionadas y/o justificadas en los soportes de la factura.
                Incluye la ausencia de lectura del profesional correspondiente, cuando aplica.'),
                ('Soportes','09','309','Atención integral (caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico)','Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian las actividades adicionales al caso, conjunto integral de atenciones, paquete o grupo relacionado por diagnóstico.'),
                ('Soportes','20','320','Recibo de pago compartido','Aplica cuando se le esté cobrando el 100% de la factura.
                Aplica cuando no se anexan a la factura los soportes de los bonos, vouchers o vales por periodos de carencia, cuotas moderadoras, de
                recuperación, copagos, que recibió el prestador de servicios de salud.'),
                ('Soportes','30','330','Autorización de servicios adicional',''),
                ('Soportes','31','331','Bonos o vouchers sin firma del paciente, con enmendaduras o tachones','Aplica cuando se evidencia que los vouchers o los bonos presentan enmendaduras, tachones o no se encuentran debidamente firmados por el paciente o un acudiente en el caso de imposibilidad para firmar. Solo aplica en caso de cobro del 100% de la factura a la entidad responsable del pago.'),
                ('Soportes','32','332','Detalle de cargos','Aplica cuando:
                1.   Existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en los soportes de la factura que evidencian el detalle de cargos, de los valores facturados.
                2.   Se anexan detalle de cargos de usuarios diferentes al registrado en la factura.'),
                ('Soportes','33','333','Copia de historia clínica completa','Aplica cuando existe ausencia total o parcial, inconsistencia,
                enmendaduras o ilegibilidad en la copia de la historia clínica completa para el recobro. Aplica sólo en los eventos de alto costo.'),
                ('Soportes','35','335','Formato accidente de trabajo y enfermedad profesional ATEP','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en el formulario del IPAT (Informe del Presunto Accidente de Trabajo), en los casos que los eventos correspondan a un accidente de trabajo o enfermedad profesional ATEP.
                En caso de no contarse con el IPAT, este soporte se sustituye por el informe que haga el prestador de servicios de salud al asegurador del presunto origen laboral para que el asegurador solicite el formulario.
                Decreto 2463/2001 Art. 25.'),
                ('Soportes','36','336','Copia de factura o detalle de cargos del seguro obligatorio de accidentes de tránsito SOAT','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en las copias de las facturas enviadas a la compañía de seguros SOAT, al encargo fiduciario de FOSYGA con sus respectivos detalles, cumpliendo los topes.'),
                ('Soportes','37','337','Orden o fórmula médica','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en la orden y/o fórmula médica.'),
                ('Soportes','38','338','Hoja de traslado en ambulancia','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en la hoja de traslado.'),
                ('Soportes','39','339','Comprobante de recibido del usuario','Aplica cuando existe ausencia total o parcial, inconsistencia,
                enmendaduras o ilegibilidad en el comprobante de recibido del usuario como evidencia de haber recibido el servicio.'),
                ('Soportes','40','340','Registro de anestesia','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad en el registro de anestesia.'),
                ('Soportes','41','341','Descripción quirúrgica','Aplica cuando existe ausencia total o parcial, inconsistencia, enmendaduras o ilegibilidad de la copia de la descripción operatoria de
                cirugía.'),
                ('Soportes','42','342','Lista de precios','Aplica únicamente cuando no existe contrato entre el prestador y el pagador y el prestador debe adjuntar la parte del documento que relaciona los precios de compra del prestador para los medicamentos e insumos incluidos en la factura.
                No aplica en los casos en que existe contrato entre el pagador y el prestador ya que esta lista de precios debe ser un anexo del contrato, a menos que se requiera actualizar la información'),
                ('Soportes','43','343','Orden o autorización de servicios vencida',''),
                ('Autorizacion','01','401','Estancia','Aplica cuando:
                1.   El  número  de  horas  en  observación,  o  días  en  habitación,  que vienen relacionados en la factura presenta diferencia con los días autorizados.
                2.   El tipo de estancia prestado no corresponde al autorizado.'),
                ('Autorizacion','02','402','Consultas, interconsultas y visitas médicas','Aplica cuando la consulta, interconsulta y/o visita médica relacionada y/o justificada en los soportes de la factura presenta diferencias con lo autorizado.'),
                ('Autorizacion','06','406','Materiales','Aplica cuando los materiales que vienen relacionados y/o justificados
                en   los   soportes   de   la   factura,   presentan   diferencias   con   los autorizados.'),
                ('Autorizacion','08','408','Ayudas diagnósticas','Aplica cuando las ayudas diagnósticas relacionadas y/o justificados en los soportes de la factura presentan diferencias con lo autorizado.'),
                ('Autorizacion','23','423','Procedimiento o actividad','Aplica  cuando  el  procedimiento  o  actividad  prestada  relacionada  y/o justificada en los soportes de la factura, difiere de la autorizada.'),
                ('Autorizacion','30','430','Autorización de servicios adicionales','Aplica   cuando   existe   ausencia   total   o   parcial,   inconsistencia, enmendaduras   o   ilegibilidad   en   los   soportes   de   la   factura   que evidencian  la  autorización  de  algunos  servicios  no  incluidos  en  el evento  principal  del  plan  de  manejo  o  de  la  solicitud  formulada oportunamente por el prestador y no respondida en los términos de la presente resolución.
                No aplica cuando durante la realización de un procedimiento quirúrgico debidamente autorizado, el cirujano considera necesaria la realización de    un    procedimiento    adicional    derivado    de    los    hallazgos intraoperatorios,  siempre  y  cuando  la  conducta  asumida  justifique  la
                realización de dicho procedimiento adicional a la luz de la sana crítica de la auditoría médica.'),
                ('Autorizacion','38','438','Traslado en ambulancia','Aplica cuando:
                3.         El  traslado  en  ambulancia  no  cuenta  con  la  autorización pactada en el acuerdo de voluntades.
                4.         No aplica en caso de traslados de urgencias.'),
                ('Autorizacion','43','443','Orden o autorización de servicios vencida','Aplica cuando:
                1.   La orden o autorización de servicios que se anexa como soporte de la factura ha superado el límite de días de vigencia.
                2.   La orden o autorización de servicios que se anexa como soporte de
                la  factura  ha  sido  reemplazada  por  solicitud  del  prestador  de servicios de salud.'),
                ('Autorizacion','44','444','Médico que ordena no adscrito','Aplica  en  aquellos  casos  que  se  presten  servicios  ordenados  por  un
                profesional  que  no  hace  parte  del  cuerpo  médico  de  la  entidad responsable del pago.'),
                ('Cobertura','01','501','Estancia','Aplica  cuando  el  número  de  días  en  observación  o  habitación,  que vienen relacionados en la  factura  no están incluidos  en el respectivo
                plan  ó  hacen  parte  integral  de  un  servicio  y  se  están  cobrando adicionalmente.'),
                ('Cobertura','02','502','Consultas, interconsultas y visitas médicas','Aplica cuando la consulta, interconsulta y/o visita médica relacionadas no están incluidas en el respectivo plan ó hacen parte integral de un servicio y se están cobrando adicionalmente.'),
                ('Cobertura','06','506','Materiales','Aplica cuando los materiales que vienen relacionados y/o justificados en los soportes de la factura, no están incluidos en el respectivo plan ó hacen   parte    integral   de    un    servicio    y   se    están   cobrando
                adicionalmente.'),
                ('Cobertura','07','507','Medicamentos','Aplica cuando los medicamentos entregados o relacionados en la hoja de suministro de medicamentos y/o justificados en la factura no están incluidos en el respectivo plan ó hacen parte integral de un servicio y
                se están cobrando adicionalmente.'),
                ('Cobertura','08','508','Ayudas diagnósticas','Aplica cuando las ayudas diagnósticas relacionadas y/o justificados en los soportes de  la  factura  no están incluidas en el respectivo plan ó hacen   parte    integral   de    un    servicio    y   se    están   cobrando
                adicionalmente.'),
                ('Cobertura','23','523','Procedimiento o actividad','Aplica cuando en la factura se cobra un procedimiento o una actividad que no están incluidos en el respectivo plan ó hacen parte integral de
                un servicio y se están cobrando adicionalmente.'),
                ('Cobertura','27','527','Servicio o procedimiento incluido en otro','Aplica  cuando  se  factura  por  separado  un  procedimiento  incluido  en otro ya facturado.'),
                ('Cobertura','45','545','Servicio no pactado','Aplica cuando en la factura se cobra un servicio que no se encuentra establecido entre las partes.'),
                ('Cobertura','46','546','Cobertura sin agotar en la póliza Seguro obligatorio de accidentes de tránsito (SOAT)','Aplica cuando se facturan servicios a la entidad responsable del pago
                sin agotar los topes cubiertos por las pólizas de Seguro Obligatorio de accidentes de tránsito y el administrador fiduciario de FOSYGA.'),
                ('Pertinencia','01','601','Estancia','Aplica  cuando  el  cargo  por  estancia,  sea  ésta  en  observación  o, habitación, que viene relacionado en los soportes de la factura, no es
                pertinente o no tiene justificación médica para el cobro.'),
                ('Pertinencia','02','602','Consultas, interconsultas y visitas médicas','Aplica  cuando  el  cargo  por  consulta,  interconsulta  y/o  visita  médica que viene relacionado en los soportes de la factura, no es pertinente o
                no tiene justificación médica para el cobro.'),
                ('Pertinencia','03','603','Honorarios médicos en procedimientos','Aplica cuando:
                1.     Los    cargos    por    honorarios    médicos    en    procedimientos quirúrgicos,  de  hemodinamia, radiología  u  otros procedimientos que  vienen  relacionados  en  los  soportes  de  la  factura  no  son pertinentes o no tienen justificación médica para el cobro.
                2.     Los cargos por honorarios de anestesia que vienen relacionados en  los  soportes  de  la  factura  no  son  pertinentes  o  no  tienen
                justificación médica para el cobro.'),
                ('Pertinencia','04','604','Honorarios otros profesionales asistenciales','Aplica   cuando   los   cargos   por   honorarios   de   otros   profesionales asistenciales   diferentes   a   los   quirúrgicos   y   clínicos,   que   vienen
                relacionados  en  los  soportes  de  la  factura  no  son  pertinentes  o  no tienen justificación para el cobro.'),
                ('Pertinencia','05','605','Derechos de sala','Aplica cuando los cargos por derechos de sala que vienen relacionados
                en  los  soportes  de   la   factura   no   son  pertinentes  o  no   tienen justificación médica para el cobro.'),
                ('Pertinencia','06','606','Materiales','Aplica cuando los cargos por materiales que vienen relacionados en el
                detalle de cargos y/o los  soportes pactados no son  pertinentes o no tienen justificación médica para el cobro.'),
                ('Pertinencia','07','607','Medicamentos','Aplica  cuando los  cargos  por  medicamentos  que  vienen relacionados en el detalle de cargos y/o los soportes pactados no son pertinentes o
                no tienen justificación médica para el cobro.'),
                ('Pertinencia','08','608','Ayudas diagnósticas','Aplica   cuando   los   cargos   por   ayudas   diagnósticas   que   vienen
                relacionados  en  los  soportes  de  la  factura  no  son  pertinentes  o  no tienen justificación médica para el cobro.'),
                ('Pertinencia','23','623','Procedimiento o actividad','Aplica cuando los cargos por procedimientos o actividades que vienen
                relacionados  en  los  soportes  de  la  factura  no  son  pertinentes  o  no tienen justificación médica para el cobro.'),
                ('Pertinencia','53','653','Urgencia no pertinente','Aplica cuando los servicios prestados no obedecen a una atención de urgencia de acuerdo con la definición de la normatividad vigente.'),
                ('Devoluciones','16','816','Usuario o servicio corresponde a otro plan o responsable','Aplica cuando:
                1.     La  factura  corresponde  a  un  usuario  que  pertenece  a  otra entidad responsable del pago
                2.     La factura corresponde a un usuario o servicio que pertenece a otro plan de la misma entidad responsable del pago
                3.     La  factura  corresponde  a  un  servicio  que  debe  ser  pagado  por otra entidad responsable del pago
                Nota: Aplica sólo cuando la factura que incluye varios usuarios, no se puede tramitar parcialmente.'),
                ('Devoluciones','17','817','Usuario retirado o moroso','Aplica cuando la factura corresponde a un usuario que en el momento de   la   prestación   del   servicio   no   está   cubierto   por   la   entidad responsable del pago o se encuentra moroso en el pago.
                Nota: Aplica sólo cuando la factura que incluye varios usuarios, no se puede tramitar parcialmente.'),
                ('Devoluciones','21','821','Autorización principal no existe no corresponde al prestador de servicios de salud','Aplica   cuando   se   carece   de   autorización   principal   o   ésta   no corresponde al prestador de servicios.  Cuando la entidad responsable del pago no se haya pronunciado dentro de los términos definidos en la normatividad vigente, será suficiente soporte la copia de la solicitud enviada   a   la   entidad   responsable   del   pago,   o   a   la   dirección departamental o distrital de salud y en tal caso no aplicará esta causa
                de devolución.'),
                ('Devoluciones','34','834','Resumen de egreso o epicrisis, hoja de atención de urgencias u odontograma','Aplica cuando:
                1.     No  se  anexa  a  la  factura  de  internación  o  de  urgencias  con observación la epicrisis.
                2.     Para el caso de facturas de atención de urgencias, cuando no se anexa la hoja de atención de urgencias
                3.     Para el caso de facturas de atención odontológica, cuando no se anexa el odontograma.
                Nota: Aplica sólo cuando la factura que incluye varios usuarios, no se puede tramitar parcialmente.'),
                ('Devoluciones','44','844','Médico que ordena no adscrito','Aplica cuando el profesional que ordena el servicio no hace parte del
                cuerpo médico de la entidad responsable de pago. No aplica en caso de atención inicial de urgencias.'),
                ('Devoluciones','47','847','Faltan soportes de justificación para recobros (Comité Técnico Científico, (CTC), accidente de trabajo o enfermedad profesional (ATEP), tutelas )','Aplica cuando no se incluyen en la factura los soportes de justificación para  recobros  de  comité  técnico  científico,  tutelas  o  accidentes  de trabajo o enfermedad profesional de conformidad con la normatividad vigente.'),
                ('Devoluciones','48','848','Informe de atención inicial de urgencias','Aplica cuando la atención de urgencias no es informada a la entidad responsable del pago, en los términos definidos.
                No  aplica  en  los  casos  que  no  sea  posible  identificar  la  entidad responsable  de  pago  dentro  de  los  términos  establecidos,  ni  en aquellos  casos  en  los  que  se  formuló  solicitud  de  autorización  para prestación de servicios adicionales dentro de las 24 horas siguientes al inicio de la atención inicial de urgencias.
                Se  consideran  informadas  aquellas  atenciones  comunicadas  a  las direcciones  departamentales  y  distritales  de  salud  por  no  haberse establecido comunicación con la entidad responsable del pago, en los
                términos establecidos en la presente resolución.'),
                ('Devoluciones','49','849','Factura no cumple requisitos legales','Aplica  cuando la  factura  o  el  documento equivalente  a  la  factura  no cumplen con alguno de los requisitos legales.'),
                ('Devoluciones','50','850','Factura ya cancelada','Aplica cuando la factura corresponda a servicios ya cancelados por la entidad responsable del pago'),
                ('Respuestas a glosas o devoluciones','96','996','Glosa o devolución injustificada','Aplica cuando el prestador de servicios de salud informa a la entidad
                responsable  del  pago  que  la  glosa  o  devolución  es  injustificada  al 100%.'),
                ('Respuestas a glosas o devoluciones','97','997','No subsanada (Glosa o devolución totalmente aceptada)','Aplica cuando el prestador de servicios de salud informa a la entidad responsable  del  pago  que  la  glosa  o  devolución  ha  sido  aceptada  al 100%.'),
                ('Respuestas a glosas o devoluciones','98','998','Subsanada parcial (Glosa o devolución parcialmente aceptada)','Aplica cuando el prestador de servicios de salud informa a la entidad responsable  del  pago  que  la  glosa  o  devolución  ha  sido  aceptada parcialmente.'),
                ('Respuestas a glosas o devoluciones','99','999','Subsanada (Glosa o devolución no aceptada)','Aplica cuando el prestador de servicios de salud informa a la entidad responsable del pago que la glosa o devolución siendo justificada ha podido ser subsanada totalmente.')";
        DB::statement($sql);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_manglosas');
    }
}
