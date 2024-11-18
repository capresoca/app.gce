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
<!--      @click.stop="disabled ? null : avalar()"-->
<!--    >-->
<!--      <v-icon :color="unicoR && items[0].avalado ?'green' : 'grey'" size="medium" :class="disabled ? '' : 'text&#45;&#45;darken-2'">done_all</v-icon>-->
<!--    </v-btn>-->
<!--    <span>{{unicoR && items[0].avalado ? 'Avalado' : 'Avalar' }}</span>-->
<!--    <loading v-model="loading" is-full-page></loading>-->
<!--  </v-tooltip>-->
  <v-btn
    flat
    dark
    @click.stop="avalar()"
  >
    <span class="subheading">MARCAR AVALADO</span>
  </v-btn>
</template>

<script>
  export default {
    name: 'SelectAvala',
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
    data: () => ({
      loading: false
    }),
    computed: {
      unicoR () {
        return this.items && this.items.length === 1
      }
    },
    methods: {
      avalar () {
        if (this.items.filter(x => !x.glosas.length).length) {
          this.loading = true
          this.axios.post(`facservicios/masivos`, {facservicios: this.items.filter(x => !x.glosas.length).map(x => x.id), item: 'avalado'})
            .then(() => {
              if (this.unicoR) {
                this.items[0].avalado = !this.items[0].avalado ? 1 : null
              } else {
                let asignar = this.items.filter(x => !x.glosas.length).find(x => !x.avalado) ? 1 : null
                this.items.filter(x => !x.glosas.length).forEach(x => { x.avalado = asignar })
              }
              this.$emit('asignados')
              this.loading = false
              this.$store.commit('SNACK_SHOW', {msg: `El aval se asignó correctamente.`, color: 'success'})
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: `al asignar aval `, error: e})
            })
        } else {
          this.$store.commit('SNACK_SHOW', {msg: this.items.length > 1 ? `Los items seleccionados tienen glosas y no se pueden avalar.` : `El item seleccionado tiene glosas y no se puede avalar.`, color: 'error'})
        }
      }
    }
  }
</script>

<style>
</style>
