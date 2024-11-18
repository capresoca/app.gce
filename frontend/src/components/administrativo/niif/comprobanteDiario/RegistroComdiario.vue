<template>
  <div>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="400">
        <v-card>
          <v-card-text class="title font-weight-light">¿Esta seguro que desea confirmar el comprobante?</v-card-text>
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
                <v-checkbox color="teal" off-icon="done" on-icon="done_all" default v-model="confirmar" :value="comDiario.estado == 'Confirmado' ? true : false"
                            :disabled="comDiario.estado == 'Anulado' ? true : false" :label="comDiario.estado != 'Anulado' ? 'Confirmar' : 'Anulado'" hide-details class="shrink mr-2"></v-checkbox>
                <!--label="Confirmar"-->
              </v-flex>
              <v-flex xs4>
                <v-checkbox color="black" v-if="comDiario.id != null" off-icon="far fa-file-pdf" on-icon="far fa-file-pdf" v-model="imprime" hide-details class="shrink mr-2" label="Imprimir"></v-checkbox>
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
          <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
          <v-card-title class="grey lighten-3">
            <v-card-text class="title">¿Por qué anula este comprobante?</v-card-text>
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
                <!--<textarea box name="Detalle de anulación" v-model="detalle_anulacion" placeholder="Detalle de anulación"></textarea>-->
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <!--<v-btn flat @click.native="dialog = false">No</v-btn>-->
            <v-btn color="primary" flat @click="esAnulado(comDiario, detalle_anulacion)">Enviar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
<!--        <v-toolbar class="grey lighten-3 elevation-0 toolbar&#45;&#45;dense">-->
<!--          <v-toolbar-title> Registro CDP </v-toolbar-title>-->
<!--          <v-spacer></v-spacer>-->
<!--          <v-chip label color="white" text-color="red" dark absolute right top>-->
<!--            <span class="subheading">N°</span>&nbsp;-->
<!--            <span class="subheading" v-text="cdp.consecutivo == null ? '00' : cdp.consecutivo"></span>-->
<!--          </v-chip>-->
<!--        </v-toolbar>-->
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title> &nbsp;
        <!--<small v-if="comDiario.id != null" class="pt-1 pb-0"> Estado: {{ comDiario.estado }}</small>-->
        <v-spacer/>
        <h2 v-if="comDiario.id != null" class="title">Comprobante de Diario</h2>
        <div v-if="comDiario.id != null">
          <v-chip label color="white" text-color="red" dark absolute right top>
            <span class="subheading">N°</span>&nbsp;
            <span class="subheading" v-text="comDiario.consecutivo == null ? '00' : comDiario.consecutivo"></span>
          </v-chip>
          <v-chip :color="colorEstado" text-color="white">
            <v-avatar>
              <v-icon v-text="iconoEstado"></v-icon>
            </v-avatar>
            {{ comDiario.estado }}
          </v-chip>
        </div>
      </v-toolbar>
      <!--<div style="width: 100%" class="text-xs-right">-->
        <!--&lt;!&ndash;<v-spacer></v-spacer>&ndash;&gt;-->
      <!--</div>-->
      <v-container fluid grid-list-md grid-list-xl class="pt-2 pb-2">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-card>
              <v-form data-vv-scope="formPrincipal">
                <v-container fluid grid-list-md grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md5 lg5>
                      <v-autocomplete
                        label="Tipo de Comprobante"
                        v-model="comDiario.nf_tipcomdiario_id"
                        :items="complementosFormComDiario.tipcomdiarios"
                        item-value="id"
                        item-text="nombre"
                        name="tipo de comprobante"
                        required
                        v-validate="'required'"
                        :error-messages="errors.collect('tipo de comprobante')"
                        autocomplete
                        :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
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
                    <v-flex xs12 sm12 md2 lg2>
                      <input-date
                        v-model="comDiario.fecha"
                        label="Fecha"
                        placeholder="YYYY-MM-DD"
                        format="YYYY-MM-DD"
                        name="fecha"
                        key="fecha"
                        v-validate="'required|fechaValida|date_format:YYYY-MM-DD'"
                        :error-messages="errors.collect('fecha')"
                        :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                      ></input-date>
