<template>
  <div>
    <v-dialog class="dense" v-model="dialogImpresionTwo" width="500px">
      <v-card class="">
        <v-card-text class="grey lighten-3 elevation-0">
          <span class="title" v-text="'Generar Reporte Plan de Cuentas'"></span>
        </v-card-text>
        <v-container fluid grid-list-xl class="elevation-0">
          <v-layout row wrap>
            <v-flex xs12 class="pb-0" style="padding-left: 10mm !important;">
              <v-radio-group label="Tipo de Archivo" v-model="file" row class="mt-0">
                <v-radio label="PDF" color="red" value="application/pdf"></v-radio>
                <v-radio label="CSV" color="success" value="application/xls"></v-radio>
              </v-radio-group>
            </v-flex>
            <v-flex xs12 sm6 class="text-xs-center">
              <v-btn medium color="primary" @click="imprimirReporte(1)" v-text="'Generar Completo'"></v-btn>
            </v-flex>
            <v-flex xs12 sm6 class="text-xs-center">
              <v-btn medium color="green" class="white--text" @click="openDialogTwo" v-text="'Generar por Rango'"></v-btn>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn color="black lighten-3" flat medium @click.prevent="dialogImpresionTwo = false" v-text="'Cancelar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogImpresion" width="450px">
      <v-card>
        <v-card-title class="grey lighten-3">
          <span class="title" v-text="'Ingrese el primer y último código de cuenta que presentara en el reporte'"></span>
        </v-card-title>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm6>
              <v-text-field
                v-model="codigoA"
                prepend-icon="label_important"
                label="Primer Código"
                single-line
                key="primer código"
                name="primer código"
                :error-messages="errors.collect('primer código')"
                v-validate="'required'"
                hide-details
                flat
              >
              </v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field
                v-model="codigoB"
                prepend-icon="label_important"
                label="Último Código"
                single-line
                key="último código"
                name="último código"
                :error-messages="errors.collect('último código')"
                v-validate="'required'"
                hide-details
                flat
              >
              </v-text-field>
            </v-flex>
            <v-flex xs12>
              <div class="text-xs-right">
                <small>*Ejemplo: Primer código: 1, Último código: 197508 </small>
              </div>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn color="black lighten-3" flat medium @click.prevent="dialogImpresion = false" v-text="'Cancelar'"></v-btn>
          <v-btn medium flat color="primary" @click="imprimirReporte(2)" v-text="'Generar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card class="">
      <v-toolbar sm12 class="elevation-0 dense">
        <v-icon>format_list_bulleted</v-icon>
        <v-toolbar-title>Plan Unico de Cuentas</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-tooltip top disabled>
          <v-btn fab small slot="activator" disabled @click.prevent="openDialogClass">
            <v-icon color="accent" v-text="'add'"></v-icon>
          </v-btn>
          <span v-text="'Agregar Clase'"></span>
        </v-tooltip>
        <v-tooltip top>
          <v-btn fab small slot="activator" @click.prevent="dialogImpresionTwo = true">
            <v-icon color="black" v-text="'far fa-file-pdf'"></v-icon>
          </v-btn>
          <span v-text="'Imprimir Reporte'"></span>
        </v-tooltip>
      </v-toolbar>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar por código de cuenta"
          single-line=""
          hide-details
          flat
          type="number"
          @input="buscar"
        >
        </v-text-field>
      </v-card-title>
      <v-card-text>
        <v-progress-circular v-if="loading" :size="50" indeterminate color="primary"></v-progress-circular>
        <cuenta
          v-else
          v-for="cuenta in puc"
          :key="cuenta.id"
          :model="cuenta"
          :last="getLast"
          @editcuenta="openToEdit"
          @addcuenta="openDialog">
        </cuenta>
        <v-alert v-if="!puc.length && !loading" :value="true" color="error" icon="warning">
          No existen cuentas con este código
        </v-alert>
      </v-card-text>
      <v-dialog v-model="dialog" :max-width="dialogClase ? '400px' : '720px'">
        <v-card v-if="dialog">
          <v-card-title class="grey lighten-3">
            <div>
              <div class="headline" v-text="'Nueva' + (!dialogClase ? ' Cuenta' : ' Clase')"></div>
              <span v-if="!dialogClase" class="body-1 grey--text text--darken-1">{{padre.codigo +' '+ padre.nombre }}</span>
            </div>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-form data-vv-scope="planCuentas">
              <v-container v-if="dialogClase" grid-list-md>
                <v-layout row wrap>
                  <v-flex xs12>
                    <v-text-field
                      label="Código"
                      v-model="newCuenta.codigo"
                      v-validate="'required'"
                      key="código"
                      name="código"
                      :error-messages="errors.collect('código')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      label="Nombre"
                      v-model="newCuenta.nombre"
                      v-validate="'required'"
                      key="nombre"
                      name="nombre"
                      :error-messages="errors.collect('nombre')"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
              <v-container v-else grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm6 md4>
                    <v-text-field
                      label="Clase"
                      hint="Clase de la cuenta padre"
                      persistent-hint
                      disabled
                      :value="clasePadre.codigo +' '+ clasePadre.nombre"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field
                      label="Nivel"
                      hint="Nivel de la cuenta"
                      persistent-hint
                      disabled
                      v-model="newCuenta.nivel.codigo"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field
                      label="Código Padre"
                      disabled
                      :value="padre.codigo">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field
                      v-model="codigoHijo"
                      name="codigo"
                      label="Código"
                      v-validate="'required|length:' + newCuenta.nivel.digitos +  '|not_in:' + codigosUsados"
                      key="codigo"
                      :maxlength="newCuenta.nivel.digitos"
                      required
                      :disabled="!!newCuenta.id"
                      :error-messages="errors.collect('codigo')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field
                      name="código completo"
                      label="Código completo"
                      disabled
                      v-model="newCuenta.codigo"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-select
                      :items="complementos.tiposniif"
                      v-model="newCuenta.tipo"
                      label="Tipo"
                      single-line
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="newCuenta.tipo === 'Retencion'">
                    <v-select
                      :items="complementos.tiposreteniif"
                      v-model="newCuenta.tipo_retencion"
                      label="Tipo Retención"
                      single-line
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="isDian">
                    <v-text-field
                      label="Tercero"
                      disabled
                      :value="complementos.dian.razon_social"
                    >
                    </v-text-field>
                  </v-flex>

                  <v-flex xs12 sm6 md4  v-if="newCuenta.tipo_retencion === 'Retención ICA'">
                    <v-text-field
                      label="Tercero ente Territorial"
                      disabled
                      required
                      v-validate="'required'"
                      :value="complementos.alcaldia.razon_social">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs12 sm6 md4>
                    <v-select
                      :items="complementos.anexosdeclaracion"
                      item-value="id"
                      item-text="nombre"
                      v-model="newCuenta.nf_anedeclaracione_id"
                      label="Anexo declaración"
                      single-line
                    ></v-select>
                  </v-flex>

                  <v-flex xs12 sm6 md8>
                    <v-text-field
                      v-model="newCuenta.nombre"
                      name="nombre"
                      label="Nombre"
                      v-validate="'required'"
                      key="nombre"
                      required
                      :error-messages="errors.collect('nombre')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4 lg4>
                    <v-select
                      :items="disponibilidades"
                      item-value="id"
                      item-text="nombre"
                      v-model="newCuenta.disponibilidad"
                      label="Disponibilidad"
                      required
                      v-validate="'required'"
                      name="disponibilidad"
                      key="disponibilidad"
                      :error-messages="errors.collect('disponibilidad')"
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-switch  color="accent" label="Maneja tercero"
                               v-model="newCuenta.maneja_tercero"  :true-value="1" :false-value="0"></v-switch>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-switch  color="accent" label="Maneja Centro de Costo"
                               v-model="newCuenta.maneja_ccosto"  :true-value="1" :false-value="0"></v-switch>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-form>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="close">Cancelar</v-btn>
            <v-btn @click="save" color="blue darken-1" type="submit" flat>Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <registro-niif :value="dialogRegistro"></registro-niif>
    </v-card>
  </div>
