<template>
  <div>
    <registro-direccionamientos
      :parent="RegistroDireccionamiento.parent"
      :item="RegistroDireccionamiento.item"
      :type="RegistroDireccionamiento.type"
      v-model="RegistroDireccionamiento.show"
    />
    <v-expansion-panel class="v-expansion-panel-pi">
      <v-expansion-panel-content
        class="v-expansion-header-dark"
        v-for="(itemListo, index) in itemsListos"
        :key="`itemListo_${index}`"
      >
        <div dense slot="header">
          <v-list-tile class="text-truncate">
            <v-list-tile-avatar color="primary" class="white--text">
              {{itemListo.consecutivo}}
            </v-list-tile-avatar>
            <v-list-tile-content class="truncate-content">
              <v-list-tile-title v-html="itemListo.title"></v-list-tile-title>
              <v-list-tile-sub-title>{{itemListo.subtitle}}</v-list-tile-sub-title>
            </v-list-tile-content>
            <v-list-tile-action>
              <v-btn color="success" small @click.stop="showDireccionamientos(itemListo)" class="px-2" v-if="item.NoPrescripcion ? (itemListo.EstJM && (itemListo.EstJM === 1 || itemListo.EstJM === 3)) : true">
                <v-icon class="mr-1">style</v-icon>
                Direccionamientos
              </v-btn>
              <v-tooltip top v-else>
                <v-btn slot="activator" @click.stop="" color="error" small class="px-2">
                  <v-icon class="mr-1">warning</v-icon>
                  Direccionamientos
                </v-btn>
                <span>{{itemListo.EstJMText}}</span>
              </v-tooltip>
            </v-list-tile-action>
          </v-list-tile>
        </div>
        <v-card>
          <v-card-text>
            <v-container
              fluid
              grid-list-lg
              class="pa-0 ma-0"
            >
              <v-layout row wrap>
                <v-flex xs12 sm12 md6 lg4 v-for="(iterator, iiter) in itemListo.principios_activos" :key="'iterator' + iiter">
                  <v-expansion-panel class="elevation-0">
                    <v-expansion-panel-content>
                      <div slot="header">
                        <h4>Principio Activo No. {{ iiter + 1 }}</h4>
                      </div>
                      <v-divider/>
                      <v-card>
                        <v-list dense>
                          <v-list-tile>
                            <v-list-tile-content class="width-70">Código PA en DCI:</v-list-tile-content>
                            <v-list-tile-content class="flex-align-end">{{ iterator.CodPriAct }}</v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content class="width-70">Concentración Cantidad:</v-list-tile-content>
                            <v-list-tile-content class="flex-align-end">{{ iterator.ConcCant }}</v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content class="width-70">Unidad de Medida Concentración:</v-list-tile-content>
                            <v-list-tile-content class="flex-align-end">{{ iterator.UMedConc }}</v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content class="width-70">Cantidad medicamento en PA:</v-list-tile-content>
                            <v-list-tile-content class="flex-align-end">{{ iterator.CantCont }}</v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content class="width-70">Unidad de Medida del medicamento en que esta contenido el Principio Activo:</v-list-tile-content>
                            <v-list-tile-content class="flex-align-end">{{ iterator.UMedCantCont }}</v-list-tile-content>
                          </v-list-tile>
                        </v-list>
                      </v-card>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                  <v-divider/>
                </v-flex>
                <input-detail-flex
                  v-for="(prop, i) in itemListo.propsTop"
                  :key="`prop${index}_${i}`"
                  v-if="prop.text !== null"
                  :flex-class="prop.flexClass"
                  :label="prop.label"
                  :text="prop.text === 0 ? 'NO' : prop.text === 1 ? 'SI' : prop.text"
                  :hint="prop.hint"
                  :prepend-icon="prop.icon"
                />
                <v-flex xs12 class="pa-0 ma-0">
                  <v-data-table
                    :items="itemListo.propsTable"
                    class="elevation-0"
                    hide-actions
                    hide-headers
                  >
                    <template slot="items" slot-scope="props">
                      <td class="text-xs-justify" :class="props.item.classTdItem" v-html="props.item.label"></td>
                      <td class="text-xs-justify" v-html="props.item.text === 0 ? 'NO' : props.item.text === 1 ? 'SI' : props.item.text"></td>
                    </template>
                  </v-data-table>
                  <v-divider/>
                </v-flex>
                <input-detail-flex
                  v-for="(prop, inx) in itemListo.propsBottom"
                  :key="`propB${index}_${inx}`"
                  v-if="prop.text !== null"
                  :flex-class="prop.flexClass"
                  :label="prop.label"
                  :text="prop.text === 0 ? 'NO' : prop.text === 1 ? 'SI' : prop.text"
                  :hint="prop.hint"
                  :prepend-icon="prop.icon"
                />
                <v-flex xs12 sm6 md4 lg3 v-for="(indicacion, iindi) in item.indicaciones_unirs" :key="'indication' + iindi">
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="'Indicación UNIRS No. ' + indicacion.ConOrden"></v-list-tile-title>
                      <v-list-tile-sub-title v-html="'Código: ' + indicacion.CodIndicacion"></v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </div>
