<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1000">
      <v-card>
        <v-card-title class="headline"><v-icon>child_care</v-icon> {{value.id ? ' Actualización' : ' Creación'}} de recién nacido</v-card-title>
        <v-divider/>
        <v-container fluid grid-list-xl>
          <form-info-basica-afiliado v-model="value" ref="infobasicoafiliado" :tipos-identidad="complementos.tipdocidentidades"/>
          <v-form data-vv-scope="formRN">
            <v-layout row wrap>
              <v-flex xs12 sm6 md6>
                <v-select
                  label="Condición de Discapacidad"
                  v-model="value.as_condicion_discapacidades_id"
                  :items="complementos.condicionesdiscapacidad"
                  item-value="id"
                  item-text="nombre"
                  name="Condición de Discapacidad"
                  clearable
                />
              </v-flex>
            </v-layout>
          </v-form>
        </v-container>
        <v-divider/>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
          <v-btn color="primary" @click.stop="submit">Registrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
  import FormInfoBasicaAfiliado from '@/components/misional/aseguramiento/tramites/FormInfoBasicaAfiliado'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'FormRecienNacido',
    props: {
      dialog: {
        type: Boolean,
        default: false
      },
      value: {
        type: Object,
        default: function () {
          return {
            as_afitramite_id: null,
            as_beneficiario_id: null,
            beneficiario: {},
            id: null,
            gn_tipdocidentidad_id: null,
            identificacion: null,
            gn_munexpedicion_id: null,
            as_condicion_discapacidades_id: null,
            as_parentesco_id: null,
            gn_sexo_id: null,
            nombre1: null,
            nombre2: null,
            apellido1: null,
            apellido2: null,
            fecha_nacimiento: null
          }
        }
      },
      afitramitern: Object
    },
    components: {
      FormInfoBasicaAfiliado
    },
    filters: {
    },
    data () {
      return {
        idReferencia: null
      }
    },
    watch: {
      'value' (val) {
        if (val.id) this.idReferencia = val.id
        this.$validator.reset()
        this.$emit('input', this.value)
      },
      'value.as_condicion_discapacidades_id' (val) {
        if (typeof val === 'undefined') this.value.as_condicion_discapacidades_id = null
      }
    },
    mounted () {
      if (this.afitramitern) {
        this.value.responsable_id = this.afitramitern.responsable_id
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAfiliacionRN
      }
    },
    methods: {
      async submit () {
        let infoBasica = await this.$refs['infobasicoafiliado'].validate()
        let infoForm = await this.$validator.validateAll('formRN')
        if (infoBasica && infoForm) {
          if (this.afitramitern.recien_nacidos.length ? this.afitramitern.recien_nacidos.find(x => (x.identificacion === this.value.identificacion) && ((this.idReferencia !== null ? this.idReferencia !== this.value.id : this.idReferencia === this.value.id))) : null) {
            this.$store.commit(SNACK_SHOW, {msg: 'Ya hay un recién nacido registrado con ésta identificación.', color: 'danger'})
          } else {
            this.$emit('download', this.value)
          }
        }
      }
    }
  }
</script>

<style scoped>
</style>
