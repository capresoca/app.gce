<template>
  <div style="width: 0px !important;">
    <v-btn
      flat
      dark
      @click.stop="dialog = true"
    >
      <span class="subheading">RECOBRO</span>
    </v-btn>
    <v-dialog v-model="dialog" persistent max-width="400px">
<!--      <template v-slot:activator="{ on }">-->
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
        <!--            <template slot="badge" v-if="unicoR && items[0].recobro">-->
        <!--              <v-icon size="10">done_outline</v-icon>-->
        <!--            </template>-->
        <!--            R-->
        <!--          </v-badge>-->
        <!--        </v-btn>-->
        <!--        <span>{{unicoR && items[0].recobro ? `Recobro: ${items[0].recobro}` : 'Recobro' }}</span>-->
        <!--      </v-tooltip>-->
<!--      </template>-->
      <v-card>
        <loading v-model="loading"/>
        <v-toolbar dense class="elevation-1 white">
          <v-avatar size="36" color="primary" class="white--text">
            R
          </v-avatar>
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title class="subheading">Asignación de recobro</v-list-tile-title>
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
            label="Tipo de recobro"
            :items="complementos.tiposRecobro"
            v-model="seleccionado"
            name="tipo de recobro"
            clearable
            v-validate="(unicoR && this.items[0].recobro) || !unicoR ? '' : 'required'"
            :error-messages="errors.collect('tipo de recobro')"
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
      seleccionado: null,
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
        this.seleccionado = val && this.unicoR ? this.items[0].recobro : null
      }
    },
    methods: {
      guardar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.axios.post(`facservicios/masivos`, {facservicios: this.items.map(x => x.id), seleccionado: this.seleccionado, item: 'recobro'})
              .then(() => {
                this.items.forEach(x => { x.recobro = this.seleccionado })
                this.$emit('asignados')
                this.loading = false
                this.dialog = false
                this.$store.commit('SNACK_SHOW', {msg: `El tipo de recobro se asignó correctamente.`, color: 'success'})
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: `al asignar el tipo de recobro `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style>
</style>
