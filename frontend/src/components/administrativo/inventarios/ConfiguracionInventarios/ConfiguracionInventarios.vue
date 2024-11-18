<template>
  <v-container
    class="pa-0 ma-0"
    fluid
    grid-list-xl
  >
    <v-layout row wrap>
      <v-flex sm12 md4>
        <v-card class="content-list-p0">
          <v-list two-line>
            <template v-for="(item, index) in items">
              <v-list-tile @click="window = index" :key="item.text" :class="window === index ? 'grey lighten-2' : ''">
                <v-list-tile-avatar :color="item.avatarColor">
                  <v-icon dark>{{ item.icon }}</v-icon>
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title>
                    {{ item.title }}
                  </v-list-tile-title>
                  <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-divider inset/>
            </template>
          </v-list>
        </v-card>
      </v-flex>
      <v-flex sm12 md8>
        <v-window
          v-model="window"
          class="elevation-1"
          vertical
        >
          <v-window-item
            v-for="(item, n) in items"
            :key="n"
            lazy
          >
            <v-card flat class="content-list-p0 pt-0">
              <v-list two-line>
                <template>
                  <v-list-tile>

                    <v-list-tile-avatar :color="item.avatarColor">
                      <v-icon dark>{{ item.icon }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ item.title }}
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <v-card-text>
                <component ref="dataComponent" :is="item.component"/>
              </v-card-text>
            </v-card>
          </v-window-item>
        </v-window>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    name: 'ConfiguracionInventario',
    props: ['parametros'],
    data: () => ({
      window: 0,
      drawer: true,
      items: [
        { icon: 'view_stream', avatarColor: 'primary', title: 'Grupos de inventarios', subTitle: 'Grupos de inventarios', component: () => import('@/components/administrativo/inventarios/Grupos/Grupos') },
        { icon: 'view_module', avatarColor: 'primary', title: 'Subgrupos de inventarios', subTitle: 'Subgrupos de inventarios', component: () => import('@/components/administrativo/inventarios/Subgrupos/Subgrupos') },
        { icon: 'add_shopping_cart', avatarColor: 'primary', title: 'Almacén de inventarios', subTitle: 'Almacén de inventarios', component: () => import('@/components/administrativo/inventarios/Almacen/Almacen') },
        { icon: 'done_outline', avatarColor: 'primary', title: 'UMI', subTitle: 'Unidades de medida de inventario', component: () => import('@/components/administrativo/inventarios/UnidadMedida/UnidadMedida') }

      ]
    })
  }
</script>

<style scoped>
  .content-list-p0 .v-list{
    padding: 0 !important;
  }
</style>
