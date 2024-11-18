<template>
  <v-container
    class="pa-0 ma-0"
    fluid
    grid-list-xl
  >
    <v-layout row wrap>
      <v-flex sm12 md3>
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
      <v-flex sm12 md9>
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
    name: 'ConfiguracionesBasicas',
    data: () => ({
      window: 0,
      drawer: true,
      items: [
        {
          icon: 'store_mall_directory',
          avatarColor: 'primary',
          title: 'Centros de costo',
          subTitle: 'Mantnimiento TH',
          component: () => import('@/components/administrativo/TalentoHumano/ConfiguracionesBasicas/TbCentroCosto')
        },
        {
          icon: 'fas fa-square',
          avatarColor: 'accent',
          title: 'Areas',
          subTitle: 'Mantenimiento TH',
          component: () => import('@/components/administrativo/TalentoHumano/ConfiguracionesBasicas/TbArea')
        },
        {
          icon: 'fas fa-compress-alt',
          avatarColor: 'teal',
          title: 'Dependencias',
          subTitle: 'Mantenimiento TH',
          component: () => import('@/components/administrativo/TalentoHumano/ConfiguracionesBasicas/TbDependencia')
        },
        {
          icon: 'fas fa-id-card-alt',
          avatarColor: 'info',
          title: 'Cargos',
          subTitle: 'Mantenimiento TH',
          component: () => import('@/components/administrativo/TalentoHumano/ConfiguracionesBasicas/TbCargo')
        },
        {
          icon: 'fas fa-file-archive',
          avatarColor: 'amber darken-1',
          title: 'Tipos de Contratos',
          subTitle: 'Mantenimiento TH',
          component: () => import('@/components/administrativo/TalentoHumano/ConfiguracionesBasicas/TbTipoContrato')
        }

      ]
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('configbasicTHumano')
      }
    }
  }
</script>

<style scoped>
  .content-list-p0 .v-list {
    padding: 0 !important;
  }
</style>

