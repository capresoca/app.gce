<template>
  <v-card>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formSolTraslado">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
            <v-tooltip top>
              <v-chip :color="solTraslado.dias_eps > 365 ? 'green' : 'red'" text-color="white" slot="activator">{{ solTraslado.dias_eps}} días</v-chip>
              <span>{{solTraslado.dias_eps > 365 ? 'Cumple' : 'No cumple'}}</span>
            </v-tooltip>

          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm6 md6>
                <v-text-field v-model="solTraslado.identificacion"
                              label="Identificación" key="indentificacion"
                              name="indentificacion" v-validate="'required'" required
                              :error-messages="errors.collect('indentificacion')" :disabled="true"></v-text-field>


              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="solTraslado.nombre_afiliado"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required'" required
                              :error-messages="errors.collect('nombre')" :disabled="true"></v-text-field>
              </v-flex>

              <v-flex xs12 sm12 md12>
                <v-text-field v-model="solTraslado.eps"
                              label="EPS" key="eps"
                              name="eps" v-validate="'required'" required
                              :error-messages="errors.collect('eps')" :disabled="true"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="solTraslado.fecha_solicitada"
                              label="Fecha de solicitud" key="fechaSolicitud"
                              name="fechaSolicitud" v-validate="'required'" required
                              :error-messages="errors.collect('fechaSolicitud')" :disabled="true"></v-text-field>
              </v-flex>


              <v-flex xs12 sm6 md6>
                <v-select
                  :items="tiposRespuesta"
                  v-model="solTraslado.respuesta"
                  name="tipo de respuesta"
                  label="Respuesta"
                  :error-messages="errors.collect('tipo de respuesta')"
                  v-validate="'required'" required
                  single-line
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-autocomplete
                  label="Motivo"
                  v-model="solTraslado.as_cautraslado_id"
                  :items="tiposCausalRespuesta"
                  item-value="id"
                  item-text="causa"
                  name="causal respuesta"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('causal respuesta')"
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.causa"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-slide-x-transition>
                <v-flex xs12 sm6 md6 v-if="solTraslado.respuesta === 'Aprobado' || solTraslado.as_cautraslado_id === 16">
                  <v-menu
                    ref="menuDate"
                    :close-on-content-click="false"
                    v-model="menuDate"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    max-width="290px"
                    min-width="290px"
                  >
                    <v-text-field
                      slot="activator"
                      v-model="computedDateFormatted"
                      label="Fecha Factible"
                      prepend-icon="event"
                      readonly
                      name="fecha"
                      v-validate="'required|date_format:YYYY/MM/DD'"
                      :error-messages="errors.collect('fecha')"
                    ></v-text-field>
                    <v-date-picker
                      v-model="solTraslado.fecha_factible"
                      @input="menuDate = false"
                      @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                      }"
                      locale='es'
                      no-title
                      :min="minDate"
                    ></v-date-picker>
                  </v-menu>
                </v-flex>
              </v-slide-x-transition>

              <!--<v-flex xs12>
                <input type="file" hidden ref="inputAnexo" accept="image/jpeg,image/gif,image/png,application/pdf" @change="onFilePicked($event)"/>
                <v-text-field
                  v-model="solTraslado.anexo.nombre"
                  label="Archivo"
                  prepend-icon="attach_file"
                  readonly
                  @click.native="$refs.inputAnexo.click()"
                  name="Archivo"
                  required
                  v-validate="'required'"
                  :error-messages="errorArchivo"
                  :loading="loadingArchivo"
                />
              </v-flex>-->



            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click.native="dialogConfirm = true" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogConfirm" persistent max-width="290">
      <v-card>
        <v-card-title class="headline grey lighten-3">Alerta</v-card-title>
        <v-card-text>¿Está seguro que desea realizar esta acción?</v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <v-checkbox
            label="Radicado"
            v-model="checkboxRadicado"
          ></v-checkbox>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="dialogConfirm = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click.native="save">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <toolbar-list :info="infoComponent" title="Solicitud de Traslados" subtitle="Registro y gestión"></toolbar-list>
    <v-tabs
      grow
      fixed-tabs
      color="cyan"
      dark
    >
      <v-tabs-slider color="accent"></v-tabs-slider>
      <v-tab href="#tab-1">
        <v-icon left>fas fa-clipboard-list</v-icon>
        Subsidiados
      </v-tab>
      <v-tab href="#tab-2">
        <v-icon left>fas fa-clipboard-list</v-icon>
        Contributivos
      </v-tab>
      <v-tab-item
        value="tab-1"
      >
        <v-card flat>
          <data-table-v2
            ref="tableSolicitudesTrasladosS2"
            v-model="dataTableS2"
            @resetOption="item => resetOptions(item)"
            @editar="item => edit(item)"
          >
          </data-table-v2>
        </v-card>
      </v-tab-item>
      <v-tab-item
        value="tab-2"
      >
        <v-card flat>
          <data-table-v2
            ref="tableSolicitudesTrasladosR2"
            v-model="dataTableR2"
            @resetOption="item => resetOptions(item)"
            @editar="item => edit(item)"
          >
          </data-table-v2>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>


