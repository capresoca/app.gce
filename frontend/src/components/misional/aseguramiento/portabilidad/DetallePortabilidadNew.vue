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
                <v-card-text>
                  <loading v-model="loadingSubmit"></loading>
                  <v-slide-y-transition>
                    <v-layout row wrap >
                      <v-flex xs12 v-if="portabilidadCopy.estado === 'Registrado'">
                        <v-subheader class="estado">Estado</v-subheader>
                        <v-radio-group
                          v-model="estadoProceso"
                          row
                          name="estado"
                          v-validate="'required'"
                          :error-messages="errors.collect('estado')"
                          @change="getgruposPrestadores"
                        >
                          <v-radio label="Aceptado" value="Aceptado"></v-radio>
                          <v-radio label="Negado" value="Negado"></v-radio>
                        </v-radio-group>
                      </v-flex>
                      <v-flex xs12 v-if="estadoProceso == 'Aceptado'">
                        <v-card>
                          <v-card-text class="pb-1">
                            <v-autocomplete
                              :readonly="portabilidadCopy.estado !== 'Registrado'"
                              :label="(grupoSeleccionado ? 'G' : 'Seleccionar g') + 'rupo de prestadores'"
                              v-model="grupoSeleccionado"
                              :items="gruposPrestadores"
                              item-value="id"
                              item-text="descrip"
                              return-object
                              name="grupo de prestadores"
                              v-validate="'required'"
                              :error-messages="errors.collect('grupo de prestadores')"
                            >
                              <template slot="selection" slot-scope="data">
                            <span style="width: 100% !important;">{{data.item.descrip}}
                              <v-chip small color="primary" text-color="white">
                                <v-avatar class="primary darken-4">{{data.item.prestadores.length}}</v-avatar>
                                Prestadores
                              </v-chip>
                            </span>
                              </template>
                              <template slot="item" slot-scope="data">
                            <span style="width: 100% !important;">{{data.item.descrip}}
                              <v-chip small color="primary" text-color="white">
                                <v-avatar class="primary darken-4">{{data.item.prestadores.length}}</v-avatar>
                                Prestadores
                              </v-chip>
                            </span>
                              </template>
                            </v-autocomplete>
                          </v-card-text>
                          <v-data-table
                            v-if="grupoSeleccionado"
                            :items="grupoSeleccionado.prestadores"
                            class="elevation-1"
                            hide-actions
                            :headers="headers"
                          >
                            <template v-slot:items="props">
                              <td class="text-xs-center">
                                <v-avatar color="green darken-2" size="36" v-if="props.item.primaria">
                                  <v-icon class="white--text">fas fa-check</v-icon>
                                </v-avatar>
                              </td>
                              <td>
                                <v-list-tile-content>
                                  <v-list-tile-title>{{props.item.entidad.nombre}}</v-list-tile-title>
                                  <v-list-tile-sub-title class="caption">Código Habilitación: {{props.item.entidad.cod_habilitacion}}</v-list-tile-sub-title>
                                </v-list-tile-content>
                              </td>
                              <td>
                                <v-icon size="small">fas fa-phone-alt</v-icon> {{props.item.entidad.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{props.item.entidad.direccion_sede}}
                              </td>
                            </template>
                          </v-data-table>
                        </v-card>
                      </v-flex>
                      <v-flex xs12 v-if="portabilidadCopy.estado == 'Registrado'">
                        <v-textarea
                          v-model="portabilidadCopy.observaciones"
                          label="Observaciones"
                          key="observaciones"
                          name="observaciones"
                          required
                          :error-messages="errors.collect('observaciones')"
                        ></v-textarea>
                      </v-flex>
                    </v-layout>
                  </v-slide-y-transition>
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
        grupoSeleccionado: null,
        gruposPrestadores: [],
        headers: [
          {
            text: 'Prestador Primario',
            align: 'center',
            sortable: false
          },
          {
            text: 'Prestador',
            align: 'left',
            sortable: false
          },
          {
            text: 'Dirección',
            align: 'left',
            sortable: false
          }
        ],
        loading: false,
        loadingSubmit: false
      }
    },
    created () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('portabilidad')
      }
    },
    methods: {
      getgruposPrestadores () {
        if (this.estadoProceso === 'Aceptado') {
          this.loading = true
          this.axios.get(`rs_servicios/grupos/${this.portabilidadCopy.municipio_receptor.id}?portable=1`)
            .then((response) => {
              this.gruposPrestadores = response.data.data
              this.loading = false
            })
            .catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los grupos de IPS. ', error: e})
              this.loading = false
            })
        } else {
          this.gruposPrestadores = []
          this.grupoSeleccionado = null
        }
      },
      getRegisto (id) {
        this.loading = true
        this.axios.get('portabilidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.portabilidad = response.data.data
              this.portabilidadCopy = {...this.portabilidad}
              this.grupoSeleccionado = (this.portabilidad.ips_grupo && this.portabilidad.ips_grupo.grupo_ips) ? this.portabilidad.ips_grupo.grupo_ips : null
              if (this.portabilidadCopy.estado === 'Aceptado') {
                this.estadoProceso = this.portabilidadCopy.estado
                this.gruposPrestadores.push(this.grupoSeleccionado)
              }
            }
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el registro de la portabilidad. ', error: e})
          })
      },
      close () {
        this.$validator.reset()
        this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
      },
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let data = {...this.portabilidadCopy}
            data.id_grupoips = this.estadoProceso === 'Aceptado' ? this.grupoSeleccionado.id : null
            data.estado = this.estadoProceso
            this.loadingSubmit = true
            this.axios.put('portabilidades/' + data.id, data)
              .then(response => {
                this.$store.commit('reloadTable', 'tablePortabilidadAprobacion')
                this.$store.commit('SNACK_SHOW', {msg: 'Portabilidad actualizada correctamente', color: 'success'})
                this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
                this.loadingSubmit = false
              })
              .catch(e => {
                this.loadingSubmit = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al actualizar el registro de la portabilidad. ', error: e})
              })
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

