<template>
  <div>
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
                <v-checkbox color="teal" off-icon="done" on-icon="done_all" default v-model="confirmar" :value="cxp.estado == 'Confirmado' ? true : false"
                            :disabled="cxp.estado == 'Anulado' ? true : false" :label="cxp.estado != 'Anulado' ? 'Confirmar' : 'Anulado'" hide-details class="shrink mr-2"></v-checkbox>
                <!--label="Confirmar"-->
              </v-flex>
              <v-flex xs4>
                <v-checkbox color="black" v-if="cxp.id != null" off-icon="far fa-file-pdf" on-icon="far fa-file-pdf" v-model="imprime" hide-details class="shrink mr-2" label="Imprimir"></v-checkbox>
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
            <v-btn color="primary" flat @click="esAnulado(cxp, detalle_anulacion)">Enviar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
      <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title> &nbsp;
      <!--<small v-if="cxp.id != null" class="pt-1 pb-0"> Estado: {{ cxp.estado }}</small>-->
      <v-spacer/>
      <h2 v-if="cxp.id != null" class="title">Cuenta por Pagar</h2>
      <div v-if="cxp.id != null">
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="cxp.consecutivo == null ? '0000' : str_pad(cxp.consecutivo, 8)"></span>
        </v-chip>
        <v-chip v-if="cxp.id && (cxp.estado !== null)" :color="colorEstado" text-color="white">
          <v-avatar >
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ cxp.estado }}
        </v-chip>
      </div>
    </v-toolbar>
    <!--<div style="width: 100%" class="text-xs-right">-->
    <!--&lt;!&ndash;<v-spacer></v-spacer>&ndash;&gt;-->
    <!--</div>-->
    <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>
    <v-container fluid grid-list-md grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12>
          <v-card>
            <v-form data-vv-scope="formPrincipal">
              <v-container fluid grid-list-md grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md4 xl4>
                    <postulador-v2
                      no-data="Busqueda por nombre o número de documento."
                      hint="tercero.nombre_completo"
                      item-text="tercero.identificacion_completa"
                      data-title="tercero.identificacion_completa"
                      data-subtitle="tercero.nombre_completo"
                      :label="cxp.pg_proveedore_id == null ? 'proveedor' : 'Proveedor'"
                      entidad="pg_proveedores"
                      preicon="local_shipping"
                      @changeid="val => cxp.pg_proveedore_id = val"
                      v-model="cxp.proveedor"
                      name="proveedor"
                      rules="required"
                      :noBtnCreate="cxp.estado === 'Confirmado'"
                      btn-create-truncate
                      @create="val => openCreateProveedor(val)"
                      v-validate="'required'"
                      :error-messages="errors.collect('proveedor')"
                      :clearable="cxp.estado === 'Registrado' || cxp.estado === null"
                      :disabled="cxp.estado === 'Confirmado'"
                    />
<!--                    <postulador-->
<!--                    v-if="!referencia.id || referencia.estado === 'Iniciada'"-->
                    <!--                      nodata="Busqueda por nombre o número de documento."-->
