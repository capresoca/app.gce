<template>
  <v-card class="ma-0 pa-0" color="grey lighten-1">
    <v-layout row justify-center>
      <v-dialog v-model="dialogBorrarAnexo" persistent max-width="400">
        <v-card v-if="anetramite">
          <v-card-text class="title font-weight-light text-xs-center">
            ¿Esta seguro de borrar el anexo <strong>{{complementos.tipanexos ? complementos.tipanexos.find(x => x.id === anetramite.as_tipanexo_id).codigo : ''}}</strong> perteneciente a <strong>{{personas.find(x => x.item.id === anetramite.as_afiliado_id).item.nombre_completo}}</strong>?
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="cancelarBorrarAnexo">Cancelar</v-btn>
            <v-btn color="primary" @click.stop="borrarAnexo">Aceptar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-toolbar dense>
      <v-icon>attach_file</v-icon>
      <v-toolbar-title>Anexos registrados</v-toolbar-title>
      <v-spacer/>
      <v-tooltip top>
        <v-btn
          slot="activator"
          fab
          right
          small
          color="accent"
          @click.native="asignarAnexo(null)"
        >
          <v-icon>add</v-icon>
        </v-btn>
        <span>Agregar anexo</span>
      </v-tooltip>
    </v-toolbar>
    <v-slide-y-transition>
      <registro-anetramite :property="property" ref="rAnetramite" :afiliados="personas" :tramiteid="value.id" :objeto="JSON.parse(JSON.stringify(anetramite))" v-if="showFormAnexo" @hide="val => showFormAnexo = !val" @submit="val => returnAnexo(val)" />
    </v-slide-y-transition>
    <v-list subheader v-if="!anetramitesPersona || !anetramitesPersona.length">
      <v-subheader inset>Ninguno de los integrantes tiene anexos registrados.</v-subheader>
    </v-list>
    <v-expansion-panel v-if="paneles.length" :value="paneles" expand>
      <v-expansion-panel-content v-if="anetramitesPersona.length && personas.length" v-for="(item, index) in anetramitesPersona" :key="index" class="pt-2">
        <div slot="header" class="pt-1">
          <div> {{personas.find(x => x.item.id === item.as_afiliado_id).item.nombre_completo}} <small class="grey--text">{{personas.find(x => x.item.id === item.as_afiliado_id).tipo}}</small> </div>
          <div> {{personas.find(x => x.item.id === item.as_afiliado_id).item.identificacion_completa}} </div>
        </div>
        <v-card>
          <v-card-text class="py-0">
            <v-chip :color="'grey ' + (itemAnexo.gn_archivo_id ? 'lighten-2' : '')" v-for="(itemAnexo, index) in filtraAnexos(value.anexos, item.as_afiliado_id)" :key="index">
              <v-avatar dark class="accent">{{complementos.tipanexos ? complementos.tipanexos.find(x => x.id === itemAnexo.as_tipanexo_id).codigo : ''}}</v-avatar>
              <v-tooltip top>
                <span slot="activator">
                  <a :href="itemAnexo.anexo.url_signed" target="_blank" v-if="itemAnexo.gn_archivo_id">{{(complementos.tipanexos ? complementos.tipanexos.find(x => x.id === itemAnexo.as_tipanexo_id).nombre + (complementos.tipdocidentidades && itemAnexo.gn_tipo_doc_identidades_id && itemAnexo.as_tipanexo_id === 1 ?  ': ' + complementos.tipdocidentidades.find(x => x.id === itemAnexo.gn_tipo_doc_identidades_id).nombre : '') : '') | textTruncate(48)}}</a>
                <p class="pt-3" v-else>{{(complementos.tipanexos ? complementos.tipanexos.find(x => x.id === itemAnexo.as_tipanexo_id).nombre + (complementos.tipdocidentidades && itemAnexo.gn_tipo_doc_identidades_id && itemAnexo.as_tipanexo_id === 1 ?  ': ' + complementos.tipdocidentidades.find(x => x.id === itemAnexo.gn_tipo_doc_identidades_id).nombre : '') : '') | textTruncate(48)}}</p>
                </span>
                <span>{{(complementos.tipanexos ? complementos.tipanexos.find(x => x.id === itemAnexo.as_tipanexo_id).nombre + (complementos.tipdocidentidades && itemAnexo.gn_tipo_doc_identidades_id && itemAnexo.as_tipanexo_id === 1  ?  ': ' + complementos.tipdocidentidades.find(x => x.id === itemAnexo.gn_tipo_doc_identidades_id).nombre : '') : '')}}</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn
                  small
                  slot="activator"
                  ripple
                  icon
                  @click.stop="asignarAnexo(itemAnexo)"
                  class="ma-0 ml-2"
                >
                  <v-icon small color="accent">mode_edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn
                  small
                  slot="activator"
                  ripple
                  icon
                  @click.stop="confirmaBorrarAnexo(itemAnexo)"
                  class="ma-0"
                >
                  <v-icon small color="accent">close</v-icon>
                </v-btn>
                <span>Borrar</span>
              </v-tooltip>
            </v-chip>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import RegistroAnetramite from '@/components/misional/aseguramiento/tramites/RegistroAnetramite'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroAnetramites',
    props: ['value', 'rootPath'],
    data () {
      return {
        filtraAnexos: (array, id) => {
          array = array.filter(item => {
            return item.as_afiliado_id === id
          })
          return array
        },
        anetramitesPersona: [],
        paneles: [],
        personas: [],
        dialogBorrarAnexo: false,
        showFormAnexo: false,
        anetramite: null
      }
    },
    components: {
      RegistroAnetramite
    },
    watch: {
      async 'value.anexos' (val) {
        if (val) {
          await this.arrayUnique()
          await this.getPersonas()
          this.obtenerPaneles()
        }
      },
      async 'value.nucleo_familiar.length' () {
        await this.arrayUnique()
        await this.getPersonas()
        this.obtenerPaneles()
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosRegistroAnetramites
      },
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
      arrayUnique () {
        let hash = {}
        this.anetramitesPersona = this.value.anexos.filter(current => {
          let exists = !hash[current.as_afiliado_id] || false
          hash[current.as_afiliado_id] = true
          return exists
        })
      },
      obtenerPaneles () {
        this.paneles = []
        this.anetramitesPersona.forEach(() => {
          this.paneles.push(true)
        })
      },
      async returnAnexo (response) {
        let index = this.value.anexos.findIndex(x => x.id === response.id)
        this.value.anexos.splice(index >= 0 ? index : 0, index >= 0 ? 1 : 0, response)
        this.showFormAnexo = false
      },
      asignarAnexo (item) {
        this.showFormAnexo = true
        this.anetramite = item
        this.$vuetify.goTo('#form-anexo')
      },
      confirmaBorrarAnexo (item) {
        this.anetramite = item
        this.dialogBorrarAnexo = true
      },
      cancelarBorrarAnexo () {
        this.dialogBorrarAnexo = false
        setTimeout(() => { this.anetramite = null }, 400)
      },
      borrarAnexo () {
        this.axios.delete('anetramites/' + this.anetramite.id)
          .then((response) => {
            if (response.status === 200) {
              this.value.anexos.splice(this.value.anexos.findIndex(x => x.id === this.anetramite.id), 1)
              this.cancelarBorrarAnexo()
            }
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar borrar el anexo. ', error: e})
          })
      },
      getPersonas () {
        this.personas = []
        this.personas.push({tipo: 'Principal', item: this.value.afiliado})
        this.value.nucleo_familiar.forEach(item => {
          this.personas.push({tipo: 'Beneficiario', item: item.beneficiario})
        })
      }
    }
  }
</script>

<style scoped>

</style>
