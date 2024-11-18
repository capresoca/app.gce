<template>
  <div>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">1. Información general</v-system-bar>
      <v-flex xs12 sm6 md4 class="pt-1">
        <input-date
          v-model="value.fecha"
          label="Fecha (Año-Mes-Día)"
          format="YYYY-MM-DD"
          name="Fecha"
          :min="minDate"
          :max="maxDate"
          v-validate="requerido + 'fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
          :error-messages="errors.collect('Fecha')"
        ></input-date>
      </v-flex>
      <v-flex xs12 sm6 md4 v-if="proceso">
        <v-select
          label="Modalidad"
          v-model="value.ce_tipocontrato_id"
          :items="complementos && complementos.tiposContrato ? complementos.tiposContrato.filter(x => x.servicios_salud === proceso.servicios_salud) : []"
          item-value="id"
          item-text="nombre"
          name="modalidad"
          v-validate="'required'"
          :error-messages="errors.collect('modalidad')"
        >
        </v-select>
      </v-flex>
      <input-detail-flex
        flex-class="xs12"
        label="Objeto a contratar"
        :text="proceso.objeto"
      ></input-detail-flex>
      <v-flex xs12>
        <v-card>
          <v-card-title class="pb-2">
            <span class="title">Imputación presupuestal</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="py-0">
            <v-autocomplete
              label="Seleccionar rubros"
              v-model="rubroSeleccionado"
              :items="rubros"
              item-value="id"
              return-object
              :filter="filterRubros"
            >
              <template slot="selection" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                      <v-list-tile-sub-title class=caption>Disponible: {{data.item.valor_disponible }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </div>
              </template>
              <template slot="item" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.rubro.nombre}} <span v-if="data.item.rubro.regimen" class="float-right"> (Régimen {{data.item.rubro.regimen}})</span></v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                      <v-list-tile-sub-title class=caption>Disponible: {{'$'}}{{data.item.valor_disponible | numberFormat(2) }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </div>
              </template>
            </v-autocomplete>
          </v-card-text>
          <v-card-text class="pa-0">
            <v-data-table
              :items="value.imputacion_presupuestal"
              class="elevation-0"
              hide-actions
              :headers="headersImputaciones"
              no-data-text="No hay imputaciones presupuestales relacionadas"
            >
              <template slot="items" slot-scope="props">
                <td>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>{{ props.item.strgasto.rubro.nombre }} <span v-if="props.item.strgasto.rubro.regimen"> (Régimen {{ props.item.strgasto.rubro.regimen }})</span></v-list-tile-title>
                      <v-list-tile-sub-title>{{ props.item.strgasto.rubro.codigo }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </td>
                <td class="text-xs-right">{{'$'}}{{ props.item.strgasto.valor_disponible | numberFormat(2) }}</td>
                <td class="text-xs-right">
                  <input-number
                    :key="'input' + props.index"
                    class="ma-0 py-1"
                    :name="`Valor${props.item.pr_strgasto_id}`"
                    prepend-icon="attach_money"
                    v-model.number="props.item.valor"
                    data-vv-as="Valor"
                    v-validate="`required|min_value:0`"
                    :error-messages="errors.collect(`Valor${props.item.pr_strgasto_id}`)"
                  ></input-number>
                </td>
                <td class="text-xs-center" width="20px">
                  <v-tooltip top>
                    <v-btn flat icon slot="activator" color="danger" @click.stop="value.imputacion_presupuestal.splice(props.index, 1)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Quitar registro</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs12>
        <span class="caption">Productos a entregar</span>
        <div>
          <ckeditor-textarea
            v-model="value.productos_entregar"
            name="productos a entregar"
            v-validate="requerido"
            :error-messages="errors.collect('productos a entregar')"
          ></ckeditor-textarea>
        </div>
      </v-flex>
    </v-layout>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">2. Descripción de la necesidad</v-system-bar>
      <v-flex xs12 class="pf-0">
        <ckeditor-textarea
          v-model="value.desc_necesidad"
          name="descripción de la necesidad"
          v-validate="requerido"
          :error-messages="errors.collect('descripción de la necesidad')"
        ></ckeditor-textarea>
      </v-flex>
    </v-layout>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">3. Definición técnica</v-system-bar>
      <v-flex xs12 class="pt-0">
        <span class="caption">Descripción</span>
        <ckeditor-textarea
          v-model="value.desc_tecnica"
          name="definición técnica"
          v-validate="requerido"
          :error-messages="errors.collect('definición técnica')"
        ></ckeditor-textarea>
      </v-flex>
      <v-flex xs12>
        <span class="caption">Actividades a desarrollar para el logro del objeto o producto a contratar</span>
        <v-card>
          <v-card-title class="pb-0">
            <span class="title">Actividades</span>
            <v-tooltip top v-if="!value.actividades.length">
              <v-btn flat icon slot="activator" color="accent" @click.stop="nuevoRegistroActividad">
                <v-icon>add</v-icon>
              </v-btn>
              <span>Crear actividad</span>
            </v-tooltip>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="pa-0">
            <v-data-table
              :items="value.actividades"
              class="elevation-0"
              hide-actions
              :headers="headersActividades"
              no-data-text="No hay actividades registradas"
            >
              <template slot="items" slot-scope="props">
                <td class="text-xs-center pa-0" width="20px">
                  <v-avatar color="purple" size="40" class="white--text">{{props.index + 1}}</v-avatar>
                </td>
                <td class="pa-0">
                  <ckeditor-textarea
                    :key="'actividad' + props.index"
                    v-model="props.item.actividad"
                    :name="`Actividad${props.index}`"
                    data-vv-as="Actividad"
                    v-validate="'required'"
                    :error-messages="errors.collect(`Actividad${props.index}`)"
                  ></ckeditor-textarea>
                </td>
                <td class="text-xs-center pa-0" width="40px">
                  <v-tooltip top>
                    <v-btn flat icon slot="activator" color="danger" @click.stop="value.actividades.splice(props.index, 1)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Quitar registro</span>
                  </v-tooltip>
                  <v-btn flat slot="activator" color="success" @click.stop="nuevoRegistroActividad" v-if="(props.index + 1) === value.actividades.length">
                    Nuevo registro
                  </v-btn>
                </td>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">4. Soporte económico</v-system-bar>
      <v-flex xs12 class="pt-0">
        <span class="caption">Soporte económico</span>
        <ckeditor-textarea
          v-model="value.sop_economico"
          name="soporte económico"
          v-validate="requerido"
          :error-messages="errors.collect('soporte económico')"
        ></ckeditor-textarea>
      </v-flex>
      <v-flex xs12>
        <span class="caption">Especificaciones técnicas del objeto o producto a contratar</span>
        <ckeditor-textarea
          v-model="value.esp_tecnicas"
          name="especificaciones técnicas"
          v-validate="requerido"
          :error-messages="errors.collect('especificaciones técnicas')"
        ></ckeditor-textarea>
      </v-flex>
      <v-flex xs12>
        <span class="caption">Identificación de alternativas de ejecución</span>
        <ckeditor-textarea
          v-model="value.alt_ejecucion"
          name="alternativas de ejecución"
          v-validate="requerido"
          :error-messages="errors.collect('alternativas de ejecución')"
        ></ckeditor-textarea>
      </v-flex>
    </v-layout>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">5. Riesgos de la contratación</v-system-bar>
      <v-flex xs12 class="pt-0">
        <span class="caption">Descripción de los posibles riesgos</span>
        <ckeditor-textarea
          v-model="value.pos_riesgos"
          name="descripción de los posibles riesgos"
          v-validate="requerido"
          :error-messages="errors.collect('descripción de los posibles riesgos')"
        ></ckeditor-textarea>
      </v-flex>
      <v-flex xs12>
        <v-card>
          <v-card-title class="pb-2 blue-grey lighten-5">
            <span class="title">Garantias</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="py-0">
            <v-autocomplete
              label="Seleccionar garantía"
              v-model="garantiaSeleccionada"
              :items="complementos.garantias"
              item-value="id"
              item-text="nombre"
              return-object
              :filter="filterGarantias"
            >
              <template slot="selection" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title>{{data.item.descripcion}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </div>
              </template>
              <template slot="item" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title>{{data.item.descripcion}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </div>
              </template>
            </v-autocomplete>
          </v-card-text>
          <v-card-text :class="!value.garantias.length ? 'pt-0' : 'pa-0'">
            <v-flex xs12>
              <div class="grey--text text-xs-center title" v-if="!value.garantias.length">No hay garantías registradas.</div>
              <v-data-table
                v-else
                :items="value.garantias"
                class="elevation-0"
                hide-actions
              >
                <template slot="headers">
                  <th>Tipo</th>
                  <th>Descripción</th>
                  <th></th>
                </template>
                <template slot="items" slot-scope="props">
                  <td>
                    {{props.item.garantia.codigo}} - {{props.item.garantia.nombre}}
                    <v-tooltip top v-if="props.item.garantia.descripcion">
                      <v-btn flat icon slot="activator" color="info">
                        <v-icon>info</v-icon>
                      </v-btn>
                      <span>{{props.item.garantia.descripcion}}</span>
                    </v-tooltip>
                  </td>
                  <td width="50%">
                    <v-textarea
                      :key="'descripciongt' + props.index"
                      class="ma-0 py-1"
                      single-line
                      label="Descripción"
                      v-model="props.item.descripcion"
                      rows="1"
                      auto-grow
                    ></v-textarea>
                  </td>
                  <td class="text-xs-center" width="20px">
                    <v-tooltip top>
                      <v-btn flat icon slot="activator" color="danger" @click.stop="value.garantias.splice(props.index, 1)">
                        <v-icon>delete</v-icon>
                      </v-btn>
                      <span>Quitar registro</span>
                    </v-tooltip>
                  </td>
                </template>
              </v-data-table>
              <span class="caption">Descripción</span>
              <ckeditor-textarea
                v-model="value.descripgarantias"
                name="descripción"
                v-validate="requerido"
                :error-messages="errors.collect('descripción')"
              ></ckeditor-textarea>
            </v-flex>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    <v-layout row wrap class="mb-3">
      <v-system-bar window dark class="elevation-1" style="width: 100% !important;">6. Condiciones del objeto contractual</v-system-bar>
      <v-flex xs12 sm6 md4>
        <v-select
          label="Tipo contratación"
          v-model="value.ce_tipcontratacione_id"
          :items="complementos.tiposContratacion"
          item-value="id"
          item-text="nombre"
          name="Tipo contratación"
          v-validate="requerido"
          :error-messages="errors.collect('Tipo contratación')"
        >
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.nombre"/>
                <v-list-tile-sub-title v-html="'Código:' + data.item.codigo"/>
              </v-list-tile-content>
            </template>
          </template>
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo en meses"
          name="Plazo en meses"
          v-validate="requerido + 'min_value:0'"
          min="0"
          type="number"
          v-model.number="value.plazo_meses"
          :error-messages="errors.collect('Plazo en meses')"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo en días"
          name="Plazo en días"
          v-validate="requerido + 'min_value:0|max_value:30'"
          min="0"
          max="30"
          type="number"
          v-model.number="value.plazo_dias"
          :error-messages="errors.collect('Plazo en días')"
        />
      </v-flex>
      <v-flex xs12>
        <span class="caption">Descripción del plazo</span>
        <ckeditor-textarea
          v-model="value.desc_plazo"
          name="descripción del plazo"
          v-validate=""
          :error-messages="errors.collect('descripción del plazo')"
        ></ckeditor-textarea>
      </v-flex>
      <input-detail-flex
        flex-class="xs12"
        label="Objeto a contratar"
        :text="proceso.objeto"
      ></input-detail-flex>
      <v-flex xs12>
        <span class="caption">Lugar de ejecución</span>
        <ckeditor-textarea
          v-model="value.lugar_ejecucion"
          name="lugar de ejecución"
          v-validate="requerido"
          :error-messages="errors.collect('lugar de ejecución')"
        ></ckeditor-textarea>
      </v-flex>
      <v-flex xs12>
        <v-card>
          <v-card-title class="pb-0">
            <span class="title">Formas de pago</span>
            <v-tooltip top>
              <v-btn flat icon slot="activator" color="accent" @click.stop="nuevoRegistroFP">
                <v-icon>add</v-icon>
              </v-btn>
              <span>Crear forma de pago</span>
            </v-tooltip>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="pa-0">
            <v-data-table
              :items="value.forpagos"
              class="elevation-0"
              hide-actions
              :headers="headersFormasPago"
              no-data-text="No hay formas de pago relacionadas"
            >
              <template slot="items" slot-scope="props">
                <td width="150px">
                  <v-select
                    :key="'tipo' + props.index"
                    class="ma-0 py-1"
                    single-line
                    label="Tipo"
                    v-model="props.item.tipo"
                    :items="complementos.tiposFormaPago"
                    :name="`Tipo${props.index}`"
                    data-vv-as="Tipo"
                    v-validate="'required'"
                    :error-messages="errors.collect(`Tipo${props.index}`)"
                    @change="() => {
                props.item.valor = null
                let indexFechca = $validator.errors.items.findIndex(x => x.field === `Fechapf${props.index}`)
                $validator.errors.items.splice((indexFechca !== -1) ? indexFechca : 0, (indexFechca !== -1) ? 1 : 0)
                let indexValor = $validator.errors.items.findIndex(x => x.field === `Valorfp${props.index}`)
                $validator.errors.items.splice((indexValor !== -1) ? indexValor : 0, (indexValor !== -1) ? 1 : 0)
                }"
                  ></v-select>
                </td>
                <td>
                  <template v-if="props.item.tipo">
                    <v-text-field
                      v-if="props.item.tipo === 'Porcentaje'"
                      :key="'porcentajefp' + props.index"
                      class="ma-0 py-1"
                      prefix="%"
                      v-model.number="props.item.valor"
                      type="number"
                      min="0"
                      :name="`Porcentajefp${props.index}`"
                      data-vv-as="Porcentaje"
                      v-validate="`min_value:0`"
                      :error-messages="errors.collect(`Porcentajefp${props.index}`)"
                    ></v-text-field>
                    <input-number
                      v-else
                      :key="'valorfp' + props.index"
                      class="ma-0 py-1"
                      :name="`Valorfp${props.index}`"
                      prepend-icon="attach_money"
                      v-model.number="props.item.valor"
                      data-vv-as="Valor"
                      v-validate="`min_value:0`"
                      :error-messages="errors.collect(`Valorfp${props.index}`)"
                    ></input-number>
                  </template>
                </td>
                <td width="50%">
                  <v-textarea
                    :key="'descripcionfp' + props.index"
                    class="ma-0 py-1"
                    single-line
                    label="Descripción"
                    v-model="props.item.descripcion"
                    :name="`Descripción${props.index}`"
                    data-vv-as="Descripción"
                    rows="1"
                    auto-grow
                    v-validate="'required'"
                    :error-messages="errors.collect(`Descripción${props.index}`)"
                  ></v-textarea>
                </td>
                <td class="text-xs-center" width="20px">
                  <v-tooltip top>
                    <v-btn flat icon slot="activator" color="danger" @click.stop="value.forpagos.splice(props.index, 1)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Quitar registro</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs12 v-if="proceso.servicios_salud">
        <v-card>
          <v-card-title>
            <span class="title">Tarifas</span>
          </v-card-title>
          <v-card-text>
            <v-card v-if="value && value.ce_tipocontrato_id && (value.ce_tipocontrato_id === 1 || value.ce_tipocontrato_id === 9 || value.ce_tipocontrato_id === 2)">
              <v-card-title class="pb-2 blue-grey lighten-5">
                <span class="title">Cobertura geográfica</span>
              </v-card-title>
              <v-divider></v-divider>
              <v-card-text class="py-0">
                <v-autocomplete
                  label="Seleccionar municipio"
                  v-model="municipioGeoSeleccionado"
                  :items="complementos.municipios"
                  item-value="id"
                  item-text="nombre"
                  return-object
                >
                </v-autocomplete>
              </v-card-text>
              <v-card-text :class="!value.proconminutageos.length ? 'pt-0' : 'pa-0'">
                <v-flex xs12>
                  <v-data-table
                    :headers-length="value.ce_tipocontrato_id === 2 ? 2 : 5"
                    :items="value.proconminutageos"
                    class="elevation-0"
                    hide-actions
                    :expand="true"
                    item-key="gn_municipio_id"
                  >
                    <template v-slot:no-data>
                      <tr>
                        <td :colspan="value.ce_tipocontrato_id === 2 ? 2 : 5">
                          <div class="grey--text text-xs-center title">No hay registros de cobertura geográfica.</div>
                        </td>
                      </tr>
                    </template>
                    <template slot="headers">
                      <th class="pa-0" v-if="value.ce_tipocontrato_id !== 2"></th>
                      <th class="pa-1">Municipio</th>
                      <template v-if="value.ce_tipocontrato_id !== 2">
                        <th class="pa-1">% Subsidiado</th>
                        <th class="pa-1">
                          <v-tooltip top>
                            <span class="subrayado-dot" slot="activator">% Subsidiado P.</span>
                            <span>% Subsidiado Portabilidad</span>
                          </v-tooltip>
                        </th>
                        <th class="pa-1">
                          <v-tooltip top>
                            <span class="subrayado-dot" slot="activator">UPC Subsidiado</span>
                            <span>Valor UPC Subsidiado</span>
                          </v-tooltip>
                        </th>
                        <th class="pa-1">% Contributivo</th>
                        <th class="pa-1">
                          <v-tooltip top>
                            <span class="subrayado-dot" slot="activator">% Contributivo P.</span>
                            <span>% Contributivo Portabilidad</span>
                          </v-tooltip>
                        </th>
                        <th class="pa-1">
                          <v-tooltip top>
                            <span class="subrayado-dot" slot="activator">UPC Contributivo</span>
                            <span>Valor UPC Contributivo</span>
                          </v-tooltip>
                        </th>
                      </template>
                      <th class="pa-1"></th>
                    </template>
                    <template slot="items" slot-scope="props">
                      <tr>
                        <td class="text-xs-center pa-0"  v-if="value.ce_tipocontrato_id !== 2">
                          <v-tooltip top>
                            <v-btn flat icon slot="activator" :color="props.expanded ? 'grey' : 'primary'" @click.stop="props.expanded = !props.expanded">
                              <v-icon>fas fa-{{props.expanded ? 'angle-up' : 'angle-down'}}</v-icon>
                            </v-btn>
                            <span>{{props.expanded ? 'Ocultar' : 'Ver'}} servicios primarios</span>
                          </v-tooltip>
                        </td>
                        <td class="pa-1">
                          {{props.item.municipio.nombre}}
                        </td>
                        <template v-if="value.ce_tipocontrato_id !== 2">
                          <td class="pa-1">
                            <v-text-field
                              :key="'porcentajesub' + props.index"
                              class="ma-0 py-1"
                              prefix="%"
                              v-model.number="props.item.porcentaje_subsidiado"
                              type="number"
                              min="0"
                              max="100"
                              :name="`porcentajesub${props.index}`"
                              data-vv-as="Porcentaje"
                              v-validate="`required|min_value:0|max_value:100`"
                              :error-messages="errors.collect(`porcentajesub${props.index}`)"
                            ></v-text-field>
                          </td>
                          <td class="pa-1">
                            <v-text-field
                              :key="'porcentajesubportabilidad' + props.index"
                              class="ma-0 py-1"
                              prefix="%"
                              v-model.number="props.item.porcentaje_subsidiado_portabilidad"
                              type="number"
                              min="0"
                              max="100"
                              :name="`porcentajesubportabilidad${props.index}`"
                              data-vv-as="Porcentaje"
                              v-validate="`required|min_value:0|max_value:100`"
                              :error-messages="errors.collect(`porcentajesubportabilidad${props.index}`)"
                            ></v-text-field>
                          </td>
                          <td class="pa-1">
                            <input-number
                              :key="'valorupcsub' + props.index"
                              :precision="2"
                              :name="'valor upc subsidiado' + props.index"
                              prepend-icon="attach_money"
                              v-model.number="props.item.valor_upc_subsidiado"
                              v-validate="`required|min_value:0`"
                              data-vv-as="valor upc subsidiado"
                              :error-messages="errors.collect('valor upc subsidiado' + props.index)"
                            ></input-number>
                          </td>
                          <td class="pa-1">
                            <v-text-field
                              :key="'porcentajecont' + props.index"
                              class="ma-0 py-1"
                              prefix="%"
                              v-model.number="props.item.porcentaje_contributivo"
                              type="number"
                              min="0"
                              max="100"
                              :name="`porcentajecont${props.index}`"
                              data-vv-as="Porcentaje"
                              v-validate="`required|min_value:0|max_value:100`"
                              :error-messages="errors.collect(`porcentajecont${props.index}`)"
                            ></v-text-field>
                          </td>
                          <td class="pa-1">
                            <v-text-field
                              :key="'porcentajecontportabilidad' + props.index"
                              class="ma-0 py-1"
                              prefix="%"
                              v-model.number="props.item.porcentaje_contributivo_portabilidad"
                              type="number"
                              min="0"
                              max="100"
                              :name="`porcentajecontportabilidad${props.index}`"
                              data-vv-as="Porcentaje"
                              v-validate="`required|min_value:0|max_value:100`"
                              :error-messages="errors.collect(`porcentajecontportabilidad${props.index}`)"
                            ></v-text-field>
                          </td>
                          <td class="pa-1">
                            <input-number
                              :key="'valorupccont' + props.index"
                              :name="'valor upc contributivo' + props.index"
                              :precision="2"
                              prepend-icon="attach_money"
                              v-model.number="props.item.valor_upc_contributivo"
                              v-validate="`required|min_value:0`"
                              data-vv-as="valor upc contributivo"
                              :error-messages="errors.collect('valor upc contributivo' + props.index)"
                            ></input-number>
                          </td>
                        </template>
                        <td class="text-xs-center pa-1">
                          <v-tooltip top>
                            <v-btn flat icon slot="activator" color="danger" @click.stop="value.proconminutageos.splice(props.index, 1)">
                              <v-icon>delete</v-icon>
                            </v-btn>
                            <span>Quitar registro</span>
                          </v-tooltip>
                        </td>
                      </tr>
                    </template>
                    <template slot="expand" slot-scope="props" :value="true" v-if="value.ce_tipocontrato_id !== 2">
                      <v-card flat class="blue-grey">
                        <v-card-text>
                          <v-data-table
                            :items="props.item.upcservicios"
                            class="elevation-0"
                            hide-actions
                          >
                            <template v-slot:no-data>
                              <tr>
                                <td colspan="4">
                                  <div class="grey--text text-xs-center title">Fallo la carga de los servicios primarios.</div>
                                </td>
                              </tr>
                            </template>
                            <template slot="headers">
                              <th>Servicio</th>
                              <template v-if="value.ce_tipocontrato_id === 1">
                                <th>% Subsidiado</th>
                                <th>% Contributivo</th>
                                <th >Porcentaje</th>
                              </template>
                              <th v-if="value.ce_tipocontrato_id === 9">Valor</th>
                              <th></th>
                            </template>
                            <template slot="items" slot-scope="propsx">
                              <td>
                                {{propsx.item.servicio.nombre}}
                              </td>
                              <template v-if="value.ce_tipocontrato_id === 1">
                                <td>
                                  <v-text-field
                                    :key="'porcentajeservsub' + propsx.index"
                                    class="ma-0 py-1"
                                    prefix="%"
                                    v-model.number="propsx.item.porcent_pob_subsidiado"
                                    type="number"
                                    min="0"
                                    max="100"
                                    :name="`porcentajeservsub${propsx.index}`"
                                    data-vv-as="% Subsidiado"
                                    v-validate="'min_value:0|max_value:100|decimal:2'"
                                    :error-messages="errors.collect(`porcentajeservsub${propsx.index}`)"
                                  ></v-text-field>
                                </td>
                                <td>
                                  <v-text-field
                                    :key="'porcentajeservcont' + propsx.index"
                                    class="ma-0 py-1"
                                    prefix="%"
                                    v-model.number="propsx.item.porcent_pob_contributivo"
                                    type="number"
                                    min="0"
                                    max="100"
                                    :name="`porcentajeservcont${propsx.index}`"
                                    data-vv-as="% Contributivo"
                                    v-validate="'min_value:0|max_value:100|decimal:2'"
                                    :error-messages="errors.collect(`porcentajeservcont${propsx.index}`)"
                                  ></v-text-field>
                                </td>
                                <td>
                                  <v-text-field
                                    :key="'porcentajeserv' + propsx.index"
                                    class="ma-0 py-1"
                                    prefix="%"
                                    v-model.number="propsx.item.porcentaje"
                                    type="number"
                                    min="0"
                                    max="100"
                                    :name="`porcentajeserv${propsx.index}`"
                                    data-vv-as="Porcentaje"
                                    v-validate="'min_value:0|max_value:100|decimal:2'"
                                    :error-messages="errors.collect(`porcentajeserv${propsx.index}`)"
                                  ></v-text-field>
                                </td>
                              </template>
                              <td v-if="value.ce_tipocontrato_id === 9">
                                <v-text-field
                                  :key="'valorserv' + propsx.index"
                                  class="ma-0 py-1"
                                  prefix="$"
                                  v-model.number="propsx.item.valor"
                                  type="number"
                                  min="0"
                                  :name="`valorserv${propsx.index}`"
                                  data-vv-as="Valor"
                                  v-validate="`min_value:0`"
                                  :error-messages="errors.collect(`valorserv${propsx.index}`)"
                                ></v-text-field>
                              </td>
                              <td class="text-xs-center" width="20px">
                                <v-tooltip top>
                                  <v-btn flat icon slot="activator" color="danger" @click.stop="propsx.item.upcservicios.splice(propsx.index, 1)">
                                    <v-icon>delete</v-icon>
                                  </v-btn>
                                  <span>Quitar registro</span>
                                </v-tooltip>
                              </td>
                            </template>
                          </v-data-table>
                        </v-card-text>
                      </v-card>
                    </template>
                  </v-data-table>
                </v-flex>
              </v-card-text>
            </v-card>
            <span class="caption">Descripción</span>
            <ckeditor-textarea
              v-model="value.tarifas"
              name="descripciontarifas"
              data-vv-as="descripción"
              v-validate="requerido"
              :error-messages="errors.collect('descripciontarifas')"
            ></ckeditor-textarea>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs12>
        <v-textarea
          v-model="value.supervicion"
          auto-grow
          label="Supervisión"
          rows="1"
          name="supervisión"
          v-validate="requerido"
          :error-messages="errors.collect('supervisión')"
        ></v-textarea>
      </v-flex>
      <v-flex xs12>
        <v-autocomplete
          label="Seleccionar supervisor"
          v-model="value.supervisor_id"
          :items="supervisores"
          item-value="id"
          item-text="tercero"
          :filter="filterSupervisores"
        >
          <template slot="selection" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.tercero.nombre_completo}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.tercero.identificacion_completa}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
          <template slot="item" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.tercero.nombre_completo}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.tercero.identificacion_completa}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
        </v-autocomplete>
      </v-flex>
    <v-flex xs12>
        <v-autocomplete
          label="Proyecta"
          v-model="value.registro"
          :items="proyectan"
          item-value="id"
          name="proyecta"
          v-validate="requerido"
          :error-messages="errors.collect('proyecta')"
          :filter="filterFirmantes"
        >
          <template slot="selection" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
          <template slot="item" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
        </v-autocomplete>
      </v-flex>
    <v-flex xs12>
        <v-autocomplete
          label="Revisa"
          v-model="value.reviso"
          :items="revisan"
          item-value="id"
          name="revisa"
          v-validate="requerido"
          :error-messages="errors.collect('revisa')"
          :filter="filterFirmantes"
        >
          <template slot="selection" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
          <template slot="item" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
        </v-autocomplete>
      </v-flex>
    <v-flex xs12>
        <v-autocomplete
          label="Aprueba"
          v-model="value.confirmo"
          :items="aprueban"
          item-value="id"
          name="aprueba"
          v-validate="requerido"
          :error-messages="errors.collect('aprueba')"
          :filter="filterFirmantes"
        >
          <template slot="selection" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
          <template slot="item" slot-scope="data">
            <div style="width: 100% !important;">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-content>
                  <v-list-tile-title>{{data.item.name}}</v-list-tile-title>
                  <v-list-tile-sub-title class=caption>{{data.item.email}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </template>
        </v-autocomplete>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import InputDate from '@/components/general/InputDate'
  export default {
    name: 'FormEstudioPrevio',
    props: {
      value: {
        type: Object,
        default: null
      },
      proceso: {
        type: Object,
        default: null
      },
      rubros: {
        type: Array,
        default: []
      },
      supervisores: {
        type: Array,
        default: []
      },
      proyectan: {
        type: Array,
        default: []
      },
      revisan: {
        type: Array,
        default: []
      },
      aprueban: {
        type: Array,
        default: []
      }
    },
    components: {
      InputDate,
      InputNumber: () => import('@/components/general/InputNumber'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data () {
      return {
        requerido: '',
        headersImputaciones: [
          {
            text: 'Rubro',
            align: 'left',
            sortable: false
          },
          {
            text: 'Disponible',
            align: 'right',
            sortable: false
          },
          {
            text: 'Valor',
            align: 'center',
            sortable: false
          },
          {
            text: '',
            align: 'center',
            sortable: false
          }
        ],
        headersFormasPago: [
          {
            text: 'Tipo',
            align: 'left',
            sortable: false
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false
          },
          {
            text: '',
            align: 'center',
            sortable: false
          }
        ],
        headersActividades: [
          {
            text: 'Consecutivo',
            align: 'center',
            sortable: false
          },
          {
            text: 'Actividad',
            align: 'left',
            sortable: false
          },
          {
            text: '',
            align: 'center',
            sortable: false
          }
        ],
        rubroSeleccionado: null,
        garantiaSeleccionada: null,
        municipioGeoSeleccionado: null,
        minDate: '1900-01-01',
        maxDate: '1900-01-01',
        filterSupervisores: (item, queryText) => this.getFilter(item, queryText, item.tercero.identificacion_completa + ' ' + item.tercero.nombre_completo),
        filterFirmantes: (item, queryText) => this.getFilter(item, queryText, item.name + ' ' + item.email),
        filterRubros: (item, queryText) => this.getFilter(item, queryText, item.rubro.codigo_simple + ' ' + item.rubro.codigo + ' ' + item.rubro.nombre),
        filterGarantias: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre)
      }
    },
    watch: {
      'rubroSeleccionado' (val) {
        if (val) {
          if (!this.value.imputacion_presupuestal.length || (this.value.imputacion_presupuestal.length && !this.value.imputacion_presupuestal.find(x => x.pr_strgasto_id === val.id))) {
            this.value.imputacion_presupuestal.push({strgasto: val, pr_detstrgasto_id: val.id, valor: null})
          }
          setTimeout(() => {
            this.rubroSeleccionado = null
          }, 40)
        }
      },
      'garantiaSeleccionada' (val) {
        if (val) {
          if (!this.value.garantias.length || (this.value.garantias.length && !this.value.garantias.find(x => x.ce_garantia_id === val.id))) {
            this.value.garantias.push({garantia: val, ce_garantia_id: val.id, descripcion: null})
          }
          setTimeout(() => {
            this.garantiaSeleccionada = null
          }, 40)
        }
      },
      'value' (val) {
        if (val) this.reloadCoberturaGeografica()
      },
      'municipioGeoSeleccionado' (val) {
        if (val) {
          if (!this.value.proconminutageos.length || (this.value.proconminutageos.length && !this.value.proconminutageos.find(x => x.municipio.id === val.id))) {
            let serviciosMunicipio = []
            if (this.complementos && this.complementos.rsServicios) {
              this.complementos.rsServicios.forEach(x => {
                serviciosMunicipio.push(
                  {
                    id: null,
                    ce_proconminutageo_id: null,
                    rs_servicio_id: x.id,
                    servicio: x,
                    porcentaje: null,
                    porcent_pob_contributivo: null,
                    porcent_pob_subsidiado: null,
                    valor: null
                  }
                )
              })
            }
            this.value.proconminutageos.push(
              {
                id: null,
                municipio: val,
                gn_municipio_id: val.id,
                ce_proconestprevio_id: this.value.id,
                porcentaje_subsidiado: null,
                porcentaje_contributivo: null,
                porcentaje_subsidiado_portabilidad: null,
                porcentaje_contributivo_portabilidad: null,
                valor_upc_contributivo: null,
                valor_upc_subsidiado: null,
                upcservicios: serviciosMunicipio,
                upcedades: []
              }
            )
          }
          setTimeout(() => {
            this.municipioGeoSeleccionado = null
          }, 40)
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosProcesosContractualesFormEstudioPrevio
      }
    },
    created () {
      this.maxDate = this.moment().add(30, 'days').format('YYYY-MM-DD')
      this.reloadCoberturaGeografica()
    },
    methods: {
      reloadCoberturaGeografica () {
        if (this.value && this.complementos && this.complementos.rsServicios) {
          this.value.proconminutageos.forEach(z => {
            let serviciosPrimarios = JSON.parse(JSON.stringify(this.complementos.rsServicios))
            let nuevosServiciosMunicipio = []
            serviciosPrimarios.forEach(y => {
              if (z.upcservicios.find(x => x.rs_servicio_id === y.id)) {
                nuevosServiciosMunicipio.push(z.upcservicios.find(x => x.rs_servicio_id === y.id))
              } else {
                nuevosServiciosMunicipio.push(
                  {
                    ce_proconminutageo_id: null,
                    rs_servicio_id: y.id,
                    servicio: y,
                    porcentaje: null,
                    porcent_pob_contributivo: null,
                    porcent_pob_subsidiado: null,
                    valor: null
                  }
                )
              }
            })
            z.upcservicios = nuevosServiciosMunicipio
          })
        }
      },
      nuevoRegistroActividad () {
        this.value.actividades.push({actividad: ''})
      },
      nuevoRegistroFP () {
        this.value.forpagos.push({id: null, tipo: null, valor: null, descripcion: null})
      },
      reset () {
        this.$validator.reset()
      },
      async assignValidation (validation) {
        this.requerido = validation
        await this.$validator.reset()
        return true
      },
      async validate () {
        let validateForm = await this.$validator.validateAll()
        if (this.requerido) {
          let validateInputacion = this.value.imputacion_presupuestal.length > 0
          let validateDistribucionGeo = 0
          let ceproconminutageos = JSON.parse(JSON.stringify(this.value.proconminutageos))
          ceproconminutageos.forEach(x => {
            x.upcservicios = x.upcservicios.filter(z => z.porcentaje !== null || z.valor !== null)
            if (x.upcservicios.length === 0 && (this.value.ce_tipocontrato_id === 9 || this.value.ce_tipocontrato_id === 1)) validateDistribucionGeo++
          })
          if (!validateForm) {
            return {validate: validateForm, error: 'Hay datos por diligenciar en el formulario.'}
          } else if (!validateInputacion) {
            return {validate: validateInputacion, error: 'No hay registros en la inputación presupuestal.'}
          } else if (validateDistribucionGeo > 0) {
            return {validate: validateDistribucionGeo === 0, error: `Hay ${validateDistribucionGeo} registro${validateDistribucionGeo === 1 ? '' : 's'} de cobertura geográfica por resolver.`}
          } else {
            return {validate: true, error: ''}
          }
        } else {
          if (!validateForm) {
            return {validate: validateForm, error: 'Hay datos por diligenciar en el formulario.'}
          } else {
            return {validate: true, error: ''}
          }
        }
      },
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }
  }
</script>

<style scoped>
</style>