<!--                      <v-menu-->
<!--                        ref="menuDate"-->
<!--                        :close-on-content-click="false"-->
<!--                        v-model="menuDate"-->
<!--                        :nudge-right="40"-->
<!--                        lazy-->
<!--                        transition="scale-transition"-->
<!--                        offset-y-->
<!--                        full-width-->
<!--                        max-width="290px"-->
<!--                        min-width="290px"-->
<!--                      >-->
<!--                        &lt;!&ndash;:return-value.sync="date"&ndash;&gt;-->
<!--                        <v-text-field-->
<!--                          slot="activator"-->
<!--                          v-model="computedDateFormatted"-->
<!--                          label="Fecha"-->
<!--                          prepend-icon="event"-->
<!--                          readonly-->
<!--                          name="fecha"-->
<!--                          required-->
<!--                          v-validate="'required|date_format:YYYY/MM/DD'"-->
<!--                          :error-messages="errors.collect('fecha')"-->
<!--                        ></v-text-field>-->
<!--                        <v-date-picker-->
<!--                          v-model="comDiario.fecha"-->
<!--                          @input="menuDate = false"-->
<!--                          locale='es'-->
<!--                          no-title-->
<!--                          :max="maxDate"-->
<!--                        ></v-date-picker>-->
<!--                        &lt;!&ndash;@input="$refs.menu2.save(date)"&ndash;&gt;-->
<!--                      </v-menu>-->
                    </v-flex>
                    <v-flex xs12 sm12 md5 lg5>
                      <v-text-field
                        v-model="comDiario.documento"
                        label="Documento"
                        name="documento"
                        :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                      ></v-text-field>
                    </v-flex>
<!--                    :class="{'md5 lg5': comDiario.id == null }"-->
<!--                    <v-flex xs12 sm12 md2 lg2 v-if="comDiario.id">-->
<!--                      <v-select-->
<!--                        :items="estados"-->
<!--                        v-model="comDiario.estado"-->
<!--                        label="Estado"-->
<!--                        name="estado"-->
<!--                      ></v-select>-->
<!--                    </v-flex>-->
                    <v-flex xs12 sm12 md12 lg12>
                      <v-textarea
                        v-model="comDiario.detalle"
                        color="primary"
                        name="observaciones"
                        auto-grow
                        rows="1"
                        :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                      >
                        <div slot="label">
                          Observaciones
                        </div>
                      </v-textarea>

                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
              <v-card-text>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12 class="text-xs-right pt-0">
                    <v-btn
                      @click="cardDialog = !cardDialog"
                      small
                      class="mr-0"
                      color="primary"
                      dark
                      v-if="comDiario.estado === 'Registrado' || comDiario.estado === null"
                      v-text="'Agregar Detalle'"
                    />
<!--                    <v-slide-x-reverse-transition> v-show="cardDialog"-->
                    <v-dialog v-model="cardDialog" width="1000px" persistent>
                      <v-card>
                        <v-card-title class="grey lighten-3">
                          <span class="title">{{ tituloDetalle }} </span>
                        </v-card-title>
                        <v-form data-vv-scope="formDetalleComdiario">
                          <v-container fluid grid-list-md grid-list-xl>
                            <v-layout row wrap>
                              <v-flex xs12 sm12 md6 lg6>
                                <!-- Agregar Detalles<cuenta-autocomplete ref="buscarCuenta" object label="Cuenta NIIF" required @change="val => cuentaSeleccionada(val)"></cuenta-autocomplete>-->
                                <postulador-v2
                                  no-data="Busqueda por código o nombre."
                                  hint="codigo"
                                  item-text="nombre"
                                  data-title="codigo"
                                  data-subtitle="nombre"
                                  scope="formDetalleComdiario"
                                  :label="(detalle.nf_niif_id === null ? 'c' : 'C') +  'uenta auxiliar'"
                                  entidad="niifs"
                                  ref="buscarCuenta"
                                  route-params="nivel:auxiliar=1"
                                  preicon="local_atm"
                                  @changeid="val => detalle.nf_niif_id = val"
                                  v-model="detalle.niif"
                                  required
                                  v-validate="'required'"
                                  name="cuenta auxiliar"
                                  key="cuenta auxiliar"
                                  :error-messages="errors.collect('cuenta auxiliar')"
                                  noBtnCreate
                                  :min-characters-search="2"
                                  :clearable="(comDiario.estado === 'Registrado' || comDiario.estado === null) ? (true) : false"
                                  :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                />
