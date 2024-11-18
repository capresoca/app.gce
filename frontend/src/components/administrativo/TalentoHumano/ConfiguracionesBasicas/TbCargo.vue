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
                  <v-text-field v-model="cargo.descripcion"
                                label="Descripción"
                                key="descripción"
                                name="descripción"
                                v-validate="'required|max:200'"
                                required
                                :error-messages="errors.collect('descripción')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-textarea
                    label="Función"
                    v-model="cargo.funcion"
                    name="función"
                    v-validate="'required'"
                    :error-messages="errors.collect('función')"
                    rows="1"
                  ></v-textarea>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    :items="tipos"
                    v-model="cargo.tipo_trabajador"
                    item-value="tipo_trabajador"
                    item-text="nombre"
                    name="tipo trabajador"
                    label="Tipo Trabajador"
                    :error-messages="errors.collect('tipo trabajador')"
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
              @click.prevent="saveCargo()"
              flat
              v-text="!cargo.cargo ? 'Guardar' : 'Actualizar'"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
    <toolbar-list
      :info="infoComponent"
      title="Cargos"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Cargo"
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
    name: 'TbCargo',
    data: () => ({
      dialog: false,
      cargo: null,
      dataTable: {
        nameItemState: 'tableCargoTh',
        route: 'talentohumano/cargos',
        makeHeaders: [
          {
            text: '# Cargo',
            align: 'center',
            sortable: false,
            value: 'cargo',
            classData: 'text-xs-center'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion',
            component: {
              template: `
                <v-tooltip right>
                  <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                    <v-list-tile-content class="truncate-content">
                      <v-list-tile-title class="body-2">{{value.descripcion}}</v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <span v-text="'Tipo trabajador: ' + (value.tipo_trabajador ? (value.tipo_trabajador === 1 ? 'Administrativo' : (value.tipo_trabajador === 2 ? 'Operativo' : 'Especial')) : null)"></span>
                </v-tooltip>
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
        return `${(this.cargo.cargo === null ? 'Nuevo ' : 'Edición del ')}Cargo ${this.cargo.cargo === null ? '' : this.cargo.cargo}`
      },
      tipos () {
        return [
          {
            tipo_trabajador: 1,
            nombre: 'Administrativo'
          },
          {
            tipo_trabajador: 2,
            nombre: 'Operativo'
          },
          {
            tipo_trabajador: 3,
            nombre: 'Especial'
          }
        ]
      }
    },
    methods: {
      formReset () {
        this.cargo = {
          cargo: null,
          descripcion: null,
          funcion: null,
          tipo_trabajador: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        item.options.push({event: 'eliminar', color: 'red', icon: 'fas fa-trash', tooltip: 'Eliminar registro'})
        return item
      },
      editarItem (item) {
        this.cargo = this.clone(item)
        this.dialog = !this.dialog
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.dialog = false
      },
      saveCargo () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let typeRequest = !this.cargo.cargo ? this.axios.post('talentohumano/cargos', this.cargo)
              : this.axios.put('talentohumano/cargos/' + this.cargo.cargo, this.cargo)
            typeRequest.then(response => {
              this.$store.commit('reloadTable', 'tableCargoTh')
              this.$store.commit('SNACK_SHOW', {msg: `El cargo ${response.data.msg}`, color: 'success'})
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el cargo. ', error: e})
              this.close()
            })
          }
        })
      },
      eliminarItem (item) {
        this.axios.delete(`talentohumano/cargos/${item.area}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableCargoTh')
            this.$store.commit('SNACK_SHOW', {msg: `El cargo ${response.data.msg}`, color: 'success'})
            this.close()
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar el cargo. ', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
