<template>
  <v-data-table
    :items="items"
    class="elevation-0"
    hide-actions
    :headers="headers"
  >
    <template slot="items" slot-scope="props">
      <td>{{props.item.FNov}}</td>
      <td>{{props.item.tipo}}</td>
      <template v-if="typeof props.item.mp_tutela_id === 'undefined'">
        <td>
          <span v-if="props.item.NoPrescripcion === item.NoPrescripcion">{{props.item.NoPrescripcion}}</span>
          <a
            v-else
            @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detallePrescripcion', titulo: 'Detalle prescripción', parametros: {entidad: {id: props.item.mp_prescripcion_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
          >{{props.item.NoPrescripcion}}</a>
        </td>
        <td>
          <span v-if="props.item.NoPrescripcionF === item.NoPrescripcion">{{props.item.NoPrescripcionF}}</span>
          <a
            v-else
            @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detallePrescripcion', titulo: 'Detalle prescripción', parametros: {entidad: {id: props.item.mp_prescripcionFinal_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
          >{{props.item.NoPrescripcionF}}</a>
        </td>
      </template>
      <template v-else>
        <td>
          <span v-if="props.item.NoPrescripcion === item.NoTutela">{{props.item.NoPrescripcion}}</span>
          <a
            v-else
            @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutelaMipres', titulo: 'Detalle tutela MIPRES', parametros: {entidad: {id: props.item.mp_tutela_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
          >{{props.item.NoPrescripcion}}</a>
        </td>
        <td>
          <span v-if="props.item.NoPrescripcionF === item.NoTutela">{{props.item.NoPrescripcionF}}</span>
          <a
            v-else
            @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutelaMipres', titulo: 'Detalle tutela MIPRES', parametros: {entidad: {id: props.item.mp_tutelafinal_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
          >{{props.item.NoPrescripcionF}}</a>
        </td>
      </template>
    </template>
  </v-data-table>
</template>

<script>
    export default {
      name: 'Novedades',
      props: ['items', 'item'],
      data: () => ({
        headers: [
          {
            text: 'Fecha',
            sortable: false
          },
          {
            text: 'Tipo',
            sortable: false
          },
          {
            text: 'Prescripción Inicial',
            sortable: false
          },
          {
            text: 'Prescripción Final',
            sortable: false
          }
        ]
      })
    }
</script>

<style scoped>

</style>
