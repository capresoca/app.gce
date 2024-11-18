<template>
  <v-container fluid grid-list-xl class="pa-0">
    <loading v-model="loading" />
    <v-slide-y-transition group>
      <v-layout row wrap v-if="item" key="seccion1">
        <v-flex xs12 class="pa-0">
          <v-card class="elevation-0">
            <v-card-title>
              Relaciones laborales
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Buscar"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="item.relaciones_laborales"
              :search="search"
              rows-per-page-text="Registros por página:"
            >
              <template slot="items" slot-scope="props">
                <td>{{ props.item.pagador.identificacion }}</td>
                <td>{{ props.item.pagador.razon_social}}</td>
                <td>{{ props.item.fecha_vinculacion }}</td>
                <td class="text-xs-center">{{ props.item.periodos_mora }}</td>
                <td class="text-xs-center">{{ props.item.estado }}</td>
                <td class="text-xs-center">
                  <v-tooltip top>
                    <v-btn
                      flat
                      icon
                      color="accent"
                      slot="activator"
                      @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleRelacionLaboral', titulo: 'Detalle relación laboral', parametros: {entidad: {id: props.item.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
                    >
                      <v-icon>find_in_page</v-icon>
                    </v-btn>
                    <span>Ver detalle</span>
                  </v-tooltip>
                </td>
              </template>
              <v-alert slot="no-results" :value="true" color="error" icon="warning">
                No hay registros para mostrar{{ search ? ' segun criterio de busqueda : ' + search : '.' }}
              </v-alert>
              <template slot="pageText" slot-scope="props">
                Mostrando registros del {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
    </v-slide-y-transition>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'DetalleGeneralRelacionesLaborales',
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
    data () {
      return {
        search: null,
        headers: [
          {
            text: 'Identificación aportante',
            align: 'left',
            value: 'pagador.identificacion'
          },
          {
            text: 'Nombre aportante',
            align: 'left',
            value: 'pagador.razon_social'
          },
          {
            text: 'Fecha vinculación',
            align: 'left',
            value: 'fecha_vinculacion'
          },
          {
            text: 'Peridos mora',
            align: 'center',
            value: 'periodos_mora'
          },
          {
            text: 'Estado',
            align: 'center',
            value: 'estado'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ],
        loading: false,
        item: null
      }
    },
    created () {
      this.afiliado && this.afiliado.id ? this.item = this.afiliado : this.getData()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('relacionesLaborales')
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.afiliadoId}`)
          .then((response) => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los las relaciones laborales. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