<script>
  export default {
    name: 'SolTraslados',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTableS2: {
          nameItemState: 'tableSolicitudesTrasladosS2',
          route: 'soltraslados?tipo=S2',
          makeHeaders: [
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'mini_afiliado',
              component: {
                template:
                  `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
                props: ['value']
              }
            },
            {
              text: 'EPS',
              align: 'left',
              sortable: false,
              value: 'eps'
            },
            {
              text: 'Dias EPS',
              align: 'left',
              sortable: true,
              value: 'dias_eps',
              component: {
                template:
                  `<v-chip :color="value.dias_eps > 365 ? 'green' : 'red'" text-color="white">{{ value.dias_eps}}</v-chip>`,
                props: ['value']
              }
            },
            {
              text: 'Fecha de solicitud',
              align: 'left',
              sortable: false,
              value: 'fecha_solicitada'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
            },
            {
              text: 'Respuesta',
              align: 'left',
              sortable: false,
              value: 'respuesta'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ]
        },
        dataTableR2: {
          nameItemState: 'tableSolicitudesTrasladosR2',
          route: 'soltraslados?tipo=R2',
          makeHeaders: [
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'mini_afiliado',
              component: {
                template:
                  `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
                props: ['value']
              }
            },
            {
              text: 'EPS',
              align: 'left',
              sortable: false,
              value: 'eps'
            },
            {
              text: 'Dias EPS',
              align: 'left',
              sortable: true,
              value: 'dias_eps',
              component: {
                template:
                  `<v-chip :color="value.dias_eps > 365 ? 'green' : 'red'" text-color="white">{{ value.dias_eps}}</v-chip>`,
                props: ['value']
              }
            },
            {
              text: 'Fecha de solicitud',
              align: 'left',
              sortable: false,
              value: 'fecha_solicitada'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
            },
            {
              text: 'Respuesta',
              align: 'left',
              sortable: false,
              value: 'respuesta'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ]
        },
        solTraslados: [],
        solTraslado: {},
        tiposRespuesta: ['Aprobado', 'Negado'],
        tiposCausalRespuesta: [],
        listaCausalesAprobacion: [],
        listaCausalesNegacion: [],
        menuDate: false,
        minDate: this.moment().format('YYYY-MM-DD'),
        dialog: false,
        dialogConfirm: false,
        loadingArchivo: false,
        errorArchivo: '',
        checkboxRadicado: ''
      }
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue registrado.'
          },
          nombre: {
            not_in: 'Este nombre ya fue registrado.'
          }
        }
      }
      this.$validator.localize('es', dict)
    },
    beforeCreate () {
      this.axios.get('cautraslados')
        .then((res) => {
          this.tiposCausalRespuesta = res.data.data
          this.filtrarCausalesDeAprobacion()
          this.filtrarCausalesDeNegacion()
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    watch: {
      'solTraslado.respuesta' (val) {
        if (val === 'Aprobado') {
          this.tiposCausalRespuesta = this.listaCausalesAprobacion
        } else {
          this.tiposCausalRespuesta = this.listaCausalesNegacion
          this.solTraslado.fecha_factible = null
        }
      },
      'solTraslado.estado' (val) {
        val === 'Radicado' ? this.checkboxRadicado = true : this.checkboxRadicado = false
      },
      'checkboxRadicado' (val) {
        val ? this.solTraslado.estado = 'Radicado' : this.solTraslado.estado = 'Registrado'
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('tramitesSolTraslado')
      },
      modalTitulo () {
        return `Solicitud de traslado de ${this.solTraslado.nombre_afiliado}`
      },
      computedDateFormatted () {
        return this.formDate(this.solTraslado.fecha_factible)
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'feedback', tooltip: this.textoTooltip(item), disabled: this.estadoBoton(item)})
        return item
      },
      filtrarCausalesDeAprobacion () {
        this.listaCausalesAprobacion = this.tiposCausalRespuesta.filter((causal) => causal.tipo === 'Aceptación')
      },
      filtrarCausalesDeNegacion () {
        this.listaCausalesNegacion = this.tiposCausalRespuesta.filter((causal) => causal.tipo === 'Negación')
      },
      estadoBoton (item) {
        return item.estado === 'Radicado'
      },
      textoTooltip (item) {
        return item.estado === 'Radicado' ? 'Ya se encuentra radicado' : 'Responder'
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.dialog = false
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      edit (item) {
        this.dialog = true
        this.solTraslado = JSON.parse(JSON.stringify(item))
      },
      async save () {
        if (await this.validador('formSolTraslado')) {
          this.axios.put('soltraslados/' + this.solTraslado.id, this.solTraslado)
            .then(response => {
              if (this.solTraslado.id) {
                this.$store.commit('SNACK_SHOW', {msg: 'La solicitud se actualizó correctamente', color: 'success'})
                this.solTraslados.splice(this.solTraslados.findIndex(solTraslado => solTraslado.id === response.data.data.id), 1, response.data.data)
              } else {
                this.$store.commit('SNACK_SHOW', {msg: 'La solicitud se creó correctamente', color: 'success'})
                this.solTraslados.splice(0, 0, response.data.data)
              }
              this.dialog = false
              this.dialogConfirm = false
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_SHOW', {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
        } else {
          this.$store.commit('SNACK_SHOW', {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }

    }

  }
</script>

<style scoped>

</style>
