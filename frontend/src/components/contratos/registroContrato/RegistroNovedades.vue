<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Novedades'"/>
          <small class="mt-2 ml-1"> Registro y gestión</small>
          <v-spacer/>
          <v-tooltip top v-if="!showFormRegistro && (value.novedades && ((value.novedades.length > 0 && value.novedades.find(x => x.tipo === 'Acta Inicio' && x.estado === 'Confirmada')) || value.novedades.length === 0))">
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
            <span>Crear novedad</span>
          </v-tooltip>
        </v-toolbar>
        <v-scroll-y-transition>
          <div v-if="showFormRegistro">
            <v-card-text>
              <v-form>
                <v-layout row wrap>
                  <v-flex xs12 sm6 md4>
                    <v-select
                      label="Tipo"
                      v-model="item.tipo"
                      :items="complementosNovedades"
                      name="Tipo"
                      v-validate="'required'"
                      :error-messages="errors.collect('Tipo')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-menu
                      ref="Fecha"
                      v-model="menuDate"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        label="Fecha"
                        v-model="item.fecha"
                        name="Fecha"
                        prepend-icon="event"
                        v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                        :error-messages="errors.collect('Fecha')"
                        readonly
                      />
                      <v-date-picker
                        ref="picker"
                        v-model="item.fecha"
                        locale="es-co"
                        :max="maxDate"
                        :min="minDate"
                        @input="$refs['Fecha'].save(item.fecha)"
                        @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'Fecha')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                      />
                    </v-menu>
                  </v-flex>
                  <template v-if="item.tipo !== null && item.tipo !== 'Acta Inicio'">
                    <v-flex xs12 sm6 md4>
                      <v-text-field
                        prepend-icon="attach_money"
                        label="Valor"
                        name="Valor"
                        v-validate="'required|min_value:0'"
                        min="0"
                        type="number"
                        v-model.number="item.valor"
                        :error-messages="errors.collect('Valor')"
                      />
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field
                        label="Plazo meses"
                        min="0"
                        type="number"
                        name="Plazo meses"
                        v-validate="'numeric|required|min_value:0'"
                        v-model.number="item.plazo_meses"
                        :error-messages="errors.collect('Plazo meses')"
                      />
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field
                        label="Plazo días"
                        min="0"
                        type="number"
                        name="Plazo días"
                        v-validate="'numeric|required|min_value:0'"
                        v-model.number="item.plazo_dias"
                        :error-messages="errors.collect('Plazo días')"
                      />
                    </v-flex>
                  </template>
                  <v-flex xs12 sm12 md12>
                    <v-textarea
                      v-model="item.descripcion"
                      auto-grow
                      label="Descripción"
                      rows="1"
                      name="Descripción"
                      v-validate="'required'"
                      :error-messages="errors.collect('Descripción')"
                    />
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-text>
            <v-divider/>
            <v-card-actions>
              <v-spacer/>
              <v-btn flat @click.stop="showFormRegistro = false">Cancelar</v-btn>
              <v-btn color="primary" @click.stop="submitItem">Registrar</v-btn>
            </v-card-actions>
          </div>
        </v-scroll-y-transition>
      </v-card>
    </v-flex>
    <v-flex xs12 class="pa-0 ma-0">
      <v-card class="elevation-0">
        <v-data-table
          :headers="headers"
          :items="value.novedades"
          hide-actions
          no-data-text="No hay novedades registradas"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.tipo }}</td>
            <td>{{ moment(props.item.fecha).format('YYYY-MM-DD')}}</td>
            <td class="text-xs-center">{{ props.item.estado }}</td>
            <td class="text-xs-center" :width="props.item.estado === 'Registrada' ? '110px' : '50px'">
              <template v-if="props.item.estado === 'Registrada'">
                <v-tooltip top>
                  <v-btn
                    class="ma-0"
                    flat
                    icon
                    small
                    color="accent"
                    slot="activator"
                    @click="novedadPreguntaConfirmar(props.item)"
                  >
                    <v-icon small>done_all</v-icon>
                  </v-btn>
                  <span>Confirmar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn
                    class="ma-0"
                    flat
                    icon
                    small
                    color="accent"
                    slot="activator"
                    @click="novedadEditar(props.item)"
                  >
                    <v-icon small>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </template>
            </td>
          </template>
        </v-data-table>
      </v-card>
    </v-flex>
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaNovedadPreguntaConfirmar" :requiere-motivo="false" @aceptar="confirmaNovedad" />
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {CONTRATO_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroNovedades',
    props: ['value'],
    components: {
      Loading,
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialogA: {
        visible: false,
        content: null
      },
      showFormRegistro: false,
      loading: false,
      flagReload: true,
      item: null,
      makeItem: {
        id: null,
        tipo: null,
        fecha: null,
        valor: null,
        plazo_meses: null,
        plazo_dias: null,
        descripcion: null,
        rs_contrato_id: null,
        estado: 'Registrada'
      },
      menuDate: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      headers: [
        {
          text: 'Tipo',
          align: 'left',
          sortable: false,
          value: 'tipo'
        },
        {
          text: 'Fecha',
          align: 'left',
          sortable: false,
          value: 'fecha'
        },
        {
          text: 'Estado',
          align: 'center',
          sortable: false,
          value: 'estado'
        },
        {
          text: 'Opciones',
          align: 'center',
          sortable: false,
          value: 'id'
        }
      ]
    }),
    watch: {
      'showFormRegistro' (val) {
        !val && this.refreshNovedad()
      },
      'item.tipo' (val) {
        if (val && this.flagReload) {
          this.item.plazo_meses = null
          this.item.plazo_dias = null
          this.item.valor = null
        }
      }
    },
    computed: {
      complementosNovedades () {
        if (store.getters.complementosContratoFormNovedad) {
          if (this.value.novedades.length > 0) {
            if (this.value.novedades.find(x => x.tipo === 'Acta Inicio' && x.estado === 'Confirmada')) {
              return JSON.parse(JSON.stringify(store.getters.complementosContratoFormNovedad)).tiposNovedad.filter(x => x !== 'Acta Inicio')
            } else {
              return JSON.parse(JSON.stringify(store.getters.complementosContratoFormNovedad)).tiposNovedad.filter(x => x === 'Acta Inicio')
            }
          } else {
            return JSON.parse(JSON.stringify(store.getters.complementosContratoFormNovedad)).tiposNovedad.filter(x => x === 'Acta Inicio')
          }
        }
      }
    },
    created () {
      this.refreshNovedad()
    },
    methods: {
      novedadEditar (item) {
        this.flagReload = false
        this.refreshNovedad()
        this.item = JSON.parse(JSON.stringify(item))
        this.item.fecha = this.moment(this.item.fecha).format('YYYY-MM-DD')
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
          this.flagReload = true
        }, 300)
      },
      novedadPreguntaConfirmar (item) {
        this.flagReload = false
        this.showFormRegistro = false
        this.dialogA.content = `Si confirma la novedad, ésta ya no se podrá editar. </br><strong>¿Está seguro de confirmar la novedad: ${item.tipo}?</strong>`
        this.item = item
        this.dialogA.visible = true
      },
      cancelaNovedadPreguntaConfirmar () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.flagReload = true
        this.refreshNovedad()
      },
      async confirmaNovedad () {
        this.item.estado = 'Confirmada'
        console.log('tihos.item', this.item)
        if (await this.registroItem()) {
          this.cancelaNovedadPreguntaConfirmar()
          setTimeout(() => {
            this.flagReload = true
          }, 300)
        }
      },
      registroItem () {
        this.loading = true
        this.item.rs_contrato_id = this.value.id
        const typeRequest = this.item.id ? 'editar' : 'crear'
        const request = typeRequest === 'editar'
          ? this.axios.put(`contratos/${this.value.id}/novcontratos/${this.item.id}`, this.item)
          : this.axios.post(`contratos/${this.value.id}/novcontratos`, this.item)
        return new Promise((resolve) => {
          request
            .then((response) => {
              console.log('response al guardar la novedad', response)
              this.$store.commit(SNACK_SHOW, {msg: `La novedad se ha ${this.item.estado === 'Confirmada' ? 'confirmado' : 'registrado'} correctamente.`, color: 'success'})
              this.$store.commit(CONTRATO_RELOAD_ITEM, {item: response.data.contrato_o, estado: typeRequest, key: null})
              this.$emit('input', response.data.contrato)
              this.showFormRegistro = false
              this.loading = false
              resolve(true)
            })
            .catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar el registro de la novedad. ', error: e})
              resolve(false)
            })
        })
      },
      submitItem () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.registroItem()
          }
        })
      },
      refreshNovedad () {
        this.item = JSON.parse(JSON.stringify(this.makeItem))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
