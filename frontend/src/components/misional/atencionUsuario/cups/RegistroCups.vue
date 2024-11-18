<template>
  <div>
    <v-dialog v-model="dialogRango" width="720px">
      <v-form data-vv-scope="formRelacionados">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Rango</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm6>
                <v-text-field v-model="rangoInicio"
                              label="Inicio" key="inicio"
                              name="inicio" ></v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field v-model="rangoFin"
                              label="Fin" key="fin"
                              name="fin" ></v-text-field>
              </v-flex>


            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeDialog">Cancelar</v-btn>
            <v-btn color="primary" @click="agregarRango" :disabled="errors.any()">Agregar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogConfirm" persistent max-width="290">
      <v-card>
        <v-card-title class="headline grey lighten-3">Alerta</v-card-title>
        <v-card-text>¿Está seguro que desea eliminar este registro?</v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="dialogConfirm = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click.native="eliminarRango">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formCup">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formCups">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="cup.codigo"
                                          label="Código" key="codigo"
                                          name="codigo" v-validate="'required|not_in:' + codigosUsados" required
                                          :error-messages="errors.collect('codigo')" :disabled="cup.id ? true : false"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm8>
                            <v-text-field v-model="cup.descripcion"
                                          label="Descripción" key="descripcion"
                                          name="descripcion" v-validate="'required'" required
                                          :error-messages="errors.collect('descripcion')">

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Categoría"
                              v-model="cup.rs_cupscategoria_id"
                              @change="val => cup.rs_cupscategoria_id = val ? val.id: null"
                              :items="cups_grupos"
                              item-value="id"
                              item-text="nombre"
                              name="categoria"
                              persistent-hint
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('categoria')"
                              return-object
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.codigo"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>


                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.generos"
                              v-model="cup.genero"
                              name="genero"
                              label="Género"
                              :error-messages="errors.collect('genero')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.ambitos"
                              v-model="cup.ambito"
                              name="ambito"
                              label="Ámbito"
                              :error-messages="errors.collect('ambito')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.estancias"
                              v-model="cup.estancia"
                              name="estancia"
                              label="Estancia"
                              :error-messages="errors.collect('estancia')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.frecuenciaUso"
                              v-model="cup.frecuencia_uso"
                              name="frecuencia de uso"
                              label="Frecuencia de uso"
                              :error-messages="errors.collect('frecuencia de uso')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.nivelAutorizacion"
                              v-model="cup.nivel_autorizacion"
                              name="nivel de autorizacion"
                              label="Nivel de autorización"
                              :error-messages="errors.collect('nivel de autorizacion')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>
                          <v-flex xs12 sm6>
                            <postulador-v2
                              no-data="Busqueda por código o nombre."
                              hint="codigo"
                              item-text="nombre"
                              data-title="nombre"
                              data-subtitle="codigo"
                              label="SOAT"
                              entidad="cm_mansoats"
                              preicon="subtitles"
                              @changeid="val => cup.cm_mansoat_id = val"
                              v-model="cup.soat"
                              name="soat"
                              no-btn-create
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('soat')"
                              clearable
                              :min-characters-search="2"
                            />
                          </v-flex>
                          <v-flex xs12 sm6>
                            <postulador-v2
                              no-data="Busqueda por código, nombre o referencia"
                              hint="codigo"
                              item-text="nombre"
                              data-title="nombre"
                              data-subtitle="codigo"
                              label="ISS"
                              entidad="cm_manisss"
                              preicon="list_alt"
                              @changeid="val => cup.cm_maniss_id = val"
                              v-model="cup.maniss"
                              name="iss"
                              no-btn-create
                            />
