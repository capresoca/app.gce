<template>
  <div>
    <v-toolbar dense class="elevation-1">
      <v-icon>file_copy</v-icon>
      <v-toolbar-title>{{ parametros.entidad.nombre }}</v-toolbar-title>
      <p></p>
      <small class="mt-2 ml-1"> {{ parametros.entidad.nombre_juzgado }}</small>
      <v-spacer/>
      <span class="pt-1 text-xs-right">
        <span class="title text-capitalize">Tutela</span>
        <p class="mb-0 caption">Estado: {{ parametros.entidad.estado }}</p>
      </span>
      <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
        <span class="subheading">N°</span>&nbsp;
        <span class="subheading" v-text="parametros.entidad.no_tutela"></span>
      </v-chip>
      <slot name="actions"></slot>
    </v-toolbar>

    <v-container
      class="pa-0 ma-0"
      fluid
      grid-list-sm
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
                      <v-list-tile-action>
                        <v-btn icon @click="$refs['dataComponent'][n].getData()">
                          <v-icon>cached</v-icon>
                        </v-btn>
                      </v-list-tile-action>
                    </v-list-tile>
                    <v-spacer></v-spacer>
                    <v-divider/>
                  </template>
                </v-list>
                <v-card-text>
                  <component ref="dataComponent" :is="item.component" :tutelaId="parametros.entidad.id"/>
                </v-card-text>
              </v-card>
            </v-window-item>
          </v-window>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
  export default {
    name: 'DetalleTutela',
    props: ['parametros'],
    data: () => ({
      window: 0,
      drawer: true,
      items: [
        { icon: 'folder_shared', avatarColor: 'primary', title: 'Contestaciones', subTitle: 'Contestaciones', component: () => import('@/components/administrativo/oficinaJuridica/tutelas/detalles/ContestacionesDetalle') },
        { icon: 'gavel', avatarColor: 'success', title: 'Fallos', subTitle: 'Fallos', component: () => import('@/components/administrativo/oficinaJuridica/tutelas/detalles/FallosDetalle') },
        { icon: 'assignment_return', avatarColor: 'danger', title: 'Impugnaciones', subTitle: 'Impugnaciones', component: () => import('@/components/administrativo/oficinaJuridica/tutelas/detalles/ImpugnacionesDetalle') },
        { icon: 'assignment_late', avatarColor: 'orange', title: 'Desacatos', subTitle: 'Desacatos', component: () => import('@/components/administrativo/oficinaJuridica/tutelas/detalles/DesacatosDetalle') },
        { icon: 'note_add', avatarColor: 'purple', title: 'Bitacoras', subTitle: 'Bitacoras', component: () => import('@/components/administrativo/oficinaJuridica/tutelas/detalles/BitacorasDetalle') }
      ]
    })
  }
</script>

<style scoped>
  .content-list-p0 .v-list{
    padding: 0 !important;
  }
</style>
