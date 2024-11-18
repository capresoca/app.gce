<template>
  <v-dialog v-model="dialog" persistent max-width="900px">
    <template v-slot:activator="{ on }" v-if="asignacion.manual">
      <v-tooltip top :disabled="!!asignacion.afiliadosSeleccionados.length || !asignacion.manual">
        <v-btn
          class="ma-0"
          :class="disabled ? '' : 'text--darken-2'"
          flat
          :color="disabled ? 'grey' : 'primary'"
          slot="activator"
          v-on="disabled ? null : on"
        >
          Asignar servicios
        </v-btn>
        <span>Seleccionar afiliados para iniciar el proceso</span>
      </v-tooltip>
    </template>
    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense class="elevation-1 white">
        <v-avatar size="36" color="primary" class="white--text">
          <v-icon>widgets</v-icon>
        </v-avatar>
        <v-list-tile>
          <v-list-tile-content>
            <v-list-tile-title class="subheading">Asignación de servicios</v-list-tile-title>
            <v-list-tile-sub-title class="caption" v-if="asignacion.manual">{{asignacion.afiliadosSeleccionados.length}} afiliados seleccionados</v-list-tile-sub-title>
            <v-list-tile-sub-title class="caption" v-else>afiliados cargados por archivo</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn
            slot="activator"
            icon
            @click="dialog = false"
          >
            <v-icon
            >
              close
            </v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-divider></v-divider>
      <v-card-text>
        <v-data-table
          :items="serviciosPrimarios.filter(x => x.id === asignacion.serviciosPendientes.find(z => z === x.id))"
          class="elevation-1"
          hide-actions
          :headers="headers"
        >
          <template v-slot:items="props">
            <td>{{ props.item.codigo }} - {{ props.item.nombre }}</td>
            <td>
              <v-autocomplete
                v-if="props.item.habilitados.length"
                placeholder="Seleccionar IPS"
                v-model="asignacion.ipssAsignadas[props.index]"
                :items="asignacion.tipo_proceso === 'Trasladar servicios' ? props.item.habilitados.filter(x => x.rs_entidad_id !== asignacion.rs_entidad_id) : props.item.habilitados"
                item-value="id"
                item-text="nombre"
                :filter="filterIPSs"
                clearable
              >
                <template slot="selection" slot-scope="data">
                  <template>
                    <v-list-tile-content style="width: 100% !important;">
                      <v-list-tile-title v-html="data.item.entidad.nombre"/>
                      <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{data.item.entidad.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{data.item.entidad.direccion_sede}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.entidad.nombre"/>
                      <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{data.item.entidad.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{data.item.entidad.direccion_sede}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
              <span v-else>
              No disponible
            </span>
            </td>
          </template>
        </v-data-table>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn flat @click="dialog = false">Cancelar</v-btn>
        <v-spacer></v-spacer>
        <v-btn v-if="(asignacion.afiliadosSeleccionados.length && asignacion.manual) || !asignacion.manual" color="primary" flat @click="confirmarAsignacion">Registrar</v-btn>
        <v-chip v-else label color="orange" text-color="white">
          <span v-if="(asignacion.afiliadosSeleccionados.length && asignacion.manual)">No se han seleccionado afiliados</span>
          <span v-if="(asignacion.ipssAsignadas.length && !asignacion.manual)">No se han seleccionado prestadores</span>
        </v-chip>
      </v-card-actions>
      <confirmar
        :value="dialogA.visible"
        :loading="dialogA.loading"
        :content="dialogA.content"
        @cancelar="cancelaAnulacion"
        :requiere-motivo="false"
        @aceptar="asignar"
      />
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'SelectServicios',
    props: {
      asignacion: {
        type: Object,
        default: null
      },
      serviciosPrimarios: {
        type: Array,
        default: []
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },
    components: {
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialog: false,
      loading: false,
      headers: [
        {
          text: 'Servicio',
          align: 'left',
          sortable: false
        },
        {
          text: 'IPS',
          align: 'left',
          sortable: false
        }
      ],
      dialogA: {
        loading: false,
        visible: false,
        content: null
      },
      filterIPSs (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.entidad.cod_habilitacion + ' ' + item.entidad.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }),
    computed: {
    },
    watch: {
    },
    methods: {
      confirmarAsignacion () {
        if (this.asignacion.ipssAsignadas.filter(Boolean).length) {
          this.dialogA.content = `El proceso de asignación masiva será registrado permanentemente <br/><strong>¿Está seguro de continuar con el registro.?</strong>`
          this.dialogA.visible = true
        } else {
          this.$store.commit('SNACK_SHOW', {msg: 'Hay que asignar al menos un servicio.', color: 'orange'})
        }
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.loading = false
        this.dialogA.content = null
      },
      open () {
        this.dialog = true
      },
      asignar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.dialogA.loading = true
            if (this.asignacion.manual) {
              let url = this.asignacion.tipo_proceso === 'Asignar servicios'
                ? this.axios.post(`redservicios/afiliadoservicios`, { tipo_proceso: this.asignacion.tipo_proceso, afiliados: this.asignacion.afiliadosSeleccionados.map(x => { return {as_afiliado_id: x.id} }), servicios_habilitados: JSON.parse(JSON.stringify(this.asignacion.ipssAsignadas.filter(Boolean))).map(x => { return {rs_servhabilitado_id: x} }) })
                : this.axios.post(`redservicios/afiliadoservicios`, { rs_servhabilitado_anterior: this.asignacion.rs_servhabilitado_anterior, tipo_proceso: this.asignacion.tipo_proceso, afiliados: this.asignacion.afiliadosSeleccionados.map(x => { return {as_afiliado_id: x.id} }), servicios_habilitados: JSON.parse(JSON.stringify(this.asignacion.ipssAsignadas.filter(Boolean))).map(x => { return {rs_servhabilitado_id: x} }) })
              url
                .then(() => {
                  this.loading = false
                  this.dialogA.loading = false
                  this.dialog = false
                  this.$store.commit('SNACK_SHOW', {msg: `El proceso de asignación masiva se registró correctamente.`, color: 'success'})
                  this.$emit('save')
                })
                .catch(e => {
                  this.loading = false
                  this.dialogA.loading = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: `al realizar el proceso de asignación masiva `, error: e})
                })
            } else {
              let data = new FormData()
              data.append('csv_afiliados', this.asignacion.archivo)
              data.append('servicios_habilitados', JSON.parse(JSON.stringify(this.asignacion.ipssAsignadas.filter(Boolean))))
              this.axios.post('redservicios/asigna_from_csv', data)
                .then(() => {
                  this.loading = false
                  this.dialogA.loading = false
                  this.dialog = false
                  this.$store.commit('SNACK_SHOW', {msg: `El proceso de asignación masiva se registró correctamente.`, color: 'success'})
                  this.$emit('save')
                })
                .catch(e => {
                  this.loading = false
                  this.dialogA.loading = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: `al realizar el proceso de asignación masiva `, error: e})
                })
            }
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