<!--                      required-->
<!--                      hint="tercero.nombre_completo"-->
<!--                      itemtext="tercero.identificacion_completa"-->
<!--                      datatitle="tercero.identificacion_completa"-->
<!--                      datasubtitle="tercero.nombre_completo"-->
<!--                      filter="tercero.identificacion_completa,tercero.nombre_completo"-->
<!--                      :label="cxp.pg_proveedore_id == null ? 'proveedor' : 'Proveedor'"-->
<!--                      scope="formPrincipal"-->
<!--                      lite-->
<!--                      ref="postulaProveedor"-->
<!--                      entidad="pg_proveedores"-->
<!--                      preicon="local_shipping"-->
<!--                      @change="val => cxp.pg_proveedore_id = val"-->
<!--                      @objectReturn="val => cxp.proveedor = val"-->
<!--                      :value="cxp.proveedor"-->
<!--                      clearable-->
<!--                    />-->
                  </v-flex>
                  <v-flex xs12 sm12 md5 xl5>
                    <postulador
                      nodata="Busqueda por código o nombre."
                      required
                      hint="nombre"
                      itemtext="codigo"
                      datatitle="codigo"
                      datasubtitle="nombre"
                      filter="codigo,nombre"
                      :label="!cxp.nf_niif_id ? 'cuenta' : 'Cuenta'"
                      scope="formPrincipal"
                      ref="buscarCuenta"
                      entidad="niifs"
                      preicon="list_alt"
                      routeparams="nivel:auxiliar=1"
                      @change="val => cxp.nf_niif_id = val"
                      @objectReturn="val => cxp.niif = val"
                      :value="cxp.niif"
                      :clearable="cxp.estado === 'Registrado' || cxp.estado === null"
                      btnplustruncate
                      :disabled="cxp.estado === 'Confirmado'"
                      @click="openNuevaNiif(1)"
                    ></postulador>
                  </v-flex>
                  <v-flex xs12 sm12 md3 lg3>
                    <v-menu
                      ref="menuDate"
                      :close-on-content-click="false"
                      v-model="menuDate"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      :disabled="cxp.estado === 'Confirmado'"
                    >
                      <!--:return-value.sync="date"-->
                      <v-text-field
                        slot="activator"
                        v-model="computedDateFormatted"
                        label="Fecha causación"
                        prepend-icon="event"
                        readonly
                        :disabled="cxp.estado === 'Confirmado'"
                        name="fecha causación"
                        required
                        v-validate="'required|date_format:YYYY/MM/DD'"
                        :error-messages="errors.collect('fecha causación')"
                      >
                      </v-text-field>
                      <v-date-picker
                        v-model="cxp.fecha_causacion"
                        @input="menuDate = false"
                        locale='es'
                        no-title
                        :max="maxDate"
                        @change="() => {
                                        let index = $validator.errors.items.findIndex(x => x.field === 'fecha causación')
                                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                      }"
                      ></v-date-picker>
                      <!--@input="$refs.menu2.save(date) disabled"-->
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 sm12 md4 xl4>
                    <v-autocomplete
                      label="Centro de Costo"
                      v-model="cxp.cencosto"
                      @change="val => cxp.nf_cencosto_id = val ? val.id : null"
                      :items="complementos.cencostos"
                      item-value="id"
                      item-text="nombre"
                      name="centro de costo"
                      required
                      :disabled="cxp.niif ? ((cxp.niif.maneja_ccosto !== 1) || (cxp.estado === 'Confirmado') ? true : false) : null"
                      v-validate="'required'"
                      :error-messages="errors.collect('centro de costo')"
                      return-object
                      :clearable="cxp.estado === 'Registrado' || cxp.estado === null">
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.nombre"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm12 md2 xl2 v-if="cxp.modulo !== 'CXP'">
                    <v-select
                      :items="modulos"
                      v-model="cxp.modulo"
                      disabled
                      label="Módulo"
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm12 md3 xl3 :class="{'md5 xl5': cxp.modulo === 'CXP' }">
                    <v-text-field
                      :prefix="(cxp.no_factura !== null && cxp.no_factura !== '') ? 'N°' : ''"
                      v-model="cxp.no_factura" key="factura"
                      name="factura" v-validate="'required|max:20|not_in:' + numeroFacturaResgistrado"
                      :disabled="!cxp.pg_proveedore_id || cxp.estado === 'Confirmado' ? true : false"
                      :error-messages="errors.collect('factura')" label="No. Factura"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md3 xl3>
                    <v-menu
                      ref="menuDate2"
                      :close-on-content-click="false"
                      v-model="menuDate2"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      :disabled="cxp.estado === 'Confirmado'"
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <!--:return-value.sync="date"-->
                      <v-text-field
                        slot="activator"
                        v-model="computedDateFormatted2"
                        label="Fecha factura"
                        prepend-icon="event"
                        readonly
                        :disabled="cxp.estado === 'Confirmado'"
                        name="fecha factura"
                        required
                        v-validate="'required|date_format:YYYY/MM/DD'"
                        :error-messages="errors.collect('fecha factura')"
                      ></v-text-field>
                      <v-date-picker
                        v-model="cxp.fecha_factura"
                        @input="menuDate2 = false"
                        locale='es'
                        no-title
                        :max="maxDate"
                        @change="() => {
                                        let index = $validator.errors.items.findIndex(x => x.field === 'fecha factura')
                                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                      }"
                      ></v-date-picker>
                      <!--@input="$refs.menu2.save(date)"-->
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 sm12 md3 md3>
                    <v-text-field
                      label="Plazo"
                      :step="0.0"
                      type="number"
                      v-model.number="plazo"
                      required
                      name="plazo"
                      key="plazo"
                      v-validate="'required'"
                      :disabled="cxp.estado === 'Confirmado'"
                      :error-messages="errors.collect('plazo')"
                      :suffix="plazo ? ( plazo !== 1 ? 'días' : 'día' ) : ''">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md3 xl3>
                    <v-text-field
                      :value="computedDateFormatted3"
                      label="Fecha Vencimiento"
                      prepend-icon="event"
                      disabled
                      name="fecha vencimiento"
                      required
                      v-validate="'required|date_format:YYYY/MM/DD'"
                      :error-messages="errors.collect('fecha vencimiento')"></v-text-field>
                  </v-flex>
<!--                  <v-flex xs12 sm12 md2 lg2 v-if="cxp.id && (cxp.estado === 'Registrado' || cxp.estado === null) && !cxp.id">-->
<!--                    <v-select-->
<!--                      :items="estados"-->
<!--                      v-model="cxp.estado"-->
<!--                      label="Estado"-->
<!--                      name="estado"-->
<!--                    ></v-select>-->
<!--                  </v-flex>-->
                  <v-flex xs12 sm12 md12 xl12 class="pt-1 pb-0">
                    <v-textarea
                      v-model="cxp.observaciones"
                      auto-grow
                      rows="1"
                      color="primary"
                      :disabled="cxp.estado === 'Confirmado'"
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
                  <!--<v-text-field :value="valores(debito)"></v-text-field>-->
                  <template v-if="cxp.detalles.length > 0">
                    <v-flex xs12 sm12 md4 xl4>
                      <v-text-field :prefix="(debito !== 0) ? '$' : ''" :value="valorTotal | numberFormat(2)"
                                    name="valor a pagar" required v-validate="'required'" readonly
                                    :error-messages="errors.collect('valor a pagar')" label="Valor a pagar"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md4 xl4>
                      <v-text-field :prefix="(debito !== 0) ? '$' : ''" :value="debito | numberFormat(2)"
                                    name="valor débito" required v-validate="'required'" readonly
                                    :error-messages="errors.collect('valor débito')" label="Valor débito"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md4 xl4>
                      <v-text-field :prefix="(credito !== 0) ? '$' : ''" :value="credito | numberFormat(2)"
                                    name="valor crédito" required v-validate="'required'" readonly
                                    :error-messages="errors.collect('valor crédito')" label="Valor crédito"></v-text-field>
                    </v-flex>
                  </template>
                </v-layout>
              </v-container>
            </v-form>
            <v-card-text>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12 class="text-xs-right">
                  <v-btn @click="openCardDialog" small class="mr-0 mt-0" v-if="cxp.estado === 'Registrado' || cxp.estado === null" color="primary" dark>Agregar Detalle</v-btn>
                  <v-slide-x-reverse-transition>
                    <v-card v-show="cardDialog">
                      <v-card-title class="grey lighten-3">
                        <span class="title">{{ tituloDetalle }} </span>
                      </v-card-title>
                      <v-form data-vv-scope="formDetalle">
                        <v-container fluid grid-list-md grid-list-xl>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md6 xl6>
                              <v-autocomplete
                                :label="detalle.pg_conpago_id === null ? 'Buscar concepto' : 'Concepto'"
                                v-model="detalle.conpago"
                                @change="val => detalle.pg_conpago_id = val ? val.id : null"
                                :items="complementos.conpagos"
                                item-value="id"
                                item-text="codigo_nombre"
                                :hint="(detalle.conpago ? 'Naturaleza: ' + detalle.conpago.niif.clascuenta.naturaleza : '')"
                                persistent-hint
                                name="concepto"
                                required
                                :disabled="cxp.estado === 'Confirmado'"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                return-object
                                :clearable="cxp.estado === 'Registrado' || cxp.estado === null">
                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.codigo"/>
                                      <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                                <template>
                                  <v-slide-x-reverse-transition
                                    slot="append-outer"
                                    mode="out-in"
                                  >
                                    <v-icon
                                      :color="detalle.pg_conpago_id ? 'success' : 'info'"
                                      @click="dialogInfoConcepto = !dialogInfoConcepto"
                                      v-text="detalle.pg_conpago_id ? 'info' : ''"
                                    ></v-icon>
                                  </v-slide-x-reverse-transition>
                                </template>
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md6 xl6 v-if="detalle.pg_conpago_id != null">
                              <v-slide-x-transition>
                                <postulador-v2
                                  no-data="Busqueda por código o nombre."
                                  hint="nombre"
                                  item-text="codigo"
                                  data-title="codigo"
                                  data-subtitle="nombre"
                                  :label="!detalle.nf_niif_id ? 'cuenta' : 'Cuenta'"
                                  entidad="niifs"
                                  route-params="nivel:auxiliar=1"
                                  preicon="business_center"
                                  @changeid="val => detalle.nf_niif_id = val"
                                  v-model="detalle.niif"
                                  v-validate="'required'"
                                  name="cuenta de deficit"
                                  :error-messages="errors.collect('cuenta')"
                                  :disabled="cxp.estado === 'Confirmado'"
                                  btnCreateTruncate
                                  @create="openNuevaNiif(2)"
                                  :min-characters-search="2"
                                  :clearable="cxp.estado === 'Registrado' || cxp.estado === null"/>
