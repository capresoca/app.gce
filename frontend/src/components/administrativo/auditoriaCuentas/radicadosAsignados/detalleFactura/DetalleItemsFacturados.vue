<template>
  <v-card>
    <v-card-title class="grey lighten-3 py-2">
      <span class="font-weight-bold"> {{items.length === 1 ? 'Item seleccionado' : 'Items seleccionados' }}</span>
    </v-card-title>
    <v-divider></v-divider>
    <v-data-table
      :items="items"
      hide-actions
    >
      <template slot="headers" slot-scope="props">
        <th class="text-xs-center" style="height: 0px !important;">item</th>
        <th class="text-xs-center" style="height: 0px !important;">Tipo</th>
        <th class="text-xs-left" style="height: 0px !important;">Código</th>
        <th class="text-xs-left" style="height: 0px !important;">Nombre</th>
        <th class="text-xs-center" style="height: 0px !important;">Cant.</th>
        <th class="text-xs-right" style="height: 0px !important;">Vl. Unit.</th>
        <th class="text-xs-right" style="height: 0px !important;">Vl. Total</th>
        <th class="text-xs-right" style="height: 0px !important;">Vl. Glosado Actual</th>
        <th class="text-xs-center" style="height: 0px !important;" v-if="items.length > 1"></th>
      </template>
      <template slot="items" slot-scope="props">
        <td class="px-3" style="height: 0px !important;">
          <v-avatar color="teal" :size="32">
            <span class="white--text" :class="items.length > 9 ? 'caption' : 'headline'">{{ props.index + 1 }}</span>
          </v-avatar>
        </td>
        <td class="px-3" style="height: 0px !important;">
          {{ props.item.tipo }}
        </td>
        <td class="px-3" style="height: 0px !important;">
          {{ props.item.codigo }}
        </td>
        <td class="px-3" style="height: 0px !important;">
          {{ props.item.nombre }}
        </td>
        <td class="px-3 text-xs-center" style="height: 0px !important;">
          {{ props.item.cantidad }}
        </td>
        <td class="px-3 text-xs-right" style="height: 0px !important;">
          {{ currency(props.item.valor_unitario) }}
        </td>
        <td class="px-3 text-xs-right" style="height: 0px !important;">
          {{currency(props.item.valor_total)}}
        </td>
        <td class="px-3 text-xs-right" style="height: 0px !important;">
          {{currency(lodash.sumBy(props.item.glosas, 'valor_glosa'))}}
        </td>
        <td class="text-xs-center" v-if="items.length > 1">
          <v-tooltip top>
            <v-btn
              small
              slot="activator"
              icon
              @click="items.splice(props.index, 1)"
            >
              <v-icon color="red" class="text--darken-2">delete</v-icon>
            </v-btn>
            <span>Quitar registro</span>
          </v-tooltip>
        </td>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
  export default {
    name: 'DetalleItemsFacturados',
    props: {
      items: {
        type: Array,
        default: []
      },
      capitado: {
        type: Boolean,
        default: false
      }
    },
    data: () => ({
      itemsServicios: []
    })
  }
</script>

<style scoped>

</style>
