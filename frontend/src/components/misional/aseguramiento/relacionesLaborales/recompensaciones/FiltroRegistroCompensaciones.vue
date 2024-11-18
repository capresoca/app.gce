<template>
  <v-card>
    <toolbar-list title="Cargue PILA" />
    <v-form data-vv-scope="formDatos">
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex xs12 sm6 md2>
            <input-date
              v-model="item.fecha_inicio"
              label="Fecha Inicio (Año-Mes-Día)"
              format="YYYY-MM-DD"
              :min="minDate"
              :max="item.fecha_fin"
              name="fecha"
              v-validate="'fechaValida:YYYY-MM-DD'"
              :error-messages="errors.collect('fecha')"
            />
          </v-flex>
          <v-flex xs12 sm6 md2>
            <input-date
              v-model="item.fecha_fin"
              label="Fecha Fin (Año-Mes-Día)"
              format="YYYY-MM-DD"
              :min="item.fecha_inicio"
              name="fecha"
              v-validate="'fechaValida:YYYY-MM-DD'"
              :error-messages="errors.collect('fecha')"
            />
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-text-field v-model="item.numero_planilla"
                          label="Número Planilla"
                          key="número planilla"
                          name="número planilla">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-text-field v-model="item.numero_documento"
                          label="Número de Documento"
                          key="número de documento"
                          name="número de documento">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-text-field v-model="item.periodo_pago"
                          label="Periodo de Pago"
                          key="periodo de pago"
                          name="periodo de pago">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-select
              :items="tipoArchivos"
              v-model="item.tipo_archivo"
              item-value="valor"
              item-text="valor"
              name="tipo archivo"
              label="Tipo Archivo"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="resultados"
              v-model="item.estadoC"
              item-value="id"
              item-text="nombre"
              name="Resultado"
              label="Resultado"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="epss"
              v-model="item.otraEPS"
              item-value="id"
              item-text="nombre"
              name="otra eps"
              label="Otra Eps"
              clearable
            ></v-select>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <v-card-actions class="grey lighten-4">
      <v-spacer></v-spacer>
      <v-layout row justify-center>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('uno')">
              <v-icon color="accent" v-text="'fas fa-search'"></v-icon>
            </v-btn>
            <span>Buscar</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('dos')">
              <v-icon color="teal" v-text="'fas fa-minus-circle'"></v-icon>
            </v-btn>
            <span>Limpiar</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('tres')">
              <v-icon color="lime lighten-1" v-text="'fas fa-download'"></v-icon>
            </v-btn>
            <span>Descargar FTP</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2 v-if="false">
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('cuatro')">
              <v-icon color="orange" v-text="'fas fa-hourglass-start'"></v-icon>
            </v-btn>
            <span>FTP Extratiempo</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2 v-if="false">
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('cinco')">
              <v-icon color="orange darken-1" v-text="'fas fa-file-download'"></v-icon>
            </v-btn>
            <span>FTP Descargador</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('seis')">
              <v-icon color="accent" v-text="'fas fa-file-export'"></v-icon>
            </v-btn>
            <span>Exportar</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2 v-if="false">
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="opcionEvent('siete')">
              <v-icon color="accent" v-text="'fas fa-retweet'"></v-icon>
            </v-btn>
            <span>Revalidar Masivo</span>
          </v-tooltip>
        </v-flex>
      </v-layout>
      <v-spacer></v-spacer>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'FiltroRegistroCompensaciones',
    data: () => ({
      minDate: '1900-01-01',
      item: null
    }),
    created () {
      this.formReset()
    },
    computed: {
      tipoArchivos () {
        return [
          {valor: 'A'},
          {valor: 'I'},
          {valor: 'AP'},
          {valor: 'IP'},
          {valor: 'AR'},
          {valor: 'IR'}
        ]
      },
      resultados () {
        return [
          { id: 1, nombre: 'Cargue Exitoso' },
          { id: 0, nombre: 'Con Inconsistencias' }
        ]
      },
      epss () {
        return [
          { id: 1, nombre: 'Si' },
          { id: 0, nombre: 'No' }
        ]
      }
    },
    methods: {
      opcionEvent (opc) {
        if (opc === 'ocho') {
          this.formReset()
        } else {
          let data = this.clone(this.item)
          data.opcion = opc
          this.$emit('filterOrSet', data)
        }
      },
      formReset () {
        this.item = {
          fecha_inicio: null,
          fecha_fin: null,
          fecha_pago: null,
          operador: null,
          numero_planilla: null,
          tipo_archivo: null,
          fecha_descarga: null,
          fecha_cargue: null,
          tipo_documento: null,
          numero_documento: null,
          periodo_pago: null,
          codigo_eps_transfiere: null,
          estadoC: null,
          otraEPS: null
        }
      }
    }
  }
</script>

<style scoped>

</style>
