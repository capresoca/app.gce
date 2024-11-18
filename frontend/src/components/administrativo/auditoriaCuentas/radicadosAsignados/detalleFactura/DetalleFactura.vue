<template>
  <v-container
    :class="factura ? 'pa-0 ma-0' : ''"
    fluid
    grid-list-md
  >
    <loading v-model="loading"/>
    <v-scroll-y-transition group>
      <template v-if="factura && factura.id && items">
        <v-card key="cardtop" class="elevation-1">
          <v-container
            class="pa-2"
            fluid
            grid-list-sm
          >
            <v-layout align-center justify-space-between row wrap fill-height>
              <v-flex md4 sm6 xs12 order-xs2 order-sm2 order-md1>
                <v-toolbar class="toolbar-pa0 elevation-0" style="background: transparent !important;">
                  <v-tooltip top>
                    <v-btn
                      slot="activator"
                      icon
                      large
                      @click="getRegistro(factura.id)"
                    >
                      <v-icon
                        color="primary"
                        large
                      >
                        cached
                      </v-icon>
                    </v-btn>
                    <span>Recargar factura</span>
                  </v-tooltip>
                  <v-divider vertical></v-divider>
                  <span class="pt-1 pl-2 text-xs-left">
                    <span class="title text-capitalize">
                      Factura
                      <v-chip label color="white" text-color="red" dark absolute right top>
                        <span class="subheading">N°</span>&nbsp;
                        <span class="subheading" v-text="factura.consecutivo"></span>
                      </v-chip>
                    </span>
                    <p class="mb-0">Estado: {{factura.estado}}</p>
                    <p class="mb-0">Modalidad: {{factura.modalidad}}</p>
                  </span>
                </v-toolbar>
              </v-flex>
              <v-flex md4 sm12 xs12 order-xs1 order-sm1 order-md2 class="text-xs-center" v-if="factura.radicado">
                <span>{{factura.radicado.entidad.nombre}}</span><br/>
                <span class="font-weight-bold">{{ factura.radicado.entidad.tercero ? factura.radicado.entidad.tercero.identificacion_completa : ''}}</span>
                <br/>
                <span class="font-weight-bold" v-if="factura.radicado.contrato">Plan Cobertura: {{factura.radicado.contrato.nombre}}</span>
              </v-flex>
              <v-flex md4 sm6 xs12 order-xs3 order-sm3 order-md3 class="text-xs-right">
                <v-toolbar class="toolbar-pa0 elevation-0 py-1" style="background: transparent !important;">
                  <v-toolbar-title class="body-1 text-xs-right pr-2" style="width: 100% !important;">
                    <span>Compartido: {{currency(factura.valor_compartido)}}</span><br/>
                    <span>Comisión: {{currency(factura.valor_comision)}}</span><br/>
                    <span>Descuento: {{currency(factura.valor_descuentos)}}</span><br/>
                    <span>Neto: {{currency(factura.valor_neto)}}</span>
                  </v-toolbar-title>
                  <v-spacer></v-spacer>
                  <v-divider vertical></v-divider>
                  <v-tooltip top>
                    <v-btn
                      slot="activator"
                      icon
                      large
                      @click="verBasica = !verBasica"
                    >
                      <v-icon
                        color="primary"
                        large
                      >
                        info
                      </v-icon>
                    </v-btn>
                    <span>Datos factura</span>
                  </v-tooltip>
                </v-toolbar>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
        <v-layout row wrap key="layout">
          <v-flex xs12>
            <v-expand-transition>
              <detalle-informacion-basica v-if="factura" v-show="verBasica" v-model="factura"></detalle-informacion-basica>
            </v-expand-transition>
            <v-card>
              <v-toolbar dense class="elevation-1 white">
                <v-avatar size="36" color="primary">
                  <v-icon dark>storage</v-icon>
                </v-avatar>
                <v-btn-toggle v-model="window" class="ml-1">
                  <v-btn flat :value="0">
                    {{esCapita ? 'Servicios Contratados'  : 'Items Facturados'}}
                  </v-btn>
                  <v-btn flat :value="1">
                    Detalle Glosas
                  </v-btn>
                  <v-btn flat :value="2" v-if="esCapita">
                    Items Facturados
                  </v-btn>
                </v-btn-toggle>
                <v-divider vertical class="mr-2"></v-divider>
                <template v-if="window === 1">
                  <v-spacer></v-spacer>
                </template>
                <template v-else>
                  <v-spacer></v-spacer>
                  <v-layout row wrap>
                    <v-flex sm4 class="offset-md3">
                      <v-select
                        label="Estado"
                        v-model="filterItemsEstado"
                        :items="[{text: 'Sin revisar', value: null}, {text: 'Glosado', value: 0}, {text: 'Avalado', value: 1}]"
                        multiple
                      ></v-select>
                    </v-flex>
                    <v-flex sm5>
                      <v-text-field
                        v-model="searchItemsFacturas"
                        label="Buscar"
                        clearable
                        hide-details
                        prepend-icon="search"
                      >
                      </v-text-field>
                    </v-flex>
                  </v-layout>
                </template>
              </v-toolbar>
              <v-window class="mt-1" v-model="window">
                <v-window-item>
                  <v-data-table
                    v-if="esCapita"
                    ref="tablaServiciosContratados"
                    :select-all="auditando ? 'accent' : false"
                    v-model="selecteds"
                    item-key="id"
                    :headers="headersServiciosContratados"
                    :items="itemsFactura.filter(x => x.tipo === 'Capita' && x.flag !== 'subheader')"
                    :pagination.sync="pagination"
                    :rows-per-page-items='[50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                    disable-initial-sort
                    >
                    <template slot="items" slot-scope="props">
                      <tr :active="props.selected">
                        <td class="text-xs-center"v-if="auditando">
                          <v-checkbox
                            :input-value="props.selected"
                            primary
                            hide-details
                            @change="props.selected = !props.selected"
                          ></v-checkbox>
                        </td>
                        <td class="text-xs-center">
                          {{props.index + 1}}
                        </td>
                        <td class="px-1 pt-2 pb-0 text-xs-center" style="height: 36px !important;">
                          <template>
                            <detalle-opcion-registro-factura
                              v-if="!!props.item.recobro"
                              :data="!!props.item.recobro"
                              :content-tooltip="`Recobro${props.item.recobro ? (':' + props.item.recobro) : ''}`"
                              content-avatar="R"
                            ></detalle-opcion-registro-factura>
                            <detalle-opcion-registro-factura
                              class="mr-1"
                              v-if="!!props.item.altos_costos.length"
                              :data="!!props.item.altos_costos.length"
                              :content-tooltip="`Alto Costo${props.item.altos_costos.length ? (':' + props.item.altos_costos.map(j => j.nombre).join(', ')) : ''}`"
                              content-avatar="AC"
                              :content-badge="props.item.altos_costos.length"
                            ></detalle-opcion-registro-factura>
                            <detalle-opcion-registro-factura
                              v-if="!!props.item.avalado"
                              :data="!!props.item.avalado"
                              :content-tooltip="`${props.item.avalado ? 'Avalado' : 'Sin Avalar'}`"
                              content-avatar="done_all"
                              type-content-avatar="icon"
                              :color-avatar="props.item.avalado && 'success darken-1'"
                            ></detalle-opcion-registro-factura>
                            <detalle-opcion-registro-factura
                              v-if="!!props.item.glosas.length"
                              :data="!!props.item.glosas.length"
                              content-tooltip="Glosas"
                              content-avatar="security"
                              type-content-avatar="icon"
                              :color-avatar="props.item.glosas.length && 'error darken-1'"
                              @click="revisarRegistro('Glosar', props.item)"
                            ></detalle-opcion-registro-factura>
                          </template>
                        </td>
                        <td class="px-2 text-xs-center" style="height: 0px !important;">
                          <v-badge color="red darken-3" overlap>
                            <template slot="badge" v-if="(props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length">
                              <span>{{props.item.glosas.length}}</span>
                            </template>
                            <v-chip label class="ma-0" :color="(props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length ? 'red' : props.item.avalado === null ? 'orange' : 'success'" text-color="white" small>
                              {{ (props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length ? 'Glosado' : props.item.avalado === null ? 'Sin revisar' : 'Avalado' }}
                            </v-chip>
                          </v-badge>
                        </td>
                        <td class="px-2" style="height: 36px !important;">
                          <p><strong>Código: {{props.item.codigo}} -- </strong>{{props.item.nombre}}</p>
                        </td>
                        <td class="px-2 text-xs-right" style="height: 36px !important;">
                          {{currency(props.item.copago)}}
                        </td>
                        <td class="px-2 text-xs-right" style="height: 36px !important;">
                          {{currency(props.item.valor_total)}}
                        </td>
                        <td class="px-2 text-xs-right" style="height: 36px !important;">
                          {{ currency(props.item.valor_glosado) }}
                        </td>
                      </tr>
                    </template>
                  </v-data-table>
                  <v-data-table
                    v-else
                    ref="tablaItemsFactura"
                    :select-all="auditando ? 'accent' : false"
                    v-model="selecteds"
                    :headers="headers"
                    :items="itemsFactura.filter(x => x.tipo !== 'Capita')"
                    :pagination.sync="pagination"
                    :rows-per-page-items='[50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                    disable-initial-sort
                    >
                    <template slot="items" slot-scope="props">
                      <tr :active="props.selected">
                        <template v-if="props.item.flag === 'subheader'">
                          <td :colspan="auditando ? '8' : '7'" class="font-weight-bold" style="height: 30px !important;">
                            {{ props.item.nombre }}
                          </td>
                          <td class="font-weight-bold text-xs-right" style="height: 30px !important;">
                            {{ currency(props.item.valor_total) }}
                          </td>
                          <td class="font-weight-bold text-xs-right" style="height: 30px !important;">
                            {{ currency(props.item.valor_glosado) }}
                          </td>
                        </template>
                        <template v-else>
                          <td class="text-xs-center" v-if="auditando">
                            <v-checkbox
                              :input-value="props.selected"
                              primary
                              hide-details
                              @change="props.selected = !props.selected"
                            ></v-checkbox>
                          </td>
                          <td class="text-xs-center">
                            {{props.item.indexItem}}
                          </td>
                          <td class="px-1 pt-2 pb-0 text-xs-center" style="height: 36px !important;">
                            <template>
                              <detalle-opcion-registro-factura
                                v-if="!!props.item.recobro"
                                :data="!!props.item.recobro"
                                :content-tooltip="`Recobro${props.item.recobro ? (':' + props.item.recobro) : ''}`"
                                content-avatar="R"
                              ></detalle-opcion-registro-factura>
                              <detalle-opcion-registro-factura
                                class="mr-1"
                                v-if="!!props.item.altos_costos.length"
                                :data="!!props.item.altos_costos.length"
                                :content-tooltip="`Alto Costo${props.item.altos_costos.length ? (':' + props.item.altos_costos.map(j => j.nombre).join(', ')) : ''}`"
                                content-avatar="AC"
                                :content-badge="props.item.altos_costos.length"
                              ></detalle-opcion-registro-factura>
                              <detalle-opcion-registro-factura
                                v-if="!!props.item.capita"
                                :data="!!props.item.capita"
                                :content-tooltip="props.item.capita ? 'Capitado' : 'No Capitado'"
                                content-avatar="C"
                              ></detalle-opcion-registro-factura>
                              <detalle-opcion-registro-factura
                                v-if="!!props.item.avalado"
                                :data="!!props.item.avalado"
                                :content-tooltip="`${props.item.avalado ? 'Avalado' : 'Sin Avalar'}`"
                                content-avatar="done_all"
                                type-content-avatar="icon"
                                :color-avatar="props.item.avalado && 'success darken-1'"
                              ></detalle-opcion-registro-factura>
                              <detalle-opcion-registro-factura
                                v-if="!!props.item.glosas.length"
                                :data="!!props.item.glosas.length"
                                content-tooltip="Glosas"
                                content-avatar="security"
                                type-content-avatar="icon"
                                :color-avatar="props.item.glosas.length && 'error darken-1'"
                                @click="revisarRegistro('Glosar', props.item)"
                              ></detalle-opcion-registro-factura>
                            </template>
                          </td>
                          <td class="px-2 text-xs-center" style="height: 0px !important;">
                            <v-badge color="red darken-3" overlap>
                              <template slot="badge" v-if="(props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length">
                                <span>{{props.item.glosas.length}}</span>
                              </template>
                              <v-chip label class="ma-0" :color="(props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length ? 'red' : props.item.avalado === null ? 'orange' : 'success'" text-color="white" small>
                                {{ (props.item.avalado !== null && !props.item.avalado) || props.item.glosas.length ? 'Glosado' : props.item.avalado === null ? 'Sin revisar' : 'Avalado' }}
                              </v-chip>
                            </v-badge>
                          </td>
                          <td class="px-2" style="height: 36px !important;">
                          </td>
                          <td class="px-2" style="height: 36px !important;">
                            <p><strong>Código: {{props.item.codigo}} -- </strong>{{props.item.nombre}} - <strong>Fecha: {{props.item.fecha_servicio}}</strong></p>
                          </td>
                          <td class="px-2 text-xs-center" style="height: 36px !important;">
                            {{ props.item.cantidad }}
                          </td>
                          <td class="px-2 text-xs-right" style="height: 36px !important;">
                            {{currency(props.item.copago)}}
                          </td>
                          <td class="px-2 text-xs-right" style="height: 36px !important;">
                            {{currency(props.item.valor_total)}}
                          </td>
                          <td class="px-2 text-xs-right" style="height: 36px !important;">
                            {{ currency(props.item.valor_glosado) }}
                          </td>
                        </template>
                      </tr>
                    </template>
                    <template slot="footer">
                      <tr style="height: 10px !important;">
                        <td :colspan="auditando ? '9' : '8'" class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          Valor radicado:
                        </td>
                        <td class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          {{currency(factura.valor_neto)}}
                        </td>
                      </tr>
                      <tr style="height: 10px !important;">
                        <td :colspan="auditando ? '9' : '8'" class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          Valor glosado:
                        </td>
                        <td class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          {{currency(valorGlosado)}}
                        </td>
                      </tr>
                      <tr style="height: 10px !important;">
                        <td :colspan="auditando ? '9' : '8'" class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          Valor avalar:
                        </td>
                        <td class="font-weight-bold text-xs-right py-0" style="height: 30px !important;">
                          {{currency(factura.valor_neto - valorGlosado)}}
                        </td>
                      </tr>
                    </template>
                    <template slot="pageText" slot-scope="props">
                      <span>{{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}</span><br/>
                      <span>Items de factura {{$refs && $refs.tablaItemsFactura ? $refs.tablaItemsFactura.filteredItems.filter(x => x.flag !== 'subheader').length : '0'}}</span>
                    </template>
                  </v-data-table>
                </v-window-item>
                <v-window-item>
                  <v-data-table
                    :select-all="auditando ? 'accent' : false"
                    v-model="selectedsDetalleGlosas"
                    :headers="headersDetalleGlosas"
                    :items="itemsGlosas"
                    :pagination.sync="paginationGlosas"
                    rows-per-page-text="Registros por página"
                    :rows-per-page-items='[50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                    disable-initial-sort
                    >
                    <template slot="items" slot-scope="props">
                      <tr :active="props.selected">
                        <template v-if="props.item.flag === 'subheader' || props.item.flag === 'totales'">
                          <td :colspan="auditando ? '3' : '2'" class="font-weight-bold" :class="props.item.flag === 'totales' ? 'text-xs-right' : ''" style="height: 30px !important;">
                            {{ props.item.codigo }} - {{ props.item.nombre }} (Cantidad: {{ props.item.cantidad }}) - Fecha: {{props.item.fecha_servicio}}
                          </td>
                          <td class="font-weight-bold text-xs-right" style="height: 30px !important;">
                            {{ currency(props.item.valor_total) }}
                          </td>
                          <td class="font-weight-bold text-xs-right" style="height: 30px !important;">
                            {{ currency(props.item.valor_glosado) }}
                          </td>
                        </template>
                        <template v-else>
                          <td class="text-xs-left" v-if="auditando">
                            <span class="red--text" v-if="props.item.of_facservicio">Glosa de factura</span>
                            <v-checkbox
                              v-else-if="auditando"
                              :input-value="props.selected"
                              primary
                              hide-details
                              @change="props.selected = !props.selected"
                            ></v-checkbox>
                          </td>
                          <td class="px-2" style="height: 36px !important;">
                            <v-tooltip top>
                              <v-btn icon flat color="info" slot="activator">
                                <v-icon>info</v-icon>
                              </v-btn>
                              <span>{{ props.item.glosa.ayuda }}</span>
                            </v-tooltip>
                            {{ props.item.glosa.glosa }} - {{ props.item.glosa.descripcion }}
                          </td>
                          <td class="px-2 text-xs-center" style="height: 36px !important;">
                            {{ props.item.tipo }}
                          </td>
                          <td colspan="2" class="px-2 text-xs-right" style="height: 36px !important;">
<!--                            {{props.item.tipo === 'Porcentaje' ? `(${props.item.valor_glosa}%)` : ''}} {{currency( props.item.tipo === 'Porcentaje' ? (props.item.valor_glosa && props.item.valor_servicio) ? ((props.item.valor_servicio * props.item.valor_glosa) / 100): 0 : props.item.valor_glosa)}}-->
                            {{props.item.tipo === 'Porcentaje' ? '' : ''}} {{currency( props.item.tipo === 'Porcentaje' ? (props.item.valor_glosa && props.item.valor_servicio) ? (props.item.valor_glosa): 0 : props.item.valor_glosa)}}
                            <v-icon color="success" v-if="!props.item.of_facservicio">add</v-icon>
                          </td>
                        </template>
                      </tr>
                    </template>
                    <template slot="pageText" slot-scope="props">
                      {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                    </template>
                  </v-data-table>
                </v-window-item>
                <v-window-item v-if="esCapita">
                  <v-data-table
                    ref="tablaItemsServiciosDetalle"
                    :headers="headersServiciosDetalle"
                    :items="itemsFactura.filter(x => x.tipo !== 'Capita')"
                    :pagination.sync="pagination"
                    :rows-per-page-items='[50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                    disable-initial-sort
                    >
                    <template slot="items" slot-scope="props">
                      <tr :active="props.selected">
                        <template v-if="props.item.flag === 'subheader'">
                          <td colspan="3" class="font-weight-bold" style="height: 30px !important;">
                            {{ props.item.nombre }}
                          </td>
                          <td class="font-weight-bold text-xs-center" style="height: 30px !important;">
                            {{ props.item.cantidad }}
                          </td>
                          <td class="font-weight-bold text-xs-center" style="height: 30px !important;">
                          </td>
                          <td class="font-weight-bold text-xs-right" style="height: 30px !important;">
                            {{ currency(props.item.valor_total) }}
                          </td>
                        </template>
                        <template v-else>
                          <td class="text-xs-center">
                            {{props.item.indexItem}}
                          </td>
                          <td class="px-2" style="height: 36px !important;">
                          </td>
                          <td class="px-2" style="height: 36px !important;">
                            <p><strong>Código: {{props.item.codigo}} -- </strong>{{props.item.nombre}}</p>
                          </td>
                          <td class="px-2 text-xs-center" style="height: 36px !important;">
                            {{ props.item.cantidad }}
                          </td>
                          <td class="px-2 text-xs-right" style="height: 36px !important;">
                            {{currency(props.item.copago)}}
                          </td>
                          <td class="px-2 text-xs-right" style="height: 36px !important;">
                            {{currency(props.item.valor_total)}}
                          </td>
                        </template>
                      </tr>
                    </template>
                    <template slot="pageText" slot-scope="props">
                      <span>{{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}</span><br/>
                      <span>Items de factura {{$refs && $refs.tablaItemsFactura ? $refs.tablaItemsFactura.filteredItems.filter(x => x.flag !== 'subheader').length : '0'}}</span>
                    </template>
                  </v-data-table>
                </v-window-item>
              </v-window>
              <v-card class="mt-2" v-if="!esCapita">
                <v-toolbar dense class="elevation-1 white">
                  <v-avatar size="36" color="primary">
                    <v-icon dark>security</v-icon>
                  </v-avatar>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title class="subheading">Glosas de factura</v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-tooltip left v-if="auditando">
                    <v-btn
                      slot="activator"
                      icon
                      @click="openGlosaFactura"
                    >
                      <v-icon
                        color="primary"
                      >
                        add
                      </v-icon>
                    </v-btn>
                    <span>agregar glosa de factura</span>
                  </v-tooltip>
                </v-toolbar>
                <v-container
                  class="pa-2"
                  fluid
                  grid-list-md
                >
                  <v-layout row wrap>
                    <v-flex xs12 v-if="factura && factura.glosas && !factura.glosas.length" class="text-xs-center">
                      <div
                        class="title grey--text text--lighten-1 font-weight-light pt-2"
                      >
                        No se {{auditando ? 'han registrado' : 'registran' }} glosas de factua.
                      </div>
                    </v-flex>
                    <v-flex xs12 v-else>
                      <v-data-table
                        :headers="headersGlosasFactura"
                        :items="factura.glosas"
                        no-data-text="No hay glosas registradas"
                        hide-actions
                      >
                        <template slot="items" slot-scope="props">
                          <td class="px-2 text-xs-center" style="height: 0px !important;">
                            <v-tooltip top>
                              <v-btn
                                small
                                slot="activator"
                                icon
                                :disabled="!auditando"
                                @click="auditando ? confirmaBorrado(props) : false"
                              >
                                <v-icon color="red" class="text--darken-2">delete_forever</v-icon>
                              </v-btn>
                              <span>Borrar glosa</span>
                            </v-tooltip>
                          </td>
                          <td class="px-2" style="height: 0px !important;">
                            <span class="ma-0 body-1">{{ props.item.glosa.glosa }}: {{ props.item.glosa.descripcion }}</span><br/>
                            <span class="ma-0 caption font-weight-bold">Tipo: {{props.item.glosa.tipo}}</span>
                          </td>
                          <td class="px-2 text-xs-right" style="height: 0px !important;">
                            {{ currency(props.item.valor_glosa) }}
                          </td>
                          <td class="px-2 text-xs-justify" style="height: 0px !important;">
                            {{ props.item.observacion }}
                          </td>
                        </template>
                        <template slot="footer">
                          <td colspan="3" class="font-weight-bold text-xs-right">
                            Total Glosado
                          </td>
                          <td class="font-weight-bold text-xs-right">
                            {{currency(totalGlosadoFactura)}}
                          </td>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
              <v-card-actions v-if="auditando">
                <v-spacer></v-spacer>
                <v-btn v-if="!itemsSinRevisar" color="indigo" @click.stop="confirmaAuditoria">
                  <span class="white--text">Confirmar Auditoría</span>
                </v-btn>
                <v-chip v-else label color="orange" text-color="white">
                  Hay registros por revisar
                </v-chip>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </template>
    </v-scroll-y-transition>
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    ></confirmar>
    <confirmar
      :loading="dialogB.loading"
      :value="dialogB.visible"
      :content="dialogB.content"
      @cancelar="cancelaConfirmacionB"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacionB"
    ></confirmar>
    <glosas
      :auditando="auditando"
      :facturaid="factura && factura.id"
      :capitado="esCapita"
      v-model="itemSeleccionado"
      :glosas="glosas_registro"
      ref="glosas"
    ></glosas>
    <registro-glosa-factura
      v-if="factura && auditando && !esCapita"
      v-model="factura"
      :glosas="glosas_registro"
      ref="glosasFactura"
      @refreshFactura="getRegistro(factura.id)"
    ></registro-glosa-factura>
    <v-bottom-nav
      v-if="auditando"
      fixed
      dark
      mandatory
      :value="selecteds.length"
      color="info"
    >
      <v-list-tile class="white--text title">
        Servicios Seleccionados: <strong> {{selecteds.length}}</strong>
      </v-list-tile>
      <template>
        <v-divider vertical></v-divider>
        <select-recobro key="crecobro" :items="selecteds.filter(x => x.id)" @asignados="selecteds = []"></select-recobro>
        <v-divider vertical></v-divider>
        <select-alto-costo key="caltocostom" :items="selecteds.filter(x => x.id)" @asignados="selecteds = []"></select-alto-costo>
        <template v-if="!esCapita">
          <v-divider vertical></v-divider>
          <select-capita key="ccapita" :items="selecteds.filter(x => x.id)" @asignados="selecteds = []"></select-capita>
        </template>
        <v-divider vertical></v-divider>
        <select-avala key="cavala" :items="selecteds.filter(x => x.id)" @asignados="selecteds = []"></select-avala>
        <v-divider vertical></v-divider>
        <registro-glosa-masivo
          key="cglosas"
          :items="selecteds.filter(x => x.id)"
          :glosas="glosas_registro"
          @cancelar="selecteds = []"
          @refreshFactura="getRegistro(factura.id)"
          :capitado="esCapita"
          :facturaid="factura && factura.id"
        ></registro-glosa-masivo>
      </template>
    </v-bottom-nav>
  </v-container>
</template>
<script>
  import {mapState} from 'vuex'
  import store from '@/store/complementos/index'
  import SelectAltoCosto from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/SelectAltoCosto'
  import SelectCapita from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/SelectCapita'
  import SelectRecobro from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/SelectRecobro'
  import SelectAvala from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/SelectAvala'
  import RegistroGlosaMasivo from '@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/RegistroGlosaMasivo'
  export default {
    name: 'DetalleFactura',
    props: ['parametros'],
    components: {
      Confirmar: () => import('@/components/general/Confirmar'),
      RegistroGlosaFactura: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/RegistroGlosaFactura'),
      DetalleInformacionBasica: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/DetalleInformacionBasica'),
      DetalleOpcionRegistroFactura: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/DetalleOpcionRegistroFactura'),
      Glosas: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/detalleFactura/Glosas'),
      SelectAltoCosto,
      SelectCapita,
      SelectRecobro,
      SelectAvala,
      RegistroGlosaMasivo
    },
    data: () => ({
      searchItemsFacturas: null,
      window: 0,
      pagination: {},
      paginationGlosas: {},
      filterItemsEstado: [null, 0, 1],
      headersGlosasFactura: [
        {
          text: '',
          align: 'center',
          sortable: false
        },
        {
          text: 'Glosa',
          align: 'left',
          sortable: false
        },
        {
          text: 'Valor',
          align: 'right',
          sortable: false
        },
        {
          text: 'Observaciones',
          align: 'center',
          sortable: false
        }
      ],
      headers: [
        {
          text: 'item',
          align: 'center',
          sortable: false,
          value: 'indexItem'
        },
        {
          text: '',
          align: 'center',
          sortable: false,
          value: 'id'
        },
        {
          text: 'Estado',
          align: 'center',
          sortable: false,
          value: 'id'
        },
        {
          text: 'Afiliado',
          align: 'left',
          sortable: false
        },
        {
          text: 'Servicio',
          align: 'left',
          sortable: false,
          value: 'codigo'
        },
        {
          text: 'Cant.',
          align: 'center',
          sortable: false,
          value: 'cantidad'
        },
        {
          text: 'Vl. Copago',
          align: 'right',
          sortable: false
        },
        {
          text: 'Vl. Neto',
          align: 'right',
          sortable: false
        },
        {
          text: 'Vl. Glosado',
          align: 'right',
          sortable: false,
          value: 'valor_glosado'
        }
      ],
      headersServiciosDetalle: [
        {
          text: 'item',
          align: 'center',
          sortable: false,
          value: 'indexItem'
        },
        {
          text: 'Afiliado',
          align: 'left',
          sortable: false
        },
        {
          text: 'Servicio',
          align: 'left',
          sortable: false,
          value: 'codigo'
        },
        {
          text: 'Cant.',
          align: 'center',
          sortable: false,
          value: 'cantidad'
        },
        {
          text: 'Vl. Copago',
          align: 'right',
          sortable: false
        },
        {
          text: 'Vl. Neto',
          align: 'right',
          sortable: false
        }
      ],
      headersServiciosContratados: [
        {
          text: 'item',
          align: 'center',
          sortable: false,
          value: ''
        },
        {
          text: '',
          align: 'center',
          sortable: false,
          value: 'id'
        },
        {
          text: 'Estado',
          align: 'center',
          sortable: false,
          value: 'id'
        },
        {
          text: 'Servicio',
          align: 'left',
          sortable: false,
          value: ''
        },
        {
          text: 'Vl. Copago',
          align: 'right',
          sortable: false
        },
        {
          text: 'Vl. Neto',
          align: 'right',
          sortable: false
        },
        {
          text: 'Vl. Glosado',
          align: 'right',
          sortable: false,
          value: 'valor_glosado'
        }
      ],
      headersDetalleGlosas: [
        {
          text: 'Nombre',
          align: 'left',
          sortable: false,
          value: 'nombre'
        },
        {
          text: 'Tipo Glosa',
          align: 'center',
          sortable: false,
          value: 'cantidad'
        },
        {
          text: 'Total Servicio',
          align: 'right',
          sortable: false
        },
        {
          text: 'Total Glosado',
          align: 'right',
          sortable: false,
          value: 'valor_glosado'
        }
      ],
      dialogA: {
        loading: false,
        visible: false,
        content: null
      },
      dialogB: {
        loading: false,
        visible: false,
        props: null,
        content: null
      },
      verBasica: false,
      loading: true,
      facturaOriginal: null,
      factura: null,
      selecteds: [],
      selectedsDetalleGlosas: [],
      itemSeleccionado: null,
      glosas_registro: [],
      glosas_factura: [],
      tiposAltoCosto: []
    }),
    watch: {
      'items': {
        handler: val => {
          if (val) {
            val.forEach(x => {
              let glosadoRegistro = 0
              if (x.flag === 'subheader') {
                val.filter(j => j.flag === 'registro' && j.tipo === x.nombre).forEach(t => {
                  glosadoRegistro = glosadoRegistro + t.valor_glosado
                })
                x.valor_glosado = glosadoRegistro
              } else {
                x.glosas && x.glosas.filter(p => !p.of_facservicio).forEach(y => {
                  // glosadoRegistro = glosadoRegistro + (y.tipo !== 'Porcentaje' ? y.valor_glosa : ((x.valor_total * y.valor_glosa) / 100))
                  glosadoRegistro = glosadoRegistro + (y.valor_glosa)
                })
                // x.valor_glosado = glosadoRegistro > x.valor_total ? x.valor_total : glosadoRegistro
                x.valor_glosado = glosadoRegistro
              }
            })
          }
        },
        deep: true
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFacturaAuditoria
      },
      ...mapState({
        currentUser: state => state.user.profile
      }),
      auditando () {
        if (this && this.parametros.esdetalle) {
          return false
        } else {
          return !!(this && this.factura && this.parametros && (this.factura.estado === 'Asignada' || this.factura.estado === 'Auditando') && this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 43))
        }
      },
      esCapita () {
        return (this && this.factura && (this.factura.modalidad === 'Capita') && this.factura.servicios_contratados && this.factura.servicios_contratados.length > 0)
      },
      totalGlosadoFactura () {
        let glosado = 0
        if (this.factura && this.factura.glosas) {
          glosado = this.factura.valor_neto
        }
        return glosado
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('cmradicadas')
      },
      cuentaGlosas () {
        let glosas = 0
        if (this.items) {
          this.items.forEach(x => {
            glosas = glosas + (x.glosas ? x.glosas.length : 0)
          })
        }
        if (this.factura && this.factura.glosas) {
          glosas = glosas + this.factura.glosas.length
        }
        return glosas
      },
      itemsSinRevisar () {
        let items = 0
        if (this.items) {
          items = this.items.filter(w => this.factura.modalidad === 'Capita' ? w.tipo === 'Capita' : w.tipo !== 'Capita').filter(x => x.flag === 'registro' && x.avalado === null).length
        }
        return items
      },
      valorGlosado () {
        let glosado = 0
        if (this.items) {
          this.items.forEach(x => {
            let glosadoRegistro = 0
            x.glosas && x.glosas.filter(z => !z.of_facservicio).forEach(y => {
              glosadoRegistro = glosadoRegistro + (y.valor_glosa)
              // glosadoRegistro = glosadoRegistro + (y.tipo !== 'Porcentaje' ? y.valor_glosa : ((x.valor_total * y.valor_glosa) / 100))
            })
            glosado = glosado + (glosadoRegistro > x.valor_total ? x.valor_total : glosadoRegistro)
          })
        }
        return glosado
      },
      itemsGlosas () {
        let data = this.items.filter(x => x.id && x.glosas.length).reduce((value, key) => {
          (value[key['id']] = value[key['id']] || []).push(key)
          return value
        }, {})
        let obj = []
        Object.entries(data).forEach((value) => {
          let subheader = { flag: 'subheader', registros: value[1][0].glosas.length, fecha_servicio: value[1][0].fecha_servicio, cantidad: value[1][0].cantidad, codigo: value[1][0].codigo, nombre: value[1][0].nombre, valor_total: value[1][0].valor_total, valor_glosado: value[1][0].valor_glosado }
          obj.push(subheader)
          value[1][0].glosas.map(x => { x.valor_servicio = value[1][0].valor_total })
          obj = obj.concat(value[1][0].glosas)
        })
        return obj
      },
      items () {
        if (this.factura && this.factura.servicios) {
          let data = this.factura && this.factura.servicios && this.factura.servicios.reduce((value, key) => {
            (value[key['tipo']] = value[key['tipo']] || []).push(key)
            return value
          }, {})
          let obj = []
          Object.entries(data).forEach((value) => {
            let subheader = { nombre: value[0], flag: 'subheader', tipo: value[1][0].tipo, registros: value[1].length, valor_total: window.lodash.sumBy(value[1], 'valor_total'), cantidad: window.lodash.sumBy(value[1], 'cantidad'), valor_glosado: window.lodash.sumBy(value[1], 'valor_glosado') }
            obj.push(subheader)
            obj = obj.concat(value[1])
          })
          return obj
        }
      },
      itemsFactura () {
        if (this.items) {
          return this.items.filter(x =>
            (x.flag === 'subheader' ||
            (x.flag === 'registro' && x.avalado === this.filterItemsEstado.find(val => x.avalado === val))) &&
            (this.searchItemsFacturas
              ? (x.nombre.toString().toLowerCase().indexOf(this.searchItemsFacturas.toString().toLowerCase()) > -1 ||
                (x.codigo && x.codigo.toString().toLowerCase().indexOf(this.searchItemsFacturas.toString().toLowerCase()) > -1))
              : true
            )
          )
        }
        return []
      }
    },
    created () {
      this.getRegistro(this.parametros.entidad.id)
      this.getGlosas()
    },
    methods: {
      revisarRegistro (tipo, item) {
        this.itemSeleccionado = item
        this.$refs.glosas.open()
      },
      openGlosaFactura () {
        this.$refs.glosasFactura.open()
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        this.dialogA.loading = false
        setTimeout(() => {
          this.dialogA.content = ''
        }, 300)
      },
      aceptaConfirmacion () {
        this.dialogA.loading = true
        this.guardar()
      },
      confirmaAuditoria () {
        this.dialogA.content = `¿Está seguro de <strong>Confirmar la auditoría para la factura ${this.factura.consecutivo}</strong>?`
        this.dialogA.visible = true
      },
      guardar () {
        this.loading = true
        this.axios.post(`facturas/${this.factura.id}`, {id: this.factura.id, estado: this.cuentaGlosas ? 'glosar' : 'avalar'})
          .then((response) => {
            this.$store.commit('reloadTable', 'tableFacturasRadicadas')
            this.$store.commit('SNACK_SHOW', {msg: `La factura ${this.factura.consecutivo} se auditó correctamente.`, color: 'success'})
            // this.$store.commit('FACTURA_RADICADA_RELOAD_ITEM', {item: {}, estado: 'reload', key: this.parametros.key})
            this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.dialogA.loading = false
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al marcar la factura como auditada. ', error: e})
          })
      },
      cancelaConfirmacionB () {
        this.dialogB.visible = false
        this.dialogB.loading = false
        setTimeout(() => {
          this.dialogB.content = ''
          this.dialogB.props = null
        }, 300)
      },
      aceptaConfirmacionB () {
        this.dialogB.loading = true
        this.borrarGlosaFactura(this.dialogB.props)
      },
      confirmaBorrado (props) {
        this.dialogB.content = `<strong>¿Está seguro de borrar la glosa de factura ${props.index + 1} de código ${props.item.glosa.glosa} por ${this.currency(props.item.valor_glosa)}?</strong>`
        this.dialogB.props = props
        this.dialogB.visible = true
      },
      borrarGlosaFactura (props) {
        this.loading = true
        this.axios.delete(`facservicios/glosas/${props.item.id}`)
          .then(response => {
            this.loading = false
            this.$store.commit('SNACK_SHOW', {msg: `La glosa de factura fue borrada correctamente.`, color: 'success'})
            this.getRegistro(this.factura.id)
            this.cancelaConfirmacionB()
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al borrar la glosa de factura`, error: e})
          })
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get(`cm_facturas/${id}`)
          .then(response => {
            console.log('responseresponse', response)
            response.data.data.servicios.forEach((x, i) => {
              x.estado = x.avalado !== null ? x.avalado ? 'Avalado' : 'Glosado' : 'Sin revisar'
              x.flag = 'registro'
              x.indexItem = i + 1
              x.valor_total = (x.cantidad * x.valor_unitario) - (x.copago || 0)
              x.valor_glosado = 0
              x.altos_costos = x.altos_costos.length ? x.altos_costos.map(x => x.alto_costo) : []
            })
            this.facturaOriginal = {...response.data.data}
            this.factura = {...response.data.data}
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer la data de la factura. ', error: e})
          })
      },
      getGlosas () {
        this.axios.get('manglosas')
          .then(response => {
            this.glosas_registro = response.data.data.glosas.filter(x => x.glosa < 900)
            this.glosas_factura = response.data.data.glosas.filter(x => x.glosa >= 800 && x.glosa < 900)
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las opciones de glosas. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
