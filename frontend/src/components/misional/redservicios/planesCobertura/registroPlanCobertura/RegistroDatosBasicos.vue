<template>
  <div>
    <v-layout row wrap>
      <v-flex xs12 sm12 md12>
        <input-detail-flex
          v-if="contrato"
          label="Contrato"
          :text="contrato.numero_contrato"
          :hint="contrato.entidad.nombre"
          prepend-icon="location_city"
        ></input-detail-flex>
<!--        <postulador-v2-->
<!--          v-else-->
<!--          no-data="Busqueda por NIT, Cod Habilitacion u Objeto."-->
<!--          hint="entidad.nombre"-->
<!--          item-text="numero_contrato"-->
<!--          data-title="entidad.nombre"-->
<!--          data-subtitle="numero_contrato"-->
<!--          label="Contrato"-->
<!--          entidad="minutas"-->
<!--          preicon="location_city"-->
<!--          @changeid="val => value.ce_proconminuta_id = val"-->
<!--          v-model="value.contrato"-->
<!--          name="Contrato"-->
<!--          rules="required"-->
<!--          v-validate="'required'"-->
<!--          :error-messages="errors.collect('Contrato')"-->
<!--          no-btn-create-->
<!--          :min-characters-search="3"-->
<!--          :readonly="!!value.actaInicio"-->
<!--        />-->
        <postulador-v3
          :readonly="!!value.actaInicio"
          no-data-text="Busqueda por NIT, Cod Habilitacion u Objeto."
          :hint="value.contrato ? `${value.contrato.entidad.nombre}` : ''"
          item-text="numero_contrato"
          label="Contrato"
          route="minutas"
          model="minutas"
          filter="numero_contrato,entidad.nombre"
          prepend-icon="location_city"
          v-model="value.contrato"
          @input="val => value.ce_proconminuta_id = val ? val.id : null"
          name="contrato"
          v-validate="`required`"
          :error-messages="errors.collect('contrato')"
          :slot-data='{
                      template:`
                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.entidad.nombre}}</v-list-tile-title>
                            <v-list-tile-sub-title>Contrato: {{value.numero_contrato}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                     }'
        ></postulador-v3>
      </v-flex>
      <v-flex xs12 sm12 md12>
        <v-textarea
          v-model="value.nombre"
          auto-grow
          label="Nombre"
          rows="1"
          name="Nombre"
          v-validate="'required'"
          :error-messages="errors.collect('Nombre')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12>
        <!--v-validate="'required'"-->
        <v-select
          label="Rubro"
          v-model="value.pr_detrp_id"
          :items="value.contrato && value.contrato.rp ? value.contrato.rp.detalles : []"
          name="Rubro"
          item-value="id"
          item-text="rubro.nombre"
          v-validate="''"
          :error-messages="errors.collect('Rubro')"
          :readonly="!!value.actaInicio"
        >
          <template slot="no-data">
            <div class="px-3">
              <v-icon color="danger">warning</v-icon> El contrato no tiene registro presupuestal
            </div>
          </template>
          <template slot="selection" slot-scope="data">
            <div style="width: 100% !important;" class="content-v-list-tile-p0">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title class="body-1 font-weight-bold">{{data.item.rubro.nombre}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.rubro.codigo}}</v-list-tile-sub-title>
                  <v-list-tile-sub-title class=caption>Disponible: {{currency(data.item.valor_disponible)}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-divider/>
            </div>
          </template>
          <template slot="item" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title class="body-1">{{data.item.rubro.nombre}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.rubro.codigo}}</v-list-tile-sub-title>
                  <v-list-tile-sub-title class=caption>Disponible: {{currency(data.item.valor_disponible)}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-divider/>
            </div>
          </template>
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md6>
        <v-select
          label="Regimen"
          v-model="value.regimen_atendido"
          :items="regimenes"
          name="Regimen"
          item-value="id"
          item-text="nombre"
          v-validate="'required'"
          :error-messages="errors.collect('Regimen')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex dflex>
        <v-checkbox
          label="Portabilidad"
          v-model="value.portabilidad"
          :false-value="0"
          :true-value="1"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroContratoDatosBasicos',
    props: ['value', 'contrato'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data () {
      return {
        DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad'),
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        regimenes: [
          {id: 1, nombre: 'Contributivo'},
          {id: 2, nombre: 'Subsidiado'}
        ]
      }
    },
    watch: {
    },
    computed: {
      complementos () {
        return store.getters.complementosContratoFormDatosBasica
      }
    },
    methods: {
      async validate () {
        let validado = await this.$validator.validateAll()
        return validado
      },
      reset () {
        return this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