<!--                                <postulador-->
<!--                                  nodata="Busqueda por código o nombre."-->
<!--                                  required-->
<!--                                  hint="codigo"-->
<!--                                  itemtext="nombre"-->
<!--                                  datatitle="codigo"-->
<!--                                  datasubtitle="nombre"-->
<!--                                  filter="codigo,nombre"-->
<!--                                  :label="(detalle.nf_niif_id === null ? 'c' : 'C') +  'uenta auxiliar'"-->
<!--                                  lite-->
<!--                                  scope="formDetalleComdiario"-->
<!--                                  ref="buscarCuenta"-->
<!--                                  entidad="niifs"-->
<!--                                  routeparams="nivel:auxiliar=1"-->
<!--                                  preicon="person"-->
<!--                                  @change="val => detalle.nf_niif_id = val"-->
<!--                                  @objectReturn="val => detalle.niif = val"-->
<!--                                  :value="detalle.niif"-->
<!--                                ></postulador>-->
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <postulador-v2
                                  no-data="Busqueda por nombre o número de documento."
                                  hint="identificacion"
                                  item-text="nombre_completo"
                                  data-title="identificacion"
                                  data-subtitle="nombre_completo"
                                  scope="formDetalleComdiario"
                                  ref="postulaTercero"
                                  :label="(detalle.gn_tercero_id === null ? 't' : 'T') +  'ercero'"
                                  entidad="terceros"
                                  preicon="person"
                                  @changeid="val => detalle.gn_tercero_id = val"
                                  v-model="detalle.tercero"
                                  :disabled="detalle.nf_niif_id ? !detalle.niif.maneja_tercero : !(detalle.nf_niif_id)"
                                  v-validate="detalle.nf_niif_id ? (detalle.niif.maneja_tercero ? 'required' : '') : ''"
                                  name="tercero"
                                  key="tercero"
                                  :error-messages="errors.collect('tercero')"
                                  noBtnCreate
                                  :min-characters-search="2"
                                  :clearable="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (false) : true"
                                  :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                />
