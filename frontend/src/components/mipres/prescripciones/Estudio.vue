<template>
  <v-container
    fluid
    grid-list-sm
    class="pa-0 ma-0"
  >
    <v-dialog v-model="value" persistent max-width="800px">
      <loading v-model="dialogEstudio.loading" />
      <v-card v-if="dialogEstudio.item">
        <v-toolbar dense>
          <v-toolbar-title>
            <v-list-tile>
              <v-icon left class="mr-2" style="margin-left: -14px !important;">ballot</v-icon>
              <v-list-tile-content>
                <v-list-tile-title v-html="type === 'prescripción' ? 'Estudio de Prescripción' : 'Estudio de Tutela'"></v-list-tile-title>
                <v-list-tile-sub-title class="caption">No. {{type === 'prescripción' ? dialogEstudio.item.NoPrescripcion : dialogEstudio.item.NoTutela}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-toolbar-title>
          <v-spacer/>
          <v-btn flat icon :disabled="dialogEstudio.loading" @click="$emit('input', false)">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12>
              <v-select
                label="Nuevo estado"
                :items="complementos.estados"
                item-text="text"
                item-value="value"
                name="Nuevo estado"
                v-validate="'required'"
                :error-messages="errors.collect('Nuevo estado')"
                v-model="dialogEstudio.data.estado"
              />
            </v-flex>
            <v-flex xs12 v-if="dialogEstudio.data.estado === 'Aprobado'">
              <postulador-v2
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="tercero.identificacion_completa"
                item-text="nombre"
                data-title="nombre"
                data-subtitle="tercero.identificacion_completa"
                label="Farmacia"
                entidad="entidades"
                preicon="location_city"
                @changeid="val => dialogEstudio.data.farmacia_id = val"
                v-model="dialogEstudio.entidad"
                route-params="rs_tipentidade_id=2"
                name="entidad"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('entidad')"
                no-btn-create
                :min-characters-search="3"
              />
            </v-flex>
            <v-flex xs12>
              <v-textarea
                label="Observaciones"
                v-model="dialogEstudio.data.observaciones"
                name="Observaciones"
                v-validate="'required'"
                :error-messages="errors.collect('Observaciones')"
                rows="1"
                auto-grow
              />
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider/>
        <v-card-actions>
          <v-btn :disabled="dialogEstudio.loading" @click="$emit('input', false)">Cerrar</v-btn>
          <v-spacer/>
          <v-btn
            :loading="dialogEstudio.loading"
            :disabled="dialogEstudio.loading"
            color="blue darken-1"
            class="white--text"
            @click="submitEstudioPrescripcion"
          >
            registrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {MIPRES_PRESCRIPCIONES_RELOAD_ITEM, MIPRES_TUTELAS_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'Estudio',
    props: {
      item: {
        type: Object,
        default: null
      },
      type: {
        type: String,
        default: 'tutela'
      },
      value: {
        type: Boolean,
        default: false
      }
    },
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading
    },
    data: () => ({
      dialogEstudio: {
        loading: false,
        item: null,
        entidad: null,
        data: {
          id: null,
          observaciones: null,
          estado: null,
          farmacia_id: null
        }
      },
      loading: true
    }),
    watch: {
      'value' (val) {
        if (!val) {
          setTimeout(() => {
            this.dialogEstudio = {
              show: false,
              loading: false,
              item: null,
              entidad: null,
              data: {
                id: null,
                observaciones: null,
                estado: null,
                farmacia_id: null
              }
            }
            this.$validator.reset()
          }, 800)
        } else {
          this.dialogEstudio.item = JSON.parse(JSON.stringify(this.item))
          this.dialogEstudio.data.estado = JSON.parse(JSON.stringify(this.dialogEstudio.item.estado))
          this.dialogEstudio.data.id = this.dialogEstudio.item.id
        }
      },
      'dialogEstudio.data.estado' (val) {
        if (val && this.dialogEstudio.show) {
          this.dialogEstudio.entidad = null
          this.dialogEstudio.data.farmacia_id = null
          this.$validator.reset()
        }
      }
    },
    computed: {
      complementos () {
        return this.type === 'prescripción' ? store.getters.complementosTablaPrescripciones : store.getters.complementosTablaTutelas
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent(this.type === 'prescripción' ? 'prescripciones' : 'tutelasMipres')
      }
    },
    methods: {
      submitEstudioPrescripcion () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.dialogEstudio.loading = true
            this.axios.put((this.type === 'prescripción' ? 'prescripciones/' : 'mptutelas/') + this.dialogEstudio.data.id, this.dialogEstudio.data)
              .then(() => {
                this.$store.commit(SNACK_SHOW, {msg: `El estudio de la ${this.type} se realizó satisfactoriamente.`, color: 'success'})
                this.$store.commit(this.type === 'prescripción' ? MIPRES_PRESCRIPCIONES_RELOAD_ITEM : MIPRES_TUTELAS_RELOAD_ITEM, {item: {}, estado: 'reload', key: null})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: null })
              })
              .catch(e => {
                this.dialogEstudio.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ` al realizar el estudio de la ${this.type}. `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
