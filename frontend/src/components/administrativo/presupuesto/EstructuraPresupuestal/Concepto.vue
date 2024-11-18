<template>
  <div>
    <loading v-model="localLoading" />
    <v-dialog v-model="dialogConcepto" width="500px">
      <v-form data-vv-scope="formConceptos">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar {{ niveles[0] ? niveles[0].nombre : ''}}</span>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap>

              <v-flex xs12 sm12>
                <v-text-field v-model="concepto.prefijo"
                              label="Prefijo" key="prefijo" disabled
                              name="prefijo" v-validate="'required'" required
                              :error-messages="errors.collect('prefijo')">

                </v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field v-model="codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|digits:' + valorMaximo + '|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')">

                </v-text-field>
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required'" required
                              :error-messages="errors.collect('nombre')">

                </v-text-field>
              </v-flex>

              <v-flex xs12>
                <v-subheader class="pa-0 ma-0">Auxiliar</v-subheader>
                <v-switch class="ma-0 pa-0" color="success" v-model="auxiliar"  :true-value="1" :false-value="0"></v-switch>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="crearConcepto" :loading="loadingSubmit" :disabled="errors.any()">Crear</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <li>
      <div
        :class="{bold: isFolder}">
      <span @click="toggle"  class="item" v-if="isFolder">
        <v-icon v-if="open">keyboard_arrow_down</v-icon>
        <v-icon v-else>keyboard_arrow_right</v-icon>
      </span>
        <span v-else >
        <v-icon color="white">keyboard_arrow_down</v-icon>
      </span>
        <span>{{model.codigo}} {{model.nombre}}</span>
        <v-tooltip top>
          <v-icon
            v-if="model.nivel_n < ultimoNivel"
            small
            @click="openDialog(model)"
            slot="activator"
            class="pa-0 ma-0 alignleft"
            color="accent"
          >
            add
          </v-icon>
          <span>Agregar</span>
        </v-tooltip>
        <v-tooltip top>
          <v-icon
            v-if="!model.has_children"
            small
            @click="editarConcepto(model)"
            slot="activator"
            class="pa-0 ma-0 alignleft"
            color="accent"
          >
            edit
          </v-icon>
          <span>Editar</span>
        </v-tooltip>
        <v-tooltip top>
          <v-icon
            v-if="!model.has_children"
            small
            @click="eliminarConcepto(model.id)"
            slot="activator"
            class="pa-0 ma-0 alignleft"
            color="accent"
          >
            delete
          </v-icon>
          <span>Eliminar</span>
        </v-tooltip>

      </div>
      <ul v-if="open">
        <concepto
          v-for="(model, index) in model.children"
          @conceptoEliminado="removerConcepto(index)"
          @arbolModificado="emitir()"
          :key="index"
          :model="model"
          :ultimoNivel="ultimoNivel"
          :nivelesConcepto="nivelesConcepto"
          :expandir="hijoExpandir"
          ref="childConcepto">
        </concepto>
      </ul>
    </li>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  export default {
    name: 'Concepto',
    components: {Loading},
    props: {
      model: {
        children: Object
      },
      ultimoNivel: null,
      nivelesConcepto: null,
      last: 0,
      expandir: null
    },
    data: function () {
      return {
        open: true,
        padre: Object,
        dialogConcepto: false,
        niveles: [],
        arbol: [],
        concepto: {
          pr_estructura_id: 1
        },
        localLoading: false,
        loadingSubmit: false,
        valorMaximo: null,
        codigosUsados: [],
        codigo: null,
        nombre: null,
        auxiliar: '0',
        hijoExpandir: true
      }
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    watch: {
      'expandir' (value) {
        this.hijoExpandir = value
        this.open = value
      },
      'hijoExpandir' (value) {
        this.open = value
      }
    },
    computed: {
      isFolder: function () {
        return this.model.children &&
          this.model.children.length
      },
      canChild: function () {
        return this.last !== this.model.nf_nivcuenta_id
      }
    },
    methods: {
      async crearConcepto () {
        if (await this.validador('formConceptos')) {
          this.loadingSubmit = true
          this.concepto.codigo = this.concepto.prefijo + '.' + this.codigo
          this.concepto.nombre = this.nombre
          this.concepto.auxiliar = this.auxiliar
          let send = !this.concepto.id ? this.axios.post('presupuesto/conceptos', this.concepto) : this.axios.put('presupuesto/conceptos/' + this.concepto.id, this.concepto)
          send.then(res => {
            if (!this.concepto.id) {
              if (!res.data.data.children) res.data.data.children = []
              this.model.children.push(res.data.data)
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
            }

            this.loadingSubmit = false
            this.$emit('arbolModificado')
            this.close()
          })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al crear el concepto. ' + e, color: 'danger'})
              this.loadingSubmit = false
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      editarConcepto (concepto) {
        this.dialogConcepto = true
        this.concepto = concepto

        let index = concepto.codigo.lastIndexOf('.') + 1
        this.concepto.prefijo = this.concepto.codigo.substring(0, index - 1)
        this.codigo = this.concepto.codigo.substring(index)
        this.nombre = concepto.nombre
        this.auxiliar = concepto.auxiliar

        this.codigosUsados = this.getCodigosUsados()

        let nivel = this.nivelesConcepto.find(nivel => nivel.id === concepto.pr_nivel_id)
        this.valorMaximo = nivel.digitos
      },
      eliminarConcepto (id) {
        this.localLoading = true
        this.axios.delete('presupuesto/conceptos/' + id)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'Item eliminado satisfactoriamente', color: 'success'})
            this.localLoading = false
            this.$emit('conceptoEliminado')
            this.$emit('arbolModificado')
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el concepto. ' + e, color: 'danger'})
            this.localLoading = false
          })
      },
      removerConcepto (index) {
        this.model.children.splice(index, 1)
      },
      emitir () {
        this.$emit('arbolModificado')
      },
      openDialog (concepto) {
        this.codigosUsados = this.getCodigosUsados()
        this.dialogConcepto = true
        this.concepto.pr_concepto_id = concepto.id
        this.concepto.pr_nivel_id = concepto.pr_nivel_id
        this.concepto.prefijo = concepto.codigo
        this.concepto.tipo_rubro = concepto.tipo_rubro

        let index = this.nivelesConcepto.findIndex(nivel => nivel.id === concepto.pr_nivel_id)
        let nivel = this.nivelesConcepto[index + 1]
        this.valorMaximo = nivel.digitos
      },
      formReset () {
        this.codigo = null
        this.nombre = null
        this.auxiliar = '0'
        this.concepto = {
          prefijo: null,
          codigo: null,
          nombre: null,
          pr_concepto_id: ''
        }
      },
      getCodigosUsados () {
        if (this.model.children) {
          return this.model.children.map(children => {
            let index = children.codigo.lastIndexOf('.') + 1
            let codigo = children.codigo.substring(index)
            if (!(this.concepto.codigo != null && this.concepto.codigo === codigo)) return codigo
          })
        }
      },
      close () {
        this.dialogConcepto = false
        this.formReset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      toggle: function () {
        if (this.isFolder) {
          this.open = !this.open
        }
      },
      test () {
        console.log('Test desde concepto')
      },
      addChild: function () {
        this.$emit('addcuenta', this)
      },
      sendParent: function (parent) {
        this.$emit('addcuenta', parent)
      },
      editCuenta: function () {
        this.axios.get('niifs/' + this.model.nf_padre_id)
          .then((response) => {
            this.padre = response.data.data
            this.$emit('editcuenta', this)
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      sendToEdit: function (parent) {
        this.$emit('editcuenta', parent)
      }
    }
  }
</script>

<style scoped>
  .item {
    cursor: pointer;
  }
  .add-cuenta {
    cursor: pointer;
  }
  .bold {
    font-weight: bold;
  }
  ul {
    padding-left: 1em;
    line-height: 1.5em;
  }
  li{
    list-style: none;
  }
</style>

