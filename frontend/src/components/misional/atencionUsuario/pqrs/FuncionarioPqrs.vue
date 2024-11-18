<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="PQRS" subtitle="Registro y gestión"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs1 sm3 md6 class="text-xs-right">
            <v-tooltip top>
              <v-btn
                slot="activator"
                flat
                icon
                color="accent"
                @click="reloadPage"
              >
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar registros</span>
            </v-tooltip>
          </v-flex>
          <v-flex xs12 sm3 md2>
            <v-select
              label="Registros por página"
              v-model="pagination.per_page"
              :items="items_page"
              item-text="text"
              item-value="value"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar"
            />
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="headers"
          :items="pqrss"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="pqrss.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.consecutivo}}</td>
            <td class="justify-center layout align-center px-0">
              <div>
                <v-tooltip top> <v-icon v-if="props.item.es_prioritaria === 'Si'" slot="activator" color="red" dark>priority_high</v-icon><span>Prioritaria</span></v-tooltip>
                <v-tooltip top> <v-icon slot="activator" :color="colorIconoEstado(props.item.estado)" dark>{{ iconoEstado(props.item.estado)  }}</v-icon><span>{{ props.item.estado }}</span></v-tooltip>
              </div>
            </td>
            <td>{{ props.item.asunto.descripcion}}</td>
            <td>{{ `${props.item.nombres}  ${props.item.apellidos != 'null' && props.item.apellidos != null? props.item.apellidos : ''}` }}</td>
            <td>{{ props.item.fecha_limite}}</td>

            <td>{{ props.item.tipo.tipo}}</td>
            <td>
              <v-chip class="index-cero" v-if="props.item.dias_para_responder >= 10000" color="info" label text-color="white" >No requiere</v-chip>
              <v-chip class="index-cero" v-if="props.item.estado == 'Respondido'" color="success" label text-color="white" >Respondido</v-chip>
              <v-chip class="index-cero" v-if="props.item.estado != 'Respondido' && props.item.dias_para_responder < 10000" :color="colorChip(props.item.dias_para_responder)" label text-color="white" >{{ props.item.dias_para_responder }} días</v-chip>
            </td>
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
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detallePqrs', titulo: 'Detalle PQRS', parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                  >
                    <v-icon color="accent">find_in_page</v-icon>
                  </v-btn>
                  <span>Ver detalle</span>
                </v-tooltip>

                <v-tooltip top>
                  <v-btn
                    v-if="props.item.respuesta"
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    :loading="loadingPdf"
                    @click="descargarPdf(props.item.id, 0)"
                  >
                    <v-icon color="accent">far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Descargar carta respuesta</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    :loading="loadingPdf"
                    @click="descargarPdf(props.item.id, 1)"
                  >
                    <v-icon color="black">cloud_download</v-icon>
                  </v-btn>
                  <span>Decargar Oficio de Descargos PQRSD</span>
                </v-tooltip>

              </v-speed-dial>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="success" icon="info">
              No tienes ninguna PQRS asignada. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>

          <template slot="footer">
            <td colspan="100%" class="text-xs-center">
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'FuncionarioPqrs',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        pqrss: [],
        search: '',
        tableLoading: false,
        loadingPdf: false,
        items_page: [
          {
            text: 15,
            value: 15
          },
          {
            text: 50,
            value: 50
          },
          {
            text: 100,
            value: 100
          }
        ],
        pagination: {
          per_page: 15,
          current_page: 1
        },

        headers: [
          {
            text: 'No.',
            align: 'left',
            sortable: false,
            value: 'numero'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Asunto',
            align: 'left',
            sortable: false,
            value: 'asunto',
            width: '400'
          },
          {
            text: 'Remitente',
            align: 'left',
            sortable: false,
            value: 'remitente'
          },
          {
            text: 'Fecha límite',
            align: 'left',
            sortable: false,
            value: 'fechaLimite'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Vence en',
            align: 'left',
            sortable: false,
            value: 'venceEn'
          },
          {
            text: 'Opciones',
            align: 'left',
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
      'itemDetallePqrs' (value) {
        this.pqrss.splice(this.pqrss.findIndex(x => x.id === value.item.id), 1, value.item)
      },
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('pqrs')
      },
      ...mapState({
        itemDetallePqrs: state => state.tables.itemDetallePqrs,
        usuario: state => state.user.profile
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get(`pqrsds?funcionario_id=${this.usuario.id}&per_page=` + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.pqrss = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            console.log('Hay un error. ' + e)
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      iconoEstado (estado) {
        if (estado === 'Leído') return 'done_all'
        if (estado === 'Respondido') return 'thumb_up'
        if (estado === 'Registrado') return 'receipt'
        if (estado === 'Borrador Respuesta') return 'question_answer'
      },
      colorIconoEstado (estado) {
        if (estado === 'Registrado') return 'warning'
        if (estado === 'Respondido') return 'success'
        if (estado === 'Borrador Respuesta') return 'pink darken-4'
        return 'primary'
      },
      colorChip (dias) {
        if (dias >= 5) return 'success'
        if (dias >= 1) return 'orange'
        return 'red'
      },
      descargarPdf (id, num) {
        this.loadingPdf = true
        this.axios.get(`firmar-ruta?nombre_ruta=${(num === 0) ? 'respuesta-pqrs-pdf' : 'descargos-pqrs-pdf'}&id=${id}`)
          .then(res => {
            window.open(res.data)
            this.loadingPdf = false
          })
          .catch(e => {
            this.loadingPdf = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al generar el archivo. ' + e, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>
  .index-cero {
    z-index: 0;
  }
</style>
