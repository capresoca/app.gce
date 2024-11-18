<template>
  <v-card>
    <loading v-model="aportesLoading" />
    <v-card-title class="py-0">
      <v-subheader>
        Registro de archivos de aporte
      </v-subheader>
      <v-spacer/>
      <v-btn
        small
        flat
        icon
        @click.stop="$emit('cancel')"
      >
        <v-icon small>close</v-icon>
      </v-btn>
    </v-card-title>
    <v-divider/>
    <v-card-text>
      <input-file-v2
        label="Pila ADRES"
        v-model="archivoPila"
        accept="text/plain"
        :hint="'Extenciones aceptadas: txt'"
        prepend-icon="description"
        name="pila ADRES"
        v-validate="'required|fileNameContain:PILA_EPS'"
        :error-messages="errors.collect('pila ADRES')"
      ></input-file-v2>
      <input-file-v2
        label="Financiero ADRES"
        v-model="archivoFinanciero"
        accept="text/plain"
        :hint="'Extenciones aceptadas: txt'"
        prepend-icon="description"
        name="financiero ADRES"
        v-validate="'required|fileNameContain:FINANCIERO_EPS'"
        :error-messages="errors.collect('financiero ADRES')"
      ></input-file-v2>
      <v-subheader class="error--text ">{{errorIntegridad}}</v-subheader>
    </v-card-text>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn flat :loading="aportesLoading" @click.stop="$emit('cancel')">Cancelar</v-btn>
      <v-btn color="primary" :loading="aportesLoading" @click.stop="submitArchivosAportes">
        <v-icon left dark>cloud_upload</v-icon>
        Cargar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {ARCHIVO_APORTE_RELOAD_ITEM} from '@/store/modules/general/tables'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroArchivosAportes',
    props: ['value'],
    components: {
      Loading,
      InputFileV2: () => import('@/components/general/InputFileV2')
    },
    data () {
      return {
        aportesLoading: false,
        archivoPila: null,
        archivoFinanciero: null
      }
    },
    watch: {
      'value' (val) {
        if (!val) {
          this.archivoPila = null
          this.archivoFinanciero = null
        }
      }
    },
    computed: {
      errorIntegridad () {
        if ((this.archivoPila && this.archivoFinanciero) && (this.archivoPila.name === this.archivoFinanciero.name)) return 'Los archivos no pueden ser nombrados igual.'
        return null
      }
    },
    methods: {
      async submitArchivosAportes () {
        this.$validator.validateAll().then(result => {
          if (result && !this.errorIntegridad) {
            this.aportesLoading = true
            let data = new FormData()
            data.append('financiero', this.archivoFinanciero)
            data.append('pila', this.archivoPila)
            this.axios.post('archivosaportes', data)
              .then(response => {
                this.aportesLoading = false
                if (response.data !== '') {
                  this.$store.commit(SNACK_SHOW, {msg: 'Los archivos se han cargado correctamente. ', color: 'success'})
                  this.$store.commit(ARCHIVO_APORTE_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
                  this.$emit('cancel')
                }
              })
              .catch(e => {
                this.aportesLoading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al cargar los archivos de aportes. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
