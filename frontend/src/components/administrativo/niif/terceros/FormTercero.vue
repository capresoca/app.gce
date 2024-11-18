<template>
  <v-stepper v-model="stepActual">
    <v-stepper-header>
      <v-stepper-step :complete="stepActual > 1" step="1" editable :rules="[() => validStepBasicos]">Datos Básicos <small v-if="!validStepBasicos">Hay datos por diligenciar.</small></v-stepper-step>
      <v-divider/>
      <v-stepper-step :complete="stepActual > 2" step="2" editable :rules="[() => validStepComplementarios]">Datos Complementarios <small v-if="!validStepComplementarios">Hay datos por diligenciar.</small></v-stepper-step>
      <v-divider/>
      <v-stepper-step step="3" :rules="[() => validStepFiscal]" editable>Información Fiscal <small v-if="!validStepFiscal">Hay datos por diligenciar.</small></v-stepper-step>
    </v-stepper-header>
    <v-stepper-items>
      <v-stepper-content step="1">
        <v-card class="mb-1">
          <v-container
            fluid
            grid-list-lg
          >
            <form-basicos ref="fBasicos" :verified="estadoTercero" @errors="val => validStepBasicos = val" @verified="val => estadoTercero = val" @update="val => complementar(val)"/>
          </v-container>
        </v-card>
      </v-stepper-content>
      <v-stepper-content step="2">
        <v-card class="mb-1">
          <v-container
            fluid
            grid-list-lg
          >
            <form-complementarios ref="fComplementarios" :verified="estadoTercero" @errors="val => validStepComplementarios = val"/>
          </v-container>
        </v-card>
      </v-stepper-content>
      <v-stepper-content step="3">
        <v-card class="mb-1">
          <v-container
            fluid
            grid-list-lg
          >
            <form-fiscal ref="fFiscal" :verified="estadoTercero" @errors="val => validStepFiscal = val"/>
          </v-container>
        </v-card>
      </v-stepper-content>
      <v-layout row wrap>
        <v-flex xs12 sm4 class="text-xs-center text-sm-left">
          <v-tooltip v-if="stepActual > 1" top>
            <v-btn slot="activator" fab small color="primary" @click.native="stepActual--">
              <v-icon>arrow_back_ios</v-icon>
            </v-btn>
            <span>Anterior</span>
          </v-tooltip>
          <v-tooltip v-if="stepActual < 3" top>
            <v-btn slot="activator" fab small color="primary" @click.native="stepActual++">
              <v-icon>arrow_forward_ios</v-icon>
            </v-btn>
            <span>Siguiente</span>
          </v-tooltip>
        </v-flex>
      </v-layout>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
  import formBasicos from '@/components/administrativo/niif/terceros/FormBasicos'
  import formComplementarios from '@/components/administrativo/niif/terceros/FormComplementarios'
  import formFiscal from '@/components/administrativo/niif/terceros/FormFiscal'

  export default {
    name: 'FormTercero',
    inject: {
      $validator: '$validator'
    },
    components: {
      formBasicos,
      formComplementarios,
      formFiscal
    },
    data () {
      return {
        validStepBasicos: true,
        validStepComplementarios: true,
        validStepFiscal: true,
        tercero: {},
        estadoTercero: false,
        stepActual: 1
      }
    },
    methods: {
      complementar (val) {
        this.tercero = val
        this.$refs.fComplementarios.assign(this.tercero)
        this.$refs.fFiscal.assign(this.tercero)
        this.$emit('update', this.tercero)
      },
      async validate () {
        if (await this.$refs.fBasicos.validate()) {
          if (await this.$refs.fComplementarios.validate()) {
            if (await this.$refs.fFiscal.validate()) {
              return true
            } else {
              this.stepActual = 3
              return false
            }
          } else {
            this.stepActual = 2
            return false
          }
        } else {
          this.stepActual = 1
          return false
        }
      },
      assign (tercero) {
        this.$refs.fBasicos.assign(JSON.parse(JSON.stringify(tercero)))
      }
    }
  }
</script>

<style scoped>

</style>
