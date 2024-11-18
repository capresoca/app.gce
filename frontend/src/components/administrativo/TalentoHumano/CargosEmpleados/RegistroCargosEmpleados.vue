<template>
  <div>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de cargo de empleados' : 'Registro de cargo de empleados' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form data-vv-scope="formRegistroCargosEmpleados">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>

              <v-flex xs12 class="pb-4">
                <v-card>
                  <v-toolbar dense class="grey lighten-4 elevation-0">
                    <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-layout row wrap>

                      <v-flex xs12 sm4>
                        <v-text-field label="Código"
                                      v-model="cargo.codigo"
                                      key="codigo"
                                      v-validate="'required|max:3'"
                                      name="Codigo"
                                      prepend-icon="list"
                                      :error-messages="errors.collect('Codigo')"
                                      :disabled="ordenEdit"
                                      @keyup.enter="findByCodigo"
                                      @blur="findByCodigo"
                                      persistent-hint
                                      :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                                      required>
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm8>
                        <v-text-field label="Nombre"
                                      v-model="cargo.nombre"
                                      key="nombre"
                                      v-validate="'required'"
                                      name="Nombre"
                                      prepend-icon="person"
                                      :error-messages="errors.collect('Nombre')"
                                      required>
                        </v-text-field>
                      </v-flex>

                    </v-layout>

                  </v-card-text>
                </v-card>
              </v-flex>

              <v-flex xs12 class="pb-4">
                <v-card>
                  <v-toolbar dense class="grey lighten-4 elevation-0">
                    <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Cargo</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-layout row wrap>

                      <v-flex xs12 sm4>
                        <postulador
                          nodata="Busqueda por codigo o por nombre."
                          required
                          hint="codigo"
                          itemtext="nombre"
                          datatitle="nombre"
                          datasubtitle="codigo"
                          filter="nombre,codigo"
                          label="Nivel de cargo"
                          scope="formRegistroCargosEmpleados"
                          lite
                          ref="postulaNivelCargo"
                          entidad="th_niveles_cargos"
                          preicon="face"
                          @change="val => cargo.th_niveles_cargo_id = val"
                          @objectReturn="val => cargo.nivel = val"
                          :value="cargo.nivel"
                          clearable
                        />
                      </v-flex>

                      <v-flex xs12 sm4>
                        <v-text-field label="Cargos aprobados"
                                      v-model="cargo.cargos_aprobados">
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm4>
                        <v-text-field label="Tope maximo de H.E. Mensual"
                                      v-model="cargo.tope_maximo">
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm2>
                        <v-checkbox
                          v-model="cargo.horas_extra"
                          label="Horas extra"
                          value="true"
                          hide-details
                        ></v-checkbox>
                      </v-flex>

                      <v-flex xs12 sm2>
                        <v-checkbox
                          v-model="cargo.recargos"
                          label="Recargos nocturnos y dominicales"
                          value="true"
                          hide-details
                        ></v-checkbox>
                      </v-flex>
                    </v-layout>

                  </v-card-text>
                </v-card>
              </v-flex>

            </v-layout>

          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions v-if="estado !== 'Confirmada' && estado !== 'Anulada'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Codigo del fondo</v-card-title>
        <v-card-text>El codigo del cargo de empleado ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {CARGOS_EMPLEADOS_TH_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroCargosEmpleadosTalentoHumano',
    props: ['parametros'],
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        loadingSubmit: false,
        cargo: {}
      }
    },
    methods: {
      findByCodigo () {
        if (this.cargo.codigo) {
          this.buscandoCodigo = true
          this.axios.get('th_cargos_empleados/codigo/' + this.cargo.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del cargo de empleado existe', color: 'success'})
                this.formReset()
                this.cargo = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              console.log(e)
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al consultar el codigo. ' + e.response, color: 'danger'})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroCargosEmpleados')) {
          this.loadingSubmit = true
          if (this.cargo.id) {
            this.axios.put('th_cargos_empleados/' + this.cargo.id, this.cargo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El cargo de empleado se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CARGOS_EMPLEADOS_TH_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('th_cargos_empleados', this.cargo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El cargo de empleado se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CARGOS_EMPLEADOS_TH_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      seleccionTercero (val) {
        if (val !== null) {
          this.cargo.tercero = val
          this.cargo.nombre = val.nombre_completo
          this.cargo.gn_tercero_id = val.id
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
        this.cargo = {
          tipo_fondo: []
        }
        this.buscandoCodigo = false
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      getRegistro (id) {
        this.axios.get('th_cargos_empleados/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el subgrupo. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.cargo = item
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
