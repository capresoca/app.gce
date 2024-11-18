<template>
  <v-card>
    <loading v-model="loading" />
    <v-toolbar dense>
      <v-icon>{{ 'assignment_late' }}</v-icon>
      <v-toolbar-title class="text-capitalize">{{ (!item.id ? 'Nueva ' : 'Edición de ') + 'autorización' }}</v-toolbar-title>
    </v-toolbar>
    <v-container fluid grid-list-sm>
      <v-layout row wrap>
        <v-flex xs12 sm6 md4>
          <v-menu
            ref="menuFechaOrden"
            v-model="menuDate"
            :return-value.sync="item.fecha_orden"
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
              label="Fecha orden médica"
              v-model="item.fecha_orden"
              prepend-icon="event"
              name="Fecha orden médica"
              v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
              :error-messages="errors.collect('Fecha orden médica')"
              readonly
            />
            <v-date-picker
              locale="es-co"
              v-model="item.fecha_orden"
              :max="maxDate"
              :min="minDate"
              @input="$refs['menuFechaOrden'].save(item.fecha_orden)"
              @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'Fecha orden médica')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
            />
          </v-menu>
        </v-flex>
        <v-flex xs12 sm6 md8>
          <postulador-v2
            no-data="Búsqueda por razon social u código de habilitación."
            hint="tercero.identificacion_completa"
            item-text="nombre"
            data-title="tercero.identificacion_completa"
            data-subtitle="nombre"
            label="IPS Origen"
            entidad="entidades"
            preicon="location_city"
            @changeid="val => item.entidad_origen_id = val"
            v-model="item.entidad_origen"
            route-params="rs_tipentidade_id=1"
            name="IPS Origen"
            rules="required"
            v-validate="'required'"
            :error-messages="errors.collect('IPS Origen')"
            no-btn-create
            :min-characters-search="3"
          />
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>person</v-icon>
                Datos del afiliado
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <dialog-red-servicios v-if="item && item.afiliado" :afiliado-id="item.afiliado.id"></dialog-red-servicios>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <v-flex xs12 :sm8="item && item.afiliado" :md9="item && item.afiliado">
                  <postulador-v2
                    no-data="Búsqueda por nombre o número de documento."
                    hint="identificacion_completa"
                    item-text="nombre_completo"
                    data-title="identificacion_completa"
                    data-subtitle="nombre_completo"
                    label="Afiliado"
                    entidad="afiliados"
                    v-model="item.afiliado"
                    @changeid="val => item.as_afiliado_id = val"
                    name="Afiliado"
                    rules="required|afiliadoValidoAutorizacion"
                    v-validate="'required|afiliadoValidoAutorizacion'"
                    :error-messages="errors.collect('Afiliado')"
                    :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
                    :slot-prepend='item && item.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                    no-btn-edit
                    no-btn-create
                  />
                </v-flex>
                <template v-if="item && item.afiliado">
                  <input-detail-flex
                    flex-class="xs12 sm4 md3"
                    label="Regimen"
                    underline
                    :text="item.afiliado.regimen.codigo + ' - ' + item.afiliado.regimen.nombre"
                  />
                  <v-flex xs12 sm6 md4>
                    <v-autocomplete
                      label="Municipio residencia"
                      v-model="item.datosAfiliado.gn_municipio_id"
                      :items="complementos.municipios"
                      item-value="id"
                      item-text="nombre"
                      name="Municipio residencia"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Municipio residencia')"
                      :filter="filterMunicipios"
                    >
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.nombre"/>
                            <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-select
                      key="select-zona"
                      label="Zona"
                      v-model="item.datosAfiliado.gn_zona_id"
                      :items="complementos.zonas"
                      item-value="id"
                      item-text="nombre"
                      name="Zona"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Zona')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="item.datosAfiliado.gn_zona_id === 2">
                    <v-autocomplete
                      key="autocomple-vereda"
                      label="Vereda"
                      v-model="item.datosAfiliado.gn_vereda_id"
                      :items="veredas"
                      item-value="id"
                      item-text="nombre"
                      name="Vereda"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Vereda')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="item.datosAfiliado.gn_zona_id === 1">
                    <v-autocomplete
                      key="autocomple-barrio"
                      label="Barrio"
                      v-model="item.datosAfiliado.gn_barrio_id"
                      :items="barrios"
                      item-value="id"
                      item-text="nombre"
                      name="Barrio"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Barrio')"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 md6>
                    <v-text-field
                      key="input-direccion"
                      v-upper="'item.datosAfiliado.direccion'"
                      label="Dirección"
                      v-model="item.datosAfiliado.direccion"
                      name="Dirección"
                      required
                      v-validate="'required|verify_direccion'"
                      :error-messages="errors.collect('Dirección')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-text-field
                      key="input-correo"
                      label="Correo electrónico"
                      v-model="item.datosAfiliado.correo_electronico"
                      name="Correo electrónico"
                      v-validate="'required|email'"
                      data-vv-validate-on="input|change|custom|blur"
                      :error-messages="errors.collect('Correo electrónico')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-text-field
                      key="input-celular"
                      label="Celular"
                      v-model="item.datosAfiliado.celular"
                      name="Celular"
                      v-number="'item.datosAfiliado.celular'"
                      data-vv-validate-on="input|change|custom|focus|blur"
                      v-validate="'required|numeric'"
                      :error-messages="errors.collect('Celular')"
                    />
                  </v-flex>
                </template>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>

        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>fas fa-first-aid</v-icon>
                Impresión diagnóstica
              </v-toolbar-title>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <v-flex xs12>
                  <postulador-v2
                    ref="postuladorCie10P"
                    no-data="Búsqueda por descripción o código."
                    hint="codigo"
                    item-text="descripcion"
                    data-title="descripcion"
                    data-subtitle="codigo"
                    label="Diagnóstico Principal"
                    entidad="rs_cie10s"
                    preicon="local_hospital"
                    v-model="item.cie10_principal"
                    @changeid="val => item.cie10_principal_id = val"
                    name="Diagnóstico Principal"
                    rules="required"
                    v-validate="'required'"
                    :error-messages="errors.collect('Diagnóstico Principal')"
                    no-btn-edit
                    no-btn-create
                  />
                </v-flex>
                <v-flex xs12>
                  <postulador-v2
                    ref="postuladorCie10S"
                    no-data="Búsqueda por descripción o código."
                    hint="codigo"
                    item-text="descripcion"
                    data-title="descripcion"
                    data-subtitle="codigo"
                    label="Diagnóstico Relacionado 1"
                    entidad="rs_cie10s"
                    preicon="queue"
                    v-model="item.cie10_rel1"
                    @changeid="val => item.cie10_rel1_id = val"
                    clearable
                    no-btn-edit
                    no-btn-create
                  />
                </v-flex>
                <v-flex xs12>
                  <postulador-v2
                    ref="postuladorCie10T"
                    no-data="Búsqueda por descripción o código."
                    hint="codigo"
                    item-text="descripcion"
                    data-title="descripcion"
                    data-subtitle="codigo"
                    label="Diagnóstico Relacionado 2"
                    entidad="rs_cie10s"
                    preicon="queue"
                    v-model="item.cie10_rel2"
                    @changeid="val => item.cie10_rel2_id = val"
                    clearable
                    no-btn-edit
                    no-btn-create
                  />
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs12 sm6 md4>
          <v-select
            label="Tipo origen"
            v-model="item.tipo_origen"
            :items="complementos.tiposOrigen"
            name="Tipo origen"
            v-validate="'required'"
            :error-messages="errors.collect('Tipo origen')"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md8>
          <v-autocomplete
            no-data="Búsqueda por razon social, código de habilitación o número de contrato."
            prepend-icon="location_city"
            label="IPS Destino"
            v-model="item.contrato"
            :items="contratosActivos"
            return-object
            item-value="id"
            item-text="contrato.entidad.nombre"
            :filter="filterIPSDestino"
            persistent-hint
            :disabled="contratosActivos.length === 0"
            :hint="item.contrato && contratosActivos.length ? ('Plan cobertura: ' + contratosActivos.find(x => x.id === item.contrato.id).nombre) : ''"
          >
            <template slot="selection" slot-scope="data">
              <v-list-tile-title v-html="data.item.contrato.entidad.nombre + ' - Contrato No. ' + data.item.contrato.numero_contrato"/>
            </template>
            <template slot="item" slot-scope="data">
              <template>
                <v-list-tile-content>
                  <v-list-tile-title v-html="data.item.contrato.entidad.nombre + ' - Contrato No. ' + data.item.contrato.numero_contrato"/>
                  <v-list-tile-sub-title v-html="'Plan cobertura: ' + data.item.nombre"/>
                </v-list-tile-content>
              </template>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm6 md4>
          <v-select
            label="Tipo destino"
            v-model="item.tipo_destino"
            :items="complementos.tiposOrigen"
            name="Tipo destino"
            v-validate="'required'"
            :error-messages="errors.collect('Tipo destino')"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md8>
          <v-autocomplete
            label="Servicio"
            v-model="item.rs_servicio_id"
            :items="serviciosContratados"
            item-value="id"
            item-text="servicio.nombre"
            name="Servicio"
            v-validate="'required'"
            :error-messages="errors.collect('Servicio')"
            :filter="filterServicios"
            persistent-hint
            :hint="item.rs_servicio_id && serviciosContratados.length ? ('Código: ' + serviciosContratados.find(x => x.id === item.rs_servicio_id).servicio.codigo) : ''"
          >
            <template slot="item" slot-scope="data">
              <template>
                <v-list-tile-content>
                  <v-list-tile-title v-html="data.item.servicio.codigo + ' - ' + data.item.servicio.nombre"/>
                </v-list-tile-content>
              </template>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm6 md4>
          <v-autocomplete
            label="Modalidad servicio"
            v-model="item.au_modservicio_id"
            :items="complementos.modalidadesServicio"
            item-value="id"
            item-text="tipo"
            name="Modalidad servicio"
            v-validate="'required'"
            :error-messages="errors.collect('Modalidad servicio')"
            :filter="filterModalidades"
          >
            <template slot="item" slot-scope="data">
              <template>
                <v-list-tile-content>
                  <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.tipo"/>
                  <v-list-tile-sub-title v-html="data.item.modalidad"/>
                </v-list-tile-content>
              </template>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex dflex>
          <v-checkbox v-model="item.alto_costo" label="Alto costo" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox v-model="item.pyp" label="P y P" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox v-model="item.atencion_materna" label="Atención materna" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox v-model="item.enfermedad_trasmisible" label="Enfermedad trasmisible" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox v-model="item.catastrofe" label="Catastrofe" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="tutelas.length">
          <v-layout align-center fill-height>
            <v-flex xs1 dflex>
              <v-checkbox v-model="marcaTutela" :label="marcaTutela ? '' : 'Tutela'"></v-checkbox>
            </v-flex>
            <v-flex dflex>
              <v-slide-x-reverse-transition mode="out-in">
                <v-select
                  label="Tutela"
                  v-if="marcaTutela"
                  v-model="item.oj_tutela_id"
                  :items="tutelas"
                  item-value="id"
                  item-text="tipo"
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.no_tutela + ' - ' + data.item.tipo_tutela"/>
                        <v-list-tile-sub-title v-html="data.item.fecha_notificacion + ' | Estado actual: ' + data.item.estado"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                  <v-slide-x-reverse-transition
                    slot="append-outer"
                    mode="out-in"
                  >
                    <v-tooltip top v-if="item.oj_tutela_id">
                      <v-icon
                        slot="activator"
                        color="success"
                        key="icon-detalle-tutela"
                        @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutela', titulo: 'Detalle tutela', parametros: {entidad: {id: item.oj_tutela_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                        v-text="'visibility'"
                      ></v-icon>
                      <span>Ver detalle</span>
                    </v-tooltip>
                  </v-slide-x-reverse-transition>
                </v-select>
              </v-slide-x-reverse-transition>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs12>
          <v-textarea
            v-model="item.observaciones"
            auto-grow
            label="Observaciones"
            rows="1"></v-textarea>
        </v-flex>
        <v-flex xs12>
          <v-slide-x-reverse-transition mode="out-in">
            <v-card v-if="item.contrato">
              <v-toolbar dense class="elevation-0">
                <v-icon>assignment</v-icon>
                <v-toolbar-title>Detalles de la autorización</v-toolbar-title>
                <small class="mt-2 ml-1"> Medicamentos, Procedimientos, Otros</small>
              </v-toolbar>
              <v-container fluid grid-list-xl class="py-0">
                <v-layout>
                  <v-flex xs12 sm5 md3 v-if="!item.detalles.length">
                    <v-select
                      label="Tipo"
                      v-model="tipoDetalle"
                      return-object
                      :items="tiposDetalle"
                      item-value="id"
                      item-text="text"
                      @change="$refs.postuladorDetalle.reset()"
                    />
                  </v-flex>
                  <v-flex xs12 :sm7="!item.detalles.length" :md9="!item.detalles.length" v-if="!calculoPagoError && !multiplesTiposError">
                    <postulador-v2
                      ref="postuladorDetalle"
                      no-data="Búsqueda por código, descripción."
                      item-text="descripcion"
                      data-title="descripcion"
                      data-subtitle="codigo"
                      :label="tipoDetalle.text"
                      :entidad="tipoDetalle.ruta"
                      :route-params="'rs_contrato_id=' + item.rs_contrato_id"
                      preicon="local_offer"
                      v-model="registroDetalle"
                      no-btn-create
                      no-btn-edit
                      :min-characters-search="3"
                    />
                  </v-flex>
                </v-layout>
              </v-container>
              <v-data-table
                :headers="headersDetails"
                :items="item.detalles"
                hide-actions
                class="elevation-1 table-detalle-solicitud"
                no-data-text="No hay detalles registrados"
              >
                <template slot="headers" slot-scope="props">
                  <tr>
                    <th
                      v-for="(header, index) in props.headers"
                      :key="header.text"
                      v-if="index !== 1 || (index === 1 && calculoPagoError !== null)"
                    >
                      {{ header.text }}
                    </th>
                  </tr>
                </template>
                <template slot="items" slot-scope="props">
                  <td class="text-xs-center" width="20px">
                    <v-tooltip top>
                      <v-btn
                        flat
                        icon
                        color="error"
                        class="ma-0"
                        slot="activator"
                        @click="item.detalles.splice(props.index, 1)"
                      >
                        <v-icon>close</v-icon>
                      </v-btn>
                      <span>Borrar registro</span>
                    </v-tooltip>
                  </td>
                  <td class="text-xs-center" width="20px" v-if="calculoPagoError !== null">
                    <v-tooltip top>
                      <v-icon @click="" :color="props.item.status ? 'success' : 'error'" slot="activator">{{props.item.status ? 'verified_user' : 'warning'}}</v-icon>
                      <span>{{props.item.status ? 'OK' : 'En conflicto'}}</span>
                    </v-tooltip>
                  </td>
                  <td>
                    {{ props.item.rs_cum_id ? 'Medicamento' : props.item.rs_cups_id ? 'Procedimiento' : 'Otro' }}
                    <v-tooltip top v-if="props.item.requiere_aprobacion">
                      <v-btn
                        flat
                        icon
                        class="ma-0"
                        color="warning"
                        slot="activator"
                      >
                        <v-icon>warning</v-icon>
                      </v-btn>
                      <span>Requiere aprobación</span>
                    </v-tooltip>
                  </td>
                  <td>{{ props.item.codigo }}</td>
                  <td>{{ props.item.descripcion }}</td>
                  <td class="text-xs-center" width="200px">
                    <v-text-field
                      :key="props.index"
                      :name="'cantidad' + props.index"
                      data-vv-as="Cantidad"
                      v-validate="'required|min_value:1'"
                      min="1"
                      type="number"
                      v-model.number="props.item.cantidad_solicitada"
                      :error-messages="errors.collect('cantidad' + props.index)"
                      @input="reloadDetail(props.item)"
                    />
                  </td>
                  <td class="text-xs-right">{{'$'}}{{ props.item.valor | numberFormat(2) }}</td>
                  <td class="text-xs-right">{{'$'}}{{ props.item.valorTotal | numberFormat(2) }}</td>
                </template>
                <template slot="footer">
                    <template v-if="!calculoPagoError && !multiplesTiposError">
                      <tr>
                        <td :colspan="headersDetails.length - 2" class="text-xs-right">
                          <strong>Valor EPS</strong>
                        </td>
                        <td class="text-xs-right">
                          {{'$'}}{{ valorEPS | numberFormat(2) }}
                        </td>
                      </tr>
                      <tr>
                        <td :colspan="headersDetails.length - 2" class="text-xs-right">
                          <strong>Valor Usuario</strong>
                        </td>
                        <td class="text-xs-right">
                          {{'$'}}{{ valorUsuario | numberFormat(2) }}
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr>
                        <td :colspan="calculoPagoError ? (headersDetails.length) : (headersDetails.length -1)" class="text-xs-center">
                          <v-alert
                            :value="true"
                            type="error"
                          >
                            <span v-if="multiplesTiposError">{{multiplesTiposError}}</span>
                            <span v-if="calculoPagoError">{{calculoPagoError}}</span>
                          </v-alert>
                        </td>
                      </tr>
                    </template>
                    <tr>
                      <td :colspan="headersDetails.length - 2" class="text-xs-right">
                        <strong>TOTAL</strong>
                      </td>
                      <td class="text-xs-right">
                        {{'$'}}{{ valorTotal | numberFormat(2) }}
                      </td>
                    </tr>
                </template>
              </v-data-table>
            </v-card>
          </v-slide-x-reverse-transition>
        </v-flex>
        <v-flex v-show="generaSolicitud" xs12 sm7 md9>
          <input-file
            ref="ordenMedica"
            label="Orden Médica"
            v-model="orden_medica"
            :required="generaSolicitud"
            accept="application/pdf"
            :hint="'Extenciones aceptadas: pdf'"
            prepend-icon="description"
          />
        </v-flex>
        <v-flex v-show="generaSolicitud" xs12 sm12 md12>
          <input-file
            ref="historiaClinica"
            label="Historia Clínica"
            v-model="historia_clinica"
            :required="generaSolicitud"
            accept="application/pdf"
            :hint="'Extenciones aceptadas: pdf'"
            prepend-icon="description"
          />
        </v-flex>
      </v-layout>
      <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaConfirmacion" :requiere-motivo="false" @aceptar="aceptaConfirmacion" />
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-btn @click="refresh(null)">Limpiar</v-btn>
      <v-spacer/>
      <v-btn @click="guardarConfirmar" color="primary">Guardar y Confirmar</v-btn>
      <v-btn @click="submit" color="primary">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {AU_AUTORIZACION_RELOAD_ITEM, AU_SOLICITUD_AUTORIZACION_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import { Validator } from 'vee-validate'
  import DialogRedServicios from '@/components/misional/redservicios/DialogRedServicios'
  export default {
    name: 'RegistroAutorizacion',
    props: ['parametros'],
    components: {
      DialogRedServicios,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputFile: () => import('@/components/general/InputFile'),
      Confirmar: () => import('@/components/general/Confirmar'),
      Loading
    },
    filters: {
    },
    data () {
      return {
        flagMounted: false,
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        loading: false,
        loadingContratos: false,
        filterIPSDestino (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.contrato.entidad.nombre + ' ' + item.contrato.numero_contrato)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterModalidades (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.tipo + ' ' + item.modalidad)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterServicios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.servicio.codigo + ' ' + item.servicio.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        headersDetails: [
          { text: '', align: 'center', sortable: false },
          { text: 'Status', align: 'center', sortable: false },
          { text: 'Tipo', sortable: false },
          { text: 'Código', sortable: false },
          { text: 'Descripción', sortable: false },
          { text: 'Cantidad', align: 'center', sortable: false },
          { text: 'Valor unitario', align: 'right', sortable: false },
          { text: 'Valor total', align: 'right', sortable: false }
        ],
        registroDetalle: null,
        tipoDetalle: {id: 1, ruta: 'cumcontratados', text: 'Medicamentos'},
        tiposDetalle: [{id: 1, ruta: 'cumcontratados', text: 'Medicamentos'}, {id: 2, ruta: 'cupcontratados', text: 'Procedimientos'}, {id: 3, ruta: 'otroscontratados', text: 'Otros'}],
        marcaTutela: false,
        fab: false,
        idAfiliadoValido: null,
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        item: null,
        makeAutorizacion: {
          id: null,
          as_afiliado_id: null,
          as_regimen_id: null,
          entidad_origen_id: null,
          tipo_origen: null,
          tipo_destino: null,
          cie10_principal_id: null,
          cie10_rel1_id: null,
          cie10_rel2_id: null,
          au_modservicio_id: null,
          rs_contrato_id: null,
          rs_servicio_id: null,
          orden_medica: null,
          fecha_orden: null,
          historia_clinica: null,
          oj_tutela_id: null,
          alto_costo: 0,
          pyp: 0,
          atencion_materna: 0,
          enfermedad_trasmisible: 0,
          catastrofe: 0,
          estado: 'Registrada',
          observaciones: null,
          // Objetos
          afiliado: null,
          contrato: null,
          servicio: null,
          entidad_origen: null,
          cie10_principal: null,
          cie10_rel1: null,
          cie10_rel2: null,
          detalles: [],
          datosAfiliado: {
            gn_municipio_id: null,
            gn_zona_id: null,
            gn_vereda_id: null,
            gn_barrio_id: null,
            direccion: null,
            correo_electronico: null,
            celular: null
          }
        },
        veredas: [],
        barrios: [],
        orden_medica: null,
        historia_clinica: null,
        contratosActivos: [],
        serviciosContratados: [],
        tutelas: [],
        cuotaCopagos: null,
        rangoActivo: null,
        calculoPagoError: null,
        multiplesTiposError: null,
        tipoValorPermitido: null,
        valorTotal: 0,
        valorEPS: 0,
        valorUsuario: 0,
        generaSolicitud: false
      }
    },
    watch: {
      'item.datosAfiliado.gn_municipio_id' (val) {
        if (val) {
          this.item.datosAfiliado.gn_zona_id && this.item.datosAfiliado.gn_zona_id === 2 ? this.getVeredas() : this.getBarrios()
          if (this.flagMounted) {
            this.item.datosAfiliado.gn_barrio_id = null
            this.item.datosAfiliado.gn_vereda_id = null
          }
        }
      },
      'item.datosAfiliado.gn_zona_id' (val) {
        if (val) {
          this.item.datosAfiliado.gn_municipio_id && val === 2 ? this.getVeredas() : this.getBarrios()
          if (this.flagMounted) {
            this.item.datosAfiliado.gn_barrio_id = null
            this.item.datosAfiliado.gn_vereda_id = null
          }
        }
      },
      'contratosActivos' (val) {
        let contrato = val.find(x => x.id === this.item.rs_contrato_id)
        if (!contrato) this.item.contrato = null
      },
      'item.contrato' (val, prev) {
        if (val) {
          this.item.rs_contrato_id = val.id
          let servicios = val.servicios_contratados ? val.servicios_contratados : []
          let servicio = servicios.find(x => x.id === this.item.rs_servicio_id)
          this.item.rs_servicio_id = servicio ? servicio.id : null
          this.serviciosContratados = servicios
          if (prev && val !== prev) {
            this.item.detalles = []
            this.$refs.postuladorDetalle.reset()
          }
        } else {
          this.item.detalles = []
          this.item.rs_contrato_id = null
          this.serviciosContratados = []
          this.item.rs_servicio_id = null
        }
      },
      async 'item.afiliado' (val, prev) {
        if (val) {
          this.item.afiliado.total_autorizado = await this.getTotalAutorizado()
          this.getContratos()
          if (this.cuotaCopagos === null) this.cuotaCopagos = await this.getCuotaCopagos()
          this.getRangoActivo()
          this.tutelas = val.tutelas ? val.tutelas : []
          this.reloadPagos()
        }
        if (prev && val !== prev) {
          this.item.cie10_principal = null
          this.item.cie10_rel1 = null
          this.item.cie10_rel2 = null
          this.$refs.postuladorCie10P.reset()
          this.$refs.postuladorCie10S.reset()
          this.$refs.postuladorCie10T.reset()
        }
        if (val) {
          this.flagMounted = false
          this.item.datosAfiliado.gn_municipio_id = val.gn_municipio_id
          this.item.datosAfiliado.gn_zona_id = val.gn_zona_id
          this.item.datosAfiliado.gn_vereda_id = val.gn_vereda_id
          this.item.datosAfiliado.gn_barrio_id = val.gn_barrio_id
          this.item.datosAfiliado.direccion = val.direccion
          this.item.datosAfiliado.correo_electronico = val.correo_electronico
          this.item.datosAfiliado.celular = val.celular
          setTimeout(() => { this.flagMounted = true }, 500)
        }
      },
      'tutelas' (val) {
        if (val) {
          let tutela = val.find(x => x.id === this.item.oj_tutela_id)
          this.item.oj_tutela_id = tutela ? tutela.id : null
          this.marcaTutela = this.item.oj_tutela_id !== null
        }
      },
      async 'registroDetalle' (val) {
        if (val && this.item.detalles) {
          let copyVal = await JSON.parse(JSON.stringify(val))
          let registro = this.item.detalles.find(x =>
            (x.rs_cum_id === (copyVal.rs_cum_id ? copyVal.id : null)) &&
            (x.rs_cups_id === (copyVal.rs_cups_id ? copyVal.id : null)) &&
            (x.rs_otros_id === (copyVal.rs_otros_id ? copyVal.id : null))
          )
          if (registro) {
            registro.cantidad_solicitada++
            registro.valorTotal = registro.valor * registro.cantidad_solicitada
          } else {
            let makeRegistroDetalle = {
              au_autaprobada_id: null,
              au_autorizacion_id: this.item.id,
              au_autsolicitud_id: null,
              cantidad_aprobada: null,
              cantidad_solicitada: 1,
              codigo: copyVal.codigo,
              descripcion: copyVal.descripcion,
              id: null,
              observaciones: null,
              rs_cum_id: copyVal.rs_cum_id ? copyVal.id : null,
              rs_cups_id: copyVal.rs_cups_id ? copyVal.id : null,
              rs_otros_id: copyVal.rs_otros_id ? copyVal.id : null,
              tipo_valor: copyVal.tipo_valor,
              valor: copyVal.tarifa,
              valorTotal: copyVal.tarifa,
              valor_usuario: null,
              status: !this.tipoValorPermitido ? true : this.tipoValorPermitido === copyVal.tipo_valor,
              requiere_aprobacion: copyVal.nivel_autorizacion ? (copyVal.nivel_autorizacion && copyVal.nivel_autorizacion !== 'Asesor') : false
            }
            this.item.detalles.unshift(makeRegistroDetalle)
          }
          this.valorTotal = this.getValorTotal()
          this.registroDetalle = null
        }
      },
      'okChecks' () {
        this.reloadPagos()
      },
      'item.detalles' (val) {
        if (val) {
          if (val.length) {
            this.tipoValorPermitido = val[val.length - 1].tipo_valor
            this.reloadStatus()
          } else {
            this.tipoValorPermitido = null
          }
          this.generaSolicitud = !!val.find(x => x.requiere_aprobacion)
          this.valorTotal = this.getValorTotal()
        }
      },
      'valorTotal' () {
        this.reloadPagos()
      },
      'multiplesTipos.length' (val, prev) {
        if (this.$refs.postuladorDetalle && prev !== 0) this.$refs.postuladorDetalle.reset()
        if (val === 1) {
          this.tipoDetalle = this.tiposDetalle.find(x => x.id === this.multiplesTipos[0])
        }
      },
      'generaSolicitud' (val) {
        if (!val) {
          this.orden_medica = null
          this.item.orden_medica = null
          this.historia_clinica = null
          this.item.historia_clinica = null
          this.item.fecha_orden = null
          if (this.$refs.ordenMedica) this.$refs.ordenMedica.reset()
          if (this.$refs.historiaClinica) this.$refs.historiaClinica.reset()
          let index = this.$validator.errors.items.findIndex(x => x.field === 'Fecha orden médica')
          this.$validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
        }
      }
    },
    computed: {
      multiplesTipos () {
        let tipos = []
        if (this.item && this.item.detalles) {
          this.item.detalles.forEach(x => {
            tipos.push(x.rs_cum_id ? 1 : x.rs_cups_id ? 2 : 3)
          })
        }
        return window.lodash.uniq(tipos)
      },
      complementos () {
        return store.getters.complementosFormAutorizacion
      },
      okChecks () {
        return this.item && (this.item.alto_costo || this.item.pyp || this.item.atencion_materna || this.item.enfermedad_trasmisible || this.item.catastrofe)
      }
    },
    created () {
      this.refresh()
      if (this.parametros.entidad && this.parametros.entidad.id) {
        this.getData()
      } else {
        this.flagMounted = true
      }
    },
    beforeMount () {
      Validator.extend('afiliadoValidoAutorizacion', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.item && this.item.as_afiliado_id && this.item.afiliado)
              ? (this.item.afiliado.estado === 3)
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 3).nombre}.`}
              : {valido: true, mensaje: null}
            return resolve({
              valid: response.valido,
              data: {
                message: response.mensaje
              }
            })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
      Validator.extend('verify_direccion', {
        getMessage: field => `El campo dirección no debe contener carácteres especiales.`,
        validate: value => {
          var strongRegex = new RegExp('^[a-zA-Z0-9_ ]*$')
          return strongRegex.test(value)
        }
      })
    },
    methods: {
      reloadStatus () {
        this.item.detalles.forEach(x => {
          x.status = (x.tipo_valor === this.tipoValorPermitido)
        })
      },
      getRangoActivo () {
        if (this.cuotaCopagos && this.cuotaCopagos.length && this.item.afiliado) {
          if (this.item.afiliado.as_regimene_id === 2) {
            this.rangoActivo = this.cuotaCopagos.find(x => x.grupo === 'Sisben Nivel 2')
          } else {
            let salMinimosCotizante = (this.item.afiliado.ingreso_total / this.cuotaCopagos[0].salminimo.valor)
            let rangosContributivos = JSON.parse(JSON.stringify(this.cuotaCopagos))
            rangosContributivos.splice(this.cuotaCopagos.findIndex(x => x.grupo === 'Sisben Nivel 2'), 1)
            this.rangoActivo = rangosContributivos.find(x =>
              (x.limite_inferior_salario === 'Incluido' ? (salMinimosCotizante >= x.salminimos_inicio) : (salMinimosCotizante > x.salminimos_inicio)) &&
              (x.limite_superior_salario === 'Incluido' ? (salMinimosCotizante <= x.salminimos_fin) : (salMinimosCotizante < x.salminimos_fin))
            )
          }
        }
      },
      getValorTotal () {
        return window.lodash.sum(this.item.detalles.map(x => x.valorTotal))
      },
      async reloadPagos () {
        if (this.multiplesTipos.length > 1) {
          this.calculoPagoError = null
          this.multiplesTiposError = 'Solo se pueden asignar items que pertenezcan a un mismo tipo.'
        } else {
          this.multiplesTiposError = null
          // if (!this.rangoActivo || (this.tipoValorPermitido !== null && this.item.detalles.length && this.item.afiliado.as_regimene_id === 1 && this.item.detalles.filter(x => x.tipo_valor !== this.tipoValorPermitido).length)) {
          if (!this.rangoActivo || (this.tipoValorPermitido !== null && this.item.detalles.length && this.item.detalles.filter(x => x.tipo_valor !== this.tipoValorPermitido).length)) {
            if (!this.rangoActivo) {
              this.calculoPagoError = 'No existen rangos configurados o el afiliado no aplica a ninguno de los rangos, ésto es indispensable para realizar el calculo de la liquidación de la autorización.'
            } else {
              this.calculoPagoError = 'No se puede generar Copago y Cuota moderadora en una misma autorización, hay items que infringen éste mandato. Para continuar, remueva los items con status "En conflicto".'
            }
          } else {
            this.calculoPagoError = null
            if (this.item.afiliado && ((this.item.afiliado.as_regimene_id === 2 && this.item.afiliado.nivel_sisben >= 2 && !this.okChecks) || (this.item.afiliado.as_regimene_id === 1))) {
              if ((this.item.afiliado.as_regimene_id === 2 && this.item.afiliado.nivel_sisben >= 2)) {
                this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
                this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
              } else {
                if (this.tipoValorPermitido === 'Copago') {
                  if (this.okChecks) {
                    this.valorUsuario = 0
                  } else {
                    if (this.item.afiliado.as_tipafiliado_id === 1) {
                      this.valorUsuario = 0
                    } else {
                      this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
                      this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
                    }
                  }
                } else {
                  this.valorUsuario = this.rangoActivo.cuota_moderadora
                }
              }
            } else {
              this.valorUsuario = 0
            }
          }
        }
        this.valorEPS = this.valorTotal - this.valorUsuario
      },
      getValorUsuarioTopes (valor) {
        // regla tope por evento
        let valorTemporal = JSON.parse(JSON.stringify(valor))
        valorTemporal = (valorTemporal > this.rangoActivo.limite_evento) ? this.rangoActivo.limite_evento : valorTemporal
        // regla tope año
        let diferencia = (valorTemporal + this.item.afiliado.total_autorizado) - this.rangoActivo.limite_anio
        valorTemporal = (diferencia > 0) ? (valorTemporal - diferencia) : valorTemporal
        return valorTemporal
      },
      getContratos () {
        if (this.item.afiliado) {
          this.loadingContratos = true
          this.axios.get(`contratos?${this.item.afiliado.portabilidad_activa ? 'portabilidad' : this.item.afiliado.as_regimene_id === 1 ? 'contributivo' : 'subsidiado'}=1`)
            .then((response) => {
              if (response.data !== '') {
                this.contratosActivos = response.data.data
                this.loadingContratos = false
              }
            })
            .catch(e => {
              this.loadingContratos = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los contratos para realizar la autorización. `, error: e})
            })
        }
      },
      reloadDetail (detail) {
        detail.valorTotal = detail.cantidad_solicitada * detail.valor
        this.valorTotal = this.getValorTotal()
      },
      getTotalAutorizado () {
        return new Promise((resolve) => {
          this.axios.get(`afiliados/${this.item.afiliado.id}?copago_anual=1`)
            .then((response) => {
              if (response.data !== '') {
                resolve(response.data.data.copago_anual)
              }
            })
            .catch(e => {
              resolve(0)
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer el valor autorizado anual del afiliado. `, error: e})
            })
        })
      },
      getCuotaCopagos () {
        return new Promise((resolve) => {
          this.axios.get(`cuotacopagos`)
            .then((response) => {
              if (response.data !== '') {
                resolve(response.data.data)
              }
            })
            .catch(e => {
              resolve(false)
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los registros activos de cuotas y copagos. `, error: e})
            })
        })
      },
      async getData () {
        this.loading = true
        this.axios.get(`${'autorizaciones'}/${this.parametros.entidad.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.idAfiliadoValido = JSON.parse(JSON.stringify(response.data.data.as_afiliado_id))
              response.data.data.detalles.forEach(x => {
                x.valorTotal = x.cantidad_solicitada * x.valor
                x.status = true
              })
              response.data.data.datosAfiliado = this.makeAutorizacion.datosAfiliado
              this.orden_medica = response.data.data.orden_medica ? response.data.data.orden_medica.nombre : null
              this.historia_clinica = response.data.data.historia_clinica ? response.data.data.historia_clinica.nombre : null
              this.item = response.data.data
              this.loading = false
              setTimeout(() => { this.flagMounted = true }, 500)
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los datos de la autorización. `, error: e})
          })
      },
      refresh () {
        this.item = JSON.parse(JSON.stringify(this.makeAutorizacion))
        this.idAfiliadoValido = null
        if (this.$refs.ordenMedica) this.$refs.ordenMedica.reset()
        if (this.$refs.historiaClinica) this.$refs.historiaClinica.reset()
        this.$validator.reset()
      },
      getBase64 (file) {
        return new Promise((resolve, reject) => {
          const reader = new FileReader()
          reader.readAsDataURL(file)
          reader.onload = () => resolve(reader.result)
          reader.onerror = error => reject(error)
        })
      },
      async guardarConfirmar () {
        if (await this.validado()) {
          this.dialogA.content = 'Si decide Aceptar, el formulario se procesará y no podrá editar estos datos.'
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      async aceptaConfirmacion () {
        this.item.estado = 'Confirmada'
        await this.submit()
        this.cancelaConfirmacion()
      },
      async validado () {
        let errorOrdenMedica = await this.$refs.ordenMedica.validate()
        let errorHistoriaClinica = await this.$refs.historiaClinica.validate()
        let errorForm = await this.$validator.validateAll()
        let errorDetails = this.item.detalles.length
        if (!errorDetails) {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay que registrar al menos un detalle.', color: 'warning'})
        }
        return (errorForm && errorDetails && errorHistoriaClinica && errorOrdenMedica && !this.calculoPagoError && !this.multiplesTiposError)
      },
      async submit () {
        if (await this.validado()) {
          this.loading = true
          if (this.orden_medica instanceof File) {
            this.item.orden_medica = {
              id: null,
              name: this.orden_medica.name,
              size: this.orden_medica.size,
              type: this.orden_medica.type,
              string: await this.getBase64(this.orden_medica)
            }
          }
          if (this.historia_clinica instanceof File) {
            this.item.historia_clinica = {
              id: null,
              name: this.historia_clinica.name,
              size: this.historia_clinica.size,
              type: this.historia_clinica.type,
              string: await this.getBase64(this.historia_clinica)
            }
          }
          let typeRequest = this.item.id ? 'editar' : 'crear'
          let request = typeRequest === 'editar' ? this.axios.put(`autorizaciones/${this.item.id}`, this.item) : this.axios.post('autorizaciones', this.item)
          request
            .then(response => {
              this.$store.commit(AU_AUTORIZACION_RELOAD_ITEM, {item: response.data.autorizacion_o, estado: 'reload', key: this.parametros.key})
              this.$store.commit(AU_SOLICITUD_AUTORIZACION_RELOAD_ITEM, {item: response.data.solicitud_o, estado: 'reload', key: this.parametros.key})
              if (this.item.estado === 'Confirmada') {
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                setTimeout(() => {
                  this.$store.commit('NAV_ADD_ITEM', {ruta: 'detalleAutorizacion', titulo: 'Detalle autorización', parametros: {entidad: response.data.autorizacion_o, tabOrigin: null}})
                }, 200)
              } else {
                this.item.id = response.data.autorizacion.id
              }
              this.loading = false
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: `al guardar el registro de la autorización.`, error: e})
            })
        } else {
          this.item.estado = 'Registrada'
        }
      },
      getVeredas () {
        if (this.item.datosAfiliado.gn_municipio_id && this.item.datosAfiliado.gn_zona_id === 2) {
          if (!(this.veredas.length && (this.item.datosAfiliado.gn_municipio_id === this.veredas[0].gn_municipio_id))) {
            this.axios.get('complementos/veredas/' + this.item.datosAfiliado.gn_municipio_id)
              .then((response) => {
                this.veredas = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las veredas. ', error: e})
              })
          }
        }
      },
      getBarrios () {
        if (this.item.datosAfiliado.gn_municipio_id && this.item.datosAfiliado.gn_zona_id === 1) {
          if (!(this.barrios.length && (this.item.datosAfiliado.gn_municipio_id === this.barrios[0].gn_municipio_id))) {
            this.axios.get('complementos/barrios/' + this.item.datosAfiliado.gn_municipio_id)
              .then((response) => {
                this.barrios = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los barrios. ', error: e})
              })
          }
        }
      }
    }
  }
</script>

<style>
  .table-detalle-solicitud table tbody td input{
    text-align: center !important;
  }
</style>
