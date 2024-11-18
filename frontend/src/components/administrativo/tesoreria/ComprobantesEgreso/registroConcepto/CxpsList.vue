<template>
  <v-dialog
    persistent
    v-model="dialog"
    width="900"
  >
    <template v-slot:activator="{ on }">
      <v-btn small flat v-on="on">
        CxPs
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dense>
        <v-list-tile class="content-v-list-tile-p0">
          <v-list-tile-content>
            <v-list-tile-title>Cuentas por pagar</v-list-tile-title>
            <v-list-tile-sub-title>{{comprobante.tercero.identificacion_completa}} - {{comprobante.tercero.nombre_completo}}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn flat icon slot="activator" @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-data-table
        v-model="selecteds"
        :items="registros"
        :headers="headers"
        hide-actions
        :loading="loading"
        no-data-text="No registra cuentas por pagar."
      >
        <template v-slot:items="props">
          <tr :active="props.selected">
            <td class="text-xs-center">
              <v-checkbox
                v-model="props.selected"
                primary
                hide-details
              ></v-checkbox>
            </td>
            <td class="text-xs-center">{{ props.item.consecutivo }}</td>
            <td class="text-xs-center">{{ props.item.modulo }}</td>
            <td class="text-xs-center">{{ props.item.no_factura }}</td>
            <td class="text-xs-right">{{ currency(props.item.valor_sin_formato) }}</td>
            <td class="text-xs-right">{{ currency(props.item.saldo) }}</td>
            <td class="text-xs-center">
              <input-number
                :disabled="!props.selected"
                :key="'inputvalor' + props.index"
                :name="`valorpagar${props.index}`"
                prepend-icon="attach_money"
                v-model.number="props.item.valor"
                data-vv-as="Valor a pagar"
                v-validate="`required|min_value:1|max_value:${props.item.saldo}`"
                :error-messages="errors.collect(`valorpagar${props.index}`)"
              ></input-number>
            </td>
          </tr>
        </template>
      </v-data-table>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cerrar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          v-if="selecteds.length"
          color="primary"
          flat
          @click="asignar"
        >
          Asignar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'CxpsList',
    props: {
      comprobante: {
        type: Object,
        default: null
      }
    },
    components: {
      InputNumber: () => import('@/components/general/InputNumber')
    },
    data: () => ({
      dialog: false,
      loading: false,
      selecteds: [],
      registros: [],
      headers: [
        {
          text: '',
          align: 'center',
          sortable: false
        },
        {
          text: 'Núm.',
          align: 'center',
          sortable: false
        },
        {
          text: 'Módulo',
          align: 'center',
          sortable: false
        },
        {
          text: 'Factura',
          align: 'center',
          sortable: false
        },
        {
          text: 'Valor',
          align: 'right',
          sortable: false
        },
        {
          text: 'Saldo',
          align: 'right',
          sortable: false
        },
        {
          text: 'Valor a pagar',
          align: 'center',
          sortable: false
        }
      ]
    }),
    watch: {
      'dialog' (val) {
        val && this.getCXPS()
      }
    },
    methods: {
      asignar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('returnItems', this.selecteds)
            this.dialog = false
          }
        })
      },
      getCXPS () {
        this.registros = []
        this.loading = true
        this.axios.get(`pg_cxps?gn_tercero_id=${this.comprobante.tercero.id}`)
          .then(response => {
            console.log('las cxps', response)
            response.data.data.forEach(x => {
              x.valor = null
            })
            this.registros = response.data.data
            this.loading = false
          }).catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las cxps. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
