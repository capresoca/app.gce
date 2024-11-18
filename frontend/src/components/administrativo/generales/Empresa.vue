<template>
  <div id="container-empresa">
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0">
        <v-toolbar-title class="title">Parametros Empresa</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon color="white" v-if="empresa.id" flat @click="isEditingP = !isEditingP">
          <v-icon color="accent" v-text="'edit'"></v-icon>
        </v-btn>
      </v-toolbar>
      <!--<snack-bar title="Parametros Empresa"></snack-bar>-->
      <v-container fluid class="pa-0 pb-2">
        <v-layout row wrap>
          <v-flex v-if="!empresa.id" xs12 md12 class="pt-3">
            <v-alert :value="true" color="error" icon="warning" width="500px">
              <v-layout row wrap>
                <v-flex xs8 class="text-xs-left pa-3">
                  {{ message }}
                </v-flex>
                <v-flex xs4 class="text-xs-right">
                  <v-btn
                    color="primary"
                    dark
                    @click="addEmpresa = !addEmpresa"
                  >
                    Agregar
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-alert>
          </v-flex>
          <v-flex xs12 v-if="empresa.id || addEmpresa">
            <v-tabs color="cyan" v-model="currentItem" grow dark icons-and-text>
              <!--show-arrows-->
              <v-tabs-slider color="accent"></v-tabs-slider>
              <v-tab v-for="(bite, item) in tabEmpresas" :href="bite.hrefId" :key="`${item}`">
                <span v-text="bite.name"></span>
                <v-icon  color="white darken-2" v-text="bite.icon"></v-icon>
              </v-tab>
            </v-tabs>
            <v-tabs-items v-model="currentItem">
              <v-tab-item value="tab-generales" key="0">
                <form-generales ref="fGeneral" v-model="empresa" @errors="val => validTabGeneral = val" :is-editing="isEditingP"></form-generales>
              </v-tab-item>
              <v-tab-item value="tab-eps" key="1">
                <form-eps ref="fEps" v-model="empresa" @errors="val => validTabEps = val" :is-editing="isEditingP"></form-eps>
              </v-tab-item>
              <!--<v-tab-item value="tab-corp">-->
              <!--<form-corporativa ref="fCorporativa" v-model="empresa"></form-corporativa>-->
              <!--</v-tab-item>-->
              <v-tab-item value="tab-contable" key="2">
                <form-contables ref="fContable" v-model="empresa" @errors="val => validTabCon = val" :is-editing="isEditingP"></form-contables>
              </v-tab-item>
              <v-tab-item value="tab-rips" key="3">
                <form-provisionarips ref="fProvisional" v-model="empresa" @errors="val => validTabPro = val" :is-editing="isEditingP"></form-provisionarips>
              </v-tab-item>
            </v-tabs-items>
          </v-flex>
        </v-layout>
      </v-container>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="close()">Cancelar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-slide-y-transition>
              <v-btn color="primary" v-if="isEditingP == false && !empresa.id ? true : (isEditingP == true && empresa.id ? true : false) " @click.prevent="save()" v-text="!empresa.id ? 'Registrar' : 'Actualizar'"></v-btn>
            </v-slide-y-transition>
            <!--<v-btn color="primary" @click.prevent="save()" v-text="(!isEditingP && !empresa.id)  ? 'Guardar' : 'Actualizar' " ></v-btn>-->
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  // import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import FormGenerales from './FormGenerales'
  import FormEps from './FormEps'
  import FormContables from './FormContables'
  import FormCorporativa from './FormCorporativa'
  import FormProvisionarips from './FormProvisionarips'
  export default {
    name: 'Empresa',
    props: ['parametros'],
    inject: {
      $validator: '$validator'
    },
    components: {
      FormProvisionarips,
      FormCorporativa,
      FormContables,
      FormEps,
      FormGenerales,
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        empresa: {},
        tabEmpresas: [
          {
            name: 'Datos Generales',
            icon: 'domain',
            hrefId: '#tab-generales'
          },
          {
            name: 'Datos EPS',
            icon: 'local_pharmacy',
            hrefId: '#tab-eps'
          },
          // {
          //   name: 'Imagen Corporativa',
          //   icon: 'photo',
          //   hrefId: '#tab-corp'
          // },
          {
            name: 'Datos Contables',
            icon: 'settings',
            hrefId: '#tab-contable'
          },
          {
            name: 'Provisión RIPS',
            icon: 'layers',
            hrefId: '#tab-rips'
          }
        ],
        validTabGeneral: true,
        validTabEps: true,
        validTabCon: true,
        validTabPro: true,
        loading: false,
        dialog: true,
        isEditingP: false,
        items: [],
        message: null,
        addEmpresa: false,
        currentItem: 'tab-generales',
        search: null,
        select: [],
        tab: null
      }
    },
    created () {
      this.formReset()
    },
    mounted () {
      this.getRegistroEmpresa()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('empresa')
      }
    },
    watch: {
    },
    methods: {
      formReset () {
        this.empresa = {
          id: null,
          gn_tercero_id: null,
          rep_legal: null,
          gerente: null,
          subgerente_servasistencial: null,
          subgerente_admin: null,
          codhabilitacion_subsid: null,
          codhabilitacion_contrib: null,
          logo: null,
          logosimbolo: null,
          nf_niifdeficit_id: null,
          nf_niifsuperavit_id: null,
          nf_niifganacias_id: null,
          nf_tipcomdiajustes_id: null,
          nf_tipcomdiacierre_id: null,
          nf_tipcomdiatraslado_id: null,
          nf_tipcomdiahomologacion_id: null,
          nf_tipcomdiadistribucion_id: null,
          nf_niifdebitoprovisional_id: null,
          nf_niifcreditoprovisional_id: null,
          nf_niifdebitoprosincontrato_id: null,
          nf_niifcreditoprosincontrato_id: null,
          nf_comdiarioprovisional_id: null,
          nf_comdiarioprovisionalsincontrato_id: null,
          nf_niifdebitoanterior_id: null,
          nf_niifcreditoanterior_id: null,
          nf_niifdebitoanteriorsincontrato_id: null,
          nf_niifcreditoanteriorsincontrato_id: null,
          gn_municipio_id: null,
          nit_dian: null,
          tesoreria_distrital: null,
          gn_cargo_id: null,
          tarjeta_profesional: null,
          firma_certificados: null,
          revisor_fiscal: null,
          fecha_inicial: null,
          //
          tercero: null,
          representante: null,
          ter_gerente: null,
          subger_admin: null,
          subger_servasistencial: null,
          deficit: null,
          ganancias: null,
          superavit: null,
          com_ajuste: null,
          com_cierre: null,
          com_traslado: null,
          com_homologacion: null,
          com_distribucion: null,
          dian: null,
          tesoreria: null,
          cargo: null,
          cuenta_debitoprovisional: null,
          cuenta_creditoprovisional: null,
          cuentadebito_sincontrato: null,
          cuentacredito_sincontrato: null,
          comdiario_sincontrato: null,
          comdiario_concontrato: null,
          debitopro_anterior: null,
          creditopro_anterior: null,
          debitopro_antsincontrato: null,
          creditopro_antsincontrato: null
        }
      },
      getRegistroEmpresa () {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('empresas').then(res => {
          console.log('> respuesta: ', res)
          if (res.data.exists === false) {
            this.addEmpresa = false
            this.message = res.data.message
          } else {
            this.addEmpresa = true
            this.empresa = res.data.data
          }
          loader.hide()
          this.currentItem = 'tab-generales'
        }).catch(e => {
          loader.hide()
          this.$store.commit(SNACK_SHOW, {msg: `Error al cargar la empresa ${e.message}`, color: 'danger'})
        })
      },
      async save () {
        // let dataEmpresa = new FormData()
        // dataEmpresa.append('gn_tercero_id', this.empresa.gn_tercero_id)
        // dataEmpresa.append('rep_legal', this.empresa.rep_legal)
        // dataEmpresa.append('gerente', this.empresa.gerente)
        // dataEmpresa.append('subgerente_servasistencial', this.empresa.subgerente_servasistencial)
        // dataEmpresa.append('subgerente_admin', this.empresa.subgerente_admin)
        // dataEmpresa.append('codhabilitacion_subsid', this.empresa.codhabilitacion_subsid)
        // dataEmpresa.append('codhabilitacion_contrib', this.empresa.codhabilitacion_contrib)
        // dataEmpresa.append('logo', this.empresa.logo)
        // dataEmpresa.append('nf_niifdeficit_id', this.empresa.nf_niifdeficit_id)
        // dataEmpresa.append('nf_niifsuperavit_id', this.empresa.nf_niifsuperavit_id)
        // dataEmpresa.append('nf_niifganacias_id', this.empresa.nf_niifganacias_id)
        // dataEmpresa.append('nf_tipcomdiajustes_id', this.empresa.nf_tipcomdiajustes_id)
        // dataEmpresa.append('nf_tipcomdiacierre_id', this.empresa.nf_tipcomdiacierre_id)
        // dataEmpresa.append('nf_tipcomdiatraslado_id', this.empresa.nf_tipcomdiatraslado_id)
        // dataEmpresa.append('nf_tipcomdiahomologacion_id', this.empresa.nf_tipcomdiahomologacion_id)
        // dataEmpresa.append('nf_tipcomdiadistribucion_id', this.empresa.nf_tipcomdiadistribucion_id)
        // dataEmpresa.append('gn_municipio_id', this.empresa.gn_municipio_id)
        // dataEmpresa.append('nit_dian', this.empresa.nit_dian)
        // dataEmpresa.append('tesoreria_distrital', this.empresa.tesoreria_distrital)
        // dataEmpresa.append('gn_cargo_id', this.empresa.gn_cargo_id)
        // dataEmpresa.append('tarjeta_profesional', this.empresa.tarjeta_profesional)
        // dataEmpresa.append('firma_certificados', this.empresa.firma_certificados)
        // dataEmpresa.append('revisor_fiscal', this.empresa.revisor_fiscal)
        // dataEmpresa.append('fecha_inicial', this.empresa.fecha_inicial)
        if (await this.$refs.fGeneral.validate()) {
          if (await this.$refs.fEps.validate()) {
            if (await this.$refs.fContable.validate()) {
              if (await this.$refs.fProvisional.validate()) {
                this.submit()
              } else {
                this.currentItem = 'tab-rips'
                return false
              }
            } else {
              this.currentItem = 'tab-contable'
              return false
            }
          } else {
            this.currentItem = 'tab-eps'
            return false
          }
        } else {
          this.currentItem = 'tab-generales'
          return false
        }
      },
      submit () {
        let send = !this.empresa.id ? this.axios.post('empresas', this.empresa) : this.axios.put('empresas/' + this.empresa.id, this.empresa)
        send.then((res) => {
          this.$store.commit(SNACK_SHOW, {
            msg: `Se ${this.empresa.id
              ? 'actualizaron los datos'
              : 'realizó el registro'} de ${res.data.data.tercero ? res.data.data.tercero.nombre_completo : null} correctamente.`,
            color: 'success'
          })
          this.empresa = res.data.data
          this.isEditingP = false
          this.currentItem = 'tab-generales'
        }).catch((e) => {
          this.$store.commit(SNACK_SHOW, {msg: `Error al cargar la empresa ${e.message}`, color: 'danger'})
        })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      close () {
        this.addEmpresa = false
        // this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
      }
      // async validate () {
      //   if (await this.$refs.fGeneral.validate()) {
      //     if (await this.$refs.fEps.validate()) {
      //       if (await this.$refs.fContable.validate()) {
      //         return true
      //       } else {
      //         document.getElementById('tab-contables')
      //         return false
      //       }
      //     } else {
      //       document.getElementById('tab-eps')
      //       return false
      //     }
      //   } else {
      //     document.getElementById('tab-generales')
      //     return false
      //   }
      // }
      // querySelections (v) {
      //   this.loading = true
      //   // Simulated ajax query
      //   this.axios.post('complementos', {items: ['sexos', 'municipios']})
      //   setTimeout(() => {
      //     this.items = this.states.filter(e => {
      //       return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
      //     })
      //     this.loading = false
      //   }, 500)
      // }
    }
  }
</script>

<style scoped>

</style>
