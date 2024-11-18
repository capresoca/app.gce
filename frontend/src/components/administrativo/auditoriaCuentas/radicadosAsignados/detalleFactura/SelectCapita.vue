<template>
<!--  <v-tooltip top>-->
<!--    <v-btn-->
<!--      class="ma-0"-->
<!--      :class="disabled ? '' : 'text&#45;&#45;darken-2'"-->
<!--      fab-->
<!--      flat-->
<!--      dark-->
<!--      small-->
<!--      color="grey"-->
<!--      slot="activator"-->
<!--      @click.stop="disabled ? null : capitar()"-->
<!--    >-->
<!--      <v-badge class="badge-options-facservicios" color="red darken-3">-->
<!--        <template slot="badge" v-if="unicoR && items[0].capita">-->
<!--          <v-icon size="10">done_outline</v-icon>-->
<!--        </template>-->
<!--        C-->
<!--      </v-badge>-->
<!--    </v-btn>-->
<!--    <span>{{unicoR && items[0].capita ? 'Capitado' : 'Capitar' }}</span>-->
<!--    <loading v-model="loading" is-full-page></loading>-->
<!--  </v-tooltip>-->
  <v-btn
    flat
    dark
    @click.stop="capitar()"
  >
    <span class="subheading">MARCAR CAPITA</span>
  </v-btn>
</template>

<script>
  import Loading from '@/components/general/Loading'
  export default {
    name: 'SelectCapita',
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
      loading: false
    }),
    computed: {
      unicoR () {
        return this.items && this.items.length === 1
      }
    },
    methods: {
      capitar () {
        this.loading = true
        this.axios.post(`facservicios/masivos`, {facservicios: this.items.map(x => x.id), item: 'capita'})
          .then(() => {
            if (this.unicoR) {
              this.items[0].capita = this.items[0].capita === 0 ? 1 : 0
            } else {
              let asignar = this.items.find(x => x.capita === 0) ? 1 : 0
              this.items.forEach(x => { x.capita = asignar })
            }
            this.$emit('asignados')
            this.loading = false
            this.$store.commit('SNACK_SHOW', {msg: `La capita se asignó correctamente.`, color: 'success'})
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al asignar capita `, error: e})
          })
      }
    }
  }
</script>

<style>
</style>
