<template>
  <main>
    <v-card>
      <toolbar-list class="elevation-0 grey lighten-4" title="Entidades Pre-Registradas"/>
      <v-container fluid>
        <v-data-table
          :headers="headers"
          :items="entidades"
          :loading="tableLoading"
          hide-actions
          class="elevation-0">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.identificacion }}</td>
            <td>{{ props.item.razon_social }}</td>
            <td>{{ props.item.res_habilitacion }}</td>
            <td>{{ props.item.cod_habilitacion }}</td>
            <td v-if="props.item.existe_entidades=='Existe'">
              <v-tooltip top>
                <v-btn outline color="success" slot="activator">
                  Valido
                </v-btn>
                <span>El código de la entidad se encuentra en el registro de prestadores</span>
              </v-tooltip>
            </td>
            <td v-else>
              <v-tooltip top>
                <v-btn outline color="danger" slot="activator">
                  No valido
                </v-btn>
                <span>El código de la entidad NO se encuentra en la base de datos de prestadores</span>
              </v-tooltip>
            </td>
            <td>{{ props.item.rs_tipentidade.tipo }}</td>
            <td>
              <v-tooltip top>
                <v-btn icon class="mx-0"
                       slot="activator"
                       dark
                       @click="habilitarEnt(props)">
                  <v-icon color="green">done_all</v-icon>
                </v-btn>
                <span>Habilitar registro</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn icon class="mx-0"
                       slot="activator"
                       dark
                       @click="eliminarEnt(props)">
                  <v-icon color="red">delete_forever</v-icon>
                </v-btn>
                <span>Eliminar</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn icon class="mx-0"
                       slot="activator"
                       dark
                       @click="masinfo(props)">
                  <v-icon color="cyan">description</v-icon>
                </v-btn>
                <span>Mas detalles</span>
              </v-tooltip>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
              Lo sentimos, no tenemos registros para mostrar. <v-icon color="white">sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
    <v-dialog
      v-model="dialogInfo"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Informacion completa
        </v-card-title>
        <v-card-text>
          <p>
            <strong>Tipo documento:</strong> {{ informacionEntidad.item.gn_tipodocidentidad.abreviatura }} <br>
            <strong>N° de identificación:</strong> {{ informacionEntidad.item.identificacion }} <br>
            <strong>Municipio de expedición:</strong> {{ informacionEntidad.item.gn_municipio_expedicion.nombre }} - {{ informacionEntidad.item.gn_municipio_expedicion.departamento.nombre }} <br>
            <strong>Primer nombre:</strong> {{ informacionEntidad.item.nombre1 }} <br>
            <strong>Segundo nombre:</strong> {{ informacionEntidad.item.nombre2 }} <br>
            <strong>Primer apellido:</strong> {{ informacionEntidad.item.apellido1 }} <br>
            <strong>Segundo apellido:</strong> {{ informacionEntidad.item.apellido2 }} <br>
            <strong>Razón social:</strong> {{ informacionEntidad.item.razon_social }} <br>
            <strong>Dirección:</strong> {{ informacionEntidad.item.direccion }} <br>
            <strong>Teléfono fijo:</strong> {{ informacionEntidad.item.telefono_fijo }} <br>
            <strong>Celular:</strong> {{ informacionEntidad.item.celular }} <br>
            <strong>Correo electrónico:</strong> {{ informacionEntidad.item.correo_electronico }} <br>
            <strong>Municipio de residencia:</strong> {{ informacionEntidad.item.gn_municipio_residencia.nombre }} - {{ informacionEntidad.item.gn_municipio_residencia.departamento.nombre }} <br>
            <strong>Tipo de retención:</strong> {{ informacionEntidad.item.tipo_retencion }} <br>
            <strong>Tipo de contribuyente:</strong> {{ informacionEntidad.item.tipo_contribuyente }} <br>
            <strong>Tipo de persona:</strong> {{ informacionEntidad.item.tipo_persona }} <br>
            <strong>Ica:</strong> {{ informacionEntidad.item.ica }} <br>
            <strong>% Ica:</strong> {{ informacionEntidad.item.porcentaje_ica }} <br>
            <strong>Autorretenedor:</strong> {{ informacionEntidad.item.autorretenedor }} <br>
            <strong>Agente declarante:</strong> {{ informacionEntidad.item.agente_declarante }} <br>
            <strong>Actividad económica:</strong> {{ informacionEntidad.item.nf_ciiu.nombre }} <br>
            <strong>Tipo de entidad:</strong> {{ informacionEntidad.item.rs_tipentidade.tipo }} <br>
            <strong>Resolución de habilitación:</strong> {{ informacionEntidad.item.res_habilitacion }} <br>
            <strong>Código de habilitación:</strong> {{ informacionEntidad.item.cod_habilitacion }} <br>
            <strong>Correo electrónico sede:</strong> {{ informacionEntidad.item.correo_electronico_sede }} <br>
            <strong>Teléfono Principal o sede:</strong> {{ informacionEntidad.item.telefono_sede }} <br>
            <strong>Dirección Principal o sede:</strong> {{ informacionEntidad.item.direccion_sede }} <br>
            <strong>Municipio Principal o sede:</strong> {{ informacionEntidad.item.gn_municipio_sede.nombre }} - {{ informacionEntidad.item.gn_municipio_sede.departamento.nombre }}  <br>
            <strong>Tipo de locación:</strong> {{ informacionEntidad.item.tipo_locacion }} <br>
            <strong>Gerente:</strong> {{ informacionEntidad.item.gerente }} <br>
            <strong>Representante legal:</strong> {{ informacionEntidad.item.replegal }} <br>
            <strong>Naturaleza:</strong> {{ informacionEntidad.item.naturaleza }} <br>
            <strong>Complejidad:</strong> {{ informacionEntidad.item.complejidad }} <br>
          </p>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            @click="dialogInfo = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogHabilitar" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">¿Habilitar entidad?</v-card-title>
        <v-card-text>La entidad se habilitara para que pueda ingresar al sistema y pueda manejar el proceso de Validador RIPS</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="siHabilitarEntidad">Habilitar</v-btn>
          <v-btn color="red darken-1" flat @click.native="dialogHabilitar = false">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogEliminar" persistent max-width="500px">
      <v-card>
        <v-card-title class="headline">¿Eliminar entidad?</v-card-title>
        <v-card-text>
          El pre-registro de la entidad se eliminara y no se le concederá acceso al sistema. <br>
          Digite un motivo de eliminación para su notificación por correo
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12 sm12 md12>
                <v-textarea
                  name="input-7-1"
                  label="Motivo"
                  v-model="motivoEliminacion"
                  :rules="rulesMotivo"
                  hint="Motivo de eliminacion"
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" flat @click.native="eliminarEntidad">Eliminar</v-btn>
          <v-btn color="grey darken-1" flat @click.native="dialogEliminar = false">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </main>
