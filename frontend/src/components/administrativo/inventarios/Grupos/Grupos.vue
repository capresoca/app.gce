<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm6>
                <v-text-field label="Codigo"
                              v-model="grupo.codigo"
                              key="codigo"
                              v-validate="'required'"
                              name="Codigo"
                              :error-messages="errors.collect('Codigo')"
                              required>
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field label="Nombre"
                              v-model="grupo.nombre"
                              key="nombre"
                              v-validate="'required'"
                              name="Nombre"
                              :error-messages="errors.collect('Nombre')"
                              required>
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                Clase
                <v-radio-group
                  v-model="grupo.clase">
                  <v-radio
                    key="claseProductos"
                    label="Productos"
                    value="Productos"
                  ></v-radio>
                  <v-radio
                    key="claseServicios"
                    label="Servicios"
                    value="Servicios"
                  ></v-radio>
                  <v-radio
                    key="claseConsignacion"
                    label="Consignacion"
                    value="Consignacion"
                  ></v-radio>
                </v-radio-group>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Cuenta de ingresos"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarCuentaIngresos"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="person"
                  @change="val => grupo.nf_niif_ingresos_id = val"
                  @objectReturn="val => grupo.ingresos = val"
                  :value="grupo.ingresos"
                  clearable
                ></postulador>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Cuenta Gastos / Inventarios"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarCuentaGastos"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="person"
                  @change="val => grupo.nf_niif_gastos_id = val"
                  @objectReturn="val => grupo.gastos = val"
                  :value="grupo.gastos"
                  clearable
                ></postulador>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Costos de venta"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarCuentaVenta"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="person"
                  @change="val => grupo.nf_niif_venta_id = val"
                  @objectReturn="val => grupo.venta = val"
                  :value="grupo.venta"
                  clearable
                ></postulador>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="RTF en compras"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarCuentaEnCompras"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="person"
                  @change="val => grupo.nf_niif_rtfencompras_id = val"
                  @objectReturn="val => grupo.rtfencompras = val"
                  :value="grupo.rtfencompras"
                  clearable
                ></postulador>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="RTF para compras compras"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarRetencionCompras"
                  entidad="conrtfs"
                  preicon="person"
                  @change="val => grupo.nf_rtfs_paracompras_id = val"
                  @objectReturn="val => grupo.rtfparacompras = val"
                  :value="grupo.rtfparacompras"
                  clearable
                ></postulador>
              </v-flex>

              <v-flex xs12 sm6>
                Retencion ICA
                <v-radio-group
                  v-model="grupo.ica" :column="false">
                  <v-radio
                    key="icaSi"
                    label="Si"
                    value="Si"
                  ></v-radio>
                  <v-radio
                    key="icaNo"
                    label="No"
                    value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                IVA al costo
                <v-radio-group
                  v-model="grupo.iva" :column="false">
                  <v-radio
                    key="ivaSi"
                    label="Si"
                    value="Si"
                  ></v-radio>
                  <v-radio
                    key="ivaNo"
                    label="No"
                    value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>

              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda descripcion."
                  itemtext="descripcion"
                  datatitle="descripcion"
                  filter="descripcion"
                  label="Rubro presupuestal"
                  lite
                  scope="formRegistroGruposInventarios"
                  ref="buscarRubro"
                  entidad="pr_rubros"
                  preicon="person"
                  @change="val => grupo.pr_rubro_id = val"
                  @objectReturn="val => grupo.rubro = val"
                  :value="grupo.rubro"
                  clearable
                ></postulador>
              </v-flex>



            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Grupos de inventarios" subtitle="Registro y gestión" btnplus btnplustitle="Crear grupo" btnplustruncate @click="dialog = true"/>

        <loading v-model="loading" />
        <v-data-table
          :headers="headers"
          :items="grupos"
          :loading="tableLoading"
          rows-per-page-text="Registros por página"
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo  }}</td>
            <td>{{ props.item.nombre}}</td>
            <td>{{ props.item.clase}}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                direction="left"
                open-on-hover
                transition="slide-x-transition"
              >
                <v-btn
                  slot="activator"
                  v-model="props.item.show"
                  color="blue darken-2"
                  dark
                  fab
                  small
                >
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="edit(props.item)"
                  >
                    <v-icon color="accent">mode_edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
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
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'GruposInventario',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading,
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        grupos: [],
        grupo: {},
        dialog: false,
        loading: false,
        tableLoading: false,
        loadingSubmit: false,

        headers: [
          {
            text: 'Codigo',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Clase',
            align: 'left',
            sortable: false,
            value: 'clase'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0'
          }
        ]
      }
    },
    mounted () {
      this.getData()
      this.formReset()
    },
    computed: {
      modalTitulo () {
        return !this.grupo.id ? 'Nuevo grupo' : 'Edición'
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('gruposinventarios')
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.tableLoading = true
        this.axios.get(`in_grupos`)
          .then((response) => {
            if (response.data !== '') {
              this.grupos = response.data.data
              this.loading = false
              this.tableLoading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al traer los datos.' + e, color: 'danger'})
            console.log('error', e)
          })
      },
      formReset () {
        this.grupo = {
          id: ''
        }
      },
      edit (item) {
        this.dialog = true
        this.grupo = JSON.parse(JSON.stringify(item))
      },
      close () {
        this.dialog = false
        this.loadingSubmit = false
        this.formReset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('form')) {
          this.loadingSubmit = true

          let send = !this.grupo.id ? this.axios.post('in_grupos', this.grupo) : this.axios.put('in_grupos/' + this.grupo.id, this.grupo)
          send.then(response => {
            if (this.grupo.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La contestación se actualizó correctamente', color: 'success'})
              this.grupos.splice(this.grupos.findIndex(grupo => grupo.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La contestación se creó correctamente', color: 'success'})
              this.grupos.splice(0, 0, response.data.data)
            }
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {msg: 'Hay un error al guardar el registro. ' + e.errors, color: 'danger'})
            this.close()
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }

    }
  }
</script>
