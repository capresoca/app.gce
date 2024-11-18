<template>
  <div>
    <!--height="442px"-->
    <v-card>
      <v-toolbar class="elevation-0 toolbar--dense">
        <v-toolbar-title>Conceptos de Retención en la Fuente </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn
          fab
          right
          small
          color="accent"
          @click="$store.commit('NAV_ADD_ITEM',
                  { ruta: infoComponent.ruta_registro,
                    titulo: infoComponent.titulo_registro,
                    parametros: {
                      entidad: null,
                      tabOrigin: $store.state.nav.currentItem.split('tab-')[1]
                    }
                  })"
          v-if="infoComponent ? infoComponent.permisos.agregar : false">
          <v-icon>add</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-title class="elevation-1">
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line=""
          hide-details
          flat
        >
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="conRtfs"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        :search="search" class="elevation-1" rows-per-page-text="Registros por página">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.manejo }}</td>
          <td class="text-xs-center">
            <v-btn icon class="mx-0" @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
              <v-icon color="accent">mode_edit</v-icon>
            </v-btn>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se encontraron resultados de "{{ search }}"
        </v-alert>
      </v-data-table>
      <v-card-actions class="text-xs-center pt-2 d-block">
        <!--<v-pagination v-model="pagination.current_page" @input="reloadPage"></v-pagination>-->
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'ConRtfs',
    data () {
      return {
        conRtfs: [],
        tableLoading: false,
        pagination: {},
        search: '',
        headers: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Manejo',
            align: 'left',
            sortable: false,
            value: 'manejo'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]

      }
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'itemConrtf' (value) {
        if (value.estado === 'crear') {
          this.conRtfs.splice(0, 0, value.conRtf)
        } else if (value.estado === 'editar') {
          // consele.log(data.data)
          this.conRtfs.splice(this.conRtfs.findIndex(conRtf => conRtf.id === value.conRtf.id), 1, value.conRtf)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('conRtfs')
      },
      ...mapState({
        itemConrtf: state => state.tables.itemConrtf
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('conrtfs').then((response) => {
          this.conRtfs = response.data.data
          this.pagination.totalItems = this.conRtfs.length
          this.tableLoading = false
        }).catch(e => {
          this.tableLoading = false
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
        })
      }
    }
  }
</script>

<style scoped>

</style>
