<template>
  <v-card>
    <v-container
      class="pt-0 pl-0 mt-0 ml-0"
      fluid
      grid-list-xl
    >
      <v-layout row wrap>
        <v-flex sm12 :md4="rol" :md12="!rol" id="flex-create-rol">
          <v-expansion-panel key="panel1" v-model="activeNewRol" expand readonly>
            <v-expansion-panel-content
              hide-actions
            >
              <v-toolbar flat class="transparent py-3" slot="header">
                <v-icon color="green darken-4">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
                <v-toolbar-title v-html="'Roles'"/>
                <small class="mt-2 ml-1"> Registro y gestión</small>
                <v-spacer/>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    fab
                    right
                    small
                    color="accent"
                    @click.stop="createNewRol"
                    v-if="infoComponent ? infoComponent.permisos.agregar : false">
                    <v-icon>add</v-icon>
                  </v-btn>
                  <span>Crear rol</span>
                </v-tooltip>
              </v-toolbar>
              <registro-rol key="registroRolNew" v-model="newRol" @cancelar="activeNewRol = [false]" @return="val => insertRol(val, -1)"/>
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-divider/>
          <v-container
            class="py-0 my-0"
            grid-list-xl
          >
            <v-layout row wrap>
              <v-flex>
                <v-text-field
                  prepend-icon="search"
                  placeholder="Buscar..."
                  clearable
                  @input="filtrando"
                  v-model="search"
                  :disabled="!registros"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
          <v-divider/>
          <v-expansion-panel v-if="registros !== null && registros.length" key="panel2" v-model="activeRegistros" expand readonly>
            <v-expansion-panel-content
              hide-actions
              v-for="(item, index) in registros"
              :key="'expancionsRoles' + index"
            >
              <v-layout slot="header" row wrap fluid >
                <v-flex>
                  <v-list :key="'listRoles' + index" class="py-0 my-0" dense two-line>
                    <v-list-tile
                      :class="itemActive === item.id ? 'grey lighten-2' : ''"
                      :key="'internalListRoles' + index"
                      ripple
                      @click="goRol(item.id, true)"
                    >
                      <v-list-tile-action>
                        <v-icon v-if="itemActive === item.id" color="accent">check_box</v-icon>
                        <v-icon v-else>check_box_outline_blank</v-icon>
                      </v-list-tile-action>
                      <v-list-tile-content>
                        <v-list-tile-title>{{ item.nombre }}</v-list-tile-title>
                        <v-list-tile-sub-title >{{ item.descripcion }}</v-list-tile-sub-title>
                      </v-list-tile-content>
                      <v-list-tile-action>
                        <v-btn
                          flat
                          icon
                          color="orange"
                          @click.stop="openPanel(item.id, index, true)"
                          v-if="infoComponent ? infoComponent.permisos.agregar : false">
                          <v-icon>edit</v-icon>
                        </v-btn>
                      </v-list-tile-action>
                    </v-list-tile>
                  </v-list>
                  <registro-rol v-if="activeRegistros[index]" :key="'componente' + index" :value="item" @cancelar="openPanel(item.id, index, false)" @return="val => insertRol(val, index)"/>
                </v-flex>
              </v-layout>
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-progress-linear v-if="!registros" :indeterminate="true"></v-progress-linear>
          <v-container
            v-if="registros && registros.length === 0"
            class="py-0 my-0"
            grid-list-xl
          >
            <v-layout row wrap>
              <v-flex>
                <v-alert :value="true" color="error" icon="warning">
                  Lo sentimos, no encontramos roles ({{this.search}}). <v-icon>sentiment_very_dissatisfied</v-icon>
                </v-alert>
              </v-flex>
            </v-layout>
          </v-container>
          <v-divider/>
        </v-flex>
        <v-flex sm12 :md8="rol" :md12="!rol">
          <div id="flag-permisos"></div>
          <v-layout
            column
            justify-center
            row
            wrap
          >
            <div v-if="rol">
              <v-subheader>Permisos del rol: <strong>{{rol.nombre}}</strong></v-subheader>
              <v-expansion-panel focusable>
                <v-expansion-panel-content
                  v-for="(modulo, i) in modulos"
                  :key="i"
                  hide-actions
                >
                  <v-layout
                    slot="header"
                    align-center
                    row
                    spacer
                  >
                    <v-flex xs4 sm2 md1>
                      <v-avatar
                        slot="activator"
                        size="36px"
                      >
                        <v-icon
                          :color="modulo.color"
                          v-text="'extension'"
                        ></v-icon>
                      </v-avatar>
                    </v-flex>

                    <v-flex sm5 md3>
                      <strong v-html="modulo.nombre"></strong>
                    </v-flex>

                    <v-flex no-wrap xs5 sm3>
                      <v-chip
                        :color="`${modulo.color} lighten-2`"
                        class="ml-0"
                        label
                        small
                      >
                        {{ modulo.componentes.length }} Componentes
                      </v-chip>
                    </v-flex>
                  </v-layout>

                  <v-card>
                    <v-divider></v-divider>
                    <v-card-text>
                      <v-list class="pt-0" two-line>
                        <template v-for="(item, index) in modulo.componentes">
                          <v-list-tile
                            :disabled="item.disabled"
                            :key="item.id"
                            @click=""
                          >
                            <v-list-tile-action>
                              <v-icon v-if="!item.disabled" :color="modulo.color">{{item.icono}}</v-icon>
                              <v-progress-circular
                                v-else
                                indeterminate
                                color="primary"
                              ></v-progress-circular>
                            </v-list-tile-action>
                            <v-layout row wrap>
                              <v-flex xs12 sm5 md3 class="mx-0 px-0 mb-3">
                                <v-list-tile-content>
                                  <v-tooltip top :disabled="item.disabled">
                                    <v-list-tile-title class="check-title-componente mb-1" slot="activator">
                                      <v-checkbox :ripple="false" :disabled="item.disabled" :false-value="0" :true-value="1" :color="modulo.color" @change="changePermiso(item, 1)" :label="item.nombre" v-model="item.selectAll" :key="'checkComponente' + index"></v-checkbox>
                                    </v-list-tile-title>
                                    <span>{{(item.selectAll ? 'Deseleccionar' : 'Seleccionar') + ' todos los permisos'}}</span>
                                  </v-tooltip>
                                  <v-list-tile-sub-title>{{ item.descripcion }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                              </v-flex>
                              <v-flex xs12 sm7 md9>
                                <v-toolbar flat class="transparent">
                                  <v-checkbox :disabled="item.disabled" :color="modulo.color" :false-value="0" :true-value="1" @change="changePermiso(item, 0)" v-for="(permiso, index) in item.permisos" v-model="permiso[permiso.propiedad]" :label="permiso.propiedad" :key="'checkPermiso' + index"></v-checkbox>
                                </v-toolbar>
                              </v-flex>
                            </v-layout>
                          </v-list-tile>
                          <v-divider
                            :key="'x' + index"
                          ></v-divider>
                        </template>
                      </v-list>
                    </v-card-text>
                  </v-card>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </div>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
  import RegistroRol from '@/components/general/seguridad/roles/RegistroRol'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'Roles',
    components: {
      RegistroRol
    },
    data () {
      return {
        activeNewRol: [false],
        activeRegistros: [],
        itemActive: null,
        search: null,
        registros: null,
        registrosAll: null,
        rol: null,
        newRol: null,
        makeNewRol: {
          id: null,
          nombre: null,
          descripcion: null
        },
        makeModulos: null,
        modulos: null
      }
    },
    watch: {
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('roles')
      }
    },
    mounted () {
      this.reloadPage()
    },
    methods: {
      filtrando () {
        this.registros = this.search ? this.registrosAll.filter(x => (x.nombre.toLowerCase().search(this.search.toLowerCase()) !== -1) || (x.descripcion.toLowerCase().search(this.search.toLowerCase()) !== -1)) : this.registrosAll
      },
      closePanelsRols () {
        this.activeNewRol = [false]
        this.activeRegistros = [...Array(this.registros).keys()].map(_ => false)
      },
      openPanel (itemId, index, valor) {
        this.goRol(itemId, false)
        this.closePanelsRols()
        this.activeRegistros[index] = valor
      },
      createNewRol () {
        this.newRol = JSON.parse(JSON.stringify(this.makeNewRol))
        this.closePanelsRols()
        this.activeNewRol = [true]
      },
      insertRol (rolReturn, index) {
        if (index === -1) {
          this.activeNewRol = [false]
          this.registros.unshift(rolReturn)
        } else {
          this.registros.splice(this.registros.findIndex(x => x.id === rolReturn.id), 1, rolReturn)
          this.closePanelsRols()
        }
        this.goRol(rolReturn.id, true)
      },
      changePermiso (componente, all) {
        componente.disabled = true
        let copyPermisosRol = {
          gs_componente_id: componente.permisos[0].gs_componente_id,
          gs_role_id: componente.permisos[0].gs_role_id,
          id: componente.permisos[0].id
        }
        componente.permisos.forEach(permiso => {
          copyPermisosRol[permiso.propiedad] = (all === 1) ? componente.selectAll : permiso[permiso.propiedad]
        })
        const typeRequest = copyPermisosRol.id ? 'editar' : 'crear'
        let request = typeRequest === 'editar' ? this.axios.put(`gs_permiso_roles/${copyPermisosRol.id}`, copyPermisosRol) : this.axios.post('gs_permiso_roles', copyPermisosRol)
        request
          .then((response) => {
            let noSelectAll = 0
            componente.permisos.forEach(permiso => {
              permiso[permiso.propiedad] = response.data.permisoRole[permiso.propiedad]
              permiso.id = response.data.permisoRole.id
              if (permiso[permiso.propiedad] === 0) noSelectAll++
            })
            componente.disabled = false
            componente.selectAll = noSelectAll === 0 ? 1 : 0
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar guardar los permisos. ', error: e})
          })
      },
      reloadPage () {
        this.axios.get('gs_roles')
          .then((response) => {
            this.registrosAll = response.data.data.roles
            this.registros = this.registrosAll
            response.data.data.modulos.forEach(modulo => {
              modulo.componentes.forEach(componente => {
                componente.selectAll = false
                componente.disabled = false
              })
            })
            this.makeModulos = response.data.data.modulos
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar cargar los roles. ', error: e})
          })
      },
      goRol (itemId, goPermisos) {
        this.itemActive = itemId
        this.axios.get('gs_roles/' + itemId)
          .then((response) => {
            this.modulos = JSON.parse(JSON.stringify(this.makeModulos))
            this.modulos.forEach(modulo => {
              modulo.componentes.forEach(componente => {
                let permisosComponente = response.data.data.permisos.find(permiso => permiso.gs_componente_id === componente.id)
                let noSelectAll = 0
                componente.permisos.forEach(permiso => {
                  permiso[permiso.propiedad] = typeof permisosComponente !== 'undefined' ? permisosComponente[permiso.propiedad] : 0
                  permiso.id = typeof permisosComponente !== 'undefined' ? permisosComponente['id'] : null
                  permiso.gs_role_id = itemId
                  if (permiso[permiso.propiedad] === 0) noSelectAll++
                })
                componente.selectAll = noSelectAll === 0 ? 1 : 0
              })
            })
            this.rol = response.data.data
            if (goPermisos) {
              this.closePanelsRols()
              this.$vuetify.goTo('#flag-permisos',
                {
                  selector: '#flag-permisos',
                  duration: 300,
                  offset: 0 - 120,
                  easing: 'easeInOutQuad'
                }
              )
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar cargar los datos del rol. ', error: e})
          })
      }
    }
  }
</script>

<style>
  #flex-create-rol div.v-expansion-panel__header{
    cursor: default !important;
    padding: 0 !important;
  }
  .check-title-componente .v-icon {
    display: initial !important;
  }
</style>
