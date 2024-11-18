<template>
  <div>
    <v-card class="transparent elevation-0">
      <v-card-text>
        <v-form data-vv-scope="formRegistroEntidadResolucion">
          <toolbar-list :title="toolTitle" subtitle="Entidad" class="elevation-1 grey lighten-4"></toolbar-list>
          <v-container fluid grid-list-xl class="pa-1">
            <v-layout row wrap>
              <v-flex xs12 sm12 md4 class="pa-2">
                <v-card>
                  <v-container fluid>
                    <v-layout row wrap>
                      <v-flex xs12 sm4>
                        <v-text-field
                          label="Vigencia"
                          v-model="entidad.periodo"
                          key="vigencia"
                          maxlength="4"
                          v-validate="'required|length:4|not_in:' + vigenciasRegistradas"
                          name="vigencia"
                          :error-messages="errors.collect('vigencia')"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm8>
                        <v-text-field label="Código"
                                      v-model="entidad.codigo"
                                      key="codigo"
                                      v-validate="'required|max:16|not_in:' + codigosUsados"
                                      name="codigo"
                                      :error-messages="errors.collect('codigo')"
                                      :disabled="ordenEdit">
                        </v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
              <v-flex xs12 sm12 md8 class="pa-2">
                <v-card>
                  <v-container fluid>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12>
                        <postulador-v2
                          no-data="Busqueda por código o nombre."
                          hint="identificacion_completa"
                          item-text="nombre_completo"
                          data-title="identificacion_completa"
                          data-subtitle="nombre_completo"
                          label="Tercero"
                          entidad="terceros"
                          preicon="person"
                          @changeid="val => entidad.gn_tercero_id = val"
                          v-model="entidad.tercero"
                          name="nit"
                          no-btn-create
                          rules="required"
                          v-validate="'required'"
                          :error-messages="errors.collect('nit')"
                          clearable
                          :min-characters-search="2"
                        />
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
              <v-flex xs12 class="pa-2">
                <loading v-model="loading"></loading>
                <v-slide-y-transition>
                  <v-card v-if="entidad.tercero">
                    <v-container fluid>
                      <v-layout row wrap>
                        <v-flex xs12 sm12 md8 lg8>
                          <v-text-field label="Nombre"
                                        v-model="entidad.nombre"
                                        key="nombre"
                                        v-validate="'required'"
                                        name="Nombre"
                                        :error-messages="errors.collect('Nombre')"
                                        required>
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md4 lg4>
                          <v-text-field label="Ciudad" :value="entidad.tercero ? (entidad.tercero.municipio ? entidad.tercero.municipio.nombre + ' - ' + entidad.tercero.municipio.nombre_departamento : null) : null" readonly></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12>
                          <v-text-field label="Resolucion"
                                        v-model="entidad.resolucion"
                                        key="resolucion"
                                        v-validate="'required'"
                                        name="Resolucion"
                                        :error-messages="errors.collect('Resolucion')"
                                        required>
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12>
                          <v-text-field label="Valor"
                                        v-model="entidad.valor"
                                        key="Valor"
                                        v-validate="'required'"
                                        name="Valor"
                                        prefix="$"
                                        :error-messages="errors.collect('Valor')"
                                        required>
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md6 lg6>
                          <v-text-field label="Direccion" :value="entidad.tercero ? entidad.tercero.direccion : null" readonly></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md2 lg2>
                          <v-text-field label="Telefono" :value="entidad.tercero ? entidad.tercero.telefono_fijo : null" readonly></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md4 lg4>
                          <v-text-field label="E-Mail" :value="entidad.tercero ? entidad.tercero.correo_electronico : null" readonly></v-text-field>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card>
                </v-slide-y-transition>
              </v-flex>
              <v-flex xs12 class="pa-2">
                <v-card>
                  <v-container fluid>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-text-field label="N° documento representante legal"
                                      v-model="entidad.identificacion_rep_legal"
                                      key="docRepresLegal"
                                      v-validate="'required'"
                                      name="N° documento representante legal"
                                      :error-messages="errors.collect('N° documento representante legal')"
                                      required>
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md7 lg7>
                        <v-text-field label="Nombre del representante legal"
                                      v-model="entidad.representante_legal"
                                      key="nombreRepLegal"
                                      v-validate="'required'"
                                      name="Nombre del representante legal"
                                      :error-messages="errors.collect('Nombre del representante legal')"
                                      required>
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-text-field label="N° documento jefe de presupuesto"
                                      v-model="entidad.identificacion_jefe_pr"
                                      key="docJefePresupuesto"
                                      v-validate="'required'"
                                      name="N° documento jefe de presupuesto"
                                      :error-messages="errors.collect('N° documento jefe de presupuesto')"
                                      required>
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md7 lg7>
                        <v-text-field label="Nombre del jefe de presupuesto"
                                      v-model="entidad.jefe_presupuesto"
                                      key="nombreJefePresupuesto"
                                      v-validate="'required'"
                                      name="Nombre del jefe de presupuesto"
                                      :error-messages="errors.collect('Nombre del jefe de presupuesto')"
                                      required>
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-text-field label="N° documento jefe financiero"
                                      v-model="entidad.identificacion_jefe_financiero"
                                      key="docJefeFinaciero"
                                      v-validate="'required'"
                                      name="N° documento jefe financiero"
                                      :error-messages="errors.collect('N° documento jefe financiero')"
                                      required>
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md7 lg7>
                        <v-text-field label="Nombre del jefe financiero"
                                      v-model="entidad.jefe_financiero"
                                      key="nombreJefeFinanciero"
                                      v-validate="'required'"
                                      name="Nombre del jefe financiero"
                                      :error-messages="errors.collect('Nombre del jefe financiero')"
                                      required>
                        </v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12 class="text-xs-right pr-3">
            <v-speed-dial left bottom>
              <v-tooltip slot="activator" left>
                <v-btn slot="activator" @click="submit"
                       color="blue darken-2" dark fab>
                  <v-icon>save</v-icon>
                  <!--&nbsp;Acciones-->
                </v-btn>
                <span>Guardar</span>
              </v-tooltip>
            </v-speed-dial>
            <!--<v-btn-->
              <!--color="primary"-->
              <!--@click="submit"-->
            <!--&gt;-->
              <!--Guardar-->
            <!--</v-btn>-->
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo de la entidad/resolucion</v-card-title>
        <v-card-text>El codigo de la entidad/resolucion ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {ENTIDAD_RESOLUCION_PR_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroEntidadResolucion',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading: () => import('@/components/general/Loading')
    },
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        entidad: {},
        resoluciones: [],
        loading: false,
        codigosUsados: '',
        vigenciasRegistradas: ''
      }
    },
    watch: {
      'entidad.tercero' (val) {
        // console.log('tercero >', val)
        if (val) {
          this.loading = true
          this.axios.get('empleado/tercero/' + val.id)
            .then((response) => {
              this.loading = false
              if (response.data.exists) {
                this.procesarTercero(response.data.tercero)
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
              }
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      }
    },
    methods: {
      getCodigosUsados () {
        return this.resoluciones.map(cod => { if (!(this.entidad.codigo != null && this.entidad.codigo === cod.codigo)) return cod.codigo })
      },
      getVigenciaRegistrada () {
        return this.resoluciones.map(cod => { if (!(this.entidad.periodo != null && this.entidad.periodo === cod.periodo)) return cod.periodo })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroEntidadResolucion')) {
          let typeRequest = this.entidad.id ? 'editar' : 'crear'
          var send = !this.entidad.id ? this.axios.post('pr_entidad_resolucion', this.entidad) : this.axios.put('pr_entidad_resolucion/' + this.entidad.id, this.entidad)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.entidad.id
                ? 'actualizaron los datos'
                : 'realizó el registro'} de la Entidad/Resolución | Código: ${response.data.data.codigo}.`,
              color: 'success'
            })
            this.formReset()
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            this.$store.commit(ENTIDAD_RESOLUCION_PR_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
          }).catch(e => {
            // console.log(e)
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.ordenEdit = false
        this.dialogCodigo = false
      },
      formReset () {
        this.entidad = {
          periodo: null,
          codigo: null,
          gn_tercero_id: null,
          nombre: null,
          resolucion: null,
          valor: null,
          identificacion_rep_legal: null,
          representante_legal: null,
          identificacion_jefe_pr: null,
          jefe_presupuesto: null,
          identificacion_jefe_financiero: null,
          jefe_financiero: null,
          // Object
          tercero: null
        }
        this.buscandoCodigo = false
        this.codigosUsados = this.getCodigosUsados()
        this.vigenciasRegistradas = this.getVigenciaRegistrada()
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      seleccionTercero (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.entidad.tercero.id) {
              this.axios.get('empleado/tercero/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    this.procesarTercero(response.data.tercero)
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        }
      },
      procesarTercero (val) {
        // console.log('procesar Tercero', val)
        this.entidad.tercero.municipio = !val.municipio ? null : val.municipio
        this.entidad.nombre = val ? val.nombre_completo : null
      },
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('pr_entidad_resolucion/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
            loader.hide()
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el subgrupo. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.entidad = item
        this.codigosUsados = this.getCodigosUsados()
        this.vigenciasRegistradas = this.getVigenciaRegistrada()
      }
    },
    computed: {
      toolTitle () {
        return `${this.ordenEdit ? 'Edición' : 'Registro'} de Entidad/Resolución`
      }
    },
    beforeCreate () {
      this.axios.get('pr_entidad_resolucion').then(res => {
        this.resoluciones = res.data.data
        this.codigosUsados = this.getCodigosUsados()
        this.vigenciasRegistradas = this.getVigenciaRegistrada()
      }).catch(e => {
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
      })
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue registrado.'
          },
          vigencia: {
            not_in: 'Ya se encuentra registrada la vigencia.'
          }
        }
      }

      this.$validator.localize('es', dict)
    }
  }
</script>
