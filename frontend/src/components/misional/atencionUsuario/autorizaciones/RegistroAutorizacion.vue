<template>
  <v-card>
    <loading v-model="loading"></loading>
    <v-toolbar dense class="elevation-0">
      <v-icon>fas fa-tasks</v-icon>
      <v-toolbar-title>Registro autorización</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>
            Solicitud No.
            <v-chip small label color="primary" v-if="solicitud" class="font-weight-bold pa-0 ma-0">
              {{solicitud.id}}
            </v-chip>
          </v-list-tile-title>
          <v-list-tile-sub-title>
            Estado:
            <span :class="solicitud.ind === '3' ? 'success--text' : solicitud.ind === '1' ? 'info--text' : 'error--text'" v-if="solicitud" class="font-weight-bold">
            {{solicitud.ind === '3' ? 'Aprobada' : solicitud.ind === '1' ? 'Pendiente' : 'Anulada'}}
          </span>
          </v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-toolbar>
    <v-container v-if="solicitud" fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12>
          <v-textarea
            v-if="solicitud.anulada"
            error
            prepend-icon="fas fa-exclamation-triangle"
            label="Motivo de anulación"
            :value="solicitud.anulada.observacion"
            rows="1"
            auto-grow
            readonly
          />
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>person</v-icon>
                Datos del afiliado
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <dialog-red-servicios v-if="solicitud && solicitud.afiliado" :afiliado-id="solicitud.afiliado.id"></dialog-red-servicios>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <input-detail-flex
                  flex-class="xs12 sm7 md9"
                  label="Afiliado"
                  :text="solicitud.afiliado.nombre_completo"
                  :hint="solicitud.afiliado.identificacion_completa"
                  v-model="solicitud"
                  :slot-prepend='{
            template:`<v-list-tile-avatar><mini-card-detail :data="value.afiliado.mini_afiliado" alone-icon></mini-card-detail></v-list-tile-avatar>`,
            props: [`value`]
          }'
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm5 md3"
                  label="Regimen"
                  :text="solicitud.afiliado.regimen.nombre"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  label="Municipio"
                  :text="`${solicitud.afiliado.municipio.nombre}, ${solicitud.afiliado.municipio.nombre_departamento}`"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  label="Zona"
                  :text="`${solicitud.afiliado.zona.nombre}`"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  :label="solicitud.afiliado.zona.id === 2 ? 'Vereda' : 'Barrio'"
                  :text="solicitud.afiliado.zona.id === 2 ? solicitud.afiliado.vereda ? solicitud.afiliado.vereda.nombre  : '' : solicitud.afiliado.barrio ? solicitud.afiliado.barrio.nombre : ''"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  label="Dirección"
                  :text="`${solicitud.afiliado.direccion}`"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  label="Correo electrónico"
                  :text="`${solicitud.afiliado.correo_electronico}`"
                ></input-detail-flex>
                <input-detail-flex
                  flex-class="xs12 sm6 md3"
                  label="Celular"
                  :text="`${solicitud.afiliado.celular}`"
                ></input-detail-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Fecha solicitud"
          :text="solicitud.fecha ? (moment((solicitud.fecha + ' ' + solicitud.hora), 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm')) : ''"
          prepend-icon="event"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Origen autorización"
          :text="solicitud.origen_autorizacion.descrip"
        ></input-detail-flex>
        <input-detail-flex
          v-if="solicitud.origen_autorizacion.codigo === '3'"
          flex-class="xs12 sm6 md3"
          label="Tutela"
          :text="solicitud.tutela ? solicitud.tutela.tipo_tutela + ' - ' + solicitud.tutela.no_tutela : ''"
          :hint="solicitud.tutela ? solicitud.tutela.fecha_notificacion : ''"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Vía solicitud"
          :text="solicitud.via_solicitud ? solicitud.via_solicitud.descrip : ''"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Fecha orden médica"
          :text="solicitud.fOrdMed"
          prepend-icon="event"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm12 md9"
          label="Prestador"
          :text="solicitud.entidad_ips ? solicitud.entidad_ips.nombre : ''"
          :hint="solicitud.entidad_ips ? 'Código: ' + solicitud.entidad_ips.cod_habilitacion : ''"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Origen atención"
          :text="solicitud.origen_atencion.descripcion"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Tipo servicio"
          :text="solicitud.servicio_solicitado ? solicitud.servicio_solicitado.descrip : ''"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Prioridad"
          :text="solicitud.prior ? 'Atención prioritara' : 'Atención no prioritara'"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Ubicación paciente"
          :text="solicitud.ubicacion_paciente ? solicitud.ubicacion_paciente.descrip : ''"
        ></input-detail-flex>
        <template v-if="solicitud.ubic === '3'">
          <input-detail-flex
            flex-class="xs12 sm6 md3"
            label="Servicio"
            :text="solicitud.serv"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md3"
            label="Cama"
            :text="solicitud.cama"
          ></input-detail-flex>
        </template>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>fas fa-first-aid</v-icon>
                Impresión diagnóstica
              </v-toolbar-title>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <input-detail-flex
                  flex-class="xs12"
                  label="Diagnostico principal"
                  :text="solicitud.diagnostico_principal ? solicitud.diagnostico_principal.descripcion : ''"
                  :hint="solicitud.diagnostico_principal ? 'Código: ' + solicitud.diagnostico_principal.codigo : ''"
                  prepend-icon="local_hospital"
                ></input-detail-flex>
                <input-detail-flex
                  v-if="solicitud.diagnostico_reluno"
                  flex-class="xs12"
                  label="Diagnostico relacionado 1"
                  :text="solicitud.diagnostico_reluno.descripcion"
                  :hint="'Código:' + solicitud.diagnostico_reluno.codigo"
                  prepend-icon="queue"
                ></input-detail-flex>
                <input-detail-flex
                  v-if="solicitud.diagnostico_reldos"
                  flex-class="xs12"
                  label="Diagnostico relacionado 2"
                  :text="solicitud.diagnostico_reldos.descripcion"
                  :hint="'Código:' + solicitud.diagnostico_reldos.codigo"
                  prepend-icon="queue"
                ></input-detail-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <input-detail-flex
          flex-class="xs12"
          label="Historia clínica"
          :text="solicitud.historia_clinica ? solicitud.historia_clinica.nombre : ''"
          prepend-icon="fas fa-file-pdf"
          :append-button="solicitud.historia_clinica ? {tooltip: 'Descargar archivo', icon: 'cloud_download', color: 'primary'} : null"
          @clickAppend="goRuta(solicitud.historia_clinica.url_signed)"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12"
          label="Justificación clínica"
          :text="solicitud.jusCli"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Médico"
          :text="solicitud.medico ? solicitud.medico.descripcion : ''"
          :hint="solicitud.medico ? 'Código: ' + solicitud.medico.codigo : ''"
          prepend-icon="fas fa-user-md"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Especialidad"
          :text="solicitud.especialidad ? solicitud.especialidad.descripcion : ''"
          :hint="solicitud.especialidad ? 'Código: ' + solicitud.especialidad.codHab + ' - Nivel: ' + solicitud.especialidad.nivel : ''"
          prepend-icon="fas fa-award"
        ></input-detail-flex>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-list-tile>
                <v-icon left v-if="data && data.ind === '3'">print</v-icon>
                <v-icon left v-else>fas fa-briefcase-medical</v-icon>
                <v-list-tile-content>
                  <v-list-tile-title>Servicios registrados</v-list-tile-title>
                  <v-list-tile-sub-title class="font-weight-bold">Vigente hasta: {{data.ind === '3' ? data.fecha_vencimiento : moment().add(60, 'days').format('YYYY-MM-DD')}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-spacer></v-spacer>
              <dialog-registro-servicios
                v-if="solicitud.ind === '1'"
                ref="registroServicio"
                :solicitud="solicitud"
                proceso="autorización"
                @asignar="val => data.detalles.push(val)"
                @aprobar="val => data.detalles.splice(servicioSeleccionado, 1, val)"
              ></dialog-registro-servicios>
            </v-toolbar>
            <v-data-table
              v-if="data.ind === '3'"
              v-model="selected"
              :headers="headersImpresion"
              :items="data.detalles"
              hide-actions
              select-all
              item-key="id"
            >
              <template v-slot:headers="props">
                <tr>
                  <th>
                    <v-checkbox
                      :input-value="props.all"
                      :indeterminate="props.indeterminate"
                      primary
                      hide-details
                      @click.stop="toggleAll"
                    ></v-checkbox>
                  </th>
                  <th
                    v-for="header in props.headers"
                    :key="header.text"
                    @click="changeSort(header.value)"
                  >
                    {{ header.text }}
                  </th>
                </tr>
              </template>
              <template v-slot:items="props">
                <tr :active="props.selected" @click="props.selected = !props.selected">
                  <td>
                    <v-checkbox
                      v-if="props.item.ind === 1 && props.item.cAut"
                      :input-value="props.selected"
                      primary
                      hide-details
                    ></v-checkbox>
                  </td>
                  <td>{{ props.item.codigo }}</td>
                  <td>{{ props.item.descrip }}</td>
                  <td>{{ props.item.entidad ? props.item.entidad.nombre : '' }}</td>
                  <td class="text-xs-center">{{ props.item.cant }}</td>
                  <td class="text-xs-center">{{ props.item.cAut }}</td>
                  <td>{{props.item.ind === 2 ? 'Anulado' : props.item.negacion ? 'Negado' : (props.item.ind === 1 && props.item.cAut) ? 'Aprobado' : 'Pendiente'}}</td>
                </tr>
              </template>
            </v-data-table>
            <v-data-table
              v-else
              :headers="headersServicios"
              :items="data.detalles"
              hide-actions
            >
              <template v-slot:items="props">
                <td>{{ props.item.codigo }}</td>
                <td>{{ props.item.descrip }}</td>
                <td class="text-xs-center">{{ props.item.cant }}</td>
                <td class="text-xs-center">{{ props.item.cAut }}</td>
                <td class="text-xs-center">{{ props.item.entidad ? props.item.entidad.nombre : '' }}</td>
                <td>{{props.item.ind === 2 ? 'Anulado' : props.item.negacion ? 'Negado' : (props.item.ind === 1 && props.item.cAut) ? 'Aprobado' : 'Pendiente'}}</td>
                <td class="text-xs-center" width="220">
                  <template v-if="!props.item.negacion && props.item.ind === 1">
                    <v-tooltip top>
                      <v-btn class="mx-0" flat icon color="primary" slot="activator" @click.stop="aprobarItem(props)">
                        <v-icon>check</v-icon>
                      </v-btn>
                      <span>Aprobar</span>
                    </v-tooltip>
                    <v-tooltip top v-if="esJefeAutorizador">
                      <v-btn class="mx-0" flat icon color="orange" slot="activator" @click.stop="negarItem(props)">
                        <v-icon>block</v-icon>
                      </v-btn>
                      <span>Negar</span>
                    </v-tooltip>
                  </template>
                  <v-tooltip top v-if="props.item.ind === 1">
                    <v-btn class="mx-0" flat icon color="error" slot="activator" @click.stop="anularServicio(props)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Anular</span>
                  </v-tooltip>
                <v-tooltip top v-if="props.item.ind === 1">
                    <v-btn class="mx-0" flat icon color="error" slot="activator" @click.stop="borrarServicio(props)">
                      <v-icon>delete_forever</v-icon>
                    </v-btn>
                    <span>Borrar</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
      <confirmar
        :value="dialogGC.visible"
        :loading="dialogGC.loading"
        :content="dialogGC.content"
        :requiere-motivo="false"
        @cancelar="cancelaGC"
        @aceptar="aceptaGC"
      ></confirmar>
      <confirmar
        :value="dialogBS.visible"
        :loading="dialogBS.loading"
        :content="dialogBS.content"
        @cancelar="cancelaBS"
        :requiere-motivo="false"
        @aceptar="aceptaBS"
      ></confirmar>
      <confirmar
        :value="dialogAS.visible"
        :loading="dialogAS.loading"
        :content="dialogAS.content"
        @cancelar="cancelaAS"
        :requiere-motivo="true"
        @aceptar="item => aceptaAS(item)"
      ></confirmar>
      <dialog-negacion-servicio ref="negarServicio" @negar="datos => aceptarNegacion(datos)"></dialog-negacion-servicio>
    </v-container>
    <v-card-actions>
      <v-spacer></v-spacer>
      <template v-if="data && data.ind === '3'">
        <v-text-field
          class="mr-4"
          v-if="selected.length"
          label="Recibe"
          v-model="recibidoPor"
        ></v-text-field>
        <v-btn v-if="selected.length" @click="imprimirServicios" color="primary">Imprimir servicios</v-btn>
      </template>
      <template v-else-if="data && data.ind === '1'">
        <v-btn @click="guardarConfirmar" color="success">Guardar y confirmar</v-btn>
        <v-btn @click="submit" color="primary">Guardar</v-btn>
      </template>
    </v-card-actions>
  </v-card>
</template>

<script>
  import DialogRegistroServicios from '@/components/misional/atencionUsuario/autorizaciones/registroAutorizacion/DialogRegistroServicio'
  import DialogNegacionServicio from '@/components/misional/atencionUsuario/autorizaciones/DialogNegacionServicio'
  import DialogRedServicios from '@/components/misional/redservicios/DialogRedServicios'
  import {mapState} from 'vuex'
  export default {
    name: 'RegistroAutorizacion',
    props: ['parametros'],
    components: {
      DialogNegacionServicio,
      DialogRegistroServicios,
      DialogRedServicios,
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      servicioSeleccionado: null,
      showNegacion: false,
      loading: false,
      solicitud: null,
      data: null,
      headersServicios: [
        {
          text: 'Código',
          align: 'left',
          sortable: false
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: false
        },
        {
          text: 'Cant. Solicitada',
          align: 'center',
          sortable: false
        },
        {
          text: 'Cant. Aprobada',
          align: 'center',
          sortable: false
        },
        {
          text: 'Entidad',
          align: 'left',
          sortable: false
        },
        {
          text: 'Estado',
          align: 'left',
          sortable: false
        },
        {
          text: 'Opciones',
          align: 'center',
          sortable: false
        }
      ],
      recibidoPor: null,
      selected: [],
      headersImpresion: [
        {
          text: 'Código',
          align: 'left',
          sortable: false
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: false
        },
        {
          text: 'Entidad',
          align: 'left',
          sortable: false
        },
        {
          text: 'Cant. Solicitada',
          align: 'center',
          sortable: false
        },
        {
          text: 'Cant. Aprobada',
          align: 'center',
          sortable: false
        },
        {
          text: 'Estado',
          align: 'left',
          sortable: false
        }
      ],
      dialogBS: {
        loading: false,
        visible: false,
        content: null,
        item: null
      },
      dialogAS: {
        loading: false,
        visible: false,
        content: null,
        item: null
      },
      dialogGC: {
        loading: false,
        visible: false,
        content: null
      }
    }),
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      esJefeAutorizador () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 51)
      }
    },
    created () {
      this.refresh()
      if (this.parametros.entidad && this.parametros.entidad.id) {
        this.getData()
      } else {
        this.$store.commit('SNACK_SHOW', {msg: 'No se ha seleccionado un proceso de autorización.', color: 'orange'})
      }
    },
    methods: {
      imprimirServicios () {
        this.$validator.validateAll().then(async result => {
          if (result) {
            this.loading = true
            await this.filePrint(`autorizacione&id=${this.solicitud.id}&idDetalles=[${this.selected.filter(z => z.ind === 1 && z.cAut).slice().map(x => x.id)}]&recibido=${this.recibidoPor}`)
            this.loading = false
          }
        })
      },
      toggleAll () {
        if (this.selected.length) this.selected = []
        else this.selected = this.data.detalles.slice()
      },
      async getData () {
        this.loading = true
        this.axios.get(`autorizaciones/anexo3/${this.parametros.entidad.id}`)
          .then((response) => {
            this.solicitud = JSON.parse(JSON.stringify(response.data.data))
            this.data = JSON.parse(JSON.stringify(response.data.data))
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los datos del proceso de autorización. `, error: e})
          })
      },
      async submit (gc = null) {
        if (await this.validado(gc)) {
          this.loading = true
          this.dialogGC.loading = true
          let dataCopia = JSON.parse(JSON.stringify(this.data))
          dataCopia.detalles.map(x => {
            if (x.si_copago) {
              x.si_copago = x.si_copago.toString().toLowerCase() === 'si' ? 1 : 2
            }
          })
          this.axios.put(`autorizaciones/anexo3/${dataCopia.id}`, dataCopia)
            .then(response => {
              this.solicitud = JSON.parse(JSON.stringify(response.data.data))
              this.data = JSON.parse(JSON.stringify(response.data.data))
              if (this.parametros && this.parametros.envia === 'funcionarios') {
                this.$store.commit('reloadTable', 'tableAutorizacionesFuncionarios')
              } else if (this.parametros && this.parametros.envia === 'prestadores') {
                this.$store.commit('reloadTable', 'tableAutorizacionesPrestadores')
              }
              this.$store.commit('SNACK_SHOW', {msg: 'La autorización se ha guardado correctamente.', color: 'success'})
              this.loading = false
              this.dialogGC.loading = false
            }).catch(e => {
              this.data.ind = '1'
              this.loading = false
              this.dialogGC.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: `al guardar el registro de la autorización.`, error: e})
            })
        } else {
          this.data.ind = '1'
        }
      },
      async guardarConfirmar () {
        if (await this.validado('gc')) {
          this.dialogGC.content = `Si acepta confirmar la autorización, los datos no se podrán editar <strong>¿Está seguro de confirmar la autorización?</strong>`
          this.dialogGC.visible = true
        }
      },
      cancelaGC () {
        this.dialogGC.visible = false
        this.dialogGC.loading = false
        setTimeout(() => {
          this.dialogGC.content = null
        }, 400)
      },
      async aceptaGC () {
        this.data.ind = '3'
        await this.submit('gc')
        this.cancelaGC()
      },
      aprobarItem (props) {
        this.servicioSeleccionado = props.index
        this.$refs.registroServicio.asignarItem(JSON.parse(JSON.stringify(props.item)), this.data)
      },
      negarItem (props) {
        this.$refs.negarServicio.assign(JSON.parse(JSON.stringify(props)))
      },
      aceptarNegacion (datos) {
        this.data.detalles[datos.index].cAut = 0
        this.data.detalles[datos.index].ind = 1
        this.data.detalles[datos.index].negacion = datos
      },
      borrarServicio (item) {
        this.dialogBS.item = JSON.parse(JSON.stringify(item))
        this.dialogBS.content = `<strong>¿Está seguro de borrar el servicio con código ${this.dialogBS.item.item.codigo}?</strong>`
        this.dialogBS.visible = true
      },
      cancelaBS () {
        this.dialogBS.visible = false
        this.dialogBS.loading = false
        this.dialogBS.content = null
        this.dialogBS.item = null
      },
      async aceptaBS () {
        this.dialogBS.loading = true
        setTimeout(() => {
          this.data.detalles.splice(this.dialogBS.item.index, 1)
          this.cancelaBS()
        }, 500)
      },
      anularServicio (item) {
        this.dialogAS.item = JSON.parse(JSON.stringify(item))
        this.dialogAS.content = `<strong>¿Está seguro de anular el servicio con código ${this.dialogAS.item.item.codigo}?</strong>`
        this.dialogAS.visible = true
      },
      cancelaAS () {
        this.dialogAS.visible = false
        this.dialogAS.loading = false
        this.dialogAS.content = null
        this.dialogAS.item = null
      },
      async aceptaAS (item) {
        this.dialogAS.loading = true
        setTimeout(() => {
          this.dialogAS.item.item.motivo_anulacion = item
          this.dialogAS.item.item.ind = 2
          this.dialogAS.item.item.cAut = null
          this.dialogAS.item.item.negacion = null
          this.data.detalles.splice(this.dialogAS.item.index, 1, this.dialogAS.item.item)
          this.cancelaAS()
        }, 500)
      },
      async validado (gc) {
        //  gc = refiere a si la valdiación que se hace es desde guardar y confirmar o solo guardar
        let errorForm = await this.$validator.validateAll()
        let errorDetails = false
        if (gc === 'gc') {
          errorDetails = this.data.detalles.filter(x => x.ind === 1 && x.cAut === null).length
          if (errorDetails) {
            this.$store.commit('SNACK_SHOW', {msg: 'Hay servicios pendientes por validar.', color: 'orange'})
          }
        }
        return (errorForm && !errorDetails)
      },
      refresh () {
        this.recibidoPor = null
        this.selected = []
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>

</style>
