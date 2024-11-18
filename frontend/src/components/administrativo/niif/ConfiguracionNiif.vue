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
        { icon: 'store_mall_directory', avatarColor: 'primary', title: 'Centros de costo', subTitle: 'Centros de costo', component: () => import('@/components/administrativo/niif/CentrosCosto') },
        { icon: 'receipt', avatarColor: 'primary', title: 'Tipos Comprobantes de Diario', subTitle: 'Tipos Comprobantes de Diario', component: () => import('@/components/administrativo/niif/TiposComdiario') },
        { icon: 'local_atm', avatarColor: 'primary', title: 'Conceptos RTF', subTitle: 'Conceptos RTF', component: () => import('@/components/administrativo/niif/conceptoRtf/ConRtfs') },
        { icon: 'assignment', avatarColor: 'primary', title: 'Anexos declaración', subTitle: 'Anexos declaración', component: () => import('@/components/administrativo/niif/puc/AnexosDeclaracion') }

      ]
    })
  }
</script>

<style scoped>
  .content-list-p0 .v-list{
    padding: 0 !important;
  }
</style>
