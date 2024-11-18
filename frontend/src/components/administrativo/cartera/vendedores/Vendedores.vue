<template>
    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-icon>face</v-icon>
        <v-toolbar-title>Vendedores</v-toolbar-title>
        <small class="mt-2 ml-1">Registro y gestion de vendedores</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            slot="activator"
            fab
            right
            small
            color="accent"
            @click="dialogSave=true">
            <v-icon>add</v-icon>
          </v-btn>
          <span>Registrar vendedores</span>
        </v-tooltip>
      </v-toolbar>
      <v-card-title>
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
        :items="listaVendedores"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.tercero.tipo_id.abreviatura }}</td>
          <td>{{ props.item.tercero.identificacion }}</td>
          <td>{{ props.item.nombre }}</td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn icon class="mx-0" slot="activator" @click="edit(props)">
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
        <template slot="pageText" slot-scope="props">
          {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se encontraron resultados de "{{ search }}"
        </v-alert>
      </v-data-table>
      <v-card-actions class="text-xs-center pt-2 d-block">
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
      </v-card-actions>
    <v-container>
    <div class="text-xs-center">
      <registro-vendedores :open="dialogSave" :parametros="param" @dialog="val => dialogSave = val" @objectReturn="val => returnDialogo(val)"></registro-vendedores>
    </div>
    </v-container>
    </v-card>
</template>


<script>
  import RegistroVendedores from '@/components/administrativo/cartera/vendedores/RegistroVendedores'
  export default {
    name: 'Vendedores',
    components: {RegistroVendedores},
    data () {
      return {
        search: '',
        buscandoCodigo: false,
        exist: false,
        ordenEdit: false,
        dialogSave: false,
        dialogCodigo: false,
        tableLoading: false,
        pagination: {},
        param: {},
        listaVendedores: [],
        vendedor: {},
        headers: [
          {
            text: 'Codigo',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Tipo identificacion',
            align: 'left',
            sortable: false,
            value: 'tercero.tipo_id.abreviatura'
          },
          {
            text: 'Identificacion',
            align: 'left',
            sortable: false,
            value: 'tercero.identificacion'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ],
        desserts: [
          {
            value: false,
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%'
          }
        ]
      }
    },
    computed: {
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    beforeCreate () {
      this.tableLoading = true
      this.axios.get('vendedor/')
        .then((res) => {
          this.listaVendedores = res.data.data
          this.pagination.totalItems = this.listaVendedores.length
          this.tableLoading = false
        })
        .catch(e => {
          return false
        })
    },
    methods: {
      returnDialogo (val) {
        console.log(val)
        if (val.tipo === 'new') {
          this.listaVendedores.splice(0, 0, val.data)
        } else {
          this.listaVendedores.splice(val.indice, 1, val.data)
        }
      },
      edit (val) {
        console.log(val.item)
        this.param = {
          vendedor: val.item,
          indice: val.index
        }
      }
    },
    mounted () {
    }
  }
</script>

<style scoped>

</style>
