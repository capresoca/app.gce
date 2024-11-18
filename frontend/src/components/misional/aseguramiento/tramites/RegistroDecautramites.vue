<template>
  <v-card class="ma-0 pa-0" color="grey lighten-1">
    <template v-for="item in decauttramites">
      <v-card>
        <v-card-text class="pl-1 pb-0">
          <v-layout align-center>
            <v-checkbox :label="item.declaracion.nombre" :disabled="item.loading" :true-value="1" :false-value="0" v-model="item.declara" hide-details class="shrink mt-0" @change="onFilePicked($event, item.id)"/>
          </v-layout>
        </v-card-text>
        <v-slide-y-transition>
          <v-card-actions v-if="item.declara && item.declaracion.anexo === 'Si'">
            <input type="file" hidden :ref="'anexo' + item.id" accept="application/pdf" @change="onFilePicked($event, item.id)"/>
            <v-btn small :color="item.anexo ? 'primary' : 'blue-grey'" :loading="item.loading" :disabled="item.loading" class="white--text" @click.native="$refs['anexo' + item.id][0].click()" >
              <v-icon dark>attach_file</v-icon>
              Anexo
            </v-btn>
            <a v-if="item.anexo" :href="item.anexo.url_signed" target="_blank" class="ml-2" v-html="item.anexo.nombre"/>
            <small class="red--text ml-2" v-html="item.messageError"/>
          </v-card-actions>
        </v-slide-y-transition>
      </v-card>
      <v-divider/>
    </template>
  </v-card>
</template>

<script>
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroDecautramites',
    props: ['ownerid', 'value', 'rootPath'],
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        decauttramites: []
      }
    },
    components: {
    },
    watch: {
      'ownerid' (val) {
        if (val) {
          this.axios.get('decautramites/crear?key=' + this.property + '&form_id=' + val)
            .then((response) => {
              response.data.forEach(item => {
                item.messageError = ''
                item.loading = false
              })
              this.decauttramites = response.data
              this.$emit('input', this.decauttramites)
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al pedir las declaraciones y autorizaciones para éste proceso. ', error: e})
            })
        }
      }
    },
    computed: {
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
      submitDecauttramite (data) {
        this.axios.post('decautramites', data)
          .then((response) => {
            response.data.declara = parseInt(response.data.declara)
            response.data.messageError = ''
            response.data.loading = false
            this.decauttramites.splice(this.decauttramites.findIndex(x => x.id === response.data.id), 1, response.data)
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al enviar el archivo. ', error: e})
          })
      },
      onFilePicked (e, id) {
        var item = this.decauttramites.find(x => x.id === id)
        let data = new FormData()
        data.append('id', item.id)
        data.append('as_formtrasmov_id', this.property === 'as_formtrasmov_id' ? item[this.property] : '')
        data.append('as_formafiliacion_id', this.property === 'as_formafiliacion_id' ? item[this.property] : '')
        data.append('as_declaracione_id', item.as_declaracione_id)
        data.append('declara', item.declara)
        item.messageError = ''
        if (!isNaN(e) && (e === 0 || (e === 1 && item.declaracion.anexo === 'No'))) {
          this.submitDecauttramite(data)
        } else if (isNaN(e) && item.declaracion.anexo === 'Si') {
          const files = e.target.files
          if (files.length) {
            if (files[0] !== undefined) {
              if (files[0].type === 'application/pdf') {
                if (files[0].name.length <= 50) {
                  item.loading = true
                  const fr = new FileReader()
                  fr.readAsDataURL(files[0])
                  fr.addEventListener('load', () => {
                    data.append('anexo', files[0])
                    data.append('nombre_archivo', files[0].name)
                    this.submitDecauttramite(data)
                  })
                } else {
                  item.messageError = 'El nombre del archivo que desea cargar es muy largo. Longitud permitida => 50 caracteres.'
                }
              } else {
                item.messageError = 'El anexo no está en formato PDF.'
              }
            } else {
              item.messageError = 'El anexo no es un archivo válido.'
            }
          } else {
            item.messageError = 'El anexo es requerido.'
          }
        } else if (!isNaN(e) && item.declaracion.anexo === 'Si') {
          item.messageError = 'El anexo es requerido.'
        }
      }
    }
  }
</script>

<style scoped>

</style>
