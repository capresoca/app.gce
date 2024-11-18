<template>
    <v-form data-vv-scope="formTabEps">
      <v-card>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm6>
              <v-text-field v-model="value.codhabilitacion_subsid"
                            label="Código Habilitación Subsidiado" key="código habilitación subsidiado"
                            name="código habilitación subsidiado" v-validate="'required|max:15'"
                            :error-messages="errors.collect('código habilitación subsidiado')"
                            :disabled="!value.id ? false : !isEditing"></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field v-model="value.codhabilitacion_contrib"
                            label="Código Habilitación Contributivo" key="código habilitación contributivo"
                            name="código habilitación contributivo" v-validate="'required|max:50'"
                            :disabled="!value.id ? false : !isEditing"
                            :error-messages="errors.collect('código habilitación contributivo')"></v-text-field>
            </v-flex>
            <v-flex xs12>
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabEps"
                :label="value.gerente != null ? 'Gerente ' : 'gerente '"
                entidad="terceros"
                preicon="person"
                @changeid="val => value.gerente = val"
                v-model="value.ter_gerente"
                v-validate="'required'"
                name="gerente"
                :error-messages="errors.collect('gerente')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="3"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por nombre o número de documento."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="value.gerente != null ? 'Gerente ' : 'gerente '"-->
<!--                scope="formTabEps"-->
<!--                ref="postulaGerente"-->
<!--                entidad="terceros"-->
<!--                preicon="person"-->
<!--                @change="val => value.gerente = val"-->
<!--                @objectReturn="val => value.ter_gerente = val"-->
<!--                :value="value.ter_gerente"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                clearable></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabEps"
                :label="value.subgerente_servasistencial != null ? 'Subgerente Servicios Asistenciales ' : 'subgerente de servicios asistenciales '"
                entidad="terceros"
                preicon="person_outline"
                @changeid="val => value.subgerente_servasistencial = val"
                v-model="value.subger_servasistencial"
                v-validate="'required'"
                name="subgerente de servicios asistenciales"
                :error-messages="errors.collect('subgerente de servicios asistenciales')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="3"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por nombre o número de documento."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="value.subgerente_servasistencial != null ? 'Subgerente Servicios Asistenciales ' : 'subgerente de servicios asistenciales '"-->
<!--                scope="formTabEps"-->
<!--                ref="postulaSubgerasistencial"-->
<!--                entidad="terceros"-->
<!--                preicon="person_outline"-->
<!--                @change="val => value.subgerente_servasistencial = val"-->
<!--                @objectReturn="val => value.subger_servasistencial = val"-->
<!--                :value="value.subger_servasistencial"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                clearable></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabEps"
                :label="value.subgerente_admin != null ? 'Subgerente Administrativo y Financiero ' : 'subgerente administrativo y financiero '"
                entidad="terceros"
                preicon="person_outline"
                @changeid="val => value.subgerente_admin = val"
                v-model="value.subger_admin"
                v-validate="'required'"
                name="subgerente administrativo y financiero"
                :error-messages="errors.collect('subgerente administrativo y financiero')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="3"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por nombre o número de documento."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="value.subgerente_admin != null ? 'Subgerente Administrativo y Financiero ' : 'subgerente administrativo y financiero '"-->
<!--                scope="formTabEps"-->
<!--                ref="postulaSubgeradmin"-->
<!--                entidad="terceros"-->
<!--                preicon="person_outline"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                @change="val => value.subgerente_admin = val"-->
<!--                @objectReturn="val => value.subger_admin = val"-->
<!--                :value="value.subger_admin"-->
<!--                clearable></postulador>-->
            </v-flex>
          </v-layout>
        </v-container>
        <!--<v-card-text>Eps</v-card-text>-->
      </v-card>

    </v-form>
</template>

<script>
  export default {
    name: 'FormEps',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
      }
    },
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
      // Postulador: () => import('@/components/general/Postulador')
    },
    methods: {
      validate () {
        return this.$validator.validateAll('formTabEps').then(result => { return result })
      }
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabEps').length > 0))
      }
    }
  }
</script>

<style scoped>

</style>
