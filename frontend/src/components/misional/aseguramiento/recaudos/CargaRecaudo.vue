<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" btnplus btnplustitle="Cargar archivos" btnicon="fas fa-upload"
                    btnplustruncate @click="cardDialog = !cardDialog" title="Recaudos Cargados" subtitle="Carga de archivos" class="pb-2"/>
      <v-expand-transition>
        <template v-if="cardDialog">
          <v-card class="elevation-0">
            <loading v-model="archivoLoading" />
            <v-form data-vv-scope="formCarga">
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12>
                    <file-input-archive
                      :title="'Archivos Recaudos'"
                      v-model="recaudo.archivo"
                      mime-types="application/zip,application/x-zip-compressed"></file-input-archive>
<!--                    <input-file-v2-->
<!--                      label="Archivos Recaudos"-->
<!--                      v-model="recaudo.files"-->
<!--                      accept="application/zip"-->
<!--                      :hint="'Extenciones aceptadas: zip'"-->
<!--                      prepend-icon="fas fa-paperclip"-->
<!--                      multiple-->
<!--                      name="archivos recaudos"-->
<!--                      v-validate="'required'"-->
<!--                      :error-messages="errors.collect('archivos recaudos')"-->
<!--                    ></input-file-v2>-->
                  </v-flex>
                </v-layout>
              </v-container>
            </v-form>
            <v-card-actions class="grey lighten-4">
              <v-spacer></v-spacer>
              <v-btn
                color="grey"
                flat
                small
                @click="cardDialog = false"
                v-text="'Cancelar'"
              />
              <v-btn
                color="accent"
                dark
                small
                @click="save"
                flat
              >
                <v-icon size="15px" v-text="'fas fa-paper-plane'"></v-icon>&nbsp;
                <span v-text="' Cargar'"></span>
              </v-btn>
            </v-card-actions>
          </v-card>
        </template>
      </v-expand-transition>
      <data-table
        v-model="dataTableRecaudosCargados"
        @resetOption="item => resetOptions(item)"
        @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, required: true, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
        :filters="false"
      />
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {RECAUDOS_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'CargaRecaudo',
    components: {
      Loading,
      FileInputArchive: () => import('../../../general/FileInput'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      InputFileV2: () => import('@/components/general/InputFileV2'),
      InputDate: () => import('@/components/general/InputDate'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        recaudo: null,
        cardDialog: false,
        archivoLoading: false,
        dataTableRecaudosCargados: {
          nameItemState: 'itemRecaudo',
          route: 'recaudos_cargados',
          simplePaginate: true,
          optionsPerPage: [
            {
              text: 15,
              value: 15
            },
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            }
          ],
          makeHeaders: [
            {
              text: 'NOMBRE ARCHIVO',
              align: 'center',
              sortable: false,
              value: 'nombre_archivos_zip',
              classData: 'text-xs-center',
              component: {
                component: {
                  template:
                    `<div><b>{{value.nombre_archivos_zip}}</b></div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'ARCHIVOS TXT',
              align: 'center',
              sortable: false,
              value: '',
              classData: 'text-xs-center',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip top class="mr-2">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.numero_archivos_txt}}</span>
                            <v-avatar color="accent" size="30">
                              <v-icon class="white--text subheading" v-text="'far fa-file-alt'"></v-icon>
                            </v-avatar>
                          </v-badge>
                          <span>Cantidad Cargada</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'FECHA DE CARGA',
              align: 'center',
              sortable: false,
              value: 'created_at',
              classData: 'text-xs-center',
              component: {
                component: {
                  template: `<div v-text="moment(value.created_at).format('YYYY/MM/DD')"></div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'USUARIO',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              value: 'usuario.name'
            }
            // {
            //   text: 'OPCIONES',
            //   align: 'center',
            //   sortable: false,
            //   actions: true,
            //   singlesActions: true,
            //   classData: 'text-xs-center'
            // }
          ]
        }
      }
    },
    created () {
      this.formData()
    },
    watch: {
      'cardDialog' (val) {
        if (val) this.formData()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cargaRecaudos')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'detalle', icon: 'fas fa-list-alt', tooltip: 'Listado de Pacientes', color: 'teal', size: '20px'})
        return item
      },
      formData () {
        this.recaudo = {
          archivo: []
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formCarga') && this.recaudo.archivo.length) {
          this.archivoLoading = true
          let sendData = new FormData()
          sendData.append('archivo', this.recaudo.archivo[0])
          this.axios.post('as_cargador_recaudos', sendData)
            .then(response => {
              console.log('reponse', response)
              this.$store.commit(SNACK_SHOW, {msg: 'Los archivos se han cargado correctamente. ', color: 'success'})
              this.$store.commit(RECAUDOS_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
              this.cardDialog = false
              this.archivoLoading = false
            }).catch(e => {
              console.log('e', e.response)
              this.archivoLoading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' ' + e.response.data.error, error: e})
            // this.$store.commit(SNACK_SHOW, {msg: 'Error al enviar los datos.' + e.response.data, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'No se ha cargado ningún archivo en el componente.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
