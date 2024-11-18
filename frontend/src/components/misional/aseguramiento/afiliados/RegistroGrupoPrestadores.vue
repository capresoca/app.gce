<template>
  <v-layout row wrap>
    <loading v-model="loading"></loading>
    <v-flex xs12 v-if="portabilidad">
      <expand-portabilidad :portabilidad="portabilidad"></expand-portabilidad>
    </v-flex>
    <v-flex xs12>
      <v-card>
        <v-card-text class="pb-1">
          <v-autocomplete
            :label="(grupoSeleccionado ? 'G' : 'Seleccionar g') + 'rupo de prestadores'"
            v-model="grupoSeleccionado"
            :items="gruposPrestadores"
            item-value="id"
            item-text="descrip"
            return-object
            name="grupo de prestadores"
            v-validate="'required'"
            :error-messages="errors.collect('grupo de prestadores')"
          >
            <template slot="selection" slot-scope="data">
                            <span style="width: 100% !important;">{{data.item.descrip}}
                              <v-chip small color="primary" text-color="white">
                                <v-avatar class="primary darken-4">{{data.item.prestadores.length}}</v-avatar>
                                Prestadores
                              </v-chip>
                            </span>
            </template>
            <template slot="item" slot-scope="data">
                            <span style="width: 100% !important;">{{data.item.descrip}}
                              <v-chip small color="primary" text-color="white">
                                <v-avatar class="primary darken-4">{{data.item.prestadores.length}}</v-avatar>
                                Prestadores
                              </v-chip>
                            </span>
            </template>
          </v-autocomplete>
        </v-card-text>
        <v-data-table
          v-if="grupoSeleccionado"
          :items="grupoSeleccionado.prestadores"
          class="elevation-1"
          hide-actions
          :headers="headers"
        >
          <template v-slot:items="props">
            <td class="text-xs-center">
              <v-avatar color="green darken-2" size="36" v-if="props.item.primaria">
                <v-icon class="white--text">fas fa-check</v-icon>
              </v-avatar>
            </td>
            <td>
              <v-list-tile-content>
                <v-list-tile-title>{{props.item.entidad.nombre}}</v-list-tile-title>
                <v-list-tile-sub-title class="caption">Código Habilitación: {{props.item.entidad.cod_habilitacion}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </td>
            <td>
              <v-icon size="small">fas fa-phone-alt</v-icon> {{props.item.entidad.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{props.item.entidad.direccion_sede}}
            </td>
          </template>
        </v-data-table>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn @click="getData">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn @click="submit" color="primary">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  export default {
    name: 'RegistroGrupoPrestadores',
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
      ExpandPortabilidad: () => import('@/components/misional/aseguramiento/portabilidad/ExpandPortabilidad')
    },
    data: () => ({
      loading: false,
      portabilidad: null,
      grupoSeleccionado: null,
      gruposPrestadores: [],
      headers: [
        {
          text: 'Prestador Primario',
          align: 'center',
          sortable: false
        },
        {
          text: 'Prestador',
          align: 'left',
          sortable: false
        },
        {
          text: 'Dirección',
          align: 'left',
          sortable: false
        }
      ]
    }),
    created () {
      this.getData()
    },
    methods: {
      getData () {
        this.loading = true
        this.axios.get(`rs_servicios/afiliado_grupo/${this.afiliadoId}`)
          .then((response) => {
            console.log('respuesta de grupo', response)
            this.portabilidad = response.data.portabilidad_activa
            this.grupoSeleccionado = response.data.ips_grupo && response.data.ips_grupo.grupo_ips ? response.data.ips_grupo.grupo_ips : null
            this.gruposPrestadores = response.data.grupos
            this.loading = false
            this.$nextTick(() => {
              this.$validator.reset()
            })
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos del grupo grupo de prestadores. ', error: e})
          })
      },
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.axios.post(`rs_servicios/afiliado/${this.afiliadoId}`, {as_afiliado_id: this.afiliadoId, id_grupoips: this.grupoSeleccionado.id})
              .then(() => {
                this.$store.commit('SNACK_SHOW', {msg: 'El grupo de prestadores se actualizó correctamente.', color: 'success'})
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al actualizar el grupo de prestadores. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
