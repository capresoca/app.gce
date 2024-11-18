<template>
  <div>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Codigo del grupo de empleados</v-card-title>
        <v-card-text>El codigo del grupo de empleados ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de grupo de empleados' : 'Registro de grupo de empleados' }} </v-toolbar-title>
      </v-toolbar>

      <v-card-text>
        <v-form data-vv-scope="formRegistroGruposEmpleados">
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
                                      v-model="grupo.codigo"
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
                                      v-model="grupo.nombre"
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
                    <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Detalle</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-layout row wrap>

                      <v-flex xs12 sm3>
                        <v-select
                          v-model="grupo.liquidar_nomina"
                          :items="['Mensual','Quincenal','Semanal']"
                          label="Liquidar nomina"
                        ></v-select>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-dialog
                          ref="pickerUltimaNomina"
                          v-model="pickerUltimaNomina"
                          :return-value.sync="grupo.ultima_nomina"
                          persistent
                          lazy
                          full-width
                          width="290px">
                          <v-text-field
                            slot="activator"
                            v-model="grupo.ultima_nomina"
                            label="Ultima nomina"
                            prepend-icon="event"
                            key="ultimaNomina"
                            :disabled="ordenEdit"
                            readonly>
                          </v-text-field>
                          <v-date-picker v-model="grupo.ultima_nomina" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="pickerUltimaNomina = false">Cancelar</v-btn>
                            <v-btn flat color="primary" @click="$refs.pickerUltimaNomina.save(grupo.ultima_nomina)">OK</v-btn>
                          </v-date-picker>
                        </v-dialog>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-dialog
                          ref="pickerProximaNomina"
                          v-model="pickerProximaNomina"
                          :return-value.sync="grupo.proxima_nomina"
                          persistent
                          lazy
                          full-width
                          width="290px">
                          <v-text-field
                            slot="activator"
                            v-model="grupo.proxima_nomina"
                            label="Proxima nomina"
                            prepend-icon="event"
                            key="ultimaNomina"
                            :disabled="ordenEdit"
                            readonly>
                          </v-text-field>
                          <v-date-picker v-model="grupo.proxima_nomina" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="pickerProximaNomina = false">Cancelar</v-btn>
                            <v-btn flat color="primary" @click="$refs.pickerProximaNomina.save(grupo.proxima_nomina)">OK</v-btn>
                          </v-date-picker>
                        </v-dialog>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-text-field label="Periodo"
                                      v-model="grupo.periodo"
                                      name="Nombre"
                                      prepend-icon="calendar_today"
                                      required>
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm3>
                        Grupo de retirados
                        <v-radio-group row
                                       v-model="grupo.retirados">
                          <v-radio
                            key="retiradosSi"
                            label="Si"
                            value="Si"
                          ></v-radio>
                          <v-radio
                            key="retiradosNo"
                            label="No"
                            value="No"
                          ></v-radio>
                        </v-radio-group>
                      </v-flex>

                      <v-flex xs12 sm3>
                        Evaluar meses de
                        <v-radio-group row
                          v-model="grupo.evaluar_meses">
                          <v-radio
                            key="evaluarMesesDias"
                            label="30 Días"
                            value="30 Días"
                          ></v-radio>
                          <v-radio
                            key="evaluarMesesCalendario"
                            label="Días Calendario"
                            value="Días Calendario"
                          ></v-radio>
                        </v-radio-group>
                      </v-flex>

                      <v-flex xs12 sm3>
                        Maneja provisiones
                        <v-radio-group row
                          v-model="grupo.maneja_provisiones">
                          <v-radio
                            key="manejaProvisionesSi"
                            label="Si"
                            value="Si"
                          ></v-radio>
                          <v-radio
                            key="manejaProvisionesNo"
                            label="No"
                            value="No"
                          ></v-radio>
                        </v-radio-group>
                      </v-flex>

                      <v-flex xs12 sm3>
                        Bloqueo de grupo
                        <v-radio-group row
                          v-model="grupo.bloqueo_grupos">
                          <v-radio
                            key="bloqueoGrupoSi"
                            label="Si"
                            value="Si"
                          ></v-radio>
                          <v-radio
                            key="bloqueoGrupoNo"
                            label="No"
                            value="No"
                          ></v-radio>
                        </v-radio-group>
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
  </div>
</template>
<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {GRUPO_EMPLEADOS_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroGruposEmpleados',
    props: ['parametros'],
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        pickerUltimaNomina: '',
        pickerProximaNomina: '',
        loadingSubmit: false,
        grupo: {}
      }
    },
    methods: {
      findByCodigo () {
        if (this.grupo.codigo) {
          this.buscandoCodigo = true
          this.axios.get('th_grupos_empleados/codigo/' + this.grupo.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del grupo de empleados existe', color: 'success'})
                this.formReset()
                this.grupo = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroGruposEmpleados')) {
          this.loadingSubmit = true
          if (this.grupo.id) {
            this.axios.put('th_grupos_empleados/' + this.grupo.id, this.grupo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grupo de empleados se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(GRUPO_EMPLEADOS_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('th_grupos_empleados', this.grupo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grupo de empleados se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(GRUPO_EMPLEADOS_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
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
        this.grupo = {}
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
        this.axios.get('th_grupos_empleados/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el grupo. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.grupo = item
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
