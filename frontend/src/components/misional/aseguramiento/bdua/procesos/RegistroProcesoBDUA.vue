<template>
  <v-card>
    <!--<toolbar-list title="Nuevo proceso BDUA"/>-->
    <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
      <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title> &nbsp;
      <!--<small v-if="cxp.id != null" class="pt-1 pb-0"> Estado: {{ cxp.estado }}</small>-->
      <v-spacer/>
      <h2 v-if="procesoBDUA.id != null" class="title">BDUA: </h2>
      <div v-if="procesoBDUA.id != null">
        <v-chip label text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="procesoBDUA.consecutivo == null ? '0000' : procesoBDUA.consecutivo"></span>
        </v-chip>
      </div>
    </v-toolbar>
    <v-form data-vv-scope="formProcesosBDUA">
      <v-container fluid grid-list-md grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12 sm12 md6 xl6>
            <v-menu
              ref="menuDate"
              :close-on-content-click="false"
              v-model="menuDate"
              :nudge-right="40"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              max-width="290px"
              min-width="290px"
            >
              <!--:return-value.sync="date"-->
              <v-text-field
                slot="activator"
                v-model="computedDateFormatted"
                label="Fecha"
                prepend-icon="event"
                readonly
                name="fecha"
                v-validate="'required|date_format:YYYY/MM/DD'"
                :error-messages="errors.collect('fecha')"
              ></v-text-field>
              <v-date-picker
                ref="picker"
                v-model="procesoBDUA.fecha"
                @input="menuDate = false"
                @change="() => {
                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                }"
                locale='es'
                :max="maxDate"
              ></v-date-picker>
            </v-menu>
          </v-flex>
          <v-flex xs12 sm12 md6 xl6>
            <v-select
              :items="tipos"
              v-model="procesoBDUA.tipo"
              label="Tipo"
              required
              v-validate="'required'"
              key="tipo"
              name="tipo"
              :error-messages="errors.collect('tipo')"
            ></v-select>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <v-container fluid grid-list-md grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 xl12 v-if="procesoBDUA.tipo === 'General'">
          <v-slide-y-transition>
            <template>
              <v-card>
                <v-tabs color="transparent" grow show-arrows>
                  <v-tabs-slider></v-tabs-slider>
                  <v-tab v-for="(item, i) in procesos" :key="i" :href="'#tab-'+ (i + 1)">
                    <span v-text="item"></span>
                  </v-tab>
                  <v-tab-item :id="'tab-' + 1" lazy>
                    <m-s></m-s>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 2" lazy>
                    <m-c></m-c>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 3" lazy>
                    <n-s></n-s>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 4" lazy>
                    <n-c></n-c>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 5" lazy>
                    <m-a></m-a>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 6" lazy>
                    <r-cuatro></r-cuatro>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 7" lazy>
                    <s-cuatro></s-cuatro>
                  </v-tab-item>
                </v-tabs>
              </v-card>
            </template>
          </v-slide-y-transition>
        </v-flex>
        <v-flex xs12 sm12 md12 xl12 v-if="procesoBDUA.tipo === 'Traslado'">
          <v-slide-y-transition>
            <template>
              <v-card>
                <v-tabs color="transparent" grow show-arrows>
                  <v-tabs-slider></v-tabs-slider>
                  <v-tab v-for="(item, i) in traslados" :key="i" :href="'#tab-'+ (i + 1)">
                    <span v-text="item"></span>
                  </v-tab>
                  <v-tab-item :id="'tab-' + 1" lazy>
                    <s-uno></s-uno>
                  </v-tab-item>
                  <v-tab-item :id="'tab-' + 2" lazy>
                    <r-uno></r-uno>
                  </v-tab-item>
                </v-tabs>
              </v-card>
            </template>
          </v-slide-y-transition>
        </v-flex>
      </v-layout>
    </v-container>
    <v-card-actions class="grey lighten-4">
      <v-layout row wrap>
        <v-flex xs6 class="text-xs-left">
          <v-btn @click="refresh(null)">Limpiar</v-btn>
        </v-flex>
        <v-flex xs6 class="text-xs-right">
          <v-btn @click="close()">Cancelar</v-btn>
          <v-btn color="primary" @click.prevent="save">Generar</v-btn>
        </v-flex>
      </v-layout>
    </v-card-actions>
    <v-dialog v-model="dialog" persistent width="300" content-class="grey lighten-3">
      <v-card color="primary" dark>
        <v-card-text>
          <span class="subheading" v-text="'Procesando registros de tipo ' + procesoBDUA.tipo"></span>
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PROCESO_BDUA_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import MS from './MS'
  import NS from './NS'
  import NC from './NC'
  import MA from './MA'
  import SUno from './S1'
  import RUno from './R1'
  import RCuatro from './R4'
  import SCuatro from './S4'
  import MC from './MC'
  export default {
    name: 'RegistroProcesoBDUA',
    components: {
      MC,
      RUno,
      SUno,
      MA,
      NC,
      NS,
      MS,
      RCuatro,
      SCuatro,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    props: ['parametros'],
    data () {
      return {
        procesoBDUA: {},
        procesoBDUAEdicion: {},
        procesos: ['MS', 'MC', 'NS', 'NC', 'MA', 'R4', 'S4'],
        traslados: ['S1', 'R1'],
        menuDate: false,
        maxDate: this.moment().format('YYYY-MM-DD'),
        tipos: ['Traslado', 'General'],
        dialog: false
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      console.log('param', this.parametros)
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    computed: {
      computedDateFormatted () {
        return this.formDate(this.procesoBDUA.fecha)
      },
      tapTitulo () {
        return !this.procesoBDUA.id ? 'Nuevo Proceso BDUA' : 'En Edición del Proceso'
      }
    },
    methods: {
      formReset () {
        this.procesoBDUAEdicion = {
          id: null,
          consecutivo: null,
          fecha: this.moment().format('YYYY-MM-DD'),
          tipo: null,
          sheet: false,
          sheet2: false
        }
      },
      getRegisto (id) {
        this.axios.get('bduaprocesos/' + id)
          .then((response) => {
            console.log('holla', response)
            if (response.data !== '') {
              this.procesoBDUA = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el proceso BDUA. ' + e.response, color: 'danger'})
          })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      refresh () {
        this.$validator.reset()
        this.procesoBDUA = JSON.parse(JSON.stringify(this.procesoBDUAEdicion))
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        this.dialog = true
        if (await this.validador('formProcesosBDUA')) {
          const typeRequest = this.procesoBDUA.id ? 'editar' : 'crear'
          this.axios.post('bduaprocesos', this.procesoBDUA).then(response => {
            if (this.procesoBDUA.id) {
              this.$store.commit(SNACK_SHOW, {
                msg: 'El proceso fue actualizó correctamente',
                color: 'success'
              })
            } else {
              this.$store.commit(SNACK_SHOW, {
                msg: 'El proceso BDUA, tipo: ' + this.procesoBDUA.tipo + ', fue enviado correctamente.',
                color: 'success'
              })
            }
            this.$store.commit(PROCESO_BDUA_RELOAD_ITEM, {
              procesoBDUA: response.data.data,
              estado: typeRequest,
              key: this.parametros.key
            })
            this.dialog = false
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {
              msg: 'Hay un error al guardar el registro. ' + e.response,
              color: 'danger'
            })
          })
        } else {
          this.dialog = false
          this.$store.commit(SNACK_SHOW, {
            msg: 'El campo fecha esta vació.',
            color: 'danger'
          })
        }
      }
    }
  }
</script>

<style scoped>

</style>
