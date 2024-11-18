<template>
  <v-card>
    <v-container fluid grid-list-xl>
      <v-layout row wrap key="seccion1">
        <input-detail-flex
          label="Tipo entidad"
          :text="item.tipo && item.tipo.tipo"
        />
        <input-detail-flex
          label="Tipo locación"
          :text="item.tipo_locacion"
        />
        <input-detail-flex
          label="Resolución habilitación"
          :text="item.res_habilitacion"
        />
        <input-detail-flex
          label="Código habilitación"
          :text="item.cod_habilitacion"
        />
        <input-detail-flex
          label="Complejidad"
          :text="item.complejidad"
        />
        <input-detail-flex
          label="Naturaleza"
          :text="item.naturaleza"
        />
        <input-detail-flex
          label="Gerente"
          :text="item.gerente"
        />
        <input-detail-flex
          label="Representante legal"
          :text="item.replegal"
        />
        <input-detail-flex
          label="Departamento"
          :text="item.municipio && item.municipio.nombre_departamento"
        />
        <input-detail-flex
          label="Municipio"
          :text="item.municipio && item.municipio.nombre"
        />
        <input-detail-flex
          label="Dirección"
          :text="item.dirección_sede"
        />
        <input-detail-flex
          label="Correo electrónico"
          :text="item.correo_electronico_sede"
        />
        <input-detail-flex
          label="Teléfono"
          :text="item.telefono_sede"
        />
      </v-layout>
      <v-layout>
        <v-flex xs12 sm12 md12>
          <detalle-tercero v-if="item.tercero" :item="item.tercero"/>
          <v-subheader v-else>Tercero no disponible... <v-icon>sentiment_very_dissatisfied</v-icon></v-subheader>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DetallePagador',
    props: ['item'],
    components: {
      DetalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    computed: {
      complementos () {
        return store.getters.complementosFormPagadores
      }
    }
  }
</script>

<style scoped>

</style>
