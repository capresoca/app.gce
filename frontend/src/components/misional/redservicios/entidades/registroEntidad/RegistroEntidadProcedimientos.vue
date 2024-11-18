<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Registro de procedimientos'"/>
          <small class="mt-2 ml-1"> Vinculación a la entidad</small>
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
            <span>Agregar procedimiento</span>
          </v-tooltip>
        </v-toolbar>
        <v-scroll-y-transition>
          <div v-if="showFormRegistro">
            <v-card-text>
              <v-form>
                <v-layout row wrap>
                  <v-flex md10 sm8 xs12>
                    <postulador-v2
                      ref="postuladorProcedimiento"
                      no-data="Busqueda por código o nombre."
                      item-text="descripcion"
                      label="procedimiento"
                      entidad="cups"
                      v-model="procedimiento.cup"
                      @changeid="val => procedimiento.rs_cups_id = val"
                      no-btn-create
                      no-btn-edit
                      name="procedimiento"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect('procedimiento')"
                      :min-characters-search="4"
                      :slot-data='{
                      template:`
                      <v-list-tile>
                      <v-list-tile-action>
                        <v-chip
                        color="indigo lighten-2"
                        label
                        small
                        >
                        {{ value.codigo }}
                        </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.cobertura }}</v-list-tile-sub-title>
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
                      v-model.number="procedimiento.tarifa"
                      :error-messages="errors.collect('Tarifa')"
                      @keyup.enter="submitProcedimiento"
                    />
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-text>
            <v-divider/>
            <v-card-actions>
              <v-spacer/>
              <v-btn flat @click.stop="showFormRegistro = false">Cancelar</v-btn>
              <v-btn color="primary" @click.stop="submitProcedimiento">Registrar</v-btn>
            </v-card-actions>
          </div>
        </v-scroll-y-transition>
      </v-card>
    </v-flex>
    <v-container fluid class="ma-0 pa-0">
      <data-table
        ref="tablaProcedimientos"
        v-if="dataTable.route"
        v-model="dataTable"
        @resetOption="item => resetOptions(item)"
        @editar="item => procedimientoEditar(item)"
        @borrar="item => procedimientoBorrar(item)"
      />
    </v-container>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroEntidadProcedimientos',
    props: ['value'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading,
      dataTable: () => import('@/components/general/DataTable')
    },
    data: () => ({
      showFormRegistro: false,
      loading: false,
      procedimiento: null,
      makeProcedimiento: {
        cup: null,
        id: null,
        rs_cups_id: null,
        rs_entidad_id: null,
        tarifa: null
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
            value: 'cup.codigo'
          },
          {
            text: 'Cobertura',
            align: 'left',
            sortable: false,
            value: 'cup.cobertura'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'cup.descripcion'
          },
          {
            text: 'Tarifa',
            align: 'right',
            sortable: false,
            value: 'tarifa',
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
            classData: 'justify-center layout px-0'
          }
        ]
      }
    }),
    watch: {
      'showFormRegistro' (val) {
        !val && this.refreshProcedimiento()
      }
    },
    created () {
      this.refreshProcedimiento()
    },
    mounted () {
      this.dataTable.route = `entidades/${this.value.id}/cups`
    },
    methods: {
      procedimientoEditar (item) {
        this.refreshProcedimiento()
        this.procedimiento = JSON.parse(JSON.stringify(item))
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
          this.$refs.postuladorProcedimiento.focus()
        }, 400)
      },
      procedimientoBorrar (item) {
        this.loading = true
        this.showFormRegistro = false
        this.axios.post(`entidades/remove-cup/${item.id}`)
          .then((response) => {
            this.$refs.tablaProcedimientos.reloadPage()
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al borrar el procedimiento. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        item.options.push({event: 'borrar', icon: 'delete', tooltip: 'Borrar'})
        return item
      },
      submitProcedimiento () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.procedimiento.rs_entidad_id = this.value.id
            this.axios.post(`entidades/add-cups`, this.procedimiento)
              .then((response) => {
                this.$refs.tablaProcedimientos.reloadPage()
                this.refreshProcedimiento()
                this.$refs.postuladorProcedimiento.focus()
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el procedimiento. ', error: e})
              })
          }
        })
      },
      refreshProcedimiento () {
        this.procedimiento = JSON.parse(JSON.stringify(this.makeProcedimiento))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