<!--                                <postulador-->
<!--                                  nodata="Busqueda por nombre o número de documento."-->
<!--                                  :required="detalle.nf_niif_id ? detalle.niif.maneja_tercero : null"-->
<!--                                  :disabled="detalle.nf_niif_id ? !detalle.niif.maneja_tercero : null"-->
<!--                                  hint="identificacion"-->
<!--                                  itemtext="nombre_completo"-->
<!--                                  datatitle="identificacion"-->
<!--                                  datasubtitle="nombre_completo"-->
<!--                                  filter="identificacion_completa,nombre_completo"-->
<!--                                  label="tercero"-->
<!--                                  scope="formDetalleComdiario"-->
<!--                                  lite-->
<!--                                  ref="postulaTercero"-->
<!--                                  entidad="terceros"-->
<!--                                  preicon="person"-->
<!--                                  @change="val => detalle.gn_tercero_id = val"-->
<!--                                  @objectReturn="val => detalle.tercero = val"-->
<!--                                  :value="detalle.tercero"-->
<!--                                  :clearable="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (false) : true"-->
<!--                                  :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"-->
<!--                                />-->
                              </v-flex>
                              <v-flex xs12 sm12 md12 lg12 v-if="detalle.niif ? detalle.niif.tipo === 'Retencion' : null">
                                <v-slide-y-transition>
                                  <template v-if="detalle.niif ? detalle.niif.tipo === 'Retencion' : null">
                                    <v-card>
                                      <v-card-title class="grey lighten-3 pt-2 pb-2">
                                        <span class="subheading font-weight-medium">Concepto de Retención </span>
                                      </v-card-title>
                                      <v-container fluid class="pt-2">
                                        <v-layout row wrap>
                                          <v-flex xs12 sm12 md12 xl12>
                                            <v-autocomplete
                                              label="Concepto de Retención"
                                              v-model="detalle.conrtf"
                                              :items="complementosFormComDiario.conrtfs"
                                              item-value="id"
                                              item-text="nombre"
                                              key="concepto de retención"
                                              name="concepto de retención"
                                              @input="calcularRetencion"
                                              @change="val => detalle.nf_conrtf_id = val ? val.id : null"
                                              :required="detalle.niif ? (detalle.niif.tipo === 'Retencion') : false"
                                              v-validate="'required'"
                                              return-object
                                              :error-messages="errors.collect('concepto de retención')">
                                              <!--:disabled="detalle.retencion === null"-->
                                              <template slot="item" slot-scope="data">
                                                <template>
                                                  <v-list-tile-content>
                                                    <v-list-tile-title v-html="data.item.nombre"/>
                                                  </v-list-tile-content>
                                                </template>
                                              </template>
                                            </v-autocomplete>
                                          </v-flex>
                                          <v-flex xs6 sm6 md4 xl4>
                                            <v-text-field readonly :value="detalle.conrtf ? detalle.conrtf.porcentaje : null" suffix="%" label="Porcentaje"></v-text-field>
                                          </v-flex>
                                          <v-flex xs6 sm6 md4 lg4>
                                            <v-text-field prefix="$" type="number" v-model.number="detalle.retencion" key="valor base"
                                                          :success-messages="porcentajeRTF > 0 && porcentajeRTF != null ? 'Se aplicara ' + porcentajeRTF + '% de retención en la fuente' : '' "
                                                          persistent-hint :hint="porcentajeRTF > 0 && porcentajeRTF != null ? 'Se aplica ' + porcentajeRTF + '% de retención en la fuente' : ''"
                                                          name="valor base" :required="detalle.nf_niif_id && detalle.niif ? (detalle.niif.tipo == 'Retencion' ? true : false) : null" v-validate="'required'" v-on:keyup="calcularRetencion"
                                                          :error-messages="errors.collect('campo valor base')" label="Valor base" min="0"></v-text-field>
                                          </v-flex>
                                          <v-flex xs6 sm6 md4 xl4>
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
                                          <!--<v-flex xs12 sm12 md12 lg12>-->
                                          <!--<v-alert v-show="porcentajeRTF" color="info" type="info">-->
                                          <!--Se aplicara <strong v-text="porcentajeRTF +'%'"></strong> de retención en la fuente.-->
                                          <!--</v-alert>-->
                                          <!--</v-flex>-->
                                        </v-layout>
                                      </v-container>
                                    </v-card>
                                  </template>
                                </v-slide-y-transition>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-autocomplete
                                  label="Centro de Costo"
                                  v-model="detalle.cencosto"
                                  @change="val => detalle.nf_cencosto_id = val ? val.id : null"
                                  :items="complementosFormComDiario.cencostos"
                                  item-value="id"
                                  item-text="nombre"
                                  name="centro de costo"
                                  required
                                  :disabled="detalle.niif ? !detalle.niif.maneja_ccosto : ''"
                                  v-validate="'required'"
                                  :error-messages="errors.collect('centro de costo')"
                                  return-object
                                  :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                  :clearable="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (false) : true"
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
                              <v-flex xs12 sm12 md3 lg3>
                                <v-text-field prefix="$" type="number" v-model.number="detalle.debito" key="débito"
                                              name="débito" required v-validate="'required'"
                                              :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                              :disabled="detalle.credito || (detalle.niif ? detalle.niif.tipo == 'Retencion' : null) || detalle.nf_niif_id == null ? true : false"
                                              :error-messages="errors.collect('débito')" label="Débito" min="0"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md3 lg3>
                                <v-text-field prefix="$" type="number" v-model.number="detalle.credito" key="crédito"
                                              name="crédito" required v-validate="'required'"
                                              :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                              :disabled="detalle.debito || (detalle.niif ? detalle.niif.tipo == 'Retencion' : null) || detalle.nf_niif_id == null ? true : false"
                                              :error-messages="errors.collect('crédito')" label="Crédito" min="0"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-textarea
                                  v-model="detalle.observacion"
                                  color="primary"
                                  name="observaciones"
                                  auto-grow
                                  rows="1"
                                  :disabled="!detalle.nf_niif_id"
                                  :readonly="(comDiario.estado === 'Confirmado' || comDiario.estado === 'Anulado') ? (true) : false"
                                >
                                  <div slot="label">Observaciones</div>
                                </v-textarea>
                              </v-flex>
                            </v-layout>
                            <!--<small>*Indica el campo requerido</small>-->
                          </v-container>
                          <v-card-actions>
                            <v-divider></v-divider>
                            <v-btn flat @click="dialogDetalle()">Cerrar</v-btn>
                            <v-btn v-if="comDiario.estado === 'Registrado' || comDiario.estado === null" color="blue darken-1" @click="agregarComdiaDetalle(detalle)" flat > {{ !detalle.id ? 'Agregar' : 'Actualizar' }}</v-btn>
                          </v-card-actions>
                        </v-form>
                      </v-card>
                    </v-dialog>