</template>

<script>
  import ToolbarList from '@/components/general/ToolbarList'
  export default {
    name: 'HabilitarEntidad',
    components: {
      ToolbarList
    },
    data () {
      return {
        headers: [
          {
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Nombre', value: 'razon_social' },
          { text: 'Resolución', value: 'res_habilitacion' },
          { text: 'Código de habilitación', value: 'cod_habilitacion' },
          { text: 'Código valido', value: 'existe_entidades' },
          { text: 'Tipo', value: 'rs_tipentidade.tipo' },
          { text: 'Opciones', value: 'id' }
        ],
        entidades: [],
        tableLoading: false,
        dialogInfo: false,
        dialogHabilitar: false,
        dialogEliminar: false,
        entidadHabilitar: null,
        motivoEliminacion: '',
        rulesMotivo: [
          v => !!v || 'El motivo de eliminación es obligatorio'
        ],
        informacionEntidad: {
          item: {
            gn_tipodocidentidad: {
              abreviatura: ''
            },
            identificacion: '',
            gn_municipio_expedicion: {
              nombre: '',
              departamento: {
                nombre: ''
              }
            },
            nombre1: '',
            nombre2: '',
            apellido1: '',
            apellido2: '',
            razon_social: '',
            direccion: '',
            telefono_fijo: '',
            celular: '',
            correo_electronico: '',
            gn_municipio_residencia: {
              nombre: '',
              departamento: {
                nombre: ''
              }
            },
            tipo_retencion: '',
            tipo_contribuyente: '',
            tipo_persona: '',
            ica: '',
            porcentaje_ica: '',
            autorretenedor: '',
            agente_declarante: '',
            nf_ciiu: {
              nombre: ''
            },
            rs_tipentidade: {
              tipo: ''
            },
            res_habilitacion: '',
            cod_habilitacion: '',
            correo_electronico_sede: '',
            telefono_sede: '',
            direccion_sede: '',
            gn_municipio_sede: {
              nombre: '',
              departamento: {
                nombre: ''
              }
            },
            tipo_locacion: '',
            gerente: '',
            replegal: '',
            naturaleza: '',
            complejidad: ''
          }
        }
      }
    },
    created () {
      this.allEntidadesTemporales()
    },
    methods: {
      allEntidadesTemporales () {
        this.tableLoading = true
        this.axios.get('rs_entidades_temporales')
          .then(response => {
            this.entidades = response.data.entidades
            this.tableLoading = false
          }).catch(e => {
            this.$store.commit('SNACK_SHOW', {msg: e.response.message, color: 'error'})
          })
      },
      masinfo (data) {
        this.informacionEntidad = data
        this.dialogInfo = true
      },
      habilitarEnt (data) {
        this.dialogHabilitar = true
        this.entidadHabilitar = data
      },
      siHabilitarEntidad () {
        if (this.entidadHabilitar === null) {
        }
        this.axios.post('rs_entidades_temporales', this.entidadHabilitar.item)
          .then(response => {
            this.allEntidadesTemporales()
            if (response.data.state === 'save') {
              this.$store.commit('SNACK_SHOW', {msg: 'Entidad habilitada correctamente', color: 'success'})
              this.dialogHabilitar = false
            } else {
              if (response.data.state === 'error') {
                this.$store.commit('SNACK_SHOW', {msg: 'Error: ' + response.data.message, color: 'error'})
                this.dialogHabilitar = false
              } else {
                this.$store.commit('SNACK_SHOW', {msg: 'No es posible habilitar la entidad, inténtelo de nuevo', color: 'error'})
                this.dialogHabilitar = false
              }
            }
          }).catch(e => {
            this.$store.commit('SNACK_SHOW', {msg: 'No es posible habilitar la entidad, inténtelo de nuevo ' + e.response.data.message, color: 'error'})
          })
      },
      eliminarEnt (data) {
        this.entidadHabilitar = data
        this.dialogEliminar = true
      },
      eliminarEntidad () {
        if (this.motivoEliminacion.trim().length === 0) {
          this.$store.commit('SNACK_SHOW', {msg: 'Digite el motivo de eliminación.', color: 'error'})
          return
        }
        this.axios.delete('rs_entidades_temporales', {id: this.entidadHabilitar.item.id, motivo: this.motivoEliminacion})
          .then(response => {
            this.allEntidadesTemporales()
            if (response.data.state === 'delete') {
              this.entidades.splice((this.entidadHabilitar.index + 1), 1)
              this.dialogEliminar = false
              this.$store.commit('SNACK_SHOW', {msg: 'Entidad eliminada correctamente', color: 'success'})
            } else {
              if (response.data.state === 'deleteNoEmail') {
                this.$store.commit('SNACK_SHOW', {msg: 'La entidad se elimino con éxito. Pero no se pudo enviar la notificación al correo', color: 'error'})
              } else {
                this.$store.commit('SNACK_SHOW', {msg: 'No es posible eliminar el pre-registro de la entidad, inténtelo de nuevo', color: 'error'})
              }
            }
          }).catch(e => {
            this.$store.commit('SNACK_SHOW', {msg: 'Error al eliminar el pre-registro ' + e.response.data.message, color: 'error'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
