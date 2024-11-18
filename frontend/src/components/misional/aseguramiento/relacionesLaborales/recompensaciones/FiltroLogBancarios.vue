<template>
  <v-card>
    <toolbar-list title="Cargue LOG BANCARIO" />
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
          <v-flex xs12 sm6 md4>
            <v-select
              :items="tipoArchivos"
              v-model="item.tipo_archivo"
              item-value="id"
              item-text="valor"
              name="tipo archivo"
              label="Tipo Archivo"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field v-model="item.banco"
                          label="Banco"
                          key="banco"
                          name="banco">
            </v-text-field>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <v-card-actions class="grey lighten-4">
      <v-spacer></v-spacer>
      <v-layout row justify-center>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="optionEvent('uno')">
              <v-icon color="accent" v-text="'fas fa-search'"></v-icon>
            </v-btn>
            <span>Buscar</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="optionEvent('dos')">
              <v-icon color="teal" v-text="'fas fa-minus-circle'"></v-icon>
            </v-btn>
            <span>Limpiar</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="optionEvent('tres')">
              <v-icon color="lime lighten-1" v-text="'fas fa-download'"></v-icon>
            </v-btn>
            <span>Cargar Archivo</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs2>
          <v-tooltip top>
            <v-btn icon class="mx-0" slot="activator" @click="optionEvent('cuatro')">
              <v-icon color="orange" v-text="'fas fa-hourglass-start'"></v-icon>
            </v-btn>
            <span>Detallado Recaudo</span>
          </v-tooltip>
        </v-flex>
      </v-layout>
      <v-spacer></v-spacer>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'FiltroLogBancarios',
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
          {id: 1, valor: 'Recaudo Aportante'},
          {id: 2, valor: 'Recaudo SGP'}
        ]
      }
    },
    methods: {
      optionEvent (opc) {
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
          banco: null,
          tipo_archivo: null
        }
      }
    }
  }
</script>

<style scoped>

</style>
