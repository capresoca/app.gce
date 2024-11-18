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
                  <v-text-field v-model="dependencia.descripcion"
                                label="Descripción"
                                key="descripción"
                                name="descripción"
                                v-validate="'required|max:200'"
                                required
                                :error-messages="errors.collect('descripción')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    :items="areas"
                    v-model="dependencia.area"
                    item-value="area"
                    item-text="descripcion"
                    name="area"
                    label="Area"
                    :error-messages="errors.collect('area')"
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
              v-text="!dependencia.dependencia ? 'Guardar' : 'Actualizar'"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
    <toolbar-list
      :info="infoComponent"
      title="Dependencias"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Dependencia"
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
    name: 'TbDependencia',
    data: () => ({
      dialog: false,
      dependencia: null,
      areas: [],
      dataTable: {
        nameItemState: 'tableDependenciaTh',
        route: 'talentohumano/dependencias',
        makeHeaders: [
          {
            text: '# Dependencia',
            align: 'center',
            sortable: false,
            value: 'dependencia',
            classData: 'text-xs-center'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Area',
            align: 'left',
            sortable: false,
            value: 'area',
            component: {
              template: `
                <span v-text="value.tbarea ? value.tbarea.descripcion : null"></span>
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
        return `${(this.dependencia.dependencia === null ? 'Nuevo ' : 'Edición de la ')}Dependencia ${this.dependencia.dependencia === null ? '' : this.dependencia.dependencia}`
      }
    },
    methods: {
      getComplementos () {
        this.axios.get(`talentohumano/complementos`)
          .then(response => {
            this.areas = response.data.data.areas
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar los complementos.', error: e})
          })
      },
      formReset () {
        this.dependencia = {
          dependencia: null,
          descripcion: null,
          area: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        item.options.push({event: 'eliminar', color: 'red', icon: 'fas fa-trash', tooltip: 'Eliminar registro'})
        return item
      },
      editarItem (item) {
        this.dependencia = this.clone(item)
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
            let typeRequest = !this.dependencia.dependencia ? this.axios.post('talentohumano/dependencias', this.dependencia)
              : this.axios.put('talentohumano/dependencias/' + this.dependencia.dependencia, this.dependencia)
            typeRequest.then(response => {
              this.$store.commit('reloadTable', 'tableDependenciaTh')
              this.$store.commit('SNACK_SHOW', {msg: `La dependencia ${response.data.msg}`, color: 'success'})
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar la dependencia. ', error: e})
              this.close()
            })
          }
        })
      },
      eliminarItem (item) {
        this.axios.delete(`talentohumano/dependencias/${item.area}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableDependenciaTh')
            this.$store.commit('SNACK_SHOW', {msg: `La dependencia ${response.data.msg}`, color: 'success'})
            this.close()
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar la dependencia. ', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
