<template>
  <v-navigation-drawer
    v-model="activo"
    right
    fixed
    temporary
    disable-resize-watcher
  >
    <v-list class="pa-1" dense>
      <v-list-tile avatar>
        <v-list-tile-avatar color="yellow darken-2">
          <v-icon >star</v-icon>
        </v-list-tile-avatar>

        <v-list-tile-content>
          <v-list-tile-title>Menu Favoritos</v-list-tile-title>
        </v-list-tile-content>
        <v-list-tile-action>
          <v-btn
            flat
            icon
            @click="activo = false"
          >
            <v-icon>close</v-icon>
          </v-btn>
        </v-list-tile-action>
      </v-list-tile>
    </v-list>
    <v-divider/>
    <div
      v-if="!Object.entries(items).length"
      class="title grey--text text-xs-center font-weight-light px-2 pt-4"
    >
      No hay items favoritos.
    </div>
    <v-list class="pa-1" v-else>
      <template v-for="(modulo, key) in items">
        <v-subheader :key="'subheader' + key">
          {{ key }}
        </v-subheader>
        <v-list-tile
          dense
          v-for="item in modulo"
          :key="'vlist' + item.id_componente"
          v-if="item.view_menu"
          @click="() => {
          $store.commit('NAV_ADD_ITEM', { ruta : item.ruta, titulo: item.titulo })
          activo = false
          }"
        >
          <v-icon :color="item.color" class="pr-2">label_important</v-icon>

          <v-list-tile-content>
            <v-list-tile-title>{{item.titulo}}</v-list-tile-title>
          </v-list-tile-content>
          <v-list-tile-action>
            <v-tooltip
              top
              v-if="item.ruta_registro && item.titulo_registro"
            >
              <v-btn
                slot="activator"
                icon
                small
                @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: item.ruta_registro, titulo: item.titulo_registro, required: true, parametros: { entidad: null, tabOrigin: null } })"
              >
                <v-icon color="primary">add</v-icon>
              </v-btn>
              <span>Crear registro</span>
            </v-tooltip>
          </v-list-tile-action>
        </v-list-tile>
        <v-divider/>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
    name: 'MenuFavoritos',
    props: ['value'],
    data: () => ({
      activo: false
    }),
    watch: {
      'activo' (val) {
        this.$emit('input', val)
      },
      'value' (val) {
        this.activo = val
      }
    },
    computed: {
      items () {
        return this.$store.getters.getComponentesFavoritos()
      }
    }
  }
</script>

<style scoped>

</style>
