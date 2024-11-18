<template>
  <v-card>
    <v-card-title class="headline grey lighten-2" primary-title>
      Registrar notas debito
    </v-card-title>
    <v-card-text>
      <v-container fluid grid-list-xl >
        <v-form data-vv-scope="formCuentaPorCobrar">
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 lg6>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Identificacion tercero" value="dawds"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-textarea
                    label="Detalle de nota"
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-layout row wrap>
                <v-flex xs12 sm12 md6 lg6>
                  <v-text-field label="Nota"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md6 lg6>
                  <v-text-field label="Fecha"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Cuenta"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Centro de costo"></v-text-field>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md2 lg2>
              <v-text-field label="Factura"></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md2 lg2>
              <v-text-field label="Fecha"></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md3 lg3>
              <v-text-field label="Valor de factura"></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md3 lg3>
              <v-text-field label="Saldo factura"></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md2 lg2>
              <v-text-field label="Acumulado"></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 lg6>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12>
                  <v-data-table
                  :headers="conceptosHeader"
                  :items="conceptos"
                  class="elevation-1"
                  >
                    <template slot="items" slot-scope="props">
                      <td class="text-xs-left">{{ props.item.concepto.codigo }}</td>
                      <td class="text-xs-left">{{ props.item.concepto.nombre }}</td>
                      <td class="text-xs-left">{{ props.item.valor }}</td>
                    </template>
                  </v-data-table>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Debitos"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Creditos"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-text-field label="Valor nota"></v-text-field>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              Naturaleza
              <v-radio-group
                key="iva" :column="false">
                <v-radio
                key="1"
                label="Débito"
                value="Débito"
                ></v-radio>
                <v-radio
                key="2"
                label="Crédito"
                value="Crédito"
                ></v-radio>
              </v-radio-group>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Codigo de retnencion" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="% de retencion" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Valor base" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Valor facturado" required></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Concepto" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Servicio" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Medico" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Cuenta" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Tercero" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Centro de costo" required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-text-field label="Valor" required></v-text-field>
            </v-flex>
          </v-layout>
        </v-form>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    name: 'RegistroNotasCredito',
    data () {
      return {
        conceptosHeader: [
          { text: 'Concepto', value: 'concepto.codigo', align: 'left', sortable: false },
          { text: 'Descripcion', value: 'concepto.nombre', align: 'left', sortable: false },
          { text: 'Valor', value: 'valor', align: 'left', sortable: false }
        ],
        conceptosCuentasPorCobrar: []
      }
    }
  }
</script>

<style scoped>

</style>