<!--                                <postulador-->
<!--                                  nodata="Busqueda por código o nombre."-->
<!--                                  required-->
<!--                                  hint="nombre"-->
<!--                                  itemtext="codigo"-->
<!--                                  datatitle="codigo"-->
<!--                                  datasubtitle="nombre"-->
<!--                                  filter="codigo,nombre"-->
<!--                                  :label="!detalle.nf_niif_id ? 'cuenta' : 'Cuenta'"-->
<!--                                  scope="formDetalle"-->
<!--                                  ref="buscarDetalleCuenta"-->
<!--                                  entidad="niifs"-->
<!--                                  routeparams="nivel:auxiliar=1"-->
<!--                                  @change="val => detalle.nf_niif_id = val"-->
<!--                                  @objectReturn="val => detalle.niif = val"-->
<!--                                  :value="detalle.niif"-->
<!--                                  clearable-->
<!--                                  btnplustruncate-->
<!--                                  @click="openNuevaNiif(2)"-->
<!--                                ></postulador>-->
                              </v-slide-x-transition>
                            </v-flex>
                            <v-flex xs12 sm12 md12 xl12 v-if="detalle.pg_conpago_id != null">
                              <v-slide-y-transition>
                                <v-layout row wrap>
                                  <v-flex xs12 sm12 md6 xl6>
                                    <postulador
                                      nodata="Busqueda por nombre o número de documento."
                                      required
                                      :disabled="detalle.conpago && detalle.niif ? ((detalle.conpago.niif.maneja_tercero || detalle.niif.maneja_tercero) && (cxp.estado === 'Registrado' || cxp.estado === null) ? false : (true)) : null"
                                      hint="nombre_completo"
                                      itemtext="identificacion_completa"
                                      datatitle="identificacion_completa"
                                      datasubtitle="nombre_completo"
                                      filter="identificacion_completa,nombre_completo"
                                      :label="detalle.gn_tercero_id == null ? 'tercero' : 'Tercero'"
                                      scope="formDetalle"
                                      ref="postulaTercero"
                                      entidad="terceros"
                                      preicon="person"
                                      @change="val => detalle.gn_tercero_id = val"
                                      @objectReturn="val => detalle.tercero = val"
                                      :value="detalle.tercero"
                                      :clearable="cxp.estado === 'Registrado' || cxp.estado === null"
                                    />
                                  </v-flex>
                                  <v-flex xs12 sm12 md6 xl6>
                                    <v-autocomplete
                                      label="Centro de Costo"
                                      v-model="detalle.cencosto"
                                      @change="val => detalle.nf_cencosto_id = val ? val.id : null"
                                      :items="complementos.cencostos"
                                      item-value="id"
                                      item-text="nombre"
                                      name="centro de costo"
                                      required
                                      :disabled="detalle.niif ? ((detalle.niif.maneja_ccosto === 1) && (cxp.estado === 'Registrado' || cxp.estado === null) ? (false) : true) : ''"
                                      v-validate="'required'"
                                      :error-messages="errors.collect('centro de costo')"
                                      return-object
                                      :clearable="cxp.estado === 'Registrado' || cxp.estado === null"
                                    >
                                      <template slot="item" slot-scope="data">
                                        <template>
                                          <v-list-tile-content>
                                            <v-list-tile-title v-html="data.item.nombre"/>
                                          </v-list-tile-content>
                                        </template>
                                      </template>
                                    </v-autocomplete>
                                  </v-flex>
                                </v-layout>
                              </v-slide-y-transition>
                            </v-flex>
                            <v-flex xs12 sm12 md6 xl6>
                              <v-autocomplete
                                :label="detalle.pg_uniapoyo_id === null ? 'Buscar unidad de apoyo' : 'Unidad de apoyo'"
                                v-model="detalle.uniapoyo"
                                @change="val => detalle.pg_uniapoyo_id = val ? val.id : null"
                                :items="complementos.uniapoyos"
                                item-value="id"
                                item-text="codigo"
                                name="unidad de apoyo"
                                :hint="detalle.uniapoyo ? 'Unidad: ' + detalle.uniapoyo.nombre : ''"
                                persistent-hint
                                required
                                v-validate="'required'"
                                :disabled="cxp.estado === 'Confirmado'"
                                :error-messages="errors.collect('unidad de apoyo')"
                                return-object
                                :clearable="cxp.estado === 'Registrado' || cxp.estado === null">
                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.codigo"/>
                                      <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md3 xl3>
                              <v-text-field v-if="detalle.niif ? detalle.niif.tipo !== 'Retencion' : null"
                                :prefix="(detalle.valor !== null && detalle.valor !== '') ? '$' : ''" v-model.number="detalle.valor" key="valor"
                                name="valor" required v-validate="'required'" min="0" :disabled="!detalle.pg_conpago_id || cxp.estado === 'Confirmado' ? true : false"
                                :error-messages="errors.collect('valor')" label="Valor"></v-text-field>
                              <v-text-field v-else label="Valor"
                                :prefix="(detalle.valor !== null && detalle.valor !== '') ? '$' : ''"
                                :value="detalle.valor | numberFormat(2)" min="0" readonly
                                :disabled="!detalle.pg_conpago_id || cxp.estado === 'Confirmado' ? true : false"
                              >
                              </v-text-field>
