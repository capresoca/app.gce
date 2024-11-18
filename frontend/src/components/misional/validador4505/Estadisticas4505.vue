<template>
  <v-card>
    <loading v-model="loading" />
    <toolbar-list
      :info="infoComponent"
      title="Reportes"
      subtitle="Generación"
    />
    <v-layout
      justify-space-between
    >
      <v-flex xs12 sm12 md4>
        <v-text-field
          class="pa-2"
          v-model="search"
          append-icon="search"
          solo
          clearable
          label="Filtrar reportes..."
          type="text"
          hide-details
        />
        <div
          v-if="!Object.entries(items).length"
          class="title grey--text text-xs-center font-weight-light pa-2"
        >
          No hay coincidencias de busqueda. <v-icon>sentiment_very_dissatisfied</v-icon>
        </div>
        <v-expansion-panel
          v-else
          v-model="panel"
          expand
        >
          <v-expansion-panel-content
            v-for="(reportes, modulo) in items"
            :key="modulo"
          >
            <div slot="header">
              {{modulo}}
              <v-chip label small color="primary" text-color="white">
                {{reportes.length}} reporte{{reportes.length === 1 ? '' : 's'}}
              </v-chip>
            </div>
            <v-card>
              <v-list>
                <template v-for="(reporte, i) in reportes">
                  <v-list-tile
                    :class="selected && reporte.id === selected.id ? 'grey lighten-2' : ''"
                    :key="i"
                    avatar
                    @click="assign(reporte)"
                  >
                    <v-list-tile-avatar>
                      <v-icon :class="reporte.modulo.color + ' white--text'">assignment</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>{{ reporte.nombre }}</v-list-tile-title>
                      <v-list-tile-sub-title>Actualizado: {{reporte.updated_at ? moment(reporte.updated_at).format('DD/MM/YYYY [a las ] HH:mm') : ''}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider inset v-if="i < (reportes.length - 1)"/>
                </template>
              </v-list>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>
      <v-flex
        xs12 sm12 md8
        text-xs-center
      >
        <div id="flag-form-reporte"></div>
        <v-scroll-y-transition mode="out-in">
          <div
            v-if="!selected"
            class="title grey--text text--lighten-1 font-weight-light pt-4"
            style="align-self: center;"
          >
            Seleccione un reporte
          </div>
          <v-card
            v-else
            :key="selected.id"
            class="pt-4 mx-auto"
            flat
          >
            <v-card-text>
              <v-avatar
                size="large"
                style="height: 0px !important;"
              >
                <v-icon large :color="selected.modulo.color">
                  assignment
                </v-icon>
              </v-avatar>
              <h3 class="headline mb-2">
                {{ selected.nombre }}
              </h3>
              <div class="blue-grey--text mb-2">Actualizado: {{ selected.updated_at ? moment(selected.updated_at).format('DD/MM/YYYY [a las ] HH:mm') : '' }}</div>
              <p class="blue-grey--text subheading font-weight-bold">{{ selected.descripcion }}</p>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-text>
              <loading v-model="loadingPrueba" />
              <v-form  data-vv-scope="formPrueba" @submit.prevent="ejecutarReporte">
                <v-container class="pa-0" grid-list-md v-if="selected && selected.variables && selected.variables.length">
                  <v-layout row wrap>
                    <v-flex v-for="(input, index) in selected.variables" :key="'input' + index" xs12 sm6 :md6="selected.variables.length !==  1" :md12="selected.variables.length === 1">
                      <template v-if="input.type && input.label">
                        <v-text-field
                          v-if="input.type === 'text'"
                          :key="'input-text' + index"
                          :name="'input-text' + index"
                          :label="input.label"
                          :data-vv-as="input.label"
                          v-validate="'required'"
                          v-model="input[input.ref]"
                          :error-messages="errors.collect('input-text' + index)"
                        />
                        <v-text-field
                          v-if="input.type === 'number'"
                          :key="'input-number' + index"
                          :name="'input-number' + index"
                          :label="input.label"
                          type="number"
                          :data-vv-as="input.label"
                          v-validate="'decimal|required'"
                          v-model.number="input[input.ref]"
                          :error-messages="errors.collect('input-number' + index)"
                        />
                        <v-menu
                          v-if="input.type === 'date'"
                          :key="'menu-date' + index"
                          :ref="'menuDate' + index"
                          v-model="input.menu"
                          :return-value.sync="input[input.ref]"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            slot="activator"
                            :label="input.label"
                            v-model="input[input.ref]"
                            prepend-icon="event"
                            :name="'input-menuDate' + index"
                            :data-vv-as="input.label"
                            v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                            :error-messages="errors.collect('input-menuDate' + index)"
                            readonly
                          />
                          <v-date-picker
                            locale="es-co"
                            v-model="input[input.ref]"
                            :max="maxDate"
                            :min="minDate"
                            @input="$refs['menuDate' + index][0].save(input[input.ref])"
                            @change="() => {
                            let indexy = $validator.errors.items.findIndex(x => x.field === ('input-menuDate' + index))
                            $validator.errors.items.splice((indexy !== -1) ? indexy : 0, (indexy !== -1) ? 1 : 0)
                        }"
                          />
                        </v-menu>
                        <v-datetime-picker
                          v-if="input.type === 'dateTime'"
                          :key="'dateTime' + index"
                          :label="input.label"
                          :datetime="moment().format('YYYY-MM-DD HH:mm')"
                          @input="val => {
                            input[input.ref] = val
                          }"
                          :timeData="input[input.ref]"
                        />
                        <v-menu
                          v-if="input.type === 'period'"
                          :key="'menu-period' + index"
                          :ref="'menuPeriod' + index"
                          v-model="input.menu"
                          :return-value.sync="input[input.ref]"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            slot="activator"
                            :label="input.label"
                            v-model="input[input.ref]"
                            prepend-icon="event"
                            :name="'input-menuPeriod' + index"
                            :data-vv-as="input.label"
                            v-validate="'required|date_format:YYYY-MM|date_between:' + moment(minDate).format('YYYY-MM') + ',' + moment(maxDate).format('YYYY-MM') + ',true'"
                            :error-messages="errors.collect('input-menuPeriod' + index)"
                            readonly
                          />
                          <v-date-picker
                            locale="es-co"
                            v-model="input[input.ref]"
                            :max="maxDate"
                            :min="minDate"
                            type="month"
                            @input="$refs['menuPeriod' + index][0].save(input[input.ref])"
                            @change="() => {
                            let indexy = $validator.errors.items.findIndex(x => x.field === ('input-menuPeriod' + index))
                            $validator.errors.items.splice((indexy !== -1) ? indexy : 0, (indexy !== -1) ? 1 : 0)
                        }"
                          />
                        </v-menu>
                      </template>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
            </v-card-text>
            <form method="post" :action="urlReporte" id="formReporte" target="_blank" hidden>
              <textarea name="sentencia" style="width: 1000px !important;">
                {{selected && sentenciaGo ? sentenciaGo : ''}}
              </textarea>
            </form>
            <v-divider/>
            <v-card-actions>
              <v-btn @click="refresh">Limpiar</v-btn>
              <v-spacer/>
              <v-btn @click="ejecutarReporte" color="primary">
                <v-icon>flash_on</v-icon>
                Ejecutar reporte
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-scroll-y-transition>
      </v-flex>
    </v-layout>
  </v-card>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Estadisticas4505',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        loading: true,
        loadingPrueba: false,
        reportesBase: [],
        panel: [],
        openAll: false,
        search: '',
        selected: null,
        sentenciaGo: '',
        urlReporte: null,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10)
      }
    },
    created () {
      this.getData()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('reportes')
      },
      items () {
        if (!this.search) this.search = ''
        return this.reportesBase.filter(reporte => reporte.nombre.toLowerCase().search(this.search.toLowerCase()) !== -1 || reporte.modulo.nombre.toLowerCase().search(this.search.toLowerCase()) !== -1).reduce((value, key) => {
          (value[key['modulo']['nombre']] = value[key['modulo']['nombre']] || []).push(key)
          return value
        }, {})
      }
    },
    methods: {
      assign (reporte) {
        this.selected = JSON.parse(JSON.stringify(reporte))
        this.$vuetify.goTo('#flag-form-reporte',
          {
            selector: '#flag-form-reporte',
            duration: 300,
            offset: 0 - 120,
            easing: 'easeInOutQuad'
          }
        )
      },
      refresh () {
        this.selected.variables.forEach(x => {
          this.$nextTick(() => {
            x[x.ref] = null
          })
        })
        this.$validator.reset()
      },
      async getData () {
        this.loading = true
        this.axios.get(`reportes-disponibles`)
          .then((response) => {
            if (response.data !== '') {
              response.data.data.forEach(x => { this.reportesBase = window.lodash.uniqBy(this.reportesBase.concat(x.reportes), 'id') })
              this.reportesBase.forEach(z => z.variables.forEach(y => { if (y.type === 'date' || y.type === 'period') this.$set(y, 'menu', false) }))
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los reportes disponibles. `, error: e})
          })
      },
      ejecutarReporte () {
        this.$validator.validateAll('formPrueba').then(result => {
          if (result) {
            this.loadingPrueba = true
            let sentenciaTemporal = JSON.parse(JSON.stringify(this.selected.sentencia))
            this.selected.variables.forEach(x => {
              sentenciaTemporal = sentenciaTemporal.replace(new RegExp(x.ref, 'g'), x.type !== 'number' ? ('"' + x[x.ref] + '"') : x[x.ref])
            })
            this.sentenciaGo = sentenciaTemporal
            this.axios.get('firmar-ruta?nombre_ruta=ejecutarReporte')
              .then((response) => {
                if (response.data !== '') {
                  this.urlReporte = response.data
                  setTimeout(() => {
                    document.getElementById('formReporte').submit()
                    this.$store.commit(SNACK_SHOW, {msg: 'El reporte se ha ejecutado para descargar.', color: 'success'})
                    this.loadingPrueba = false
                  }, 500)
                }
              })
              .catch(e => {
                this.loadingPrueba = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ` al ejecutar el reporte. `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
