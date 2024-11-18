<template>
    <v-dialog :value="value" persistent max-width="920px">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="headline">Nueva Cuenta</span>
            <!--<span class="body-1 grey&#45;&#45;text text&#45;&#45;darken-1">{{padre.codigo +' '+ padre.nombre }}</span>-->
          </v-card-title>
          <v-divider></v-divider>
          <v-form data-vv-scope="formNewCuenta">
            <v-container fluid  grid-list-md>
              <v-layout row wrap>
                <v-flex xs12 sm12 md5>
                  <v-select
                    :items="registros"
                    v-model="formCuenta.clase"
                    required
                    item-value="id"
                    item-text="nombre"
                    :hint="formCuenta.clase ? 'Código: ' + formCuenta.clase.codigo : '' "
                    persistent-hint
                    v-validate="'required'"
                    label="Clase"
                    key="clase"
                    name="clase"
                    :error-messages="errors.collect('clase')"
                    return-object
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"/>
                          <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, no se presentan registros de la clases. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-select>
                </v-flex>
                <v-flex xs12 sm12 md7>
                  <v-select
                    :items="formCuenta.clase ? formCuenta.clase.children : []"
                    :disabled="formCuenta.clase ? false : true"
                    v-model="formCuenta.grupo"
                    required
                    item-value="id"
                    item-text="nombre"
                    :hint="formCuenta.grupo ? 'Código: ' + formCuenta.grupo.codigo : '' "
                    persistent-hint
                    v-validate="'required'"
                    label="Grupo"
                    key="grupo"
                    name="grupo"
                    :error-messages="errors.collect('grupo')"
                    return-object
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"/>
                          <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, la clase no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-select>
                </v-flex>
                <v-flex xs12 sm12 md6>
                  <v-select
                    :items="formCuenta.grupo ? formCuenta.grupo.children : []"
                    v-model="formCuenta.nCuenta"
                    :disabled="formCuenta.grupo ? false : true"
                    required
                    item-value="id"
                    item-text="nombre"
                    :hint="formCuenta.nCuenta ? 'Código: ' + formCuenta.nCuenta.codigo : '' "
                    persistent-hint
                    v-validate="'required'"
                    label="Cuenta"
                    key="cuenta"
                    name="cuenta"
                    :error-messages="errors.collect('cuenta')"
                    return-object
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"/>
                          <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, el grupo no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-select>
                </v-flex>
                <v-flex xs12 sm12 md6>
                  <v-select
                    :items="formCuenta.nCuenta ? formCuenta.nCuenta.children : []"
                    v-model="formCuenta.subcuenta"
                    :disabled="formCuenta.nCuenta ? false : true"
                    required
                    item-value="id"
                    item-text="nombre"
                    :hint="formCuenta.subcuenta ? 'Código: ' + formCuenta.subcuenta.codigo : '' "
                    persistent-hint
                    v-validate="'required'"
                    label="Subcuenta"
                    key="subcuenta"
                    name="subcuenta"
                    :error-messages="errors.collect('subcuenta')"
                    return-object
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"/>
                          <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, la cuenta no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-select>
                </v-flex>
                <v-flex xs12 sm12 md12 v-if="formCuenta.subcuenta != null ? formCuenta.subcuenta.children != '' : null">
                  <v-layout row wrap>
                    <v-flex xs9 sm9 md9>
                      <v-select
                        :items="formCuenta.subcuenta ? formCuenta.subcuenta.children : []"
                        v-model="formCuenta.nauxiliar"
                        :disabled="!formCuenta.subcuenta || auxiliar != false ? true : false"
                        required
                        item-value="id"
                        item-text="nombre"
                        :hint="formCuenta.nauxiliar ? 'Código: ' + formCuenta.nauxiliar.codigo : '' "
                        persistent-hint
                        v-validate="'required'"
                        label="Auxiliar"
                        key="auxiliar"
                        name="auxiliar"
                        :error-messages="errors.collect('auxiliar')"
                        return-object
                      >
                        <template slot="item" slot-scope="data">
                          <template>
                            <v-list-tile-content>
                              <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"/>
                              <!--<v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>-->
                            </v-list-tile-content>
                          </template>
                        </template>
                        <template slot="no-data" class="error">
                          <div class="pa-1 pl-2 text--white">
                            Lo sentimos, la cuenta no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                          </div>
                        </template>
                      </v-select>
                    </v-flex>
                    <v-flex xs3 sm3 md3>
                      <v-switch  color="success" label="Nuevo Auxiliar" :disabled="formCuenta.subcuenta ? false : true"
                                 v-model="auxiliar"></v-switch>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <template v-if="formCuenta.subcuenta != null ? (formCuenta.subcuenta.children == '' || auxiliar != false || formCuenta.nauxiliar != null) : null">
                  <v-card width="100%" class="mt-1">
                    <v-toolbar class="elevation-0 toolbar--dense">
                      <v-toolbar-title>{{ subtituloForm }} </v-toolbar-title>
                      <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-container fluid grid-list-md>
                      <v-layout row wrap>
                        <v-flex xs6 sm6 md2 lg2 class="pr-0">
                          <!--:prefix="formCuenta.nf_nivcuenta_id === 5 ? (formCuenta.subcuenta ? formCuenta.subcuenta.codigo : '') : (formCuenta.nauxiliar ? formCuenta.nauxiliar.codigo : '')"-->
                          <v-text-field
                            :value="formCuenta.nf_nivcuenta_id === 5 ? (formCuenta.subcuenta ? formCuenta.subcuenta.codigo : '') : (formCuenta.nauxiliar ? formCuenta.nauxiliar.codigo : '')"
                            label="Código Padre"
                            disabled>
                          </v-text-field>
                        </v-flex>
                        <v-flex xs6 sm6 md3 lg3 class="pl-0">
                          <!--<v-layout align-center>-->
                          <!--<v-text-field label="Código Padre" disabled :value="formCuenta.nf_nivcuenta_id === 5 ? (formCuenta.subcuenta ? formCuenta.subcuenta.codigo : '') : (formCuenta.nauxiliar ? formCuenta.nauxiliar.codigo : '')"></v-text-field>-->
                          <v-text-field
                            v-model="codeDigi"
                            name="codigo"
                            label="Código"
                            v-validate="'required|length:' + nPadre.digitos + '|not_in:' + codsUsados"
                            :maxlength="nPadre.digitos"
                            key="codigo"
                            required :error-messages="errors.collect('codigo')">
                          </v-text-field>
                          <v-text-field
                            v-model="formCuenta.codigo" v-if="formCuenta.id < 0">
                          </v-text-field>
                          <!--</v-layout>-->
                          <!--<small>{{errors.collect('codigo')}}</small>-->
                        </v-flex>
                        <v-flex xs12 sm6 md7 lg7>
                          <v-text-field
                            v-model="formCuenta.nombre"
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
                            :items="complements.tiposniif"
                            v-model="formCuenta.tipo"
                            label="Tipo"
                            :error-messages="errors.collect('tipo')"
                            key="tipo"
                            name="tipo">
                          </v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4 v-if="formCuenta.tipo === 'Retencion'">
                          <v-select
                            :items="complements.tiposreteniif"
                            v-model="formCuenta.tipo_retencion"
                            label="Tipo Retención"
                            :error-messages="errors.collect('tipo retención')"
                            key="tipo retención"
                            name="tipo retención">
                          </v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4 v-if="esDian">
                          <v-text-field
                            label="Tercero"
                            disabled
                            :value="complements.dian.razon_social"
                          >
                          </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md4 lg4 v-if="formCuenta.tipo_retencion === 'Retención ICA'">
                          <v-text-field
                            label="Tercero ente Territorial"
                            disabled
                            required
                            v-validate="'required'"
                            :value="complements.alcaldia.razon_social">
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4>
                          <v-select
                            :items="complements.anexosdeclaracion"
                            item-value="id"
                            item-text="nombre"
                            v-model="formCuenta.nf_anedeclaracione_id"
                            label="Anexo declaración"
                            required
                            v-validate="'required'"
                            name="anexo declaración"
                            key="anexo declaración"
                            :error-messages="errors.collect('anexo declaración')"
                          ></v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4>
                          <v-select
                            :items="disponibilidades"
                            item-value="id"
                            item-text="nombre"
                            v-model="formCuenta.disponibilidad"
                            label="Disponibilidad"
                            required
                            v-validate="'required'"
                            name="disponibilidad"
                            key="disponibilidad"
                            :error-messages="errors.collect('disponibilidad')"
                          ></v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4>
                          <v-switch  color="accent" label="Maneja tercero"
                                     v-model="formCuenta.maneja_tercero"  true-value="1" false-value="0"></v-switch>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4>
                          <v-switch  color="accent" label="Maneja Centro de Costo"
                                     v-model="formCuenta.maneja_ccosto"  true-value="1" false-value="0"></v-switch>
                        </v-flex>
                        <v-flex xs12 sm6 md4 lg4>
                          <v-switch  color="success" :label="formCuenta.estado" :readonly="nPadre.auxiliar === 0"
                                     v-model="formCuenta.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card>
                </template>

              </v-layout>
              <div class="text-xs-right">
                <small>*Si no existe el registro para los campos clase, grupo, cuenta y subcuenta. Por favor, reportar con contabilidad </small>
              </div>
            </v-container>
          </v-form>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
            <v-btn flat color="primary" @click="guardaNiif()">Guardar</v-btn>
            <!--<v-btn @click="save" color="blue darken-1" type="submit" flat>Guardar</v-btn>-->
          </v-card-actions>
        </v-card>
      </v-dialog>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroNiif',
    props: ['value', 'cue'],
    data () {
      return {
        tab: null,
        auxiliar: false,
        cuentaCreada: null,
        registros: [],
        formCuenta: {},
        clases: [],
        disponibilidades: ['Corriente', 'No Corriente'],
        niveles: null,
        nPadre: Object,
        codsUsados: '',
        codeDigi: '', // código derivado
        complements: null
      }
    },
    beforeCreate () {
      this.axios.get('niifs/puc').then(res => {
        this.registros = res.data.data
        // console.log(this.registros)
      }).catch(e => {
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
      })
    },
    created () {
      this.getComplements()
      // this.cue = this.formCuenta
    },
    watch: {
      value (val) {
        if (!val) {
          this.formReset()
        }
      },
      'formCuenta.clase' (val) {
        this.formCuenta.grupo = null
        this.formCuenta.nf_clascuenta_id = val ? val.codigo : null
      },
      'formCuenta.grupo' (val) {
        this.formCuenta.nCuenta = null
      },
      'formCuenta.nCuenta' (val) {
        this.formCuenta.subcuenta = null
      },
      'formCuenta.subcuenta' (val) {
        if (val) {
          this.formCuenta.nauxiliar = null
          this.formCuenta.nf_padre_id = val.id
          this.formCuenta.nf_nivcuenta_id = val.nf_nivcuenta_id + 1
          this.nPadre = this.complements.nivelescuentas.find(nivel => nivel.id === this.formCuenta.nf_nivcuenta_id)
          this.codsUsados = this.getCodUsados(val.children)
          this.auxiliar = !val.children.length
        }
      },
      'formCuenta.nauxiliar' (val) {
        if (val) {
          this.formCuenta.nf_padre_id = val.id
          this.formCuenta.nf_nivcuenta_id = val.nf_nivcuenta_id + 1
          this.nPadre = this.complements.nivelescuentas.find(nivel => nivel.id === this.formCuenta.nf_nivcuenta_id)
          this.codsUsados = this.getCodUsados(val.children)
        }
      },
      'auxiliar' (val) {
        if (val !== false) {
          this.formCuenta.nauxiliar = null
          this.formCuenta.nf_padre_id = this.formCuenta.subcuenta.id
          this.formCuenta.nf_nivcuenta_id = this.formCuenta.subcuenta.nf_nivcuenta_id + 1
          this.nPadre = this.complements.nivelescuentas.find(nivel => nivel.id === this.formCuenta.nf_nivcuenta_id)
          this.codsUsados = this.getCodUsados(this.formCuenta.subcuenta.children)
        }
      },
      'formCuenta.tipo' (val) {
        if (val !== 'Retencion') this.formCuenta.tipo_retencion = null
      },
      'codeDigi' (val) {
        if (this.formCuenta.nf_nivcuenta_id === 5 && this.auxiliar !== false) {
          this.formCuenta.codigo = (this.formCuenta.subcuenta ? this.formCuenta.subcuenta.codigo : '') + val
        } else {
          this.formCuenta.codigo = (this.formCuenta.nauxiliar ? this.formCuenta.nauxiliar.codigo : '') + val
        }
      }
    },
    computed: {
      subtituloForm () {
        return this.auxiliar ? 'Nuevo Auxiliar' : 'Nuevo Subauxiliar'
      },
      esDian () {
        return (this.formCuenta.tipo_retencion === 'Retención IVA' ||
          this.formCuenta.tipo_retencion === 'Retención en la fuente' ||
          this.formCuenta.tipo_retencion === 'Otras') &&
          this.formCuenta.tipo === 'Retencion'
      }
    },
    beforeMount () {
      this.formReset()
      if (this.cue != null) this.formCuenta = this.cue
    },
    methods: {
      formReset () {
        this.formCuenta = {
          clase: null,
          grupo: null,
          nCuenta: null,
          subcuenta: null,
          nauxiliar: null,
          // db
          id: null,
          codigo: null,
          nombre: null,
          nf_nivcuenta_id: null,
          nf_clascuenta_id: null,
          tipo: null,
          tipo_retencion: null,
          nf_anedeclaracione_id: null,
          nf_padre_id: null,
          maneja_tercero: '0',
          maneja_ccosto: '0',
          estado: 'Activo',
          disponibilidad: 'Corriente'
        }
        this.$validator.reset()
        this.codeDigi = ''
      },
      getCodUsados (val) {
        let _this = this
        return val.map(cod => { if (!(this.codeDigi != null && this.codeDigi === cod.codigo)) return cod.codigo.slice(-1 * _this.nPadre.digitos) })
      },
      getComplements () {
        this.axios.get('niifs/complementos')
          .then((response) => {
            this.complements = response.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardaNiif (val) {
        if (await this.validador('formNewCuenta')) {
          var send = !this.formCuenta.id ? this.axios.post('niifs', this.formCuenta) : this.axios.put('niifs/' + this.formCuenta.id, this.formCuenta)
          send.then(response => {
            if (this.formCuenta.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La cuenta se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La cuenta se creó correctamente', color: 'success'})
            }
            this.formCuenta = response.data.data
            this.$emit('crear', this.formCuenta)
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          console.log('Existen campos sin completar.')
        }
      },
      codPadre (val) {
        if (this.formCuenta.nf_nivcuenta_id === 5) {
          this.formCuenta.subcuenta.codigo.toString()
        } else {
          this.formCuenta.nauxiliar.codigo.toString()
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
