<template>
  <v-dialog v-model="openM" persistent width="720px">
    <v-card v-if="facturas.length">
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-toolbar-title>{{`Auditores factura${facturas.length === 1 ? '' : 's'} No. ${facturas.map(x => x.no_factura).join(', ')}`}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="openM = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container
        fluid
        grid-list-md
      >
        <postulador-v3
          no-data-text="Busqueda por identificación, nombre o código de auditor."
          item-text="usuario.name"
          label="Auditores"
          route="ac_auditores"
          model="ac_auditores"
          filter="identificacion_completa,usuario.name,codigo"
          prepend-icon="person_outline"
          v-model="auditorSeleccionado"
          name="auditor"
          v-validate="`required`"
          :error-messages="errors.collect('auditor')"
          :slot-data='{
                      template:`
                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.codigo}} - {{value.usuario.name}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{value.identificacion_completa}} - {{value.tipo}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                     }'
        ></postulador-v3>
        <v-data-table
          v-if="facturas.length === 1"
          :items="facturas[0].auditores"
          class="elevation-1"
          :headers="headersAuditor"
          hide-actions :key="'table'">
          <template slot="items" slot-scope="props">
            <tr :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
              <td class="text-xs-left">{{ props.item ?  props.item.identificacion_completa : null }}</td>
              <td class="text-xs-left">{{ props.item ?  props.item.usuario.name : null }}</td>
              <td class="text-xs-left">{{ props.item ?  props.item.tipo : null }}</td>
              <td class="text-xs-center">
                <v-tooltip top>
                  <v-btn icon slot="activator"
                         class="mx-0"
                         fab
                         small
                         @click="eliminar(props.index)"
                  >
                    <v-icon color="accent">delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
              </td>
            </tr>
          </template>
          <template slot="no-data" class="error">
            <div class="pa-1 pl-2 red--text text-xs-center">
              No hay auditores asignados
            </div>
          </template>
        </v-data-table>
        <v-data-table
          v-else
          :items="auditoresSeleccionados"
          class="elevation-1"
          :headers="headersAuditor"
          hide-actions :key="'table'">
          <template slot="items" slot-scope="props">
            <tr :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
              <td class="text-xs-left">{{ props.item ?  props.item.identificacion_completa : null }}</td>
              <td class="text-xs-left">{{ props.item ?  props.item.usuario.name : null }}</td>
              <td class="text-xs-left">{{ props.item ?  props.item.tipo + (props.item.tecnico === 1 ? ' Técnico' : '') : null }}</td>
              <td class="text-xs-center">
                <v-tooltip top>
                  <v-btn icon slot="activator"
                         class="mx-0"
                         fab
                         small
                         @click="auditoresSeleccionados.splice(props.index, 1)"
                  >
                    <v-icon color="accent">delete</v-icon>
                  </v-btn>
                  <span>Elimina</span>
                </v-tooltip>
              </td>
            </tr>
          </template>
          <template slot="no-data" class="error">
            <div class="pa-1 pl-2 red--text text-xs-center">
              No hay auditores seleccionados
            </div>
          </template>
        </v-data-table>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn flat color="grey"  @click="openM = false" v-text="'Cerrar'"></v-btn>
        <v-spacer></v-spacer>
        <v-btn flat color="primary" @click="asignar()">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DialogAuditores',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data: () => ({
      loading: false,
      openM: false,
      facturas: [],
      auditoresSeleccionados: [],
      auditorSeleccionado: null,
      headersAuditor: [
        {
          text: 'Identificación',
          align: 'left',
          sortable: false,
          value: 'auditor.usuario.identification'
        },
        {
          text: 'Nombre',
          align: 'left',
          sortable: false,
          value: 'auditor.usuario.name'
        },
        {
          text: 'Tipo Auditor',
          align: 'left',
          sortable: false,
          value: 'auditor'
        },
        {
          text: 'Eliminar',
          align: 'center',
          sortable: false,
          value: ''
        }
      ]
    }),
    watch: {
      async 'auditorSeleccionado' (val) {
        if (val) {
          if (this.auditoresSeleccionados.length && this.auditoresSeleccionados.find(x => x.id === val.id)) {
            this.$store.commit('SNACK_SHOW', {msg: `El auditor ya se encuentra seleccionado`, color: 'orange'})
          } else if (this.auditoresSeleccionados.length && this.auditoresSeleccionados.find(x => x.tipo === val.tipo)) {
            this.$store.commit('SNACK_SHOW', {msg: `Ya hay un auditor de tipo ${val.tipo}`, color: 'orange'})
          } else {
            this.auditoresSeleccionados.push(val)
          }
        }
        this.$nextTick(() => {
          this.auditorSeleccionado = null
          this.$validator.reset()
        })
      }
    },
    methods: {
      asignar () {
        if (this.facturas.length && this.auditoresSeleccionados.length) {
          this.loading = true
          return new Promise(resolve => {
            this.axios.post(`asignaciones/facturas`, {facturas: this.facturas.map(x => x.id), auditores: this.auditoresSeleccionados.map(x => x.id)})
              .then(response => {
                console.log('response auditor', response)
                this.facturas.forEach(x => {
                  x.auditores = this.auditoresSeleccionados
                })
                this.$store.commit('SNACK_SHOW', {msg: `Se asignaron correctamente los auditores.`, color: 'success'})
                this.$emit('asigna')
                this.loading = false
                resolve(true)
                this.close()
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ` al asignar el auditor. `, error: e})
                resolve(false)
              })
          })
        } else {
          this.$store.commit('SNACK_SHOW', {msg: !this.facturas.length ? `No hay facturas seleccionadas` : 'No hay auditores seleccionados', color: 'orange'})
        }
      },
      eliminar (item) {
        this.auditoresSeleccionados.splice(item, 1)
      },
      open (facturas) {
        this.auditoresSeleccionados = []
        this.facturas = facturas
        if (this.facturas.length === 1) this.auditoresSeleccionados = this.facturas[0].auditores
        this.openM = true
      },
      close () {
        this.openM = false
        this.$nextTick(() => {
          this.auditorSeleccionado = null
          this.$validator.reset()
        })
      }
    }
  }
</script>

<style scoped>

</style>
