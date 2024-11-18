<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Medicamento</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm4 md4>
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
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    required
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="medicamento.fecha"
                    @input="menuDate = false"
                    locale='es'
                    no-title
                    @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12>
                <postulador-v2
                  ref="postuladorMedicamento"
                  no-data="Busqueda por código, nombre o registro sanitario."
                  item-text="producto"
                  data-title="producto"
                  data-subtitle="expediente"
                  label="Medicamento"
                  entidad="rs_cums"
                  v-model="medicamento.cum"
                  @changeid="val => medicamento.rs_cum_id = val"
                  no-btn-create
                  no-btn-edit
                  name="Medicamento"
                  rules="required"
                  preicon="featured_play_list"
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
                        {{ value.estado_cum }}
                        </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.expediente}} - {{ value.producto }} - {{value.titular}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.descripcion_comercial }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
                />
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field v-model="medicamento.cantidad" prepend-icon="dns"
                              label="Cantidad" name="cantidad" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('cantidad')" ></v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-select
                  :items="tiposCausa"
                  v-model="medicamento.causa"
                  label="Causa"
                  name="causa"
                  prepend-icon="input"
                  :error-messages="errors.collect('causa')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-autocomplete
                  :items="glosas"
                  v-model="medicamento.cm_manglosa_id"
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
                <v-textarea
                  v-model="medicamento.observaciones"
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
        <v-toolbar-title>Medicamentos</v-toolbar-title>
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
        :items="data.concums"
        :loading="loading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id  }}</td>
          <td>{{ props.item.cum.codigo}}</td>
          <td>{{ props.item.cantidad}}</td>
          <td>{{ props.item.causa}}</td>
          <td>{{ props.item.fecha}}</td>
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
    name: 'Medicamentos',
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
        medicamento: {},
        tiposCausa: ['ACCESIBILIDAD', 'CONTINUIDAD', 'OPORTUNIDAD', 'PERTINENCIA', 'SEGURIDAD'],
        glosas: [],
        dialog: false,
        loading: false,
        loadingSubmit: false,
        menuDate: false,

        headers: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Código CUM',
            align: 'left',
            sortable: false,
            value: 'cum'
          },
          {
            text: 'Cantidad',
            align: 'left',
            sortable: false,
            value: 'cantidad'
          },
          {
            text: 'Causa',
            align: 'left',
            sortable: false,
            value: 'causa'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
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
      computedDateFormatted () {
        return this.formDate(this.medicamento.fecha)
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
        this.medicamento = {
          id: '',
          cm_convisita_id: null,
          cantidad: null,
          observaciones: null,
          rs_cum_id: null,
          cm_manglosa_id: null,
          fecha: null
        }
      },
      edit (item) {
        this.openDialog()
        this.medicamento = JSON.parse(JSON.stringify(item))
      },
      openDialog () {
        this.dialog = true
        this.medicamento.cm_convisita_id = this.data.id
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
          let send = !this.medicamento.id ? this.axios.post('cuentasmedicas/concurrencia/concums', this.medicamento) : this.axios.put('cuentasmedicas/concurrencia/concums/' + this.medicamento.id, this.medicamento)
          send.then(response => {
            if (this.medicamento.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.data.concums.splice(this.data.concums.findIndex(medicamento => medicamento.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.data.concums.unshift(response.data.data)
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
