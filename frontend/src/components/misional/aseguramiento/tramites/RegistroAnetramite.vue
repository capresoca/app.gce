<template>
  <v-card color="blue lighten-4" ref="formAnetramite">
    <v-card-title>
      <strong>
        {{anetramite.id ? 'actualización de anexo' : 'nuevo anexo'}}
      </strong>
    </v-card-title>
    <v-divider/>
    <v-container fluid grid-list-lg>
      <v-form data-vv-scope="formAnetramite">
        <v-layout row wrap>
          <v-flex xs12>
            <v-autocomplete
              label="Tipo anexo"
              v-model="anetramite.as_tipanexo_id"
              :items="complementos.tipanexos"
              item-value="id"
              item-text="nombre"
              name="Tipo anexo"
              required
              v-validate="'required'"
              :error-messages="errors.collect('Tipo anexo')"
            />
          </v-flex>
          <v-flex xs12>
            <v-autocomplete
              :label="labelAfiliado ? labelAfiliado : 'Afiliado'"
              v-model="anetramite.as_afiliado_id"
              :items="afiliados"
              item-value="item.id"
              :hint="hintAfiliado"
              persistent-hint
              item-text="item.nombre_completo"
              :name="labelAfiliado ? labelAfiliado : 'Afiliado'"
              required
              v-validate="'required'"
              :error-messages="errors.collect(labelAfiliado ? labelAfiliado : 'Afiliado')"
            />
          </v-flex>
          <v-flex xs12>
            <input type="file" hidden ref="inputAnexo" accept="image/jpeg,image/gif,image/png,application/pdf" @change="onFilePicked($event)"/>
            <v-text-field
              v-model="anetramite.anexo.nombre"
              label="Archivo"
              prepend-icon="attach_file"
              readonly
              @click.native="$refs.inputAnexo.click()"
              name="Archivo"
              required
              v-validate="'required'"
              :error-messages="errorArchivo"
              :loading="loadingArchivo"
            />
          </v-flex>
        </v-layout>
      </v-form>
    </v-container>
    <v-card-actions>
      <v-spacer/>
      <v-btn flat @click.native="$emit('hide', true)">Cancelar</v-btn>
      <v-btn flat color="primary" @click.native="submit">Registrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    props: ['afiliados', 'tramiteid', 'objeto', 'property'],
    name: 'RegistroAfitramite',
    data () {
      return {
        loader: null,
        loadingArchivo: false,
        errorArchivo: '',
        hintAfiliado: '',
        labelAfiliado: '',
        anetramite: {},
        afiliado: null,
        make: {
          anexo: {
            nombre: ''
          },
          as_afiliado_id: null,
          as_tipanexo_id: null,
          as_formafiliacion_id: null,
          as_formtrasmov_id: null,
          gn_archivo_id: null,
          gn_tipo_doc_identidades_id: null,
          id: null
        }
      }
    },
    watch: {
      'errors.items' (val) {
        if (val.length) {
          if (val.find(x => x.field === 'Archivo')) {
            this.errorArchivo = val.find(x => x.field === 'Archivo').msg
          } else {
            this.errorArchivo = ''
          }
        }
      },
      'anetramite.as_afiliado_id' (val) {
        if (val) {
          this.hintAfiliado = this.afiliados.find(x => x.item.id === val).item.identificacion_completa
          this.labelAfiliado = this.afiliados.find(x => x.item.id === val).tipo
        }
      },
      'objeto' (val) {
        val ? this.asignaObjeto(val) : this.reset()
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAnetramite
      }
    },
    beforeMount () {
      this.reset()
    },
    mounted () {
      if (this.objeto) this.asignaObjeto(this.objeto)
    },
    methods: {
      onFilePicked (e) {
        const files = e.target.files
        if (files.length) {
          if (files[0] !== undefined) {
            if (files[0].type === 'application/pdf' || files[0].type === 'image/jpeg' || files[0].type === 'image/gif' || files[0].type === 'image/png') {
              if (files[0].name.length <= 50) {
                this.errorArchivo = ''
                this.loadingArchivo = true
                const fr = new FileReader()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', (x) => {
                  this.loadingArchivo = false
                  if (x.returnValue) {
                    this.anetramite.anexo.nombre = files[0].name
                    this.anetramite.anexo.anexo = files[0]
                  } else {
                    this.errorArchivo = 'El archivo no se ha podido cargar... intentelo nuevamente.'
                  }
                })
              } else {
                this.errorArchivo = 'El nombre del archivo que desea cargar es muy largo. Longitud permitida => 50 caracteres.'
              }
            } else {
              this.errorArchivo = 'El archivo no está en formato de imágen o PDF.'
            }
          } else {
            this.errorArchivo = 'El archivo no es un archivo válido.'
          }
        }
      },
      asignaObjeto (obj) {
        this.anetramite = obj
      },
      reset () {
        this.$validator.reset()
        this.anetramite = JSON.parse(JSON.stringify(this.make))
      },
      async submit () {
        this.$validator.validateAll('formAnetramite').then(result => {
          if (result) {
            this.loader = this.$loading.show({
              container: this.$refs['formAnetramite'].$el
            })
            let data = new FormData()
            data.append('anexo', typeof this.anetramite.anexo.anexo === 'undefined' ? '' : this.anetramite.anexo.anexo)
            data.append('nombre_archivo', this.anetramite.anexo.nombre)
            data.append('as_afiliado_id', this.anetramite.as_afiliado_id)
            data.append('as_tipanexo_id', this.anetramite.as_tipanexo_id)
            data.append('as_formafiliacion_id', this.property === 'as_formafiliacion_id' ? this.tramiteid : '')
            data.append('as_formtrasmov_id', this.property === 'as_formtrasmov_id' ? this.tramiteid : '')
            data.append('id', this.anetramite.id ? this.anetramite.id : '')
            this.axios.post('anetramites', data)
              .then((response) => {
                response.data.as_afiliado_id = parseInt(response.data.as_afiliado_id)
                response.data.as_tipanexo_id = parseInt(response.data.as_tipanexo_id)
                response.data.as_formafiliacion_id = parseInt(response.data.as_formafiliacion_id)
                response.data.gn_archivo_id = parseInt(response.data.gn_archivo_id)
                response.data.gn_tipo_doc_identidades_id = parseInt(response.data.gn_tipo_doc_identidades_id)
                response.data.id = parseInt(response.data.id)
                this.$emit('submit', response.data)
                this.loader.hide()
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el beneficiario. ', error: e})
                this.loader.hide()
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
