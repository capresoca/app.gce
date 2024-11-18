<template>
  <v-dialog v-model="dialog" persistent max-width="920">
    <template v-slot:activator="{ on }">
      <v-btn color="info" v-on="on">
        <v-icon left>fas fa-file-upload</v-icon>
        Cargar Respuesta
      </v-btn>
    </template>
    <v-card>
      <loading v-model="loading"></loading>
      <v-card-title class="title py-1">
        <v-avatar size="38" color="primary" class="mr-2">
          <v-icon class="white--text">fas fa-file-upload</v-icon>
        </v-avatar>
        Cargar Respuesta
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-container fluid grid-list-md class="pb-0">
        <v-layout row wrap>
          <v-flex xs12 sm12 md4>
            <input-date
              v-model="respuesta.fecha"
              label="Fecha (Año-Mes-Día)"
              format="YYYY-MM-DD"
              name="Fecha"
              :min="minDate"
              :max="maxDate"
              v-validate="(validaArchivo === true ? 'required' : '')+'|fechaValida|date_format:YYYY-MM-DD' + '|date_between:' + minDate + ',' + maxDate + ',true'"
              :error-messages="errors.collect('Fecha')"
            ></input-date>
          </v-flex>
          
          <v-flex xs12 sm12 md8>
            <v-text-field
              label="Descripción"
              v-model="respuesta.descripcion"
              name="Descripción"
              required
              v-validate="validaArchivo === true ? 'required' : ''"
              :error-messages="errors.collect('Descripción')"
            />
          </v-flex>
          
          <v-flex xs12 sm12 md4>
            <v-select
              label="Tipo Archivo"
              :items="tipos"
              name="tipo"
              v-validate="'required'"
              :error-messages="errors.collect('tipo')"
              v-model="respuesta.tipo"
              v-on:change="setSeleccionTipo()"
            ></v-select>
          </v-flex>
          
          <v-flex xs12 sm12 md4>
            <v-select
              label="Archivo"
              :items="listaComboSiNo"
              name="Archivo"
              v-validate="'required'"
              :error-messages="errors.collect('Archivo')"
              v-model="respuesta.archivo"
              v-on:change="setSeleccionTipo()"
            ></v-select>
          </v-flex>
          
          <v-flex xs12 sm12 md4>
            <v-select
              label="Finaliza"
              :items="listaComboSiNo"
              name="Finaliza"
              v-validate="'required'"
              :error-messages="errors.collect('Finaliza')"
              v-model="respuesta.finaliza"
            ></v-select>
          </v-flex>
          
          <v-flex xs12 sm12 md12>
          	<v-autocomplete
              label="Periodos"
              v-model="respuesta.vigencia"
              :items="listaCombo"
              item-value="consecutivo_vigencia"
              item-text="descripcion"
              name="vigencia"
              required
              v-validate="'required'"
              :error-messages="errors.collect('vigencia')"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm12 md12>
            <input-file-v2
              label="Archivo"
              v-model="respuesta.file"
              accept="text/plain"
              :hint="'Extenciones aceptadas: txt'"
              prepend-icon="description"
              name="archivo"
              v-validate="validaArchivo === true ? 'required' : ''"
              :error-messages="errors.collect('archivo')"
            ></input-file-v2>
          </v-flex>
        </v-layout>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success darken-1" @click="submit">Cargar archivo</v-btn>
      </v-card-actions>
      <v-slide-y-transition group>
        <template v-if="errores && errores.length">
          <v-divider key="dividererrorbdua"></v-divider>
          <v-card-text key="cardtexterrorbdua">
            <template v-for="(error, indexerror) in errores">
              <v-subheader class="title">
                <v-icon left :key="`iconoerror${indexerror}`" :color="error.tipo === 'error' ? 'error' : 'info'">{{error.tipo === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle'}}</v-icon>
                {{error.titulo}}
              </v-subheader>
              <v-data-table
                v-if="error.afiliados && error.afiliados.length"
                :key="`tableerror${indexerror}`"
                :items="error.afiliados"
                hide-actions
                hide-headers
                class="elevation-1"
              >
                <template v-slot:items="props">
                  <tr>
                    <td style="height: 24px !important;" class="py-0">
                      <a v-if="props.item.id" @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: props.item.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">{{ [(props.item.tipo_identificacion + '' + props.item.identificacion), props.item.nombre_completo].filter(x => x).join(' ') }}</a>
                      <span v-else>{{ [(props.item.tipo_identificacion + '' + props.item.identificacion), props.item.nombre_completo].filter(x => x).join(' ') }}</span>
                    </td>
                  </tr>
                </template>
              </v-data-table>
            </template>
          </v-card-text>
        </template>
      </v-slide-y-transition>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'CargaRespuesta',
    components: {
      InputFileV2: () => import('@/components/general/InputFileV2')
    },
    data: () => ({
      vigencia: '',
      validaArchivo: true,
      listaCombo: [],
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      dialog: false,
      loading: false,
      tipos: ['MS.VAL', 'MS.NEG', 'MC.VAL', 'MC.NEG', 'NC.VAL', 'NC.NEG', 'NS.VAL', 'NS.NEG', 'R1.VAL', 'R2', 'S3', 'S4', 'R4', 'R5', 'R6', 'S1.VAL', 'S2', 'S5', 'S6', 'AUTOMATICOS-R1', 'AUTOMATICOS-R2', 'AUTOMATICOS-S1', 'AUTOMATICOS-S2'],
      listaComboSiNo: ['Si', 'No'],
      respuesta: null,
      makeRespuesta: {
        tipo: null,
        fecha: null,
        descripcion: null,
        vigencia: null,
        archivo: null,
        finaliza: null,
        file: null
      },
      errores: []
    }),
    watch: {
      'dialog' (val) {
        if (!val) {
          setTimeout(() => {
            this.resetModel()
          }, 400)
        }
      }
    },
    created () {
      this.resetModel()
    },
    computed: {
      complementos () {
        return store.getters.complementosPeriodosEnvios
      }
    },
    methods: {
      setSeleccionTipo () {
        if (this.respuesta.tipo !== null && this.respuesta.tipo !== '') {
          var temporal = this.respuesta.tipo.split('.')
          console.log(temporal[0])
          this.listaCombo = temporal[0] === 'NS' || temporal[0] === 'NC' ? this.complementos.periodosNovedadesCerrados : this.complementos.periodosTrasladosCerrados
        }
        if (this.respuesta.archivo !== null && this.respuesta.archivo !== '') {
          this.validaArchivo = true
          if (this.respuesta.archivo === 'No') {
            this.validaArchivo = false
          }
          console.log(this.validaArchivo)
        }
      },
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            console.log(this.respuesta.vigencia)
            this.errores = []
            this.loading = true
            let data = new FormData()
            data.append('tipo', this.respuesta.tipo)
            data.append('descripcion', this.respuesta.descripcion)
            data.append('archivo', this.respuesta.archivo)
            data.append('finaliza', this.respuesta.finaliza)
            data.append('fecha', this.respuesta.fecha)
            data.append('vigencia', this.respuesta.vigencia)
            data.append('file', this.respuesta.file)
            this.axios.post('bduafiles/respuesta', data)
              .then(response => {
                console.log(response.data)
                this.errores = response.data
                if (this.errores.find(x => x.tipo === 'error')) {
                  this.$store.commit('SNACK_SHOW', {msg: 'La carga de las respuestas contienen errores. ', color: 'orange'})
                } else {
                  this.$store.commit('SNACK_SHOW', {msg: 'La carga de las respuestas se registró sin errores. ', color: 'success'})
                  this.$store.commit('reloadTable', 'tableRespuestasBDUA')
                }
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el archivo de respuestas. ', error: e})
              })
          }
        })
      },
      resetModel () {
        this.respuesta = {...this.makeRespuesta}
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>

</style>
