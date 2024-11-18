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
          disable-initial-sort
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-center">{{ props.item.codigo }}</td>
            <td>{{ props.item.nombre }}</td>
            <td class="text-xs-center" width="180px">
              <v-text-field
                :ref="'porcenjate' + props.item.id"
                :key="'porcenjate' + props.item.id"
                :name="'porcenjate' + props.item.id"
                data-vv-as="Porcentaje"
                placeholder="Afiliados"
                suffix="%"
                v-validate="'min_value:0|max_value:100'"
                step="0.1"
                min="0"
                max="100"
                type="number"
                v-model.number="props.item.porcentaje"
                :error-messages="errors.collect('porcenjate' + props.item.id)"
                :disabled="!!value.actaInicio"
              />
            </td>
            <td class="text-xs-center" width="180px">
              <v-text-field
                :ref="'porcenjate_contratado' + props.item.id"
                :key="'porcenjate_contratado' + props.item.id"
                :name="'porcenjate_contratado' + props.item.id"
                data-vv-as="porcentaje contratado"
                placeholder="Contratado"
                suffix="%"
                v-validate="`min_value:0|max_value:100`"
                step="0.1"
                min="0"
                max="100"
                type="number"
                v-model.number="props.item.porcentaje_contratado"
                :error-messages="errors.collect('porcenjate_contratado' + props.item.id)"
                :disabled="!!value.actaInicio"
              />
            </td>
          </template>
        </v-data-table>
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
          :items="items_generales"
          :search="searchGenerales"
          :pagination.sync="paginationGenerales"
          :headers="headersPrimarios"
          item-key="id"
          disable-initial-sort
        >
          <template slot="headers" slot-scope="props">
            <tr>
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
            <td class="text-xs-center">{{ props.item.codigo }}</td>
            <td>{{ props.item.nombre }}</td>
            <td class="text-xs-center" width="180px">
              <v-text-field
                :key="'porcenjate' + props.item.id"
                :name="'porcenjate' + props.item.id"
                data-vv-as="Porcentaje"
                placeholder="Afiliados"
                suffix="%"
                v-validate="'min_value:0|max_value:100'"
                min="0"
                max="100"
                step="0.1"
                type="number"
                v-model.number="props.item.porcentaje"
                :error-messages="errors.collect('porcenjate' + props.item.id)"
                :disabled="!!value.actaInicio"
              />
            </td>
            <td class="text-xs-center" width="180px">
              <v-text-field
                :key="'porcenjate_contratado' + props.item.id"
                :name="'porcenjate_contratado' + props.item.id"
                data-vv-as="porcentaje contratado"
                placeholder="Contratado"
                suffix="%"
                v-validate="'min_value:0|max_value:100'"
                min="0"
                max="100"
                step="0.1"
                type="number"
                v-model.number="props.item.porcentaje_contratado"
                :error-messages="errors.collect('porcenjate_contratado' + props.item.id)"
                :disabled="!!value.actaInicio"
              />
            </td>
          </template>
        </v-data-table>
      </v-card>
      <v-tooltip left v-if="!value.actaInicio">
        <v-btn
          slot="activator"
          fixed
          dark
          fab
          bottom
          right
          color="primary"
          class="mb-3"
          @click="submit"
        >
          <v-icon>save</v-icon>
        </v-btn>
        <span>Guardar</span>
      </v-tooltip>
    </v-flex>
  </v-layout>
</template>

<script>
  export default {
    name: 'RegistroServicios',
    props: ['value'],
    data: () => ({
      loading: false,
      items_generales: [],
      items_primarios: [],
      // tabla generales
      paginationGenerales: {
        sortBy: 'codigo'
      },
      searchGenerales: null,
      // tabla primarios
      headersPrimarios: [
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
          sortable: true,
          value: 'porcentaje'
        },
        {
          text: 'Porcetaje contratado',
          align: 'center',
          sortable: true,
          value: 'porcentaje_contratado'
        }
      ]
    }),
    created () {
      this.buildServisces()
    },
    watch: {
      'value' (val) {
        val && this.buildServisces()
      }
    },
    methods: {
      changeSort (column) {
        if (this.paginationGenerales.sortBy === column) {
          this.paginationGenerales.descending = !this.paginationGenerales.descending
        } else {
          this.paginationGenerales.sortBy = column
          this.paginationGenerales.descending = false
        }
      },
      async submit () {
        if (await this.$validator.validateAll()) {
          this.loading = true
          let registros = this.items_primarios.filter(x => x.porcentaje_contratado || x.porcentaje).concat(this.items_generales.filter(z => z.porcentaje_contratado || z.porcentaje)).map(j => { return { id: j.id, porcentaje: j.porcentaje, porcentaje_contratado: j.porcentaje_contratado } })
          console.log('registrosregistrosregistros', registros)
          this.axios.post(`contratos/${this.value.id}/add-servicios`, {servicios: registros})
            .then((response) => {
              this.loading = false
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar servicios contratados. ', error: e})
            })
        }
      },
      buildServisces () {
        this.items_primarios = this.value.contrato.entidad.servicios_primarios.map(s => {
          let esta = this.value.servicios_primarios.find(x => x.id === s.id)
          return {
            id: s.id,
            codigo: s.codigo,
            nombre: s.nombre,
            primario: s.primario,
            porcentaje: esta && esta.pivot.porcentaje_afiliados ? esta.pivot.porcentaje_afiliados : null,
            porcentaje_contratado: esta && esta.pivot.porcentaje_contratado ? esta.pivot.porcentaje_contratado : null
          }
        })
        this.items_generales = this.value.contrato.entidad.servicios_generales.map(s => {
          let esta = this.value.servicios_generales.find(x => x.id === s.id)
          return {
            id: s.id,
            codigo: s.codigo,
            nombre: s.nombre,
            primario: s.primario,
            porcentaje: esta && esta.pivot.porcentaje_afiliados ? esta.pivot.porcentaje_afiliados : null,
            porcentaje_contratado: esta && esta.pivot.porcentaje_contratado ? esta.pivot.porcentaje_contratado : null
          }
        })
      }
    }
  }
</script>

<style>
  .table-servicios-primarios table tbody td input{
    text-align: center !important;
  }
</style>
