<template>
  <v-container fluid grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 class="pa-0">
        <v-subheader>Datos básicos</v-subheader>
      </v-flex>
      <input-detail-flex
        label="Tipo documento"
        :text="item.tipo_id && item.tipo_id.nombre"
      />
      <input-detail-flex
        label="Identificación"
        :text="item.identificacion"
      />
      <input-detail-flex
        label="Primer apellido"
        :text="item.apellido1"
      />
      <input-detail-flex
        label="Segundo apellido"
        :text="item.apellido2"
      />
      <input-detail-flex
        label="Primer nombre"
        :text="item.nombre1"
      />
      <input-detail-flex
        label="Segundo nombre"
        :text="item.nombre2"
      />
    </v-layout>
    <v-layout row wrap key="seccion2">
      <v-flex xs12 class="pa-0">
        <v-divider/>
        <v-subheader>Datos complementarios</v-subheader>
      </v-flex>
      <input-detail-flex
        label="Departamento"
        :text="item.gn_municipio_id && complementos.complementarios.municipios && complementos.complementarios.municipios.find(x => x.id === item.gn_municipio_id).nombre_departamento"
      />
      <input-detail-flex
        label="Municipio"
        :text="item.gn_municipio_id && complementos.complementarios.municipios && complementos.complementarios.municipios.find(x => x.id === item.gn_municipio_id).nombre"
      />
      <input-detail-flex
        label="Dirección"
        :text="item.direccion"
      />
      <input-detail-flex
        label="Teléfono fijo"
        :text="item.telefono_fijo"
      />
      <input-detail-flex
        label="Teléfono celular"
        :text="item.celular"
      />
      <input-detail-flex
        label="Correo electrónico"
        :text="item.correo_electronico"
      />
    </v-layout>
    <v-layout row wrap key="seccion3">
      <v-flex xs12 class="pa-0">
        <v-divider/>
        <v-subheader>Datos fiscales</v-subheader>
      </v-flex>
      <input-detail-flex
        label="Tipo persona"
        :text="item.tipo_persona && complementos.fiscales.tipos_persona && complementos.fiscales.tipos_persona.find(x => x === item.tipo_persona)"
      />
      <input-detail-flex
        label="Tipo contribuyente"
        :text="item.tipo_contribuyente && complementos.fiscales.tipos_contribuyente && complementos.fiscales.tipos_contribuyente.find(x => x === item.tipo_contribuyente)"
      />
      <input-detail-flex
        label="Tipo retención"
        :text="item.tipo_retencion && complementos.fiscales.tipos_retencion && complementos.fiscales.tipos_retencion.find(x => x === item.tipo_retencion)"
      />
      <input-detail-flex
        flex-class="xs12 sm12 md6"
        v-if="item.tipo_retencion === 'Autorretenedor'"
        label="Autorretenedor"
        :text="item.autorretenedor.join(', ')"
      />
      <input-detail-flex
        flex-class="xs12 sm12 md6"
        label="Actividad económica"
        :text="item.nf_ciiu_id ? complementos.fiscales.ciius && complementos.fiscales.ciius.find(x => x.id === item.nf_ciiu_id).nombre : ''"
      />
      <input-detail-flex
        v-if="item.ica"
        label="Porcentaje ICA"
        :text="item.porcentaje_ica + '%'"
      />
      <input-detail-flex
        label="Agente declarador"
        :text="item.agente_declarante ? 'SI' : 'NO'"
      />
    </v-layout>
  </v-container>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DetalleTercero',
    props: ['item'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    computed: {
      complementos () {
        return {
          basicos: store.getters.complementosTercerosFormBasicos,
          complementarios: store.getters.complementosTercerosFormComplementarios,
          fiscales: store.getters.complementosTercerosFormFiscal
        }
      }
    }
  }
</script>

<style scoped>

</style>
