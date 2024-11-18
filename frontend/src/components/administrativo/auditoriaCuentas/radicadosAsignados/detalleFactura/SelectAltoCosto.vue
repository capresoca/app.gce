<template>
  <div style="width: 0px !important;">
    <v-btn
      flat
      dark
      @click.stop="dialog = true"
    >
      <span class="subheading">ALTO COSTO</span>
    </v-btn>
    <v-dialog v-model="dialog" persistent max-width="600px">
      <!--    <template v-slot:activator="{ on }">-->
      <!--      <v-tooltip top>-->
      <!--        <v-btn-->
      <!--          class="ma-0"-->
      <!--          :class="disabled ? '' : 'text&#45;&#45;darken-2'"-->
      <!--          fab-->
      <!--          flat-->
      <!--          dark-->
      <!--          small-->
      <!--          color="grey"-->
      <!--          slot="activator"-->
      <!--          v-on="disabled ? null : on"-->
      <!--        >-->
      <!--          <v-badge class="badge-options-facservicios" color="red darken-3">-->
      <!--            <template slot="badge" v-if="unicoR && items[0].altos_costos.length">-->
      <!--              <span>{{items[0].altos_costos.length}}</span>-->
      <!--            </template>-->
      <!--            AC-->
      <!--          </v-badge>-->
      <!--        </v-btn>-->
      <!--        <span>{{unicoR && items[0].altos_costos.length ? `Alto costo: ${items[0].altos_costos.map(j => j.nombre).join(', ')}` : 'Alto costo' }}</span>-->
      <!--      </v-tooltip>-->
      <!--    </template>-->
      <v-card>
        <loading v-model="loading"/>
        <v-toolbar dense class="elevation-1 white">
          <v-avatar size="36" color="primary" class="white--text">
            AC
          </v-avatar>
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title class="subheading">Asignación de alto costo</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-spacer></v-spacer>
          <v-tooltip left>
            <v-btn
              slot="activator"
              icon
              @click="dialog = false"
            >
              <v-icon
              >
                close
              </v-icon>
            </v-btn>
            <span>Cerrar</span>
          </v-tooltip>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text>
          <v-select
            label="Tipos de alto costo"
            :items="complementos.tiposAltoCosto"
            item-text="nombre"
            item-value="id"
            multiple
            v-model="seleccionados"
            return-object
            chips
            deletable-chips
            hide-selected
            name="tipos de alto costo"
            v-validate="(unicoR && this.items[0].altos_costos.length) || !unicoR ? '' : 'required'"
            :error-messages="errors.collect('tipos de alto costo')"
          ></v-select>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn flat @click="dialog = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn v-if="items.length" color="primary" flat @click="guardar">Guardar</v-btn>
          <v-chip v-else label color="orange" text-color="white">
            No se han seleccionado items de factura
          </v-chip>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  export default {
    name: 'SelectAltoCosto',
    props: {
      value: {
        type: Array,
        default: () => []
      },
      items: {
        type: Array,
        default: []
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },
    components: {
      Loading
    },
    data: () => ({
      seleccionados: [],
      dialog: false,
      loading: false
    }),
    computed: {
      unicoR () {
        return this.items && this.items.length === 1
      },
      complementos () {
        return store.getters.complementosFacturaAuditoria
      }
    },
    watch: {
      'dialog' (val) {
        this.seleccionados = val && this.unicoR ? this.items[0].altos_costos : []
      }
    },
    methods: {
      guardar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.axios.post(`facservicios/altoscostos`, {facservicios: this.items, altos_costos: this.seleccionados.map(j => j.id)})
              .then(() => {
                this.items.forEach(x => { x.altos_costos = this.seleccionados })
                this.$emit('asignados')
                this.loading = false
                this.dialog = false
                this.$store.commit('SNACK_SHOW', {msg: `Los tipos de alto costo se han asignado correctamente.`, color: 'success'})
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: `al asignar los tipos de alto costo `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style>
  .badge-options-facservicios>span.v-badge__badge {
    margin-top: 4px !important;
    height: 16px !important;
    width: 16px !important;
    font-size: 11px !important;
    right: -12px !important;
  }
</style>
