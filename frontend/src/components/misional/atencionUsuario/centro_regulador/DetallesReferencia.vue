<template>
  <div>
    <v-layout wrap>
      <v-card class="refWidth">
        <v-toolbar class="grey lighten-3 elevation-1 toolbar--dense">
          <v-tooltip bottom>
            <v-btn
              slot="activator"
              class="accent"
              fab
              small
              @click.stop="drawer = !drawer"
            >
              <v-icon medium v-text="'sort'"></v-icon>
            </v-btn>
            <span>Información Afiliado</span>
          </v-tooltip>
          <v-toolbar-title v-text="tapTitle"></v-toolbar-title>
          <v-spacer></v-spacer>
          <v-tooltip bottom>
            <v-btn
              slot="activator"
              class="grey lighten-3"
              fab
              :disabled="referencia.estado !== 'Finalizada' ? true : (false)"
              small
              @click.stop="cardReferencia = !cardReferencia">
              <v-icon medium>chrome_reader_mode</v-icon>
            </v-btn>
            <span v-text="referencia.estado !== 'Finalizada' ? 'Opción habilitada al finalizar traslado.' : 'Cierre del Proceso'"></span>
          </v-tooltip>
          <v-divider
            class="mx-2"
            inset
            vertical
          ></v-divider>
          <h2 v-if="referencia.id != null" class="title">Referencia</h2>
          <div v-if="referencia.id != null">
            <v-chip label color="white" class="pa-0" text-color="red" dark absolute right top>
              <span class="subheading">N°</span>&nbsp;
              <span class="subheading" v-text="referencia.consecutivo == null ? '0000' : referencia.consecutivo"></span>
            </v-chip>
          </div>
        </v-toolbar>
        <form-referencia
          v-model="cardReferencia"
          :referId="idReferencia"
          @close="cardReferencia = false"
          :origen-id="ipsOrigen"
          @actualizar="val => getReferencia(val)"
        ></form-referencia>
        <!--:centro=""-->
        <!--:centro="centroReferente"-->
        <loading v-model="loadingRef" />
        <v-container fluid grid-list-xl class="pa-2">
          <v-layout row wrap>
            <v-flex xs12></v-flex>
            <v-flex xs12 v-if="referencia.estado_egreso !== null" class="pt-0">
              <v-alert
                v-model="alertCierre"
                type="success"
                class="mt-0"
              >
                <span v-text="`¡Proceso Cerrado! El estado de egreso del paciente es ${referencia.estado_egreso}`"></span>
              </v-alert>

            </v-flex>
            <v-flex xs12 class="pa-0">
              <v-container class="ma-1 pt-2 pb-3 mt-0" fluid grid-list-xl>
                <v-layout row align-center :justify-center="!$vuetify.breakpoint.xs" id="layoutBitacora">
                  <v-speed-dial fixed :direction="!$vuetify.breakpoint.xs ? 'left' : 'right'" open-on-hover
                                transition="slide-y-reverse-transition" class="ref_speed">
                    <v-tooltip :right="!$vuetify.breakpoint.xs" :top="$vuetify.breakpoint.xs" slot="activator">
                      <v-btn fab color="blue darken-2"
                             slot="activator">
                        <v-icon color="white">add_circle</v-icon>
                      </v-btn>
                      <span>Registrar</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <v-btn
                        fab
                        color="white"
                        small
                        :disabled="timelines.filter(b => (b.au_tipaccion_id === 10
                             || b.au_tipaccion_id === 12
                             || b.au_tipaccion_id === 16
                             || b.au_tipaccion_id === 17
                             || b.au_tipaccion_id === 18
                             || b.au_tipaccion_id === 19
                             || b.au_tipaccion_id === 20
                             )).length ? true : null"
                        @click="openCardDialog(10)"
                        slot="activator">
                        <v-icon color="pink darken-1" v-text="'cancel'"></v-icon>
                      </v-btn>
                      <span v-text="'Cancelar'"></span>
                    </v-tooltip>
                    <v-tooltip top v-if="typeForm !== 'Bitacora'">
                      <v-btn fab color="white" small
                             slot="activator" @click="openCardDialog(7)">
                        <v-icon color="teal">list_alt</v-icon>
                      </v-btn>
                      <span>Registrar la Bitácora</span>
                    </v-tooltip>
                    <v-tooltip top v-if="typeForm !== 'Presentar'">
                      <v-btn fab
                             color="white"
                             small
                             :disabled="timelines.filter(b => (b.au_tipaccion_id === 5
                             || b.au_tipaccion_id === 10
                             || b.au_tipaccion_id === 12
                             || b.au_tipaccion_id === 16
                             || b.au_tipaccion_id === 17
                             || b.au_tipaccion_id === 18
                             || b.au_tipaccion_id === 19
                             || b.au_tipaccion_id === 20
                             )).length ? true : null"
                             slot="activator" @click="openCardDialog(1)">
                        <v-icon color="primary">local_library</v-icon>
                      </v-btn>
                      <!--<span v-if="timelines.filter(b => (b.id > bitacora.id) && (b.au_tipaccion_id === 5)).length">No puede presentar (IPS Selecciona)</span>-->
                      <span>Presentar el Afiliado</span>
                    </v-tooltip>
                  </v-speed-dial>
                </v-layout>
                <v-timeline v-if="bitacoras.length" :dense="$vuetify.breakpoint.xs">
                  <v-slide-x-transition group hide-on-leave>
                    <v-timeline-item
                      v-for="(item, index) in bitacoras"
                      :key="index"
                      :id="`${item.id}`"
                      :icon="item.accion.icon"
                      :color="item.accion.color"
                      fill-dot
                    >
                      <span
                        slot="opposite"
                        :class="`title font-weight-medium`"
                        v-text="item.timeline_fecha" v-if="!$vuetify.breakpoint.xs">
                      </span>
                      <div class="py-3">
                        <v-hover>
                          <v-card dark :class="item.accion.color" slot-scope="{ hover }">
                            <v-expand-transition>
                              <article v-if="hover"
                                       class="d-flex transition-fast-in-fast-out v-card--reveal display-3 white--text text-xs-center" style="height: auto !important;">
                                <v-tooltip
                                  class="link_status"
                                  bottom
                                  v-for="(accionItem, i) in complementos.tipacciones.filter(type => type.id === item.acciones.find(val => type.id === val))"
                                  :key="i"
                                >
                                  <v-btn
                                    slot="activator"
                                    icon
                                    @click.prevent="openCardDialog(accionItem.option, item)"
                                  >
                                    <v-icon
                                      :color="accionItem.color"
                                      v-text="accionItem.icon"
                                    >
                                    </v-icon>
                                  </v-btn>
                                  <span v-text="accionItem.tooltip"></span>
                                </v-tooltip>
                                <!--<v-checkbox label="Seleccionar" v-model="bitacora.presentacion.seleccionada"></v-checkbox>-->
                              </article>
                            </v-expand-transition>
                            <!--&& item.au_tipaccion_id !== 10 && item.au_tipaccion_id !== 3 && item.au_tipaccion_id !== 6 && item.au_tipaccion_id !== 13 && item.au_tipaccion_id !== 12-->
                            <v-card-title
                              v-if="item.au_tipaccion_id !== 7"
                              class="mb-0 pb-0"
                            >
                              <v-toolbar
                                color="blue-grey darken-4"
                                class="pa-0 elevation-1 dense"
                              >
                                <v-toolbar-title
                                  class="title font-weight-medium pa-0"
                                  v-text="item.accion.descripcion"
                                >
                                </v-toolbar-title>
                              </v-toolbar>
                            </v-card-title>
                            <v-card-text>
                              <!--<v-hover> slot-scope="{ hoverTwo }"-->
                                <v-card dark class="mx-auto my-auto noneBefore" color="grey lighten-4">
                                  <!--<v-expand-transition>-->
                                    <!--<article-->
                                      <!--v-if="item.au_tipaccion_id === 10 || item.au_tipaccion_id === 3 || item.au_tipaccion_id === 6 || item.au_tipaccion_id === 13 || item.au_tipaccion_id === 12"-->
                                      <!--class="d-flex transition-fast-in-fast-out v-card&#45;&#45;reveal-two display-3 white&#45;&#45;text" :class="item.accion.color"-->
                                      <!--style="height: 100% !important">-->
                                      <!--&lt;!&ndash;<v-icon v-if="item.au_tipaccion_id === 8" size="6rem" v-text="'directions_car'"></v-icon>&ndash;&gt;-->
                                      <!--{{ item.accion.accion}}-->
                                    <!--</article>-->
                                  <!--</v-expand-transition>-->
                                  <v-container class="elevation-1 dense" style="background-color: #f5f5dc; border-radius: 5px">
                                    <v-layout row wrap justify-start class="mb-2" v-if="item.accion.id !== 7">
                                      <v-flex xs12 sm12 md12 lg12 class="pa-0" v-show="item.presentacion">
                                        <div class="d-inline-block">
                                          <v-icon class="mr-1 footFont" v-text="'location_city'"></v-icon>
                                          <small class="footFont d-inline-block">IPS: </small>
                                          <h2 class="subheading font-weight-thin footFont d-inline-block"
                                              :class="{'tachado': (
                                              item.accion.id === 10
                                              || item.accion.id === 6
                                              || item.accion.id === 3
                                              || item.accion.id === 12
                                              || item.accion.id === 16
                                              || item.accion.id === 17
                                              || item.accion.id === 18
                                              || item.accion.id === 19
                                              )}"
                                              v-text="item.presentacion ? item.presentacion.entidad.nombre : 'No Registrado'"
                                          >
                                          </h2>
                                        </div>
                                      </v-flex>
                                      <v-flex xs12 sm12 md12 lg12 class="pa-0" v-show="item.traslado" :class="{'lg12 d-none': (item.accion.id === 2 || item.accion.id === 5)}">
                                        <div v-if="item.accion.id === 4
                                              || item.accion.id === 10
                                              || item.accion.id === 12
                                              || item.accion.id === 16
                                              || item.accion.id === 17
                                              || item.accion.id === 18
                                              || item.accion.id === 19">
                                          <v-icon class="mr-1 footFont" v-text="'local_shipping'"></v-icon>
                                          <h2
                                            class="subheading font-weight-thin footFont d-inline-block"
                                            :class="{'tachado': (
                                            item.accion.id === 10
                                            || item.accion.id === 12
                                            || item.accion.id === 16
                                            || item.accion.id === 17
                                            || item.accion.id === 18
                                            || item.accion.id === 19)}"
                                            v-text="item.traslado ? (item.traslado.entidad_transportadora
                                            ? item.traslado.entidad_transportadora.nombre
                                            : (item.traslado.tipo_traslado === 'Interno' ? 'Intrahospitalaria' : 'No Registrado')) : 'No Registrado'"
                                          ></h2>
                                        </div>
                                      </v-flex>
                                      <v-flex xs12 sm12 md12 lg12 class="pa-0" :class="{'lg12 d-none': (item.accion.id === 2 || item.accion.id === 5)}">
                                        <div v-if="item.accion.id === 8 || item.accion.id === 13 || item.accion.id === 15
                                        || item.au_tipaccion_id === 12 || item.au_tipaccion_id === 9 || item.au_tipaccion_id === 20">
                                          <v-icon class="mr-1 footFont" v-text="'how_to_reg'"></v-icon>
                                          <small class="footFont d-inline-block">Afiliado: </small>
                                          <h2 class="subheading font-weight-thin footFont d-inline-block"
                                              :class="{'tachado': item.accion.id === 10}" v-text="referencia.afiliado ? referencia.afiliado.nombre_completo : 'No Registrado'"></h2>
                                        </div>
                                      </v-flex>
                                      <v-flex xs12 sm12 md12 lg12 class="pa-0" :class="{'d-none': (item.accion.id !== 2 || item.accion.id !== 5 || item.accion.id !== 9)}">
                                        <div v-show="item.accion.id === 2 || item.accion.id === 5 || item.accion.id === 9">
                                          <v-icon class="mr-1 footFont" v-text="'person'"></v-icon>
                                          <small class="footFont d-inline-block">Médico: </small>
                                          <h2 class="subheading font-weight-thin footFont d-inline-block" v-text="item.presentacion ? item.presentacion.medico_acepta : 'No Registrado'"></h2>
                                        </div>
                                      </v-flex>
                                      <v-flex v-if="item.accion.id === 5 || item.accion.id === 11 || item.accion.id === 6" xs12 sm12 md12 lg12
                                              class="pa-0" :class="{'lg12': (item.accion.id === 5 || item.accion.id === 11 || item.accion.id === 6)}">
                                        <div class="d-block">
                                          <v-icon class="mr-1 footFont" v-text="'settings_phone'"></v-icon>
                                          <h2 class="subheading font-weight-thin footFont d-inline-block" :class="{'tachado': item.accion.id === 6}" v-text="item.presentacion
                                          ? (item.presentacion.entidad.telefono_sede ? item.presentacion.entidad.telefono_sede : 'No Registra') : null"></h2>
                                        </div>
                                        <div class="d-block">
                                          <v-icon class="mr-1 footFont" v-text="'room'"></v-icon>
                                          <h2 class="subheading font-weight-thin footFont d-inline-block" :class="{'tachado': item.accion.id === 6}" v-text="item.presentacion
                                          ? (item.presentacion.entidad.direccion_sede ? item.presentacion.entidad.direccion_sede : 'No Registra') : null"></h2>
                                        </div>
                                      </v-flex>
                                      <v-flex xs12 sm12 md12 lg12 class="pa-0">
                                        <div v-if="item.accion.id === 4 || item.accion.id === 10 || item.accion.id === 8 || item.accion.id === 15" class="d-block">
                                          <v-icon class="mr-1 footFont" v-text="(item.traslado ? (item.traslado.tipo_traslado === 'Terrestre' ? 'local_taxi' : (item.traslado.tipo_traslado === 'Interno' ? 'transfer_within_a_station' : 'local_airport')) : '')"></v-icon>
                                          <h2 class="subheading font-weight-thin d-inline-block footFont" :class="{'tachado': item.accion.id === 10}" v-text="(item.traslado ? item.traslado.tipo_traslado : null)"></h2>
                                        </div>
                                        <div v-if="item.accion.id === 4 || item.accion.id === 10" class="d-block">
                                          <v-icon class="mr-1 footFont" v-text="'local_pharmacy'"></v-icon>
                                          <h2 class="subheading font-weight-thin d-inline-block footFont" :class="{'tachado': item.accion.id === 10}" v-text="(item.traslado ? item.traslado.tipo_ambulancia : null)"></h2>
                                        </div>
                                        <v-flex xs12 class="pa-0">
                                          <v-card class="text-xs-justify noneBefore elevation-0 dense transparent">
                                            <v-card-text class="text-xs-justify pa-0">
                                              <div
                                                v-if="item.accion.id === 4
                                                || item.accion.id === 10
                                                || item.accion.id === 8
                                                || item.accion.id === 13
                                                || item.accion.id === 15
                                                || item.accion.id === 12
                                                || item.accion.id === 16
                                                || item.accion.id === 17
                                                || item.accion.id === 18
                                                || item.accion.id === 19"
                                                   class="d-block pl-0 pt-0 text-xs-justify">
                                                <v-icon class="mr-1 footFont" v-text="'format_indent_increase'"></v-icon>
                                                <p class="subheading font-weight-thin d-inline-block footFont text-xs-justify pb-0"
                                                   :class="{'tachado': (
                                                    item.accion.id === 10
                                                    || item.accion.id === 12
                                                    || item.accion.id === 16
                                                    || item.accion.id === 17
                                                    || item.accion.id === 18
                                                    || item.accion.id === 19
                                                    )}"
                                                   v-text="(item.traslado ? (item.traslado.contacto ? item.traslado.contacto : 'No Registrado') : 'No Registrado')"></p>
                                              </div>
                                            </v-card-text>
                                          </v-card>
                                        </v-flex>
                                      </v-flex>
                                    </v-layout>
                                    <v-layout v-if="item.observaciones" justify-start class="mb-2">
                                      <v-flex xs12 class="pa-0">
                                        <v-card class="text-xs-justify noneBefore elevation-0 dense transparent">
                                          <v-card-text class="text-xs-justify" :class="{'pa-0 pl-1': item.au_tipaccion_id !== 7}">
                                            <h4 v-if="item.au_tipaccion_id !== 7" class="footFont pl-0 pr-1" style="border-bottom: 0.1pt solid #454454" v-text="'Observaciones'"></h4>
                                            <p class="footFont mb-1 parrafo" :class="{'pt-1 pb-0': item.au_tipaccion_id !== 7}" v-text="item.observaciones"></p>
                                          </v-card-text>
                                          <v-card-actions v-if="item.au_tipaccion_id === 7 && item.archivo">
                                            <v-layout row wrap>
                                              <v-flex xs12 class="text-xs-left text-xs-center text-md-right">
                                                <!--<span v-text="``"></span>-->
                                                <v-tooltip bottom>
                                                  <v-btn icon slot="activator" :href="item.archivo ? item.archivo.url_signed : ''" dark outline color="grey">
                                                    <v-icon color="accent" v-text="'archive'"></v-icon>
                                                  </v-btn>
                                                  <span v-text="`Descargar ${item.archivo ? item.archivo.nombre : '###'}`"></span>
                                                </v-tooltip>
                                              </v-flex>
                                            </v-layout>
                                          </v-card-actions>
                                        </v-card>
                                      </v-flex>
                                    </v-layout>
                                    <v-layout align-center justify-end class="mt-2">
                                      <v-flex xs12 sm12 md12 class="text-xs-justify text-md-right pa-0">
                                        <div>
                                          <v-icon class="mr-1 footFont" v-text="'event'"></v-icon>
                                          <span class="subheading mr-1 footFont"  style="font-size: 1em !important;" v-text="getfecha(item.fecha)"></span>
                                          <!--<span class="mr-1 footFont">·</span>-->
                                          <v-icon class="mr-1 footFont" v-text="'access_time'"></v-icon>
                                          <span class="subheading footFont" style="font-size: 1em !important;" v-text="getTime(item.fecha)"></span>
                                        </div>
                                      </v-flex>
                                    </v-layout>
                                  </v-container>
                                </v-card>
                              <!--</v-hover>-->
                            </v-card-text>
                          </v-card>
                        </v-hover>
                  </div>
                    </v-timeline-item>
                  </v-slide-x-transition>
                </v-timeline>
                <template v-else>
                  <div>
                    <v-alert
                      value="true"
                      type="info"
                      transition="scale-transition"
                    >
                      {{ message }}
                    </v-alert>
                  </div>
                </template>
              </v-container>
            </v-flex>
          </v-layout>
        </v-container>
        <template>
          <v-dialog
            v-model="cardDialog"
            :max-width="cardWidth"
            persistent
          >
            <v-form data-vv-scope="formReferencia">
              <v-card>
                <toolbar-list :title="titleForm" subtitle="Referencia"/>
                <v-container fluid grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md9 lg9 xl9 v-if="typeForm === '1'">
                      <postulador-v2
                        no-data="Busqueda por NIT, razon social u código de habilitación."
                        hint="tercero.identificacion_completa"
                        item-text="nombre"
                        data-title="tercero.identificacion_completa"
                        data-subtitle="nombre"
                        label="IPS"
                        entidad="entidades"
                        preicon="location_city"
                        @changeid="val => bitacora.presentacion.rs_entidades_id = val"
                        v-model="bitacora.presentacion.entidad"
                        name="IPS"
                        route-params="rs_tipentidade_id=1"
                        rules="required|entidadReferencia"
                        v-validate="'required|entidadReferencia'"
                        :error-messages="errors.collect('IPS')"
                        no-btn-create
                        :min-characters-search="3"
                        clearable/>
                    </v-flex>
                    <v-flex xs12 sm12 md9 lg9 xl9 v-if="typeForm === '2'">
                      <v-text-field
                        v-model="bitacora.presentacion.medico_acepta"
                        prepend-icon="how_to_reg"
                        name="médico"
                        required v-validate="'required'"
                        :error-messages="errors.collect('médico')"
                        label="Médico"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md3  v-if="typeForm === '1'">
                      <!--<v-text-field-->
                        <!--label="Fecha de Presentación"-->
                        <!--v-model="bitacora.presentacion.fecha_presentacion"-->
                        <!--readonly-->
                      <!--&gt;-->
                      <!--</v-text-field>-->
                      <v-datetime-picker
                        label="Fecha de Presentación"
                        :datetime="bitacora.presentacion.fecha_presentacion"
                        @input="updateDatetime"
                        :timeData="bitacora.presentacion.fecha_presentacion"
                        :min="referencia.fecha_solicitud"
                        :max="moment().format('YYYY-MM-DD HH:mm:ss')"></v-datetime-picker>
                    </v-flex>
                    <v-flex xs12 sm12 md3 v-if="typeForm === '2'">
                      <v-datetime-picker
                        label="Fecha de Aceptación"
                        :datetime="bitacora.presentacion.fecha_aceptacion"
                        @input="updateDatetimeTwo"
                        :timeData="bitacora.presentacion.fecha_aceptacion"
                        :min="bitacora.presentacion.fecha_presentacion"
                        :max="moment().format('YYYY-MM-DD HH:mm:ss')"
                      >
                      </v-datetime-picker>
                    </v-flex>
                    <v-flex xs12 v-if="typeForm === '10'">
                      <v-select
                        :items="opcionesCancelado"
                        v-model="cancelado"
                        @change="val => bitacora.au_tipaccion_id = val ? val.id : null"
                        item-text="accion"
                        name="motivo"
                        label="Motivo"
                        return-object
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 v-if="typeForm === '7'">
                      <input-file
                        ref="archivoAdjunto"
                        label="Adjuntar Archivo"
                        v-model="bitacora.archivo"
                        :file-name="bitacora.archivo ? bitacora.archivo.nombre : null"
                        accept="application/pdf"
                        :hint="'Extenciones aceptadas: pdf'"
                        class="mb-3"
                        prepend-icon="attach_file"
                      />
                    </v-flex>
                    <v-flex xs12 sm12 class="pb-0 pt-0">
                      <v-textarea
                        v-model="bitacora.observaciones"
                        color="primary"
                        name="observaciones"
                        v-validate="'required'"
                        key="observaciones"
                        :error-messages="errors.collect('observaciones')">
                        <div slot="label">
                          Observaciones
                        </div>
                      </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-if="bitacora.presentacion.id < 0">
                      <v-select
                        :items="estadosPresentacion"
                        v-model="bitacora.presentacion.estado"
                        label="Estado"
                        name="estado"
                        key="estado"
                        v-validate="'required'"
                        :readonly="typeForm === 'Presentar' ? true : false"
                        :error-messages="errors.collect('estado')">
                        <template slot="no-data">
                          <div class="pa-1 red lighten-4">
                            <span class="body-1 white" v-text="'No se encuentra.'"> </span>
                          </div>
                        </template>
                      </v-select>
                    </v-flex>
                  </v-layout>
                </v-container>
                <v-card-actions>
                  <v-divider></v-divider>
                  <v-btn
                    flat
                    @click="cardDialog = false">Cerrar</v-btn>
                  <v-btn
                    color="blue darken-1"
                    @click.prevent="validateBitacora(bitacora)"
                    flat
                  > {{ !bitacora.presentacion.id ? 'Agregar' : 'Actualizar' }}</v-btn>
                </v-card-actions>
              </v-card>
            </v-form>
          </v-dialog>
        </template>
        <form-traslado
          :value="dialogTraslado"
          @cancelar="dialogTraslado = false"
          @crear="val => validateTraslado(val)"
          :traslado="bitacora"
          :type-form="typeForm"
          :referencia="referencia"
          :titleForm="titleForm"
          :optionsCancelable="opcionesCancelado"
        >
        </form-traslado>
      </v-card>
      <informacion-ref-afiliado
        v-model="drawer"
        :referencia="referencia"
        @cancelar="drawer = false"
      >
      </informacion-ref-afiliado>
    </v-layout>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import VDatetimePicker from '../../../general/VDatetimePicker'
  import store from '../../../../store/complementos/index'
  import { Validator } from 'vee-validate'
  import FormTraslado from './FormTraslado'
  import InformacionRefAfiliado from './InformacionRefAfiliado'
  import FormReferencia from './FormReferencia'
  import InputFile from '../../../general/InputFile'
  import {REFERENCIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'DetallesReferencia',
    props: ['parametros'],
    components: {
      InputFile,
      FormReferencia,
      InformacionRefAfiliado,
      FormTraslado,
      VDatetimePicker,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        datetime: this.moment().format('YYYY-MM-DD h:mm:ss'),
        datetimeTwo: this.moment().format('YYYY-MM-DD h:mm:ss'),
        referencia: {},
        bitacoras: [],
        timelines: [],
        bitacora: {},
        acciones: [],
        cancelado: null,
        opcionesCancelado: [],
        centroReferente: {},
        ipsOrigen: null,
        alertCierre: false,
        estadosPresentacion: ['Presentado', 'Aceptado', 'No Aceptado'],
        cardDialog: false,
        validDialogTraslado: true,
        typeForm: null,
        dialog: null,
        titleForm: null,
        idReferencia: null,
        message: null,
        drawer: false,
        cardReferencia: false,
        dialogTraslado: false,
        right: null,
        duration: 300,
        offset: 0,
        easing: 'easeInOutCubic',
        payData: null,
        loadingRef: false
      }
    },
    watch: {
      'dialogTraslado' (val) {
        if (val === false) {
          this.dialogRef()
        } else {
          this.bitacora.traslado.entidad_origen = this.referencia ? this.referencia.entidad_uno : null
        }
      },
      'timelines' (items) {
        items && items.length && this.getTimetobit(JSON.parse(JSON.stringify(items)))
      },
      'referencia.id' (val) {
        if (val) {
          this.bitacora.presentacion.au_referencia_id = val
          this.bitacora.au_referencia_id = val
        }
      },
      'cardDialog' (val) {
        if (!val) {
          this.formBitacora()
          this.$validator.reset()
          this.typeForm = null
          this.cancelado = null
        }
      },
      'bitacora.presentacion.fecha_presentacion' (val) {
        if (this.bitacora.presentacion.estado === 'Presentado') {
          this.bitacora.fecha = val
        }
      },
      'bitacora.presentacion.fecha_aceptacion' (val) {
        if (this.bitacora.presentacion.estado === 'Aceptado') {
          this.bitacora.fecha = val
        }
      },
      'typeForm' (val) {
        const accion = val ? this.complementos.tipacciones.find(value => parseInt(value.option) === parseInt(val)) : null
        this.bitacora.au_tipaccion_id = !accion ? null : accion.id
        this.opcionesCancelado = parseInt(val) === 10 ? this.getOpcionesCancelado() : []
      }
    },
    beforeMount () {
      Validator.extend('entidadReferencia', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = {valido: true, mensaje: null}
            if (value.id === this.ipsOrigen) {
              response = {valido: false, mensaje: `La IPS origen no debe ser igual a la IPS destino.`}
            } else {
              let items = this.timelines.filter(a => (a.presentacion ? a.presentacion.rs_entidades_id === value.id : null))
              if (items.length) {
                let final = items[0]
                let verificarAccion = (final.au_tipaccion_id === 8 || final.au_tipaccion_id === 4 || final.au_tipaccion_id === 2 || final.au_tipaccion_id === 1)
                response = verificarAccion
                  ? {valido: false,
                    mensaje: `La entidad no se puede presentar porque se encuentra ${verificarAccion.au_tipaccion_id === 2
                      ? 'activada' : verificarAccion.au_tipaccion_id === 4 ? 'en solicitud de traslado'
                        : verificarAccion.au_tipaccion_id === 8 ? 'en camino' : 'presentada'}.`}
                  : (this.referencia.entidad_origen_id === value.id)
                    ? {valido: false, mensaje: `La IPS origen no debe ser igual a la IPS destino.`}
                    : {valido: true, mensaje: null}
              }
            }
            return resolve({valid: response.valido, data: {message: response.mensaje}})
          }
        }),
        getMessage: (field, params, data) => data.message
      })
      this.formBitacora()
      // this.ipsOrigen = this.referencia.entidad_uno ? this.referencia.entidad_uno.id : null
    },
    created () {
      if (this.parametros.entidad !== null) {
        this.getReferencia(this.parametros.entidad.id)
        this.idReferencia = this.parametros.entidad.id
      }
    },
    mounted () {
      // this.getBitacora(this.parametros.entidad.id)
    },
    methods: {
      getReferencia (id) {
        this.loadingRef = true
        this.axios.get('au_referencias/' + id)
          .then(res => {
            this.referencia = res.data.data
            this.idReferencia = res.data.data.id
            this.ipsOrigen = this.referencia.entidad_origen_id
            this.timelines = res.data.data.bitacoras
            this.message = this.timelines.length > 1 ? null : 'La referencia cargada no presenta registros en la bitácora.'
            let boolVal = false
            this.alertCierre = this.referencia.estado_egreso !== null ? (true) : boolVal
            this.cardReferencia = false
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro.', color: 'danger'})
          }).finally(() => (this.loadingRef = false))
      },
      getOpcionesCancelado () {
        return this.complementos.tipacciones.filter(type => type.id === [12, 16, 17, 18, 19, 20].find(val => type.id === val))
      },
      // getBitacora (id) {
      //   this.axios.get('refbitacoras/' + id)
      //     .then((res) => {
      //       this.timelines = res.data.data
      //       this.message = this.timelines.length > 1 ? null : 'La referencia cargada no presenta registros en la bitácora.'
      //     }).catch(() => {
      //       this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registro.', color: 'danger'})
      //     })
      // },
      getTimetobit (items) {
        let bitacoraTemporales = []
        let existen = items.filter(x => x.au_tipaccion_id === [10, 12, 16, 17, 18, 19, 20].find(val => x.au_tipaccion_id === val) && x.traslado === null && x.presentacion === null && x.au_reftraslado_id === null && x.au_refpresentacion_id === null)
        items.forEach(val => {
          let actions = (existen.length) ? [] : this.actions(val, items)
          // let actions = this.actions(val, items)
          bitacoraTemporales.push(
            {
              accion: val.accion,
              au_tipaccion_id: val.au_tipaccion_id,
              timeline_fecha: val.timeline_fecha,
              id: val.id,
              fecha: val.fecha,
              acciones: actions,
              observaciones: val.observaciones,
              presentacion: val.au_tipaccion_id !== 7 ? val.presentacion : null,
              traslado: val.au_tipaccion_id !== 7 ? val.traslado : null,
              archivo: val.archivo ? val.archivo : null
            }
          )
        })
        this.bitacoras = bitacoraTemporales
      },
      actions (val, items) {
        let acciones = []
        if (items.filter(x => (x.id > val.id) && (x.au_refpresentacion_id === val.au_refpresentacion_id)).length === 0) {
          if (val.accion.id) {
            switch (val.accion.id) {
              case 1:
                if (items.filter(y => y.au_tipaccion_id === 5).length === 0) {
                  acciones = [2, 3, 10]
                } else {
                  acciones = []
                }
                break
              case 2:
                if (items.filter(y => y.au_tipaccion_id === 5).length === 0) {
                  acciones = [3, 5, 10]
                } else {
                  acciones = []
                }
                break
              case 4:
                acciones = [10, 8]
                break
              case 5:
                acciones = [4, 6, 10]
                break
              case 8:
                acciones = [9]
                break
              case 13:
                acciones = [15, 10]
                break
              case 15:
                acciones = [9, 10, 12]
                break
              default:
                acciones = []
            }
          }
        }
        return acciones
      },
      validateTraslado (traslado) {
        // console.log('> traslado', traslado)
        let dataTraslado = JSON.parse(JSON.stringify(traslado))
        dataTraslado.fecha = dataTraslado.traslado.estado === 'Solicitado'
          ? dataTraslado.traslado.fecha_solicitud
          : (dataTraslado.traslado.estado === 'En Camino'
            ? dataTraslado.traslado.fecha_traslado : (dataTraslado.traslado.estado === 'Terminado'
              ? dataTraslado.traslado.fecha_llegada : null))
        this.agregar(dataTraslado)
      },
      async validateBitacora (bitacora) {
        if (await this.validador('formReferencia')) {
          this.agregar(bitacora)
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no deben estar vacíos.', color: 'danger'})
        }
      },
      addFormData () {
        let formData = new FormData()
        formData.append('id', this.bitacora.id)
        formData.append('au_referencia_id', this.bitacora.au_referencia_id)
        formData.append('au_refpresentacion_id', (this.bitacora.au_refpresentacion_id ? this.bitacora.au_refpresentacion_id : ' '))
        formData.append('au_reftraslado_id', (this.bitacora.au_reftraslado_id ? this.bitacora.au_reftraslado_id : ' '))
        formData.append('au_tipaccion_id', this.bitacora.au_tipaccion_id)
        formData.append('fecha', this.bitacora.fecha)
        formData.append('observaciones', this.bitacora.observaciones)
        formData.append('presentacion', this.bitacora.presentacion)
        formData.append('traslado', this.bitacora.traslado)
        if (this.bitacora.archivo) formData.append('archivo', typeof this.bitacora.archivo === 'undefined' ? '' : this.bitacora.archivo)
        this.payData = formData
      },
      async agregar (bitacora) {
        // console.log('presentar---1', bitacora)
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        if (this.bitacora.au_tipaccion_id === 7) this.addFormData()
        let send = !this.bitacora.id ? this.axios.post('au_refbitacoras', (this.bitacora.au_tipaccion_id === 7 ? this.payData : bitacora)) : null
        send.then(response => {
          // console.log('res', response.data.data)
          this.getReferencia(this.idReferencia)
          this.timelines.unshift(response.data.data)
          loader.hide()
          this.$store.commit(SNACK_SHOW, {msg: `Se realizó correctamente el registro de la bitácora.`, color: 'success'})
          this.$store.commit(REFERENCIA_RELOAD_ITEM, {item: '', estado: 'reload', key: this.parametros.key})
          // this.$vuetify.goTo(document.getElementById(response.data.data.id), this.opcions)
          this.dialogRef()
          this.$vuetify.goTo('#layoutBitacora',
            {
              selector: '#layoutBitacora',
              duration: 300,
              offset: 0,
              easing: 'easeInOutQuad'
            }
          )
        }).catch(() => {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro.', color: 'danger'})
        })
      },
      dialogRef () {
        this.formBitacora()
        this.$validator.reset()
        this.cardDialog = false
        this.dialogTraslado = false
      },
      openCardDialog (val, data) {
        this.formBitacora()
        this.typeForm = val.toString()
        this.options(this.typeForm, data)
      },
      options (typeForm, data) {
        switch (parseInt(typeForm)) {
          case 1:
            this.titleForm = 'Presentar Afiliado'
            this.bitacora.presentacion.estado = 'Presentado'
            this.cardDialog = true
            break
          case 2:
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.presentacion.fecha_aceptacion = this.moment().format('YYYY-MM-DD HH:mm:ss')
            this.titleForm = 'Afiliado Aceptado'
            this.bitacora.presentacion.estado = 'Aceptado'
            this.cardDialog = true
            break
          case 3:
            this.titleForm = 'Afiliado No Aceptado'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.presentacion.estado = 'No Aceptado'
            this.bitacora.au_tipaccion_id = 3
            this.cardDialog = true
            break
          case 4:
            this.titleForm = 'Solicitud de Traslado'
            this.dialogTraslado = true
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.traslado.fecha_solicitud = this.moment().format('YYYY-MM-DD HH:mm:ss')
            this.bitacora.traslado.estado = 'Solicitado'
            this.bitacora.au_tipaccion_id = 4
            break
          case 5:
            this.titleForm = 'IPS Seleccionada'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.presentacion.seleccionada = 'Si'
            this.bitacora.au_tipaccion_id = 5
            this.cardDialog = true
            break
          case 6:
            this.titleForm = 'IPS Deseleccionada'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.fecha = this.bitacora.presentacion ? this.bitacora.presentacion.fecha_aceptacion : null
            this.bitacora.presentacion.estado = 'Aceptado'
            this.bitacora.au_tipaccion_id = 6
            this.cardDialog = true
            break
          case 7:
            this.titleForm = 'Registrar Bitácora'
            this.cardDialog = true
            break
          case 8:
            this.titleForm = 'Inicio de Traslado'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.traslado = JSON.parse(JSON.stringify(data.traslado))
            this.bitacora.traslado.fecha_traslado = this.moment().format('YYYY-MM-DD HH:mm:ss')
            this.bitacora.traslado.estado = 'En Camino'
            this.bitacora.au_tipaccion_id = 8
            this.dialogTraslado = true
            this.cardDialog = false
            break
          case 9:
            this.titleForm = 'Llegada a IPS Destino'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.traslado = JSON.parse(JSON.stringify(data.traslado))
            this.bitacora.traslado.fecha_llegada = this.moment().format('YYYY-MM-DD HH:mm:ss')
            this.bitacora.traslado.estado = 'Terminado'
            this.bitacora.au_tipaccion_id = 9
            this.dialogTraslado = true
            break
          case 10:
            this.titleForm = 'Proceso Cancelado'
            if (data) {
              this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
              this.bitacora.presentacion.estado = 'No Aceptado'
              this.bitacora.au_tipaccion_id = 10
              if (data.traslado) {
                this.bitacora.traslado = JSON.parse(JSON.stringify(data.traslado))
                this.bitacora.traslado.estado = 'Cancelado'
                this.dialogTraslado = true
              } else {
                this.cardDialog = true
              }
            } else {
              this.bitacora.au_tipaccion_id = 10
              this.cardDialog = true
            }
            break
          case 13:
            this.titleForm = 'Asunto: Suspender Traslado'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.traslado = JSON.parse(JSON.stringify(data.traslado))
            this.bitacora.au_tipaccion_id = 13
            this.bitacora.traslado.estado = 'Suspendido'
            this.dialogTraslado = true
            this.cardDialog = false
            break
          case 15:
            this.titleForm = 'Salida del Transporte'
            this.bitacora.presentacion = JSON.parse(JSON.stringify(data.presentacion))
            this.bitacora.traslado = JSON.parse(JSON.stringify(data.traslado))
            this.bitacora.traslado.estado = 'En Camino'
            this.bitacora.au_tipaccion_id = 15
            this.dialogTraslado = true
            this.cardDialog = false
            // this.agregar(JSON.parse(JSON.stringify(this.bitacora)))
            break
        }
      },
      updateDatetime (datetime) {
        this.bitacora.presentacion.fecha_presentacion = datetime
      },
      updateDatetimeTwo (datetimeTwo) {
        this.bitacora.presentacion.fecha_aceptacion = datetimeTwo
      },
      formBitacora () {
        this.bitacora = {
          id: null,
          au_referencia_id: this.idReferencia,
          au_refpresentacion_id: null,
          au_reftraslado_id: null,
          au_tipaccion_id: null,
          fecha: this.moment().format('YYYY-MM-DD HH:mm:ss'),
          observaciones: null,
          gn_archivo_id: null,
          archivo: null,
          presentacion: {
            id: null,
            au_referencia_id: this.idReferencia,
            estado: null,
            fecha_aceptacion: null,
            fecha_llegada: null,
            fecha_presentacion: this.moment().format('YYYY-MM-DD HH:mm:ss'),
            fecha_salida: null,
            medico_acepta: null,
            rs_entidades_id: null,
            seleccionada: 'No',
            entidad: null
            // itemdetalle: null
          },
          traslado: {
            id: null,
            au_referencia_id: this.idReferencia,
            contacto: null,
            entidad_destino_id: null,
            entidad_origen_id: null,
            entidad_transporta_id: null,
            fecha_llegada: null,
            estado: null,
            fecha_solicitud: null,
            fecha_traslado: null,
            tipo_ambulancia: null,
            tipo_traslado: null,
            entidad_origen: null,
            entidad_destino: null,
            entidad_transportadora: null
          }
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      // getFechaPresentacion (val) {
      //   let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
      //   let dateFormat = datetime.split(' ')[0]
      //   // let dateFormat = this.moment(date1).format('YYYY/MM/DD')
      //   return `${dateFormat}`
      // },
      // getTimePresentacion (val) {
      //   let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
      //   let dateFormat = datetime.split(' ')[1]
      //   // let dateFormat = this.moment(date1).format('YYYY/MM/DD')
      //   return `${dateFormat}`
      // },
      getfecha (val) {
        let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
        let date1 = datetime.split(' ')[0]
        let dateFormat = this.moment(date1).format('YYYY/MM/DD')
        return `${dateFormat}`
      },
      getTime (val) {
        let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
        let time1 = datetime.split(' ')[1]
        let dt = datetime.split(' ')[2]
        return `${time1 + ' ' + dt}`
      }
    },
    computed: {
      tapTitle () {
        return 'Bitácora del Proceso'
      },
      complementos () {
        return store.getters.complementosReferencias
      },
      cardWidth () {
        if (this.typeForm === 'Bitacora') {
          return '500px'
        } else {
          return '800px'
        }
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('referencias')
      }
    }
  }
</script>

<style scoped>
  .refWidth {
    width: -webkit-fill-available !important;
    width: 100% !important;
  }
  .v-card--reveal {
    /*align-items: center;*/
    /*max-height: initial;*/
    bottom: 0;
    background-color: rgba(255,255,255, .5);
    justify-content: center;
    /*opacity: .5;*/
    position: absolute;
    width: 100%;
    /*height: 100%;*/
    z-index: 1;
  }
  .v-card--reveal-two {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .5;
    position: absolute;
    width: 100%;
    height: 100% !important;
    font-size: 40px !important;
  }

  @media (min-width: 0) and (max-width: 499px){
    .v-card--reveal-two {
      font-size: 1rem !important;
    }
  }

  @media (min-width: 500px) and (max-width: 614px) {
    .v-card--reveal-two {
      font-size: 1.5rem !important;
    }
  }

  @media (min-width: 615px) and (max-width: 750px){
    .v-card--reveal-two {
      font-size: 2rem !important;
    }
  }

  .parrafo {
    overflow-wrap: break-word !important;
    text-align: justify !important;
  }

  .ref_speed {
    position: relative;
    z-index: auto;
  }
  p {
    color: black !important;
  }
  .footFont {
    color: #424242 !important;
  }
  .tachado {
    text-decoration: line-through !important;
  }

  .noneBefore:before, .noneBefore:after {
    display: none !important;
  }

</style>
