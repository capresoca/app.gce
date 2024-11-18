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
                  <v-text-field v-model="cencosto.descripcion"
                                label="Descripción"
                                key="descripción"
                                name="descripción"
                                v-validate="'required|max:200'"
                                required
                                :error-messages="errors.collect('descripción')"></v-text-field>
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
              @click.prevent="saveCencosto()"
              flat
              v-text="!cencosto.centro_costo ? 'Guardar' : 'Actualizar'"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
    <toolbar-list
      :info="infoComponent"
      title="Centros de Costo"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Centro"
      btnplustruncate
      @click="dialog = true"
    ></toolbar-list>
    <data-table-v2
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => editarItem(item)"
      @eliminar="item => eliminar(item)"
    >
    </data-table-v2>
  </v-card>
</template>

<script>
  export default {
    name: 'TbCentroCosto',
    data: () => ({
      dialog: false,
      cencosto: null,
      dataTable: {
        nameItemState: 'tableCentroCosto',
        route: 'talentohumano/cencostos',
        makeHeaders: [
          {
            text: '# Centro',
            align: 'center',
            sortable: false,
            value: 'centro_costo',
            classData: 'text-xs-center'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
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
        return `${(this.cencosto.centro_costo === null ? 'Nuevo ' : 'Edición de la ')}Centro de Costo ${this.cencosto.centro_costo === null ? '' : this.cencosto.centro_costo}`
      }
    },
    methods: {
      formReset () {
        this.cencosto = {
          centro_costo: null,
          descripcion: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        item.options.push({event: 'eliminar', color: 'red', icon: 'fas fa-trash', tooltip: 'Eliminar registro'})
        return item
      },
      editarItem (item) {
        this.cencosto = this.clone(item)
        this.dialog = !this.dialog
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.dialog = false
      },
      saveCencosto () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let typeRequest = !this.cencosto.centro_costo ? this.axios.post('talentohumano/cencostos', this.cencosto)
              : this.axios.put('talentohumano/cencostos/' + this.cencosto.centro_costo, this.cencosto)
            typeRequest.then(response => {
              this.$store.commit('reloadTable', 'tableCentroCosto')
              this.$store.commit('SNACK_SHOW', {msg: `El centro de costo ${response.data.msg}`, color: 'success'})
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el centro de costo. ', error: e})
              this.close()
            })
          }
        })
      },
      eliminar (item) {
        this.axios.delete(`talentohumano/cencostos/${item.centro_costo}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableCentroCosto')
            this.$store.commit('SNACK_SHOW', {msg: `El centro de costo ${response.data.msg}`, color: 'success'})
            this.close()
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar el centro de costo. ', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
