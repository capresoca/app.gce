<template>
  <v-dialog v-model="visible" persistent max-width="900">
    <v-card v-if="value">
      <v-toolbar dense class="elevation-1 white">
        <v-avatar size="36" color="primary">
          <v-icon dark>security</v-icon>
        </v-avatar>
        <v-list-tile>
          <v-list-tile-content>
            <v-list-tile-title class="subheading">Glosas</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn
            slot="activator"
            icon
            @click="close"
          >
            <v-icon
            >
              close
            </v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-container
        class="pa-2"
        fluid
        grid-list-md
      >
        <v-layout row wrap>
          <v-flex xs12>
            <detalle-items-facturados :items="[value]"></detalle-items-facturados>
          </v-flex>
          <v-flex xs12>
            <v-window
              v-model="window"
              class="elevation-1"
            >
              <v-window-item>
                <v-card>
                  <v-card-title class="grey lighten-3 py-0">
                    <span class="font-weight-bold"> Glosas registradas </span>
                    <v-spacer></v-spacer>
                    <v-tooltip left v-if="auditando">
                      <v-btn
                        slot="activator"
                        icon
                        @click="window = 1"
                      >
                        <v-icon
                          color="primary"
                        >
                          add
                        </v-icon>
                      </v-btn>
                      <span>agregar glosa</span>
                    </v-tooltip>
                  </v-card-title>
                  <v-divider></v-divider>
                  <v-data-table
                    :headers="headersGlosas"
                    :items="value.glosas"
                    no-data-text="No hay glosas registradas"
                    hide-actions
                  >
                    <template slot="items" slot-scope="props">
                      <td class="px-2 text-xs-center" style="height: 0px !important;">
<!--                        <v-tooltip top>-->
<!--                          <v-btn-->
<!--                            small-->
<!--                            slot="activator"-->
<!--                            icon-->
<!--                            @click="props.item.of_facservicio ? null : confirmaBorrado(props)"-->
<!--                          >-->
<!--                            <v-icon :color="props.item.of_facservicio ? 'grey' : 'red'" :class="props.item.of_facservicio ? '' : 'text&#45;&#45;darken-2'">delete_forever</v-icon>-->
<!--                          </v-btn>-->
<!--                          <span>{{props.item.of_facservicio ? 'No es posible borrar glosas de factura desde éste punto.' : 'Borrar glosa'}}</span>-->
<!--                        </v-tooltip>-->
                        <v-tooltip top>
                          <v-btn
                            small
                            slot="activator"
                            icon
                            :disabled="!auditando"
                            @click="auditando ? confirmaBorrado(props) : null"
                          >
                            <v-icon color="red" class="text--darken-2">delete_forever</v-icon>
                          </v-btn>
                          <span>Borrar glosa</span>
                        </v-tooltip>
                      </td>
                      <td class="px-2" style="height: 0px !important;">
                        <span class="ma-0 body-1">{{ props.item.glosa.glosa }}: {{ props.item.glosa.descripcion }}</span><br/>
                        <span class="ma-0 caption font-weight-bold">Tipo: {{props.item.glosa.tipo}}</span>
                      </td>
                      <td class="px-2 text-xs-right" style="height: 0px !important;">
                        {{ props.item.tipo !== 'Porcentaje' ? currency(props.item.valor_glosa) : (`(${((props.item.valor_glosa/value.valor_total)*100)}%) ` + currency(props.item.valor_glosa)) }}
                        <v-icon color="success" v-if="!props.item.of_facservicio">add</v-icon>
                      </td>
                      <td class="px-2 text-xs-justify" style="height: 0px !important;">
                        {{ props.item.observacion }}
                      </td>
                    </template>
                    <template slot="footer">
                      <td colspan="3" class="font-weight-bold text-xs-right">
                        Total Glosado
                      </td>
                      <td class="font-weight-bold text-xs-right">
                        {{currency(totalGlosado)}}
                      </td>
                    </template>
                  </v-data-table>
                </v-card>
              </v-window-item>
              <v-window-item>
                <registro-glosa :facturaid="facturaid" :capitado="capitado" :window="window" :servicios="[value]" :glosas="glosas" @cancelar="window = 0" @success="registraGlosa"></registro-glosa>
              </v-window-item>
            </v-window>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    ></confirmar>
  </v-dialog>
</template>

<script>
  import DetalleItemsFacturados from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/DetalleItemsFacturados'
  export default {
    name: 'Glosas',
    props: {
      value: {
        type: Object,
        default: null
      },
      glosas: {
        type: Array,
        default: []
      },
      registrar: {
        type: Boolean,
        default: true
      },
      auditando: {
        type: Boolean,
        default: false
      },
      capitado: {
        type: Boolean,
        default: false
      },
      facturaid: {
        type: [String, Number],
        default: null
      }
    },
    components: {
      DetalleItemsFacturados,
      Confirmar: () => import('@/components/general/Confirmar'),
      RegistroGlosa: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/RegistroGlosa')
    },
    data: () => ({
      window: 0,
      headersGlosas: [
        {
          text: '',
          align: 'center',
          sortable: false
        },
        {
          text: 'Glosa',
          align: 'left',
          sortable: false
        },
        {
          text: 'Valor',
          align: 'center',
          sortable: false
        },
        {
          text: 'Observaciones',
          align: 'center',
          sortable: false
        }
      ],
      dialogA: {
        loading: false,
        visible: false,
        content: null,
        props: null
      },
      visible: false
    }),
    computed: {
      totalGlosado () {
        let glosado = 0
        if (this.value && this.value.glosas) {
          this.value.glosas.filter(j => !j.of_facservicio).forEach(y => {
            glosado = glosado + (y.valor_glosa)
          })
        }
        // return glosado > this.value.valor_total ? this.value.valor_total : glosado
        return glosado
      }
    },
    methods: {
      registraGlosa () {
        this.value.avalado = 0
        this.window = 0
      },
      open () {
        this.visible = true
        this.window = 0
      },
      close () {
        this.visible = false
        setTimeout(() => {
          this.window = 0
        }, 300)
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        this.dialogA.loading = false
        setTimeout(() => {
          this.dialogA.content = ''
          this.dialogA.props = null
        }, 300)
      },
      aceptaConfirmacion () {
        this.dialogA.loading = true
        this.borrarGlosa(this.dialogA.props)
      },
      confirmaBorrado (props) {
        this.dialogA.content = `<strong>¿Está seguro de borrar la glosa ${props.index + 1} de código ${props.item.glosa.glosa} por ${this.currency(props.item.valor_glosa)}?</strong>`
        this.dialogA.props = props
        this.dialogA.visible = true
      },
      borrarGlosa (props) {
        this.axios.delete(`facservicios/glosas/${props.item.id}`)
          .then(() => {
            this.value.glosas.splice(props.index, 1)
            !this.value.glosas.length && (this.value.avalado = null)
            this.$store.commit('SNACK_SHOW', {msg: `La glosa fue borrada correctamente.`, color: 'success'})
            this.cancelaConfirmacion()
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al borrar la glosa`, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
