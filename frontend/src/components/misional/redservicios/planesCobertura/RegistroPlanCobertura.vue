<template>
  <v-container
    class="pa-0 ma-0"
    fluid
    grid-list-md
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
            :color="item.props.avatarColor"
            @click="window = index"
            :class="window === index ? 'elevation-5' : ''"
          >
            <v-icon>{{item.props.icon}}</v-icon>
          </v-btn>
          <span>{{item.props.title}}</span>
        </v-tooltip>
      </v-speed-dial>
    </v-slide-x-reverse-transition>
    <v-toolbar :dense="!planCoberturaOriginal.id">
      <v-tooltip top class="mr-2" v-if="planCoberturaOriginal.id && !floatMenu">
        <v-btn
          slot="activator"
          icon
          @click="floatMenu = true"
          >
          <v-icon color="accent">group_work</v-icon>
        </v-btn>
        <span>activar menú flotante</span>
      </v-tooltip>
      <v-icon>wifi_tethering</v-icon>
      <v-toolbar-title v-if="!planCoberturaOriginal.id">Nuevo plan de cobertura</v-toolbar-title>
      <v-list-tile v-else>
        <v-list-tile-content>
          <v-list-tile-title v-html="planCoberturaOriginal.nombre"></v-list-tile-title>
          <v-list-tile-sub-title v-if="planCoberturaOriginal.contrato.entidad" v-html="planCoberturaOriginal.contrato.entidad.tercero.nombre_completo"></v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
      <template>
        <v-spacer/>
        <span class="pt-1 text-xs-right">
          <span class="title text-capitalize">Contrato</span>
          <p class="mb-0 caption">{{planCoberturaOriginal.contrato && planCoberturaOriginal.contrato.estado}}</p>
        </span>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="!(planCoberturaOriginal.contrato && planCoberturaOriginal.contrato.numero_contrato) ? '00' : planCoberturaOriginal.contrato.numero_contrato"></span>
        </v-chip>
      </template>
    </v-toolbar>
    <loading v-model="loading" />
    <v-layout row wrap>
      <v-slide-x-reverse-transition>
        <v-flex sm12 md3 v-if="planCoberturaOriginal.id && !floatMenu">
          <v-card class="content-list-p0">
            <v-list two-line>
              <template v-for="(item, index) in items">
                <v-list-tile @click="window = index" :key="'list' + index" :class="window === index ? 'grey lighten-2' : ''">
                  <v-list-tile-avatar :color="item.props.avatarColor">
                    <v-icon dark>{{ item.props.icon }}</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title>
                      {{ item.props.title }}
                    </v-list-tile-title>
                    <v-list-tile-sub-title v-html="item.props.subTitle"></v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-divider inset/>
              </template>
            </v-list>
          </v-card>
        </v-flex>
      </v-slide-x-reverse-transition>
      <v-flex :md9="planCoberturaOriginal.id && !floatMenu">
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
              <v-list v-if="planCoberturaOriginal.id" two-line>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar :color="item.props.avatarColor">
                      <v-icon dark>{{ item.props.icon }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ item.props.title }}
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-html="item.props.subTitle"></v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-action style="width: 120px !important;" v-if="(n > 1 && n < 5) && !planCoberturaOriginal.actaInicio">
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
                            :href="axios.defaults.baseURL + '/../files/Plantilla_importacion_' + (n === 2 ? 'cups' : n === 3 ? 'cums' : 'otros') + '_contrato.xlsx'"
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
                <component :ref="'dataComponent' + n" :is="item.component" v-model="planCobertura" :contrato="parametros.contrato" @clean="refresh"/>
              </v-card-text>
              <template v-if="n === 0">
                <v-divider/>
                <v-card-actions v-if="!planCoberturaOriginal.actaInicio">
                  <v-btn flat @click.stop="refresh">Limpiar</v-btn>
                  <v-spacer/>
                  <v-btn flat color="primary" @click.stop="submit">Registrar</v-btn>
                </v-card-actions>
              </template>
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
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {ENTIDAD_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroPlanCobertura',
    props: ['parametros'],
    components: {
      Loading,
      InputFile: () => import('@/components/general/InputFile')
    },
    data: () => ({
      floatMenu: false,
      fabMenu: false,
      loading: false,
      planCoberturaOriginal: null,
      planCobertura: null,
      contratoLectura: false,
      makePlanCobertura: {
        id: null,
        portabilidad: 0,
        estado: 'Registrado',
        ce_proconminuta_id: null,
        regimen_atendido: null,
        pr_detrp_id: null,
        nombre: null,
        contrato: null,
        actaInicio: false
      },
      window: 0,
      items: [
        { props: {icon: 'work', avatarColor: 'primary', title: 'Información básica', subTitle: 'Datos básicos de la contrato.'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroDatosBasicos') },
        { props: {icon: 'verified_user', avatarColor: 'success', title: 'Servicios contratados', subTitle: 'Registro de servicios contratados'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroServicios') },
        { props: {icon: 'healing', avatarColor: 'danger', title: 'Procedimientos', subTitle: 'Registro de CUPS'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroProcedimientos') },
        { props: {icon: 'colorize', avatarColor: 'purple', title: 'Medicamentos', subTitle: 'Registro de CUMS'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroMedicamentos') },
        { props: {icon: 'category', avatarColor: 'orange', title: 'Otros bienes y servicios', subTitle: 'Otros bienes y servicios'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroOtros') },
        { props: {icon: 'settings_input_composite', avatarColor: 'accent', title: 'Interfaz contable', subTitle: 'Configuración interfaz contable'}, component: () => import('@/components/misional/redservicios/planesCobertura/registroPlanCobertura/RegistroInterfazContable') }
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
    async created () {
      await this.refresh()
      if (!this.parametros.entidad && this.parametros.contrato) {
        this.planCoberturaOriginal.contrato = this.parametros.contrato
        this.planCoberturaOriginal.ce_proconminuta_id = this.parametros.contrato.id
        this.planCobertura.contrato = this.parametros.contrato
        this.planCobertura.ce_proconminuta_id = this.parametros.contrato.id
      }
      this.parametros.entidad !== null && this.parametros.entidad.id !== null && this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      async submitArchivo () {
        if (await this.$refs.archivoMasivo.validate()) {
          this.archivoMasivo.loading = true
          let data = new FormData()
          data.append('archivo', this.archivoMasivo.archivo)
          this.axios.post(`contratos/${this.planCobertura.id}/${this.archivoMasivo.ruta}`, data)
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
        switch (n) {
          case 2: {
            this.$refs['dataComponent' + n][0].reloadAll()
            break
          }
          case 3:
          case 4: {
            this.$refs['dataComponent' + n][0].$refs[n === 3 ? 'tablaMedicamentos' : 'tablaOtros'].reloadPage()
            break
          }
          default: {
            this.getRegistro(this.planCobertura.id)
          }
        }
      },
      async submit () {
        console.log('llegando')
        if (await this.$refs['dataComponent0'][0].validate()) {
          this.loading = true
          const typeRequest = this.planCobertura.id ? 'editar' : 'crear'
          let request = typeRequest === 'editar' ? this.axios.put(`contratos/${this.planCobertura.id}`, this.planCobertura) : this.axios.post('contratos', this.planCobertura)
          request
            .then(response => {
              console.log('el respose del contrato', response)
              this.planCobertura = response.data.data
              this.planCoberturaOriginal = response.data.data
              this.$store.commit(ENTIDAD_RELOAD_ITEM, {item: {}, estado: 'reload', key: this.parametros.key})
              this.$store.commit('PLAN_COBERTURA_RELOAD_ITEM', {item: JSON.parse(JSON.stringify(response.data.data)), estado: typeRequest, key: this.parametros.key})
              this.$store.commit(SNACK_SHOW, {msg: 'Los datos del plan de cobertura se han guardado correctamente. ', color: 'success'})
              this.loading = false
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar los datos del plan de cobertura. ', error: e})
              this.loading = false
            })
        }
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get('contratos/' + id)
          .then((response) => {
            if (response.data !== '') {
              console.log('el plan de cobertura', response)
              response.data.data.actaInicio = response.data.data.estado !== 'Registrado'
              this.planCoberturaOriginal = JSON.parse(JSON.stringify(response.data.data))
              this.planCobertura = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el plan de cobertura. ', error: e})
          })
      },
      refresh () {
        this.refreshArchivoMasivo()
        this.planCoberturaOriginal = JSON.parse(JSON.stringify(this.makePlanCobertura))
        this.planCobertura = JSON.parse(JSON.stringify(this.makePlanCobertura))
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
  .content-list-p0 .v-list{
    padding: 0 !important;
  }
</style>
