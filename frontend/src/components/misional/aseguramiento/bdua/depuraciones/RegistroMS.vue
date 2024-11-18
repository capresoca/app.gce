<template>
  <v-card>
    <toolbar-list :title="'Glosas BDUA'" subtitle="ms - mc"/>
    <v-data-table
      :headers="headers"
      :items="bduas"
      class="elevation-1"
      :expand="expand"
      item-key="consecutivo_ms"
      hide-actions
    >
      <template v-slot:items="props">
        <tr>
          <td>{{ props.item.fecha_inicio_novedad }}</td>
          <td>{{ `${props.item.tipo_identificacion_afiliado} ${props.item.numero_identificacion_afiliado}` }}</td>
          <td>
          	{{props.item.informacion}}
          </td>
        </tr>
      </template>
      <template v-slot:expand="props">
        <v-card flat>
          <loading v-model="loading"></loading>
          <toolbar-list :title="'Datos Depuración'" subtitle=""/>
          <v-container fluid grid-list-xl class="pt-0">
            <v-form data-vv-scope="formDepuracionBDUA">
              <v-divider class="gray mb-3"></v-divider>
              <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.nombre1'"
                    label="Primer nombre"
                    v-model="datosBasicosAfiliado.nombre1"
                    name="Primer nombre"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Primer nombre')"
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.nombre2'"
                    label="Segundo nombre"
                    v-model="datosBasicosAfiliado.nombre2"
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.apellido1'"
                    label="Primer apellido"
                    v-model="datosBasicosAfiliado.apellido1"
                    name="Primer apellido"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Primer apellido')"
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <v-text-field
                    v-upper="'datosBasicosAfiliado.apellido2'"
                    label="Segundo apellido"
                    v-model="datosBasicosAfiliado.apellido2"
                    prepend-icon="person"
                  ></v-text-field>
                </v-flex>
              </v-layout>
              <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                  <v-autocomplete
                    label="Municipio"
                    v-model="datosBasicosAfiliado.gn_municipio_id"
                    :items="complementos.municipios"
                    item-value="id"
                    item-text="nombre"
                    name="Municipio"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Municipio')"
                    :filter="filterMunicipiosExpedicion"
                    prepend-icon="location_city">
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
                <v-flex xs12 sm6 md3>
                  <v-select
                    label="Zona"
                    v-model="datosBasicosAfiliado.gn_zona_id"
                    :items="complementos.zonas"
                    item-value="id"
                    item-text="nombre"
                    name="Zona"
                    :disabled="!afiliado.gn_municipio_id"
                  />
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <input-date
                    v-model="datosBasicosAfiliado.fecha_afiliacion"
                    label="Fecha afiliación (Día-Mes-Año)"
                    format="DD-MM-YYYY"
                    :min="minDate"
                    :max="maxDate"
                    name="Fecha afiliación"
                    v-validate="'required|fechaValida:DD-MM-YYYY'"
                    :error-messages="errors.collect('Fecha afiliación')"
                  />
                </v-flex>
                <v-flex xs12 sm6 md3>
                  <postulador-v2
                    no-data="Búsqueda por razon social u código de habilitación."
                    hint="tercero.identificacion_completa"
                    item-text="nombre"
                    data-title="tercero.identificacion_completa"
                    data-subtitle="nombre"
                    label="IPS Primaria"
                    entidad="entidades"
                    preicon="location_city"
                    @changeid="val => datosBasicosAfiliado.rs_entidade_id = val"
                    v-model="datosBasicosAfiliado.entidad"
                    route-params="rs_tipentidade_id=1"
                    name="IPS Primaria"
                    rules="required"
                    v-validate="'required'"
                    :error-messages="errors.collect('IPS Primaria')"
                    no-btn-create
                    :min-characters-search="3"
                  />
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
          <v-card-actions>
            <v-btn @click="refresh(true)">Cancelar</v-btn>
            <v-spacer></v-spacer>
            <!--            <div style="text-align: end !important;">-->
            <!--              <v-switch-->
            <!--                class="mr-4"-->
            <!--                color="success"-->
            <!--                label="Enviar Fosyga"-->
            <!--                v-model="novtramite.fosyga"-->
            <!--              ></v-switch>-->
            <!--            </div>-->
            <v-btn color="primary" @click.prevent="saveDepuracion">Actualizar</v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-data-table>
    <detalle-eliminacion ref="negeliminar" @eliminar="dato => aceptarEliminar(dato)"></detalle-eliminacion>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import DetalleEliminacion from './DetalleEliminacion'
  export default {
    name: 'RegistroMS',
    components: {DetalleEliminacion},
    props: ['parametros'],
    data: () => ({
      bduas: [],
      expand: false,
      loading: false,
      afiliado: null,
      no_afafiliado_id: null,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      components: {
        DetalleEliminacion
      },
      datosBasicosAfiliado: {
        id: null,
        gn_tipdocidentidad_id: null,
        identificacion: null,
        nombre1: null,
        nombre2: null,
        apellido1: null,
        apellido: null,
        fecha_nacimiento: null,
        gn_municipio_id: null,
        gn_zona_id: null,
        gn_sexo_id: null,
        fecha_afiliacion: null,
        rs_entidade_id: null,
        entidad: null
      },
      headers: [
        {
          text: 'Fecha NEG',
          align: 'left',
          sortable: false,
          value: 'fecha_inicio_novedad'
        },
        {
          text: 'Reporte',
          sortable: false,
          value: 'afiliado'
        },
        {
          text: 'Glosa',
          sortable: false,
          value: 'informacion_ns'
        }
      ]
    }),
    computed: {
      complementos () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosFormAfiliados))
        if (beforeComplement.tipdocidentidades) {
          beforeComplement.tipdocidentidades = beforeComplement.tipdocidentidades.filter(x => x.id !== 12)
        }
        return beforeComplement
      },
      tipo_identidad () {
        return (this.datosBasicosAfiliado.gn_tipdocidentidad_id === 1 || this.datosBasicosAfiliado.gn_tipdocidentidad_id === 12) ? 'number' : 'text'
      },
      longitudDocumento () {
        if (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && this.datosBasicosAfiliado.gn_tipdocidentidad_id) {
          return this.complementos.tipdocidentidades.find(tipo => tipo.id === this.datosBasicosAfiliado.gn_tipdocidentidad_id).longitud
        }
      }
    },
    watch: {
      'afiliado' (val) {
        if (val) {
          console.log('DAta', val)
          this.datosBasicosAfiliado = this.clone(val)
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      async saveDepuracion () {
        this.loading = true
        if (await this.$validator.validateAll('formDepuracionBDUA')) {
          this.axios.post(`bduas/depuracionms?no_afafiliado_id=${this.no_afafiliado_id}`, this.datosBasicosAfiliado)
            .then(response => {
              this.$store.commit('SNACK_SHOW', {msg: 'La depuración en la glosa de BDUA se realizó éxitoamente.', color: 'success'})
              this.loading = false
              this.refresh(true)
            }).catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el registro. ', error: e})
            })
        }
      },
      openExpandDetalle (props, strnum) {
        if (strnum === 'uno') props.expanded = !props.expanded
        if (strnum === 'dos') this.$refs.negeliminar.assign(JSON.parse(JSON.stringify(props)), strnum)
      },
      aceptarEliminar (item) {
        // console.log('asss', item)
        this.axios.post(`bduas/eliminars/${this.no_afafiliado_id}`, item)
          .then((response) => {
            this.$store.commit('reloadTable', 'tableDepuracionMs')
            this.$store.commit('SNACK_SHOW', {msg: 'Se ha eliminado la glosa de BDUA.', color: 'success'})
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al intentar eliminar la glosa de BDUA. ', error: e})
          })
      },
      filterMunicipiosExpedicion: (item, queryText) => this.getFilter(item, queryText, item.nombre + ' ' + item.nombre_departamento),
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      getProperty: (item, arr) => window.toProperty(item, arr),
      getRegistro (id) {
        this.axios.get(`bduas/depuracionms/${id}`)
          .then(response => {
            console.log('respuesta', response)
            this.afiliado = response.data.data.afiliado.afiliado
            this.no_afafiliado_id = this.clone(response.data.data.afiliado.consecutivo_ns)
            this.bduas.push(response.data.data.log)
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el registro. ', error: e})
          })
      },
      refresh (num) {
        this.$validator.reset()
        this.afiliado = null
        if (num) this.$store.commit('reloadTable', 'tableDepuracionMs')
      }
    }
  }
</script>

<style scoped>

</style>
