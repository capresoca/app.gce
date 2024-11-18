<template>
  <div>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de fondo' : 'Registro de fondo' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form data-vv-scope="formRegistroFondos">
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
                        <v-text-field
                          label="Código"
                          v-model="fondo.codigo"
                          prepend-icon="list"
                          key="codigo"
                          v-validate="'required|max:3'"
                          name="Codigo"
                          :error-messages="errors.collect('Codigo')"
                          :disabled="ordenEdit"
                          @keyup.enter="findByCodigo"
                          @blur="findByCodigo"
                          persistent-hint
                          :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                          required>
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm4>
                        <postulador
                          nodata="Busqueda por nombre o número de documento."
                          required
                          hint="nombre_completo"
                          itemtext="identificacion_completa"
                          datatitle="identificacion_completa"
                          datasubtitle="nombre_completo"
                          filter="identificacion_completa,nombre_completo"
                          label="Tercero"
                          scope="formRegistroFondos"
                          lite
                          ref="postulaTercero"
                          entidad="terceros"
                          preicon="face"
                          @change="val => fondo.gn_tercero_id = val"
                          @objectReturn="val => seleccionTercero(val)"
                          :value="fondo.tercero"
                          clearable
                        />
                      </v-flex>

                      <v-flex xs12 sm4>
                        <v-text-field
                          label="Nombre"
                          v-model="fondo.nombre"
                          key="nombre"
                          v-validate="'required'"
                          name="Nombre"
                          prepend-icon="person"
                          :error-messages="errors.collect('Nombre')"
                          required>
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm4>
                        <v-select
                          v-model="fondo.tipo_fondo"
                          :items="tiposFondo"
                          label="Tipos de fondo"
                          multiple
                          chips
                        ></v-select>
                      </v-flex>

                      <v-flex xs12 sm4>
                        <v-subheader class="subheader">Clase</v-subheader>
                        <v-radio-group row
                          v-model="fondo.tipo">
                          <v-radio
                            key="privado"
                            label="Privado"
                            value="Privado"
                          ></v-radio>
                          <v-radio
                            key="publico"
                            label="Publico"
                            value="Publico"
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
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del fondo</v-card-title>
        <v-card-text>El codigo del fondo ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {FONDOS_TH_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroFondosTalentoHumano',
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
        tiposFondo: ['Cesantias', 'Salud', 'Pensión', 'Riesgos', 'I.S.S', 'Privado', 'Público'],
        fondo: {
          tipo_fondo: []
        }
      }
    },
    methods: {
      findByCodigo () {
        if (this.fondo.codigo) {
          this.buscandoCodigo = true
          this.axios.get('th_fondos/codigo/' + this.fondo.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del fondo existe', color: 'success'})
                this.formReset()
                this.fondo = response.data.data
                this.pasarArrya(response.data.data.tipo_fondo)
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
        if (await this.validador('formRegistroFondos')) {
          this.loadingSubmit = true
          let tipoString = ''
          for (var i = 0; i < this.fondo.tipo_fondo.length; i++) {
            if (i === (this.fondo.tipo_fondo.length - 1)) {
              tipoString = tipoString + this.fondo.tipo_fondo[i]
            } else {
              tipoString = tipoString + this.fondo.tipo_fondo[i] + ','
            }
          }
          this.fondo.tipo_fondo = tipoString
          if (this.fondo.id) {
            this.axios.put('th_fondos/' + this.fondo.id, this.fondo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El fondo se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(FONDOS_TH_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('th_fondos', this.fondo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El fondo se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(FONDOS_TH_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
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
          this.fondo.tercero = val
          this.fondo.nombre = val.nombre_completo
          this.fondo.gn_tercero_id = val.id
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
      pasarArrya (data) {
        this.fondo.tipo_fondo = data.split(',')
      },
      formReset () {
        this.fondo = {
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
        this.axios.get('th_fondos/' + id)
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
        this.fondo = item
        this.pasarArrya(item.tipo_fondo)
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
<style>
  .subheader {
    height: 10px;

  }
</style>
