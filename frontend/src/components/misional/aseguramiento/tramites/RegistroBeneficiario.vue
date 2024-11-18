<template>
  <v-card color="blue lighten-4">
    <v-card-title>
      <strong>
        {{nucfamiliar.id ? 'actualización beneficiario' : 'nuevo beneficiario'}}
      </strong>
    </v-card-title>
    <v-divider/>
    <v-container fluid grid-list-lg>
      <v-form data-vv-scope="formBeneficiario">
        <v-layout row wrap>
          <v-flex xs12>
            <postulador-v2
              no-data="Busqueda por nombre o número de documento."
              hint="identificacion_completa"
              item-text="nombre_completo"
              data-title="identificacion_completa"
              data-subtitle="nombre_completo"
              label="Beneficiario"
              :detail="detalleAfiliado"
              entidad="afiliados"
              preicon="person"
              @changeid="val => nucfamiliar.as_beneficiario_id = val"
              v-model="nucfamiliar.beneficiario"
              name="Beneficiario"
              rules="required|beneficiarioValido"
              v-validate="'required|beneficiarioValido'"
              :error-messages="errors.collect('Beneficiario')"
              :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
              :slot-prepend='nucfamiliar.beneficiario ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
              :pros-datos="afilliatum ? afilliatum : null"
            />
          </v-flex>
          <v-flex xs12 sm6>
            <v-autocomplete
              label="Parentesco"
              v-model="nucfamiliar.as_parentesco_id"
              :items="complementos.parentescos"
              item-value="id"
              item-text="nombre"
              name="Parentesco"
              required
              v-validate="'required'"
              :error-messages="errors.collect('Parentesco')"
            ></v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6>
		    <postulador-v2
		      no-data="Busqueda por NIT, razon social u código de habilitación."
		      hint="identificacion"
		      item-text="nombre_razon_social"
		      data-title="identificacion"
		      data-subtitle="nombre_razon_social"
		      label="IPS"
		      entidad="entidades_contrato"
		      preicon="location_city"
		      @changeid="val => nucfamiliar.rs_entidade_id = val"
		      v-model="ipsB"
		      name="ips"
		      rules="required"
		      v-validate="'required'"
		      :error-messages="errors.collect('ips')"
		      :route-params="`rs_tipentidade_id=1&afiliado=${nucfamiliar.as_beneficiario_id}`"
		      no-btn-create
		      :min-characters-search="3"
		    />
		    
		  </v-flex>
          
        </v-layout>
      </v-form>
    </v-container>
    <v-card-actions>
      <v-spacer/>
      <v-btn flat @click.native="$emit('cancelar')">Cancelar</v-btn>
      <v-btn flat color="primary" @click.native="submit">Registrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  export default {
    props: ['entidad', 'afitramiteid', 'objeto', 'property', 'afilliatum'],
    name: 'RegistroBeneficiario',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        title: '',
        loading: false,
        nucfamiliar: {},
        ipsB: {
          nombre_razon_social: null,
          identificacion: null
        },
        idAfiliadoValido: null,
        make: {
          as_beneficiario_id: null,
          as_formafiliacion_id: null,
          as_formtrasmov_id: null,
          as_parentesco_id: null,
          beneficiario: null,
          id: null,
          ips: null,
          parentesco: null,
          rs_entidade_id: null,
          upc: null
        }
      }
    },
    watch: {
      'objeto' (val) {
        val ? this.asignaObjeto(val) : this.reset()
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormBeneficiario
      }
    },
    beforeMount () {
      Validator.extend('beneficiarioValido', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = {valido: true, mensaje: null}
            return resolve({
              valid: response.valido,
              data: {
                message: response.mensaje
              }
            })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    mounted () {
      console.log(this.entidad, this.afitramiteid, this.objeto, this.property)
      this.reset()
      if (this.objeto) this.asignaObjeto(this.objeto)
    },
    methods: {
      asignaObjeto (obj) {
        this.idAfiliadoValido = JSON.parse(JSON.stringify(obj.beneficiario.id))
        this.nucfamiliar = JSON.parse(JSON.stringify(obj))
      },
      asignaIPS (IPS) {
        this.nucfamiliar.rs_entidade_id = IPS.id
        this.nucfamiliar.ips = IPS
        // console.log(IPS)
        // this.ipsB = IPS
      },
      reset () {
        this.idAfiliadoValido = null
        this.$validator.reset()
        this.nucfamiliar = JSON.parse(JSON.stringify(this.make))
        this.ipsB = this.nucfamiliar.ips
        if (typeof this.entidad !== 'undefined' && this.entidad.id) this.asignaIPS(this.entidad)
        if (this.afitramiteid) this.nucfamiliar[this.property] = this.afitramiteid
      },
      submit () {
        this.$validator.validateAll('formBeneficiario').then(result => {
          if (result) {
            delete this.nucfamiliar.beneficiario
            delete this.nucfamiliar.ips
            delete this.nucfamiliar.parentesco
            this.axios.post('/beneficiarios', this.nucfamiliar)
              .then(response => {
                this.$emit('registrar', response.data.nucfamiliar)
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el beneficiario. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
