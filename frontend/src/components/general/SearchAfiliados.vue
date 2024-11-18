<template>
  <v-menu
    transition="slide-x-reverse-transition"
    v-model="menuAfiliado"
    :close-on-content-click="false"
    offset-x
  >
    <template v-slot:activator="{ on }">
      <v-btn
        slot="activator"
        style="z-index: 900000 !important; margin-top: 8em; margin-left: -2.8em"
        v-on="on"
        color="indigo"
        fab
        dark
        fixed
        top
        left
      >
        <v-icon right>search</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-container class="py-1 px-2" fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex dflex xs12>
            <postulador-v2
              style="width: 100%"
              no-data="Busqueda por nombre o número de documento."
              hint="identificacion_completa"
              item-text="nombre_completo"
              data-title="identificacion_completa"
              data-subtitle="nombre_completo"
              label="Afiliado"
              entidad="afiliados"
              v-model="afiliadoSeleccionado"
              name="Afiliado"
              no-btn-edit
              clearable
              :slot-selection='{
                      template:`
                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.nombre_completo}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                     }'
              :slot-prepend='afiliadoSeleccionado ? {
                                template:`<mini-card-detail style="margin-top: -4px;" :data="value.mini_afiliado" alone-icon></mini-card-detail>`,
                                props: [`value`]
                               } : null'
            />
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </v-menu>
</template>

<script>
  export default {
    name: 'SearchAfiliados',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data: () => ({
      menuAfiliado: false,
      afiliadoSeleccionado: null
    })
  }
</script>

<style scoped>

</style>
