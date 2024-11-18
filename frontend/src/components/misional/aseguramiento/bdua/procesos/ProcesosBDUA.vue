<template>
<div>
  <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
       	<v-card>
       		<v-card-title class="grey lighten-3 elevation-0">
	    		<span class="title">{{ modalTitulo }}</span>
	    	</v-card-title>
	    	<v-container fluid grid-list-md>
	    		<v-layout row wrap>
	    			<v-flex xs12 sm12 md12>
			            <v-autocomplete
			              label="Periodos"
			              v-model="vigencia"
			              :items="listaCombo"
			              item-value="consecutivo_vigencia"
			              item-text="descripcion"
			              name="vigencia"
			              required
			              v-validate="'required'"
			              :error-messages="errors.collect('vigencia')"
			            >
			            </v-autocomplete>
			          </v-flex>
	    		</v-layout>
	    	</v-container>
	    	<v-card-actions class="grey lighten-4">
	            <v-spacer></v-spacer>
	            <v-btn @click="close">Cancelar</v-btn>
	            <v-btn color="primary" @click="download()">Descargar</v-btn>
	          </v-card-actions>
       	</v-card>
      </v-form>
  </v-dialog>
  <v-card>
    <toolbar-list class="elevation-1 grey lighten-4" :info="infoComponent" title="Procesos BDUA"></toolbar-list>
    <v-container fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-card-title class="title py-2">
              <v-avatar size="38" color="primary" class="mr-2">
                <v-icon class="white--text">fas fa-file-download</v-icon>
              </v-avatar>
              Descarga de archivos
            </v-card-title>
            <v-divider></v-divider>
            <v-container fluid grid-list-md class="pt-2">
              <v-layout row wrap>
                <v-flex xs12 sm12 md6>
                  <v-card>
                    <v-subheader>Régimen Contributivo</v-subheader>
                    <v-card-actions>
                      <v-layout align-center justify-center row fill-height>
                        <template v-for="(btn, index) in tiposProceso.contributivos">
                          <v-btn :key="'btnC' + index" :loading="btn.loading" outline color="primary" @click="abrirDialog(btn)">{{btn.type}}</v-btn>
                        </template>
                      </v-layout>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs12 sm12 md6>
                  <v-card>
                    <v-subheader>Régimen  Subsidiado</v-subheader>
                    <v-card-actions>
                      <v-layout align-center justify-center row fill-height>
                        <template v-for="(btn, index) in tiposProceso.subsidiados">
                          <v-btn :key="'btnS' + index" :loading="btn.loading" outline color="primary" @click.stop="abrirDialog(btn)">{{btn.type}}</v-btn>
                        </template>
                      </v-layout>
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
      </v-layout>
      <v-card>
        <v-card-title class="title py-2">
          Respuestas
          <v-spacer></v-spacer>
          <carga-respuesta></carga-respuesta>
        </v-card-title>
        <data-table-v2
          ref="tableRespuestasBDUA"
          v-model="dataTable"
          @resetOption="item => resetOptions(item)"
        ></data-table-v2>
      </v-card>
    </v-container>
  </v-card>
</div>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'ProcesosBDUA',
    components: {
      CargaRespuesta: () => import('@/components/misional/aseguramiento/bdua/procesos/CargaRespuesta')
    },
    data () {
      return {
        dialog: false,
        vigencia: '',
        listaCombo: [],
        botonTener: null,
        tiposProceso: {
          contributivos: [
            {
              loading: false,
              type: 'MC'
            },
            {
              loading: false,
              type: 'NC'
            },
            {
              loading: false,
              type: 'R1'
            },
            {
              loading: false,
              type: 'R4'
            }
          ],
          subsidiados: [
            {
              loading: false,
              type: 'MS'
            },
            {
              loading: false,
              type: 'NS'
            },
            {
              loading: false,
              type: 'S1'
            },
            {
              loading: false,
              type: 'S4'
            }
          ]
        },
        dataTable: {
          nameItemState: 'tableRespuestasBDUA',
          route: 'respuestasbdua',
          makeHeaders: [
            {
              text: 'ID',
              align: 'center',
              sortable: true,
              value: 'id',
              classData: 'text-xs-center'
            },
            {
              text: 'Archivo',
              align: 'left',
              sortable: true,
              value: 'nombreArchivo'
            },
            {
              text: 'Fecha Proceso',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                template:
                  `<span>{{value.fechaProceso ? moment(value.fechaProceso).format('YYYY-MM-DD') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: true,
              value: 'id',
              component: {
                template:
                  `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.usuario.email}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">{{value.usuario.name}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Fecha Registro',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                template:
                  `<span>{{value.created_at ? moment(value.created_at).format('YYYY-MM-DD HH:mm') : ''}}</span>`,
                props: ['value']
              }
            }
            // {
            //   text: 'Opciones',
            //   align: 'center',
            //   sortable: false,
            //   actions: true,
            //   singlesActions: true,
            //   classData: 'text-xs-center'
            // }
          ]
        }
      }
    },
    created () {
    },
    watch: {
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('procesosBDUA')
      },
      modalTitulo () {
        return 'Seleccione el Periodo'
      },
      complementos () {
        return store.getters.complementosPeriodosEnvios
      }
    },
    methods: {
      async download () {
        if (await this.validador('formPago')) {
          this.dialog = false
          this.botonTener.loading = true
          await this.filePrint(`bdua_file&tipo=${this.botonTener.type}&periodo=${this.vigencia}`, false)
          this.$store.commit('SNACK_SHOW', {msg: 'Generando archivo.', color: 'info'})
          this.botonTener.loading = false
          this.dialog = false
          // this.formReset()
          this.$validator.reset()
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      resetOptions (item) {
        item.options = []
        // if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'verdetalle', icon: 'find_in_page', tooltip: 'Ver detalle', color: 'primary'})
        // if (this.infoComponent && this.infoComponent.permisos.anular && this.$refs.tableAsignacionMasiva.returnIndex(item)item.estado === 'Registrado') item.options.push({event: 'eliminar', icon: 'delete_forever', tooltip: 'Eliminar', color: 'error'})
        return item
      },
      async save () {
        this.dialog = false
        // this.formReset()
        this.$validator.reset()
      },
      close () {
        this.dialog = false
        // this.formReset()
        this.$validator.reset()
      },
      abrirDialog (btn) {
        this.dialog = true
        this.botonTener = btn
        this.listaCombo = btn.type === 'NS' || btn.type === 'NC' ? this.complementos.periodosNovedades : this.complementos.periodosTraslados
      }
    }
  }
</script>

<style scoped>

</style>
