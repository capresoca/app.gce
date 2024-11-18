<template>
  <v-card class="elevation-1 grey lighten-4">
    <v-card-title class="elevation-1 grey lighten-4 pa-0">
      <toolbar-list :title="title"></toolbar-list>
    </v-card-title>
    <v-container fluid grid-list-xl class="pa-3">
      <v-layout row wrap>
        <input type="file" ref="file" id="uploadFile" :accept="mimeTypes" hidden @change="onChange" :multiple="multiple"/>
        <v-flex xs12 class="grey lighten-4 elevation-0 pb-2">
          <v-card id="scroll-target" style="max-height: 250px; overflow-x: hidden" color="white lighten-2" class="elevation-0 scroll-y">
            <v-card-title v-if="!files.length" class="pt-3">
              <span class="title font-weight-medium" v-text="multiple === false ? 'Selecciona un archivo' : 'Seleccionar archivos'"></span>
            </v-card-title>
            <v-container v-else id="content-archive" class="pa-0 pt-1 ma-1" v-scroll:#scroll-target="onScroll"
                         row wrap :height="container_px" align-content-space-around>
              <v-chip color="grey lighten-2" class="theme--light"  text-color="black" v-for="(archive, index) in files" :key="`${index}`" @input="clearFile(index)" close>
                <v-avatar color="accent">
                  <v-icon color="white" small v-text="mediaTypes.find(type => type.mime === archive.type).icon"></v-icon>
                  <!--<v-icon color="white" small v-text="archive.type === 'text/plain' ? 'insert_drive_file' : 'view_compact'"></v-icon>-->
                </v-avatar>
                {{ archive.name }}
              </v-chip>
            </v-container>
          </v-card>
          <v-divider></v-divider>
          <v-alert :value="alertFile" :type="typeError" transition="scale-transition">
            {{errorArchivo}}
          </v-alert>
          <v-flex class="text-xs-right pa-1">
            <v-tooltip bottom v-if="files.length" >
              <v-btn slot="activator" class="mr-0" color="accent" small fab dark @click="clearFile('all')">
                <v-icon color="white">delete</v-icon>
              </v-btn>
              <span>Eliminar archivos</span>
            </v-tooltip>
            <v-tooltip bottom>
              <v-btn slot="activator" class="mr-0" color="accent" small fab dark @click="showFilePicker">
                <v-icon color="white">attach_file</v-icon>
              </v-btn>
              <span>Cargar archivos</span>
            </v-tooltip>
          </v-flex>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
    export default {
      name: 'FileInputArchive',
      props: ['value', 'label', 'mimeTypes', 'multiple', 'title', 'container_px', 'nameLength'],
      components: {
        ToolbarList: () => import('@/components/general/ToolbarList')
      },
      data () {
        return {
          valor: 0,
          focus: false,
          loadingArchivo: false,
          activos: null,
          errorArchivo: null,
          deleteFile: true,
          typeError: null,
          files: [],
          types: [],
          offsetTop: 0,
          alertFile: false,
          mediaTypes: [
            {
              icon: 'picture_as_pdf',
              mime: 'application/pdf'
            },
            {
              icon: 'insert_chart_outlined',
              mime: 'text/csv'
            },
            {
              icon: 'photo',
              mime: 'image/png'
            },
            {
              icon: 'photo',
              mime: 'image/jpeg'
            },
            {
              icon: 'insert_drive_file',
              mime: 'text/plain'
            },
            {
              icon: 'far fa-file-archive',
              mime: 'application/x-zip-compressed'
            }
          ]
        }
      },
      watch: {
        value (val) {
          this.files = val
        }
      },
      mounted () {
        this.types = this.mimeTypes.split(',')
      },
      methods: {
        onScroll (e) {
          this.offsetTop = e.target.scrollTop
        },
        showFilePicker () {
          this.$refs.file.click()
          this.alertFile = false
        },
        onChange (e) {
          // let files = Array.from(e.target.files)
          let files = e.target.files
          if (files.length) {
            let filesValido = []
            for (let i = 0; i < files.length; i++) {
              let file = files[i]
              // console.log('consoleje', files[i])
              if (file !== undefined) {
                if (this.types.find(type => type === file.type)) {
                  if (file.name.length <= 50) {
                    filesValido.push(file)
                  } else {
                    this.alertFile = true
                    this.typeError = 'error'
                    this.errorArchivo = 'El nombre del archivo que desea cargar es muy largo. Longitud permitida => 50 caracteres.'
                  }
                } else {
                  this.alertFile = true
                  this.typeError = 'warning'
                  if (this.types.find(type => type !== file.type)) {
                    this.errorArchivo = 'Algunos archivos que intento cargar no cumplen con el formato.'
                  } else {
                    this.errorArchivo = 'No se admite el formato del archivo.'
                  }
                }
              } else {
                this.alertFile = true
                this.typeError = 'error'
                this.errorArchivo = 'El archivo no es un archivo válido.'
              }
            }
            this.$emit('input', filesValido)
          } else {
            this.alertFile = true
            this.typeError = 'error'
            this.errorArchivo = 'El campo para cargar archivos esta vació.'
          }
        },
        clearFile (item) {
          this.alertFile = false
          item === 'all' ? this.files = [] : this.files.splice(item, 1)
          this.$emit('input', this.files)
          document.getElementById('uploadFile').value = ''
        },
        previewFile (item) {
        }
      }
    }
</script>

<style scoped>
  .warning {
   background-color: hsl(30, 100%, 50%) !important;
  }
</style>