</template>

<script>
  import Cuenta from './Cuenta'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Puc',
    components: {
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Cuenta
    },
    props: ['parametros'],
    data () {
      return {
        search: '',
        puc: [],
        dialogRegistro: false,
        anedeclaraciones: [],
        loading: false,
        dialog: false,
        dialogImpresion: false,
        dialogClase: false,
        dialogImpresionTwo: false,
        codigoA: null,
        codigoB: null,
        file: null,
        complementos: Object,
        clasePadre: Object,
        nivelPadre: Object,
        disponibilidades: ['Corriente', 'No Corriente'],
        codigoHijo: '',
        padre: Object,
        cogigosUsados: '',
        newCuenta: {
          codigo: ''
        }
      }
    },
    created () {
      this.getPuc()
      this.getComplementos()
    },
    computed: {
      isDian () {
        return (this.newCuenta.tipo_retencion === 'Retención IVA' ||
          this.newCuenta.tipo_retencion === 'Retención en la fuente' ||
          this.newCuenta.tipo_retencion === 'Otras') &&
          this.newCuenta.tipo === 'Retencion'
      },
      getLast () {
        if (this.complementos.nivelescuentas) {
          return this.complementos.nivelescuentas[this.complementos.nivelescuentas.length - 1].id
        }
        return 50
      }
    },
    methods: {
      openDialogClass () {
        this.dialogClase = !this.dialogClase
      },
      openDialogTwo () {
        if (this.file !== null) {
          this.dialogImpresion = true
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado el tipo de archivo.', color: 'danger'})
        }
      },
      imprimirReporte (item) {
        if (item === 2) {
          this.$validator.validateAll().then(result => {
            if (result && this.codigoA !== null && this.codigoB !== null && this.file !== null) {
              this.enviar(item)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Los campos se encuentran vacíos.', color: 'danger'})
            }
          })
        } else {
          if (this.file !== null) {
            this.enviar(item)
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha seleccionado el tipo de archivo.', color: 'danger'})
          }
        }
      },
      enviar (item) {
        this.axios.get(`firmar-ruta?nombre_ruta=reporte-nf-niifs${item === 1 ? '&all=' + item : ('&codigoA=' + this.codigoA + '&codigoB=' + this.codigoB)}&file=${this.file}`)
          .then(res => {
            this.dialogImpresion = false
            this.dialogImpresionTwo = false
            let url = res.data
            if (this.file === 'application/pdf') {
              window.open(url)
            } else {
              window.fileDownload(url)
            }
            this.file = null
            // let win = window.open(url, '_blank')
            // win.focus()
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Existe un error al generar el reporte.', color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        if (this.search === '') {
          this.getPuc()
        } else {
          this.getNiif()
        }
      }, 500),
      getNiif () {
        this.loading = true
        this.axios.get('niifs?' + 'codigo=' + this.search + '&descendencia=1')
          .then((response) => {
            this.puc = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      getPuc () {
        this.loading = true
        this.axios.get('niifs/puc' + this.search)
          .then((response) => {
            this.puc = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      openDialog (cuenta) {
        this.getComplementos()
        this.getComplementoModal(cuenta)
        this.newCuenta.nf_padre_id = cuenta.model.id
        let nivel = this.complementos.nivelescuentas.find(nivel => nivel.codigo === this.nivelPadre.codigo + 1)
        this.newCuenta.nivel = nivel
        this.newCuenta.nf_nivcuenta_id = nivel.id
        this.padre = cuenta.model
        this.codigosUsados = this.getCodigosUsados()
        this.dialog = true
        cuenta.open = true
      },
      openToEdit (cuenta) {
        this.getComplementos()
        this.getComplementoModal(cuenta)
        this.newCuenta.nf_padre_id = cuenta.padre.id
        let nivel = this.complementos.nivelescuentas.find(nivel => nivel.codigo === this.nivelPadre.codigo)
        this.padre = cuenta.padre
        this.newCuenta = cuenta.model
        this.newCuenta.nivel = nivel
        this.newCuenta.nf_nivcuenta_id = nivel.id
        this.codigosUsados = this.getCodigosUsados()
        this.dialog = true
      },
      getComplementoModal (cuenta) {
        this.newCuenta.nf_clascuenta_id = cuenta.model.nf_clascuenta_id
        this.clasePadre = this.complementos.clasescuentas.find(clase => clase.id === cuenta.model.nf_clascuenta_id)
        this.nivelPadre = this.complementos.nivelescuentas.find(nivel => nivel.id === cuenta.model.nf_nivcuenta_id)
      },
      getComplementos () {
        this.axios.get('niifs/complementos')
          .then((response) => {
            this.complementos = response.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      getCodigosUsados () {
        let _this = this
        let codigos = this.padre.children.map(children => {
          return children.codigo.slice(-1 * _this.nivelPadre.digitos)
        })
        return codigos
      },
      save () {
        this.$validator.validateAll('planCuentas').then(result => {
          if (result) {
            var send = !this.newCuenta.id ? this.axios.post('niifs', this.newCuenta) : this.axios.put('niifs/' + this.newCuenta.id, this.newCuenta)
            send.then(response => {
              if (this.newCuenta.id) {
                this.$store.commit(SNACK_SHOW, {msg: 'La cuenta se actualizó correctamente', color: 'success'})
                this.padre = response.data.data
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'La cuenta se creó correctamente', color: 'success'})
                this.padre.children.push(response.data.data)
              }
              this.close()
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
          }
        })
      },
      formReset () {
        this.newCuenta = {
          codigo: ''
        }
        this.codigoHijo = ''
      },
      close () {
        this.dialogClase = false
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      }
    },
    watch: {
      codigoHijo (val) {
        this.newCuenta.codigo = this.padre.codigo + val
      },
      'dialogImpresion' (value) {
        if (value === true) {
          this.dialogImpresionTwo = false
        } else {
          this.codigoA = null
          this.codigoB = null
          // this.file = null
          this.$validator.reset()
        }
      },
      'dialogClase' (val) {
        if (val) {
          this.dialog = true
        }
      }
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue usado'
          }
        }
      }

      this.$validator.localize('es', dict)
    }
  }
</script>

<style scoped>

</style>
