<template>
  <section>
    <v-dialog
      v-model="menuItem"
      fullscreen
      transition="dialog-bottom-transition"
      hide-overlay
    >
      <v-card
        tile
      >
        <v-toolbar card dark  color="primary"  >
          <v-toolbar-title>Menu</v-toolbar-title>
          <v-layout row wrap>
            <v-flex  xs8 offset-xs2 >
              <v-text-field
                ref="componentSearch"
                prepend-icon="search"
                label="Buscar..."
                clearable
                solo-inverted
                class="mx-3"
                flat
                v-model="search"
              />
            </v-flex>
          </v-layout>
          <v-btn icon dark @click.native="$store.commit('NAV_SET_MENU_ITEM')">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <div v-for="(modulo, key) in items" :key="key" md3>
          <v-subheader>{{key}}</v-subheader>
          <v-container fluid grid-list-md class="pt-0">
            <v-layout row wrap >
              <v-hover
                v-for="item in modulo"
                :key="item.id_componente"
                v-if="item.view_menu"
              >
                <v-flex slot-scope="{ hover }" md3 @click="$store.commit('NAV_ADD_ITEM', { ruta : item.ruta, titulo: item.titulo })">
                  <v-card
                    flat
                    hover
                    class="pt-1"
                    :class="hover ? 'elevation-5' : 'elevation-1'"
                  >
                    <v-list-tile :key="'vlist' + item.id_componente">
                      <v-icon class="pr-2">{{item.icono}}</v-icon>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="item.titulo"></v-list-tile-title>
                        <v-list-tile-sub-title v-html="item.descripcion"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </v-list-tile>
                    <v-card-actions class="pt-0 pb-1">
                      <v-spacer></v-spacer>
                      <v-tooltip top>
                        <v-btn
                          slot="activator"
                          icon
                          small
                          @click.stop="$store.dispatch('FAVORITO_ASIGNAR', item.id_componente)"
                        >
                          <v-icon v-if="item.favorito" color="yellow darken-2">star</v-icon>
                          <v-icon v-else color="grey lighten-1">star_border</v-icon>
                        </v-btn>
                        <span>{{item.favorito ? 'Desmarcar de favoritos' : 'Marcar como favorito'}}</span>
                      </v-tooltip>

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
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </v-hover>
            </v-layout>
          </v-container>
        </div>
        <v-divider></v-divider>
      </v-card>
    </v-dialog>
  </section>
</template>

<script>
  import {mapGetters} from 'vuex'
  export default {
    name: 'MenuApp',
    data: () => ({
      search: ''
    }),
    watch: {
      'menuItem' (val) {
        if (val) {
          setTimeout(() => {
            this.$refs.componentSearch.focus()
          }, 300)
        }
      },
      'search' (val) {
        if (val === null) this.search = ''
      }
    },
    computed: {
      ...mapGetters(['menuItem']),
      items () {
        return this.$store.getters.getComponentes(this.search)
      }
    }
  }
</script>

<style scoped>

</style>
