<template>
  <article>
    <v-card>
      <v-form data-vv-scope="formEncuesta">
        <v-container fluid grid-list-xl>
          <v-layout row wrap class="refSpace">
            <v-flex xs12 sm12 md5 lg5 xl5 class="grey lighten-4">
              <v-toolbar class="grey lighten-4 elevation-0 toolbar--dense">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md12 class="pa-0 pt-2 head1">
                    <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
                  </v-flex>
                </v-layout>
                <!--&nbsp;-->
              </v-toolbar>
            </v-flex>
            <v-spacer></v-spacer>
            <v-divider vertical class="dNone pl-1"></v-divider>
            <v-flex xs12 sm12 md3 lg3 xl3>
              <v-datetime-picker label="Fecha Encuesta" :datetime="encuesta.fecha" @input="updateDatetime" :timeData="encuesta.fecha"></v-datetime-picker>
            </v-flex>
            <v-flex xs12 sm12 md8 lg8 xl8 class="pt-2">
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                label="Afiliado"
                :detail="detalleAfiliado"
                entidad="afiliados"
                preicon="person"
                @changeid="val => encuesta.as_afiliado_id = val"
                v-model="encuesta.afiliado"
                name="afiliado"
                rules="required"
                v-validate="'required'"
                clearable
                :error-messages="errors.collect('afiliado')"
              />
            </v-flex>
            <v-flex xs12 sm12 md4 lg4 xl4 class="pt-2">
              <v-autocomplete
                label="Municipio"
                v-model="encuesta.gn_municipio_id"
                :items="complementos.municipios"
                item-value="id"
                item-text="nombre"
                name="municipio"
                required
                v-validate="'required'"
                :error-messages="errors.collect('municipio')"
                :filter="filterMunicipios">
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.nombre"></v-list-tile-title>
                      <v-list-tile-sub-title v-html="data.item.nombre_departamento"></v-list-tile-sub-title>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm12 md12 xl12 class="pt-0">
              <v-textarea
                v-model="encuesta.lugar"
                color="primary"
                name="lugar"
                required
                v-validate="'required'"
                key="lugar"
                :error-messages="errors.collect('lugar')">
                <div slot="label">
                  <h4 class="font-weight-thin" v-text="'Lugar'"></h4>
                </div>
              </v-textarea>
            </v-flex>
            <v-flex xs12 md12 class="pt-0">
              <v-subheader class="pl-0">Preguntas de la Encuesta</v-subheader>
              <v-data-table
                :headers="headers"
                :items="preguntas"
                class="elevation-1"
                :loading="tableLoading"
                hide-actions>
                <template slot="items" slot-scope="props">
                  <tr>
                    <td>
                      <v-chip label color="teal" class="pa-1" outline v-text="props.item.numero < 10 ? ('0' + props.item.numero) : props.item.numero"></v-chip>
                    </td>
                    <td class="text-xs-justify pa-0">{{ props.item.pregunta }}</td>
                    <td class="pa-0">
                      <v-container fluid class="pb-0">
                        <v-radio-group row :key="'respuesta' + props.index"
                                       v-model="props.item.respuesta"
                                       v-validate="'required'"
                                       data-vv-as="respuesta"
                                       :error-messages="errors.collect('respuesta' + props.index)"
                                       :name="'respuesta' + props.index ">
                          <v-radio label="Si" :value="1"></v-radio>
                          <v-radio label="No" :value="0"></v-radio>
                        </v-radio-group>
                      </v-container>
                    </td>
                  </tr>
                </template>
                <template slot="no-data">
                  <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
                    Lo sentimos, no se han cargado las preguntas. <v-icon>sentiment_very_dissatisfied</v-icon>
                  </v-alert>
                  <v-alert v-else :value="true" color="info" icon="info">
                    Estamos cargando las preguntas. <v-icon>sentiment_satisfied_alt</v-icon>
                  </v-alert>
                </template>
              </v-data-table>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="save">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </article>
</template>
<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  import VDatetimePicker from '../../../general/VDatetimePicker'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {ENCUESTA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroEncuesta',
    components: {
      VDatetimePicker,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        encuesta: {},
        preguntas: [],
        respuesta: null,
        row: null,
        tableLoading: false,
        headers: [
          {
            text: 'Número',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Pregunta',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id',
            width: '200px'
          }
        ]
      }
    },
    beforeCreate () {
      this.axios.get('encuesta_complementos').then(res => {
        if (res.data.preguntas) {
          res.data.preguntas.map(x => { x.respuesta = null })
          this.preguntas = res.data.preguntas
        }
      }).catch(() => {
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los complementos.', color: 'danger'})
      })
    },
    created () {
    },
    beforeMount () {
      this.formReset()
    },
    watch: {
      'encuesta.afiliado' (val) {
        console.log('valores', val)
        this.encuesta.gn_municipio_id = val ? val.gn_municipio_id : null
      }
    },
    methods: {
      formReset () {
        this.encuesta = {
          id: null,
          as_afiliado_id: null,
          fecha: this.moment().format('YYYY-MM-DD HH:mm:ss'),
          gn_municipio_id: null,
          lugar: null,
          afiliado: null,
          municipio: null,
          respuestas: []
        }
      },
      updateDatetime (datetime) {
        this.encuesta.fecha = datetime
      },
      refresh () {
        this.$validator.reset()
        this.formReset()
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: null })
      },
      async save () {
        if (await this.validador('formEncuesta')) {
          this.encuesta.respuestas = this.preguntas.map(x => { return {au_encpregunta_id: x.id, respuesta: x.respuesta} })
          const typeRequest = this.encuesta.id ? 'editar' : 'crear'
          this.axios.post('au_encuestas', this.encuesta).then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se ha creado la encuesta correctamente.', color: 'success'})
            this.$store.commit(ENCUESTA_RELOAD_ITEM, {item: res.data.data, estado: typeRequest, key: null})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: null })
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al crear el registro.', color: 'error'})
          })
        }
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      }
    },
    computed: {
      tapTitulo () {
        return this.encuesta.id ? 'En Edición de Encuesta del Afiliado: ' + (this.afiliado ? this.afiliado.nombre1 : '####') : 'Nueva Encuesta del Estado de Salud'
      },
      complementos () {
        return store.getters.complementosPostuladorAfiliados
      }
    }
  }
</script>

<style scoped>
  table.v-table tbody td, table.v-table tbody th {
    height: 30px !important;
  }
  .dividerTwo {
    padding: 0.1mm;
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  .refSpace .spacer {
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  @media (max-width: 601px) {
    .head2 {
      text-align: right !important;
    }
  }
  @media (min-width: 602px) and (max-width: 959px){
    .head1 {
      padding-top: 4px !important;
    }
    .head2 {
      text-align: right;
    }
    .head2 h2 {
      font-size: 20px !important;
      font-weight: 500;
      line-height: 1 !important;
      letter-spacing: 0.02em !important;
      font-family: 'Roboto', sans-serif !important;
    }
  }

  @media (max-width: 959px) {
    .dNone {
      display: none !important;
    }
    .refSpace .spacer {
      display: none !important;
    }
  }
</style>