<!--                              <v-text-field v-else-->
<!--                                :prefix="(detalle.valor !== null && detalle.valor !== '') ? '$' : ''" :value="detalle.valor | numberFormat(2)" key="valor"-->
<!--                                name="valor" required v-validate="'required'" min="0" :disabled="!detalle.pg_conpago_id || cxp.estado === 'Confirmado' ? true : false"-->
<!--                                :error-messages="errors.collect('valor')" label="Valor"></v-text-field>-->
                            </v-flex>
                            <v-flex xs12 sm12 md3 xl3>
<!--                              <span class="v-label caption theme&#45;&#45;light" v-text="'Naturaleza'"></span>-->
                              <v-radio-group
                                class="mt-0"
                                v-model="detalle.naturaleza"
                                :disabled="cxp.estado === 'Confirmado'"
                                v-validate="'required'"
                                name="naturaleza"
                                :error-messages="errors.collect('naturaleza')"
                              >
                                <v-radio
                                  label="Débito"
                                  :value="1"
                                >
                                </v-radio>
                                <v-radio
                                  label="Crédito"
                                  :value="0"
                                >
                                </v-radio>
                              </v-radio-group>
                            </v-flex>
                            <v-flex xs12 sm12 md12 xl12 class="pl-0 pr-0" v-if="detalle.niif ? detalle.niif.tipo === 'Retencion' : null">
                              <v-slide-y-transition>
                                <v-card class="elevation-0">
                                  <v-card-title class="grey lighten-2">
                                    <span class="title">Concepto de Retención</span>
                                  </v-card-title>
                                  <v-container fluid grid-list-md grid-list-xl  class="pa-3">
                                    <v-layout row wrap>
                                      <v-flex xs12 sm12 md4 xl4>
                                        <v-autocomplete
                                          :label="detalle.nf_conrtf_id === null ? 'Buscar concepto de retención' : 'Retención'"
                                          v-model="detalle.conrtf"
                                          @change="val => detalle.nf_conrtf_id = val ? val.id : null"
                                          :items="complementos.conrtfs"
                                          item-value="id"
                                          item-text="codigo_nombre"
                                          name="concepto de retención"
                                          required
                                          :disabled="cxp.estado === 'Confirmado'"
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
                                      <v-flex xs6 sm6 md2 xl2>
                                        <v-text-field readonly :disabled="cxp.estado === 'Confirmado'" :value="detalle.conrtf ? detalle.conrtf.porcentaje : null" suffix="%" label="Porcentaje"></v-text-field>
                                      </v-flex>
                                      <v-flex xs6 sm6 md3 xl3>
                                        <v-text-field
                                          prefix="$"
                                          label="Valor Base"
                                          v-model="valorBase"
                                          :disabled="detalle.conrtf ? ((detalle.conrtf.porcentaje !== null) && (cxp.estado === 'Registrado' || cxp.estado === null) ? false : (true)) : true "
                                          required
                                          v-validate="'required'"
                                          key="valor base"
                                          name="valor base"
                                          :error-messages="errors.collect('valor base')"
                                        ></v-text-field>
                                        <!--placeholder="0"-->

                                        <!--<money v-model="valorBase" v-bind="money"></money>-->
                                      </v-flex>
                                      <!--<v-flex xs6 sm6 md4 xl4>-->
                                      <!--<v-text-field-->
                                      <!--prefix="$"-->
                                      <!--label="Valor facturado"-->
                                      <!--v-model="valorFacturado"-->
                                      <!--v-money="money"-->
                                      <!--readonly-->
                                      <!--required-->
                                      <!--&gt;</v-text-field>-->
                                      <!--</v-flex>-->
                                      <v-flex xs6 sm6 md3 xl3>
                                        <v-text-field
                                          prefix="$"
                                          label="Valor retención"
                                          :value="valorRetencion | numberFormat(2)"
                                          required
                                          :disabled="cxp.estado === 'Confirmado'"
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
                        <v-card-actions>
                          <v-divider></v-divider>
                          <v-btn flat @click="dialogDetalle()">Cerrar</v-btn>
                          <v-btn v-if="cxp.estado === 'Registrado' || cxp.estado === null" color="blue darken-1" @click.prevent="agregarCxpDetalle(detalle)" flat>{{(typeof detalle.index !== 'number') ? 'Agregar' : 'Actualizar' }}</v-btn>
                        </v-card-actions>
                      </v-form>
                    </v-card>
                  </v-slide-x-reverse-transition>
                </v-flex>
                <v-dialog v-model="dialogInfoConcepto" width="600px">
                  <v-card class="cal-item">
                    <v-card-title class="grey lighten-2">
                      <v-icon>info</v-icon>
                      <h4 class="title"> Información del Concepto</h4>
                      <v-spacer></v-spacer>
                      <v-icon @click="dialogInfoConcepto = false">close</v-icon>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-container fluid grid-list-md>
                      <v-layout row wrap>
                        <v-flex xs12 md7>
                          <v-text-field label="Nombre concepto" readonly persistent-hint
                                        :hint="'código: ' + (detalle.conpago ? detalle.conpago.codigo : '')"
                                        :value="detalle.conpago ? detalle.conpago.nombre : ''">
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 md5>
                          <v-text-field label="Código cuenta" readonly
                                        :value="detalle.conpago ? detalle.conpago.niif.codigo : ''">
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 md12>
                          <v-text-field label="Nombre cuenta" readonly
                                        :value="detalle.conpago ? detalle.conpago.niif.nombre : ''">
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 md6>
                          <v-text-field label="Naturaleza" readonly
                          :value="detalle.conpago ? detalle.conpago.niif.clascuenta.naturaleza : ''">
                          </v-text-field>
                        </v-flex>
                      </v-layout>

                    </v-container>
                  </v-card>
                </v-dialog>
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
                <v-flex xs12 sm12 md12 lg12 v-if="cxp.detalles.length">
                  <v-expand-transition>
                    <v-card>
                      <v-card-title class="grey lighten-3">
                        <h3>Detalles de la Cuenta</h3>
                      </v-card-title>
                      <v-divider></v-divider>
                      <v-data-table
                        :headers="saldoInicial ? headerSaldoInicial : headers"
                        :items="cxp.detalles"
                        :loading="tableLoading"
                        :pagination.sync="pagination"
                        hide-actions rows-per-page-text="Registros por página"
                        :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                        <template slot="items" slot-scope="props">
                          <template v-if="!saldoInicial">
                            <td class="text-xs-left">{{ props.item.conpago.codigo + ' - ' + props.item.conpago.nombre}}</td>
                            <!--                          <td class="text-xs-left">{{ props.item.conpago.nombre}}</td>-->
                            <!--                          conpago.niif.clascuenta.-->
                            <td class="text-xs-center">{{ props.item.naturaleza === 0 ? 'Crédito' : 'Débito'}}</td>
                            <td class="text-xs-left" v-html="`<b>${props.item.niif ? props.item.niif.codigo : ''}</b> - ${props.item.niif ? props.item.niif.nombre : ''}`"></td>
                            <!--                          <td class="text-xs-left">{{ }}</td>-->
                            <td class="text-xs-left">{{ props.item.uniapoyo.nombre }}</td>
                            <td class="text-xs-center">{{ props.item.cencosto ? props.item.cencosto.codigo : '' }}</td>
                          </template>
                          <template v-else>
                            <td class="text-xs-left" v-html="`<b>${props.item.niif ? props.item.niif.codigo : ''}</b> - ${props.item.niif ? props.item.niif.nombre : ''}`"></td>
                            <td class="text-xs-left" v-html="`<b>${props.item.tercero ? props.item.tercero.identificacion_completa : ''}</b> | ${props.item.tercero ? props.item.tercero.nombre_completo : ''}`"></td>
                            <td class="text-xs-center">{{ props.item.naturaleza === 0 ? 'Crédito' : 'Débito'}}</td>
                          </template>
                          <td class="text-xs-right">{{ currency(props.item.valor) }}</td>
                          <td class="text-xs-center" v-if="!saldoInicial">
                            <v-layout class="d-flex">
                              <div class="d-inline-block">
                                <v-btn icon class="mx-0" fab small @click="editarDetalle(props.index, props.item)"><v-icon color="accent" v-text="cxp.estado === 'Registrado' || cxp.estado === null ? 'edit' : 'fas fa-eye'"></v-icon></v-btn>
                              </div>
                              <div class="d-inline-block">
                                <v-btn v-if="cxp.estado === 'Registrado' || cxp.estado === null" icon class="mx-0" fab small @click="preguntarEliminarDetalle(props.item)"> <v-icon color="accent">delete</v-icon> </v-btn>
                              </div>
                            </v-layout>
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
                <v-flex xs12 sm12 md12 lg12 v-if="cxp.modulo !== 'Saldo Inicial'">
                  <dialog-registro-rp
                    v-model="rubroDetalleObligacion"
                    :tercero="cxp.tercero"
                    @agregar="item => add(item)"
                    @cancelar="formReset"
                    @total_detalles="suma => cxp.valor_obligacion_header = suma"
                    :data-table-details="cxp.rubrosDetalles"
                    :estado-cxp="cxp.estado"
                    @editar="val => {
                      rubroDetalleObligacion = val.detalle
                      rubroDetalleObligacion.index = val.index
                    }"
                  ></dialog-registro-rp>
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-card>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" v-if="cxp.estado === 'Registrado' || cxp.estado === null" @click.prevent="confirmarSave">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CXP_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../store/complementos/index'
  import DialogRegistroRp from '../presupuesto/rp/DialogRegistroRp'
  // import {mapState} from 'vuex'
  export default {
    name: 'RegistroCxp',
    components: {
      DialogRegistroRp,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['parametros'],
    data () {
      return {
        cxps: [],
        cxp: {},
        cxpEdicion: {
          id: null,
          consecutivo: null,
          fecha_causacion: this.moment().format('YYYY-MM-DD'),
          pg_proveedore_id: null,
          no_factura: null,
          fecha_factura: this.moment().format('YYYY-MM-DD'),
          fecha_vencimiento: null,
          nf_niif_id: null,
          gn_tercero_id: null,
          nf_cencosto_id: null,
          cxp_plazo: null,
          observaciones: null,
          modulo: null,
          estado: null,
          proveedor: null,
          tercero: null,
          niif: null,
          cencosto: null,
          valor_obligacion_header: 0,
          detalles: [],
          rubrosDetalles: []
        },
        plazo: null,
        cuentaNiff: null,
        dialogFormNiif: false,
        valoresCuenta: {},
        dialogInfoConcepto: false,
        niifNueva: null,
        maxDate: this.moment().format('YYYY-MM-DD'),
        pagination: {},
        modulos: ['CXP', 'Inventarios', 'Saldo Inicial', 'Activos Fijos'],
        estados: ['Registrado', 'Confirmado', 'Anulado'],
        detalle: {},
        rubroDetalleObligacion: {},
        dialogEliminar: false,
        detalle_anulacion: null,
        registrar: false,
        confirmar: false,
        dialog: false,
        dialog2: false,
        dialog3: false,
        menuDate: false,
        menuDate2: false,
        menuDate3: false,
        numeroFacturaResgistrado: null,
        debito: 0,
        credito: 0,
        valorTotal: 0,
        date: null,
        cardDialog: false,
        imprime: false,
        tableLoading: false,
        descriptionLimit: 60,
        auxiliar: {},
        valorBase: 0,
        valorFacturado: null,
        valorRetencion: 0,
        keyProveedor: null,
        headerSaldoInicial: [
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tercero',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Naturaleza',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: 'Valor',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: 'Sin Acción',
            align: 'center',
            sortable: false,
            value: 'id',
            width: '100px'
          }
        ],
        headers: [
          {
            text: 'Concepto',
            align: 'left',
            sortable: false,
            value: '',
            width: '180px'
          },
          // {
          //   text: 'Nombre Concepto',
          //   align: 'left',
          //   sortable: false,
          //   value: ''
          // },
          {
            text: 'Naturaleza',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: ''
          },
          // {
          //   text: 'Nombre Cuenta',
          //   align: 'left',
          //   sortable: false,
          //   value: ''
          // },
          {
            text: 'Unidad de Apoyo',
            align: 'left',
            sortable: false,
            value: 'pg_uniapoyo_id'
          },
          {
            text: 'Centro de Costo',
            align: 'center',
            sortable: false,
            value: 'nf_cencosto_id'
          },
          {
            text: 'Valor',
            align: 'center',
            sortable: false,
            value: 'valor',
            width: '150px'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id',
            width: '100px'
          }
        ]
      }
    },
    watch: {
      'cxp.proveedor' (val) {
        if (val) {
          this.cxp.niif = val.niif
          this.cxp.tercero = val ? val.tercero : null
          this.cxp.gn_tercero_id = this.cxp.tercero.id
          this.plazo = val.plazo
        } else {
          this.cxp.niif = null
          this.cxp.tercero = null
          this.cxp.gn_tercero_id = null
          this.plazo = null
        }
        // this.rpsTercero(val)
      },
      'cxp.cencosto' (val) {
        if (val === null || this.cxp.niif === null || this.cxp.proveedor == null || this.cxp.niif.maneja_ccosto === 0 || this.cxp.proveedor.niif.maneja_ccosto === 0) {
          this.$validator.reset()
        }
      },
      'cxp.detalles' (vals) {
        this.debito = 0
        this.credito = 0
        vals.forEach(val => {
          // val.conpago.niif.clascuenta.naturaleza
          if (val.naturaleza !== 0) {
            this.debito = this.debito + val.valor
          } else {
            this.credito = this.credito + val.valor
          }
        })
        this.valorTotal = this.debito - this.credito
        if (this.valorTotal < 0) {
          this.valorTotal = 0
        } else {
          return this.valorTotal
        }
      },
      'plazo' (val) {
        this.cxp.fecha_vencimiento = this.moment(this.cxp.fecha_factura).add(val, 'd').format('YYYY-MM-DD')
        this.cxp.cxp_plazo = val
      },
      'detalle.conpago' (val) {
        if (val === null) {
          this.$validator.reset()
          this.formReset()
        } else {
          let niff = this.detalle.niif
          if ((typeof this.detalle.index !== 'number') || (niff.id === val.niif.id)) {
            this.detalle.niif = val ? val.niif : null
            this.detalle.nf_niif_id = val ? val.niif.id : null
          }
        }
      },
      'detalle.niif' (val) {
        this.detalle.tercero = val && val.maneja_tercero ? this.cxp.proveedor.tercero : null
        this.detalle.cencosto = val && val.maneja_ccosto ? this.detalle.cencosto : null
        let natu = this.detalle.naturaleza
        this.detalle.naturaleza = val && (val.tipo === 'Retención') ? 0 : natu
      },
      'detalle.tercero' (val) {
        if (val === null) this.$validator.reset()
      },
      'detalle.uniapoyo' (val) {
        if (val === null) {
          this.$validator.reset()
        }
      },
      'detalle.conrtf' (val) {
        if (val === null) {
          this.$validator.reset()
        }
      },
      'valorBase' (val) {
        if (val) {
          let porcent = this.detalle.conrtf ? this.detalle.conrtf.porcentaje : null
          this.valorRetencion = val * (porcent / 100)
          this.detalle.valor = this.valorRetencion
          this.detalle.retencion = parseFloat(val)
        }
      },
      'stateItem' (val) {
        if (val.key === this.keyProveedor) {
          this.cxp.proveedor = val.item
        }
      },
      'dialogFormNiif' (val) { if (!val) this.niifNueva = null }
    },
    computed: {
      tituloDetalle () {
        return !this.detalle.id ? 'Nuevo Detalle de Cuenta' : `${(this.cxp.estado === 'Registrado' || this.cxp.estado === null) ? 'En Edición del' : 'Visualizando'} Detalle con Cuenta N° ${(this.detalle.niif ? this.detalle.niif.codigo : '####')}`
      },
      tapTitulo () {
        return !this.cxp.id ? 'Nueva Cuenta por Pagar' : (this.cxp.estado === 'Registrado' || this.cxp.estado === null ? 'En Edición' : 'Visualizando')
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      },
      typeObject () {
        return typeof this.object === 'undefined'
      },
      computedDateFormatted () {
        return this.formDate(this.cxp.fecha_causacion)
      },
      computedDateFormatted2 () {
        return this.formDate(this.cxp.fecha_factura)
      },
      computedDateFormatted3 () {
        return this.formDate(this.cxp.fecha_vencimiento)
      },
      complementos () {
        return store.getters.complementosFormCxp
      },
      infoComponentProveedor () {
        return this.$store.getters.getInfoComponent('pg_proveedores')
      },
      stateItem () {
        return this.$store.state.tables.itemPgProveedor
      },
      iconoEstado () {
        if (this.cxp.estado === 'Registrado') return 'stars'
        if (this.cxp.estado === 'Confirmado') return 'check_circle'
        if (this.cxp.estado === 'Anulado') return 'remove_circle'
      },
      colorEstado () {
        if (this.cxp.estado === 'Registrado') return 'primary'
        if (this.cxp.estado === 'Confirmado') return 'teal'
        if (this.cxp.estado === 'Anulado') return 'red'
      },
      saldoInicial () {
        let fal = false
        return this.cxp.modulo === 'Saldo Inicial' ? true : fal
      }
    },
    beforeCreate () {
      this.axios.get('pg_cxps').then(res => {
        this.cxps = res.data.data
        this.numeroFacturaResgistrado = this.getNumFacturaUsado()
      }).catch(e => {
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las cuentas por pagar. ' + e.response, color: 'danger'})
      })
    },
    beforeMount () {
      this.refresh()
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
      const dict = {
        custom: {
          factura: {
            not_in: 'El número factura ya se encuentra registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    methods: {
      add (item) {
        if (typeof item.index !== 'number') {
          this.cxp.rubrosDetalles.push(item)
        } else {
          this.cxp.rubrosDetalles
            .splice(this.cxp.rubrosDetalles
              .findIndex(x => this.cxp.rubrosDetalles.indexOf(x) === item.index), 1, item)
        }
      },
      openCreateProveedor (val) {
        this.cxp.proveedor = val
        if (val !== null) {
          this.keyProveedor = 'key-proveedor-' + Math.random().toString(36).substring(2)
          this.$store.commit('NAV_ADD_ITEM', { ruta: 'pg_proveedores', titulo: this.infoComponentProveedor.titulo_registro, required: true, parametros: {entidad: null, key: this.keyProveedor, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })
        }
      },
      openNuevaNiif (val) {
        this.niifNueva = val
        this.dialogFormNiif = true
      },
      goNiif (val) {
        if (this.niifNueva === 1) {
          this.cxp.niif = val
        } else if (this.niifNueva === 2) {
          this.detalle.niif = val
        }
        this.dialogFormNiif = false
      },
      formReset () {
        this.detalle = {
          id: null,
          index: null,
          pg_cxp_id: null,
          pg_conpago_id: null,
          pg_uniapoyo_id: null,
          gn_tercero_id: null,
          nf_cencosto_id: null,
          nf_niif_id: null,
          nf_conrtf_id: null,
          naturaleza: null,
          valor: null,
          retencion: null,
          uniapoyo: null,
          cxp: null,
          conpago: null,
          conrtf: null,
          tercero: null,
          cencosto: null,
          niif: null
        }
        this.rubroDetalleObligacion = {
          id: null,
          index: null,
          pr_detrp_id: null,
          pr_tipo_gasto_id: null,
          tipo_gasto: null,
          valor: 0,
          saldo_por_obligar: 0,
          rubro: null,
          rp: null,
          cdp: null
        }
        this.numeroFacturaResgistrado = this.getNumFacturaUsado()
      },
      valores (val) {
        return val ? this.number_format(parseInt(val), 2, ['.', '.', ',']) : null
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('pg_cxps/' + id)
          .then((response) => {
            if (response.data !== '') {
              response.data.data.rubrosDetalles = []
              response.data.data.valor_obligacion_header = 0
              this.cxp = response.data.data
              this.cxp.modulo = response.data.data.modulo
              if (response.data.data.obligacion !== null) {
                // this.cxp.valor_obligacion_header = response.data.data.obligacion.valor_obligacion
                this.cxp.rubrosDetalles = JSON.parse(JSON.stringify(response.data.data.obligacion.detalles))
              }
              this.numeroFacturaResgistrado = this.getNumFacturaUsado()
              loader.hide()
            }
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la cuenta por pagar. ' + e.response, color: 'danger'})
          })
      },
      getNumFacturaUsado () {
        return this.cxps.map(cod => { if (!(this.cxp.no_factura != null && this.cxp.no_factura === cod.no_factura)) return cod.no_factura })
      },
      // formatear número
      number_format (value, decimals, separators) {
        decimals = decimals >= 0 ? parseInt(decimals, 0) : 2
        separators = separators || ['.', "'", ',']
        var number = (parseFloat(value) || 0).toFixed(decimals)
        if (number.length <= (4 + decimals)) return number.replace('.', separators[separators.length - 1])
        var parts = number.split(/[-.]/)
        value = parts[parts.length > 1 ? parts.length - 2 : 0]
        var result = value.substr(value.length - 3, 3) + (parts.length > 1 ? separators[separators.length - 1] + parts[parts.length - 1] : '')
        var start = value.length - 6
        var idx = 0
        while (start > -3) {
          result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start)) + separators[idx] + result
          idx = (++idx) % 2
          start -= 3
        }
        return (parts.length === 3 ? '-' : '') + result
      },
      openCardDialog () {
        if (this.cxp.pg_proveedore_id) {
          this.cardDialog = !this.cardDialog
        } else {
          this.cardDialog = false
          this.$store.commit(SNACK_SHOW, {msg: 'Debe seleccionar un proveedor.', color: 'error'})
        }
      },
      editarDetalle (index, item) {
        let random = parseFloat(item.valor) * (item.conrtf ? (100 / item.conrtf.porcentaje) : 0)
        let valor1 = parseFloat(random)
        this.valorBase = Math.floor(valor1)
        this.detalle = JSON.parse(JSON.stringify(item))
        this.detalle.index = index
        this.cardDialog = true
      },
      preguntarEliminarDetalle (item) {
        this.dialogEliminar = true
        this.detalle = JSON.parse(JSON.stringify(item))
      },
      eliminarDetalle (detalle) {
        this.dialogEliminar = false
        this.cxp.detalles.splice(this.cxp.detalles.indexOf(detalle), 1)
        this.formReset()
      },
      dialogDetalle (val) {
        if (val === 'abrir') {
          this.cardDialog = true
        } else {
          this.cardDialog = false
          this.valorBase = 0
          this.formReset()
        }
      },
      async agregarCxpDetalle (detalle) {
        this.tableLoading = true
        if (await this.validador('formDetalle')) {
          if (typeof detalle.index !== 'number') {
            this.cxp.detalles.push(detalle)
          } else {
            this.cxp.detalles
              .splice(this.cxp.detalles
                .findIndex(x => this.cxp.detalles.indexOf(x) === detalle.index), 1, detalle)
          }
          this.tableLoading = false
          this.formReset()
        } else {
          if (this.detalle.pg_conpago_id == null) {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado el concepto.', color: 'error'})
          } else if (this.detalle.pg_uniapoyo_id == null) {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado la unidad de apoyo.', color: 'error'})
          } else if (this.detalle.conpago.niif.clascuenta.naturaleza === null) {
            this.$store.commit(SNACK_SHOW, {msg: 'La cuenta no presenta naturaleza.', color: 'error'})
          }
        }
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      refresh () {
        this.$validator.reset()
        this.plazo = null
        this.cxp = JSON.parse(JSON.stringify(this.cxpEdicion))
        // console.log('ff', this.cxp)
      },
      // Modales Confirmar y registrar
      imprimirCuenta () {
        this.dialog2 = false
        let id = this.cxp.id
        this.axios.get('reportecxps?id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al Imprimir. ' + e.response, color: 'danger'})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      // Guardar
      async confirmarSave () {
        let errorCuenta = await this.$refs.buscarCuenta.validate()
        // let errorTercero = await this.$refs.postulaProveedor.validate()
        if (await this.validador('formPrincipal') && errorCuenta && this.cxp.detalles.length > 2 && this.credito <= this.debito) {
          this.dialog2 = true
        } else {
          if (!this.cxp.detalles.length) {
            this.$store.commit(SNACK_SHOW, {msg: 'Se deben agregar al menos dos detalles.', color: 'error'})
          } else if (this.credito > this.debito) {
            this.$store.commit(SNACK_SHOW, {msg: 'El valor crédito no puede ser superior al del débito.', color: 'error'})
          } else {
            this.dialog2 = true
          }
        }
      },
      confirmarEstado (val) {
        if (val === 'yesConfirmar') {
          if (this.confirmar && this.imprime) {
            this.cxp.estado = 'Confirmado'
            this.imprimirCuenta()
          } else {
            this.cxp.estado = 'Confirmado'
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
          this.cxp.estado = 'Registrado'
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
          this.cxp.estado = 'Registrado'
          this.imprimirCuenta()
          this.save()
        } else if (this.cxp.estado === 'Anulado') {
          this.dialog3 = true
        } else if (this.cxp.estado === 'Anulado' && this.imprime) {
          this.dialog3 = true
        } else {
          this.cxp.estado = 'Registrado'
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta  ha sido Registrado', color: 'success'})
        }
      },
      cerrarDialogs () {
        this.dialog = false
        this.dialog2 = false
        this.dialog3 = false
        this.imprime = false
        this.confirmar = false
      },
      save () {
        const typeRequest = this.cxp.id ? 'editar' : 'crear'
        this.axios.post('pg_cxps', this.cxp).then(response => {
          if (this.cxp.id) {
            this.$store.commit(SNACK_SHOW, {
              msg: 'La cuenta se actualizó correctamente',
              color: 'success'
            })
          } else {
            this.$store.commit(SNACK_SHOW, {
              msg: 'La cuenta se creó correctamente',
              color: 'success'
            })
          }
          this.$store.commit(CXP_RELOAD_ITEM, {
            item: response.data.cxp,
            estado: typeRequest,
            key: this.parametros.key
          })
          this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
          this.cerrarDialogs()
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {
            msg: e.response.data.errors,
            color: 'danger'
          })
          this.cxp.estado = 'Registrado'
          this.cerrarDialogs()
        })
      }
    }
  }
</script>

<style scoped>
  /*#inputUsage .layout .flex div {*/
    /*border: 1px dashed rgba(0,0,0,0.4) !important;*/
  /*}*/
  .cal-item .btn {
    margin: 0 5px;
    height: 28px;
    width: 28px;
  }
  .cal-item .btn i {
    font-size: 22px;
  }

  .cal-item .card__title {
    padding: 16px 16px
  }

  .cal-item .list__tile {
    height: unset!important;
    padding: 10px 16px;
  }

  .cal-item .list__tile .list__tile__action {
    align-self: flex-start;
    min-width: unset;
    padding-right: 16px;
  }

  .cal-item .color-2 {
    background-color: #F44336;
  }
</style>
