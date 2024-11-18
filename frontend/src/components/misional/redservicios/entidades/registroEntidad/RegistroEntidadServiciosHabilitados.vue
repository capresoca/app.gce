<template>
  <v-layout row wrap>
    <loading v-model="loading" />
    <v-card-title>
      <v-layout align-center justify-space-between row fill-height>
        <v-flex d-flex>
          <treeselect
            multiple
            value-consists-of="LEAF_PRIORITY"
            v-model="tree"
            :options="items"
            placeholder="Seleccionar servicios"
          />
        </v-flex>
      </v-layout>
      <v-divider></v-divider>
    </v-card-title>
    <v-card-text
      v-if="selections && !selections.length"
      class="title font-weight-light grey--text text--center"
    >
      <v-layout class="pa-3" align-center justify-center row fill-height>
        Ésta entidad no tiene servicios habilitados.
      </v-layout>
    </v-card-text>
    <v-card-text v-else class="show-overflow-x pt-0 elevation-1">
      <v-card-title>
        Servicios seleccionados
        <v-spacer></v-spacer>
        <v-text-field
          v-model="searchServicios"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          clearable
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="selections"
        :search="searchServicios"
        rows-per-page-text="Registros por página:"
      >
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.label }}</td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn
                icon
                slot="activator"
                @click="submit(props.item.id, 'delete')"
              >
                <v-icon color="red">close</v-icon>
              </v-btn>
              <span>Quitar servicio</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="pageText" slot-scope="props">
          Mostrando registros del {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No hay registros para mostrar{{ searchServicios ? ' segun criterio de busqueda : ' + searchServicios : '.' }} <v-icon>sentiment_very_dissatisfied</v-icon>
        </v-alert>
      </v-data-table>
    </v-card-text>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'
  export default {
    name: 'RegistroEntidadServiciosHabilitados',
    props: ['value'],
    components: {
      Loading,
      Treeselect
    },
    data: () => ({
      searchServicios: null,
      headers: [
        { text: 'Código', value: 'codigo' },
        { text: 'Descripción', value: 'label', sortable: false },
        { text: '', value: 'id', sortable: false, align: 'center' }
      ],
      loading: false,
      servicios: [],
      fullServices: [],
      items: [],
      tree: [],
      selections: []
    }),
    created () {
      this.getServicios()
    },
    watch: {
      'tree' (val) {
        if (val.length > 0 && !this.loading) this.submit(JSON.parse(JSON.stringify(val)), 'insert')
      }
    },
    methods: {
      arrayUnique (oldSelection) {
        let hash = {}
        let x = oldSelection.filter(current => {
          let exists = !hash[current.id] || false
          hash[current.id] = true
          return exists
        })
        return x
      },
      submit (val, type) {
        this.loading = true
        let idsSelections = this.selections.map(item => { return item.id })
        let idsSubmited = type === 'insert' ? val.filter(x => parseInt(x) > 0).concat(idsSelections) : idsSelections.filter(x => x !== val)
        this.tree = []
        this.axios.post(`entidades/${this.value.id}/habilitar-servicios`, {servicios: idsSubmited})
          .then((response) => {
            this.selections = this.arrayUnique(this.reloadSelections(idsSubmited))
            this.items = this.reloadItems()
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el estado de los servicios. ', error: e})
          })
      },
      reloadItems () {
        const items = JSON.parse(JSON.stringify(this.fullServices))
        const ids = this.selections.map(item => { return item.id })
        for (const grupo of items) {
          for (const subgrupo of grupo.children) {
            let estan = []
            for (const servicio of subgrupo.children) {
              const esta = ids.find(x => x === servicio.id)
              if (esta) estan.push(esta)
            }
            if (estan.length) {
              estan.forEach(esta => {
                subgrupo.children.splice(subgrupo.children.findIndex(x => x.id === esta), 1)
              })
            }
          }
          grupo.children.filter(sub => !sub.children.length).map(x => { return x.id }).forEach(z => {
            grupo.children.splice(grupo.children.findIndex(j => j.id === z), 1)
          })
        }
        items.filter(grup => !grup.children.length).map(x => { return x.id }).forEach(z => {
          items.splice(items.findIndex(j => j.id === z), 1)
        })
        return items
      },
      reloadSelections (val) {
        const selections = []
        for (const itemId of val) {
          const servicio = this.servicios.find(x => x.id === itemId)
          if (!servicio) continue
          selections.push(servicio)
        }
        return selections
      },
      getServicios () {
        this.loading = true
        this.axios.get('gruposervicios')
          .then((response) => {
            if (response.data !== '') {
              this.fullServices = response.data.filter(grupo => grupo.subgrupos.length).map(grupo => {
                return {
                  id: 'g-' + grupo.id,
                  label: grupo.codigo + ' - ' + grupo.nombre,
                  children: grupo.subgrupos.filter(subgrupo => subgrupo.servicios.length).map(subgrupo => {
                    return {
                      id: 's-' + subgrupo.id,
                      label: subgrupo.codigo + ' - ' + subgrupo.nombre,
                      children: subgrupo.servicios.map(servicio => {
                        let service = {
                          id: servicio.id,
                          codigo: servicio.codigo,
                          label: servicio.codigo + ' - ' + servicio.nombre
                        }
                        this.servicios.push(service)
                        return service
                      })
                    }
                  })
                }
              })
              this.selections = this.reloadSelections(this.value.servicios_habilitados.map(servicio => { return servicio.id }))
              this.items = this.reloadItems()
              this.loading = false
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los servicios habilitados. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>
  .show-overflow-x {
    overflow-x: auto !important;
  }
</style>
