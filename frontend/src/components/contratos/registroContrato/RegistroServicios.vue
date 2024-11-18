<template>
  <v-layout row wrap>
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card class="elevation-2">
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Servicios primarios'"/>
          <small class="mt-2 ml-1"> Configuración de servicios primarios</small>
        </v-toolbar>
        <v-data-table
          :headers="headersPrimarios"
          :items="items_primarios"
          hide-actions
          class="table-servicios-primarios"
        >
          <template slot="items" slot-scope="props">
            <td>
              <v-checkbox
                :key="'check' + props.item.id"
                v-model="props.item.selected"
                primary
                hide-details
                @change="!props.item.selected && (props.item.porcentaje = null)"
                :disabled="!!value.actaInicio"
              />
            </td>
            <td class="text-xs-center">{{ props.item.codigo }}</td>
            <td>{{ props.item.nombre }}</td>
            <td class="text-xs-center" width="300px">
              <v-text-field
                v-if="props.item.selected"
                :key="'porcenjate' + props.item.id"
                :name="'porcenjate' + props.item.id"
                data-vv-as="Porcentaje"
                placeholder="Porcentaje"
                v-validate="'required|min_value:1|max_value:100'"
                min="0"
                max="100"
                type="number"
                v-model.number="props.item.porcentaje"
                :error-messages="errors.collect('porcenjate' + props.item.id)"
                :disabled="!!value.actaInicio"
              />
            </td>
          </template>
        </v-data-table>
        <template v-if="!value.actaInicio">
          <v-divider/>
          <v-card-actions>
            <v-spacer/>
            <v-btn color="primary" @click.stop="submitPrimarios">Registrar</v-btn>
          </v-card-actions>
        </template>
      </v-card>
    </v-flex>
    <v-flex xs12>
      <v-card class="elevation-1">
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Servicios generales'"/>
          <small class="mt-2 ml-1"> Selección de servicios</small>
          <v-spacer></v-spacer>
          <v-text-field
            class="mb-2"
            v-model="searchGenerales"
            append-icon="search"
            label="Buscar"
            single-line
            hide-details
          />
        </v-toolbar>
        <v-data-table
          :headers="headersGenerales"
          :items="items_generales"
          :search="searchGenerales"
          :pagination.sync="paginationGenerales"
          select-all
          item-key="id"
          rows-per-page-text="Registros por página:"
        >
          <template slot="headers" slot-scope="props">
            <tr>
              <th>
                <v-checkbox
                  v-model="selectAllGenerales"
                  :input-value="props.all"
                  :indeterminate="props.indeterminate"
                  primary
                  hide-details
                  @change="toggleAll"
                  :disabled="!!value.actaInicio"
                ></v-checkbox>
              </th>
              <th
                v-for="header in props.headers"
                :key="header.text"
                :class="['column sortable', paginationGenerales.descending ? 'desc' : 'asc', header.value === paginationGenerales.sortBy ? 'active' : '']"
                @click="changeSort(header.value)"
              >
                <v-icon small>arrow_upward</v-icon>
                {{ header.text }}
              </th>
            </tr>
          </template>
          <template slot="items" slot-scope="props">
            <tr :active="props.seleccionados_generales" @click="props.seleccionados_generales = !props.seleccionados_generales">
              <td>
                <v-checkbox
                  :key="props.item.id"
                  v-model="seleccionados_generales"
                  primary
                  hide-details
                  :value="props.item.id"
                  :disabled="!!value.actaInicio"
                ></v-checkbox>
              </td>
              <td class="text-xs-center">{{ props.item.codigo }}</td>
              <td>{{ props.item.nombre }}</td>
            </tr>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="warning">
            No hay registros para mostrar{{ searchGenerales ? ' segun criterio de busqueda : ' + searchGenerales : '.' }}
          </v-alert>
          <template slot="pageText" slot-scope="props">
            Mostrando registros del {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
          </template>
        </v-data-table>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroServicios',
    props: ['value'],
    components: {
      Loading
    },
    data: () => ({
      loading: false,
      items_generales: [],
      items_primarios: [],
      seleccionados_generales: [],
      seleccionados_primarios: [],
      // tabla generales
      selectAllGenerales: false,
      paginationGenerales: {
        sortBy: 'codigo'
      },
      searchGenerales: null,
      headersGenerales: [
        {
          text: 'Código',
          align: 'center',
          sortable: true,
          value: 'codigo'
        },
        {
          text: 'Nombre',
          align: 'left',
          sortable: true,
          value: 'nombre'
        }
      ],
      // tabla primarios
      headersPrimarios: [
        {
          text: '',
          align: 'center',
          sortable: false,
          value: 'id'
        },
        {
          text: 'Código',
          align: 'center',
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
          text: 'Porcetaje afiliados',
          align: 'center',
          sortable: false,
          value: 'porcentaje'
        }
      ]
    }),
    created () {
      this.buildServisces()
    },
    watch: {
      'seleccionados_generales' (val, prev) {
        if (!this.loading) this.submitGenerales({val: JSON.parse(JSON.stringify(val)), prev: JSON.parse(JSON.stringify(prev))})
      },
      'value' (val) {
        val && this.buildServisces()
      }
    },
    methods: {
      toggleAll () {
        if (this.selectAllGenerales) this.seleccionados_generales = this.items_generales.map(s => { return s.id })
        else this.seleccionados_generales = []
      },
      changeSort (column) {
        if (this.paginationGenerales.sortBy === column) {
          this.paginationGenerales.descending = !this.paginationGenerales.descending
        } else {
          this.paginationGenerales.sortBy = column
          this.paginationGenerales.descending = false
        }
      },
      procesaRegistros (data) {
        return data.map(x => {
          return {
            id: (x.id ? x.id : x),
            porcentaje: typeof x.porcentaje === 'undefined' ? null : x.porcentaje
          }
        })
      },
      async submitPrimarios () {
        if (await this.$validator.validateAll()) {
          this.loading = true
          let registros = this.items_primarios.filter(x => x.selected).length ? this.procesaRegistros(this.seleccionados_generales).concat(this.procesaRegistros(this.items_primarios.filter(x => x.selected))) : this.procesaRegistros(this.seleccionados_generales)
          this.axios.post(`contratos/${this.value.id}/add-servicios`, {servicios: registros})
            .then((response) => {
              this.seleccionados_primarios = JSON.parse(JSON.stringify(this.items_primarios))
              this.loading = false
            })
            .catch(e => {
              this.items_primarios = JSON.parse(JSON.stringify(this.seleccionados_primarios))
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar servicios contratados. ', error: e})
            })
        }
      },
      submitGenerales (data) {
        this.loading = true
        let registros = this.procesaRegistros(data.val).concat(this.procesaRegistros(this.seleccionados_primarios.filter(x => x.selected)))
        this.axios.post(`contratos/${this.value.id}/add-servicios`, {servicios: registros})
          .then(response => {
            this.selectAllGenerales = ((this.items_generales.length === this.seleccionados_generales.length) && this.seleccionados_generales.length > 0)
            this.items_primarios = JSON.parse(JSON.stringify(this.seleccionados_primarios))
            this.loading = false
          })
          .catch(e => {
            this.seleccionados_generales = data.prev
            this.selectAllGenerales = ((this.items_generales.length === this.seleccionados_generales.length) && this.seleccionados_generales.length > 0)
            setTimeout(() => {
              this.loading = false
            }, 400)
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar servicios contratados. ', error: e})
          })
      },
      buildServisces () {
        this.items_primarios = this.value.entidad.servicios_primarios.map(s => {
          let esta = this.value.servicios_primarios.find(x => x.id === s.id)
          return {
            id: s.id,
            codigo: s.codigo,
            nombre: s.nombre,
            primario: s.primario,
            selected: esta,
            porcentaje: esta ? esta.pivot.porcentaje_afiliados : null
          }
        })
        this.seleccionados_primarios = JSON.parse(JSON.stringify(this.items_primarios))
        this.items_generales = this.value.entidad.servicios_generales.map(s => {
          return {
            id: s.id,
            codigo: s.codigo,
            nombre: s.nombre,
            primario: s.primario
          }
        })
        this.seleccionados_generales = this.value.servicios_generales.map(x => { return x.id })
      }
    }
  }
</script>

<style>
  .table-servicios-primarios table tbody td input{
    text-align: center !important;
  }
</style>
