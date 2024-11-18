<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Servicio</span>
          </v-card-title>
          <v-container fluid grid-list-md>



            <v-layout row wrap>

              <v-flex xs12 md6>
                <v-menu
                  ref="menuDateDesde"
                  :close-on-content-click="false"
                  v-model="menuDateDesde"
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
                    v-model="computedDateFormattedDesde"
                    label="Fecha desde"
                    prepend-icon="event"
                    readonly
                    name="fecha desde"
                    required
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha desde')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="procedimiento.fecha_desde"
                    @input="menuDateDesde = false"
                    locale='es'
                    no-title
                    @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha desde')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 md6>
                <v-menu
                  ref="menuDateHasta"
                  :close-on-content-click="false"
                  v-model="menuDateHasta"
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
                    v-model="computedDateFormattedHasta"
                    label="Fecha hasta"
                    prepend-icon="event"
                    readonly
                    name="fecha hasta"
                    required
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha hasta')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="procedimiento.fecha_hasta"
                    @input="menuDateHasta = false"
                    locale='es'
                    no-title
                    @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha hasta')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 md6>
                <v-select
                  :items="tiposServicio"
                  v-model="procedimiento.tipo_servicio"
                  label="Servicio"
                  name="servicio"
                  prepend-icon="input"
                  :error-messages="errors.collect('servicio')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>
            </v-layout>

            <v-layout row wrap>
              <v-flex xs12>
                <postulador-v2 v-if="procedimiento.tipo_servicio === 'PROCEDIMIENTO'"
                  ref="postuladorProcedimiento"
                  no-data="Busqueda por código o nombre."
                  item-text="descripcion"
                  label="Procedimiento"
                  entidad="cups"
                  hint="codigo"
                  persistent-hint
                  v-model="procedimiento.cup"
                  @changeid="val => procedimiento.rs_cup_id = val"
                  no-btn-create
                  no-btn-edit
                  name="procedimiento"
                  rules="required"
                  preicon="assignment"
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

              <v-flex xs12>
                <postulador-v2 v-if="procedimiento.tipo_servicio === 'ESTANCIA'"
                               ref="postuladorProcedimiento"
                               no-data="Busqueda por código o nombre."
                               item-text="descripcion"
                               label="Procedimiento"
                               entidad="cups"
                               route-params="cupscategoria:subgrupo:rs_cupsgrupo_id=109"
                               hint="codigo"
                               persistent-hint
                               v-model="procedimiento.cup"
                               @changeid="val => procedimiento.rs_cup_id = val"
                               no-btn-create
                               no-btn-edit
                               name="procedimiento"
                               rules="required"
                               preicon="assignment"
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
            </v-layout>

            <v-layout row wrap>
              <v-flex xs12 md6>
                <v-text-field v-model="procedimiento.cantidad" prepend-icon="dns"
                              label="Cantidad" name="cantidad" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('cantidad')" ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  :items="glosas"
                  v-model="procedimiento.cm_manglosa_id"
                  item-text="descripcion"
                  item-value="id"
                  label="Glosa"
                  no-data-text="No hay datos disponibles"
                  name="glosa"
                  prepend-icon="security"
                  v-validate="'required'" required
                  :error-messages="errors.collect('glosa')"
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.descripcion"/>
                        <v-list-tile-sub-title v-html="data.item.tipo"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12>
                <v-select
                  :items="tiposCausa"
                  v-model="procedimiento.causa"
                  label="Causa"
                  name="causa"
                  prepend-icon="input"
                  :error-messages="errors.collect('causa')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-select
                  :items="tiposCausaEspecifica"
                  v-model="procedimiento.causa_especifica"
                  label="Causa específica"
                  name="causa especifica"
                  prepend-icon="input"
                  :error-messages="errors.collect('causa especifica')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="procedimiento.observaciones"
                  outline
                  name="observaciones"
                  label="Observaciones"
                ></v-textarea>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="guardar" :loading="loadingSubmit" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-toolbar-title>Servicio</v-toolbar-title>
        <small class="mt-2 ml-1"> Registro y gestión</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            fab
            color="accent"
            small
            slot="activator"
            @click="openDialog()"
            :disabled="concurrencia.estado === 'Cerrada'"
          >
            <v-icon>add</v-icon>
          </v-btn>
          <span>{{ concurrencia.estado === 'Abierta' ? 'Crear registro' : 'Concurrencia cerrada'}}</span>
        </v-tooltip>
      </v-toolbar>

      <loading v-model="loading" />
      <v-data-table
        :headers="headers"
        :items="data.concups"
        :loading="loading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id  }}</td>
          <td>{{ props.item.cup.codigo}}</td>
          <td>{{ props.item.cantidad}}</td>
          <td>{{ props.item.tipo_servicio}}</td>
          <td>{{ props.item.fecha_desde}}</td>
          <td>{{ props.item.fecha_hasta}}</td>
          <td>
            <v-tooltip top>
              <v-btn
                icon
                slot="activator"
                @click="edit(props.item)"
                :disabled="concurrencia.estado === 'Cerrada'"
              >
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>{{ concurrencia.estado === 'Abierta' ? 'Editar' : 'Concurrencia cerrada'}}</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!loading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Procedimientos',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading
    },
    props: {
      data: {
        type: Object,
        default: null
      },
      concurrencia: {
        type: Object,
        default: {}
      }
    },
    data () {
      return {
        procedimiento: {},
        tiposServicio: ['ESTANCIA', 'PROCEDIMIENTO'],
        tiposCausa: ['ACCESIBILIDAD', 'CONTINUIDAD', 'OPORTUNIDAD', 'PERTINENCIA', 'SEGURIDAD'],
        tiposCausaEspecifica: ['ACEPTACIÓN SERVICIO DOMICILIARIO', 'ESTANCIA PROLONGADA', 'INOPORTUNIDAD EN PRESTACIÓN DEL SERVICIO', 'SERVICIO NO PERTINENTE'],
        glosas: [],
        dialog: false,
        loading: false,
        loadingSubmit: false,
        menuDateDesde: false,
        menuDateHasta: false,

        headers: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Código CUP',
            align: 'left',
            sortable: false,
            value: 'cup'
          },
          {
            text: 'Cantidad',
            align: 'left',
            sortable: false,
            value: 'cantidad'
          },
          {
            text: 'Tipo servicio',
            align: 'left',
            sortable: false,
            value: 'tipoServicio'
          },
          {
            text: 'Desde',
            align: 'left',
            sortable: false,
            value: 'fecha_desde'
          },
          {
            text: 'Hasta',
            align: 'left',
            sortable: false,
            value: 'fecha_hasta'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.resetDialog()
    },
    mounted () {
      this.getGlosas()
    },
    computed: {
      computedDateFormattedDesde () {
        return this.formDate(this.procedimiento.fecha_desde)
      },
      computedDateFormattedHasta () {
        return this.formDate(this.procedimiento.fecha_hasta)
      }
    },
    methods: {
      getGlosas () {
        this.axios.get('manglosas')
          .then((response) => {
            if (response.data !== '') {
              this.glosas = response.data.data.glosas
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las glosas. ' + e, color: 'danger'})
          })
      },
      resetDialog () {
        this.procedimiento = {
          id: '',
          cm_convisita_id: null,
          cantidad: null,
          observaciones: null,
          rs_cup_id: null,
          tipo_servicio: null,
          causa: null,
          causa_especifica: null,
          fecha_desde: null,
          fecha_hasta: null
        }
      },
      edit (item) {
        this.openDialog()
        this.procedimiento = JSON.parse(JSON.stringify(item))
      },
      openDialog () {
        this.dialog = true
        this.procedimiento.cm_convisita_id = this.data.id
      },
      close () {
        this.dialog = false
        this.resetDialog()
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardar () {
        if (await this.validador('form')) {
          this.loadingSubmit = true
          let send = !this.procedimiento.id ? this.axios.post('cuentasmedicas/concurrencia/concups', this.procedimiento) : this.axios.put('cuentasmedicas/concurrencia/concups/' + this.procedimiento.id, this.procedimiento)
          send.then(response => {
            if (this.procedimiento.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.data.concups.splice(this.data.concups.findIndex(procedimiento => procedimiento.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.data.concups.unshift(response.data.data)
            }
            this.close()
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
