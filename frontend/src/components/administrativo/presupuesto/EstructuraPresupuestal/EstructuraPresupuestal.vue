<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formNiveles">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar Nivel</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout>

              <v-flex xs12 sm6>
                <v-text-field v-model="nivel.digitos"
                              label="Dígitos" key="digitos"
                              name="digitos" v-validate="'required|numeric|max_value:3'" required
                              :error-messages="errors.collect('digitos')">

                </v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field v-model="nivel.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required'" required
                              :error-messages="errors.collect('nombre')">

                </v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="crearNivel" :loading="loadingSubmit" :disabled="errors.any()">Crear</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogConcepto" width="500px">
      <v-form data-vv-scope="formConceptos">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar {{ niveles[0] ? niveles[0].nombre : ''}} (estructura rubro presupuestales)</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout>

              <v-flex xs12>
                <v-select
                  :items="tiposRubro"
                  v-model="concepto.tipo_rubro"
                  name="tipo"
                  label="Tipo"
                  :error-messages="errors.collect('tipo')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="concepto.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|digits:' + valorMaximo + '|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')">

                </v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeConcepto">Cancelar</v-btn>
            <v-btn color="primary" @click="crearConcepto" :loading="loadingSubmit" :disabled="errors.any()">Crear</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-container fluid grid-list-sm grid-list-xl ref="estructuraPresupuestal">
      <v-layout row wrap>

        <v-flex xs12 sm8>
          <v-card>
            <v-card-text>
              <v-alert v-if="!niveles.length" :value="true" type="info">
                No hay ningún nivel creado.
                <v-btn color="accent" class="white--text"@click="dialog = true">
                  Crear nivel
                </v-btn>
              </v-alert>
              <v-btn v-if="niveles.length" color="accent" class="white--text"@click="openDialog">
                {{ `Crear ${this.niveles[0].nombre}` }}
              </v-btn>
              <br>
              <v-tooltip top slot="activator">
                <v-btn v-if="niveles.length" @click="padreExpandir = !padreExpandir" fab dark small color="primary" slot="activator">
                  <v-icon dark>swap_vert</v-icon>
                </v-btn>
                <span>{{ toggleArbol }}</span>
              </v-tooltip>
              <concepto
                v-for="(cuenta, index) in arbol"
                @conceptoEliminado="removerConcepto(index)"
                @arbolModificado="obtenerNivelesYArbol"
                :key="cuenta.id"
                :model="cuenta"
                :ultimoNivel="ultimoNivel"
                :nivelesConcepto="niveles"
                :expandir="padreExpandir"
                >
              </concepto>
            </v-card-text>
          </v-card>
        </v-flex>

        <v-flex xs12 sm4>
          <v-card>
            <loading v-model="localLoading" />
            <v-card-text class="text-xs-center" ref="niveles">
              <v-data-table
                :headers="headers"
                :items="niveles"
                :loading="tableLoading"
                hide-actions
                class="elevation-0">
                <template slot="items" slot-scope="props">
                  <td>{{ props.item.nivel }}</td>
                  <td>{{ props.item.nombre }}</td>
                  <td>{{ props.item.digitos }}</td>
                  <td>
                    <v-tooltip top>
                      <v-icon
                        v-if="props.item.removable"
                        small
                        @click="eliminarNivel(props.item.id)"
                        slot="activator"
                      >
                        delete
                      </v-icon>
                      <span>Eliminar</span>
                    </v-tooltip>

                  </td>
                </template>
                <template slot="no-data">
                  <span  v-if="!tableLoading" :value="true">
                    No hay ningún nivel creado
                  </span>
                  <span v-else :value="true">
                    Cargando registros...
                  </span>
                </template>
              </v-data-table>

              <v-btn color="accent" class="white--text"@click="dialog = true">
                Crear nivel
              </v-btn>
            </v-card-text>
          </v-card>
        </v-flex>

      </v-layout>
    </v-container>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Concepto from './Concepto'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'EstructuraPresupuestal',
    components: {Concepto, Loading},
    data () {
      return {
        nivel: null,
        concepto: null,
        tiposRubro: ['Ingresos', 'Gastos'],
        niveles: [],
        arbol: null,
        loading: false,
        localLoading: false,
        tableLoading: true,
        loadingSubmit: false,
        dialog: false,
        dialogConcepto: false,
        ultimoNivel: null,
        valorMaximo: null,
        nivelId: '',
        codigosUsados: null,
        padreExpandir: true,

        headers: [
          {
            text: 'Nivel',
            align: 'center',
            sortable: false,
            value: 'nivel'
          },
          {
            text: 'Nombre',
            align: 'center',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Dígitos',
            align: 'center',
            sortable: false,
            value: 'digitos'
          },
          {
            text: '',
            align: 'center',
            sortable: false,
            value: 'eliminar'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      this.obtenerNivelesYArbol()
    },
    computed: {
      toggleArbol () {
        return this.padreExpandir === true ? 'Contraer árbol' : 'Expandir árbol'
      }
    },
    watch: {
      'concepto.tipo_rubro' (rubro) {
        this.concepto.nombre = rubro
      }
    },
    methods: {
      obtenerNivelesYArbol () {
        this.tableLoading = true
        let loader = this.$loading.show({
          container: this.$refs.estructuraPresupuestal.$el
        })

        this.axios.get('presupuesto/arbol')
          .then(res => {
            this.arbol = res.data
            loader.hide()
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e, color: 'danger'})
            loader.hide()
          })

        this.axios.get('presupuesto/niveles')
          .then(res => {
            this.niveles = res.data.data
            this.ultimoNivel = this.niveles[this.niveles.length - 1] ? this.niveles[this.niveles.length - 1].nivel : 0
            this.valorMaximo = this.niveles[0] ? this.niveles[0].digitos : 0
            this.concepto.pr_nivel_id = this.niveles[0] ? this.niveles[0].id : 0
            this.nivelId = this.niveles[0] ? this.niveles[0].id : 0
            this.tableLoading = false
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al crear concepto. ' + e, color: 'danger'})
            this.tableLoading = false
          })
      },
      async crearConcepto () {
        if (await this.validador('formConceptos')) {
          this.loadingSubmit = true
          this.axios.post('presupuesto/conceptos', this.concepto)
            .then(res => {
              if (!res.data.data.children) res.data.data.children = []
              this.arbol.push(res.data.data)
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.loadingSubmit = false
              this.closeConcepto()
              this.$emit('conceptoCreado')
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al crear el concepto. ' + e, color: 'danger'})
              this.loadingSubmit = false
            })
        }
      },
      removerConcepto (index) {
        this.arbol.splice(index, 1)
      },
      emitir () {
        this.$emit('arbolModificado')
      },
      expandir () {
        this.$refs.childConcepto.close()
      },
      async crearNivel () {
        if (await this.validador('formNiveles')) {
          this.loadingSubmit = true
          this.axios.post('presupuesto/niveles', this.nivel)
            .then(res => {
              this.loadingSubmit = false
              this.close()
              this.obtenerNivelesYArbol()
            })
            .catch(e => {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al crear el registro. ' + e, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      eliminarNivel (nivel) {
        this.localLoading = true
        this.axios.delete('presupuesto/niveles/' + nivel)
          .then(res => {
            this.localLoading = false
            this.obtenerNivelesYArbol()
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e, color: 'danger'})
            this.localLoading = false
          })
      },
      formReset () {
        this.nivel = {
          nombre: null,
          digitos: null
        }
        this.concepto = {
          tipo_rubro: null,
          codigo: null,
          nombre: null,
          pr_concepto_id: '',
          pr_estructura_id: 1,
          pr_nivel_id: this.nivelId
        }
      },
      openDialog () {
        this.dialogConcepto = true
        this.codigosUsados = this.getCodigosUsados()
      },
      getCodigosUsados () {
        if (this.arbol) {
          return this.arbol.map(children => {
            let index = children.codigo.lastIndexOf('.') + 1
            let codigo = children.codigo.substring(index)
            if (!(this.concepto.codigo != null && this.concepto.codigo === codigo)) return codigo
          })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      close () {
        this.dialog = false
        this.formReset()
      },
      closeConcepto () {
        this.dialogConcepto = false
        this.formReset()
      }
    }
  }
</script>

<style scoped>
  .v-btn--floating.v-btn--small {
    height: 35px;
    width: 35px;
  }
</style>
