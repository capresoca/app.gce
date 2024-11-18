<template>
  <div>
    <v-bottom-nav
      mandatory
      fixed
      dark
      :color="errorEstado ? 'error' : 'blue'"
      :value="value.length"
    >
      <v-list-tile class="white--text">
        <v-list-tile-content>
          <v-list-tile-title class="title">Cuentas seleccionadas: <strong>{{value.length}}</strong></v-list-tile-title>
          <v-list-tile-title v-if="value.length">estado: {{errorEstado || value[0].estadolote.estado}}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <template v-if="!errorEstado">
        <template v-for="(accion, indexaccion) in accionesDisponibles">
          <v-divider vertical></v-divider>
          <v-btn
            :key="'btn' + indexaccion"
            dark
            @click.stop="confirmar(accion)"
          >
            <span :class="accion.color + '--text'">{{accion.title}}</span>
            <v-icon :color="accion.color">{{accion.icon}}</v-icon>
          </v-btn>
        </template>
      </template>
    </v-bottom-nav>
    <v-dialog v-model="dialog.show" persistent max-width="320">
      <v-card v-if="dialog.accion">
        <v-card-title class="headline align-center">{{dialog.accion.text}}</v-card-title>
        <v-card-text>
          <v-textarea
            label="Observaciones"
            v-model="dialog.observaciones"
            auto-grow
            rows="1">
          </v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="dialog.show = false">Cancelar</v-btn>
          <v-btn
            color="green darken-1"
            flat
            @click.stop="enviar(dialog.accion.data)"
          >
            Aceptar
          </v-btn>
        </v-card-actions>
        <loading v-model="loading"></loading>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  export default {
    name: 'EnvioCuentas',
    props: {
      value: {
        type: Array,
        default: []
      }
    },
    data: () => ({
      loading: false,
      dialog: {
        show: false,
        accion: null,
        observaciones: null
      }
    }),
    watch: {
      'dialog.show' (val) {
        if (!val) {
          setTimeout(() => {
            this.resetDialog()
          }, 400)
        }
      }
    },
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      accesoAuditoria () {
        return !!(this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 41 || x.id === 48 || x.id === 39))
      },
      accesoContabilidad () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 24 || x.id === 25)
      },
      accesoCxP () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 24 || x.id === 25)
      },
      accesoTesoreria () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 22 || x.id === 23)
      },
      errorEstado () {
        if (this && this.value && this && this.value.length) {
          let registros = this.lodash.uniqBy(this.value, 'estadolote.estado')
          if (registros.length === 1) {
            return this.lodash.uniq(this.value.filter(x => x.estadolote.estado === registros[0].estadolote.estado).map(z => z.estadolote.aceptado)).length === 1
              ? ''
              : 'El estado debe ser el mismo para los items seleccionados'
          } else {
            return 'El estado debe ser el mismo para los items seleccionados'
          }
        }
        return ''
      },
      accionesDisponibles () {
        if (this && this.value && this.value.length) {
          let acciones = []
          switch (this.value[0].estadolote.estado) {
            case 'Radicado': {
              if (this.accesoAuditoria) {
                acciones.push({
                  color: 'error',
                  title: 'Devolver',
                  icon: 'fas fa-backward',
                  text: '¿Esta seguro de hacer la devolución de las cuentas seleccionadas?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 7,
                    aceptado: 1
                  }
                })
              }
              break
            }
            case 'Auditoria': {
              if (this.accesoAuditoria) {
                acciones.push({
                  color: 'white',
                  title: 'Enviar a contabilidad',
                  icon: 'fas fa-paper-plane',
                  text: '¿Esta seguro de enviar las cuentas seleccionadas a contabilidad?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 3,
                    aceptado: 3
                  }
                })
              }
              break
            }
            case 'Contabilidad': {
              if (this.accesoContabilidad && this.value[0].estadolote.aceptado !== '1') {
                acciones.push({
                  color: 'error',
                  title: 'Devolver a auditoría',
                  icon: 'fas fa-arrow-circle-left',
                  text: '¿Esta seguro de hacer la devolución de las cuentas seleccionadas a Auditoría?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 2,
                    aceptado: 1
                  }
                })
                acciones.push({
                  color: 'white',
                  title: 'Aceptar en contabilidad',
                  icon: 'fas fa-thumbs-up',
                  text: '¿Esta seguro de aceptar las cuentas seleccionadas en contabilidad?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 3,
                    aceptado: 1
                  }
                })
              } else if (this.value[0].estadolote.aceptado === '1') {
                acciones.push({
                  color: 'white',
                  title: 'Enviar a CxP',
                  icon: 'fas fa-paper-plane',
                  text: '¿Esta seguro de enviar las cuentas seleccionadas a cuentas por pagar?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 8,
                    aceptado: 3
                  }
                })
              }
              break
            }
            case 'CXP': {
              if (this.accesoCxP && this.value[0].estadolote.aceptado !== '1') {
                acciones.push({
                  color: 'error',
                  title: 'Devolver a contabilidad',
                  icon: 'fas fa-arrow-circle-left',
                  text: '¿Esta seguro de hacer la devolución de las cuentas seleccionadas a Contabilidad?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 3,
                    aceptado: 1
                  }
                })
                acciones.push({
                  color: 'white',
                  title: 'Aceptar en CxP',
                  icon: 'fas fa-thumbs-up',
                  text: '¿Esta seguro de aceptar las cuentas seleccionadas en cuentas por pagar?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 8,
                    aceptado: 1
                  }
                })
              } else if (this.value[0].estadolote.aceptado === '1') {
                acciones.push({
                  color: 'white',
                  title: 'Enviar a tesorería',
                  icon: 'fas fa-paper-plane',
                  text: '¿Esta seguro de enviar las cuentas seleccionadas a tesorería?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 4,
                    aceptado: 3
                  }
                })
              }
              break
            }
            case 'Tesoreria': {
              if (this.accesoTesoreria && this.value[0].estadolote.aceptado !== '1') {
                acciones.push({
                  color: 'error',
                  title: 'Devolver a CxP',
                  icon: 'fas fa-arrow-circle-left',
                  text: '¿Esta seguro de hacer la devolución de las cuentas seleccionadas a cuentas por pagar?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 8,
                    aceptado: 1
                  }
                })
                acciones.push({
                  color: 'white',
                  title: 'Aceptar en tesorería',
                  icon: 'fas fa-thumbs-up',
                  text: '¿Esta seguro de aceptar las cuentas seleccionadas en tesorería?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 4,
                    aceptado: 1
                  }
                })
              } else if (this.value[0].estadolote.aceptado === '1') {
                acciones.push({
                  color: 'white',
                  title: 'Pagado',
                  icon: 'fas fa-file-invoice-dollar',
                  text: '¿Esta seguro de marcar las cuentas seleccionadas como pagas?',
                  data: {
                    ids: [],
                    descripcion: null,
                    cm_estadosrad_id: 5,
                    aceptado: 1
                  }
                })
              }
              break
            }
          }

          return acciones
        } else {
          return []
        }
      }
    },
    methods: {
      confirmar (accion) {
        this.dialog.accion = accion
        this.dialog.show = true
      },
      enviar (data) {
        data.ids = this.value.map(x => x.id)
        data.descripcion = this.dialog.observaciones
        this.loading = true
        this.axios.post('cm_radcambios', data)
          .then(response => {
            this.$store.commit('reloadTable', 'tableCuentasMedicas')
            this.$emit('enviado')
            this.dialog.show = false
            this.loading = false
          }).catch(() => {
            this.loading = false
            this.$store.commit('SNACK_SHOW', {msg: 'Error al enviar los datos de las cuentas seleccionadas.', color: 'danger'})
          })
      },
      resetDialog () {
        this.dialog.show = false
        this.dialog.accion = null
        this.dialog.observaciones = null
      }
    }
  }
</script>

<style scoped>

</style>
