<template>
  <v-card>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="400">
        <v-card>
          <v-card-text class="title font-weight-light">¿Esta seguro que desea confirmar el estado de la cuenta?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="confirmarEstado()">No</v-btn>
            <v-btn color="primary" @click.stop="confirmarEstado('yesConfirmar')">Si</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialog2" persistent max-width="400">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="title">¿Qué desea hacer?</span>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap class="justify-center">
              <v-flex xs4>
                <v-checkbox color="teal" off-icon="done" on-icon="done_all" default v-model="confirmar" :value="cr_cuentaxcobrar.estado == 'Confirmado' ? true : false"
                            :disabled="cr_cuentaxcobrar.estado == 'Anulado' ? true : false" :label="cr_cuentaxcobrar.estado != 'Anulado' ? 'Confirmar' : 'Anulado'" hide-details class="shrink mr-2"></v-checkbox>
                <!--label="Confirmar"-->
              </v-flex>
              <v-flex xs4>
                <v-checkbox color="black" v-if="cr_cuentaxcobrar.id != null" off-icon="far fa-file-pdf" on-icon="far fa-file-pdf" v-model="imprime" hide-details class="shrink mr-2" label="Imprimir"></v-checkbox>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" flat @click="enviarCambio()">Aceptar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialog3" persistent max-width="400">
        <v-card>
          <v-card-title class="grey lighten-3">
            <v-card-text class="title">¿Por qué anula esta cuenta?</v-card-text>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap>
              <v-flex xs12>
                <v-textarea
                  outline
                  v-model="detalle_anulacion"
                  color="primary"
                  name="Detalle de anulación"
                >
                  <div slot="label">
                    Detalle de anulación
                  </div>
                </v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <!--<v-btn flat @click.native="dialog = false">No</v-btn>-->
            <v-btn color="primary" flat @click="esAnulado(cr_cuentaxcobrar, detalle_anulacion)">Enviar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
      <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title> &nbsp;
      <v-spacer/>
      <h2 v-if="cr_cuentaxcobrar.id != null" class="title">Cuenta por Cobrar</h2>
      <div v-if="cr_cuentaxcobrar.id != null">
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="cr_cuentaxcobrar.consecutivo == null ? '0000' : cr_cuentaxcobrar.consecutivo"></span>
        </v-chip>
      </div>
    </v-toolbar>
    <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>
    <v-form data-vv-scope="formCuentaPorCobrar">
      <v-container fluid grid-list-md grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12 sm12 md9 lg9 xl9>
            <v-layout row wrap class="pa-0">
              <v-flex xs12 sm12 md6 lg6 xl6>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="tercero.nombre_completo"
                  itemtext="tercero.identificacion_completa"
                  datatitle="tercero.identificacion_completa"
                  datasubtitle="tercero.nombre_completo"
                  filter="tercero.identificacion_completa,tercero.nombre_completo"
                  label="Cliente"
                  scope="formCuentaPorCobrar"
                  ref="postulaCliente"
                  entidad="clientes"
                  preicon="person_pin"
                  @change="val => cr_cuentaxcobrar.cr_cliente_id = val"
                  @objectReturn="val => cr_cuentaxcobrar.cliente = val"
                  :value="cr_cuentaxcobrar.cliente"
                  clearable/>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6 xl6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  required
                  hint="nombre"
                  itemtext="codigo"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  :label="!cr_cuentaxcobrar.nf_niif_id ? 'cuenta' : 'Cuenta'"
                  scope="formCuentaPorCobrar"
                  ref="buscarCuentaxcobrar"
                  entidad="niifs"
                  preicon="list_alt"
                  routeparams="nivel:auxiliar=1"
                  @change="val => cr_cuentaxcobrar.nf_niif_id = val"
                  @objectReturn="val => cr_cuentaxcobrar.niif = val"
                  :value="cr_cuentaxcobrar.niif"
                  clearable
                  btnplustruncate
                  @click="openNuevaNiif(1)"
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6 xl6 class="pt-0">
                <v-autocomplete
                label="Centro de Costo"
                persistent-hint
                v-model="cr_cuentaxcobrar.cencosto"
                @change="val => cr_cuentaxcobrar.nf_cencosto_id = val ? val.id : null"
                :disabled="cr_cuentaxcobrar.niif ? (cr_cuentaxcobrar.niif.maneja_ccosto === 0 ? true : false) : null"
                :items="complementosFormCxc.cencostos"
                item-value="id"
                item-text="nombre"
                key="centro de costo"
                name="centro de costo"
                v-validate="'required'"
                :error-messages="errors.collect('centro de costo')"
                prepend-icon="center_focus_weak"
                required
                return-object
                clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4 xl4 class="pt-0" :class="{'md6 lg6 xl6': !cr_cuentaxcobrar.id}">
                <v-text-field :prefix="(cr_cuentaxcobrar.numero_factura !== null && cr_cuentaxcobrar.numero_factura !== '') ? 'N°' : ''" v-model="cr_cuentaxcobrar.numero_factura" key="factura"
                              name="factura" required v-validate="'required|max:50|not_in:' + numeroFacturaResgistrado" :disabled="!cr_cuentaxcobrar.cr_cliente_id"
                              :error-messages="errors.collect('factura')" label="No. Factura"></v-text-field>
              </v-flex>
              <v-flex v-if="cr_cuentaxcobrar.id" xs12 sm12 md2 lg2 xl2 class="pt-0">
                <v-select
                  :items="estados"
                  v-model="cr_cuentaxcobrar.estado"
                  label="Estado"
                  name="estado" key="estado"  :error-messages="errors.collect('estado')"></v-select>
              </v-flex>
              <v-flex xs12 sm12 class="pt-0">
                <v-textarea
                  v-model="cr_cuentaxcobrar.observaciones"
                  color="primary"
                  name="observaciones"
                  required
                  v-validate="'required'"
                  key="observaciones"
                  :error-messages="errors.collect('observaciones')">
                  <div slot="label">
                    Observaciones
                  </div>
                </v-textarea>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 sm12 md3 lg3 xl3>
            <v-flex xs12 sm12 md12 lg12 class="pa-1">
              <v-dialog
                ref="pickerFechaActual"
                v-model="pickerFechaActual"
                :return-value.sync="cr_cuentaxcobrar.fecha"
                persistent
                lazy
                full-width
                width="290px">
                <v-text-field
                  slot="activator"
                  v-model="cr_cuentaxcobrar.fecha"
                  label="Fecha Actual"
                  prepend-icon="event"
                  key="fecha actual"
                  v-validate="'required|date_format:YYYY-MM-DD'"
                  name="fecha actual"
                  :error-messages="errors.collect('fecha actual')"
                  readonly>
                </v-text-field>
                <v-date-picker
                  v-model="cr_cuentaxcobrar.fecha"
                  :min="fechaActual"
                  @change="() => {
                    let index = $validator.errors.items.findIndex(x => x.field === 'fecha actual')
                    $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                  }"
                  locale='es'
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="pickerFechaActual = false">Cancelar</v-btn>
                  <v-btn flat color="primary" @click="$refs.pickerFechaActual.save(cr_cuentaxcobrar.fecha)">OK</v-btn>
                </v-date-picker>
              </v-dialog>
            </v-flex>
            <v-flex xs12 sm12 md12 lg12 class="pa-1" >
              <v-dialog
                ref="pickerFechaFactura"
                v-model="pickerFechaFactura"
                :return-value.sync="cr_cuentaxcobrar.fecha_factura"
                persistent
                lazy
                full-width
                width="290px">
                <v-text-field
                  slot="activator"
                  v-model="cr_cuentaxcobrar.fecha_factura"
                  label="Fecha de la Factura"
                  prepend-icon="event"
                  key="fecha factura"
                  data-vv-delay="1000"
                  v-validate="'required|date_format:YYYY-MM-DD'"
                  name="fecha factura"
                  :error-messages="errors.collect('fecha factura')"
                  readonly>
                </v-text-field>
                <v-date-picker v-model="cr_cuentaxcobrar.fecha_factura" :min="fechaActual" locale='es' scrollable>
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="pickerFechaFactura = false">Cancelar</v-btn>
                  <v-btn flat color="primary" @click="$refs.pickerFechaFactura.save(cr_cuentaxcobrar.fecha_factura)">OK</v-btn>
                </v-date-picker>
              </v-dialog>
            </v-flex>
            <v-flex xs12 sm12 md12 lg12 class="pa-1 pt-3 mb-2">
              <v-text-field label="Plazo" key="plazo" class="mt-0 pt-0" prepend-icon="today"
                            v-validate="'required'" name="plazo"
                            :error-messages="errors.collect('plazo')" min="0" v-model.number="cr_cuentaxcobrar.plazo"
                            :suffix="cr_cuentaxcobrar.plazo ? ( cr_cuentaxcobrar.plazo !== 1 ? 'días' : 'día' ) : ''" ></v-text-field>
            </v-flex>
            <!--@blur="calcularPlazo" @keyup.enter="calcularPlazo"-->
            <v-flex xs12 sm12 md12 lg12 class="pa-1 pt-3">
              <v-text-field prepend-icon="event" label="Fecha de Vencimiento"
                            key="fecha vencimiento" class="mt-0 pt-0"
                            v-validate="'required'" name="fecha vencimiento" disabled
                            :error-messages="errors.collect('fecha vencimiento')"
                            v-model="cr_cuentaxcobrar.fecha_vencimiento"
                            readonly required></v-text-field>
            </v-flex>
          </v-flex>
        </v-layout>
        <v-divider></v-divider>
        <v-layout row wrap>
          <v-flex xs12 sm12 md4 lg4 class="pb-0">
            <v-text-field :prefix="(cr_cuentaxcobrar.debitos !== 0) ? '$' : ''"
                          label="Valor Débito"
                          :value="cr_cuentaxcobrar.debitos | numberFormat(2)"
                          box readonly required></v-text-field>
            <!--v-model="cr_cuentaxcobrar.debitos"-->
          </v-flex>
          <v-flex xs12 sm12 md4 lg4 class="pb-0">
            <v-text-field :prefix="(cr_cuentaxcobrar.creditos !== 0) ? '$' : ''"
                          label="Valor Crédito"
                          :value="cr_cuentaxcobrar.creditos | numberFormat(2)"
                          box readonly required></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 md4 lg4 class="pb-0">
            <v-text-field :prefix="(cr_cuentaxcobrar.debitos !== 0) ? '$' : ''"
                          label="Valor Total"
                          :value="cr_cuentaxcobrar.valor | numberFormat(2)"
                          box readonly required></v-text-field>
          </v-flex>
        </v-layout>
        <v-divider></v-divider>
      </v-container>
    </v-form>
    <v-container fluid grid-list-xl class="pt-1">
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12 class="text-xs-right pa-0">
          <v-btn @click="openCardDialog" color="primary" dark v-text="'Agregar Detalle'"></v-btn>
          <v-slide-x-reverse-transition>
            <v-card v-show="cardDialog" class="mt-3">
              <v-card-title class="grey lighten-3">
                <span class="title" v-text="tituloDetalle"></span>
              </v-card-title>
              <v-form data-vv-scope="formCxcdetalle">
                <v-container fluid grid-list-md grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md6 xl6>
                      <postulador
                      nodata="Busqueda por nombre o código."
                      required
                      hint="codigo"
                      itemtext="nombre"
                      datatitle="codigo"
                      datasubtitle="nombre"
                      filter="codigo,nombre,tipo_concepto"
                      :label="!detalle.cr_concepto_id ? 'concepto cartera' : 'Concepto Cartera'"
                      scope="formCxcdetalle"
                      ref="postulaConcepto"
                      entidad="conceptoscartera"
                      preicon="subtitles"
                      @change="val => detalle.cr_concepto_id = val"
                      @objectReturn="val => detalle.concepto = val"
                      :value="detalle.concepto"
                      clearable/>
                    </v-flex>
                    <v-flex xs12 sm12 md6 xl6>
                      <postulador
                        nodata="Busqueda por código o nombre."
                        required
                        hint="nombre"
                        itemtext="codigo"
                        datatitle="codigo"
                        datasubtitle="nombre"
                        filter="codigo,nombre"
                        :label="!detalle.nf_niif_id ? 'cuenta' : 'Cuenta'"
                        scope="formCxcdetalle"
                        ref="postulaCuentaconcepto"
                        entidad="niifs"
                        preicon="list_alt"
                        routeparams="nivel:auxiliar=1"
                        @change="val => detalle.nf_niif_id = val"
                        @objectReturn="val => detalle.niif = val"
                        :value="detalle.niif"
                        clearable
                        btnplustruncate
                        @click="openNuevaNiif(2)"
                      ></postulador>
                    </v-flex>
                    <v-flex xs12 sm12 md12 xl12 v-if="detalle.cr_concepto_id !== null">
                      <v-slide-y-transition>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md6 xl6>
                            <postulador
                              nodata="Busqueda por nombre o número de documento."
                              required
                              :disabled="detalle.concepto && detalle.niif ? (detalle.concepto.niif.maneja_tercero || detalle.niif.maneja_tercero ? false : true) : null"
                              hint="nombre_completo"
                              itemtext="identificacion_completa"
                              datatitle="identificacion_completa"
                              datasubtitle="nombre_completo"
                              filter="identificacion_completa,nombre_completo"
                              :label="detalle.gn_tercero_id == null ? 'tercero' : 'Tercero'"
                              scope="formCxcdetalle"
                              ref="postulaTercero"
                              entidad="terceros"
                              preicon="person"
                              @change="val => detalle.gn_tercero_id = val"
                              @objectReturn="val => detalle.tercero = val"
                              :value="detalle.tercero"
                              clearable
                            />
                          </v-flex>
                          <v-flex xs12 sm12 md6 xl6>
                            <v-autocomplete
                              label="Centro de Costo"
                              v-model="detalle.cencosto"
                              @change="val => detalle.nf_cencosto_id = val ? val.id : null"
                              :items="complementosFormCxc.cencostos"
                              item-value="id"
                              item-text="nombre"
                              name="centro de costo"
                              required
                              :disabled="detalle.niif ? !detalle.niif.maneja_ccosto : ''"
                              v-validate="'required'"
                              :error-messages="errors.collect('centro de costo')"
                              return-object
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md6 xl6>
                            <v-text-field v-if="detalle.niif ? detalle.niif.tipo !== 'Retencion' : null"
                                          :prefix="(detalle.valor !== null && detalle.valor !== '') ? '$' : ''" v-model.number="detalle.valor" key="valor base"
                                          name="valor base" required v-validate="'required'" min="0"
                                          :error-messages="errors.collect('valor base')" label="Valor Base"></v-text-field>
                            <v-text-field v-else
                                          :prefix="(detalle.valor !== null && detalle.valor !== '') ? '$' : ''" :value="detalle.valor | numberFormat(2)" key="valor base"
                                          name="valor base" required v-validate="'required'" min="0" :disabled="!detalle.cr_concepto_id ? true : false"
                                          :error-messages="errors.collect('valor base')" label="Valor Base"></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-slide-y-transition>
                    </v-flex>
                    <v-flex xs12 sm12 md12 xl12 class="pl-0 pr-0" v-if="detalle.niif ? detalle.niif.tipo === 'Retencion' : null">
                      <v-slide-y-transition>
                        <v-card class="elevation-0">
                          <v-card-title class="grey lighten-2">
                            <span class="title">Concepto de Retención</span>
                          </v-card-title>
                          <v-container fluid grid-list-md grid-list-xl  class="pa-3">
                            <v-layout row wrap>
                              <v-flex xs12 sm12 md8 xl8>
                                <v-autocomplete
                                  :label="detalle.nf_conrtf_id === null ? 'Buscar concepto de retención' : 'Concepto de Retención'"
                                  v-model="detalle.conrtf"
                                  @change="val => detalle.nf_conrtf_id = val ? val.id : null"
                                  :items="complementosFormCxc.conrtfs"
                                  item-value="id"
                                  item-text="codigo_nombre"
                                  name="concepto de retención"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('concepto de retención')"
                                  return-object
                                  clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.codigo_nombre"/>
                                        <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>
                              <v-flex xs6 sm6 md4 xl4>
                                <v-text-field readonly :value="detalle.nf_conrtf_id ? detalle.conrtf.porcentaje : null" suffix="%" label="Porcentaje"></v-text-field>
                              </v-flex>
                              <v-flex xs6 sm6 md6 xl6>
                                <!--<input-number :value="valorBase" :label="'Valor Base'" v-validate="'required'" errormessage="valor base" :disabled="detalle.conrtf !== null ? (detalle.conrtf.porcentaje !== null ? false : true) : true"></input-number>-->
                                <v-text-field
                                  prefix="$"
                                  label="Valor Base"
                                  v-model="valorBase"
                                  :disabled="detalle.conrtf !== null ? (detalle.conrtf.porcentaje !== null ? false : true) : true "
                                  required
                                  v-validate="'required'"
                                  key="valor base"
                                  name="valor base"
                                  :error-messages="errors.collect('valor base')"
                                ></v-text-field>
                              </v-flex>
                              <v-flex xs6 sm6 md6 xl6>
                                <v-text-field
                                  prefix="$"
                                  label="Valor retención"
                                  :value="valorRetencion | numberFormat(2)"
                                  required
                                  readonly
                                  v-validate="'required'"
                                  key="valor retención"
                                  name="valor retención"
                                  :error-messages="errors.collect('valor retención')"
                                ></v-text-field>
                              </v-flex>
                            </v-layout>
                          </v-container>
                        </v-card>
                      </v-slide-y-transition>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
              <v-card-actions>
                <v-divider></v-divider>
                <v-btn flat @click="dialogDetalle()">Cerrar</v-btn>
                <v-btn color="blue darken-1" @click.prevent="agregarCxcDetalle(detalle)" flat > {{ !detalle.id ? 'Agregar' : 'Actualizar' }}</v-btn>
              </v-card-actions>
            </v-card>
          </v-slide-x-reverse-transition>
        </v-flex>
        <v-dialog v-model="dialogEliminar" max-width="400">
          <v-card>
            <v-card-text class="title font-weight-light">¿Desea eliminar el detalle de la cuenta?</v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat @click="dialogEliminar = false">No</v-btn>
              <v-btn color="primary" @click="eliminarDetalle(detalle)">Si</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-flex xs12 sm12 md12 lg12 class="pr-0 pl-0" v-if="cr_cuentaxcobrar.detalles.length">
          <v-expand-transition>
            <v-card>
              <v-card-title class="grey lighten-3">
                <h3>Detalles de la Cuenta por Cobrar</h3>
              </v-card-title>
              <v-divider></v-divider>
              <v-data-table
                :headers="headers"
                :items="cr_cuentaxcobrar.detalles"
                :loading="tableLoading"
                :pagination.sync="pagination"
                hide-actions rows-per-page-text="Registros por página"
                :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                <template slot="items" slot-scope="props">
                  <td class="text-xs-left">{{ props.item.concepto ? props.item.concepto.codigo : null }}</td>
                  <td class="text-xs-left">{{ props.item.concepto ? props.item.concepto.nombre : null }}</td>
                  <td class="text-xs-left">{{ props.item.niif ? props.item.niif.clascuenta.naturaleza : null}}</td>
                  <td class="text-xs-left">{{ props.item.niif ? props.item.niif.codigo : null}}</td>
                  <td class="text-xs-right">{{ props.item.valor | numberFormat(2, '$')}}</td>
                  <td>
                    <v-tooltip top v-show="props.item.id">
                      <v-btn icon class="mx-0" slot="activator" @click="editarDetalle(props.item)">
                        <v-icon color="accent">edit</v-icon>
                      </v-btn>
                      <span>Editar</span>
                    </v-tooltip>
                    <v-tooltip top v-show="!props.item.id">
                      <v-btn icon class="mx-0" slot="activator" @click="eliminarConceptoCuenta(props)">
                        <v-icon color="danger">delete_forever</v-icon>
                      </v-btn>
                      <span>Eliminar</span>
                    </v-tooltip>
                  </td>
                </template>
                <template slot="no-data">
                  <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
                    Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                  </v-alert>
                  <v-alert v-else :value="true" color="info" icon="info">
                    Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                  </v-alert>
                </template>
                <template slot="pageText" slot-scope="props">
                  {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
              </v-data-table>
            </v-card>
          </v-expand-transition>
        </v-flex>
      </v-layout>
    </v-container>
    <v-divider></v-divider>
    <v-card-actions class="grey lighten-4">
      <v-layout row wrap>
        <v-flex xs6 class="text-xs-left">
          <v-btn @click="refresh(null)" v-text="'Limpiar'"></v-btn>
        </v-flex>
        <v-flex xs6 class="text-xs-right">
          <v-btn @click="close()" v-text="'Cancelar'"></v-btn>
          <v-btn color="primary" @click.prevent="confirmarSave" v-text="'Guardar'"></v-btn>
        </v-flex>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>
<script>
  import store from '../../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CXC_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import moment from 'moment'
  export default {
    name: 'RegistroCuentaPorCobrar',
    components: {
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['parametros'],
    data () {
      return {
        cxcs: [],
        search: '',
        fechaActual: '2018-01-01',
        pickerFechaActual: false,
        pickerFechaFactura: false,
        indexUpdateConceptoCxc: 0,
        dialogFormNiif: false,
        confirmar: false,
        imprime: false,
        dialog: false,
        dialog2: false,
        dialog3: false,
        dialogEliminar: false,
        detalle_anulacion: null,
        cuentaNiff: null,
        niifNueva: null,
        cardDialog: false,
        tableLoading: false,
        numeroFacturaResgistrado: null,
        valorBase: 0,
        valorRetencion: 0,
        valorFacturado: null,
        estados: ['Registrado', 'Confirmado', 'Anulado'],
        cr_cuentaxcobrar: {
          id: null,
          consecutivo: null,
          observaciones: null,
          estado: null,
          fecha: this.moment().format('YYYY-MM-DD'),
          fecha_factura: this.moment().format('YYYY-MM-DD'),
          fecha_vencimiento: null,
          plazo: null,
          numero_factura: null,
          valor: 0,
          creditos: 0,
          debitos: 0,
          nf_cencosto_id: null,
          nf_niif_id: null,
          cr_cliente_id: null,
          cliente: null,
          niif: null,
          cencosto: null,
          detalles: []
        },
        detalle: {},
        pickerFechaActivoNuevo: false,
        date: '',
        pagination: {},
        headers: [
          { text: 'Concepto', value: 'concepto.codigo', align: 'left', sortable: false },
          { text: 'Descripcion', value: 'concepto.nombre', align: 'left', sortable: false },
          { text: 'Naturaleza', value: 'naturaleza', align: 'left', sortable: false },
          { text: 'Cuenta', value: 'niif.codigo', align: 'left', sortable: false },
          { text: 'Valor concepto', value: 'valor', align: 'left', sortable: false },
          { text: 'Opciones', value: 'id', align: 'left', sortable: false }
        ]
      }
    },
    computed: {
      tituloDetalle () {
        return !this.detalle.id ? 'Nuevo Detalle de Cuenta por Cobrar' : 'En Edición del Detalle con Concepto N° ' + (this.detalle.concepto ? this.detalle.concepto.codigo : ' ####')
      },
      complementosFormCxc () {
        return store.getters.complementosFormComDiario
      },
      tapTitulo () {
        return !this.cr_cuentaxcobrar.id ? 'Nueva Cuenta por Cobrar' : 'En Edición'
      }
    },
    watch: {
      'cr_cuentaxcobrar.cliente' (val) {
        // console.log('Val', val)
        this.cr_cuentaxcobrar.niif = val ? val.niif : null
        this.cr_cuentaxcobrar.plazo = val ? parseInt(val.plazo) : null
      },
      'cr_cuentaxcobrar.plazo' (val) {
        this.cr_cuentaxcobrar.fecha_vencimiento = val ? this.calcularPlazo(this.cr_cuentaxcobrar.fecha_factura, val) : null
      },
      'cr_cuentaxcobrar.fecha' (val) {
        var convert1 = this.parseDate(val)
        if (convert1 != null) this.cr_cuentaxcobrar.fecha = convert1
      },
      'cr_cuentaxcobrar.fecha_factura' (val) {
        var convert2 = this.parseDate(val)
        if (convert2 != null) this.cr_cuentaxcobrar.fecha_factura = convert2
        let plazo = parseInt(this.cr_cuentaxcobrar.plazo)
        this.cr_cuentaxcobrar.fecha_vencimiento = val ? this.calcularPlazo(val, plazo) : null
      },
      'cr_cuentaxcobrar.detalles' (vals) {
        this.cr_cuentaxcobrar.debitos = 0
        this.cr_cuentaxcobrar.creditos = 0
        vals.forEach((val) => {
          if (val.concepto.niif.clascuenta.naturaleza !== 'Crédito') {
            this.cr_cuentaxcobrar.debitos = this.cr_cuentaxcobrar.debitos + val.valor
          } else {
            this.cr_cuentaxcobrar.creditos = this.cr_cuentaxcobrar.creditos + val.valor
          }
        })
        this.cr_cuentaxcobrar.valor = this.cr_cuentaxcobrar.debitos - this.cr_cuentaxcobrar.creditos
        if (this.cr_cuentaxcobrar.valor < 0) {
          this.cr_cuentaxcobrar.valor = 0
        } else {
          return this.cr_cuentaxcobrar.valor
        }
      },
      'detalle.concepto' (val) {
        // console.log('valores', val)
        if (val === null) {
          this.$validator.reset()
          this.formReset()
        } else {
          this.detalle.niif = val ? val.niif : null
          this.detalle.nf_niif_id = val ? val.niif.id : null
        }
      },
      'detalle.niif' (val) {
        this.detalle.tercero = (val && val.maneja_tercero) ? this.cr_cuentaxcobrar.cliente.tercero : null
        this.detalle.cencosto = (val && val.maneja_ccosto) ? this.detalle.cencosto : null
      },
      'detalle.tercero' (val) {
        if (val === null) this.$validator.reset()
      },
      'valorBase' (val) {
        if (val) {
          let porcent = this.detalle.conrtf ? this.detalle.conrtf.porcentaje : null
          this.valorRetencion = val * (porcent / 100)
          this.detalle.valor = this.valorRetencion
        }
      },
      'cardDialog' (val) { if (val === false) { this.dialogDetalle(val) } },
      'dialogFormNiif' (val) { if (!val) this.niifNueva = null },
      'detalle.conrtf' (val) {
        if (val === null) {
          this.$validator.reset()
        } else {
          if (val.porcentaje === '') {
            this.$store.commit(SNACK_SHOW, {msg: 'El concepto de retención no cuenta con un porcentaje. ', color: 'danger'})
          }
        }
      }
    },
    methods: {
      refresh () {
        this.$validator.reset()
        this.cr_cuentaxcobrar = JSON.parse(JSON.stringify(this.cr_cuentaxcobrar))
      },
      close () {
        this.formReset()
        this.refresh()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      openNuevaNiif (val) {
        this.niifNueva = val
        this.dialogFormNiif = true
      },
      goNiif (val) {
        if (this.niifNueva === 1) {
          this.cr_cuentaxcobrar.niif = val
        } else if (this.niifNueva === 2) {
          this.detalle.niif = val
        }
        this.dialogFormNiif = false
      },
      getRegistro (id) {
        this.axios.get('cr_cuentasxcobrars/' + id)
          .then((response) => {
            // console.log('res', response)
            this.cr_cuentaxcobrar = response.data.data
            this.numeroFacturaResgistrado = this.getNumFacturaUsado()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {msg: 'Hay un error al traer la cuenta por cobrar.', color: 'danger'})
          })
      },
      calcularPlazo (fecha, plazo) { if (fecha && plazo) { return moment(fecha).add(plazo, 'd').format('YYYY-MM-DD') } },
      getNumFacturaUsado () {
        return this.cxcs.map(cod => { if (!(this.cr_cuentaxcobrar.numero_factura != null && this.cr_cuentaxcobrar.numero_factura === cod.numero_factura)) return cod.numero_factura })
      },
      openCardDialog () {
        if (this.cr_cuentaxcobrar.cr_cliente_id) {
          this.cardDialog = !this.cardDialog
        } else {
          this.cardDialog = false
          this.$store.commit(SNACK_SHOW, {msg: 'Debe seleccionar un cliente.', color: 'error'})
        }
      },
      dialogDetalle (val) {
        if (val === 'abrir') {
          this.cardDialog = true
        } else {
          this.cardDialog = false
          this.formReset()
          this.$validator.reset()
        }
      },
      editarDetalle (item) {
        this.cardDialog = true
        this.detalle = JSON.parse(JSON.stringify(item))
      },
      eliminarDetalle (detalle) {
        this.dialogEliminar = false
        this.cr_cuentaxcobrar.detalles.splice(this.cr_cuentaxcobrar.detalles.indexOf(detalle), 1)
        this.formReset()
      },
      async agregarCxcDetalle (detalle) {
        // console.log('val', detalle)
        let erroConcepto = await this.$refs.postulaConcepto.validate()
        let erroCuentaconcepto = await this.$refs.postulaCuentaconcepto.validate()
        let errorTercero = await this.$refs.postulaTercero.validate()
        if (await this.validador('formCxcdetalle') && erroConcepto && erroCuentaconcepto && errorTercero) {
          if (detalle.id) {
            this.cr_cuentaxcobrar.detalles.splice(this.cr_cuentaxcobrar.detalles.findIndex(item => item.id === detalle.id), 1)
            this.cr_cuentaxcobrar.detalles.push(detalle)
          } else {
            this.cr_cuentaxcobrar.detalles.push(detalle)
          }
          this.formReset()
        } else {
          console.log('erroor')
          if (this.detalle.cr_concepto_id == null) {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado el concepto.', color: 'error'})
          } else if (this.detalle.nf_niif_id == null) {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado la cuenta del concepto.', color: 'error'})
          } else if (this.detalle.concepto.niif.clascuenta.naturaleza === null) {
            this.$store.commit(SNACK_SHOW, {msg: 'La cuenta no presenta naturaleza.', color: 'error'})
          }
        }
      },
      confirmarEstado (val) {
        if (val === 'yesConfirmar') {
          if (this.confirmar && this.imprime) {
            this.cr_cuentaxcobrar.estado = 'Confirmado'
            this.imprimirCuenta()
          } else {
            this.cr_cuentaxcobrar.estado = 'Confirmado'
          }
          this.dialog = false
          this.dialog2 = false
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta ha sido Confirmado', color: 'success'})
        } else {
          if (this.imprime) {
            this.imprimirCuenta()
          }
          this.confirmar = false
          this.dialog = false
          this.dialog2 = false
          this.cr_cuentaxcobrar.estado = 'Registrado'
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta ha sido Registrado', color: 'success'})
        }
      },
      esAnulado (comdiario, detalleAnulacion) {
        this.dialog3 = false
        this.dialog2 = false
        comdiario.detalle_anulacion = detalleAnulacion
        this.save()
      },
      enviarCambio () {
        if (this.confirmar) {
          this.dialog = true
        } else if (this.confirmar && this.imprime) {
          this.dialog = true
        } else if (this.imprime) {
          this.cr_cuentaxcobrar.estado = 'Registrado'
          this.imprimirCuenta()
          this.save()
        } else if (this.cr_cuentaxcobrar.estado === 'Anulado') {
          this.dialog3 = true
        } else if (this.cr_cuentaxcobrar.estado === 'Anulado' && this.imprime) {
          this.dialog3 = true
        } else {
          this.cr_cuentaxcobrar.estado = 'Registrado'
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta ha sido Registrado', color: 'success'})
        }
      },
      save () {
        const typeRequest = this.cr_cuentaxcobrar.id ? 'editar' : 'crear'
        this.axios.post('cr_cuentasxcobrars', this.cr_cuentaxcobrar).then(response => {
          console.log('LOGO', response)
          this.$store.commit(SNACK_SHOW, {
            msg: `Se ${this.cr_cuentaxcobrar.id
              ? 'actualizaron los datos'
              : 'realizó el registro'} correctamente de la No. CXC ${response.data.data.numero_factura}.`,
            color: 'success'
          })
          this.$store.commit(CXC_RELOAD_ITEM, {
            cr_cuentaxcobrar: response.data.data,
            estado: typeRequest,
            key: this.parametros.key
          })
          this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {
            msg: 'Hay un error al guardar el registro. ' + e.response,
            color: 'danger'
          })
        })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      parseDate (date) {
        if (!date) return null
        if (date.split('/').length !== 3) return null
        const [month, day, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
      async confirmarSave () {
        let errorCliente = await this.$refs.postulaCliente.validate()
        let errorCuenta = await this.$refs.buscarCuentaxcobrar.validate()
        if (await this.validador('formCuentaPorCobrar') && errorCliente && errorCuenta && this.cr_cuentaxcobrar.detalles > 1) {
          this.dialog2 = true
        } else {
          if (!this.cr_cuentaxcobrar.detalles.length) {
            this.$store.commit(SNACK_SHOW, {msg: 'Se deben agregar al menos dos detalles.', color: 'error'})
          } else if (this.cr_cuentaxcobrar.creditos > this.cr_cuentaxcobrar.debitos) {
            this.$store.commit(SNACK_SHOW, {msg: 'El valor crédito no puede ser superior al del débito.', color: 'error'})
          } else {
            this.dialog2 = true
          }
        }
      },
      calcularValorCxc () {
        this.cr_cuentaxcobrar.creditos = 0
        this.cr_cuentaxcobrar.debitos = 0
        this.cr_cuentaxcobrar.valor = 0
        for (var i = 0; i < this.conceptosCuentasPorCobrar.length; i++) {
          if (this.conceptosCuentasPorCobrar[i].naturaleza === 'Crédito') {
            this.cr_cuentaxcobrar.creditos = parseInt(this.cr_cuentaxcobrar.creditos) + parseInt(this.conceptosCuentasPorCobrar[i].valor)
            this.cr_cuentaxcobrar.valor = parseInt(this.cr_cuentaxcobrar.valor) - parseInt(this.conceptosCuentasPorCobrar[i].valor)
          }
          if (this.conceptosCuentasPorCobrar[i].naturaleza === 'Débito') {
            this.cr_cuentaxcobrar.debitos = parseInt(this.cr_cuentaxcobrar.debitos) + parseInt(this.conceptosCuentasPorCobrar[i].valor)
            this.cr_cuentaxcobrar.valor = parseInt(this.cr_cuentaxcobrar.valor) + parseInt(this.conceptosCuentasPorCobrar[i].valor)
          }
        }
      },
      eliminarConceptoCuenta (val) {
        this.conceptosCuentasPorCobrar.splice(val.index, 1)
        this.calcularValorCxc()
      },
      edit (cxc, conceptosCxc) {
        this.ordenEdit = true
        this.cr_cuentaxcobrar = cxc
        this.conceptosCuentasPorCobrar = conceptosCxc
      },
      formReset () {
        this.detalle = {
          id: null,
          cr_concepto_id: null,
          cr_cuentaxcobrar_id: null,
          gn_tercero_id: null,
          nf_cencosto_id: null,
          nf_conrtf_id: null,
          nf_niif_id: null,
          valor: null,
          niif: null,
          tercero: null,
          cencosto: null,
          conrtf: null,
          concepto: null
        }
      }
    },
    formCuenta () {
      this.pickerFechaActual = false
      this.pickerFechaFactura = false
      this.fechaActual = moment().format('YYYY-MM-DD')
      this.cr_cuentaxcobrar.fecha = moment().format('YYYY-MM-DD')
      this.cr_cuentaxcobrar.fecha_factura = moment().format('YYYY-MM-DD')
      this.ordenEdit = false
    },
    beforeCreate () {
      this.axios.get('cr_cuentasxcobrars')
        .then((res) => {
          this.cxcs = res.data.data
          this.numeroFacturaResgistrado = this.getNumFacturaUsado()
        }).catch(() => {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las cuentas por cobrar. ', color: 'danger'})
        })
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      const dict = {
        custom: {
          factura: {
            not_in: 'El número factura ya se encuentra registrado.'
          }
        }
      }
      this.$validator.localize('es', dict)
    },
    beforeMount () {
      this.formReset()
      this.fechaActual = moment().format('YYYY-MM-DD')
      this.cr_cuentaxcobrar.fecha = moment().format('YYYY-MM-DD')
      this.cr_cuentaxcobrar.fecha_factura = moment().format('YYYY-MM-DD')
    }
  }
</script>

<style scoped>

</style>
