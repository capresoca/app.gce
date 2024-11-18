<template>
  <div>
  	<v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
       	<v-card>
       		<v-card-title class="grey lighten-3 elevation-0">
	    		<span class="title">Migrar Entidad</span>
	    	</v-card-title>
	    	<v-container fluid grid-list-md>
	    		<v-layout row wrap>
	    			<v-flex xs12 sm12 md12>
			            <v-text-field
					          label="Nit Actual"
					          name="Nit Actual"
					          v-model="nitActual"
					          readonly
					          v-validate="'required'"
					          :error-messages="errors.collect('Nit Actual')"
					        />
			          </v-flex>
			          <v-flex xs12 sm12 md12>
			            <v-autocomplete
				          label="Entidades"
				          v-model="idNuevo"
				          :items="itemsEntidades"
				          item-value="id"
				          item-text="nombre"
				          name="Entidades"
				          required
				          v-validate="'required'"
				          :error-messages="errors.collect('Entidades')"
				        />
			          </v-flex>
	    		</v-layout>
	    	</v-container>
	    	<v-card-actions class="grey lighten-4">
	            <v-spacer></v-spacer>
	            <v-btn @click="close">Cancelar</v-btn>
	            <v-btn color="primary" @click="migrarEntidad">Migrar Entidad</v-btn>
	          </v-card-actions>
       	</v-card>
      </v-form>
  </v-dialog>
	  <v-container
	    class="pa-0 ma-0"
	    fluid
	    grid-list-xl
	  >
	    <v-slide-x-reverse-transition mode="out-in">
	      <v-speed-dial
	        class="ml-1"
	        key="btn1"
	        v-model="fabMenu"
	        left
	        direction="bottom"
	        open-on-hover
	        fixed
	        v-if="floatMenu"
	      >
	        <v-tooltip right slot="activator">
	          <v-btn
	            class="mt-1"
	            slot="activator"
	            v-model="fabMenu"
	            color="blue darken-2"
	            dark
	            fab
	            @click="floatMenu = false"
	          >
	            <v-icon>vertical_split</v-icon>
	          </v-btn>
	          <span>Activar menú vertical</span>
	        </v-tooltip>
	        <v-tooltip v-for="(item, index) in items" right :key="index" v-if="window !== index">
	          <v-btn
	            slot="activator"
	            fab
	            dark
	            small
	            :color="item.avatarColor"
	            @click="window = index"
	            :class="window === index ? 'elevation-5' : ''"
	          >
	            <v-icon>{{item.icon}}</v-icon>
	          </v-btn>
	          <span>{{item.title}}</span>
	        </v-tooltip>
	      </v-speed-dial>
	    </v-slide-x-reverse-transition>
	    <v-toolbar :dense="!entidad.id">
	      <v-tooltip top class="mr-2" v-if="entidad.id && !floatMenu">
	        <v-btn
	          slot="activator"
	          icon
	          @click="floatMenu = true"
	          >
	          <v-icon color="accent">group_work</v-icon>
	        </v-btn>
	        <span>activar menú flotante</span>
	      </v-tooltip>
	      <v-icon>account_balance</v-icon>
	      <v-toolbar-title v-if="!entidad.id">Nueva entidad</v-toolbar-title>
	      <v-list-tile v-else>
	        <v-list-tile-content>
	          <v-list-tile-title v-if="entidad.tercero" v-html="entidad.tercero.nombre_completo"></v-list-tile-title>
	          <v-list-tile-sub-title v-html="'Código de habilitación: ' + entidad.cod_habilitacion"></v-list-tile-sub-title>
	        </v-list-tile-content>
	      </v-list-tile>
	    </v-toolbar>
	    <loading v-model="loading" />
	    <v-layout row wrap>
	      <v-slide-x-reverse-transition>
	        <v-flex sm12 md3 v-if="entidad.id && !floatMenu">
	          <v-card class="content-list-p0">
	            <v-list two-line>
	              <template v-for="(item, index) in items">
	                <v-list-tile @click="window = index" :key="item.text" :class="window === index ? 'grey lighten-2' : ''">
	                  <v-list-tile-avatar :color="item.avatarColor">
	                    <v-icon dark>{{ item.icon }}</v-icon>
	                  </v-list-tile-avatar>
	                  <v-list-tile-content>
	                    <v-list-tile-title>
	                      {{ item.title }}
	                    </v-list-tile-title>
	                    <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
	                  </v-list-tile-content>
	                </v-list-tile>
	                <v-divider inset/>
	              </template>
	            </v-list>
	          </v-card>
	        </v-flex>
	      </v-slide-x-reverse-transition>
	      <v-flex :md9="entidad.id && !floatMenu">
	        <v-window
	          v-model="window"
	          class="elevation-1"
	          vertical
	        >
	          <v-window-item
	            v-for="(item, n) in items"
	            :key="n"
	            lazy
	          >
	            <v-card flat class="content-list-p0 pt-0">
	              <v-list v-if="entidad.id" two-line>
	                <template>
	                  <v-list-tile>
	                    <v-list-tile-avatar :color="item.avatarColor">
	                      <v-icon dark>{{ item.icon }}</v-icon>
	                    </v-list-tile-avatar>
	                    <v-list-tile-content>
	                      <v-list-tile-title>
	                        {{ item.title }}
	                      </v-list-tile-title>
	                      <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
	                    </v-list-tile-content>
	                    <v-list-tile-action style="width: 120px !important;" v-if="n > 1 && n < 5">
	                      <v-layout align-center justify-space-around row fill-height>
	                        <v-tooltip left>
	                          <v-btn
	                            slot="activator"
	                            icon
	                            large
	                            @click="reloadSection(n)"
	                          >
	                            <v-icon
	                              color="primary"
	                              large
	                            >
	                              cached
	                            </v-icon>
	                          </v-btn>
	                          <span>Recargar sección</span>
	                        </v-tooltip>
	                        <v-divider vertical />
	                        <v-tooltip left>
	                          <v-btn
	                            slot="activator"
	                            icon
	                            :href="axios.defaults.baseURL + '/../files/Plantilla_importacion_' + (n === 2 ? 'cups' : n === 3 ? 'cums' : 'otros') + '_entidad.xlsx'"
	                          >
	                            <v-icon color="blue-grey">archive</v-icon>
	                          </v-btn>
	                          <span>Descargar plantilla</span>
	                        </v-tooltip>
	                        <v-tooltip left>
	                          <v-btn
	                            slot="activator"
	                            icon
	                            @click="cargarArchivo(n)"
	                          >
	                            <v-icon color="blue-grey">publish</v-icon>
	                          </v-btn>
	                          <span>Carga masiva</span>
	                        </v-tooltip>
	                      </v-layout>
	                    </v-list-tile-action>
	                    <v-list-tile-action v-else>
	                      <v-tooltip left>
	                        <v-btn
	                          slot="activator"
	                          icon
	                          large
	                          @click="reloadSection(n)"
	                        >
	                          <v-icon
	                            color="primary"
	                            large
	                          >
	                            cached
	                          </v-icon>
	                        </v-btn>
	                        <span>Recargar sección</span>
	                      </v-tooltip>
	                    </v-list-tile-action>
	                  </v-list-tile>
	                  <v-spacer></v-spacer>
	                  <v-divider/>
	                </template>
	              </v-list>
	              <v-card-text>
	                <component :ref="'dataComponent' + n" :is="item.component" v-model="entidad"/>
	              </v-card-text>
	            </v-card>
	          </v-window-item>
	        </v-window>
	      </v-flex>
	    </v-layout>
	    <v-dialog v-model="archivoMasivo.visible" persistent max-width="700px">
	      <v-card>
	        <v-toolbar dense>
	          <v-toolbar-title>
	            <v-icon>publish</v-icon>
	            Cargar archivo
	          </v-toolbar-title>
	        </v-toolbar>
	        <v-container grid-list-md>
	          <v-layout wrap>
	            <v-flex xs12 sm12 md12>
	              <input-file
	                v-if="archivoMasivo.visible"
	                ref="archivoMasivo"
	                :label="archivoMasivo.label"
	                v-model="archivoMasivo.archivo"
	                required
	                accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
	                :hint="'Extenciones aceptadas: xls, xlsx'"
	                prepend-icon="description"
	                clearable
	              />
	            </v-flex>
	            <v-slide-y-reverse-transition mode="out-in">
	              <v-flex xs12 sm12 md12 v-if="this.archivoMasivo.invalido.items.length">
	                <v-card>
	                  <v-toolbar dense class="elevation-0">
	                    <v-toolbar-title>
	                      <v-icon>label_off</v-icon>
	                      Registros invalidos
	                    </v-toolbar-title>
	                  </v-toolbar>
	                  <v-data-table
	                    :headers="archivoMasivo.invalido.headers"
	                    :items="archivoMasivo.invalido.items"
	                    class="elevation-1"
	                    hide-actions
	                  >
	                    <template slot="items" slot-scope="props">
	                      <tr>
	                        <td
	                          v-for="(dato, index) in props.item"
	                          :key="'registro' + index"
	                          style="height: 10px !important;"
	                        >
	                          {{dato}}
	                        </td>
	                      </tr>
	                    </template>
	                  </v-data-table>
	                </v-card>
	              </v-flex>
	            </v-slide-y-reverse-transition>
	          </v-layout>
	        </v-container>
	        <v-divider/>
	        <v-card-actions>
	          <v-btn :disabled="archivoMasivo.loading" @click="archivoMasivo.visible = false">Cerrar</v-btn>
	          <v-spacer/>
	          <v-btn
	            :loading="archivoMasivo.loading"
	            :disabled="archivoMasivo.loading"
	            color="blue darken-1"
	            class="white--text"
	            @click="submitArchivo"
	          >
	            Subir archivo
	            <v-icon right dark>cloud_upload</v-icon>
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	    <v-slide-x-reverse-transition mode="out-in" group>
	      <v-speed-dial
	        key="btn1"
	        v-if="window === 0"
	        v-model="fab"
	        bottom
	        right
	        direction="top"
	        open-on-hover
	        fixed
	        class="mb-3"
	      >
	        <v-tooltip left slot="activator">
	          <v-btn
	            slot="activator"
	            v-model="fab"
	            color="blue darken-2"
	            dark
	            fab
	            @click="submit"
	          >
	            <v-icon>save</v-icon>
	            <v-icon>save</v-icon>
	          </v-btn>
	          <span>Guardar</span>
	        </v-tooltip>

	        <v-tooltip left>
	          <v-btn
	            slot="activator"
	            fab
	            dark
	            small
	            color="purple"
	            @click="migrar(null)"
	          >
	            <v-icon>info</v-icon>
	          </v-btn>
	          <span>Migrar</span>
	        </v-tooltip>

	        <v-tooltip left>
	          <v-btn
	            slot="activator"
	            fab
	            dark
	            small
	            color="purple"
	            @click="refresh(null)"
	          >
	            <v-icon>clear_all</v-icon>
	          </v-btn>
	          <span>Limpiar formulario</span>
	        </v-tooltip>
	        <v-tooltip left>
	          <v-btn
	            slot="activator"
	            fab
	            dark
	            small
	            color="red"
	            @click="close"
	          >
	            <v-icon>close</v-icon>
	          </v-btn>
	          <span>Cerrar</span>
	        </v-tooltip>
	      </v-speed-dial>
	      <v-speed-dial
	        key="btn2"
	        v-else
	        v-model="fab"
	        bottom
	        right
	        fixed
	        class="mb-3"
	      >
	        <v-tooltip bottom slot="activator">
	          <v-btn
	            slot="activator"
	            v-model="fab"
	            color="blue darken-2"
	            dark
	            fab
	            @click="close"
	          >
	            <v-icon>close</v-icon>
	          </v-btn>
	          <span>Cerrar</span>
	        </v-tooltip>
	      </v-speed-dial>
	    </v-slide-x-reverse-transition>
	  </v-container>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {ENTIDAD_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  export default {
    name: 'RegistroEntidad',
    props: ['parametros'],
    components: {
      Loading,
      InputFile: () => import('@/components/general/InputFile')
    },
    data: () => ({
      floatMenu: false,
      fabMenu: false,
      fab: false,
      loading: false,
      entidad: null,
      nitActual: null,
      idActual: null,
      itemsEntidades: null,
      idNuevo: null,
      nitNuevo: null,
      dialog: false,
      makeEntidad: {
        id: null,
        gn_tercero_id: null,
        replegal: null,
        gerente: null,
        nombre: null,
        rs_tipentidade_id: null,
        tipo_locacion: null,
        naturaleza: null,
        complejidad: null,
        res_habilitacion: null,
        codigo: null,
        cod_habilitacion: null,
        codHabilitacionValido: null,
        telefono_sede: null,
        correo_electronico_sede: null,
        gn_municipiosede_id: null,
        direccion_sede: null,
        ive: 0,
        primaria: 0,
        portabilidad: 0,
        auditoria_concurrente: 0,
        tercero: null,
        tipo: null,
        municipio: null
      },
      window: 0,
      drawer: true,
      items: [
        { icon: 'account_balance', avatarColor: 'primary', title: 'Información básica', subTitle: 'Datos básicos de la entidad.', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadDatosBasicos') },
        { icon: 'verified_user', avatarColor: 'success', title: 'Servicios habilitados', subTitle: 'Listado de servicios habilitados', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadServiciosHabilitados') },
        { icon: 'healing', avatarColor: 'danger', title: 'Procedimientos', subTitle: 'Listado de CUPS', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadProcedimientos') },
        { icon: 'colorize', avatarColor: 'purple', title: 'Medicamentos', subTitle: 'Listado de CUMS', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadMedicamentos') },
        { icon: 'category', avatarColor: 'orange', title: 'Otros bienes y servicios', subTitle: 'Bienes y servicios propios', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadOtros') },
        { icon: 'business', avatarColor: 'accent', title: 'Capacidad instalada', subTitle: 'Capacidad instalada', component: () => import('@/components/misional/redservicios/entidades/registroEntidad/RegistroEntidadCapacidadInstalada') }
      ],
      archivoMasivo: null,
      makeArchivoMasivo: {
        loading: false,
        visible: false,
        index: null,
        tipo: null,
        ruta: null,
        label: null,
        archivo: null,
        invalido: {
          headers: [],
          items: []
        }
      }
    }),
    watch: {
      'archivoMasivo.visible' (val) {
        if (!val) this.refreshArchivoMasivo()
      },
      'archivoMasivo.archivo' () {
        this.archivoMasivo.invalido.headers = []
        this.archivoMasivo.invalido.items = []
      }
    },
    created () {
      this.refresh()
      this.parametros.entidad !== null && this.parametros.entidad.id !== null && this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      async submitArchivo () {
        if (await this.$refs.archivoMasivo.validate()) {
          this.archivoMasivo.loading = true
          let data = new FormData()
          data.append('archivo', this.archivoMasivo.archivo)
          this.axios.post(`entidades/${this.entidad.id}/${this.archivoMasivo.ruta}`, data)
            .then(response => {
              if (response.data.guardado.length) {
                this.reloadSection(this.archivoMasivo.index)
              }
              if (response.data.invalido.length) {
                this.archivoMasivo.invalido.headers.push({
                  text: 'Fila',
                  align: 'left',
                  sortable: false
                })
                Object.keys(response.data.invalido[0].row).forEach((x) => {
                  this.archivoMasivo.invalido.headers.push({
                    text: x,
                    align: 'left',
                    sortable: false
                  })
                })
                this.archivoMasivo.invalido.headers.push({
                  text: 'Error',
                  align: 'left',
                  sortable: false
                })
                response.data.invalido.forEach(x => {
                  let registro = []
                  registro.push(x.fila)
                  Object.values(x.row).forEach(r => {
                    registro.push(r)
                  })
                  registro.push(window.lodash.flattenDeep(x.errors).join(', '))
                  this.archivoMasivo.invalido.items.push(registro)
                })
                this.$store.commit(SNACK_SHOW, {
                  msg: `El archivo de ${this.archivoMasivo.tipo} se ha subido con ${response.data.invalido.length} ${response.data.invalido.length === 1 ? 'registro invalido' : 'Registros invalidos'}.`,
                  color: 'warning'
                })
                this.archivoMasivo.loading = false
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'El archivo de ' + this.archivoMasivo.tipo + ' se ha subido correctamente. ', color: 'success'})
                this.archivoMasivo.visible = false
              }
            })
            .catch(e => {
              this.archivoMasivo.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al subir el archivo de ' + this.archivoMasivo.tipo + '. ', error: e})
            })
        }
      },
      cargarArchivo (index) {
        this.archivoMasivo.index = index
        console.log('index', index)
        switch (index) {
          case 2: {
            this.archivoMasivo.label = 'Archivo de procedimientos'
            this.archivoMasivo.tipo = 'procedimientos'
            this.archivoMasivo.ruta = 'importcups'
            break
          }
          case 3: {
            this.archivoMasivo.label = 'Archivo de medicamentos'
            this.archivoMasivo.tipo = 'medicamentos'
            this.archivoMasivo.ruta = 'importcums'
            break
          }
          case 4: {
            this.archivoMasivo.label = 'Archivo de otros bienes y servicios'
            this.archivoMasivo.tipo = 'otros bienes y servicios'
            this.archivoMasivo.ruta = 'importotros'
            break
          }
        }
        this.archivoMasivo.visible = true
      },
      reloadSection (n) {
        (n === 2 || n === 3 || n === 4)
          ? this.$refs['dataComponent' + n][0].$refs[n === 2 ? 'tablaProcedimientos' : n === 3 ? 'tablaMedicamentos' : 'tablaOtros'].reloadPage()
          : this.getRegistro(this.entidad.id)
      },
      migrarEntidad () {
        this.loading = true
        this.dialog = false
        if (this.validador('formPago')) {
          console.log('migrar/entidades/' + this.idActual + '/' + this.idNuevo)
          this.axios.get('migrar/entidades/' + this.idActual + '/' + this.idNuevo)
            .then((response) => {
              if (response.data !== '') {
                // console.log(response.data)
                this.$store.commit(SNACK_SHOW, {msg: 'La migración se ha realizado correctamente.', color: 'success'})
                this.close()
                this.loading = false
                // console.log(this.entidad.tercero.identificacion)
              }
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer la entidad. ', error: e})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      migrar () {
        this.dialog = true
      },
      async submit () {
        if (await this.$refs['dataComponent0'][0].validate()) {
          this.loading = true
          if (this.entidad.rs_tipentidade_id !== 1 && this.entidad.rs_tipentidade_id !== 6) this.entidad.cod_habilitacion = null
          if (this.entidad.rs_tipentidade_id !== 1 && this.entidad.rs_tipentidade_id !== 2 && this.entidad.rs_tipentidade_id !== 6) this.entidad.res_habilitacion = null
          const typeRequest = this.entidad.id ? 'editar' : 'crear'
          let request = typeRequest === 'editar' ? this.axios.put(`entidades/${this.entidad.id}`, this.entidad) : this.axios.post('entidades', this.entidad)
          request
            .then(response => {
              response.data.entidad.codHabilitacionValido = response.data.entidad.cod_habilitacion
              response.data.entidad.codigo = response.data.entidad.cod_habilitacion
              this.entidad = response.data.entidad
              this.loading = false
              this.$store.commit(ENTIDAD_RELOAD_ITEM, {item: response.data.entidad_o, estado: typeRequest, key: this.parametros.key})
              this.$store.commit(SNACK_SHOW, {msg: 'Los datos de la entidad se han guardado satisfactoriamente. ', color: 'success'})
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar los datos de la entidad. ', error: e})
              this.loading = false
            })
        }
      },
      close () {
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get('entidades_diferente/?idEntidad=' + id)
          .then((response) => {
            if (response.data !== '') {
              this.itemsEntidades = response.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer la entidad. ', error: e})
          })
        this.axios.get('entidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              response.data.data.codHabilitacionValido = response.data.data.cod_habilitacion
              response.data.data.codigo = response.data.data.cod_habilitacion
              this.entidad = response.data.data
              this.nitActual = this.entidad.tercero.identificacion
              this.idActual = this.entidad.id
              this.loading = false
              console.log(this.entidad.tercero.identificacion)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer la entidad. ', error: e})
          })
      },
      refresh () {
        this.refreshArchivoMasivo()
        this.entidad = JSON.parse(JSON.stringify(this.makeEntidad))
        this.$validator.reset()
        this.$refs['dataComponent0'] && this.$refs['dataComponent0'][0] && this.$refs['dataComponent0'][0].reset()
      },
      refreshArchivoMasivo () {
        this.archivoMasivo = JSON.parse(JSON.stringify(this.makeArchivoMasivo))
        if (this.$refs['archivoMasivo']) this.$refs['archivoMasivo'].reset()
      }
    }
  }
</script>

<style scoped>

</style>
