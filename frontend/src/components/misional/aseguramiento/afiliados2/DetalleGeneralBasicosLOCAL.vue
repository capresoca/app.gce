<template>
  <v-container fluid grid-list-xl class="pa-0">
    <loading v-model="loading" />
    <v-slide-y-transition group>
      <v-layout row wrap v-if="item" key="seccion1">
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
          label="Fecha expedición"
          :text="item.fecha_expedicion | dateDMA"
        />
        <input-detail-flex
          label="Fecha nacimiento"
          :text="item.fecha_nacimiento | dateDMA"
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
        <input-detail-flex
          label="Sexo"
          :text="item.sexo && item.sexo.nombre"
        />
        <input-detail-flex
          label="Etnia"
          :text="item.etnia && item.etnia.nombre"
        />
        <input-detail-flex
          label="Pais procedencia"
          :text="item.gn_paise_id && complementos.paises && complementos.paises.find(x => x.id === item.gn_paise_id).nombre"
        />
      </v-layout>
      <v-layout row wrap v-if="item" key="seccion2">
        <v-flex xs12 class="pa-0">
          <v-divider/>
          <v-subheader>Contacto y domicilio</v-subheader>
        </v-flex>
        <input-detail-flex
          label="Departamento"
          :text="item.gn_municipio_id && complementos.municipios && complementos.municipios.find(x => x.id === item.gn_municipio_id).nombre_departamento"
        />
        <input-detail-flex
          label="Municipio"
          :text="item.gn_municipio_id && complementos.municipios && complementos.municipios.find(x => x.id === item.gn_municipio_id).nombre"
        />
        <input-detail-flex
          label="Zona"
          :text="item.zona && item.zona.nombre"
        />
        <input-detail-flex
          :label="item.gn_zona_id && (item.gn_zona_id === 1 ? 'Barrio' : 'Vereda')"
          :text="item.gn_zona_id && (item.gn_zona_id === 1 ? (item.barrio && item.barrio.nombre) : (item.vereda && item.vereda.nombre))"
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
      <v-layout row wrap v-if="item" key="seccion3">
        <v-flex xs12 class="pa-0">
          <v-divider/>
          <v-subheader>Datos adicionales</v-subheader>
        </v-flex>
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Condición de discapacidad"
          :text="item.condicion_discapacidad && item.condicion_discapacidad.nombre"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Población especial"
          :text="item.poblacion_especial && item.poblacion_especial.nombre"
        />
        <input-detail-flex
          label="Ficha SISBEN"
          :text="item.ficha_sisben"
        />
        <input-detail-flex
          label="Puntaje SISBEN"
          :text="item.puntaje_sisben"
        />
        <input-detail-flex
          label="Nivel SISBEN"
          :text="item.nivel_sisben"
        />
        <input-detail-flex
          label="Zona DNP"
          :text="item.zona_dnp && item.zona_dnp.nombre"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Caja de compensación"
          :text="item.ccf && item.ccf.nombre"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Administradora de fondos de pensión"
          :text="item.afp && item.afp.nombre"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Administradora de riesgos profesionales"
          :text="item.arl && item.arl.nombre"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md12"
          label="Actividad económica"
          :text="item.ciiu && (item.ciiu.codigo + ' - ' + item.ciiu.nombre)"
        />
      </v-layout>
    </v-slide-y-transition>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import store from '@/store/complementos/index'
  export default {
    name: 'DetalleGeneralBasicos',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    components: {
      Loading,
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data () {
      return {
        loading: false,
        item: null
      }
    },
    filters: {
      dateDMA (val) {
        if (val) {
          let date = val.split('-')
          return date[2] + '/' + date[1] + '/' + date[0]
        }
        return ''
      }
    },
    created () {
      this.afiliado && this.afiliado.id ? this.item = this.afiliado : this.getData()
    },
    computed: {
      complementos () {
        return store.getters.complementosDetalleGeneralBasicosAfiliado
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.afiliadoId}`)
          .then((response) => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos básicos del afiliado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
