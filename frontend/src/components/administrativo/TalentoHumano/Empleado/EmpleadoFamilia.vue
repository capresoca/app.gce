<template>  
  <div>
    <!-- inicio formulario modal-->
    <v-dialog v-model="dialog" width="1000px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar familiar</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6 lg6>
              <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Parentesco"
                  :items="parentescos"
                  name="parentesco"
                  v-validate="'required'"
                  :error-messages="errors.collect('parentesco')"
                  v-model="familia.parentesco"
                ></v-select>                
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.grado"
                              label="Grado" key="grado"
                              name="grado" v-validate="'required|max:10|'" required readonly
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.nombre"
                              label="Nombres y apellidos" key="nombres y apellidos"
                              name="nombres y apellidos" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('nombres y apellidos')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.direccion"
                              label="Dirección" key="dirección de familiar"
                              name="dirección de familiar" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('dirección de familiar')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.telefono"
                              label="Teléfono" key="teléfono de familiar"
                              name="teléfono de familiar" v-validate="'max:50'"
                              :error-messages="errors.collect('teléfono de familiar')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-menu
                  ref="menuDateFechaFamiliar"
                  :close-on-content-click="false"
                  v-model="menuDateFechaFamiliar"
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
                    v-model="computedDateFormattedFechaFamiliar"
                    label="Fecha nacimiento"
                    prepend-icon="event"
                    readonly
                    name="fecha nacimiento de familiar"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha nacimiento de familiar')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="familia.fecha_nacimiento"
                    @input="menuDateFechaFamiliar = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha nacimiento')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>           
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario 2-->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveFamiliaCH">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <!-- <v-card >
      <v-card-text> -->
        <v-toolbar v-show="!mostrar" dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Grupo familiar</v-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="mostrarCardEd(1,'familia')">
            <v-icon title="mostrar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
      <!-- </v-card-text>
    </v-card> -->
    <v-card flat v-show="mostrar">
      <v-card-text>
        <v-toolbar dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Grupo familiar</v-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon @click="dialog = true">add</v-icon>
          </v-btn>
          <v-btn icon @click="mostrarCardEd(2,'familia')">
            <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
        <v-data-table
          :headers="headers"
          :items="dataFamilia.familia"
          :loading="tableLoading"
          :total-items="dataFamilia.length"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.parentesco }}</td>
            <td>{{ props.item.grado }}</td>
            <td>{{ props.item.nombre }}</td>
            <td>{{ props.item.direccion }}</td>
            <td>{{ props.item.telefono }}</td>
            <td>{{ props.item.fecha_nacimiento }}</td>
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
                    @click="eliminarFamilia(props.item)"
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
                    @click="editarFamilia(props.item)"
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
        headers: [
          {
            text: 'Parentesco',
            align: 'left',
            sortable: false,
            value: 'parentesco'
          },
          {
            text: 'Grado',
            align: 'left',
            sortable: false,
            value: 'grado'
          },
          {
            text: 'Nombres y apellidos',
            align: 'left',
            sortable: false,
            value: 'nombres'
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
            text: 'F.Nacimiento',
            align: 'left',
            sortable: false,
            value: 'fecha_nacimiento'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        tabs: null,
        menuDateFechaFamiliar: false,
        dataFamilia: {
          familia: []
        },
        mostrar: true,
        familia: {},
        parentescos: [
          {
            value: '1',
            nombre: 'Hijo'
          },
          {
            value: '2',
            nombre: 'Padre'
          },
          {
            value: '3',
            nombre: 'Hermano'
          },
          {
            value: '4',
            nombre: 'Abuelo'
          },
          {
            value: '5',
            nombre: 'Primo'
          },
          {
            value: '6',
            nombre: 'Otros'
          }
        ]
      }
    },
    mounted () {
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'familia.parentesco' () {
        if (this.familia.parentesco === 'Padre' || this.familia.parentesco === 'Hijo') {
          this.familia.grado = 'Primero'
        } else if (this.familia.parentesco === 'Hermano' || this.familia.parentesco === 'Abuelo') {
          this.familia.grado = 'Segundo'
        } else if (this.familia.parentesco === 'Primo') {
          this.familia.grado = 'Cuarto'
        } else if (this.familia.parentesco === 'Otros') {
          this.familia.grado = 'Tercero'
        }
      }
    },
    computed: {
      computedDateFormattedFechaFamiliar () {
        return this.formDate(this.familia.fecha_nacimiento)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('empleadosfamilia?id=' + this.empleado)
          .then((response) => {
            this.dataFamilia.familia = response.data
            this.tableLoading = false
            this.localLoading = false
            for (let i = 0; i < this.dataFamilia.familia.length; i++) {
              for (let j = 0; j < this.parentescos.length; j++) {
                const array = this.parentescos[j]
                if (this.dataFamilia.familia[i].parentesco.toString() === array.value) this.dataFamilia.familia[i].parentesco = array.nombre
              }
            }
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      mostrarCardEd (value, strg) {
        if (value === 1 && strg === 'familia') {
          this.mostrar = true
        } else if (value === 2 && strg === 'familia') {
          this.mostrar = false
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async saveFamiliaCH () {
        if (await this.validador('formPago')) {
          this.familia.empleado = this.empleado
          console.log(this.familia)
          this.localLoading = true
          var send = !this.familia.empleado_familia ? this.axios.post('empleadosfamilia/crear', this.familia) : this.axios.put('empleadosfamilia/actualizar/' + this.familia.empleado_familia, this.familia)
          send.then(response => {
            this.localLoading = false
            if (this.familia.empleado_familia) {
              this.$store.commit(SNACK_SHOW, {msg: 'El familiar se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Familiar agregado exitosamente', color: 'success'})
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
      eliminarFamilia (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item.empleado_familia
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('empleadosfamilia/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El familiar se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editarFamilia (item, index) {
        this.dialog = true
        this.familia = Object.assign({}, item)
        this.i = index
      },
      closeTable () {
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.familia = {
          parentesco: null,
          grado: null,
          nombre: null,
          direccion: null,
          telefono: null,
          fecha_nacimiento: null
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
