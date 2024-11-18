<template>
  <v-layout row wrap>
    <loading v-model="loading" />
    <v-flex xs12 class="pa-0">
      <v-data-table
        :headers="headersPrimarios"
        :items="serviciosHabilitar"
        hide-actions
        :no-data-text="loading ? 'Cargando servicios habilitados' : 'No hay servicios habilitados'"
      >
        <template slot="items" slot-scope="props">
          <td width="50%">
            <template>
              <v-list-tile-content class="truncate-content">
                <v-list-tile-title class="font-weight-bold">{{props.item.codigo}} - {{ props.item.nombre }}</v-list-tile-title>
                <v-list-tile-title>{{esPortabilidad ? props.item.asignado_portabilidad.nombre : props.item.asignado_contratado.nombre}}</v-list-tile-title>
                <v-list-tile-sub-title class="caption" v-if="esPortabilidad ? props.item.asignado_portabilidad.cod_habilitacion : props.item.asignado_contratado.cod_habilitacion">Código habilitación: {{(esPortabilidad ? props.item.asignado_portabilidad.cod_habilitacion : props.item.asignado_contratado.cod_habilitacion)}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </td>
          <td v-if="!esPortabilidad">
            <v-autocomplete
              v-if="props.item.habilitados.length"
              placeholder="Seleccionar IPS"
              v-model="seleccionados[props.index]"
              :items="props.item.habilitados"
              item-value="id"
              item-text="nombre"
              :filter="filterIPSs"
              clearable
              persistent-hint
              :hint="!!seleccionados[props.index] ? ('Código: ' + props.item.habilitados.find(x => x.id === seleccionados[props.index]).cod_habilitacion) : ''"
            >
              <template slot="item" slot-scope="data">
                <template>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.nombre"/>
                    <v-list-tile-sub-title v-html="'Código: ' + data.item.cod_habilitacion"/>
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
    </v-flex>
    <v-flex xs12 class="pa-0" v-if="!esPortabilidad">
      <template>
        <v-divider/>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat color="primary" @click.stop="submit">Registrar</v-btn>
        </v-card-actions>
      </template>
    </v-flex>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroRedServicios',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    components: {
      Loading
    },
    data: () => ({
      loading: false,
      esPortabilidad: false,
      serviciosHabilitar: [],
      headersPrimarios: [],
      seleccionados: [],
      filterIPSs (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }),
    created () {
      this.getData()
    },
    watch: {
    },
    methods: {
      submit () {
        this.loading = true
        this.axios.post(`afiliados/${this.afiliado.id}/servicios/sync`, {servicios_habilitados: JSON.parse(JSON.stringify(window.lodash.difference(this.seleccionados.filter(Boolean), this.afiliado.servicios_habilitados.map(x => x.id))))}).then(response => {
          this.$store.commit(SNACK_SHOW, {msg: 'Los servicios se han asignado satisfactoriamente. ', color: 'success'})
          this.afiliado.servicios_habilitados = response.data
          this.getData()
        }).catch(e => {
          this.loading = false
          this.$store.commit(SNACK_ERROR_LIST, {expression: ' al asignar los servicios. ', error: e})
        })
      },
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.afiliadoId}`)
          .then((response) => {
            this.afiliado.servicios_contratados = response.data.data.servicios_contratados
            this.afiliado.servicios_habilitados = response.data.data.servicios_habilitados
            this.afiliado.servicios_portabilidad = response.data.data.servicios_portabilidad
            this.axios.get(`servicios/primarios`)
              .then((responsex) => {
                this.reOrderData(responsex)
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los servicios habilitados. ', error: e})
              })
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos del afiliado. ', error: e})
          })
      },
      reOrderData (response) {
        this.headersPrimarios = [
          {
            text: 'Servicio',
            align: 'left',
            sortable: false,
            value: 'nombre'
          }
        ]
        this.esPortabilidad = !!response.data.data[0].habilitados_portabilidad
        let theData = response.data.data.map(x => {
          return {
            id: x.id,
            codigo: x.codigo,
            nombre: x.nombre,
            habilitados_portabilidad: this.esPortabilidad
              ? x.habilitados_portabilidad.map(z => {
                return {
                  id: z.id,
                  rs_servicio_id: z.rs_servicio_id,
                  cod_habilitacion: z.entidad.cod_habilitacion,
                  nombre: z.entidad.nombre
                }
              })
              : [],
            asignado_portabilidad: this.esPortabilidad
              ? this.afiliado.servicios_portabilidad && !!this.afiliado.servicios_portabilidad.find(y => y.rs_servicio_id === x.id)
                ? {nombre: this.afiliado.servicios_portabilidad.find(y => y.rs_servicio_id === x.id).entidad.nombre, cod_habilitacion: this.afiliado.servicios_portabilidad.find(y => y.rs_servicio_id === x.id).entidad.cod_habilitacion}
                : {nombre: 'No asignado', cod_habilitacion: null}
              : {nombre: 'No asignado', cod_habilitacion: null},
            asignado_contratado: !this.esPortabilidad
              ? this.afiliado.servicios_habilitados && !!this.afiliado.servicios_habilitados.find(y => y.rs_servicio_id === x.id)
                ? {nombre: this.afiliado.servicios_habilitados.find(y => y.rs_servicio_id === x.id).entidad.nombre, cod_habilitacion: this.afiliado.servicios_habilitados.find(y => y.rs_servicio_id === x.id).entidad.cod_habilitacion}
                : {nombre: 'No asignado', cod_habilitacion: null}
              : {nombre: 'No asignado', cod_habilitacion: null},
            habilitados: x.habilitados
              ? x.habilitados.map(z => {
                return {
                  id: z.id,
                  rs_servicio_id: z.rs_servicio_id,
                  cod_habilitacion: z.entidad ? z.entidad.cod_habilitacion : 'No registra entidad ' + z.rs_entidad_id,
                  nombre: z.entidad ? z.entidad.nombre : 'No registra entidad'
                }
              })
              : []
          }
        })
        this.serviciosHabilitar = theData
        this.seleccionados = []
        this.serviciosHabilitar.forEach(x => {
          if (this.esPortabilidad) {
            this.seleccionados.push((this.afiliado.servicios_portabilidad && !!this.afiliado.servicios_portabilidad.find(y => y.rs_servicio_id === x.id)) ? this.afiliado.servicios_portabilidad.find(y => y.rs_servicio_id === x.id).id : null)
          } else {
            this.seleccionados.push((this.afiliado.servicios_habilitados && !!this.afiliado.servicios_habilitados.find(y => y.rs_servicio_id === x.id)) ? this.afiliado.servicios_habilitados.find(y => y.rs_servicio_id === x.id).id : null)
          }
        })
        if (this.esPortabilidad) {
          // this.headersPrimarios.push({
          //   text: 'IPS portabilidad',
          //   align: 'left',
          //   sortable: false,
          //   value: 'id'
          // })
        } else {
          // {
          //   text: 'IPS asignada',
          //     align: 'left',
          //   sortable: false,
          //   value: 'id'
          // },
          this.headersPrimarios.push({
            text: 'Reasignar IPS',
            align: 'center',
            sortable: false,
            value: 'id'
          })
        }
      }
    }
  }
</script>

<style scoped>

</style>