<!--                    </v-slide-x-reverse-transition>-->
                  </v-flex>
                  <v-dialog v-model="dialogEliminar" max-width="400">
                    <v-card>
                      <v-card-text class="title font-weight-light">¿Desea eliminar el detalle del comprobante?</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn flat @click="dialogEliminar = false">No</v-btn>
                        <v-btn color="primary" @click="eliminarDetalle(detalle)">Si</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                  <v-flex xs12 sm12 md12 lg12 v-if="comDiario.detalles.length">
                    <v-expand-transition>
                      <v-card>
                        <v-card-title class="grey lighten-3">
                          <h3>Detalles del Comprobante</h3>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-data-table
                          :headers="headers"
                          :items="comDiario.detalles"
                          :loading="tableLoading"
                          :pagination.sync="pagination"
                          hide-actions rows-per-page-text="Registros por página"
                          :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item.niif.nombre }}</td>
                            <td>{{ props.item.tercero ? props.item.tercero.nombre_completo : ''}}</td>
                            <td>{{ props.item.cencosto ? props.item.cencosto.nombre : '' }}</td>
                            <td class="text-xs-right">{{ (props.item.debito ? props.item.debito : '0') | numberFormat(2,'$')}}</td>
                            <td class="text-xs-right">{{ (props.item.credito ? props.item.credito : '0') | numberFormat(2,'$') }}</td>
                            <td class="text-xs-center">
                              <v-btn icon class="mx-0" fab small @click="editarDetalle(props.item)"><v-icon color="accent" v-text="comDiario.estado === 'Registrado' || comDiario.estado === null ? 'edit' : 'remove_red_eye'"></v-icon></v-btn>
                              <v-btn v-if="comDiario.estado === 'Registrado' || comDiario.estado === null" icon class="mx-0" fab small @click="preguntarEliminarDetalle(props.item)"> <v-icon color="accent">delete</v-icon> </v-btn>
                            </td>
                          </template>
                          <template slot="footer">
                            <tr class="green accent-1" :class="{'red lighten-2': totalDetalles.sumasIguales !== 0}">
                              <td></td>
                              <td></td>
                              <td class="text-xs-right">
                                <strong>Totales $</strong>
                              </td>
                              <td class="text-xs-right font-weight-regular">
                                {{ totalDetalles.totalDebito | numberFormat(2) }}
                              </td>
                              <td class="text-xs-right font-weight-regular">
                                {{ totalDetalles.totalCredito | numberFormat(2)}}
                              </td>
                              <td class="text-xs-center font-weight-regular" :class="totalDetalles.clase">
                                {{ totalDetalles.sumasIguales | numberFormat(2)}}
                              </td>
                            </tr>
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
              <v-btn @click="close()">{{(comDiario.estado === 'Registrado' || comDiario.estado === null) ? 'Cancelar' : 'Cerrar'}}</v-btn>
              <v-btn color="primary" v-if="comDiario.estado === 'Registrado' || comDiario.estado === null" @click.prevent="confirmarSave">Guardar</v-btn>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
  </div>
</template>

