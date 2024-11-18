<template>  
  <div>
    <!-- inicio formulario modal -->
    <v-dialog v-model="dialog" width="1000px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar experiencia laboral</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.empresa"
                              label="Empresa" key="empresa"
                              name="empresa" v-validate="'required'" required
                              :error-messages="errors.collect('empresa')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.cargo"
                              label="Cargo" key="cargo"
                              name="cargo" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('cargo')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
               <v-menu
                  ref="menuDateFechaIngreso"
                  :close-on-content-click="false"
                  v-model="menuDateFechaIngreso"
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
                    v-model="computedDateFormattedFechaIngreso"
                    label="Fecha ingreso"
                    prepend-icon="event"
                    readonly
                    name="fecha ingreso"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha ingreso')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="experienciaLaboral.fecha_ingreso"
                    @input="menuDateFechaIngreso = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha ingreso')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>  
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-menu
                  ref="menuDateFechaRetiro"
                  :close-on-content-click="false"
                  v-model="menuDateFechaRetiro"
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
                    v-model="computedDateFormattedFechaRetiro"
                    label="Fecha retiro"
                    prepend-icon="event"
                    readonly
                    name="fecha retiro"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha retiro')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="experienciaLaboral.fecha_retiro"
                    @input="menuDateFechaRetiro = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha retiro')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>               
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.direccion"
                              label="Dirección" key="dirección de empresa"
                              name="dirección de empresa" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('dirección de empresa')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.telefono"
                              label="Teléfono" key="teléfono de empresa"
                              name="teléfono de empresa" v-validate="'required|max:50|'" required
                              :error-messages="errors.collect('teléfono de empresa')"></v-text-field>                 
              </v-flex>


            </v-layout>
          </v-container>
          <!-- fin formulario -->

          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveExperiencia" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <!-- <v-card >
      <v-card-text> -->
        <v-toolbar v-show="!mostrar" dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Experiencia laboral</v-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="mostrarCardEd(1,'laboral')">
            <v-icon title="mostrar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
      <!-- </v-card-text>
    </v-card> -->
    <v-card flat v-show="mostrar">
      <v-card-text>
        <v-toolbar dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Experiencia laboral</v-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon @click="dialog = true">add</v-icon>
          </v-btn>
          <v-btn icon @click="mostrarCardEd(2,'laboral')">
            <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
        <v-data-table
          :headers="headersInfoLaboral"
          :items="dataExperienciaLaboral.laboral"
          :loading="tableLoading"
          :total-items="dataExperienciaLaboral.length"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.empresa}}</td>
            <td>{{ props.item.cargo}}</td>
            <td>{{ props.item.fecha_ingreso}}</td>
            <td>{{ props.item.fecha_retiro}}</td>
            <td>{{ props.item.direccion}}</td>
            <td>{{ props.item.telefono }}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                direction="left"
                open-on-hover
                transition="slide-x-transition"
              >
                <v-btn
                  slot="activator"
                  v-model="props.item.show"
                  color="blue darken-2"
                  dark
                  fab
                  small
                >
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="eliminarLaboral(props.item)"
                  >
                    <v-icon color="accent">delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="editarLaboral(props.item)"
                  >
                    <v-icon color="accent">mode_edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
              No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Confirmar from '@/components/general/Confirmar2'
  export default {
    name: 'estudio',
    props: [
      'empleado'
    ],
    components: {
      Confirmar
    },
    data () {
      return {
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dialog: false,
        tableLoading: false,
        headersInfoLaboral: [
          {
            text: 'Empresa',
            align: 'left',
            sortable: false,
            value: 'empresa'
          },
          {
            text: 'Cargo',
            align: 'left',
            sortable: false,
            value: 'cargo'
          },
          {
            text: 'F.Ingreso',
            align: 'left',
            sortable: false,
            value: 'fecha_ingreso'
          },
          {
            text: 'F.Retiro',
            align: 'left',
            sortable: false,
            value: 'fecha_retiro'
          },
          {
            text: 'Dirección',
            align: 'left',
            sortable: false,
            value: 'direccion'
          },
          {
            text: 'Teléfono',
            align: 'left',
            sortable: false,
            value: 'telefono'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        tabs: null,
        menuDateFechaIngreso: false,
        menuDateFechaRetiro: false,
        dataExperienciaLaboral: {
          laboral: []
        },
        mostrar: true,
        experienciaLaboral: {}
      }
    },
    mounted () {
    },
    created () {
      this.reloadPage()
    },
    watch: {
    },
    filers: {
    },
    computed: {
      computedDateFormattedFechaIngreso () {
        return this.formDate(this.experienciaLaboral.fecha_ingreso)
      },
      computedDateFormattedFechaRetiro () {
        return this.formDate(this.experienciaLaboral.fecha_retiro)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('empleadoslaboral?id=' + this.empleado)
          .then((response) => {
            this.dataExperienciaLaboral.laboral = response.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      mostrarCardEd (value, strg) {
        if (value === 1 && strg === 'laboral') {
          this.mostrar = true
        } else if (value === 2 && strg === 'laboral') {
          this.mostrar = false
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async saveExperiencia () {
        if (await this.validador('formPago')) {
          this.experienciaLaboral.empleado = this.empleado
          console.log(this.experienciaLaboral)
          this.localLoading = true
          var send = !this.experienciaLaboral.empleado_laboral ? this.axios.post('empleadoslaboral/crear', this.experienciaLaboral) : this.axios.put('empleadoslaboral/actualizar/' + this.experienciaLaboral.empleado_laboral, this.experienciaLaboral)
          send.then(response => {
            this.localLoading = false
            if (this.experienciaLaboral.empleado_laboral) {
              this.$store.commit(SNACK_SHOW, {msg: 'La experiencia laboral se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Experiencia laboral agregada exitosamente', color: 'success'})
              this.reloadPage()
            }
            this.dialog = false
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
        this.formTableReset()
      },
      eliminarLaboral (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item.empleado_laboral
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('empleadoslaboral/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'La experiencia laboral se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editarLaboral (item, index) {
        this.dialog = true
        this.experienciaLaboral = Object.assign({}, item)
        this.i = index
      },
      closeTable () {
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.experienciaLaboral = {
          empresa: null,
          cargo: null,
          fecha_ingreso: null,
          fecha_retiro: null,
          direccion: null,
          telefono: null
        }
        this.dialog = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
</style>
