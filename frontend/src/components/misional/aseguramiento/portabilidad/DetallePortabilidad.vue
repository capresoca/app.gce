<template>
  <v-card>
    <v-toolbar dense>
      <v-list-tile class="content-v-list-tile-p0">
        <v-list-tile-avatar color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="font-weight-bold title">Detalle de portabilidad</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <template v-if="portabilidad">
        <v-spacer></v-spacer>
        <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading">{{portabilidad.consecutivo}}</span>
        </v-chip>
      </template>
    </v-toolbar>
    <v-container fluid grid-list-sm>
      <loading v-model="loading"></loading>
      <v-slide-y-transition group>
        <template v-if="portabilidad">
          <v-layout row wrap key="seccion1">
            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Estado</v-list-tile-sub-title>
                <span>{{ portabilidad.estado }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Identificación</v-list-tile-sub-title>
                <span>{{ portabilidad.afiliado.identificacion_completa }}</span>
              </v-list-tile-content>
            </v-flex>
            <v-flex xs3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Afiliado</v-list-tile-sub-title>
                <span>{{ portabilidad.afiliado.nombre_completo }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Municipio</v-list-tile-sub-title>
                <span>{{ `${portabilidad.municipio_receptor.nombre}, ${portabilidad.municipio_receptor.nombre_departamento}` }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Dirección</v-list-tile-sub-title>
                <span>{{ portabilidad.direccion }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs1>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Telefono</v-list-tile-sub-title>
                <span>{{ portabilidad.telefono }}</span>
              </v-list-tile-content>
            </v-flex>

          </v-layout>
          <v-layout row wrap key="seccion2">
            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha solicitud</v-list-tile-sub-title>
                <span>{{ portabilidad.fecha_solicitud }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Inicio portabilidad</v-list-tile-sub-title>
                <span>{{ portabilidad.fecha_inicio }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs2>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fin portabilidad</v-list-tile-sub-title>
                <span>{{ portabilidad.fecha_fin }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 v-if="portabilidad.observaciones !== null">
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Observaciones</v-list-tile-sub-title>
                <span>{{ portabilidad.observaciones }}</span>
              </v-list-tile-content>
            </v-flex>
          </v-layout>
          <v-layout row wrap class="mt-3" key="seccion3" >
            <v-flex xs12>
              <v-card flat v-if="portabilidadCopy">
                <v-list two-line>
                  <template>
                    <v-divider/>
                    <v-list-tile>
                      <v-list-tile-avatar color="success">
                        <v-icon dark>input</v-icon>
                      </v-list-tile-avatar>
                      <v-list-tile-content>
                        <v-list-tile-title>
                          Respuesta
                        </v-list-tile-title>
                        <v-list-tile-sub-title v-if="portabilidadCopy.estado === 'Registrado'">Estado: {{ !estadoProceso ? 'Pendiente' : estadoProceso }}</v-list-tile-sub-title>
                        <v-list-tile-sub-title v-else>Estado: {{ portabilidadCopy.estado }}</v-list-tile-sub-title>
                      </v-list-tile-content>
                    </v-list-tile>
                    <v-divider/>
                  </template>
                </v-list>
                <v-card-text v-if="portabilidadCopy.estado === 'Registrado'">
                  <loading v-model="loadingSubmit"></loading>
                  <v-slide-y-transition>
                    <v-layout row wrap >
                      <v-flex xs12>
                        <v-subheader class="estado">Estado</v-subheader>
                        <v-radio-group
                          v-model="estadoProceso"
                          row
                          name="estado"
                          v-validate="'required'"
                          :error-messages="errors.collect('estado')"
                          @change="getPrestadores"
                        >
                          <v-radio label="Aceptado" value="Aceptado"></v-radio>
                          <v-radio label="Negado" value="Negado"></v-radio>
                        </v-radio-group>
                      </v-flex>
                      <v-flex xs12 v-if="estadoProceso == 'Aceptado'">
                        <v-toolbar dense class="elevation-1 white">
                          <v-avatar size="36" color="primary" class="white--text">
                            <v-icon>widgets</v-icon>
                          </v-avatar>
                          <v-toolbar-title>Asignación de servicios</v-toolbar-title>
                        </v-toolbar>
                        <v-data-table
                          :items="serviciosPrimarios"
                          class="elevation-1"
                          hide-actions
                          :headers="headers"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.codigo }} - {{ props.item.nombre }}</td>
                            <td>
                              <v-autocomplete
                                v-if="props.item.habilitados.filter(x => x.entidad && (x.entidad.gn_municipiosede_id === portabilidadCopy.munreceptor_id)).length"
                                placeholder="Seleccionar IPS"
                                v-model="portabilidadCopy.servicios_portabilidad[props.index]"
                                :items="props.item.habilitados.filter(x => x.entidad && ((x.entidad.gn_municipiosede_id === portabilidadCopy.munreceptor_id) || (x.rs_entidad_id === 67091) || (x.rs_entidad_id === 37546)))"
                                item-value="id"
                                item-text="nombre"
                                :filter="filterIPSs"
                                clearable
                              >
                                <template slot="selection" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.entidad.nombre"/>
                                      <v-list-tile-sub-title v-html="'Código: ' + data.item.entidad.cod_habilitacion + ' - Dirección: ' + data.item.entidad.direccion_sede"/>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.entidad.nombre"/>
                                      <v-list-tile-sub-title v-html="'Código: ' + data.item.entidad.cod_habilitacion + ' - Dirección: ' + data.item.entidad.direccion_sede"/>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                              </v-autocomplete>
                              <span v-else>
                                No disponible
                              </span>
                            </td>
                          </template>
                        </v-data-table>
                      </v-flex>
                      <v-flex xs12>
                        <v-textarea v-model="portabilidadCopy.observaciones"
                                    label="Observaciones" key="observaciones"
                                    name="observaciones" required
                                    :error-messages="errors.collect('respuesta')" ></v-textarea>
                      </v-flex>
                    </v-layout>
                  </v-slide-y-transition>
                </v-card-text>
                <v-card-text v-if="portabilidadCopy.estado === 'Aceptado'">
                  <v-toolbar dense class="elevation-1 white">
                    <v-avatar size="36" color="primary" class="white--text">
                      <v-icon>widgets</v-icon>
                    </v-avatar>
                    <v-toolbar-title>Servicios asignados</v-toolbar-title>
                  </v-toolbar>
                  <v-data-table
                    :items="serviciosPrimarios"
                    class="elevation-1"
                    hide-actions
                    :headers="headers"
                  >
                    <template v-slot:items="props">
                      <td>{{ props.item.codigo }} - {{ props.item.nombre }}</td>
                      <td>
                        <v-list-tile class="content-v-list-tile-p0" v-if="!!filterServicioAsignado(props.item.habilitados)">
                          <v-list-tile-content>
                            <v-list-tile-title class="body-2">{{filterServicioAsignado(props.item.habilitados).entidad.nombre}}</v-list-tile-title>
                            <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{filterServicioAsignado(props.item.habilitados).entidad.telefono_sede}} - <v-icon size="small">fas fa-map-signs</v-icon> {{filterServicioAsignado(props.item.habilitados).entidad.direccion_sede}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                        <span v-else>No asignado</span>
                      </td>
                    </template>
                  </v-data-table>
                </v-card-text>
                <v-card-text style="padding-top: 0 !important; margin-top: 0 !important;" v-if="portabilidad.estado === 'Cancelada'">
                  <v-flex xs12>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">Descripción</v-list-tile-sub-title>
                      <span>{{ portabilidadCopy.cancel_portabilidad !== null ? portabilidadCopy.cancel_portabilidad.descripcion : null }}</span>
                    </v-list-tile-content>
                  </v-flex>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </template>
      </v-slide-y-transition>
    </v-container>
    <v-slide-x-transition group>
      <template v-if="portabilidad && portabilidad.estado === 'Registrado'">
        <v-divider key="divideractions"></v-divider>
        <v-card-actions key="buttonsactions" class="grey lighten-4">
          <v-layout row wrap>
            <v-flex xs12 class="text-xs-right">
              <v-btn @click="close">Cancelar</v-btn>
              <v-btn @click="submit" color="primary">Guardar</v-btn>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </template>
    </v-slide-x-transition>
  </v-card>
</template>

<script>
  export default {
    name: 'DetallePortabilidad',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: ['parametros'],
    data () {
      return {
        estadoProceso: null,
        portabilidad: null,
        portabilidadCopy: null,
        serviciosPrimarios: [],
        headers: [
          {
            text: 'Servicio',
            align: 'left',
            sortable: false
          },
          {
            text: 'IPS',
            align: 'left',
            sortable: false
          }
        ],
        filterIPSs (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        loading: false,
        loadingSubmit: false
      }
    },
    created () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
      this.getServiciosPrimarios()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('portabilidad')
      }
    },
    methods: {
      getPrestadores () {
        console.log('esta en ', this.estadoProceso)
      },
      filterServicioAsignado (habilitados) {
        return habilitados.find(x => x.id === this.portabilidadCopy.servicios_portabilidad.map(z => z.rs_servportabilidad_id).find(y => x.id === y))
      },
      getRegisto (id) {
        this.loading = true
        this.axios.get('portabilidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.portabilidad = response.data.data
              this.portabilidadCopy = {...this.portabilidad}
            }
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el registro de la portabilidad. ', error: e})
          })
      },
      getServiciosPrimarios () {
        this.axios.get(`servicios/primarios`)
          .then((response) => {
            this.serviciosPrimarios = response.data.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los servicios primarios. ', error: e})
          })
      },
      close () {
        this.$validator.reset()
        this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
      },
      submit () {
        let data = JSON.parse(JSON.stringify(this.portabilidadCopy))
        data.servicios_portabilidad = data.servicios_portabilidad.filter(Boolean)
        this.$validator.validateAll().then(result => {
          if (result) {
            data.estado = this.estadoProceso
            if ((data.estado === 'Aceptado' && data.servicios_portabilidad.length) || data.estado === 'Negado') {
              this.loadingSubmit = true
              this.axios.put('portabilidades/' + data.id, data)
                .then(response => {
                  this.$store.commit('PORTABILIDAD_RELOAD_ITEM', {item: {}, estado: 'reload', key: this.parametros.key})
                  this.$store.commit('SNACK_SHOW', {msg: 'Portabilidad actualizada correctamente', color: 'success'})
                  this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
                  this.loadingSubmit = false
                })
                .catch(e => {
                  this.loadingSubmit = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: ' al actualizar el registro de la portabilidad. ', error: e})
                })
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'Se debe asignar IPS al menos a uno de los servicios.', color: 'warning'})
            }
          }
        })
      }
    }
  }
</script>

<style scoped>
  .estado {
    height: 10px;
    padding: 0;
  }

  .p-12 {
    height: 10px;
    padding: 12px;
  }
</style>