<script>
    import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
    import {COMDIARIO_RELOAD_ITEM} from '../../../../store/modules/general/tables'
    import {SNACK_SHOW} from '@/store/modules/general/snackbar'
    import store from '../../../../store/complementos/index'
    import CuentaAutocomplete from '../CuentaAutocomplete'
    export default {
      name: 'RegistroComdiario',
      components: {
        PostuladorV2: () => import('@/components/general/PostuladorV2'),
        InputDate: () => import('@/components/general/InputDate'),
        Postulador: () => import('@/components/general/Postulador'),
        CuentaAutocomplete
      },
      props: ['parametros'],
      data () {
        return {
          comDiario: {},
          comDiarioEdicion: {
            id: null,
            consecutivo: null,
            nf_tipcomdiario_id: null,
            fecha: null,
            detalle: null,
            estado: null,
            documento: null,
            detalles: []
          },
          detalle_anulacion: null,
          valorRetencion: 0,
          maxDate: this.moment().format('YYYY-MM-DD'),
          pagination: {},
          headers: [
            {
              text: 'Cuenta',
              align: 'left',
              sortable: false,
              value: 'nf_comdiario_id',
              width: '450px'
            },
            {
              text: 'Tercero',
              align: 'left',
              sortable: false,
              value: 'gn_tercero_id',
              width: '450px'
            },
            {
              text: 'Centro de Costo',
              align: 'left',
              sortable: false,
              value: 'nf_cencosto_id'
            },
            {
              text: 'Débito',
              align: 'center',
              sortable: false,
              value: 'debito',
              width: '150px'
            },
            {
              text: 'Crédito',
              align: 'center',
              sortable: false,
              value: 'credito',
              width: '150px'
            },
            {
              text: 'Acciones/Sumatoria',
              align: 'center',
              sortable: false,
              value: 'id'
            }
          ],
          estados: ['Registrado', 'Confirmado', 'Anulado'],
          detalle: {},
          menuDate: false,
          date: null,
          cardDialog: false,
          dialog: false,
          dialog2: false,
          dialog3: false,
          dialogEliminar: false,
          registrar: false,
          confirmar: false,
          imprime: false,
          tableLoading: false,
          descriptionLimit: 60,
          porcentaje: '',
          porcentajeRTF: null,
          totalDetalles: {
            totalDebito: 0,
            totalCredito: 0,
            sumasIguales: 0,
            clase: null
          },
          auxiliar: {}
        }
      },
      watch: {
        'detalle.niif' (val) {
          if (val === null) {
            this.formReset()
            this.$validator.reset()
          }
        },
        'detalle.retencion' (val) {
          let porcent = this.detalle.conrtf ? this.detalle.conrtf.porcentaje : null
          this.valorRetencion = val ? val * (porcent / 100) : 0
        }
      },
      computed: {
        tituloDetalle () {
          // return !this.detalle.id ? 'Detalle de Comprobante' : 'En edición del detalle con cuenta N° ' + this.detalle.niif.codigo
          return this.statusComdiario().tituloDetalle + (this.detalle.niif !== null ? this.detalle.niif.codigo : '')
        },
        tapTitulo () {
          return this.statusComdiario().titulo
          // return !this.comDiario.id ? 'Nuevo Comprobante de Diario' : 'En Edición'
        },
        complementosFormComDiario () {
          return store.getters.complementosFormComDiario
        },
        pages () {
          if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
          return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        },
        typeObject () {
          return typeof this.object === 'undefined'
        },
        computedDateFormatted () {
          return this.formDate(this.comDiario.fecha)
        },
        colorEstado () {
          return this.statusComdiario().color
        },
        iconoEstado () {
          return this.statusComdiario().icono
        }
      },
      beforeMount () {
        this.refresh()
        this.formReset()
        this.porcentajeRTF = null
        this.sumatoriaDetalles()
      },
      mounted () {
        if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
      },
      methods: {
        statusComdiario () {
          let color = null
          let icono = null
          let titulo = null
          let tituloDetalle = null
          switch (this.comDiario.estado) {
            case 'Registrado':
              color = 'primary'
              icono = 'stars'
              titulo = 'En Edición'
              tituloDetalle = 'En edición del detalle con cuenta N° '
              break
            case 'Confirmado':
              color = 'teal'
              icono = 'check_circle'
              titulo = 'Visualizando'
              tituloDetalle = 'Mostrando detalle con cuenta N° '
              break
            case 'Anulado':
              color = 'red'
              icono = 'remove_circle'
              titulo = 'Visualizando'
              tituloDetalle = 'Mostrando detalle con cuenta N° '
              break
            default:
              color = 'primary'
              icono = 'stars'
              titulo = 'Nuevo Comprobante de Diario'
              tituloDetalle = 'Detalle de Comprobante'
          }
          return {color, icono, titulo, tituloDetalle}
        },
        formReset () {
          this.detalle = {
            nf_niif_id: null,
            nf_comdiario_id: null,
            gn_tercero_id: null,
            nf_cencosto_id: null,
            nf_conrtf_id: null,
            debito: null,
            credito: null,
            retencion: null,
            conrtf: null,
            cencosto: null,
            tercero: null,
            observacion: null,
            niif: null
          }
        },
        getRegisto (id) {
          this.axios.get('comdiarios/' + id).then(response => {
            if (response.data !== '') {
              this.comDiario = response.data.data
            }
            this.sumatoriaDetalles()
          }).catch((e) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el comprobante de Diario. ' + e.response, color: 'danger'})
          })
        },
        formDate (date) {
          if (!date) return null

          const [year, month, day] = date.split('-')
          return `${year}/${month}/${day}`
        },
        // Sumatoria
        sumatoriaDetalles () {
          var debitoTotal = 0
          var creditoTotal = 0
          this.totalDetalles.clase = 'green--text'
          this.comDiario.detalles.forEach((detalle) => {
            debitoTotal += detalle.credito
            creditoTotal += detalle.debito
          })

          this.totalDetalles.totalDebito = debitoTotal
          this.totalDetalles.totalCredito = creditoTotal
          this.totalDetalles.sumasIguales = debitoTotal - creditoTotal
          if (this.totalDetalles.sumasIguales !== 0) {
            this.totalDetalles.clase = 'red--text'
          }
        },
        editarDetalle (item) {
          this.cardDialog = true
          this.detalle = JSON.parse(JSON.stringify(item))
        },
        preguntarEliminarDetalle (item) {
          this.dialogEliminar = true
          this.detalle = JSON.parse(JSON.stringify(item))
        },
        eliminarDetalle (detalle) {
          this.dialogEliminar = false
          this.comDiario.detalles.splice(this.comDiario.detalles.indexOf(detalle), 1)
          this.formReset()
          this.sumatoriaDetalles()
        },
        // Cal Retención
        calcularRetencion () {
          this.detalle.credito = null
          this.porcentajeRTF = null
          if (this.detalle.conrtf && this.detalle.retencion) {
            this.porcentaje = this.obtenerPorcentajeRtf()
            this.detalle.credito = (this.detalle.retencion) * (this.porcentaje / 100)
            this.porcentajeRTF = this.porcentaje
          }
        },
        obtenerPorcentajeRtf () {
          var conceptoRtf = this.detalle.conrtf
          var vBase = this.detalle.retencion
          if (conceptoRtf.manejo === 'Base') {
            if (conceptoRtf.base_minima <= vBase) {
              return conceptoRtf.porcentaje
            }
            return 0
          } else if (conceptoRtf.manejo === 'Variable') {
            if (conceptoRtf.base_minima <= vBase) {
              return conceptoRtf.porcentaje
            }
            return 0
          } else {
            var detalleRTF = this.ubicarDetalle(vBase)
            return detalleRTF.porcentaje
          }
        },
        ubicarDetalle (vBase) {
          var detalleRtf2 = ''
          this.detalle.conrtf.detalles.forEach((detalle2) => {
            detalle2.activo = false
            if (vBase >= detalle2.valor_inicial) {
              detalleRtf2 = detalle2
            }
          })
          return detalleRtf2
        },
        dialogDetalle (val) {
          // this.formReset()
          if (val === 'abrir') {
            this.cardDialog = true
          } else {
            this.formReset()
            this.$validator.reset()
            this.cardDialog = false
          }
        },
        async agregarComdiaDetalle (detalle) {
          // let errorCuenta = await this.$refs.buscarCuenta.validate()
          // let errorTercero = await this.$refs.postulaTercero.validate()
          if (await this.validador('formDetalleComdiario')) {
            if (detalle.id) {
              this.comDiario.detalles.splice(this.comDiario.detalles.findIndex(item => item.id === detalle.id), 1)
              this.comDiario.detalles.push(detalle)
              // this.cardDialog = !this.cardDialog
            } else {
              this.comDiario.detalles.push(detalle)
            }
            this.sumatoriaDetalles()
            this.formReset()
          } else {
            if (this.detalle.nf_niif_id === null) {
              this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado una cuenta.', color: 'error'})
            } else if (this.detalle.niif.maneja_tercero && this.detalle.gn_tercero_id == null) {
              this.$store.commit(SNACK_SHOW, {msg: 'La cuenta seleccionada maneja tercero.', color: 'error'})
            } else if (this.detalle.niif.maneja_ccosto && this.detalle.nf_cencosto_id == null) {
              this.$store.commit(SNACK_SHOW, {msg: 'La cuenta seleccionada maneja centro de costo.', color: 'error'})
            } else if (this.detalle.niif.tipo === 'Retencion' && this.detalle.nf_conrtf_id == null) {
              this.$store.commit(SNACK_SHOW, {msg: 'La cuenta es de tipo retención.', color: 'error'})
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
          this.comDiario = JSON.parse(JSON.stringify(this.comDiarioEdicion))
        },
        // Modales Confirmar y registrar
        async confirmarSave (val) {
          if (await this.validador('formPrincipal') && this.comDiario.detalles > 1 && this.totalDetalles.sumasIguales === 0) {
            this.dialog2 = true
          } else {
            if (this.comDiario.detalles.length < 2) {
              this.$store.commit(SNACK_SHOW, {msg: 'Se debe agregar al menos una cuenta para acreditar y una para debitar', color: 'error'})
            } else if (this.totalDetalles.sumasIguales !== 0) {
              this.$store.commit(SNACK_SHOW, {msg: 'El total de débito debe ser igual al total de crédito', color: 'error'})
            } else {
              this.dialog2 = true
            }
          }
        },
        confirmarEstado (val) {
          if (val === 'yesConfirmar') {
            if (this.confirmar && this.imprime) {
              this.comDiario.estado = 'Confirmado'
              this.imprimirComprobante()
            } else {
              this.comDiario.estado = 'Confirmado'
            }
            this.dialog = false
            this.dialog2 = false
            this.save()
            this.$store.commit(SNACK_SHOW, {msg: 'El estado del comprobante ha sido Confirmado', color: 'success'})
          } else {
            if (this.imprime) {
              this.imprimirComprobante()
            }
            this.confirmar = false
            this.dialog = false
            this.dialog2 = false
            this.comDiario.estado = 'Registrado'
            this.save()
            this.$store.commit(SNACK_SHOW, {msg: 'El estado del comprobante ha sido Registrado', color: 'success'})
          }
        },
        enviarCambio () {
          if (this.confirmar) {
            this.dialog = true
          } else if (this.confirmar && this.imprime) {
            this.dialog = true
          } else if (this.imprime) {
            this.comDiario.estado = 'Registrado'
            this.imprimirComprobante()
            this.save()
          } else if (this.comDiario.estado === 'Anulado') {
            this.dialog3 = true
          } else if (this.comDiario.estado === 'Anulado' && this.imprime) {
            this.dialog3 = true
          } else {
            this.comDiario.estado = 'Registrado'
            this.save()
            this.$store.commit(SNACK_SHOW, {msg: 'El estado del comprobante ha sido Registrado', color: 'success'})
          }
        },
        esAnulado (comdiario, detalleAnulacion) {
          this.dialog3 = false
          this.dialog2 = false
          comdiario.detalle_anulacion = detalleAnulacion
          this.save()
        },
        imprimirComprobante () {
          this.dialog2 = false
          let id = this.comDiario.id
          this.axios.get('reportecomdiarios?id=' + id)
            .then(res => {
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
        save () {
          delete this.porcentajeRTF
          this.$validator.validateAll().then(result => {
            if (result) {
              const typeRequest = this.comDiario.id ? 'editar' : 'crear'
              this.axios.post('comdiarios', this.comDiario).then(response => {
                if (this.comDiario.id) {
                  this.$store.commit(SNACK_SHOW, {
                    msg: 'El comprobante de diario se actualizó correctamente',
                    color: 'success'
                  })
                } else {
                  this.$store.commit(SNACK_SHOW, {
                    msg: 'El comprobante de diario se creó correctamente',
                    color: 'success'
                  })
                }
                this.$store.commit(COMDIARIO_RELOAD_ITEM, {
                  item: response.data.comdiario,
                  estado: typeRequest,
                  key: this.parametros.key
                })
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {
                  msg: 'Hay un error al guardar el registro. ' + e.response.data.errors,
                  color: 'danger'
                })
              })
            }
          })
        }
      }
    }
</script>

<style scoped>

</style>
