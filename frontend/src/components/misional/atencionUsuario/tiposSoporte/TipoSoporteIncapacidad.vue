<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Crear tipo de soporte</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12>
                <v-text-field v-model="soporte.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="soporte.observacion"
                  solo
                  name="observacion"
                  label="Observación"
                  rows="1"
                ></v-textarea>               
              </v-flex>

              <v-flex xs12>
                <v-select
                  label="Posicion en formulario"
                  v-model="soporte.posicion_formulario"
                  :items="recobros"
                  item-text="nombre"
                  item-value="value"
                  v-show="mostrarcampete"
                />
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="soporte.codigo_recobro"
                              label="Código de recobros" key="codigo"
                              name="codigo" v-validate="'max:50'"
                              :error-messages="errors.collect('codigo')"
                              v-show="mostrarcampete"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-select
                  label="Identificación recobro"
                  v-model="soporte.identificacion_recobro"
                  :items="recobros"
                  item-text="nombre"
                  item-value="value"
                  v-show="mostrarcampete"
                />
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario -->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear registro" title= "Tipos de soporte x Tipo incapacidad" subtitle="Registro y gestión"/>
      <data-table
        v-model="dataTable"
        ref="childComponent"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar tipo soporte por tipo incapacidad', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
    </v-card>
  </div>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'tipoSoportes',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dialog: false,
        mostrarcampete: false,
        soporte: {},
        recobros: [
          {
            value: '1',
            nombre: '1'
          },
          {
            value: '0',
            nombre: '0'
          }
        ],
        dataTable: {
          nameItemState: 'itemSoporteIncapacidad',
          route: 'soportexincapacidad',
          makeHeaders: [
            {
              text: 'Tipo incapacidad',
              align: 'left',
              sortable: false,
              value: 'proceso',
              component: {
                component: {
                  template: `
                    <div>
                      {{value.proceso|tipoIncapacidades}}
                    </div>
                  `,
                  props: ['value'],
                  filters: {
                    tipoIncapacidades: function (val) {
                      if (val === 2) {
                        return 'Incapacidad Enfermedad General'
                      } else if (val === 4) {
                        return 'Incapacidad por Accidente de Trabajo'
                      } else if (val === 5) {
                        return 'Incapacidad por Accidente de Tránsito'
                      } else if (val === 7) {
                        return 'Licencia de Maternidad'
                      } else if (val === 8) {
                        return 'Licencia de Paternidad'
                      } else if (val === 17) {
                        return 'Licencia Fallecimiento Madre'
                      }
                    }
                  }
                }
              }
            },
            {
              text: 'Descripción',
              align: 'left',
              sortable: true,
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
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('tiposoportexincapacidad')
      }
    },
    created () {
      this.$refs.childComponent.reloadPage()
    },
    mounted () {
      this.$refs.childComponent.reloadPage()
    },
    methods: {
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'success', icon: 'mode_edit', tooltip: 'Editar'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
