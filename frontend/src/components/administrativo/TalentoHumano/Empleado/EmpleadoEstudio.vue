<template>  
  <div>
    <!-- inicio formulario modal 1 estudios-->
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar estudios dsdf</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-text-field v-model="educacionH.establecimiento"
                              label="Establecimiento educativo" key="establecimiento educativo"
                              name="establecimiento educativo" v-validate="'required'" required
                              :error-messages="errors.collect('establecimiento educativo')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="educacionH.titulo"
                              label="Título obtenido" key="título obtenido"
                              name="título obtenido" v-validate="'required'" required
                              :error-messages="errors.collect('título obtenido')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-menu
                  ref="menuDateFechaEducacion"
                  :close-on-content-click="false"
                  v-model="menuDateFechaEducacion"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    ref="prueba"
                    slot="activator"
                    v-model="computedDateFormattedFechaEducacion"
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="educacionH.fecha"
                    @input="menuDateFechaEducacion = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>             
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario 1-->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveEducacionCH" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <!-- <v-card >
      <v-card-text> -->
        <v-toolbar v-show="!mostrarEducacionM" dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Estudios</v-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="mostrarCardEd(1,'educacion')">
            <v-icon title="mostrar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
      <!-- </v-card-text>
    </v-card> -->
    <v-card flat v-show="mostrarEducacionM">
      <v-card-text>
        <v-toolbar dense short flat>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-title>Estudios</v-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon @click="dialog = true">add</v-icon>
          </v-btn>
          <v-btn icon @click="mostrarCardEd(2,'educacion')">
            <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
          </v-btn>
        </v-toolbar>
        <v-data-table
          :headers="headersEducacion"
          :items="dataEducacion.estudio"
          :loading="tableLoading"
          :total-items="dataEducacion.length"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.establecimiento}}</td>
            <td>{{ props.item.titulo}}</td>
            <td>{{ props.item.fecha}}</td>
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
                    @click="eliminarEducacion(props.item)"
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
                    @click="editarEducacion(props.item)"
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
        headersEducacion: [
          {
            text: 'Establecimiento educativo',
            align: 'left',
            sortable: false,
            value: 'establecimiento educativo'
          },
          {
            text: 'Título obtenido',
            align: 'left',
            sortable: false,
            value: 'titulo obtenido'
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
        ],
        tabs: null,
        menuDateFechaEducacion: false,
        dataEducacion: {
          estudio: []
        },
        mostrarEducacionM: true,
        educacionH: {}
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
      computedDateFormattedFechaEducacion () {
        return this.formDate(this.educacionH.fecha)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('empleadosestudio?id=' + this.empleado)
          .then((response) => {
            this.dataEducacion.estudio = response.data
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
        if (value === 1 && strg === 'educacion') {
          this.mostrarEducacionM = true
        } else if (value === 2 && strg === 'educacion') {
          this.mostrarEducacionM = false
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async saveEducacionCH () {
        if (await this.validador('formPago')) {
          this.educacionH.empleado = this.empleado
          console.log(this.educacionH)
          this.localLoading = true
          var send = !this.educacionH.empleado_estudio ? this.axios.post('empleadosestudio/crear', this.educacionH) : this.axios.put('empleadosestudio/actualizar/' + this.educacionH.empleado_estudio, this.educacionH)
          send.then(response => {
            this.localLoading = false
            if (this.educacionH.empleado_estudio) {
              this.$store.commit(SNACK_SHOW, {msg: 'El estudio se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Estudio agregado exitosamente', color: 'success'})
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
      eliminarEducacion (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item.empleado_estudio
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('empleadosestudio/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El estudio se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editarEducacion (item, index) {
        this.dialog = true
        this.educacionH = Object.assign({}, item)
        this.i = index
      },
      closeTable () {
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.educacionH = {
          establecimiento: null,
          titulo: null,
          fecha: null,
          empleado: null
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
