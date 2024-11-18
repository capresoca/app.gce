<template>  
  <div>

    <v-dialog v-model="dialog" width="1000px">
      <v-card>
        <v-card-title class="grey lighten-3 elevation-0">
          Informe Aportes por Planilla
          <v-spacer></v-spacer>
          <v-btn icon @click="closeDetalle">
            <v-icon title="cerrar">highlight_off</v-icon>
          </v-btn>
        </v-card-title>
        <v-container fluid grid-list-md>
          <v-data-table
            :headers="headersDetallePlanilla"
            :items="dataDetallesPlanillas.detalle"
            :loading="tableLoading"
            :total-items="dataDetallesPlanillas.length"
            hide-actions
            class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{props.item.planilla}}</td>
              <td>{{props.item.identificacion_aportante}}</td>
              <td>{{props.item.razon_social_aportante}}</td>
              <td>{{props.item.identificacion_cotizante}}</td>
              <td>{{props.item.nombre_cotizante}}</td>
              <td>{{props.item.tipo_planilla}}</td>
              <td>{{props.item.correcciones}}</td>
              <td>{{props.item.planilla_corregida}}</td>
              <td>{{props.item.tipo_cotizante}}</td>
              <td>{{props.item.subtipo_cotizante}}</td>
              <td>{{props.item.periodo}}</td>
              <td>{{props.item.fecha_pago}}</td>
              <td>{{props.item.dias}}</td>
              <td>{{props.item.ing}}</td>
              <td>{{props.item.ret}}</td>
              <td>{{props.item.tde}}</td>
              <td>{{props.item.tae}}</td>
              <td>{{props.item.vsp}}</td>
              <td>{{props.item.vst}}</td>
              <td>{{props.item.sln}}</td>
              <td>{{props.item.ige}}</td>
              <td>{{props.item.lma}}</td>
              <td>{{props.item.vac}}</td>
              <td>{{props.item.ibc}}</td>
              <td>{{props.item.tarifa}}</td>
              <td>{{props.item.cotizacion_obligatoria}}</td>
              <td>{{props.item.estado_conciliacion}}</td>
              <td>{{props.item.estado_compensacion}}</td>
              <td>{{props.item.fecha_proceso_giro_compensacion}}</td>
              <td>{{props.item.total_dias_compensados}}</td>
              <td>{{props.item.upc_reconocida}}</td>
              <td>{{props.item.provision_incapacidades}}</td>
              <td>{{props.item.valor_upc_promocion_prevencion}}</td>
              <td>{{props.item.serial_bdua}}</td>
              <td>{{props.item.serial_ha}}</td>
            </template>
            <template slot="no-data">
              <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
              </v-alert>
              <v-alert v-else :value="true" color="info" icon="info">
                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
              </v-alert>
            </template>
          </v-data-table>
        </v-container>
        <!-- <v-card-actions class="grey lighten-4">
          <v-spacer></v-spacer>
          <v-btn @click="closeDetalle">Cerrar</v-btn>
        </v-card-actions> -->
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialog2" width="500px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Rechazo</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-autocomplete
                  label="Causal Rechazo"
                  v-model="arrayRechazo.causal_rechazo"
                  :items="complementosRechazos.rechazos"
                  item-value="consecutivo_rechazo"
                  item-text="descripcion"
                  name="causal rechazo"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('causal rechazo')"
                  :filter="filterRechazo"> 

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.descripcion"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveCausal" :loading="loadingSubmitRechazo">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card ref="loaderRef">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion' : 'Auditoría Prestaciones Económicas' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text class="no-padding">
      <!-- start -->
        <v-container fluid grid-list-xl>
          <v-layout>
            <v-flex>
              <div class="text-xs-center">
                <h4>{{parametros.afiliado.afiliado}}</h4>
                <h4>{{parametros.afiliado.identificacion}}</h4>
              </div>
            </v-flex>
          </v-layout>
          <v-data-table
            :headers="headersTipoSoporte"
            :items="dataSoporte.detalle"
            :loading="tableLoading"
            :total-items="dataSoporte.length"
            hide-actions
            class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.nombre_soporte }}</td>
              <td>
                  <!-- {{ props.item.urlsoporte}}   -->
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="descargar(props.item.urlsoporte)"
                  >
                    <v-icon color="accent">image_search</v-icon>
                  </v-btn>
              </td>
            </template>
            <template slot="no-data">
              <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
              </v-alert>
              <v-alert v-else :value="true" color="info" icon="info">
                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
              </v-alert>
            </template>
          </v-data-table>

          <v-layout row wrap>
            <v-flex xs12 sm6 align="center"> 
              <v-form data-vv-scope="formRadicar">
              <v-toolbar dense short>
                Consulta de Salud
              </v-toolbar>
              <v-card>
                <v-card-text>
                  <v-layout row wrap>
                      <v-flex xs12>
                        <v-autocomplete
                          label="Prestador atiende"
                          v-model="incapacidad.prestador"
                          :items="complementosIncapacidades.entidades"
                          item-value="id"
                          item-text="nombre"
                          name="prestador atiende"
                          required
                          v-validate="'required'"
                          :error-messages="errors.collect('prestador atiende')"
                          :filter="filterPrestador"> 

                          <template slot="item" slot-scope="data">
                            <template>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item.nombre"/>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>

                        <v-autocomplete
                          label="Registro Profesional"
                          v-model="incapacidad.registro_profesional"
                          :items="complementosIncapacidades.medicosolicitante"
                          item-value="id"
                          item-text="codigo"
                          name="registro profesional"
                          required
                          v-validate="'required'"
                          :error-messages="errors.collect('registro profesional')"
                          :filter="filterRegistro"> 

                          <template slot="item" slot-scope="data">
                            <template>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item.codigo"/>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                        <v-text-field v-model="incapacidad.nombre_profesional"
                                      label="Nombre Profesional" key="nombre profesional"
                                      name="nombre profesional"
                                      v-validate="'max:50'"
                                      :error-messages="errors.collect('nombre profesional')" readonly></v-text-field>
                        <v-text-field v-model="incapacidad.cargo" v-show="false"
                                      label="Cargo o Actividad" key="cargo"
                                      name="cargo"
                                      v-validate="'max:50'"
                                      :error-messages="errors.collect('cargo')" ></v-text-field>
                        <v-autocomplete
                          label="Diagnóstico Principal"
                          v-model="incapacidad.diagnostico_principal"
                          :items="complementosIncapacidades.cie10"
                          item-value="id"
                          item-text="descripcion"
                          name="diagnóstico principal"
                          required
                          v-validate="'required'"
                          :error-messages="errors.collect('diagnóstico principal')"
                          :filter="filterDiagnostico"> 

                          <template slot="item" slot-scope="data">
                            <template>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item.descripcion"/>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
              </v-form>
            </v-flex>
            <v-flex xs12 sm6> 
              <v-form data-vv-scope="formRadicar">
              <v-toolbar dense short>
                Trasncripción Incapacidad
              </v-toolbar>
              <v-card>
                <v-card-text>
                  <v-layout row wrap>
                      <v-flex xs12>
                        <v-text-field v-model="incapacidad.tipoLicencia"
                                      label="Tipo Incapacidad" readonly></v-text-field>
                        <v-text-field v-model="incapacidad.fecha_incio_transcripcion"
                                      label="Fecha Inicio" readonly></v-text-field>
                        <v-menu
                          ref="menuDateFechaFinTranscripcion"
                          :close-on-content-click="false"
                          v-model="menuDateFechaFinTranscripcion"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                        >
                          <v-text-field
                            ref="prueba"
                            slot="activator"
                            v-model="computedDateFormattedFechaFinTranscrip"
                            label="Fecha Fin"
                            prepend-icon="event"
                            readonly
                            name="fecha"
                            v-validate="'required|date_format:YYYY/MM/DD'"
                            :error-messages="errors.collect('fecha')"
                          ></v-text-field>
                          <v-date-picker
                            v-model="incapacidad.fecha_fin"
                            @input="menuDateFechaFinTranscripcion = false"
                            @change="() => {
                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                            }"
                            locale='es'
                            no-title
                          ></v-date-picker>
                        </v-menu>
                        <v-menu
                          ref="menuDateFechaExpedicionTranscripcion"
                          :close-on-content-click="false"
                          v-model="menuDateFechaExpedicionTranscripcion"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                        >
                          <v-text-field
                            ref="prueba"
                            slot="activator"
                            v-model="computedDateFormattedFechaExpedicion"
                            label="Fecha Expedición"
                            prepend-icon="event"
                            readonly
                            name="fecha"
                            v-validate="'required|date_format:YYYY/MM/DD'"
                            :error-messages="errors.collect('fecha')"
                          ></v-text-field>
                          <v-date-picker
                            v-model="incapacidad.fecha_expedicion"
                            @input="menuDateFechaExpedicionTranscripcion = false"
                            @change="() => {
                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                            }"
                            locale='es'
                            no-title
                          ></v-date-picker>
                        </v-menu> 
                        <v-text-field v-model="incapacidad.dias"
                                      label="Número días incapacidad" readonly></v-text-field>
                        <v-text-field v-model="incapacidad.fecha_inicio_liquida"
                                      label="Fecha Inicio Liquida" readonly></v-text-field>
                        <v-text-field v-model="incapacidad.fecha_fin_liquida"
                                      label="Fecha Fin Liquida" readonly></v-text-field>
                        <v-text-field label="Días Liquida" readonly></v-text-field>

                      </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
              </v-form>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card flat >
          <v-container fluid grid-list-xl>
            <v-card>
              <v-layout>
                <v-flex>
                  <div class="text-xs-center">
                    <h4>Aportante</h4>
                    <h4>{{parametros.afiliado.aportante}}</h4>
                    <h4>{{parametros.afiliado.identificacion_aportante}}</h4>
                  </div>
                </v-flex>
              </v-layout>
              <v-card-text>
                <v-toolbar dense short>
                  Relaciones Laborales
                </v-toolbar>
                <v-data-table
                  :headers="headersRelacion"
                  :items="dataRelaciones.detalle"
                  :loading="tableLoading"
                  :total-items="dataRelaciones.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <td>{{ props.item.codigo}} - {{ props.item.descripcion}}</td>
                    <td>{{ props.item.fecha_vinculacion}}</td>
                    <td>{{ props.item.fecha_fin_vinculacion}}</td>
                    <td>{{ props.item.ibc | formatoNumero}}</td>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span v-for="ale in alerta" :key="ale.id">
                  <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                    <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                  </v-alert>
                </span>
              </v-card-text>

              <v-card-text>
                <v-toolbar dense short>
                  Planillas
                  <v-spacer></v-spacer>
                  <v-btn icon @click="detalles ()">
                    <v-icon title="mostrar detalles" @click="dialog=true">article</v-icon>
                  </v-btn>
                </v-toolbar>
                <v-data-table
                  :headers="headersPlanilla"
                  :items="dataPlanillas.detalle"
                  :loading="tableLoading"
                  :total-items="dataPlanillas.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <tr>
                      <td>{{props.item.consecutivo_recaudo_encabezado}}</td>
                      <td>{{props.item.tipo_cotizante}}</td>
                      <td>{{props.item.subtipo_cotizante}}</td>
                      <td>{{props.item.fecha_pago}}</td>
                      <td>{{props.item.periodo_pago}}</td>
                      <td>{{props.item.dias_cotizados}}</td>
                      <td>{{props.item.ingreso_base_cotizacion | formatoNumero}}</td>
                      <td>{{props.item.ibc_compensado | formatoNumero}}</td>
                      <td>{{props.item.sw_ing}}</td>
                      <td>{{props.item.sw_ret}}</td>
                      <td>{{props.item.sw_sln}}</td>
                      <td>{{props.item.sw_vsp}}</td>
                      <td>{{props.item.sw_vst}}</td>
                      <td>{{props.item.compensado}}</td>
                    </tr>
                    <tr v-if="props.index + 1 == lengthPlanilla">
                      <td colspan="5">Total:</td>
                      <td>{{totalDias}}</td>
                      <td colspan="8">{{totalIbc | formatoNumero}}</td>
                    </tr>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span v-for="ale in alerta" :key="ale.id">
                  <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                    <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                  </v-alert>
                </span>
              </v-card-text>

              <v-card-text>
                <v-toolbar dense short>
                  Cartera Sin Sanear
                </v-toolbar>
                <v-data-table
                  :headers="headersCartera"
                  :items="dataCartera.detalle"
                  :loading="tableLoading"
                  :total-items="dataCartera.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <td>{{props.item.fecha_plazo}}</td>
                    <td>{{props.item.tipo_cotizante_esperado}}</td>
                    <td>{{props.item.periodo_pago}}</td>
                    <td>{{props.item.dias_esperados}}</td>
                    <td>{{props.item.ibc_esperado | formatoNumero}}</td>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span v-for="ale in alerta" :key="ale.id">
                  <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                    <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                  </v-alert>
                </span>
              </v-card-text>
            </v-card>

            <v-card>
              <v-card-text>
                <v-toolbar dense short>
                  Incapacidades Previas
                </v-toolbar>
                <v-data-table
                  :headers="headersIncapacidadPrevia"
                  :items="dataIncPrevias.detalle"
                  :loading="tableLoading"
                  :total-items="dataIncPrevias.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <tr id="tderr" style="background-color: #6ccd29;" v-if="parametros.afiliado.solicitud == props.item.consecutivo_licencia">
                      <td>
                        <v-flex>
                          <v-layout column justify-space-between>
                            <v-checkbox
                              :id="`${props.item.consecutivo_licencia}`"
                              @click.native="agregarIncapacidadesPrevias(props.item)"
                              style="margin: 20% 0 0 20%;"
                              disabled
                            ></v-checkbox>
                          </v-layout>
                        </v-flex>
                      </td>
                      <td>{{props.item.consecutivo_licencia}}</td>
                      <td>{{props.item.dx}}</td>
                      <td>{{props.item.tipo_incapacidad | tipoincapacidad(tipoIncapacidad)}}</td>
                      <td>{{props.item.numero_dias_incapacidad}}</td>
                      <td>
                        <span v-if="props.item.dias_acumulados">{{props.item.dias_acumulados}}</span>
                        <span v-else>0</span>
                      </td>
                      <td>{{props.item.fecha_inicio}}</td>
                      <td>{{props.item.fecha_fin}}</td>
                      <td>{{props.item.estado_licencia | estado}}</td>
                    </tr>
                    <tr v-else>
                      <td>
                        <v-flex>
                          <v-layout column justify-space-between>
                            <v-checkbox
                              :id="`${props.item.consecutivo_licencia}`"
                              @click.native="agregarIncapacidadesPrevias(props.item)"
                              style="margin: 20% 0 0 20%;"
                            ></v-checkbox>
                          </v-layout>
                        </v-flex>
                      </td>
                      <td>{{props.item.consecutivo_licencia}}</td>
                      <td>{{props.item.dx}}</td>
                      <td>{{props.item.tipo_incapacidad | tipoincapacidad(tipoIncapacidad)}}</td>
                      <td>{{props.item.numero_dias_incapacidad}}</td>
                      <td>
                        <span v-if="props.item.dias_acumulados">{{props.item.dias_acumulados}}</span>
                        <span v-else>0</span>
                      </td>
                      <td>{{props.item.fecha_inicio}}</td>
                      <td>{{props.item.fecha_fin}}</td>
                      <td>{{props.item.estado_licencia | estado}}</td>
                    </tr>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span v-for="ale in alerta" :key="ale.id">
                  <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                    <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                  </v-alert>
                </span>
              </v-card-text>

            
              <v-card-text>
                <v-toolbar dense short>
                  Liquidación
                </v-toolbar>
                <v-data-table
                  :headers="headersLiquidacion"
                  :items="dataLiquidacion.detalle"
                  :loading="tableLoading"
                  :total-items="dataLiquidacion.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <tr>
                      <td>{{props.item.mes}}</td>
                      <td>
                        <!-- <v-text-field v-model="diasLiquidados"
                                      label="Días liquidados" readonly></v-text-field> -->
                        {{props.item.diasLiquidados}}         
                      </td>
                      <td>
                        <!-- <v-text-field v-model="diasReconocer"
                                      label="Días reconocer" readonly></v-text-field> -->
                        {{props.item.diasReconocer}}
                      </td>
                      <td>
                        <!-- <v-text-field v-model="valorReconocer"
                                      label="Valor reconocer" readonly></v-text-field> -->
                        {{props.item.valorReconocer | formatoNumero}}
                      </td>
                    </tr>
                    <tr v-if="props.index + 1 == lengthLiquidacion">
                      <td style="text-align: right;">Total:</td>
                      <td>{{totalDiasLiquidados}}</td>
                      <td>{{totalDiasReconocer}}</td>
                    </tr>
                    <tr v-if="props.index + 1 == lengthLiquidacion" style="border: 0;">
                      <td style="text-align:center;" colspan="4">
                        <v-btn @click="liquidar(totalDiasAcumulados)" color="success" :loading="loadingSubmit">Liquidar</v-btn>
                      </td>
                    </tr>
                    <tr v-if="props.index + 1 == lengthLiquidacion" style="border: 0;">
                      <td colspan="2" width="50%"></td>
                      <td colspan="2" width="50%" style="text-align:center;">
                        <v-card style="margin-top: 20px;margin-bottom: 20px;">
                          <v-card-text>
                            <v-layout row wrap>
                              <v-flex xs12>
                                <v-text-field v-model="incapacidad.tipo_cotizante"
                                              label="Tipo cotizante" readonly></v-text-field>
                                <v-layout row wrap>
                                  <v-flex xs6>
                                    <v-select
                                      item-text="nombre"
                                      item-value="value"
                                      label="Tipo IBC"
                                      :items="valores"
                                      name="ibc"
                                      v-validate="'required'"
                                      :error-messages="errors.collect('ibc')"
                                      v-model="valor"
                                      @change="calcularIbc(valor)"
                                    ></v-select>
                                  </v-flex>
                                  <v-flex xs6>
                                     <v-text-field v-model="ibcLabel" readonly></v-text-field>
                                  </v-flex>
                                </v-layout>
                                <v-text-field v-model="totalLiquidacion"
                                              label="Ibc Diario" readonly></v-text-field>
                                <v-text-field v-model="totalLiquidacion"
                                              label="Total liquidación" readonly></v-text-field>
                                <v-text-field v-model="diasAcumulados"
                                              label="Días acumulados" readonly></v-text-field>
                              </v-flex>
                            </v-layout>
                          </v-card-text>
                        </v-card>
                      </td>
                    </tr>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span v-for="ale in alerta" :key="ale.id">
                  <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                    <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                  </v-alert>
                </span>
              </v-card-text>

              <v-card-text>
                <v-toolbar dense short>
                  Rechazo
                </v-toolbar>
                <v-layout row wrap>
                  <v-flex xs12>
                    <v-col cols="12" md="6">
                      <v-textarea
                        v-model="incapacidad.concepto_medico"
                        name="input-7-4"
                        label="Concepto Médico"
                        rows="3"
                      ></v-textarea>
                    </v-col>
                  </v-flex>
                </v-layout>
                <v-data-table
                  :headers="headersRechazo"
                  :items="dataRechazo.rechazo"
                  :loading="tableLoading"
                  :total-items="dataRechazo.length"
                  hide-actions
                  class="elevation-1">
                  <template slot="items" slot-scope="props">
                    <td>{{props.item.causal_rechazo | filterRechazos(causalesRechazo)}}</td>
                    <td><!-- vacio por diseño --></td>
                    <!-- <td style="text-align: center;"> -->
                    <td>
                      <v-speed-dial
                        v-model="props.item.show"
                        direction="left"
                        open-on-hover
                        transition="slide-x-transition"
                      >
                        <v-btn
                          slot="activator"
                          v-model="props.item.show"
                          color="blue darken-2"
                          dark
                          fab
                          small
                        >
                          <v-icon>chevron_left</v-icon>
                          <v-icon>close</v-icon>
                        </v-btn>
                        <v-tooltip top>
                          <v-btn
                            fab
                            dark
                            small
                            color="white"
                            slot="activator"
                            @click="eliminarCausal(props.index)"
                          >
                            <v-icon color="accent">delete</v-icon>
                          </v-btn>
                          <span>Eliminar</span>
                        </v-tooltip>
                        <v-tooltip top>
                          <v-btn
                            fab
                            dark
                            small
                            color="white"
                            slot="activator"
                            @click="editarCausal(props.item, props.index)"
                          >
                            <v-icon color="accent">mode_edit</v-icon>
                          </v-btn>
                          <span>Editar</span>
                        </v-tooltip>
                      </v-speed-dial>
                    </td>
                  </template>
                  <template slot="no-data">
                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                      Agregue las causales de rechazo. <v-icon>sentiment_very_dissatisfied</v-icon>
                    </v-alert>
                    <v-alert v-else :value="true" color="info" icon="info">
                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                    </v-alert>
                  </template>
                </v-data-table>
                <span style="display:table; margin:0 auto;">
                  <v-btn @click="dialog2 = true" title="Agregar causal de rechazo">
                    <v-icon>add</v-icon> Agregar
                  </v-btn>
                </span>
              </v-card-text>        
            </v-card>
          </v-container>
        </v-card>
      <!-- end --> 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Anterior</v-btn>
            <v-btn color="primary" @click.prevent="aprobarORechazar('aprobar')" :loading="loadingSubmitAprobacion" >Aprobar</v-btn>
            <v-btn color="primary" @click.prevent="aprobarORechazar('rechazar')" :loading="loadingSubmitRechazo" >Rechazar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  // import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import moment from 'moment'
  export default {
    name: 'GestionAuditPrestacionEconomica',
    components: {
      InputFile: () => import('@/components/general/InputFile')
    },
    props: ['parametros'],
    data () {
      return {
        i: null, // para editar un elemento en las tabla de rechazo
        totalDias: '',
        totalIbc: '',
        totalDiasLiquidados: '',
        totalDiasReconocer: '',
        totalDiasAcumulados: [],
        itemsIncapacidadesPrevias: [],
        totalLiquidacion: 0,
        ibcLabel: 0,
        diasAcumulados: 0,
        lengthPlanilla: '',
        lengthLiquidacion: '',
        diasLiquidados: 0,
        diasReconocer: 0,
        valorReconocer: 0,
        valor: '',
        payload: null,
        alerta: {},
        checkbox: false,
        veralerta: false,
        veralertaarchivos: false,
        fechasInicio: [],
        idAportantes: [],
        identificacionAportantes: [],
        // dataSoporte: { soporte: [] },
        dialog: false,
        dialog2: false,
        tableLoading: false,
        arrayTutela: [],
        dataDetalles: { detalle: [] },
        dataSoporte: { detalle: [] },
        dataRelaciones: { detalle: [] },
        dataPlanillas: { detalle: [] },
        dataDetallesPlanillas: { detalle: [] },
        dataCartera: { detalle: [] },
        dataIncPrevias: { detalle: [] },
        dataLiquidacion: { detalle: [] },
        causalesRechazo: { causal: [] },
        // arrayTutela: {no_tutela: '', id: ''},
        filterProfesion (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion + ' ' + item.consecutivo_rechazo)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        headersRelacion: [
          {
            text: 'Tipo Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Inicio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Fin',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Base Cotización',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        headersTipoSoporte: [
          {
            text: 'Tipo Soporte',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Ver',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersPlanilla: [
          {
            text: 'Planilla',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Subtipo Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Pagos',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Periodo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'IBC ESPERADO',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'IBC Compensado',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'ING',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'RET',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'SLN',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'VSP',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'VST',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Conc',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersDetallePlanilla: [
          {
            text: 'Planilla',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Identificación Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Razón Social Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Identificación Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Nombre Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Planilla',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Correcciones',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Planilla Corregida',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Subtipo Cotizante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Periodo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Pagos',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'ING',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'RET',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'TDE',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'TAE',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'VSP',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'VST',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'SLN',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'IGE',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'LMA',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'VAC',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'IBC',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tarifa',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Cotización Obligatoria',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Estado Conciliación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Estado Compensación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Giro Compensación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Total Días Compensados',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'UPC Reconocida',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Provisión de Incapacidades',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Valor UPC promoción',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Serial BDUA',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Serial HA',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersCartera: [
          {
            text: 'Fecha Plazo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Cotizante Esperado',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Periodo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días Esperados',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'IBC ESPERADO',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersIncapacidadPrevia: [
          {
            text: '',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'ID Solicitud',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Dx',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Incapacidad',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días Incapacidad',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días Acumulados',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Inicio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Fin',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersLiquidacion: [
          {
            text: 'Periodo Liquidación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días Liquidados',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Días Reconocer',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Valor a Reconocer',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersRechazo: [
          {
            text: 'Causal Rechazo',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: '',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        tipoIncapacidad: [
          {
            value: '2',
            nombre: 'Incapacidad enfermedad general'
          },
          {
            value: '4',
            nombre: 'Incapacidad por accidente de trabajo'
          },
          {
            value: '5',
            nombre: 'Incapacidad por accidente de tránsito'
          },
          {
            value: '7',
            nombre: 'Licencia de maternidad'
          },
          {
            value: '8',
            nombre: 'Licencia de paternidad'
          },
          {
            value: '17',
            nombre: 'Licencia fallecimiento madre'
          }
        ],
        valores: [
          {
            value: '1',
            nombre: 'IBC Promedio'
          },
          {
            value: '2',
            nombre: 'IBC Fijo'
          }
        ],
        tabs: null,
        menuDateFecha: false,
        menuDateFechaParto: false,
        menuDateFechaFinTranscripcion: false,
        menuDateFechaExpedicionTranscripcion: false,
        incapacidad: {},
        arrayRechazo: {},
        dataRechazo: {rechazo: []},
        archivo: [],
        mostrar: true,
        mostrarDocs: true,
        filterTutelas (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.no_tutela)
          const query = hasValue(queryText)
          return text.toString().indexOf(query) > -1
        },
        buscandoCodigo: false,
        stepActual: 1,
        fechaActual: '2018-01-01',
        tabActive: null,
        ordenEdit: false,
        loadingSubmitAprobacion: false,
        loadingSubmit: false,
        loadingSubmitRechazo: false,
        motivosRetiro: [],
        cargos: [],
        gradosProfesional: [],
        fondosDeSalud: [],
        fondosDeCesantia: [],
        filterPrestador (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterRegistro (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterDiagnostico (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    mounted () {
      if (this.parametros.afiliado !== null) this.getRegistro(this.parametros.afiliado.id)
      console.log(this.parametros)
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'incapacidad.fecha_fin' () {
        var inicio = moment(this.incapacidad.fecha_incio_transcripcion)
        var fin = moment(this.incapacidad.fecha_fin)
        // console.log('inicio', this.incapacidad.fecha)
        // console.log('fin', this.incapacidad.fecha_fin)
        this.incapacidad.dias = fin.diff(inicio, 'days') + 1
      },
      'incapacidad.registro_profesional' () {
        // console.log(this.incapacidad.registro_profesional)
        for (const key in store.getters.complementosIncapacidades.medicosolicitante) {
          if (store.getters.complementosIncapacidades.medicosolicitante.hasOwnProperty(key)) {
            const element = store.getters.complementosIncapacidades.medicosolicitante[key]
            // console.log(element.codigo + ' ' + element.id + ' ' + element.descripcion)
            if (element.id === this.incapacidad.registro_profesional) {
              this.incapacidad.nombre_profesional = element.descripcion
            }
          }
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      complementosIncapacidades () {
        // console.log('el complemento', JSON.parse(JSON.stringify(store.getters.complementosIncapacidades)))
        return JSON.parse(JSON.stringify(store.getters.complementosIncapacidades))
      },
      complementosRechazos () {
        this.causalesRechazo.rechazo = JSON.parse(JSON.stringify(store.getters.complementosRechazos))
        return JSON.parse(JSON.stringify(store.getters.complementosRechazos))
      },
      computedDateFormattedFecha () {
        return this.formDate(this.incapacidad.fecha)
      },
      computedDateFormattedFechaParto () {
        return this.formDate(this.incapacidad.fecha_parto)
      },
      computedDateFormattedFechaFinTranscrip () {
        return this.formDate(this.incapacidad.fecha_fin)
      },
      computedDateFormattedFechaExpedicion () {
        return this.formDate(this.incapacidad.fecha_expedicion)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.incapacidad.consecutivo_afiliado = this.parametros.afiliado.id
        this.axios.get('licencia?id=' + this.parametros.afiliado.solicitud)
          .then((response) => {
            this.dataDetalles.detalle = response.data.data
            // this.dataSoporte.detalle = Object.keys(response.data.data[0].soportes)
            // this.dataSoporte = response.data.data[0].soportes
            this.dataSoporte.detalle = response.data.data
            this.dataRelaciones.detalle = response.data.data[0].relaciones
            this.dataPlanillas.detalle = response.data.data[0].planilla
            this.dataDetallesPlanillas.detalle = response.data.data[0].detallesPlanilla
            this.dataCartera.detalle = response.data.data[0].cartera
            this.dataIncPrevias.detalle = response.data.data[0].incapacidadesPrevias
            this.dataLiquidacion.detalle = response.data.data[0].liquidacion
            this.totalDias = response.data.data[0].totalDias
            this.totalIbc = response.data.data[0].totalIbc
            this.lengthPlanilla = response.data.data[0].planilla.length
            this.lengthLiquidacion = response.data.data[0].liquidacion.length
            var tamano = response.data.data[0].liquidacion.length - 1
            console.log('el tamano', tamano)
            this.totalDiasLiquidados = response.data.data[0].liquidacion[tamano].totalDiasLiquidados
            this.totalDiasReconocer = response.data.data[0].liquidacion[tamano].totalDiasReconocer
            console.log('la response data', response.data)
            // console.log('detallesPlanilla', this.dataDetallesPlanillas.detalle)
            this.incapacidad = response.data.data[0].incapacidad
            this.incapacidad.fecha_incio_transcripcion = moment(this.incapacidad.fecha_incio_transcripcion).format('YYYY/MM/DD')
            for (let i = 0; i < this.tipoIncapacidad.length; i++) {
              const element = this.tipoIncapacidad[i]
              if (element.value === this.incapacidad.tipoLicencia.toString()) {
                this.incapacidad.tipoLicencia = element.nombre
              }
            }
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      closeTable () {
        this.dialog = false
        this.dialog2 = false
      },
      getRegistro (id) {
        //
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      detalles () {
        // alert('detalle')
      },
      getSoportes () {
        this.axios.get('getsoportes?idtipo=' + this.incapacidad.tipo)
          .then((response) => {
            // response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            // this.pagination = response.data.meta
            // this.profesiones = response.data.data
            for (let i = 0; i < this.tipoIncapacidad.length; i++) {
              const element = this.tipoIncapacidad[i]
              if (this.incapacidad.tipo === element.value) {
                this.incapacidad.tipoLicencia = element.nombre
              }
            }
            this.incapacidad.fecha_incio_transcripcion = moment(this.incapacidad.fecha).format('MM/DD/YYYY')
            this.dataSoporte.soporte = response.data
            console.log(this.dataSoporte)
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      formReset () {
        this.mostrarTablaEducacion = false
        this.totalDias = null
        this.totalIbc = null
        this.empleadoID = null
        this.incapacidad.tutela = null
        this.incapacidad.tipo = null
        this.incapacidad.tutela = null
        this.incapacidad.fecha = null
        this.incapacidad.fecha_parto = null
        // this.incapacidad.archivo = null
        this.incapacidad.prestador = null
        this.incapacidad.registro_profesional = null
        this.incapacidad.nombre_profesional = null
        this.incapacidad.cargo = null
        this.incapacidad.diagnostico_principal = null
        this.incapacidad.tipoLicencia = null
        this.incapacidad.fecha_incio_transcripcion = null
        this.incapacidad.fecha_fin = null
        this.incapacidad.fecha_expedicion = null
        this.incapacidad.dias = null
        this.incapacidad.fecha_inicio_liquida = null
        this.incapacidad.fecha_fin_liquida = null
        this.payload = null
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      closeDetalle () {
        this.dialog = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      descargar (soporte) {
        console.log(soporte)
        this.axios.get('descargasoporte?url=' + soporte)
          .then((response) => {
            let urls = soporte
            let ultimaPosicion = urls.lastIndexOf('/')
            let nombreArchivo = urls.substr(ultimaPosicion + 1, urls.length)
            // obtener extension
            let punto = urls.lastIndexOf('.')
            let extension = urls.substr(punto + 1, urls.length)
            console.log(extension)
            // let blob = new Blob([response.data], { type: response.headers['content-type'] })
            // console.log('contentype', response.headers['content-type'])
            console.log(response.data)
            // let link = document.createElement('a')
            // link.href = window.URL.createObjectURL(blob)
            // link.download = nombreArchivo
            // link.click()
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', nombreArchivo) // or any other extension
            document.body.appendChild(link)
            link.click()
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al descargar el archvio.', color: 'danger'})
            return false
          })
      },
      liquidar (totalDiasAcumulados) {
        this.loadingSubmit = true
        this.incapacidad.totalDiasLiquidados = totalDiasAcumulados
        this.incapacidad.listaPeriodo = this.dataLiquidacion.detalle
        this.axios.post('liquidar', this.incapacidad)
          .then((response) => {
            this.loadingSubmit = false
            this.totalLiquidacion = response.data.total_liquidacion.toLocaleString()
            this.diasAcumulados = response.data.dias_acumulados
            console.log('el array que busco', response.data)
            this.dataLiquidacion.detalle = response.data.arrayLiquidacion
            var diasReconocesSuma = 0
            for (let i = 0; i < response.data.arrayLiquidacion.length; i++) {
              const element = response.data.arrayLiquidacion[i]
              diasReconocesSuma = diasReconocesSuma + element.diasReconocer
            }
            this.totalDiasReconocer = diasReconocesSuma
          })
          .catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error en la liquidación.', color: 'danger'})
            return false
          })
      },
      agregarIncapacidadesPrevias (item) {
        let elt = document.getElementById(item.consecutivo_licencia)
        if (elt.checked) {
          if (!item.dias_acumulados) {
            item.dias_acumulados = 0
          }
          this.totalDiasAcumulados.push(item.dias_acumulados)
          this.itemsIncapacidadesPrevias.push(item)
        } else {
          const indexFecha = this.totalDiasAcumulados.indexOf(item.dias_acumulados)
          let indexID = -1
          for (let i = 0; i < this.itemsIncapacidadesPrevias.length; i++) {
            const element = this.itemsIncapacidadesPrevias[i]
            if (element.consecutivo_licencia === item.consecutivo_licencia) {
              indexID = i
            }
          }
          this.totalDiasAcumulados.splice(indexFecha, 1)
          this.itemsIncapacidadesPrevias.splice(indexID, 1)
        }
      },
      saveCausal () {
        // this.loadingSubmitRechazo = true
        if (this.i !== '' && this.i !== null) {
          // this.dataEducacion.estudio[this.i] = Object.assign({}, this.educacion)
          this.dataRechazo.rechazo[this.i].causal_rechazo = this.arrayRechazo.causal_rechazo
        } else {
          this.dataRechazo.rechazo.push(this.arrayRechazo)
        }
        this.formTableReset()
      },
      eliminarCausal (item) {
        this.dataRechazo.rechazo.splice(item, 1)
      },
      editarCausal (item, index) {
        this.dialog2 = true
        this.arrayRechazo = Object.assign({}, item)
        this.i = index
      },
      formTableReset () {
        this.i = null
        this.arrayRechazo = {
          causal_rechazo: null
        }
        this.dialog2 = false
        this.loadingSubmitRechazo = false
      },
      calcularIbc (valor) {
        console.log(valor)
        this.incapacidad.valorSelectIbc = valor
        this.incapacidad.totalDias = this.totalDias
        this.incapacidad.totalIbc = this.totalIbc
        // this.dataPlanillas.valorSelectIbc = valor
        this.axios.post('calcularIbc', this.incapacidad)
        // this.axios.post('calcularIbc', this.dataPlanillas.detalle)
          .then((response) => {
            console.log(response)
            this.ibcLabel = response.data.toLocaleString()
          })
          .catch(e => {
            return false
          })
      },
      aprobarORechazar (accionAuditoria) {
        if (accionAuditoria === 'aprobar') {
          this.loadingSubmitAprobacion = true
        } else {
          this.loadingSubmitRechazo = true
        }
        this.incapacidad.accionAuditoria = accionAuditoria
        this.incapacidad.incapacidadesPrevias = this.itemsIncapacidadesPrevias
        this.incapacidad.rechazos = this.dataRechazo.rechazo
        this.incapacidad.total_liquidacion = this.totalLiquidacion
        this.axios.post('send_approve_or_reject', this.incapacidad)
          .then((response) => {
            alert('procesando ' + accionAuditoria)
            if (accionAuditoria === 'aprobar') {
              this.loadingSubmitAprobacion = false
            } else {
              this.loadingSubmitRechazo = false
            }
            if (response.data !== 'correcto') {
              this.$store.commit(SNACK_SHOW, {msg: response.data, color: 'danger'})
              return false
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Auditoria radicada correctamente.', color: 'success'})
            }
          })
          .catch(e => {
            return false
          })
      }
    },
    filters: {
      dicotomia: function (val) {
        if (val === 1) {
          return 'Si'
        } else {
          return 'No'
        }
      },
      tipoincapacidad: function (val, tipoIncapacidad) {
        for (let i = 0; i < tipoIncapacidad.length; i++) {
          const element = tipoIncapacidad[i]
          if (val.toString() === element.value) {
            return element.nombre
          }
        }
      },
      formatoNumero: function (val) {
        if (val) {
          return val.toLocaleString('es-ES')
        }
      },
      estado: function (val) {
        if (val === 1) {
          return 'Radicada'
        } else if (val === 2) {
          return 'Aprobada'
        } else if (val === 3) {
          return 'Negado'
        } else if (val === 4) {
          return 'En progreso'
        } else if (val === 5) {
          return 'Anulada'
        } else if (val === 6) {
          return 'Rechazado'
        }
      },
      filterRechazos (val, causalesRechazo) {
        for (let i = 0; i < causalesRechazo.rechazo.rechazos.length; i++) {
          const element = causalesRechazo.rechazo.rechazos[i]
          if (element.consecutivo_rechazo === val) {
            return element.descripcion
          }
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
  .trverde {
    background-color: #6ccd29;
  }
</style>
