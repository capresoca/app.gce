<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Registro de medicamentos'"/>
          <small class="mt-2 ml-1"> Vinculación al contrato</small>
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
            <span>Agregar medicamento</span>
          </v-tooltip>
        </v-toolbar>
        <v-scroll-y-transition>
          <div v-if="showFormRegistro">
            <v-card-text>
              <v-form>
                <v-layout row wrap>
                  <v-flex md10 sm8 xs12>
                    <postulador-v2
                      ref="postuladorMedicamento"
                      no-data="Busqueda por código, nombre o registro sanitario."
                      item-text="cum.producto"
                      data-title="cum.producto"
                      data-subtitle="cum.expediente"
                      label="Medicamento"
                      :entidad="`entidades/${value.entidad.id}/cums`"
                      v-model="medicamento.cum"
                      no-btn-create
                      no-btn-edit
                      name="Medicamento"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect('Medicamento')"
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
                          {{ value.cum.estado_cum }}
                          </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.cum.expediente}} - {{ value.cum.producto }} - {{value.cum.titular}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.cum.descripcion_comercial }}</v-list-tile-sub-title>
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
                      v-model.number="medicamento.tarifa"
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
              <v-btn color="primary" @click.stop="submitMedicamento">Registrar</v-btn>
            </v-card-actions>
          </div>
        </v-scroll-y-transition>
      </v-card>
    </v-flex>
    <v-container fluid class="ma-0 pa-0">
      <data-table
        ref="tablaMedicamentos"
        v-if="dataTable.route"
        v-model="dataTable"
        @resetOption="item => resetOptions(item)"
        @editar="item => medicamentoEditar(item)"
        @borrar="item => medicamentoBorrar(item)"
      />
    </v-container>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroMedicamentos',
    props: ['value'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading,
      dataTable: () => import('@/components/general/DataTable')
    },
    data: () => ({
      showFormRegistro: false,
      loading: false,
      medicamento: null,
      makeMedicamento: {
        cum: null,
        id: null,
        rs_cum_id: null,
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
            value: 'cum.codigo'
          },
          {
            text: 'Producto',
            align: 'left',
            sortable: true,
            value: 'cum.producto'
          },
          {
            text: 'Laboratorio',
            align: 'left',
            sortable: true,
            value: 'cum.titular'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'cum.descripcion_comercial'
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
      'medicamento.cum.id' (val) {
        if (val) {
          this.medicamento.rs_cum_id = this.medicamento.cum.cum.id
          this.medicamento.tarifa_entidad = this.medicamento.cum.tarifa
          this.medicamento.tarifa = JSON.parse(JSON.stringify(this.medicamento.cum.tarifa))
        }
      },
      'showFormRegistro' (val) {
        !val && this.refreshMedicamento()
      }
    },
    created () {
      this.refreshMedicamento()
    },
    mounted () {
      this.dataTable.route = `contratos/${this.value.id}/cums`
    },
    methods: {
      async medicamentoEditar (item) {
        if (await this.refreshMedicamento()) {
          let temporal = JSON.parse(JSON.stringify(item))
          this.medicamento = {
            cum: {
              cum: temporal.cum,
              tarifa: temporal.tarifa_entidad
            },
            id: temporal.id,
            rs_contratos_id: temporal.rs_contratos_id,
            rs_cum_id: temporal.rs_cum_id,
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
            this.$refs.postuladorMedicamento.assign(this.medicamento.cum)
            this.$refs.postuladorMedicamento.focus()
          }, 300)
        }
      },
      medicamentoBorrar (item) {
        this.loading = true
        this.showFormRegistro = false
        this.axios.post(`contratos/remove-cum/${item.id}`)
          .then((response) => {
            this.$refs.tablaMedicamentos.reloadPage()
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
      submitMedicamento () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.medicamento.rs_contratos_id = this.value.id
            this.axios.post(`contratos/${this.value.id}/add-cum`, this.medicamento)
              .then((response) => {
                this.$refs.tablaMedicamentos.reloadPage()
                this.refreshMedicamento()
                this.$refs.postuladorMedicamento.focus()
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el medicamento. ', error: e})
              })
          }
        })
      },
      refreshMedicamento () {
        this.medicamento = JSON.parse(JSON.stringify(this.makeMedicamento))
        this.$validator.reset()
        return true
      }
    }
  }
</script>

<style scoped>
</style>