</template>

<script>
    export default {
      name: 'Medicamentos',
      props: ['item', 'items'],
      components: {
        InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
        RegistroDireccionamientos: () => import('@/components/mipres/direccionamientos/RegistroDireccionamientos')
      },
      data: () => ({
        RegistroDireccionamiento: {
          parent: null,
          item: null,
          type: 'medicamento',
          show: false
        },
        itemsListos: []
      }),
      created () {
        this.items.forEach(x => {
          this.itemsListos.push({
            showEntregas: false,
            title: x.DescMedPrinAct,
            subtitle: (x.tipo_medicamento ? 'Tipo: ' + x.tipo_medicamento + ' - ' : '') + '' + (x.CodFF ? 'Forma Farmacéutica: ' + x.forma_farmaceutica.codigo + ' - ' + x.forma_farmaceutica.descripcion : ''),
            consecutivo: x.ConOrden,
            id: x.id,
            TipoTec: 'M',
            TipoMed: x.TipoMed,
            cantidadTotal: x.CantTotalF,
            EstJM: x.EstJM,
            EstJMText: x.EstadoJunta,
            direccionamientos: x.direccionamientos,
            datosBasicos: [
              {label: 'Descripción', text: x.DescMedPrinAct, hint: null, icon: null, flexClass: null},
              {label: 'Dosis', text: x.Dosis + (x.DosisUM ? ' ' + x.unidad_medida_dosis.descripcion : ''), hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Administración', text: x.NoFAdmon ? x.NoFAdmon + (x.FrecuenciaAdministracion ? ' ' + x.FrecuenciaAdministracion : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.CanTrat ? x.CanTrat + (x.DuracionTratamiento ? ' ' + x.DuracionTratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total Formulada', text: x.CantTotalF !== null ? x.CantTotalF + (x.presentacion ? ' ' + x.presentacion.descripcion : '') : '', hint: null, icon: null, flexClass: null}
            ],
            principios_activos: x.principios_activos,
            indicaciones_unirs: x.indicaciones_unirs,
            propsTop: [
              {label: 'Descripción', text: x.DescMedPrinAct, hint: null, icon: null, flexClass: null},
              {label: 'Tipo de Prestación', text: x.tipo_prestador !== null ? x.tipo_prestador.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Forma Farmacéutica', text: (x.CodFF ? x.forma_farmaceutica.codigo + ' - ' + x.forma_farmaceutica.descripcion : ''), hint: null, icon: null, flexClass: null},
              {label: 'Vía de Administración', text: x.CodVA ? x.via_administracion.codigo + ' - ' + x.via_administracion.descripcion : '', hint: null, icon: null, flexClass: null},
              {label: 'Justificación No PBS', text: x.JustNoPBS, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Dosis', text: x.Dosis + (x.DosisUM ? ' ' + x.unidad_medida_dosis.descripcion : ''), hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Administración', text: x.NoFAdmon ? x.NoFAdmon + (x.FrecuenciaAdministracion ? ' ' + x.FrecuenciaAdministracion : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.CanTrat ? x.CanTrat + (x.DuracionTratamiento ? ' ' + x.DuracionTratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total Formulada', text: x.CantTotalF !== null ? x.CantTotalF + (x.presentacion ? ' ' + x.presentacion.descripcion : '') : '', hint: null, icon: null, flexClass: null},
              // {label: 'Unidad Farmacéutica Cantidad Total', text: x.UFCantTotal.toString(), hint: null, icon: null, flexClass: null},
              {label: 'Indicaciones Especiales', text: x.IndicacionesEspeciales ? x.IndicacionesEspeciales : '', hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Indicaciones o recomendaciones para el paciente', text: x.IndRec, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Estado de la Junta de Profesionales', text: x.EstadoJunta, hint: null, icon: null, flexClass: null}
            ],
            propsTable: [
              {label: 'Causa Solicitud 1 ¿El medicamento está financiado por el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS1, hint: '', icon: null, classTdItem: ''},
              {label: 'Causa Solicitud 2 ¿El medicamento se encuentra en fase experimental?', text: x.CausaS2, hint: '', icon: null},
              {label: 'Causa Solicitud 3 ¿Ya utilizó los medicamentos existentes en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS3, hint: '', icon: null, classTdItem: ''},
              {label: 'Medicamento PBS Utilizado', text: x.MedPBSUtilizado, hint: '', icon: null, classTdItem: ''},
              {label: 'Razón Causa Solicitud 3.1 - Resultados clínicos no satisfactorios ¿Lo utilizó y no se obtuvieron resultados clínicos o paraclínicos satisfactorios en el término previsto de sus indicaciones?', text: (x.RznCausaS31 === 1 ? 'SI' : x.RznCausaS31 === 0 ? 'NO' : x.RznCausaS31) + (x.DescRzn31 ? ': ' + x.DescRzn31 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 3.2 - Reacciones adversas o intolerancia Lo utilizó y se observaron reacciones adversas o intolerancia por el paciente', text: (x.RznCausaS32 === 1 ? 'SI' : x.RznCausaS32 === 0 ? 'NO' : x.RznCausaS32) + (x.DescRzn32 ? ': ' + x.DescRzn32 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Causa Solicitud 4 ¿Ya descartó los medicamentos existentes en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS4, hint: '', icon: null, classTdItem: ''},
              {label: 'Medicamento PBS Descartado', text: x.MedPBSDescartado, hint: '', icon: null, classTdItem: ''},
              {label: 'Razón Causa Solicitud 4.1 - Reacciones adversas o intolerancia ¿Lo descartó porque se prevén reacciones adversas o intolerancia por el paciente?', text: (x.RznCausaS41 === 1 ? 'SI' : x.RznCausaS41 === 0 ? 'NO' : x.RznCausaS41) + (x.DescRzn41 ? ': ' + x.DescRzn41 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 4.2 - Indicaciones/Contraindicaciones ¿Lo descartó porque existen indicaciones o contraindicaciones expresas?', text: (x.RznCausaS42 === 1 ? 'SI' : x.RznCausaS42 === 0 ? 'NO' : x.RznCausaS42) + (x.DescRzn42 ? ': ' + x.DescRzn42 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 4.3 - No existe  alternativa ¿Lo descartó porque no existe otra alternativa en el PBS?', text: (x.RznCausaS43 === 1 ? 'SI' : x.RznCausaS43 === 0 ? 'NO' : x.RznCausaS43) + (x.DescRzn43 ? ': ' + x.DescRzn43 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 4.4 - Evidencia  científica   disponible ¿Lo descartó porque tiene mejor evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?', text: (x.RznCausaS44 === 1 ? 'SI' : x.RznCausaS44 === 0 ? 'NO' : x.RznCausaS44) + (x.DescRzn44 ? ': ' + x.DescRzn44 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Causa Solicitud 5 ¿La indicación o usoprevisto del medicamento está registrado/aprobado por el competente?', text: x.CausaS5, hint: '', icon: null, classTdItem: ''},
              {label: 'Razón Causa Solicitud 5 - ¿El medicamento aparece en la lista de uso No Indicado en el Registro Sanitario - UNIRS?', text: (x.RznCausaS5 === 1 ? 'SI' : x.RznCausaS5 === 0 ? 'NO' : x.RznCausaS5), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Causa Solicitud 6 ¿Existe evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?', text: x.CausaS6, hint: '', icon: null, classTdItem: ''}
            ],
            propsBottom: []
          })
        })
      },
      methods: {
        showDireccionamientos (medicamento) {
          this.RegistroDireccionamiento.parent = this.item
          this.RegistroDireccionamiento.item = JSON.parse(JSON.stringify(medicamento))
          this.RegistroDireccionamiento.show = true
        }
      }
    }
</script>

<style scoped>
  .width-70 {
    width: 70% !important;
    min-width: 50% !important;
  }
  .flex-align-end {
    align-items: flex-end !important;
  }
</style>
