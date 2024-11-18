<template>
  <v-card>
    <toolbar-list :title="'Novedad'" subtitle="ns - nc"/>
    <v-data-table
      :headers="headers"
      :items="novedades"
      class="elevation-1"
      :expand="expand"
      item-key="consecutivo_ns"
      hide-actions
    >
      <template v-slot:items="props">
        <tr>
          <td>{{ props.item.fecha_inicio_novedad }}</td>
          <td class="text-xs-left">{{ props.item.codigo_novedad }}</td>
          <td>{{ `${props.item.tipo_identificacion_afiliado} ${props.item.numero_identificacion_afiliado}` }}</td>
          <td>
            <v-tooltip right>
              <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                <v-list-tile-content class="truncate-content">
                  <v-list-tile-title slot="activator" class="body-2">{{props.item.informacion_ns}}</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
              <span>{{props.item.informacion_ns}}</span>
            </v-tooltip>
          </td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn
                small class="mx-0 elevation-0"
                fab
                slot="activator" @click="openExpandDetalleNovedad(props, !expand ? 'uno' : 'tres')">
                <v-icon :color="'green'" v-text="'fas fa-search'"></v-icon>
              </v-btn>
              <span v-text="'Ver Detalle'"></span>
            </v-tooltip>
            <v-tooltip top>
              <v-btn
                small class="mx-0 elevation-0"
                fab
                slot="activator"
                :disabled="isNovedadPermitidad"
                @click="openExpandDetalleNovedad(props, 'dos')">
                <v-icon :color="'accent'" v-text="'fas fa-trash'"></v-icon>
              </v-btn>
              <span v-text="'Eliminar'"></span>
            </v-tooltip>
          </td>
        </tr>
      </template>
      <template v-slot:expand="props">
        <v-card flat>
          <loading v-model="loading"></loading>
          <toolbar-list :title="'Datos Depuración'" subtitle=""/>
          <v-container fluid grid-list-xl class="pt-0">
            <v-form data-vv-scope="formDepuracion">
              <v-divider class="gray mb-3"></v-divider>
              <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                  <v-autocomplete
                    label="Tipo documento"
                    v-model="datosBasicosAfiliado.gn_tipdocidentidad_id"
                    :items="complementos.tipdocidentidades"
                    item-value="id"
                    item-text="nombre"
                    name="gn_tipocidentidad_id"
                    data-vv-as="Tipo identificación"
                    required
                    disabled
                    v-validate="'required'"
                    :error-messages="errors.collect('gn_tipocidentidad_id')"
                    :filter="filterTiposIdentidad"
                    prepend-icon="person_pin"
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.abreviatura"></v-list-tile-title>
                          <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.identificacion'"
                    label="Identificación"
                    v-model="datosBasicosAfiliado.identificacion"
                    name="Identificación"
                    required
                     
                    :type="tipo_identidad"
                    v-validate="'max:' + longitudDocumento + (tipo_identidad === 'text' ? '|alpha_num' : '|numeric') + '|required'"
                    :error-messages="errors.collect('Identificación')"
                    disabled
                    :maxlength="longitudDocumento"
                    :counter="longitudDocumento"
                    prepend-icon="subtitles"
                  >
                  </v-text-field>
                </v-flex>
              </v-layout>
              <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.apellido1'"
                    label="Primer apellido"
                    v-model="datosBasicosAfiliado.apellido1"
                    name="Primer apellido"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Primer apellido')"
                    disabled
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.apellido2'"
                    label="Segundo apellido"
                    v-model="datosBasicosAfiliado.apellido2"
                    disabled
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.nombre1'"
                    label="Primer nombre"
                    v-model="datosBasicosAfiliado.nombre1"
                    name="Primer nombre"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Primer nombre')"
                    disabled
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.nombre2'"
                    label="Segundo nombre"
                    v-model="datosBasicosAfiliado.nombre2"
                    disabled
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <input-date
                    v-model="datosBasicosAfiliado.fecha_nacimiento"
                    label="Fecha nacimiento (Día-Mes-Año)"
                    format="DD-MM-YYYY"
                    disabled
                    name="Fecha nacimiento"
                    v-validate="'required|fechaValida:DD-MM-YYYY'"
                    :error-messages="errors.collect('Fecha nacimiento')"
                  />
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-autocomplete
                    label="Municipio"
                    v-model="datosBasicosAfiliado.gn_municipio_id"
                    :items="complementos.municipios"
                    item-value="id"
                    item-text="nombre"
                    name="Municipio"
                    required
                    disabled
                    v-validate="'required'"
                    :error-messages="errors.collect('Municipio')"
                    :filter="filterMunicipiosExpedicion"
                    prepend-icon="location_city">
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"></v-list-tile-title>
                          <v-list-tile-sub-title v-html="data.item.nombre_departamento"></v-list-tile-sub-title>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                  <!--                <template v-if="manager === 'Afiliado'">-->
                  <!--                  <input-date-->
                  <!--                    v-model="tercero.fecha_expedicion"-->
                  <!--                    label="Fecha expedicion (Día-Mes-Año)"-->
                  <!--                    format="DD-MM-YYYY"-->
                  <!--                    name="Fecha expedición"-->
                  <!--                    v-validate="'required|fechaValida:DD-MM-YYYY'"-->
                  <!--                    :error-messages="errors.collect('Fecha expedición')"-->
                  <!--                    :disabled="!verified"-->
                  <!--                  />-->
                  <!--                </template>-->
                </v-flex>
                <v-flex xs12 ms6 md3>
                  <v-menu
                    ref="menuFechaIni"
                    v-model="menuDateIni"
                    :return-value.sync="novtramite.fecha_ini_novedad"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    disabled
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <v-text-field
                      slot="activator"
                      label="Fecha novedad"
                      v-model="novtramite.fecha_ini_novedad"
                      prepend-icon="event"
                      disabled
                      name="Fecha novedad"
                      v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDateIniNovedad + ',' + maxDateIniNovedad + ',true'"
                      :error-messages="errors.collect('Fecha novedad')"
                      readonly
                    />
                    <v-date-picker
                      locale="es-co"
                      v-model="novtramite.fecha_ini_novedad"
                      :max="maxDateIniNovedad"
                      :min="minDateIniNovedad"
                      disabled
                      @input="$refs['menuFechaIni'].save(novtramite.fecha_ini_novedad)"
                      @change="() => {
                          let index = $validator.errors.items.findIndex(x => x.field === 'Fecha novedad')
                          $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                          }"
                    />
                  </v-menu>
                </v-flex>
              </v-layout>
              <v-divider class="red"></v-divider>
              <v-layout row wrap v-if="tipoNovedad">
                <template v-for="(campo, i) in (tipoNovedad && tipoNovedad.campos ? tipoNovedad.campos : null)">
                  <v-flex :class="campo.flex_class">
                    <v-select
                      v-if="campo.tipo === 'select' && getProperty((campo.param_first === 'internal' ? $this : complementosNovedad), campo.referencia_complemento.split('.'))"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      v-model="novtramite['v' + (i + 1)]"
                      :item-value="campo.value_select"
                      :item-text="campo.text_select"
                      :items="getProperty((campo.param_first === 'internal' ? $this : complementosNovedad), campo.referencia_complemento.split('.'))"
                      :name="campo.nombre"
                      :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                      :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                      v-validate="campo.validacion"
                      :error-messages="errors.collect(campo.nombre)"
                    ></v-select>
                    <v-autocomplete
                      v-if="campo.tipo === 'autocomplete'"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      v-model="novtramite['v' + (i + 1)]"
                      :item-value="campo.value_select"
                      :item-text="campo.text_select"
                      :items="getProperty((campo.param_first === 'internal' ? $this : complementosNovedad), campo.referencia_complemento.split('.'))"
                      :name="campo.nombre"
                      :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                      :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                      v-validate="campo.validacion"
                      :error-messages="errors.collect(campo.nombre)"
                      :no-filter="!campo.referencia_filtro"
                      :filter="campo.referencia_filtro ? $this[campo.referencia_filtro] : () => false"
                    >
                      <template :slot="(campo.title_item_select || campo.subtitle_item_select) ? 'item' : ''" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="getProperty(data.item, campo.title_item_select.split('.'))"/>
                            <v-list-tile-sub-title v-if="campo.subtitle_item_select" v-html="getProperty(data.item, campo.subtitle_item_select.split('.'))"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                    <v-text-field
                      v-upper="'novtramite.v' + (i + 1)"
                      v-if="campo.tipo === 'text'"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      v-model="novtramite['v' + (i + 1)]"
                      :name="campo.nombre"
                      :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                      v-validate="campo.validacion"
                      :error-messages="errors.collect(campo.nombre)"
                    />
                    <v-text-field
                      v-if="campo.tipo === 'number'"
                      :label="campo.nombre"
                      type="number"
                      :key="campo.tipo + i + campo.nombre"
                      v-model.number="novtramite['v' + (i + 1)]"
                      :min="campo.param_first"
                      :max="campo.param_second"
                      :name="campo.nombre"
                      required
                      v-validate="'required|numeric|max_value:100'"
                      :error-messages="errors.collect(campo.nombre)"
                    />
                    <v-menu
                      v-if="campo.tipo === 'date'"
                      :ref="campo.nombre"
                      :key="campo.tipo + i + campo.nombre + 'menu'"
                      :close-on-content-click="false"
                      v-model="menuDatex"
                      :nudge-right="40"
                      :return-value.sync="novtramite['v' + (i + 1)]"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        :key="campo.tipo + i + campo.nombre + 'vtext'"
                        v-model="novtramite['v' + (i + 1)]"
                        :label="campo.nombre"
                        :name="campo.nombre"
                        :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                        :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                        v-validate="campo.validacion"
                        :error-messages="errors.collect(campo.nombre)"
                        prepend-icon="event"
                        readonly
                      />
                      <v-date-picker
                        v-model="novtramite['v' + (i + 1)]"
                        @input="$refs[campo.nombre][0].save(novtramite['v' + (i + 1)])"
                        @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === campo.nombre)
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                      />
                    </v-menu>

                    <!--IPS-->
                    <postulador-v2
                      v-if="campo.tipo === 'ipslite'"
                      no-data="Busqueda por NIT, razon social u código de habilitación."
                      hint="tercero.identificacion_completa"
                      item-text="tercero.nombre_completo"
                      data-title="tercero.identificacion_completa"
                      data-subtitle="tercero.nombre_completo"
                      :label="campo.nombre"
                      entidad="entidades"
                      preicon="location_city"
                      v-model="novtramite.afiliado.ips"
                      @changeid="val => novtramite['v' + (i + 1)] = val"
                      :name="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      :rules="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                      v-validate="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                      :error-messages="errors.collect(campo.nombre)"
                      :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                      no-btn-edit
                      no-btn-create
                      route-params="rs_tipentidade_id=1"
                    />
                    <!--Afiliado-->
                    <postulador-v2
                      v-if="campo.tipo === 'afiliado'"
                      no-data="Busqueda por nombre o número de documento."
                      hint="identificacion_completa"
                      item-text="nombre_completo"
                      data-title="identificacion_completa"
                      data-subtitle="nombre_completo"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      :detail="detalleAfiliado"
                      entidad="afiliados"
                      preicon="person"
                      v-model="afiliado"
                      @changeid="val => novtramite['v' + (i + 1)] = val"
                      :name="campo.nombre"
                      :route-params="campo.param_second ? campo.param_second : null"
                      :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                      :rules="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                      v-validate="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                      :error-messages="errors.collect(campo.nombre)"
                      no-btn-edit
                      :no-btn-create ="!(campo.param_first && campo.param_first === 'lite')"
                    />
                    <!--Pagador-->
                    <postulador-v2
                      v-if="campo.tipo === 'pagador'"
                      no-data="Busqueda por nombre o número de documento."
                      item-text="razon_social"
                      data-title="identificacion"
                      data-subtitle="razon_social"
                      hint="identificacion"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      :detail="detallePagador"
                      entidad="pagadores"
                      preicon="person"
                      v-model="pagador"
                      @changeid="val => novtramite['v' + (i + 1)] = val"
                      :name="campo.nombre"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect(campo.nombre)"
                      no-btn-edit
                    />
                    <!--Identificación-->
                    <v-text-field
                      v-if="campo.tipo === 'identification'"
                      :label="campo.nombre"
                      :key="campo.tipo + i + campo.nombre"
                      v-model="novtramite['v' + (i + 1)]"
                      :name="campo.nombre"
                      required
                      v-validate="'uniqueAfiliado:' + novtramite.afiliado.id + ',' + novtramite.afiliado.identificacion + '|required|alpha_num|max:' + longitudDocumento"
                      :error-messages="errors.collect(campo.nombre)"
                      :maxlength="longitudDocumento"
                      :counter="longitudDocumento"
                    />
                    <!--Fecha de nacimiento-->
                    <v-menu
                      v-if="campo.tipo === 'birthdate'"
                      :key="campo.tipo + i + campo.nombre + 'bb'"
                      :ref="campo.nombre"
                      v-model="menuDate"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        :key="campo.tipo + i + campo.nombre + 'aa'"
                        :label="campo.nombre"
                        v-model="novtramite['v' + (i + 1)]"
                        :name="campo.nombre"
                        required
                        v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                        :error-messages="errors.collect(campo.nombre)"
                        return-masked-value
                        mask="####-##-##"
                        @click="menuDate = false"
                        readonly
                      />
                      <v-date-picker
                        locale="es-co"
                        :key="campo.tipo + i + campo.nombre + 'cc'"
                        ref="picker"
                        v-model="novtramite['v' + (i + 1)]"
                        :max="maxDate"
                        :min="minDate"
                        @input="$refs[campo.nombre][0].save(novtramite['v' + (i + 1)])"
                        @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === campo.nombre)
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        menuDate = false
                        }"
                      />
                    </v-menu>
                  </v-flex>
                </template>

                <!-- Fecha Inicio Novedad-->

                <!--              <v-flex xs12>-->
                <!--                <v-textarea-->
                <!--                  v-model="novtramite.observaciones"-->
                <!--                  auto-grow-->
                <!--                  label="Observaciones"-->
                <!--                  rows="1"></v-textarea>-->
                <!--              </v-flex>-->
              </v-layout>
            </v-form>
          </v-container>
          <v-card-actions>
            <v-btn @click="refresh('cancel')">Cancelar</v-btn>
            <v-spacer></v-spacer>
