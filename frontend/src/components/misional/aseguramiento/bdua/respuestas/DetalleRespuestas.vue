<template>
  <div>
    <v-dialog v-model="dialogPendientes"  max-width="720">
      <v-card>
        <v-card-text class="pa-0">
          <v-toolbar flat color="white">
            <v-toolbar-title>Pendientes</v-toolbar-title>
          </v-toolbar>
          <v-data-table
            :headers="headersPendientes"
            :items="respuesta.pendientes"
            class="elevation-1"
            no-data-text="No existen datos"
          >
            <template v-slot:items="props">
              <td>{{ props.item.id }}</td>
              <td>{{ props.item.motivo }}</td>
            </template>
          </v-data-table>
        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat @click="close">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="loaderRef">
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-card flat class="content-list-p0 pt-0">

              <v-list two-line>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar color="info">
                      <v-icon dark>assignment</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Respuesta BDUA
                      </v-list-tile-title>
                      <v-list-tile-sub-title>Id: {{ respuesta.id }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>

              <v-card-text>
                <v-container fluid grid-list-xl class="pa-0" ref="detallePqrs">
                  <loading v-model="loading" />
                  <v-slide-y-transition group>

                    <v-layout row wrap key="seccion1">

                      <v-flex xs12 sm2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Tipo respuesta</v-list-tile-sub-title>
                          <span>{{ respuesta.tipo_respuesta }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Avance</v-list-tile-sub-title>
                          <span>{{ respuesta.avance }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Aplicadas</v-list-tile-sub-title>
                          <span>{{ respuesta.aplicadas }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Total registros</v-list-tile-sub-title>
                          <span>{{ respuesta.total_registros }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Archivo</v-list-tile-sub-title>
                          <v-layout align-center fill-height>
                            <v-flex d-flex xs10 sm8>
                              <v-text-field v-model="respuesta.path.nombre"key="archivo"
                                            name="archivo" disabled prepend-icon="description"
                                            :error-messages="errors.collect('archivo')" ></v-text-field>

                            </v-flex>

                            <v-flex d-flex xs2 sm4>
                              <v-tooltip top>
                                <v-btn
                                  fab
                                  color="success"
                                  small
                                  :href="respuesta.path.url_signed"
                                  target="_blank"
                                  slot="activator"
                                >
                                  <v-icon>remove_red_eye</v-icon>
                                </v-btn>
                                <span>Ver archivo</span>
                              </v-tooltip>
                            </v-flex>
                          </v-layout>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2>
                          <v-btn @click="openPendientes" color="primary">Pendientes</v-btn>
                      </v-flex>

                    </v-layout>

                    <v-layout row wrap key="secccion2">
                      <v-flex xs12>
                        <v-expansion-panel expand>
                          <v-expansion-panel-content
                            v-for="(item, i) in respuesta.registros"
                            :key="i"
                          >
                            <template v-slot:header>
                              <div> <v-icon left> calendar_today</v-icon> Id: {{ item.id }} | Glosas: {{ item.glosas.length }} | Acciones: {{ item.acciones.length }}</div>
                            </template>
                            <v-card>

                              <v-card-text class="grey lighten-3">

                                <v-toolbar flat color="white">
                                  <v-toolbar-title>Glosas</v-toolbar-title>
                                </v-toolbar>

                                <v-data-table
                                  :headers="headersGlosas"
                                  :items="item.glosas"
                                  class="elevation-1"
                                  no-data-text="No existen datos"
                                >
                                  <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.glosa }}</td>
                                    <td>{{ props.item.estado }}</td>
                                    <td>{{ props.item.observaciones }}</td>
                                  </template>
                                </v-data-table>

                                <v-divider style="margin-top: 20px; margin-bottom: 20px"></v-divider>


                                <v-toolbar flat color="white">
                                  <v-toolbar-title>Acciones</v-toolbar-title>
                                </v-toolbar>
                                <v-data-table
                                  :headers="headersAcciones"
                                  :items="item.acciones"
                                  class="elevation-1"
                                  no-data-text="No existen datos"
                                >
                                  <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.accion }}</td>
                                  </template>
                                </v-data-table>


                              </v-card-text>


                            </v-card>
                          </v-expansion-panel-content>
                        </v-expansion-panel>
                      </v-flex>
                    </v-layout>

                  </v-slide-y-transition>
                </v-container>
              </v-card-text>
            </v-card>
          </v-flex>

        </v-layout>
      </v-container>

    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'

  export default {
    name: 'DetalleRespuestas',
    components: {
      Loading
    },
    props: ['parametros'],
    data () {
      return {
        respuesta: {
          path: {nombre: null}
        },
        dialogPendientes: false,
        loading: false,

        headersGlosas: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Glosa',
            align: 'left',
            sortable: false,
            value: 'glosa'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Observaciones',
            align: 'left',
            sortable: false,
            value: 'observaciones'
          }
        ],
        headersAcciones: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Acción',
            align: 'left',
            sortable: false,
            value: 'accion'
          }
        ],
        headersPendientes: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Motivo',
            align: 'left',
            sortable: false,
            value: 'motivo'
          }
        ]
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('bduarespuestas/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.respuesta = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el registro. ', error: e})
          })
      },
      openPendientes () {
        this.dialogPendientes = true
      },
      close () {
        this.dialogPendientes = false
      }
    }
  }
</script>

<style scoped>

</style>