<!--                            rules="required"-->
<!--                            v-validate="'required'"-->
<!--                            :error-messages="errors.collect('iss')"-->
                          </v-flex>
                          <v-flex xs12 sm2>
                            <v-subheader class="pa-0 ma-0">POS</v-subheader>
                            <v-switch class="ma-0 pa-0"  color="accent"
                                      v-model="cup.pos"  true-value="Si" false-value="No"></v-switch>
                          </v-flex>
                          <v-flex xs12 sm2>
                            <v-subheader class="pa-0 ma-0">Requiere Permiso</v-subheader>
                            <v-switch class="ma-0 pa-0"  color="accent"
                                      v-model="cup.sw_requiere_permiso_especial"  :true-value="1" :false-value="0"></v-switch>
                          </v-flex>

                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>settings</v-icon> Configuración validador</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.coberturas"
                              v-model="cup.cobertura"
                              name="cobertura"
                              label="Cobertura"
                              :error-messages="errors.collect('cobertura')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.duplicados"
                              v-model="cup.duplicado"
                              name="duplicado"
                              label="Duplicado"
                              :error-messages="errors.collect('duplicado')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementos_cups.vidas"
                              v-model="cup.vida"
                              name="vida"
                              label="Vida"
                              :error-messages="errors.collect('vida')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>
                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 sm12 md12>
                    <template>
                      <v-card>
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading"><v-icon>import_contacts</v-icon> Diagnósticos Relacionados</v-toolbar-title>
                          <v-spacer></v-spacer>
                          <v-btn small color="primary" @click.prevent="dialogRango = true"><v-icon>add</v-icon> Agregar item</v-btn>
                        </v-toolbar>
                        <v-divider></v-divider>

                        <v-divider></v-divider>
                        <v-data-table :headers="headers"
                                      :items="rangosDiagnosticos"
                                      :loading="tableLoading"
                                      hide-actions>
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item[0] }}</td>
                            <td>{{ props.item[1] }}</td>
                            <td>
                              <v-speed-dial
                                v-model="props.item.show"
                                direction="left"
                                open-on-hover
                                transition="slide-x-transition"
                              >
                                <v-btn
                                  slot="activator"
                                  v-model="props.item.show"
                                  color="blue darken-2"
                                  dark
                                  fab
                                  small
                                >
                                  <v-icon>chevron_left</v-icon>
                                  <v-icon>close</v-icon>
                                </v-btn>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="editarRango(props.index)"
                                  >
                                    <v-icon color="accent">mode_edit</v-icon>
                                  </v-btn>
                                  <span>Editar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="cargarRangoAEliminar(props.index)"
                                  >
                                    <v-icon color="accent">delete</v-icon>
                                  </v-btn>
                                  <span>Eliminar</span>
                                </v-tooltip>

                              </v-speed-dial>
                            </td>
                          </template>
                          <template slot="no-data" v-if="cup.id">
                            <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
                              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                            </v-alert>
                            <v-alert v-else :value="true" color="info" icon="info">
                              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                            </v-alert>
                          </template>
                          <template v-else slot="no-data">
                            <v-alert  :value="true" color="info" icon="info">
                              No has agregado ningún diagnóstico relacionado.
                            </v-alert>
                          </template>
                          <template slot="pageText" slot-scope="props">
                            {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                          </template>
                        </v-data-table>
                      </v-card>
                    </template>
                  </v-flex>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CUPS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  export default {
    name: 'RegistroCups',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: ['parametros'],
    data () {
      return {
        cups: [],
        cup: {},
        complementos_cups: {
          generos: [
            'Masculino',
            'Femenino',
            'Sin restricción'
          ],
          ambitos: [
            'Ambulatorio',
            'Hospitalario',
            'Urgencias',
            'Domiciliario',
            'Sin restricción'
          ],
          estancias: [
            'El procedimiento debe tener asociado un numero de dias de estanca mayor a cero',
            'Sin restricción'
          ],
          coberturas: [
            'Hace parte del Plan de Beneficios',
            'No hace parte del Plan de Beneficios',
            'EXCLUSION',
            'RIESGOS LABORALES',
            'SALUD PUBLICA'
          ],
          duplicados: [
            'Procedimento que se realiza una vez en el año',
            'Procedimento que se realiza una vez en el día',
            'Sin restricción'
          ],
          vidas: [
            'Procedimento que  se realiza una vez en la vida',
            'Sin restricción'
          ],
          frecuenciaUso: [
            'Diario',
            'Mensual',
            'Anual',
            'Vida'
          ],
          nivelAutorizacion: [
            'Asesor',
            'Coordinador',
            'Medico auditor',
            'Gerencia'
          ]
        },
        headers: [
          {
            text: 'Inicio',
            align: 'left',
            sortable: false,
            value: 'inicio'
          },
          {
            text: 'Fin',
            align: 'left',
            sortable: false,
            value: 'fin'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        rangosDiagnosticos: [],
        cups_grupos: [],
        pos: ['Si', 'No'],
        codigosUsados: '',
        loadingSubmit: false,
        dialogRango: false,
        dialogConfirm: false,
        tableLoading: false,
        rangoInicio: '',
        rangoFin: '',
        rangoEditado: null,
        cargoAEliminar: null
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
    },
    beforeCreate () {
      this.axios.get('cups_grupos')
        .then((res) => {
          this.cups_grupos = res.data
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })

      this.axios.get('cups')
        .then((res) => {
          this.cups = res.data.data
          this.codigosUsados = this.getCodigosUsados()
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      tapTitulo () {
        return !this.cup.id ? 'Nuevo Item' : 'Edición de : ' + this.cup.codigo
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formCup.$el
        })
        this.axios.get('cups/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.cup = response.data.data
              this.getRangosDiagnosticos()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la CUP. ' + e, color: 'danger'})
          })
      },
      getRangosDiagnosticos () {
        if (this.cup.cie10_relacionados) {
          let rangosPlanos = this.cup.cie10_relacionados.split(',')
          this.rangosDiagnosticos = rangosPlanos.map((rango) => rango.split('-'))
        }
      },
      editarRango (id) {
        this.rangoEditado = id
        this.rangoInicio = this.rangosDiagnosticos[id][0]
        this.rangoFin = this.rangosDiagnosticos[id][1]
        this.dialogRango = true
      },
      agregarRango () {
        if (this.rangoEditado || this.rangoEditado === 0) {
          this.rangosDiagnosticos.splice(this.rangoEditado, 1, [this.rangoInicio, this.rangoFin])
          this.rangoEditado = null
          this.closeDialog()
        } else {
          this.rangosDiagnosticos.push([this.rangoInicio, this.rangoFin])
          this.closeDialog()
        }
      },
      cargarRangoAEliminar (detalle) {
        this.dialogConfirm = true
        this.cargoAEliminar = detalle
      },
      eliminarRango () {
        this.rangosDiagnosticos.splice(this.cargoAEliminar, 1)
        this.dialogConfirm = false
      },
      convertirArrayARangos () {
        let rangosEnUnArray = this.rangosDiagnosticos.map((rango) => rango.join('-'))
        this.cup.cie10_relacionados = rangosEnUnArray.join()
      },
      getCodigosUsados () {
        return this.cups.map(cod => { if (!(this.cups.codigo != null && this.cups.codigo === cod.codigo)) return cod.codigo })
      },
      formReset () {
        this.cup = {
          id: '',
          rs_cupscategoria_id: null,
          cm_mansoat_id: null,
          cm_maniss_id: null,
          codigo: null,
          descripcion: null,
          genero: null,
          ambito: null,
          estancia: null,
          cobertura: null,
          duplicado: null,
          vida: null,
          frecuencia_uso: null,
          nivel_autorizacion: null,
          pos: 'No',
          sw_requiere_permiso_especial: 0,
          cie10_relacionados: null,
          soat: null,
          maniss: null
        }
        this.$validator.reset()
      },

      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      closeDialog () {
        this.rangoInicio = ''
        this.rangoFin = ''
        this.dialogRango = false
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        if (await this.validador('formCups')) {
          this.convertirArrayARangos()
          this.loadingSubmit = true
          const typeRequest = this.cup.id ? 'editar' : 'crear'
          let send = !this.cup.id ? this.axios.post('cups', this.cup) : this.axios.put('cups/' + this.cup.id, this.cup)
          send.then(response => {
            if (this.cup.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(CUPS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(CUPS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
