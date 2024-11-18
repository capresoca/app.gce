<template>
  <v-card>
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" :requiere-motivo="false" @aceptar="confirmaAnulacion" />
    <v-container fluid grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12>
          <postulador-v2
            no-data="Busqueda por nombre o número de documento."
            hint="identificacion_completa"
            item-text="nombre_completo"
            data-title="identificacion_completa"
            data-subtitle="nombre_completo"
            label="Mamá o papá"
            :detail="detalleAfiliado"
            entidad="afiliados"
            preicon="person"
            v-model="value.afiliado"
            @changeid="val => value.as_afiliado_id = val"
            name="Mamá o papá"
            rules="required|responsableValido"
            v-validate="'required|responsableValido'"
            :error-messages="errors.collect('Mamá o papá')"
            :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
            no-btn-edit
            no-btn-create
          />
        </v-flex>
        <v-flex xs12 v-if="listoRRN">
          <form-recien-nacido :dialog="dialogRegistro" v-model="recienNacido" :afitramitern="afitramite_rn" @cancelar="dialogRegistro = false" @download="val => refreshListRN(val)"/>
          <v-toolbar dense class="elevation-0">
            <v-icon>child_care</v-icon>
            <v-toolbar-title>Recién nacidos</v-toolbar-title>
            <small class="mt-2 ml-1"> Registro y gestión</small>
            <v-spacer/>
            <v-tooltip top>
              <v-btn
                slot="activator"
                fab
                right
                small
                color="accent"
                @click="dialogRegistro = true"
              >
                <v-icon>add</v-icon>
              </v-btn>
              <span>Agregar recién nacido</span>
            </v-tooltip>
          </v-toolbar>

          <v-list two-line subheader>
            <v-subheader v-if="afitramite_rn.recien_nacidos && !afitramite_rn.recien_nacidos.length" inset>Sin recién nacidos</v-subheader>
            <template v-else v-for="(item, index) in afitramite_rn.recien_nacidos">
              <v-divider inset />
              <v-list-tile :key="index" avatar>
                <v-list-tile-avatar color="primary">
                  <span class="white--text headline">{{ (index + 1) }}</span>
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title v-html="[item.nombre1, item.nombre2, item.apellido1, item.apellido2].join(' ')"/>
                  <v-list-tile-sub-title v-html="complementos.tipdocidentidades && complementos.tipdocidentidades.find(x => x.id === item.gn_tipdocidentidad_id).abreviatura + ' ' + item.identificacion"/>
                </v-list-tile-content>
                <v-tooltip top>
                  <v-btn
                    small
                    slot="activator"
                    ripple
                    icon
                    @click="goEditar(item)"
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
                    @click="registroAnular(item, index)"
                  >
                    <v-icon color="accent">close</v-icon>
                  </v-btn>
                  <span>Desvincular</span>
                </v-tooltip>
              </v-list-tile>
            </template>
          </v-list>
        </v-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs6 class="text-xs-left">
          <v-btn @click="refresh()">Limpiar</v-btn>
        </v-flex>
        <v-flex xs6 class="text-xs-right">
          <v-btn @click="submit" color="primary">Guardar</v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import Confirmar from '@/components/general/Confirmar'
  import FormRecienNacido from '@/components/misional/aseguramiento/tramites/afiliaciones/FormRecienNacido'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroAfiliacionRecienNacido',
    props: ['afitramite', 'parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      FormRecienNacido,
      Confirmar
    },
    filters: {
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        listoRRN: false,
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dialogRegistro: false,
        value: null,
        recienNacido: undefined,
        responsable: null,
        afitramite_rn: {},
        makeAafitramite_rn: {
          estado: 'Registrado',
          fecha_radicacion: null,
          id: null,
          responsable_id: null,
          recien_nacidos: [],
          recien_nacido: 1
        }
      }
    },
    watch: {
      'dialogRegistro' (val) {
        if (val === false) this.recienNacido = undefined
      },
      'afitramite' (val) {
        this.value = val
      },
      'value' (val) {
        if (val) {
          this.configurarAfitramite()
        }
      },
      async 'value.afiliado' (val) {
        if (val) {
          this.responsable = val
          this.afitramite_rn.responsable_id = val.id
        }
        if (val === null) {
          this.value.as_afiliado_id = null
        }
        this.listoRRN = (this.value.as_afiliado_id && await this.$validator.validateAll())
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAfiliacionRN
      }
    },
    created () {
      this.value = this.afitramite
    },
    beforeMount () {
      Validator.extend('responsableValido', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.value.as_afiliado_id)
              ? (this.value.afiliado &&
                ((this.value.afiliado.estado === 3 && this.value.afiliado.id !== this.value.idAfiliadoValido) ||
                  (this.value.afiliado.estado !== 3 && this.value.afiliado.id === this.value.idAfiliadoValido)))
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 3).nombre}.`}
              : {valido: true, mensaje: null}
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
    },
    methods: {
      refresh () {
        this.responsable = null
        this.value.afiliado = null
        this.afitramite_rn = JSON.parse(JSON.stringify(this.makeAafitramite_rn))
        this.$validator.reset()
      },
      configurarAfitramite () {
        this.afitramite_rn = JSON.parse(JSON.stringify(this.makeAafitramite_rn))
        if (this.value.id) {
          this.afitramite_rn.estado = this.value.estado
          this.afitramite_rn.id = this.value.id
          this.afitramite_rn.responsable_id = this.value.afiliado.id
          this.afitramite_rn.recien_nacido = this.value.recien_nacido
          this.value.nucleo_familiar.forEach(nucleo => {
            let rn = {
              as_afitramite_id: nucleo.as_afitramite_id,
              as_beneficiario_id: nucleo.as_beneficiario_id,
              as_parentesco_id: nucleo.as_parentesco_id,
              beneficiario: nucleo.beneficiario,
              id: nucleo.id,
              gn_tipdocidentidad_id: nucleo.beneficiario.gn_tipdocidentidad_id,
              identificacion: nucleo.beneficiario.identificacion,
              fecha_expedicion: nucleo.beneficiario.fecha_expedicion,
              as_condicion_discapacidades_id: nucleo.beneficiario.as_condicion_discapacidades_id,
              gn_sexo_id: nucleo.beneficiario.gn_sexo_id,
              nombre1: nucleo.beneficiario.nombre1,
              nombre2: nucleo.beneficiario.nombre2,
              apellido1: nucleo.beneficiario.apellido1,
              apellido2: nucleo.beneficiario.apellido2,
              fecha_nacimiento: nucleo.beneficiario.fecha_nacimiento
            }
            this.afitramite_rn.recien_nacidos.push(rn)
          })
        }
      },
      refreshListRN (bebe) {
        if (bebe.id === null) bebe.id = 'id_' + Math.random().toString(36).substring(2)
        let index = this.afitramite_rn.recien_nacidos.findIndex(x => x.id === bebe.id)
        this.afitramite_rn.recien_nacidos.splice((index > -1 ? index : 0), (index > -1 ? 1 : 0), bebe)
        this.dialogRegistro = false
      },
      goEditar (item, index) {
        item.id = index
        this.recienNacido = JSON.parse(JSON.stringify(item))
        this.dialogRegistro = true
      },
      registroAnular (item, index) {
        this.recienNacido = item
        this.dialogA.content = '¿Está seguro de disvincular al recien nacido: <strong>' + [item.nombre1, item.nombre2, item.apellido1, item.apellido2].filter(Boolean).join(' ') + '</strong>?'
        this.dialogA.registroID = index
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion () {
        this.afitramite_rn.recien_nacidos.splice(this.dialogA.registroID, 1)
        this.cancelaAnulacion()
      },
      async submit () {
        let errorResponsable = await this.$validator.validateAll()
        let errorRN = this.afitramite_rn.recien_nacidos.length > 0
        if (!errorRN) {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay que registrar al menos un recién nacido.', color: 'warning'})
        }
        if (errorResponsable && errorRN) {
          let valueCopy = JSON.parse(JSON.stringify(this.afitramite_rn))
          valueCopy.recien_nacidos.forEach(rNacido => {
            delete rNacido.beneficiario
          })
          this.$emit('endsubmit', valueCopy)
        }
      }
    }
  }
</script>

<style scoped>
</style>
