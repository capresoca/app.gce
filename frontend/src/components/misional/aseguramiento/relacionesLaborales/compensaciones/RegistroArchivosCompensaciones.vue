<template>
  <v-card>
    <loading v-model="compensacionesLoading" />
    <v-card-title class="py-0">
      <v-subheader>
        Registro de archivos de compensaciones
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
      <input-date
        v-model="fecha_resultado"
        label="Fecha resultado"
        hint="Formato: (Año-Mes-Día)"
        name="fecha resultado"
        :max="maxDate"
        :min="minDate"
        v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
        :error-messages="errors.collect('fecha resultado')"
      ></input-date>
      <input-file-v2
        label="Archivo ABX"
        v-model="archivoABX"
        accept="text/plain"
        :hint="'Extenciones aceptadas: txt'"
        prepend-icon="description"
        name="archivo ABX"
        v-validate="'required|fileNameContain:ABX'"
        :error-messages="errors.collect('archivo ABX')"
      ></input-file-v2>
      <input-file-v2
        label="Archivo ACX"
        v-model="archivoACX"
        accept="text/plain"
        :hint="'Extenciones aceptadas: txt'"
        prepend-icon="description"
        name="archivo ACX"
        v-validate="'required|fileNameContain:ACX'"
        :error-messages="errors.collect('archivo ACX')"
      ></input-file-v2>
      <v-subheader class="error--text ">{{errorIntegridad}}</v-subheader>
    </v-card-text>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn flat :loading="compensacionesLoading" @click.stop="$emit('cancel')">Cancelar</v-btn>
      <v-btn color="primary" :loading="compensacionesLoading" @click.stop="submitArchivosCompensaciones">
        <v-icon left dark>cloud_upload</v-icon>
        Cargar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {ARCHIVO_COMPENSACION_RELOAD_ITEM} from '@/store/modules/general/tables'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import InputDate from '@/components/general/InputDate'
  import { Validator } from 'vee-validate'
  Validator.extend('fileNameContain', {
    validate: (value, prop) => new Promise(resolve => {
      console.log('prop', prop)
      console.log('value', value)
      let response = {valido: true, mensaje: null}
      if (value) {
        if (typeof value === 'string') {
          response = value.indexOf(prop[0]) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `El nombre del archivo debe contener ${prop[0]}`}
        } else {
          response = value.name.indexOf(prop[0]) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `El nombre del archivo debe contener ${prop[0]}`}
        }
      }
      return resolve({
        valid: response.valido,
        data: {
          message: response.mensaje
        }
      })
    }),
    getMessage: (field, params, data) => data.message
  })
  export default {
    name: 'RegistroArchivosCompensaciones',
    props: ['value'],
    components: {
      Loading,
      InputDate,
      InputFileV2: () => import('@/components/general/InputFileV2')
    },
    data () {
      return {
        compensacionesLoading: false,
        archivoABX: null,
        archivoACX: null,
        fecha_resultado: null,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10)
      }
    },
    watch: {
      'value' (val) {
        if (!val) {
          this.archivoABX = null
          this.archivoACX = null
        }
      }
    },
    computed: {
      errorIntegridad () {
        if ((this.archivoABX && this.archivoACX) && (this.archivoABX.name === this.archivoACX.name)) return 'Los archivos no pueden ser nombrados igual.'
        return null
      }
    },
    methods: {
      async submitArchivosCompensaciones () {
        this.$validator.validateAll().then(result => {
          if (result && !this.errorIntegridad) {
            this.compensacionesLoading = true
            let data = new FormData()
            data.append('ABX', this.archivoACX)
            data.append('ACX', this.archivoABX)
            data.append('fecha_resultado', this.fecha_resultado)
            this.axios.post('resultado_compensacion', data)
              .then(response => {
                this.compensacionesLoading = false
                this.$store.commit(SNACK_SHOW, {msg: 'Los archivos se han cargado correctamente. ', color: 'success'})
                this.$store.commit(ARCHIVO_COMPENSACION_RELOAD_ITEM, {item: {}, estado: 'reload', key: null})
                this.$emit('cancel')
              })
              .catch(e => {
                this.compensacionesLoading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al cargar los archivos de compensaciones. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