<!--            <div style="text-align: end !important;">-->
<!--              <v-switch-->
<!--                class="mr-4"-->
<!--                color="success"-->
<!--                label="Enviar Fosyga"-->
<!--                v-model="novtramite.fosyga"-->
<!--              ></v-switch>-->
<!--            </div>-->
            <v-btn color="primary" @click.prevent="saveDepuracion">Actualizar</v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-data-table>
    <detalle-eliminacion ref="eliminarneg" @eliminar="datos => aceptarEliminar(datos)"></detalle-eliminacion>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import DetalleEliminacion from './DetalleEliminacion'
  export default {
    name: 'RegistroDepuracionBDUA',
    props: ['parametros'],
    components: {
      DetalleEliminacion,
      InputDate: () => import('@/components/general/InputDate'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data: () => ({
      expand: false,
      labels: [],
      causales: null,
      afiliado: null,
      pagador: null,
      component: null,
      novedades: [],
      novtramite: null,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      menuDateIni: false,
      menuDatex: false,
      menuDate: false,
      minDateIniNovedad: null,
      maxDateIniNovedad: null,
      novedad_id: null,
      idNovedadAnterior: null,
      tipoNovedad: null,
      loading: false,
      datosBasicosAfiliado: {
        gn_tipdocidentidad_id: null,
        identificacion: null,
        nombre1: null,
        nombre2: null,
        apellido1: null,
        apellido: null,
        fecha_nacimiento: null,
        gn_municipio_id: null
      },
      makeNovtramite: {
        id: null,
        as_tramite_id: null,
        as_tipnovedade_id: null,
        as_afiliado_id: null,
        fecha_ini_novedad: null,
        v1: null,
        v2: null,
        v3: null,
        v4: null,
        v5: null,
        v6: null,
        v7: null,
        afiliado: null,
        tipoNovedad: null,
        tiposNovedad: [],
        observaciones: null,
        fosyga: true,
        consecutivo_ns_procesada: null
      },
      headers: [
        {
          text: 'Fecha NEG',
          align: 'left',
          sortable: false,
          value: 'fecha_inicio_novedad'
        },
        {
          text: 'Novedad',
          align: 'left',
          sortable: false,
          value: 'codigo_novedad'
        },
        {
          text: 'Reporte',
          sortable: false,
          value: 'afiliado'
        },
        {
          text: 'Glosa',
          sortable: false,
          value: 'informacion_ns'
        },
        {
          text: 'Opciones',
          align: 'center',
          sortable: false,
          value: 'id'
        }
      ],
      isNovedadPermitidad: false,
      $this: null,
      filterPoblacionEspecial: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre),
      filterAportantes: (item, queryText) => this.getFilter(item, queryText, item.tercero.nombre_completo + ' ' + item.tercero.identificacion_completa),
      filterMunicipios: (item, queryText) => this.getFilter(item, queryText, item.nombre + ' ' + item.nombre_departamento),
      filterMunicipiosExpedicion: (item, queryText) => this.getFilter(item, queryText, item.nombre + ' ' + item.nombre_departamento),
      filterTiposIdentidad: (item, queryText) => this.getFilter(item, queryText, item.abreviatura + ' ' + item.nombre),
      filterCiius: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre)
    }),
    computed: {
      complementosNovedad: () => store.getters.complementosFormNovtramite,
      complementos () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))
        if (beforeComplement.tipdocidentidades) {
          beforeComplement.tipdocidentidades = beforeComplement.tipdocidentidades.filter(x => x.id !== 12)
        }
        return beforeComplement
      },
      tipo_identidad () {
        return (this.datosBasicosAfiliado.gn_tipdocidentidad_id === 1 || this.datosBasicosAfiliado.gn_tipdocidentidad_id === 12) ? 'number' : 'text'
      },
      longitudDocumento () {
        if (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && this.datosBasicosAfiliado.gn_tipdocidentidad_id) {
          return this.complementos.tipdocidentidades.find(tipo => tipo.id === this.datosBasicosAfiliado.gn_tipdocidentidad_id).longitud
        }
      }
    },
    watch: {
      'novtramite' (val) {
        console.log('DATA', val)
        if (val) {
          val.id = null
          this.datosBasicosAfiliado = val
        }
      },
      'novedad_id' (val) {
        if (val) {
          this.axios.get(`bduas/tipo_novedad/campos/${val}`)
            .then(response => {
              console.log('response', response)
              this.tipoNovedad = response.data.data
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el tipo de novedad. ', error: e})
            })
        }
      }
    },
    created () {
      this.minDateIniNovedad = this.moment().subtract(365, 'days').format('YYYY-MM-DD')
      this.maxDateIniNovedad = this.moment().format('YYYY-MM-DD')
    },
    beforeMount () {
      this.$this = this
      this.refresh()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      aceptarEliminar (data) {
        console.log('Data', data)
        this.axios.delete(`bduas/eliminar-estado/${this.idNovedadAnterior}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableDepuracionNovedades')
            this.$store.commit('SNACK_SHOW', {msg: 'Se ha eliminado la glosa de novedad.', color: 'success'})
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al intentar eliminar la glosa de novedad. ', error: e})
          })
      },
      async saveDepuracion () {
        if (await this.$validator.validateAll('formDepuracion')) {
          this.loading = true
          let novedadCopy = JSON.parse(JSON.stringify(this.datosBasicosAfiliado))
          novedadCopy.fosyga = false
          delete novedadCopy.afiliado
          delete novedadCopy.tipoNovedad
          delete novedadCopy.tiposNovedad
          console.log('ddkkdd', this.novtramite)
          const typeRequest = !novedadCopy.id ? this.axios.post(`bduas/depuraciones?id_novedadAnterior=${this.idNovedadAnterior}`, novedadCopy) : null
          typeRequest.then(response => {
            console.log('reponse', response)
            this.$store.commit('reloadTable', 'tableDepuracionNovedades')
            this.$store.commit('SNACK_SHOW', {msg: 'La depuración en la glosa de novedad se realizó éxitoamente.', color: 'success'})
            this.loading = false
          }).catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar el trámite de novedad. ', error: e})
          })
        }
      },
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      getProperty: (item, arr) => window.toProperty(item, arr),
      getCampos () {
        this.labels = []
        this.causales = null
        this.afiliado = null
        this.pagador = null
        this.novtramite.v1 = null
        this.novtramite.v2 = null
        this.novtramite.v3 = null
        this.novtramite.v4 = null
        this.novtramite.v5 = null
        this.novtramite.v6 = null
        this.novtramite.v7 = null

        this.novtramite.tipoNovedad && this.novtramite.tipoNovedad.campos.forEach((campo, index) => {
          this.novtramite['v' + (index + 1)] = campo.propiedad ? this.getProperty(this.novtramite, campo.propiedad.split('.')) : null
        })
      },
      getRegistro ($id) {
        this.axios.get(`bduas/depuraciones/${$id}`)
          .then(response => {
            console.log('respuesta', response)
            let log = JSON.parse(JSON.stringify(response.data.data.log))
            let codigoNovedad = log.codigo_novedad
            if ((codigoNovedad === 'N05') || (codigoNovedad === 'N06') || (codigoNovedad === 'N07') || (codigoNovedad === 'N08') || (codigoNovedad === 'N09') || (codigoNovedad === 'N10') || (codigoNovedad === 'N11') || (codigoNovedad === 'N14')) {
              this.isNovedadPermitidad = true
            }
            this.idNovedadAnterior = log.consecutivo_ns
            this.novedades.push(response.data.data.log)
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el registro. ', error: e})
          })
      },
      openExpandDetalleNovedad (props, info) {
        if (info === 'uno') {
          this.loading = false
          this.novtramite = JSON.parse(JSON.stringify(this.makeNovtramite))
          let idNovtramite = JSON.parse(JSON.stringify(props.item.consecutivo_ns))
          let codigo = JSON.parse(JSON.stringify(props.item.codigo_novedad))
          if (props.expanded === false) {
            this.$validator.reset()
            this.novtramite = null
          }
          this.axios.get(`bduas/validacion-estado/${idNovtramite}?codigo_novedad=${codigo}`)
            .then(response => {
              console.log('response', response.data)
              this.novtramite = response.data.data
              this.novedad_id = this.novtramite.novedad ? this.novtramite.novedad.codigo : null
              props.expanded = !props.expanded
              this.loading = false
            }).catch(e => {
              props.expanded = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el registro. ', error: e})
            })
        } else if (info === 'dos') {
          this.$refs.eliminarneg.assign(JSON.parse(JSON.stringify(props)))
        } else {
          props.expanded = false
          this.refresh()
        }
      },
      refresh (num = null) {
        this.$validator.reset()
        this.novtramite = JSON.parse(JSON.stringify(this.makeNovtramite))
        if (num === 'cancel') this.$store.commit('reloadTable', 'tableDepuracionNovedades')
      },
      hayCambios (type) {
        if (type === 19) return (this.novtramite.v1 !== this.novtramite.afiliado.tipo_id.id || this.novtramite.v2 !== this.novtramite.afiliado.identificacion || this.novtramite.v3 !== this.novtramite.afiliado.fecha_nacimiento)
        if (type === 20) return (this.novtramite.v1 !== this.novtramite.afiliado.nombre1 || this.novtramite.v2 !== this.novtramite.afiliado.nombre2)
        if (type === 21) return (this.novtramite.v1 !== this.novtramite.afiliado.apellido1 || this.novtramite.v2 !== this.novtramite.afiliado.apellido2)
        if (type === 22) return (this.novtramite.v1 !== this.novtramite.afiliado.gn_municipio_id)
        if (type === 25) return (this.novtramite.v1 !== this.novtramite.afiliado.as_parentesco_id || this.novtramite.v2 !== this.novtramite.afiliado.as_condicion_discapacidades_id || this.novtramite.v3 !== this.novtramite.afiliado.cabfamilia_id)
        if (type === 30) return (this.novtramite.v1 !== this.novtramite.afiliado.as_condicion_discapacidades_id)
        if (type === 32) return (this.novtramite.v1 !== this.novtramite.afiliado.estado)
        if (type === 37) return (this.novtramite.v1 !== this.novtramite.afiliado.nivel_sisben || this.novtramite.v2 !== this.novtramite.afiliado.puntaje_sisben)
        if (type === 38) return (this.novtramite.v1 !== this.novtramite.afiliado.as_pobespeciale_id)
        if (type === 40) return (this.novtramite.v1 !== this.novtramite.afiliado.rs_entidade_id)
        if (type === 41) return (this.novtramite.v1 !== this.novtramite.afiliado.as_pobespeciale_id || this.novtramite.v2 !== this.novtramite.afiliado.nivel_sisben || this.novtramite.v3 !== this.novtramite.afiliado.puntaje_sisben)
        if (type === 42) return (this.novtramite.v1 !== this.novtramite.afiliado.as_parentesco_id || this.novtramite.v2 !== this.novtramite.afiliado.as_condicion_discapacidades_id)
        if (type === 44) return (this.novtramite.v1 !== this.novtramite.afiliado.fecha_afiliacion)
        if ((type === 23) || (type === 24) || (type === 26) || (type === 27) || (type === 28) || (type === 29) || (type === 34) || (type === 35) || (type === 36) || (type === 43)) return true
      },
      asignarDatosBasicos (item) {
        this.datosBasicosAfiliado.gn_tipdocidentidad_id = item.gn_tipdocidentidad_id
        this.datosBasicosAfiliado.identificacion = item.identificacion
        this.datosBasicosAfiliado.nombre1 = item.nombre1
        this.datosBasicosAfiliado.nombre2 = item.nombre2
        this.datosBasicosAfiliado.apellido2 = item.apellido2
        this.datosBasicosAfiliado.apellido1 = item.apellido1
        this.datosBasicosAfiliado.fecha_nacimiento = item.fecha_nacimiento
        this.datosBasicosAfiliado.gn_municipio_id = item.gn_municipio_id
      }
    }
  }
</script>

<style scoped>

</style>
