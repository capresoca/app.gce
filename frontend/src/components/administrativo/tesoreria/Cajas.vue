<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formCajas">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm6>
                <v-text-field v-model="caja.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>


              </v-flex>
              <v-flex xs12 sm6>
                <v-text-field v-model="caja.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-select
                  :items="tiposCaja"
                  v-model="caja.tipo"
                  name="tipo de caja"
                  label="Tipo"
                  :error-messages="errors.collect('tipo de caja')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 sm6>
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
                  <!--:return-value.sync="date"-->
                  <v-text-field
                    slot="activator"
                    v-model="computedDateFormatted"
                    label="Fecha Apertura"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="caja.fecha_apertura"
                    @input="menuDate = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                    :max="maxDate"
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field v-model="caja.saldo_inicial"
                              label="Saldo Inicial" key="saldoInicial"
                              name="saldoInicial" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('saldoInicial')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                    <postulador
                      nodata="Busqueda por código o nombre."
                      required
                      hint="codigo"
                      itemtext="nombre"
                      datatitle="codigo"
                      datasubtitle="nombre"
                      filter="codigo,nombre"
                      label="cuenta auxiliar"
                      scope="formDetalle"
                      ref="buscarCuenta"
                      entidad="niifs"
                      routeparams="nivel:auxiliar=1"
                      preicon="person"
                      btnplustruncate
                      @click="openNuevaNiif()"
                      @change="val => caja.nf_niif_id = val"
                      @objectReturn="val => caja.niif = val"
                      :value="caja.niif"
                      clearable
                    ></postulador>
                  </v-flex>


              <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>

              <v-flex xs12 sm6>
                <v-autocomplete
                  label="Centro de Costo"
                  v-model="caja.cencosto"
                  @change="val => caja.nf_cencosto_id = val ? val.id : null"
                  :items="complementos.cencostos"
                  item-value="id"
                  item-text="nombre"
                  name="centro de costo"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('centro de costo')"
                  return-object
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm6>
                <v-subheader class="pa-0 ma-0">Estado</v-subheader>
                <v-switch class="ma-0 pa-0"  color="accent" :label="caja.estado"
                           v-model="caja.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Cajas" subtitle="Registro y gestión" btnplus btnplustitle="Crear Caja" btnplustruncate @click="dialog = true"/>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line=""
          hide-details
          flat
        >
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="cajas"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.tipo}}</td>
          <td>{{ props.item.estado}}</td>
          <td>{{ props.item.fecha_apertura}}</td>
          <td>{{ props.item.saldo_inicial}}</td>
          <td>{{ props.item.niif.nombre }}</td>
          <td>{{ props.item.cencosto.nombre }}</td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn icon class="mx-0" slot="activator" @click="edit(props.item)">
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
          </td>

        </template>
        <template slot="no-data">
          <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
        <template slot="pageText" slot-scope="props">
          {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se encontraron resultados de "{{ search }}"
        </v-alert>
      </v-data-table>
      <v-card-actions class="text-xs-center pt-2 d-block">
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../store/complementos/index'

  export default {
    name: 'cajas',
    components: {
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        cajas: [],
        caja: {},
        search: '',
        dialog: false,
        tableLoading: false,
        pagination: {},
        tiposCaja: ['Mayor', 'Menor'],
        menuDate: false,
        maxDate: this.moment().format('YYYY-MM-DD'),
        codigosUsados: '',
        nombresUsados: '',
        dialogFormNiif: false,
        cuentaNiff: null,

        headers: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Fecha Apertura',
            align: 'left',
            sortable: false,
            value: 'fechaApertura'
          },
          {
            text: 'Saldo Inicial',
            align: 'left',
            sortable: false,
            value: 'saldoInicial'
          },
          {
            text: 'Cuenta NIIF',
            align: 'left',
            sortable: false,
            value: 'cuentaNIIF'
          },
          {
            text: 'Centro de Costos',
            align: 'left',
            sortable: false,
            value: 'centroDeCostos'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
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
      this.tableLoading = true
      this.axios.get('cajas')
        .then((res) => {
          this.cajas = res.data.data
          this.pagination.totalItems = this.cajas.length
          this.tableLoading = false
          this.codigosUsados = this.getCodigosUsados()
          this.nombresUsados = this.getNombresUsados()
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      complementos () {
        return store.getters.complementosFormComDiario
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('cajas')
      },
      modalTitulo () {
        return !this.caja.id ? 'Nueva Caja' : 'Edición de la caja: ' + this.caja.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      },
      computedDateFormatted () {
        return this.formDate(this.caja.fecha_apertura)
      }
    },
    methods: {
      formReset () {
        this.caja = {
          id: '',
          codigo: null,
          nombre: null,
          tipo: null,
          estado: 'Inactivo',
          fecha_apertura: null,
          saldo_inicial: null,
          nf_niif_id: null,
          nf_cencosto_id: null,
          niif: null,
          cencosto: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        this.dialog = true
        this.caja = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.cajas.map(cod => { if (!(this.caja.codigo != null && this.caja.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.cajas.map(nom => { if (!(this.caja.nombre != null && this.caja.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      openNuevaNiif () {
        this.dialogFormNiif = true
      },
      goNiif (val) {
        console.log('GoVal', val)
        this.caja.niif = val
        this.dialogFormNiif = false
      },
      async save () {
        if (await this.validador('formCajas')) {
          let send = !this.caja.id ? this.axios.post('cajas', this.caja) : this.axios.put('cajas/' + this.caja.id, this.caja)
          send.then(response => {
            if (this.caja.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La caja se actualizó correctamente', color: 'success'})
              this.cajas.splice(this.cajas.findIndex(caja => caja.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La caja se creó correctamente', color: 'success'})
              this.cajas.splice(0, 0, response.data.data)
            }
            this.dialog = false
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }

    }

  }
</script>

<style scoped>
  .v-subheader {
    height: 28px;
  }
</style>
