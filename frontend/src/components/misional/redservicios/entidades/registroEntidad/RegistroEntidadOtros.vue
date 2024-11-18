<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Otros bienes y servicios'"/>
          <small class="mt-2 ml-1"> Registro y gestión por entidad</small>
          <v-spacer/>
          <v-tooltip top v-if="!showFormRegistro">
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
                  <v-flex xs12 sm6 :md4="otro.id" :md3="!otro.id">
                    <v-text-field
                      label="Código"
                      name="Código"
                      v-validate="'required'"
                      v-model="otro.codigo"
                      :error-messages="errors.collect('Código')"
                      @keyup.enter="submitOtro"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 :md4="otro.id" :md3="!otro.id">
                    <v-text-field
                      label="Tarifa"
                      name="Tarifa"
                      v-validate="'required|min_value:0'"
                      min="0"
                      type="number"
                      v-model.number="otro.tarifa"
                      :error-messages="errors.collect('Tarifa')"
                      @keyup.enter="submitOtro"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="otro.id">
                    <v-select
                      label="Estado"
                      v-model="otro.estado"
                      :items="estadosBasicos"
                      name="Estado"
                      v-validate="'required'"
                      :error-messages="errors.collect('Estado')"
                      @keyup.enter="submitOtro"
                    />
                  </v-flex>
                  <v-flex xs12 sm12 :md12="otro.id" :md6="!otro.id">
                    <v-textarea
                      v-model="otro.nombre"
                      auto-grow
                      label="Nombre"
                      rows="1"
                      name="Nombre"
                      v-validate="'required'"
                      :error-messages="errors.collect('Nombre')"
                      @keyup.enter="submitOtro"
                    ></v-textarea>
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
      />
    </v-container>
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroEntidadOtros',
    props: ['value'],
    components: {
      Loading,
      dataTable: () => import('@/components/general/DataTable')
    },
    data: () => ({
      showFormRegistro: false,
      loading: false,
      otro: null,
      makeOtro: {
        id: null,
        rs_entidades_id: null,
        codigo: null,
        nombre: null,
        tarifa: null,
        estado: 'Activo'
      },
      selections: [],
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
            text: 'Nombre',
            align: 'left',
            sortable: true,
            value: 'nombre'
          },
          {
            text: 'Tarifa',
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
            text: 'Estado',
            align: 'center',
            sortable: true,
            value: 'estado',
            classData: 'text-xs-center'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0'
          }
        ]
      }
    }),
    watch: {
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
      this.dataTable.route = `otrosentidades?entidad:id=${this.value.id}`
    },
    methods: {
      otroEditar (item) {
        this.refreshOtro()
        this.otro = JSON.parse(JSON.stringify(item))
        this.$vuetify.goTo('#flag-top',
          {
            selector: '#flag-top',
            duration: 300,
            offset: 0 - 120,
            easing: 'easeInOutQuad'
          }
        )
        this.showFormRegistro = true
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      },
      submitOtro () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.otro.rs_entidades_id = this.value.id
            let request = this.otro.id ? this.axios.put(`otrosentidades/${this.otro.id}`, this.otro) : this.axios.post(`otrosentidades`, this.otro)
            request
              .then((response) => {
                this.$refs.tablaOtros.reloadPage()
                this.refreshOtro()
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar el registro. ', error: e})
              })
          }
        })
      },
      refreshOtro () {
        this.otro = JSON.parse(JSON.stringify(this.makeOtro))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
