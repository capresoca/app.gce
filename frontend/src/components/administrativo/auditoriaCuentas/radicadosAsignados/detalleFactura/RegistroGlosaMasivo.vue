<template>
  <v-dialog v-model="dialog" persistent max-width="1200px">
    <template v-slot:activator="{ on }">
<!--      <v-tooltip top>-->
<!--        <v-btn-->
<!--          class="ma-0"-->
<!--          :class="disabled ? '' : 'text&#45;&#45;darken-2'"-->
<!--          fab-->
<!--          flat-->
<!--          dark-->
<!--          small-->
<!--          color="grey"-->
<!--          slot="activator"-->
<!--          v-on="disabled ? null : on"-->
<!--        >-->
<!--          <v-badge class="badge-options-facservicios" color="red darken-3">-->
<!--            <template slot="badge" v-if="unicoR && items[0].glosas.length">-->
<!--              <span>{{items[0].glosas.length}}</span>-->
<!--            </template>-->
<!--            <v-icon>security</v-icon>-->
<!--          </v-badge>-->
<!--        </v-btn>-->
<!--        <span>{{unicoR && items[0].glosas.length ? `${items[0].glosas.length} glosa${items[0].glosas.length === 1 ? '': 's'}` : 'Glosar' }}</span>-->
<!--      </v-tooltip>-->
      <v-btn
        flat
        dark
        v-on="on"
      >
        <span class="subheading">GLOSAR</span>
      </v-btn>
      <v-divider vertical></v-divider>
    </template>
    <v-card>
      <loading v-model="loading"/>
      <v-toolbar dense class="elevation-1 white">
        <v-avatar size="36" color="primary" class="white--text">
          <v-icon color="white">security</v-icon>
        </v-avatar>
        <v-list-tile>
          <v-list-tile-content>
            <v-list-tile-title class="subheading">Asignación de glosa</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn
            slot="activator"
            icon
            @click="cancelar"
          >
            <v-icon
            >
              close
            </v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-divider></v-divider>
      <detalle-items-facturados :items="itemsFacturados" :capitado="capitado"></detalle-items-facturados>
      <registro-glosa :facturaid="facturaid" :capitado="capitado" :servicios="itemsFacturados" :glosas="glosas" @cancelar="cancelar" @success="registraGlosas"></registro-glosa>
<!--      <v-divider></v-divider>-->
<!--      <v-card-actions>-->
<!--        <v-btn flat @click="cancelar">Cancelar</v-btn>-->
<!--        <v-spacer></v-spacer>-->
<!--        <v-btn v-if="items.length" color="primary" flat @click="">Guardar</v-btn>-->
<!--        <v-chip v-else label color="orange" text-color="white">-->
<!--          No se han seleccionado items de factura-->
<!--        </v-chip>-->
<!--      </v-card-actions>-->
    </v-card>
  </v-dialog>
</template>

<script>
  import DetalleItemsFacturados from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/DetalleItemsFacturados'
  import Loading from '@/components/general/Loading'
  export default {
    name: 'RegistroGlosaMasivo',
    props: {
      glosas: {
        type: Array,
        default: []
      },
      items: {
        type: Array,
        default: []
      },
      disabled: {
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
      Loading,
      DetalleItemsFacturados,
      RegistroGlosa: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/RegistroGlosa')
    },
    data: () => ({
      itemsFacturados: [],
      dialog: false,
      loading: false
    }),
    computed: {
      unicoR () {
        return this.items && this.items.length === 1
      }
    },
    watch: {
      'items': {
        handler (val) {
          val && (this.itemsFacturados = val)
        },
        immediate: true
      }
    },
    methods: {
      cancelar () {
        this.dialog = false
        this.$emit('cancelar')
      },
      registraGlosas () {
        this.$emit('refreshFactura')
        this.cancelar()
      }
    }
  }
</script>

<style>
</style>
