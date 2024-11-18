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
                  <v-text-field v-model="tipocontrato.descripcion"
                                label="Descripción"
                                key="descripción"
                                name="descripción"
                                v-validate="'required|max:200'"
                                required
                                :error-messages="errors.collect('descripción')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field
                    label="Duración"
                    v-model="tipocontrato.duracion"
                    name="duración"
                    type="number"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('duración')"
                  ></v-text-field>
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
              @click.prevent="saveTipo()"
              flat
              v-text="!tipocontrato.tipo_contrato ? 'Guardar' : 'Actualizar'"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
    <toolbar-list
      :info="infoComponent"
      title="Tipos de Contratos"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Tipo Contrato"
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
    name: 'TbTipoContrato',
    data: () => ({
      dialog: false,
      tipocontrato: null,
      dataTable: {
        nameItemState: 'tableTipoContratoTh',
        route: 'talentohumano/tipos_contratos',
        makeHeaders: [
          {
            text: '# Tipo Contrato',
            align: 'center',
            sortable: false,
            value: 'tipo_contrato',
            classData: 'text-xs-center'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Duración',
            align: 'left',
            sortable: false,
            value: 'duracion',
            component: {
              template: `
                <span v-text="value.duracion + (value.duracion === 1 ? ' Mes' : ' Meses')"></span>
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
        return `${(this.tipocontrato.tipo_contrato === null ? 'Nuevo ' : 'Edición del ')}Tipo Contrato ${this.tipocontrato.tipo_contrato === null ? '' : this.tipocontrato.tipo_contrato}`
      }
    },
    methods: {
      formReset () {
        this.tipocontrato = {
          tipo_contrato: null,
          descripcion: null,
          duracion: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        item.options.push({event: 'eliminar', color: 'red', icon: 'fas fa-trash', tooltip: 'Eliminar registro'})
        return item
      },
      editarItem (item) {
        this.tipocontrato = this.clone(item)
        this.dialog = !this.dialog
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.dialog = false
      },
      saveTipo () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let typeRequest = !this.tipocontrato.tipo_contrato ? this.axios.post('talentohumano/tipos_contratos', this.tipocontrato)
              : this.axios.put('talentohumano/tipos_contratos/' + this.tipocontrato.tipo_contrato, this.tipocontrato)
            typeRequest.then(response => {
              this.$store.commit('reloadTable', 'tableTipoContratoTh')
              this.$store.commit('SNACK_SHOW', {msg: `El tipo contrato ${response.data.msg}`, color: 'success'})
              this.close()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el tipo contrato. ', error: e})
              this.close()
            })
          }
        })
      },
      eliminar (item) {
        this.axios.delete(`talentohumano/tipos_contratos/${item.tipo_contrato}`)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableTipoContratoTh')
            this.$store.commit('SNACK_SHOW', {msg: `El tipo contrato ${response.data.msg}`, color: 'success'})
            this.close()
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar el tipo contrato. ', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
