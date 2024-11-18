<template>
  <div>
    <v-list two-line subheader>
      <v-subheader>Datos básicos</v-subheader>
      <v-layout row wrap>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.apellido1}}</v-list-tile-title>
              <v-list-tile-sub-title>Primer apellido</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.apellido2}}</v-list-tile-title>
              <v-list-tile-sub-title>Segundo apellido</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.nombre1}}</v-list-tile-title>
              <v-list-tile-sub-title>Primer nombre</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.nombre2}}</v-list-tile-title>
              <v-list-tile-sub-title>Segundo nombre</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.gn_tipdocidentidad_id && complementos.tipdocidentidades && complementos.tipdocidentidades.find(x => x.id === item.gn_tipdocidentidad_id).abreviatura}}</v-list-tile-title>
              <v-list-tile-sub-title>Tipo documento</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.identificacion}}</v-list-tile-title>
              <v-list-tile-sub-title>Documento</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.gn_sexo_id && complementos.sexos && complementos.sexos.find(x => x.id === item.gn_sexo_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Sexo</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.fecha_nacimiento | dateDMA}}</v-list-tile-title>
              <v-list-tile-sub-title>Fecha nacimiento</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.as_regimene_id && complementos.regimenes ? complementos.regimenes.find(x => x.id === item.as_regimene_id).nombre : ''}}</v-list-tile-title>
              <v-list-tile-sub-title>Régimen</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.estado_afiliado.codigo + ' - ' + item.estado_afiliado.nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Estado</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
      </v-layout>
    </v-list>
    <v-divider></v-divider>
    <v-list two-line subheader>
      <v-subheader>Datos complementarios</v-subheader>
      <v-layout row wrap>
        <v-flex xs12 sm6 md3 v-if="item.as_etnia_id">
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.as_etnia_id && complementos.etnias && complementos.etnias.find(x => x.id === item.as_etnia_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Etnia</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs12 sm6 md3 v-if="item.as_tipo_discapacidade_id">
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{(complementos.tiposdiscapacidad && complementos.condicionesdiscapacidad) ? complementos.tiposdiscapacidad.find(x => x.id === item.as_tipo_discapacidade_id).nombre + ' - ' + complementos.condicionesdiscapacidad.find(x => x.id === item.as_condicion_discapacidades_id).nombre : ''}}</v-list-tile-title>
              <v-list-tile-sub-title>Discapacidad</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.puntaje_sisben}}</v-list-tile-title>
              <v-list-tile-sub-title>Puntaje SISBEN</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="item.as_pobespeciale_id">
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.as_pobespeciale_id && complementos.pobespeciales && complementos.pobespeciales.find(x => x.id === item.as_pobespeciale_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Población especial</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="item.as_arl_id">
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.as_arl_id && complementos.arls && complementos.arls.find(x => x.id === item.as_arl_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Administradora de riesgos - ARL</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="item.as_afp_id">
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.as_afp_id && complementos.afps && complementos.afps.find(x => x.id === item.as_afp_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Administradora de pensiones - AFP</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
      </v-layout>
    </v-list>
    <v-divider></v-divider>
    <v-list two-line subheader>
      <v-subheader>Datos de localización</v-subheader>
      <v-layout row wrap>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.direccion}}</v-list-tile-title>
              <v-list-tile-sub-title>Dirección</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.telefono_fijo}}</v-list-tile-title>
              <v-list-tile-sub-title>Teléfono fijo</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.celular}}</v-list-tile-title>
              <v-list-tile-sub-title>Teléfono celular</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.correo_electronico}}</v-list-tile-title>
              <v-list-tile-sub-title>Correo electrónico</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.gn_municipio_id && complementos.municipios && complementos.municipios.find(x => x.id === item.gn_municipio_id).nombre_departamento}}</v-list-tile-title>
              <v-list-tile-sub-title>Departamento</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.gn_municipio_id && complementos.municipios && complementos.municipios.find(x => x.id === item.gn_municipio_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Municipio</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.gn_zona_id && complementos.zonas && complementos.zonas.find(x => x.id === item.gn_zona_id).nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Zona</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
        <v-flex xs6 sm3 md3>
          <v-list-tile avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{item.nombre_vereda}}</v-list-tile-title>
              <v-list-tile-sub-title>Vereda</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-flex>
      </v-layout>
    </v-list>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DetalleAfiliado',
    props: ['item'],
    data () {
      return {
        visible: false
      }
    },
    filters: {
      dateDMA (val) {
        let date = val.split('-')
        return date[2] + '/' + date[1] + '/' + date[0]
      }
    },
    mounted () {
      this.visible = true
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAfiliados
      }
    }
  }
</script>

<style scoped>

</style>
