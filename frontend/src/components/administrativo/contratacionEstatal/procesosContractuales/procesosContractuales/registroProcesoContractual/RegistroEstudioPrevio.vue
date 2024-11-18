<template>
  <v-card>
    <loading v-model="loading" />
    <v-card-text>
      <form-estudio-previo
        v-model="estudioPrevio"
        :proceso="procesoContractual"
        :rubros="rubros"
        :supervisores="supervisores"
        :proyectan="proyectan"
        :revisan="revisan"
        :aprueban="aprueban"
        ref="fEstudioPrevio"
      ></form-estudio-previo>
    </v-card-text>
    <v-divider/>
    <v-card-actions v-if="estudioPrevio.estado === 'Registrado'">
      <v-spacer/>
      <span class="caption error--text mr-3"><strong>{{errorValidate}}</strong></span>
      <v-btn color="primary" @click.prevent="reloadRequerido('required|')" :loading="loading">Guardar y confirmar</v-btn>
      <v-btn color="primary" @click.prevent="reloadRequerido('')" :loading="loading">Guardar</v-btn>
    </v-card-actions>
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import FormEstudioPrevio from '@/components/administrativo/contratacionEstatal/procesosContractuales/procesosContractuales/registroProcesoContractual/FormEstudioPrevio'
  export default {
    name: 'RegistroEstudioPrevio',
    components: {
      Loading,
      FormEstudioPrevio,
      Confirmar: () => import('@/components/general/Confirmar')
    },
    props: {
      value: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        formRequerido: '',
        dialogA: {
          loading: false,
          visible: false,
          content: null
        },
        estudioPrevio: null,
        makeEstudioPrevio: {
          ce_procontractuale_id: this.value.id,
          id: null,
          consecutivo: null,
          fecha: null,
          desc_necesidad: '',
          desc_tecnica: '',
          esp_tecnicas: '',
          sop_economico: '',
          alt_ejecucion: '',
          pos_riesgos: '',
          ce_tipcontratacione_id: null,
          plazo_meses: null,
          plazo_dias: null,
          valor: null,
          supervicion: null,
          lugar_ejecucion: '',
          productos_entregar: '',
          descripgarantias: '',
          desc_plazo: '',
          tarifas: '',
          imputacion_presupuestal: [],
          forpagos: [],
          garantias: [],
          actividades: [],
          proconminutageos: [],
          supervisor: null,
          supervisor_id: null,
          ce_tipocontrato_id: null,
          estado: 'Registrado',
          registro: null,
          reviso: null,
          confirmo: null
        },
        procesoContractual: null,
        rubros: [],
        supervisores: [],
        proyectan: [],
        revisan: [],
        aprueban: [],
        loading: false,
        errorValidate: ''
      }
    },
    watch: {
      'value' (val) {
        this.procesoContractual = val
        this.procesoContractual.estudios_previos ? this.estudioPrevio = this.procesoContractual.estudios_previos : this.formReset()
      }
    },
    created () {
      this.procesoContractual = this.value
      this.procesoContractual.estudios_previos ? this.estudioPrevio = this.procesoContractual.estudios_previos : this.formReset()
      this.getPresupuesto()
      this.getSupervisores()
      this.getFirmantes()
    },
    methods: {
      async reloadRequerido (text) {
        await this.$refs.fEstudioPrevio.assignValidation(text)
        text ? this.confirm() : this.save()
      },
      formReset () {
        this.estudioPrevio = JSON.parse(JSON.stringify(this.makeEstudioPrevio))
        this.errorValidate = ''
      },
      getPresupuesto () {
        this.axios.get('presupuestoActual')
          .then(response => {
            if (response.data.data.detalles) {
              this.rubros = response.data.data.detalles
            }
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el presupuesto. ', error: e})
          })
      },
      getSupervisores () {
        this.axios.get('ce_supervisores')
          .then(response => {
            if (response.data.data) this.supervisores = response.data.data
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los supervisores. ', error: e})
          })
      },
      getFirmantes () {
        this.axios.get('usuarios-firman-estudio-previo')
          .then(response => {
            if (response.data) {
              this.proyectan = response.data.proyectan
              this.revisan = response.data.revisan
              this.aprueban = response.data.aprueban
            }
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los funcionarios habilitados para firmar el estudio previo. ', error: e})
          })
      },
      async confirm () {
        if (await this.validacion()) {
          this.dialogA.content = `Los datos del estudio previo serán confirmados, si decide continuar, los datos no se podran editar nuevamente.<br/> <strong>¿Está seguro de confirmar los datos?</strong>`
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      aceptaConfirmacion () {
        this.dialogA.loading = true
        this.estudioPrevio.estado = 'Confirmado'
        this.save()
      },
      async save () {
        if (await this.validacion()) {
          this.loading = true
          if (!this.procesoContractual.servicios_salud) this.estudioPrevio.tarifas = ''
          return new Promise(resolve => {
            let dataCopy = JSON.parse(JSON.stringify(this.estudioPrevio))
            dataCopy.proconminutageos.forEach(x => {
              x.upcservicios = x.upcservicios.filter(z => z.porcentaje !== null || z.valor !== null)
            })
            const request = dataCopy.id ? this.axios.put('ce_proconestprevios/' + dataCopy.id, dataCopy) : this.axios.post('ce_proconestprevios', dataCopy)
            request
              .then(response => {
                if (response.data.data && !response.data.data.proconminutageos) {
                  response.data.data.proconminutageos = []
                }
                this.$store.commit('SNACK_SHOW', {msg: 'El estudio previo se registró correctamente ', color: 'success'})
                this.$store.commit('PROCESOCONTRACTUAL_RELOAD_ITEM', {item: response.data.procontractuale, estado: 'reload', key: null})
                this.errorValidate = ''
                this.estudioPrevio = response.data.data
                this.procesoContractual.estudios_previos = response.data.data
                if (this.procesoContractual.estudios_previos.estado === 'Confirmado') this.filePrint(`certificado-presupuestal&id=${this.procesoContractual.estudios_previos.pr_solicitud_cp_id}`)
                this.$emit('input', this.procesoContractual)
                this.loading = false
                this.cancelaConfirmacion()
                resolve(true)
              }).catch(e => {
                this.estudioPrevio.estado = this.procesoContractual.estudios_previos ? this.procesoContractual.estudios_previos.estado : 'Registrado'
                this.loading = false
                this.dialogA.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar el estudio previo. ', error: e})
                resolve(false)
              })
          })
        } else {
          return false
        }
      },
      async validacion () {
        let validate = await this.$refs.fEstudioPrevio.validate()
        if (validate.validate) {
          return true
        } else {
          this.errorValidate = validate.error
          setTimeout(() => {
            this.errorValidate = ''
          }, 8000)
          return false
        }
      }
    }
  }
</script>

<style scoped>

</style>
