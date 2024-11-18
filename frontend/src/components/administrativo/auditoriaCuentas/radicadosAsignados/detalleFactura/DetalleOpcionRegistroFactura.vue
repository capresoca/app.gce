<template>
  <v-tooltip top>
    <v-avatar
      size="24"
      :color="`${colorAvatar ? colorAvatar : 'blue-grey'} ${!colorAvatar && data ? 'lighten-1' : 'lighten-4'}`"
      class="white--text cursor-pointer"
      slot="activator"
      @click="$emit('click')"
    >
      <v-badge
        v-if="data && contentBadge"
        class="badge-options-facservicios"
        color="red darken-3"
      >
        <template v-slot:badge>
          <template>
            <span class="caption" v-if="typeContentBadge === 'text'">{{contentBadge}}</span>
            <v-icon v-else size="10" class="white--text">{{contentBadge}}</v-icon>
          </template>
        </template>
        <template>
          <span class="body-2" v-if="typeContentAvatar === 'text'">{{contentAvatar}}</span>
          <v-icon size="18" class="white--text" v-else>{{contentAvatar}}</v-icon>
        </template>
      </v-badge>
      <template v-else>
        <span class="body-2" v-if="typeContentAvatar === 'text'">{{contentAvatar}}</span>
        <v-icon size="18" class="white--text" v-else>{{contentAvatar}}</v-icon>
      </template>
    </v-avatar>
    <span>{{contentTooltip}}</span>
  </v-tooltip>
</template>

<script>
  export default {
    name: 'DetalleOpcionRegistroFactura',
    props: {
      data: {
        type: Boolean,
        default: false
      },
      contentTooltip: {
        type: [String, Number],
        default: ''
      },
      contentAvatar: {
        type: [String, Number],
        default: ''
      },
      typeContentAvatar: {
        type: String,
        default: 'text'
      },
      colorAvatar: {
        type: String,
        default: ''
      },
      contentBadge: {
        type: [String, Number],
        default: ''
      },
      typeContentBadge: {
        type: String,
        default: 'text'
      }
    }
  }
</script>

<style>
</style>
