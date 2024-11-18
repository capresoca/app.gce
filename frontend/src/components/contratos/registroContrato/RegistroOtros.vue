<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Otros bienes y servicios'"/>
          <small class="mt-2 ml-1"> Registro y gestión</small>
          <v-spacer/>
          <v-tooltip top v-if="!showFormRegistro && !value.actaInicio">
            <v-btn
              slot="activator"
              fab
              right
              small
              color="accent"
              @click="showFormRegistro = true"
            >
              <v-icon>add</v-icon>
            </v-btn>
            <span>Crear item</span>
          </v-tooltip>
        </v-toolbar>
        <v-scroll-y-transition>
          <div v-if="showFormRegistro">
            <v-card-text>
              <v-form>
                <v-layout row wrap>
                  <v-flex md10 sm8 xs12>
                    <postulador-v2
                      ref="postuladorOtro"
                      no-data="Busqueda por código o nombre."
                      item-text="nombre"
                      label="Bienes y servicios"
                      :entidad="`entidades/${value.entidad.id}/otros`"
                      v-model="item.otro"
                      no-btn-create
                      no-btn-edit
                      name="Otro"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect('Otro')"
                      :min-characters-search="4"
                      :slot-data='{
                      template:`
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.nombre}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption> Código: {{ value.codigo }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
                    />
                  </v-flex>
                  <v-flex md2 sm4 xs12>
                    <v-text-field
                      label="Tarifa"
                      name="Tarifa"
                      v-validate="'required|min_value:0'"
                      min="0"
                      type="number"
                      v-model.number="item.tarifa"
                      :error-messages="errors.collect('Tarifa')"
                      @keyup.enter="submitMedicamento"
                    />
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-text>
            <v-divider/>
            <v-card-actions>
              <v-spacer/>
              <v-btn flat @click.stop="showFormRegistro = false">Cancelar</v-btn>
              <v-btn color="primary" @click.stop="submitOtro">Registrar</v-btn>
            </v-card-actions>
          </div>
        </v-scroll-y-transition>
      </v-card>
    </v-flex>
    <v-container fluid class="ma-0 pa-0">
      <data-table
        ref="tablaOtros"
        v-if="dataTable.route"
        v-model="dataTable"
        @resetOption="item => resetOptions(item)"
        @editar="item => otroEditar(item)"
        @borrar="item => otroBorrar(item)"
      />
    </v-container>
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroOtros',
    props: ['value'],
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      dataTable: () => import('@/components/general/DataTable')
    },
    data: () => ({
      showFormRegistro: false,
      loading: false,
      item: null,
      makeItem: {
        otro: null,
        codigo: null,
        id: null,
        descripcion: null,
        rs_otrosentidad_id: null,
        rs_contratos_id: null,
        tarifa: null,
        tarifa_entidad: null
      },
      dataTable: {
        nameItemState: null,
        route: null,
        makeHeaders: [
          {
            text: 'Código',
            align: 'left',
            sortable: true,
            value: 'codigo'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: true,
            value: 'descripcion'
          },
          {
            text: 'Tarifa ofertada',
            align: 'right',
            sortable: false,
            value: 'tarifa_entidad',
            classData: 'text-xs-right',
            component: {
              component: {
                template:
                  `<span className="white--text caption">{{'$'}}{{value.tarifa_entidad | numberFormat(2)}}</span>`,
                props: ['value']
              }
            }
          },
          {
            text: 'Tarifa contratada',
            align: 'right',
            sortable: false,
            value: 'tarifa',
            classData: 'text-xs-right',
            component: {
              component: {
                template:
                  `<span className="white--text caption">{{'$'}}{{value.tarifa | numberFormat(2)}}</span>`,
                props: ['value']
              }
            }
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'text-xs-center'
          }
        ]
      }
    }),
    watch: {
      'item.otro.id' (val) {
        if (val) {
          this.item.rs_otrosentidad_id = this.item.otro.id
          this.item.tarifa_entidad = this.item.otro.tarifa
          this.item.tarifa = JSON.parse(JSON.stringify(this.item.otro.tarifa))
        }
      },
      'showFormRegistro' (val) {
        !val && this.refreshOtro()
      }
    },
    computed: {
      estadosBasicos () {
        return store.state.estadosBasicos
      }
    },
    created () {
      this.refreshOtro()
    },
    mounted () {
      this.dataTable.route = `contratos/${this.value.id}/otros`
    },
    methods: {
      otroEditar (item) {
        this.refreshOtro()
        const temporal = JSON.parse(JSON.stringify(item))
        console.log('el temporal', temporal)
        this.item = {
          otro: {
            id: temporal.rs_otrosentidad_id,
            tarifa: temporal.tarifa,
            codigo: temporal.codigo,
            nombre: temporal.descripcion
          },
          id: temporal.id,
          rs_contratos_id: temporal.rs_contratos_id,
          rs_otrosentidad_id: temporal.rs_otrosentidad_id,
          tarifa: temporal.tarifa,
          tarifa_entidad: temporal.tarifa_entidad
        }
        this.$vuetify.goTo('#flag-top',
          {
            selector: '#flag-top',
            duration: 300,
            offset: 0 - 120,
            easing: 'easeInOutQuad'
          }
        )
        this.showFormRegistro = true
        setTimeout(() => {
          this.$refs.postuladorOtro.assign(this.item.otro)
          this.$refs.postuladorOtro.focus()
        }, 300)
      },
      otroBorrar (item) {
        this.loading = true
        this.showFormRegistro = false
        this.axios.post(`contratos/remove-otro/${item.id}`)
          .then((response) => {
            this.$refs.tablaOtros.reloadPage()
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al borrar el medicamento. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (!this.value.actaInicio) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (!this.value.actaInicio) item.options.push({event: 'borrar', icon: 'delete', tooltip: 'Borrar'})
        return item
      },
      submitOtro () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.item.rs_contratos_id = this.value.id
            this.axios.post(`contratos/${this.value.id}/add-otro`, this.item)
              .then((response) => {
                this.$refs.tablaOtros.reloadPage()
                this.refreshOtro()
                this.$refs.postuladorOtro.focus()
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el item. ', error: e})
              })
          }
        })
      },
      refreshOtro () {
        this.item = JSON.parse(JSON.stringify(this.makeItem))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
