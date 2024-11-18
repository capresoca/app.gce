<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Condición clínica</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-text-field v-model="condicionClinica.nombre"
                              label="Código" name="codigo" v-validate="'required'" required
                              :error-messages="errors.collect('codigo')" ></v-text-field>
              </v-flex>


              <v-flex xs12>
                <v-textarea
                  v-model="condicionClinica.descripcion"
                  outline
                  name="descripcion"
                  label="Descripción"
                ></v-textarea>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="guardar" :loading="loadingSubmit" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-toolbar-title>Condiciones Clínicas</v-toolbar-title>
        <small class="mt-2 ml-1"> Registro y gestión</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            fab
            color="accent"
            small
            slot="activator"
            @click="openDialog()"
          >
            <v-icon>add</v-icon>
          </v-btn>
          <span>Crear registro</span>
        </v-tooltip>
      </v-toolbar>

      <loading v-model="loading" />
      <v-data-table
        :headers="headers"
        :items="data.condclinicas"
        :loading="loading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id  }}</td>
          <td>{{ props.item.nombre}}</td>
          <td>
            <v-tooltip top>
              <v-btn
                icon
                slot="activator"
                @click="edit(props.item)"
              >
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>Ver detalle</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!loading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'CondicionesClinicas',
    components: {
      Loading
    },
    props: {
      data: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        condicionClinica: {},
        dialog: false,
        loading: false,
        loadingSubmit: false,

        headers: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.resetDialog()
    },
    methods: {
      resetDialog () {
        this.condicionClinica = {
          id: '',
          cm_convisita_id: null,
          nombre: null,
          descripcion: null
        }
      },
      edit (item) {
        this.openDialog()
        this.condicionClinica = JSON.parse(JSON.stringify(item))
      },
      openDialog () {
        this.dialog = true
        this.condicionClinica.cm_convisita_id = this.data.id
      },
      close () {
        this.dialog = false
        this.resetDialog()
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardar () {
        if (await this.validador('form')) {
          this.loadingSubmit = true
          let send = !this.condicionClinica.id ? this.axios.post('cuentasmedicas/concurrencia/condicionesclinicas', this.condicionClinica) : this.axios.put('cuentasmedicas/concurrencia/visitas/' + this.condicionClinica.id, this.condicionClinica)
          send.then(response => {
            if (this.condicionClinica.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.data.condclinicas.splice(this.data.condclinicas.findIndex(condicion => condicion.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.data.condclinicas.unshift(response.data.data)
            }
            this.close()
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
