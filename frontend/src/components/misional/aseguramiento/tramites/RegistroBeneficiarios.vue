<template>
  <v-card v-if="value" class="ma-0 pa-0" color="grey lighten-1">
    <v-layout row justify-center>
      <v-dialog v-model="dialogBorrarBeneficiario" persistent max-width="400">
        <v-card v-if="beneficiario">
          <v-card-text class="title font-weight-light">
            ¿Esta seguro de desvincular a <strong>{{beneficiario.beneficiario.nombre_completo}}</strong> de su lista de beneficiarios.?
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="cancelarBorrarBeneficiario">Cancelar</v-btn>
            <v-btn color="primary" @click.stop="borrarBeneficiario">Aceptar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-toolbar dense>
      <v-icon>face</v-icon>
      <v-toolbar-title>Beneficiarios registrados</v-toolbar-title>
      <v-spacer/>
      <v-tooltip top>
        <v-btn
          slot="activator"
          fab
          right
          small
          color="accent"
          @click.native="asignarBeneficiario(null)"
        >
          <v-icon>add</v-icon>
        </v-btn>
        <span>Agregar beneficiario</span>
      </v-tooltip>
    </v-toolbar>
    <v-slide-y-transition>
      <registro-beneficiario :property="property" ref="rBeneficiario"  :entidad="value.ips" :afilliatum="value.afiliado" :afitramiteid="value.id" :objeto="beneficiario" v-if="showFormBeneficiario" @cancelar="showFormBeneficiario = false" @registrar="val => returnBeneficiario(val)" />
    </v-slide-y-transition>
    <v-list two-line subheader>
      <v-subheader v-if="value.nucleo_familiar && value.nucleo_familiar.length === 0" inset>Sin beneficiarios</v-subheader>
      <template v-else v-for="(item, index) in value.nucleo_familiar">
        <v-divider inset />
        <v-list-tile :key="index" avatar>
          <v-list-tile-avatar color="primary">
            <span class="white--text headline">{{ (index + 1) }}</span>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title v-html="item.beneficiario.nombre_completo"/>
            <v-list-tile-sub-title v-html="item.beneficiario.identificacion_completa + ' - ' + item.parentesco.nombre"/>
          </v-list-tile-content>
          <v-tooltip top>
            <v-btn
              small
              slot="activator"
              ripple
              icon
              @click="asignarBeneficiario(item)"
            >
              <v-icon color="accent">mode_edit</v-icon>
            </v-btn>
            <span>Editar</span>
          </v-tooltip>
          <v-tooltip top>
            <v-btn
              small
              slot="activator"
              icon
              ripple
              @click="confirmaBorrarBeneficiario(item)"
            >
              <v-icon color="accent">close</v-icon>
            </v-btn>
            <span>Desvincular</span>
          </v-tooltip>
        </v-list-tile>
      </template>
    </v-list>
  </v-card>
</template>

<script>
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import RegistroBeneficiario from '@/components/misional/aseguramiento/tramites/RegistroBeneficiario'
  export default {
    name: 'RegistroBeneficiarios',
    props: ['value', 'rootPath'],
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        dialogBorrarBeneficiario: false,
        showFormBeneficiario: false,
        beneficiario: null
      }
    },
    components: {
      RegistroBeneficiario
    },
    watch: {
      'showFormBeneficiario' (val) {
        if (val === false) this.beneficiario = null
      }
    },
    computed: {
      property () {
        switch (this.rootPath) {
          case 'formtrasmovs' : {
            return 'as_formtrasmov_id'
          }
          case 'formafiliaciones' : {
            return 'as_formafiliacion_id'
          }
        }
      }
    },
    methods: {
      asignarBeneficiario (item) {
        this.showFormBeneficiario = true
        this.beneficiario = item
        this.$vuetify.goTo('#form-beneficiario')
      },
      confirmaBorrarBeneficiario (item) {
        this.beneficiario = item
        this.dialogBorrarBeneficiario = true
      },
      cancelarBorrarBeneficiario () {
        this.dialogBorrarBeneficiario = false
        setTimeout(() => { this.beneficiario = null }, 400)
      },
      borrarBeneficiario () {
        this.axios.delete('/beneficiarios/' + this.beneficiario.id)
          .then((response) => {
            if (response.status === 200) {
              let index = this.value.nucleo_familiar.findIndex(x => x.id === this.beneficiario.id)
              this.value.nucleo_familiar.splice(index, 1)
              this.cancelarBorrarBeneficiario()
            }
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar desvincular el beneficiario. ', error: e})
          })
      },
      returnBeneficiario (response) {
        let index = this.value.nucleo_familiar.findIndex(x => x.id === response.id)
        this.value.nucleo_familiar.splice(index === -1 ? 0 : index, index === -1 ? 0 : 1, response)
        this.showFormBeneficiario = false
      }
    }
  }
</script>

<style scoped>

</style>
