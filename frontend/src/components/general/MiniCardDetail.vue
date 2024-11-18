<template>
    <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
      <v-tooltip right>
        <span class="mr-2" slot="activator" style="font-size: x-large !important; cursor: pointer !important;" @click="data ? (data.id !== null ? $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: data.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}}) : null): null" v-text="data ? data.emoticon : ''"></span>
        <span v-text="data ? (data.id !== null ? 'Ver más información' : 'Paciente no registrado') : null"></span>
      </v-tooltip>
      <v-list-tile-content v-if="!aloneIcon">
          <v-list-tile-title class="body-2">
            <a @click.prevent="data ? (data.id !== null ? $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: data.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}}) : null): null">
              {{data ? data.nombre_completo : null}}
            </a>
          </v-list-tile-title>
        <v-list-tile-sub-title class="caption">
          <span class="font-weight-medium" v-text="data ? data.identificacion_completa : ''"></span>
        </v-list-tile-sub-title>
        <v-list-tile-sub-title class="caption">
          <div class="d-block pb-2" v-if="data">
            <v-tooltip v-if="data.badges.length" v-for="(item, i) in data.badges" right :key="`key-${i}`">
              <span slot="activator" class="cursor-pointer" v-text="item.emoticon"></span>
              <span v-text="item.title + (item.estado === null || item.estado === ' ' || (typeof item.estado === 'undefined') ? ' ' : ' / ' + item.estado)"></span>
            </v-tooltip>
          </div>
        </v-list-tile-sub-title>
      </v-list-tile-content>
    </v-list-tile>
</template>

<script>
  export default {
    name: 'MiniCardDetail',
    props: {
      data: {
        type: Object,
        default: null
      },
      aloneIcon: {
        type: Boolean,
        default: false
      }
    }
  }
</script>

<style scoped>

</style>
