<template>
  <v-toolbar dense class="elevation-0">
    <v-icon v-if="info">{{info ? (!iconom ? info.icono : iconom) : ''}}</v-icon>
    <v-toolbar-title v-if="title" v-html="title"/>
    <small class="mt-2 ml-1" v-if="subtitle"> {{subtitle}}</small>
    <v-spacer/>
    <v-tooltip top style="z-index: 9999 !important;">
      <v-btn
        slot="activator"
        fab
        right
        small
        color="accent"
        @click="showBtnplusTruncate ? $emit('click') : $store.commit('NAV_ADD_ITEM',
        { ruta: info.ruta_registro,
          titulo: info.titulo_registro,
          parametros: {
            entidad: null,
            tabOrigin: $store.state.nav.currentItem.split('tab-')[1]
          }
        })"
        v-if="(info && showBtnplus) ? info.permisos.agregar : false">
        <v-icon v-text="!btnicon ? 'add' : btnicon"></v-icon>
      </v-btn>
      <span>{{btnplustitle}}</span>
    </v-tooltip>
    <slot name="actions"></slot>
  </v-toolbar>
</template>

<script>
  export default {
    name: 'SnackBar',
    props: ['info', 'title', 'subtitle', 'btnplus', 'btnplustruncate', 'btnplustitle', 'btnicon', 'iconom'],
    data () {
      return {
      }
    },
    computed: {
      showBtnplus () {
        return typeof this.btnplus !== 'undefined'
      },
      showBtnplusTruncate () {
        return typeof this.btnplustruncate !== 'undefined'
      }
    }
  }
</script>

<style scoped>

</style>
