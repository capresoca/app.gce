<template>
  <v-card>
    <v-dialog v-model="dialog" max-width="500px">
      <form ref="form">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="headline">{{ modalTitle }}</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm12 md12>
                  <v-text-field v-model="area.descripcion"
                                label="Descripción"
                                key="descripción"
                                name="descripción"
                                v-validate="'required|max:200'"
                                required
                                :error-messages="errors.collect('descripción')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    :items="cencostos"
                    v-model="area.centro_costo"
                    item-value="centro_costo"
                    item-text="descripcion"
                    name="centro de costo"
                    label="Centro de costo"
                    :error-messages="errors.collect('centro de costo')"
                    v-validate="'required'"
                  ></v-select>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="close">Cancelar</v-btn>
            <v-btn
              color="blue darken-1"
              @click.prevent="saveArea()"
              flat
              v-text="!area.area ? 'Guardar' : 'Actualizar'"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
    <toolbar-list
      :info="infoComponent"
      title="Areas"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Area"
      btnplustruncate
      @click="dialog = true"
    ></toolbar-list>
    <data-table-v2
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => editarItem(item)"
      @eliminar="item => eliminarItem(item)"
    >
    </data-table-v2>
  </v-card>
</template>

<script>
  export default {
    name: 'TbArea',
    data: () => ({
      dialog: false,
      area: null,
      cencostos: [],
      dataTable: {
        nameItemState: 'tableAreaTh',
        route: 'talentohumano/areas',
        makeHeaders: [
          {
            text: '# Area',
            align: 'center',
            sortable: false,
            value: 'area',
            classData: 'text-xs-center'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Cetro de costo',
            align: 'left',
            sortable: false,
            value: 'centro_costo',
            component: {
              template: `
                <span v-text="value.cencosto ? value.cencosto.descripcion : null"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            singlesActions: true,
            classData: 'text-xs-center'
          }
        ]
      }
    }),
    watch: {
      dialog (value) {
        if (value === false) {
          this.close()
        } else {
          this.getComplementos()
        }
      }
    },
    created () {
      this.formReset()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('configbasicTHumano')
      },
      modalTitle () {
        return `${(this.area.area === null ? 'Nuevo ' : 'Edición de la ')}Area ${this.area.area === null ? '' : this.area.area}`
      }
    },
    methods: {
      getComplementos () {
        this.axios.get(`talentohumano/complementos`)
          .then(response => {
            this.cencostos = response.data.data.centroscostos
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar los complementos.', error: e})
          })
      },
      formReset () {
        this.area = {
          area: null,
          descripcion: null,
          centro_costo: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        item.options.push({event: 'eliminar', color: 'red', icon: 'fas fa-trash', tooltip: 'Eliminar registro'})
        return item
      },
      editarItem (item) {
        this.area = this.clone(item)
        this.dialog = !this.dialog
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.dialog = false
      },
      saveArea () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let typeRequest = !this.area.area ? this.axios.post('talentohumano/areas', this.area)
              : this.axios.put('talentohumano/areas/' + this.area.area, this.area)
            typeRequest.then(response => {
              this.$store.commit('reloadTable', 'tableAreaTh')
              this.$store.commit('SNACK_SHOW', {msg: `El area ${response.data.msg}`, color: 'success'})
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el area. ', error: e})
              this.close()
            })
          }
        })
      },
      eliminarItem (item) {
        this.axios.delete(`talentohumano/areas/${item.area}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableAreaTh')
            this.$store.commit('SNACK_SHOW', {msg: `El area ${response.data.msg}`, color: 'success'})
            this.close()
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar el area. ', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
