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
    <v-toolbar :dense="!contratoOriginal.id">
      <v-tooltip top class="mr-2" v-if="contratoOriginal.id && contratoOriginal.estado !== 'Registrado' && !floatMenu">
        <v-btn
          slot="activator"
          icon
          @click="floatMenu = true"
          >
          <v-icon color="accent">group_work</v-icon>
        </v-btn>
        <span>activar menú flotante</span>
      </v-tooltip>
      <v-icon>work</v-icon>
      <v-toolbar-title v-if="!contratoOriginal.id">Nuevo contrato</v-toolbar-title>
      <v-list-tile v-else-if="contratoOriginal.proceso_contractual">
        <v-list-tile-content v-if="contratoOriginal.proceso_contractual.servicios_salud === 1 && contratoOriginal.entidad">
          <v-list-tile-title v-html="contratoOriginal.entidad.nombre"></v-list-tile-title>
          <v-list-tile-sub-title v-html="'Código de habilitación: ' + (contratoOriginal.entidad.cod_habilitacion || 'No registra')"></v-list-tile-sub-title>
        </v-list-tile-content>
        <v-list-tile-content v-else-if="contratoOriginal.proceso_contractual.servicios_salud === 0 && contratoOriginal.tercero">
          <v-list-tile-title v-html="contratoOriginal.tercero.nombre_completo"></v-list-tile-title>
          <v-list-tile-sub-title v-html="contratoOriginal.tercero.identificacion_completa"></v-list-tile-sub-title>
        </v-list-tile-content>
        <v-list-tile-content v-else>
          <v-list-tile-title v-html="'No registra contratista'"></v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile v-else>
        <v-list-tile-content>
          <v-list-tile-title v-html="'No registra proceso contractual'"></v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <template>
        <v-spacer/>
        <span class="pt-1 text-xs-right">
          <span class="title text-capitalize">Contrato</span>
          <p class="mb-0 caption">{{contratoOriginal.estado}}</p>
        </span>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="contratoOriginal.numero_contrato == null ? '00' : contratoOriginal.numero_contrato"></span>
        </v-chip>
      </template>
      <v-tooltip top v-if="contratoOriginal.id">
        <v-btn
          slot="activator"
          icon
          large
          @click="getRegistro(contratoOriginal.id)"
        >
          <v-icon
            color="primary"
            large
          >
            cached
          </v-icon>
        </v-btn>
        <span>Recargar contrato</span>
      </v-tooltip>
    </v-toolbar>
    <loading v-model="loading" />
    <v-layout row wrap>
      <v-slide-x-reverse-transition>
        <v-flex sm12 md3 v-if="contratoOriginal.id && contratoOriginal.estado !== 'Registrado' && !floatMenu">
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
      <v-flex :md9="contratoOriginal.id && contratoOriginal.estado !== 'Registrado' && !floatMenu">
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
              <v-list v-if="contratoOriginal.id" two-line dense>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar :color="item.props.avatarColor">
                      <v-icon dark>{{ item.props.icon }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title class="subheading">
                        {{ item.props.title }}
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-html="item.props.subTitle"></v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                      <v-tooltip top v-if="item.props.buttons && item.props.buttons === 'far fa-file-pdf'">
                        <v-btn
                          slot="activator"
                          icon
                          large
                          :loading="loadingPrint"
                          @click="imprimirMinuta"
                        >
                          <v-icon
                            medium
                            color="success"
                          >
                            far fa-file-pdf
                          </v-icon>
                        </v-btn>
                        <span>Vista previa</span>
                      </v-tooltip>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <component v-if="item.props.ref === 'basicos'" parent="primary" :ref="item.props.ref" :is="item.component" v-model="contrato" :original="contratoOriginal" @clean="refresh" @addCdp="data => assignCDP(data)" @deleteCDP="data => eliminarCDP(data)"></component>
              <component v-else parent="primary" :ref="item.props.ref" :is="item.component" v-model="contrato" :original="contratoOriginal" @clean="refresh"/>
              <template v-if="n === 0 && contratoOriginal.estado === 'Registrado'">
                <v-divider/>
                <v-card-actions v-if="!contratoOriginal.fecha_acta_inicio">
                  <v-btn flat @click.stop="refresh">Limpiar</v-btn>
                  <v-spacer/>
                  <v-btn flat color="primary" @click.prevent="confirm" :loading="loading">Guardar y confirmar</v-btn>
                  <v-btn flat color="primary" @click.stop="submit" :loading="loading">Guardar</v-btn>
                </v-card-actions>
              </template>
            </v-card>
          </v-window-item>
        </v-window>
      </v-flex>
    </v-layout>
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {CONTRATO_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroContrato',
    props: ['parametros'],
    components: {
      Loading,
      InputFile: () => import('@/components/general/InputFile'),
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialogA: {
        loading: false,
        visible: false,
        content: null
      },
      floatMenu: false,
      fabMenu: false,
      loading: false,
      loadingPrint: false,
      procesoContractual: null,
      contratoOriginal: null,
      contrato: null,
      makeContrato: {
        id: null,
        ce_procontractuale_id: null,
        proceso_contractual: null,
        consecutivo: null,
        fecha_contrato: null,
        fecha_acta_inicio: null,
        fecha_finalizacion: null,
        numero_contrato: null,
        rs_entidad_id: null,
        entidad: null,
        tercero: null,
        gn_tercero_id: null,
        objeto: null,
        ce_tipocontrato_id: null,
        plazo_meses: null,
        plazo_dias: null,
        valor: null,
        valor_total: null,
        modificaciones_plazo: null,
        modificaciones_valor: null,
        pr_cdp_id: null,
        cdp: null,
        estado: 'Registrado',
        gs_usuario_id: null,
        minuta: null,
        minuta_original: null,
        minuta_firmada: null,
        archivo_minuta: null,
        prcdps: []
      },
      window: 0,
      items: [
        { props: {icon: 'info', ref: 'basicos', avatarColor: 'info', title: 'Datos básicos', subTitle: 'Datos básicos del contrato.'}, component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroDatosBasicos') }
      ]
    }),
    computed: {
      verMinutaEditable () {
        return this.contrato && this.contrato.proceso_contractual && (this.contrato.estado === 'Confirmado' || this.contrato.estado === 'Legalizado')
      },
      verPlanes () {
        return this.contrato && this.contrato.proceso_contractual && this.contrato.proceso_contractual.servicios_salud && (this.contrato.estado === 'Confirmado' || this.contrato.estado === 'Legalizado')
      }
    },
    watch: {
      'verPlanes' (val) {
        if (val) {
          this.items.push({
            props: {
              icon: 'wifi_tethering',
              ref: 'planes',
              avatarColor: 'purple',
              title: 'Planes de Cobertura',
              subTitle: 'Registro de planes de cobertura'
            },
            component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroPlanesCobertura')
          })
        }
      },
      'verMinutaEditable' (val) {
        if (val) {
          this.items.push({
            props: {
              icon: 'receipt',
              ref: 'minuta',
              avatarColor: 'primary',
              title: 'Minuta',
              subTitle: 'Minuta contrato',
              buttons: 'far fa-file-pdf'
            },
            component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroMinuta')
          })
          this.items.push({
            props: {
              icon: 'done_all',
              ref: 'perfeccionamiento',
              avatarColor: 'orange',
              title: 'Perfeccionamiento',
              subTitle: 'Items del perfeccionamiento'
            },
            component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroPerfeccionamiento')
          })
          this.items.push({
            props: {
              icon: 'how_to_vote',
              ref: 'novedades',
              avatarColor: 'teal',
              title: 'Novedades',
              subTitle: 'Registro de novedades'
            },
            component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroNovedades')
          })
        }
      }
    },
    created () {
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) {
        this.refresh()
        this.getRegistro(this.parametros.entidad.id)
      } else if (this.parametros.procesoContractual) {
        this.refresh()
        this.procesoContractual = JSON.parse(JSON.stringify(this.parametros.procesoContractual))
        this.contrato.valor = this.procesoContractual.estudios_previos ? window.lodash.sumBy(this.procesoContractual.estudios_previos.imputacion_presupuestal, 'valor') : 0
        this.contrato.plazo_meses = this.procesoContractual.estudios_previos ? this.procesoContractual.estudios_previos.plazo_meses : null
        this.contrato.plazo_dias = this.procesoContractual.estudios_previos ? this.procesoContractual.estudios_previos.plazo_dias : null
        this.contrato.objeto = this.procesoContractual.objeto
        this.contrato.ce_procontractuale_id = this.procesoContractual.id
        this.contrato.proceso_contractual = this.procesoContractual
      } else {
        this.$store.commit(SNACK_SHOW, {msg: 'No hay un proceso contractual seleccionado previamente.', color: 'warning'})
        this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
      }
    },
    methods: {
      async confirm () {
        if (await this.$refs['basicos'][0].validate()) {
          this.dialogA.content = `Los datos del contrato serán confirmados, si decide continuar, los datos no se podran editar nuevamente.<br/> <strong>¿Está seguro de confirmar los datos?</strong>`
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      async aceptaConfirmacion () {
        this.dialogA.loading = true
        this.contrato.estado = 'Confirmado'
        if (await this.enviar()) this.cancelaConfirmacion()
        this.dialogA.loading = false
      },
      async submit () {
        if (await this.$refs['basicos'][0].validate()) this.enviar()
      },
      async imprimirMinuta () {
        this.loadingPrint = true
        if (await this.$refs['minuta'][0].save(false)) this.filePrint(`minuta-pdf&id=${this.contrato.id}`)
        this.loadingPrint = false
      },
      async enviar (message = true) {
        this.loading = true
        if (this.contrato.proceso_contractual) {
          if (this.contrato.proceso_contractual.servicios_salud) {
            this.contrato.tercero = null
            this.contrato.gn_tercero_id = null
          } else {
            this.contrato.entidad = null
            this.contrato.rs_entidad_id = null
          }
        }
        let data = this.clone(this.contrato)
        if (data.prcdps.length) {
          data.prcdps.forEach(x => ({
            ce_proconminuta_id: (x.ce_proconminuta_id ? x.ce_proconminuta_id : null),
            cdp: x.cdp,
            pr_cdp_id: x.pr_cdp_id
          }))
        }
        let request = data.id ? this.axios.put(`minutas/${data.id}`, data) : this.axios.post('minutas', data)
        return new Promise(resolve => {
          request
            .then(response => {
              console.log('el respose del contrato', response.data.data)
              this.contratoOriginal = response.data.data
              this.contrato = response.data.data
              this.contrato.prcdps = response.data.data.prcdps
              this.$store.commit(CONTRATO_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: this.parametros.key})
              if (message) this.$store.commit(SNACK_SHOW, {msg: 'Los datos del contrato se han guardado satisfactoriamente. ', color: 'success'})
              this.loading = false
              resolve(true)
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar los datos del contrato. ', error: e})
              resolve(false)
            })
        })
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get('minutas/' + id)
          .then(response => {
            if (response.data !== '') {
              response.data.data.fecha_contrato = response.data.data.fecha_contrato ? this.moment(response.data.data.fecha_contrato).format('YYYY-MM-DD') : null
              response.data.data.fecha_acta_inicio = response.data.data.fecha_acta_inicio ? this.moment(response.data.data.fecha_acta_inicio).format('YYYY-MM-DD') : null
              this.contratoOriginal = JSON.parse(JSON.stringify(response.data.data))
              this.contrato = response.data.data
              this.contrato.prcdps = response.data.data.prcdps
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el contrato. ', error: e})
          })
      },
      refresh () {
        this.contratoOriginal = JSON.parse(JSON.stringify(this.makeContrato))
        this.contrato = JSON.parse(JSON.stringify(this.makeContrato))
        this.$validator.reset()
        this.$refs['basicos'] && this.$refs['basicos'][0] && this.$refs['basicos'][0].reset()
      },
      assignCDP (item) {
        this.contrato.prcdps.push(item)
      },
      eliminarCDP (item) {
        this.contrato.prcdps.splice(this.contrato.prcdps.findIndex(x => x.cdp.id === item.cdp.id), 1)
      }
    }
  }
</script>

<style scoped>

</style>
