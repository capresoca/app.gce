<template>
  <section>
    <v-snackbar
      ref="mySnackbar"
      :timeout="snackBarTimeout"
      :top="y === 'top'"
      :bottom="y === 'bottom'"
      :right="x === 'right'"
      :left="x === 'left'"
      :multi-line="mode === 'multi-line'"
      :vertical="mode === 'vertical'"
      :value="snackbar"
      :color="snackBarColor"
      auto-height
    >
      <span v-html="message"></span>
      <v-btn flat color="white" @click.native="$store.commit('SNACK_HIDDEN')">Cerrar</v-btn>
    </v-snackbar>
  </section>
</template>

<script>
  import {mapGetters, mapState} from 'vuex'
  export default {
    name: 'SnackBar',
    data () {
      return {
        y: 'top',
        x: 'right',
        mode: 'vertical'
      }
    },
    computed: {
      ...mapGetters(['message', 'snackBarColor', 'snackBarTimeout']),
      ...mapState({
        snackbar: state => state.snackbar.show
      })
    },
    methods: {
      cambioActiva () {
        setTimeout(() => {
          this.$store.commit('SNACK_HIDDEN')
        }, this.snackBarTimeout)
      }
    },
    watch: {
      snackbar (value) {
        if (value) {
          this.cambioActiva()
        }
      }
    }
  }
</script>

<style scoped>

</style>
