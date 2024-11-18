<template>
    <div>
      <v-card>
        <toolbar-list :info="infoComponent" btnplus btnplustitle="Cargar archivo" btnicon="fas fa-upload"
                      btnplustruncate @click="cardDialog = !cardDialog" title="Censos" subtitle="Registro y gestión" class="pb-2"/>
        <v-expand-transition>
          <template v-if="cardDialog">
            <v-card class="elevation-0">
              <loading v-model="archivoLoading" />
              <toolbar-list title="Registro de Censo"/>
              <v-form data-vv-scope="formCargaCenso">
                <v-container fluid grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <postulador-v2
                        no-data="Busqueda por NIT, razon social u código de habilitación."
                        hint="cod_habilitacion"
                        item-text="nombre"
                        data-title="nombre"
                        data-subtitle="cod_habilitacion"
                        label="IPS"
                        entidad="entidades"
                        preicon="location_city"
                        @changeid="val => censo.ips_id = val"
                        v-model="censo.ips"
                        route-params="rs_tipentidade_id=1"
                        name="IPS"
                        no-btn-create
                        rules="required"
                        v-validate="'required'"
                        :error-messages="errors.collect('IPS')"
                        :min-characters-search="3"
                      />
                    </v-flex>
                    <v-flex xs12 sm8 md8>
                      <input-file-v2
                        label="Archivo Censo"
                        v-model="censo.archivo"
                        accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        :hint="'Extenciones aceptadas: xls'"
                        prepend-icon="fas fa-paperclip"
                        name="archivo censo"
                        v-validate="'required'"
                        :error-messages="errors.collect('archivo censo')"
                      ></input-file-v2>
                    </v-flex>
                    <v-flex xs12 sm4 md4>
                      <input-date
                        v-model="censo.fecha"
                        label="Fecha"
                        format="YYYY-MM-DD"
                        name="fecha"
                        key="fecha"
                        v-validate="'required|fechaValida|date_format:YYYY-MM-DD'"
                        :error-messages="errors.collect('fecha')"
                      ></input-date>
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
                >
                  Cancelar
                </v-btn>
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
          v-model="dataTableCensos"
          @resetOption="item => resetOptions(item)"
          @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, required: true, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
          :filters="false"
        >
        </data-table>
      </v-card>
    </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {CENSOS_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'VCenso',
    components: {
      Loading,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      InputFileV2: () => import('@/components/general/InputFileV2'),
      InputDate: () => import('@/components/general/InputDate'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        censo: null,
        cardDialog: false,
        archivoLoading: false,
        dataTableCensos: {
          nameItemState: 'itemCensos',
          route: 'censos',
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
              text: 'FECHA',
              align: 'left',
              sortable: false,
              value: 'fecha'
            },
            {
              text: 'NOMBRE IPS',
              align: 'left',
              sortable: true,
              value: 'ips.nombre'
            },
            {
              text: 'NOMBRE ARCHIVO',
              align: 'left',
              sortable: false,
              value: 'archivo.nombre'
            },
            {
              text: 'PACIENTES NUEVOS',
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
                          <span slot="badge" class="caption">{{value.cantidad_pacientes}}</span>
                            <v-avatar color="indigo" size="30">
                              <v-icon class="white--text subheading" v-text="'fas fa-bed'"></v-icon>
                            </v-avatar>
                          </v-badge>
                          <span>Pacientes</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'USUARIO',
              align: 'left',
              sortable: false,
              value: 'usuario.name'
            },
            {
              text: 'OPCIONES',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
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
        return this.$store.getters.getInfoComponent('censos')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'detalle', icon: 'fas fa-list-alt', tooltip: 'Listado de Pacientes', color: 'teal', size: '20px'})
        return item
      },
      formData () {
        this.censo = {
          archivo: null,
          fecha: null,
          ips_id: null,
          ips: null
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formCargaCenso')) {
          this.archivoLoading = true
          let data = new FormData()
          data.append('ips_id', this.censo.ips_id)
          data.append('archivo', this.censo.archivo)
          data.append('fecha', this.censo.fecha)
          this.axios.post('censos', data)
            .then(response => {
              console.log('reponse', response)
              this.$store.commit(SNACK_SHOW, {msg: 'Los archivos se han cargado correctamente. ', color: 'success'})
              this.$store.commit(CENSOS_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
              this.cardDialog = false
              this.archivoLoading = false
            }).catch(e => {
              console.log('e', e.response)
              this.archivoLoading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al cargar el archivo. ', error: e})
            // this.$store.commit(SNACK_SHOW, {msg: 'Error al enviar los datos.' + e.response.data, color: 'danger'})
            })
        }
      }
    }
  }
</script>

<style scoped>

</style>
