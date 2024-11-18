<template>
  <main>
    <v-speed-dial
      bottom
      fixed
      left
      direction="top"
      open-on-hover
      transition="slide-y-reverse-transition"
      class="mb-4">
      <v-tooltip right slot="activator">
        <v-btn fab
               color="blue darken-2"
               slot="activator">
          <v-icon color="white">save</v-icon>
        </v-btn>
        <span>Crear/Importar</span>
      </v-tooltip>
      <v-tooltip top>
        <v-btn flat icon color="white"
               slot="activator" @click="dialogNuevo=true">
          <v-icon color="primary">add_circle_outline</v-icon>
        </v-btn>
        <span>Crear Medicamento Individual</span>
      </v-tooltip>
      <v-tooltip top>
        <v-btn flat icon color="white"
               slot="activator" @click="importarArchivo('plano')">
          <v-icon color="blue-grey">assignment</v-icon>
        </v-btn>
        <span>Importar por archivo de texto</span>
      </v-tooltip>
      <v-tooltip top>
        <v-btn flat icon color="white"
               slot="activator" @click="importarArchivo('csv')">
          <v-icon color="green">ballot</v-icon>
        </v-btn>
        <span>Importar por archivo Excel</span>
      </v-tooltip>
    </v-speed-dial>
    <v-card>
      <toolbar-list class="elevation-0 grey lighten-4"  title="CIE10" />
      <v-card-text>
        <v-container fluid grid-list-md>
          <v-layout row wrap>
            <v-flex xs12 sm3 md2>
              <v-select
                label="Registros por página"
                v-model="pagination.per_page"
                :items="items_page"
                item-text="text"
                item-value="value">
              </v-select>
            </v-flex>
            <v-flex xs12 sm9 md9 lg9 xl9>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Buscar"
                @input="buscar">
              </v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-container fluid>
        <v-data-table
          :headers="headers"
          :items="cie10s"
          :search="search"
          :pagination="pagination"
          hide-actions
          :total-items="cie10s.length"
          :loading="tableLoading" :rows-per-page-items='[50,100,150,300,{"text":"Todos","value":-1}]'>
          <template slot="items" slot-scope="props">
            <td>{{ props.item.id }}</td>
            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.descripcion }}</td>
            <td>{{ props.item.genero }}</td>
            <td>{{ props.item.edad_min }}</td>
            <td>{{ props.item.edad_max }}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                :direction="favbutton.direction"
                :open-on-hover="favbutton.hover"
                :transition="favbutton.transition">
                <v-btn slot="activator" v-model="favbutton.fab"
                  color="blue darken-2" dark fab small>
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <!--<v-tooltip bottom>-->
                  <!--<v-btn small fab color="white"-->
                         <!--slot="activator" @click="verCie10(props.item)">-->
                    <!--<v-icon color="green">description</v-icon>-->
                  <!--</v-btn>-->
                  <!--<span>Detalles</span>-->
                <!--</v-tooltip>-->
                <v-tooltip bottom>
                  <v-btn small fab color="white"
                         slot="activator" @click="editarCie10(props.item)">
                    <v-icon color="accent">edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </template>
          <template slot="footer">
            <td colspan="100%">
              <div class="text-xs-center">
                <v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>
                <!--<v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>-->
              </div>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>
        </v-data-table>
      </v-container>
      <v-layout row justify-center>
        <v-dialog v-model="dialogNuevo" max-width="600px">
          <v-card>
            <v-card-title class="grey lighten-3">
              <h2 class="title">{{ modalTitle }}</h2>
              <!--<v-divider></v-divider>-->
            </v-card-title>
            <v-card-text  class="pt-0 pb-0">
              <v-form data-vv-scope="formCie10">
                <v-container fluid grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-text-field label="Código" key="codigo" name="codigo"
                                    v-model="cie10.codigo" v-validate="'required|max:4|not_in:' + codigosUsados"
                                    :error-messages="errors.collect('codigo')"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-text-field label="Código de 3 Digitos" v-model="cie10.codigo_tres" required :disabled="true" readonly></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-textarea label="Descripción" key="descripción" name="descripción"
                                  v-model="cie10.descripcion" v-validate="'required'" :error-messages="errors.collect('descripción')"></v-textarea>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-select
                        :items="complementosCie10.generos"
                        label="Género"
                        v-model="cie10.genero"
                        key="género"
                        name="género"
                        v-validate="'required'"
                        :error-messages="errors.collect('género')">
                      </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-text-field label="Edad Mínima" v-model="cie10.edad_min" v-validate="'required'"
                                    key="edad mínima" name="edad mínima" :error-messages="errors.collect('edad mínima')"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-select
                        :items="select_dias"
                        item-text="text"
                        item-value="value"
                        label="Tipo de Edad (-)"
                        v-model="tipoEdadNewMin"
                        @change="tipoEdadNewMinCambio()"
                        v-validate="'required'"
                        key="tipo de edad (-)"
                        name="tipo de edad (-)"
                        :error-messages="errors.collect('tipo de edad (-)')">
                      </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-text-field label="Edad Máxima" v-model="cie10.edad_max" v-validate="'required'"
                                    key="edad máxima" name="edad máxima" :error-messages="errors.collect('edad máxima')"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6 lg6>
                      <v-select
                        :items="select_dias"
                        item-text="text"
                        item-value="value"
                        label="Tipo de Edad (+)"
                        v-model="tipoEdadNewMax"
                        @change="tipoEdadNewMaxCambio()"
                        v-validate="'required'"
                        key="tipo de edad (+)"
                        name="tipo de edad (+)"
                        :error-messages="errors.collect('tipo de edad (+)')">
                      </v-select>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
              <v-divider></v-divider>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat @click.native="close" v-text="'Cancelar'"></v-btn>
              <v-btn flat color="blue darken-1" @click="save" v-text="'Guardar'"></v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
      <v-layout row justify-center>
        <v-dialog v-model="dialogArchivo" max-width="500px">
          <v-card>
            <v-card-title class="grey lighten-3">
              <v-container class="pt-0 pb-0">
                <h2 class="title" v-text="'Cargar CIE10 por Archivo ' + (mimeType === 'text/plain' ? 'Plano' : 'Excel CSV') "></h2>
              </v-container>
            </v-card-title>
            <v-card-text class="pt-0 pb-0">
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <input-file ref="fileAdd" :label="'Cargar Archivo'" v-model="archivo"
                    :accept="mimeType" :hint="'Exensiones aceptadas: ' + extension" prepend-icon="description"></input-file>
                    <!--<input type="file" ref="filePlano" v-on:change="handleFilePlano()" id="filePlano" name="filePlano[]" multiple>-->
                  </v-flex>
                </v-layout>
              </v-container>
              <v-container fluid grid-list-xl class="pt-0">
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <h3>Observaciones.</h3>
                    <p v-if="extension !== '.csv'" class="text-xs-justify" v-text="'Solo se puede cargar un archivo en formato .txt o .TXT, Se debe seguir el formato y los requisitos que existen en el manual de usuario. Para más información.'"></p>
                    <p v-else class="text-xs-justify" v-text="'Solo se puede cargar un archivo de un solo libro en formato .csv (Archivo de excel delimitado por comas), Se debe segir el formato y requisitos que existen en el manual de usuario.'"></p>
                  </v-flex>
                  <v-flex xs12 sm12 md12 lg12>
                    <h3>Formato.</h3>
                    <p class="text-xs-justify">{{ 'Los datos deben estar separados por ' + (extension !== '.csv' ? '"," (coma) ' : '";" (punto y coma)') + 'de la siguiente manera:' }} <br>
                      CODIGO<strong class="headline" v-text="(extension !== '.csv' ? ' , ' : ' ; ')"></strong>
                      CODIGO DE 3 DIGITOS<strong class="headline" v-text="(extension !== '.csv' ? ' , ' : ' ; ')"></strong>
                      DESCRIPCION<strong class="headline" v-text="(extension !== '.csv' ? ' , ' : ' ; ')"></strong>
                      GENERO<strong class="headline" v-text="(extension !== '.csv' ? ' , ' : ' ; ')"></strong>
                      EDAD MINIMA<strong class="headline" v-text="(extension !== '.csv' ? ' , ' : ' ; ')"></strong>
                      EDAD MAXIMA
                    </p>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat @click.native="closeTwo()" v-text="'Cerrar'"></v-btn>
              <v-btn color="blue darken-1" flat @click="(extension !== '.csv') ? cargarArchivoPlanoCie10() : cargarArchivoCsvCie10()" v-text="'Guardar'"></v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
      <v-dialog v-model="dialogInicioTransaccion" hide-overlay
                persistent width="300">
        <v-card color="primary" dark>
          <v-card-text>
            Por favor espere, mientras se carga el archivo
            <v-progress-linear indeterminate color="white" class="mb-0">
            </v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-card>
  </main>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../store/complementos/index'
  import InputFile from '../../general/InputFile'
  export default {
    name: 'Cie10',
    components: {
      InputFile,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        cie10s: [],
        fab: false,
        search: '',
        tableLoading: false,
        archivo: null,
        extension: '',
        mimeType: '',
        dialogNuevo: false,
        dialogArchivo: false,
        dialogInicioTransaccion: false,
        tipoEdadVerMax: 'NA',
        tipoEdadVerMin: 'NA',
        tipoEdadNewMax: 'NA',
        tipoEdadNewMin: 'NA',
        codigosUsados: '',
        cie10: {},
        cie10Ver: {
          id: '',
          codigo: '',
          codigo_tres: '',
          descripcion: '',
          genero: '',
          edad_min: '',
          edad_max: ''
        },
        select_dias: [
          { text: 'No aplica', value: 'NA' },
          { text: 'Dias', value: 'D' },
          { text: 'Meses', value: 'M' },
          { text: 'Años', value: 'A' }
        ],
        items_page: [
          {
            text: 50,
            value: 50
          },
          {
            text: 100,
            value: 100
          },
          {
            text: 150,
            value: 150
          },
          {
            text: 300,
            value: 300
          }
        ],
        pagination: {
          per_page: 50,
          current_page: 1
        },
        headers: [
          {
            text: 'Id',
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Codigo', align: 'left', sortable: false, value: 'codigo' },
          { text: 'Descripcion', align: 'left', sortable: false, value: 'descripcion' },
          { text: 'Genero', align: 'left', sortable: false, value: 'genero' },
          { text: 'Edad minima', align: 'left', sortable: false, value: 'edad_min' },
          { text: 'Edad maxima', align: 'left', sortable: false, value: 'edad_max' },
          { text: 'Opciones', align: 'right', sortable: false, value: '' }
        ],
        favbutton: {direction: 'left', hover: true, transition: 'slide-x-transition'}
      }
    },
    computed: {
      complementosCie10 () {
        return store.getters.complementosCie10
      },
      modalTitle () {
        return !this.cie10.id ? 'Nuevo CIE10' : 'En Edición del CIE10 Cod: ' + this.cie10.codigo
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue usado.'
          }
        }
      }
      this.$validator.localize('es', dict)
    },
    methods: {
      formReset () {
        this.cie10 = {
          id: '',
          codigo: '',
          codigo_tres: '',
          descripcion: '',
          genero: '',
          edad_min: '',
          edad_max: ''
        }
        this.codigosUsados = this.getCodigosUsados()
      },
      importarArchivo (val) {
        this.dialogArchivo = true
        if (val === 'plano') {
          this.mimeType = 'text/plain'
          this.extension = '.txt'
        } else if (val === 'csv') {
          this.mimeType = 'application/vnd.ms-excel'
          this.extension = '.csv'
        }
      },
      closeTwo () {
        this.dialogArchivo = false
        this.archivo = null
        this.$refs.fileAdd.reset()
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.dialogNuevo = false
      },
      getCodigosUsados () {
        return this.cie10s.map(cod => { if (!(this.cie10.codigo != null && this.cie10.codigo === cod.codigo)) return cod.codigo })
      },
      async save () {
        if (await this.validador('formCie10')) {
          let send = !this.cie10.id ? this.axios.post('rs_cie10s', this.cie10) : this.axios.put('rs_cie10s/' + this.cie10.id, this.cie10)
          send.then(res => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.cie10.id
                ? (this.cie10s.splice(this.cie10s.findIndex(cie10 => cie10.id === res.data.data.id),
                  1, res.data.data), 'actualizaron los datos')
                : (this.cie10s.splice(0, 0, res.data.data), 'realizó el registro')} correctamente del CIE10 N° ${res.data.data.codigo}.`,
              color: 'success'
            })
            this.dialogNuevo = false
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro.', color: 'danger'})
          })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      reloadPage () {
        this.tableLoading = true
        this.axios.get('rs_cie10s?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.cie10s = response.data.data
            this.tableLoading = false
            this.codigosUsados = this.getCodigosUsados()
          }).catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      // verCie10 (data) {
      //   this.editar = false
      //   this.dialog = true
      //   this.cie10Ver = data
      // },
      editarCie10 (item) {
        this.dialogNuevo = true
        this.cie10 = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
      },
      cargarArchivoPlanoCie10 () {
        this.dialogArchivo = false
        this.dialogInicioTransaccion = true
        // Initialize the form data
        let formData = new FormData()
        formData.append('cie10Plano', this.archivo)
        this.axios.post('importar-cie10-desde-plano', formData)
          .then(response => {
            this.dialogInicioTransaccion = false
            this.archivo = null
            this.$store.commit(SNACK_SHOW, {
              msg: `${response.data.state === 'save'
                ? 'Los archivos CIE10 se guardaron con éxito en la base de datos.'
                : 'Algo salio mal al guardar los CIE10, ' + response.data.errors}`,
              color: ` ${response.data.state === 'save' ? 'success' : 'error'}`
            })
            this.$refs.fileAdd.reset()
          }).catch(e => {
            this.$refs.fileAdd.reset()
            this.dialogInicioTransaccion = false
            this.$store.commit('SNACK_SHOW', {msg: 'Error en el servidor ' + e.response.data.message, color: 'error'})
          })
      },
      cargarArchivoCsvCie10 () {
        this.dialogArchivo = false
        this.dialogInicioTransaccion = true
        // Initialize the form data
        let formData = new FormData()
        formData.append('cie10Csv', this.archivo)
        this.axios.post('importar-cie10-desde-csv', formData)
          .then(response => {
            this.dialogInicioTransaccion = false
            this.archivo = null
            this.$store.commit(SNACK_SHOW, {
              msg: `${response.data.state === 'save'
                ? 'Los archivos CIE10 se guardaron con éxito en la base de datos.'
                : 'Algo salio mal al guardar los CIE10, ' + response.data.errors}`,
              color: ` ${response.data.state === 'save' ? 'success' : 'error'}`
            })
            this.$refs.fileAdd.reset()
          }).catch(e => {
            this.$refs.fileAdd.reset()
            this.dialogInicioTransaccion = false
            this.$store.commit('SNACK_SHOW', {msg: 'Error en el servidor ' + e.response.data.message, color: 'error'})
          })
      },
      parseTipoEntidad (tipoEdad, edad) {
        var textConcat = ''
        if (tipoEdad === 'NA') {
          var isYear = edad.split('A')
          if (isYear.length > 1) {
            for (let i = 0; i < isYear.length; i++) {
              if (isYear[i] !== 'A') {
                textConcat = textConcat + isYear[i]
              }
            }
            edad = textConcat
            return edad
          }
          let isMonths = edad.split('M')
          if (isMonths.length > 1) {
            for (let i = 0; i < isMonths.length; i++) {
              if (isMonths[i] !== 'M') {
                textConcat = textConcat + isMonths[i]
              }
            }
            edad = textConcat
            return edad
          }
          var isDays = edad.split('D')
          if (isDays.length > 1) {
            for (let i = 0; i < isDays.length; i++) {
              if (isDays[i] !== 'D') {
                textConcat = textConcat + isDays[i]
              }
            }
            edad = textConcat
            return edad
          }
        }
        if (tipoEdad === 'D') {
          let isYear = edad.split('A')
          if (isYear.length > 1) {
            for (let i = 0; i < isYear.length; i++) {
              if (isYear[i] !== 'A') {
                textConcat = textConcat + isYear[i]
              }
            }
            edad = textConcat + 'D'
            return edad
          }
          let isMonths = edad.split('M')
          if (isMonths.length > 1) {
            for (let i = 0; i < isMonths.length; i++) {
              if (isMonths[i] !== 'M') {
                textConcat = textConcat + isMonths[i]
              }
            }
            edad = textConcat + 'D'
            return edad
          }
          let isDays = edad.split('D')
          if (isDays.length > 1) {
            for (let i = 0; i < isDays.length; i++) {
              if (isDays[i] !== 'D') {
                textConcat = textConcat + isDays[i]
              }
            }
            edad = textConcat + 'D'
            return edad
          }
          edad = edad + 'D'
          return edad
        }
        if (tipoEdad === 'A') {
          let isYear = edad.split('A')
          if (isYear.length > 1) {
            for (let i = 0; i < isYear.length; i++) {
              if (isYear[i] !== 'A') {
                textConcat = textConcat + isYear[i]
              }
            }
            edad = textConcat + 'A'
            return edad
          }
          var isMonths = edad.split('M')
          if (isMonths.length > 1) {
            for (let i = 0; i < isMonths.length; i++) {
              if (isMonths[i] !== 'M') {
                textConcat = textConcat + isMonths[i]
              }
            }
            edad = textConcat + 'A'
            return edad
          }
          let isDays = edad.split('D')
          if (isDays.length > 1) {
            for (let i = 0; i < isDays.length; i++) {
              if (isDays[i] !== 'D') {
                textConcat = textConcat + isDays[i]
              }
            }
            edad = textConcat + 'A'
            return edad
          }
          edad = edad + 'A'
          return edad
        }
        if (tipoEdad === 'M') {
          let isYear = edad.split('A')
          if (isYear.length > 1) {
            for (let i = 0; i < isYear.length; i++) {
              if (isYear[i] !== 'A') {
                textConcat = textConcat + isYear[i]
              }
            }
            edad = textConcat + 'M'
            return edad
          }
          let isMonths = edad.split('M')
          if (isMonths.length > 1) {
            for (let i = 0; i < isMonths.length; i++) {
              if (isMonths[i] !== 'M') {
                textConcat = textConcat + isMonths[i]
              }
            }
            edad = textConcat + 'M'
            return edad
          }
          let isDays = edad.split('D')
          if (isDays.length > 1) {
            for (let i = 0; i < isDays.length; i++) {
              if (isDays[i] !== 'D') {
                textConcat = textConcat + isDays[i]
              }
            }
            edad = textConcat + 'M'
            return edad
          }
          edad = edad + 'M'
          return edad
        }
      },
      // tipoEdadVerMinCambio () {
      //   this.cie10Ver.edad_min = this.parseTipoEntidad(this.tipoEdadVerMin, this.cie10Ver.edad_min)
      // },
      // tipoEdadVerMaxCambio () {
      //   this.cie10Ver.edad_max = this.parseTipoEntidad(this.tipoEdadVerMax, this.cie10Ver.edad_max)
      // },
      tipoEdadNewMinCambio () {
        this.cie10.edad_min = this.parseTipoEntidad(this.tipoEdadNewMin, this.cie10.edad_min)
      },
      tipoEdadNewMaxCambio () {
        this.cie10.edad_max = this.parseTipoEntidad(this.tipoEdadNewMax, this.cie10.edad_max)
      }
    },
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      },
      'cie10Ver.codigo' (codigo) {
        this.cie10Ver.codigo_tres = ''
        var splited = codigo.split('')
        for (var i = 0; i < splited.length; i++) {
          if (i <= 2) {
            this.cie10Ver.codigo_tres = this.cie10Ver.codigo_tres + splited[i]
          }
        }
      },
      'cie10.codigo' (codigo) {
        this.cie10.codigo_tres = ''
        var splited = codigo.split('')
        for (var i = 0; i < splited.length; i++) {
          if (i <= 2) {
            this.cie10.codigo_tres = this.cie10.codigo_tres + splited[i]
          }
        }
      },
      'cie10Ver.edad_min' (edad) {
        var isYear = edad.split('A')
        if (isYear.length > 1) { this.tipoEdadMin = 'A'; return }
        var isMonths = edad.split('M')
        if (isMonths.length > 1) { this.tipoEdadMin = 'M'; return }
        var isDays = edad.split('D')
        if (isDays.length > 1) { this.tipoEdadMin = 'D'; return }
        this.tipoEdadMin = 'NA'
      },
      'dialogNuevo' (val) {
        if (val === false) {
          this.close()
        }
      }
    },
    created () {
      this.reloadPage()
    }
  }
</script>

<style scoped>

</style>
