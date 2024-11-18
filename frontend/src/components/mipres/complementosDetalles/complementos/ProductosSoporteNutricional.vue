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
        <div slot="header">
          <v-list-tile>
            <v-list-tile-avatar color="primary" class="white--text">
              {{itemListo.consecutivo}}
            </v-list-tile-avatar>
            <v-list-tile-content class="truncate-content">
              <v-list-tile-title v-html="itemListo.title "></v-list-tile-title>
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
                      <td class="text-xs-justify" v-html="props.item.text !== 'null' ? (props.item.text === 0 ? 'NO' : props.item.text === 1 ? 'SI' : props.item.text) : ''"></td>
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
      name: 'ProductosSoporteNutricional',
      props: ['item', 'items'],
      components: {
        InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
        RegistroDireccionamientos: () => import('@/components/mipres/direccionamientos/RegistroDireccionamientos')
      },
      data: () => ({
        RegistroDireccionamiento: {
          parent: null,
          item: null,
          type: 'producto nutricional',
          show: false
        },
        itemsListos: []
      }),
      created () {
        this.items.forEach(x => {
          this.itemsListos.push({
            title: x.producto ? (x.producto.codigo + ' - ' + x.producto.descripcion) : x.DescProdNutr ? x.DescProdNutr : 'No registra.',
            subtitle: 'Tipo presentación: ' + (x.tipo_prestador ? x.tipo_prestador : 'No registra.'),
            consecutivo: x.ConOrden,
            id: x.id,
            TipoTec: 'N',
            TipoMed: null,
            cantidadTotal: x.CantTotalF,
            EstJM: x.EstJM,
            EstJMText: x.EstadoJunta,
            direccionamientos: x.direccionamientos,
            datosBasicos: [
              {label: 'Descripción', text: x.producto ? (x.producto.codigo + ' - ' + x.producto.descripcion) : x.DescProdNutr ? x.DescProdNutr : 'No registra.', hint: null, icon: null, flexClass: null},
              {label: 'Dosis Número', text: x.Dosis !== null ? x.Dosis.toString() + (x.DosisUM ? ' ' + x.unidad_medida_dosis.descripcion : '') : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Administración', text: x.NoFAdmon ? x.NoFAdmon + (x.frecuencia_administracion ? ' ' + x.frecuencia_administracion : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.CanTrat ? x.CanTrat + (x.duracion_tratamiento ? ' ' + x.duracion_tratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total Formulada', text: x.CantTotalF ? (x.CantTotalF + (x.forma_cantidad_total ? ' ' + x.forma_cantidad_total.descripcion : '')) : '', hint: null, icon: null, flexClass: null}
            ],
            propsTop: [
              {label: 'Tipo Producto Nutricional', text: x.tipo ? (x.tipo.codigo + ' - ' + x.tipo.descripcion) : x.TippProNut ? x.TippProNut : '', hint: null, icon: null, flexClass: null},
              {label: 'Código Forma', text: x.CodForma ? (x.forma ? x.forma.codigo + ' - ' + x.forma.descripcion : '') : '', hint: null, icon: null, flexClass: null},
              {label: 'Código Vía de Administración', text: x.via_administracion, hint: null, icon: null, flexClass: null},
              {label: 'Justificación No PBS', text: x.JustNoPBS, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Dosis Número', text: x.Dosis !== null ? x.Dosis.toString() + (x.DosisUM ? ' ' + x.unidad_medida_dosis.descripcion : '') : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Administración', text: x.NoFAdmon ? x.NoFAdmon + (x.frecuencia_administracion ? ' ' + x.frecuencia_administracion : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.CanTrat ? x.CanTrat + (x.duracion_tratamiento ? ' ' + x.duracion_tratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total Formulada', text: x.CantTotalF ? (x.CantTotalF + (x.forma_cantidad_total ? ' ' + x.forma_cantidad_total.descripcion : '')) : '', hint: null, icon: null, flexClass: null},
              // {label: 'Presentación Cantidad Total', text: x.UFCantTotal.toString(), hint: null, icon: null, flexClass: null},
              {label: 'Indicaciones Especiales', text: x.indicaciones_especiales, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Indicaciones o recomendaciones para el paciente', text: x.IndRec, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Estado de la Junta de Profesionales', text: x.EstadoJunta, hint: null, icon: null, flexClass: null}
            ],
            propsTable: [
              {label: 'Código Causa Solicitud 1 ¿El Producto Nutricional se encuentra en fase experimental?', text: x.CausaS1, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 2 ¿El Producto de Soporte Nutricional está registrado por el INVIMA?', text: x.CausaS2, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 3 ¿El Producto Nutricional está cubierto por el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS3, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 4 ¿Ya utilizó el producto nutricional o medicamento existente en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS4, hint: '', icon: null, classTdItem: ''},
              {label: 'Producto nutricional utilizado', text: x.ProNutUtilizado, hint: '', icon: null, classTdItem: ''},
              {label: 'Razón Causa Solicitud 41 ¿Lo utilizó y no se obtuvieron resultados clínicos o paraclínicos satisfactorios en el término previsto de sus indicaciones?', text: (x.RznCausaS41 === 1 ? 'SI' : x.RznCausaS41 === 0 ? 'NO' : x.RznCausaS41) + (x.DescRzn41 ? ': ' + x.DescRzn41 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 42 ¿Lo utilizó y se observaron reacciones adversas o intolerancia por el paciente?', text: (x.RznCausaS42 === 1 ? 'SI' : x.RznCausaS42 === 0 ? 'NO' : x.RznCausaS42) + (x.DescRzn42 ? ': ' + x.DescRzn42 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Código Causa Solicitud 5 ¿Ya descartó el Producto Nutricional existente en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS5, hint: '', icon: null, classTdItem: ''},
              {label: 'Descripción Producto nutricional descartado', text: x.ProNutDescartado, hint: '', icon: null, classTdItem: ''},
              {label: 'Razón Causa Solicitud 51 ¿Lo descartó porque se prevén reacciones adversas o intolerancia por el paciente?', text: (x.RznCausaS51 === 1 ? 'SI' : x.RznCausaS51 === 0 ? 'NO' : x.RznCausaS51) + (x.DescRzn51 ? ': ' + x.DescRzn51 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 52 ¿Lo descartó porque existen indicaciones o contraindicaciones expresas?', text: (x.RznCausaS52 === 1 ? 'SI' : x.RznCausaS52 === 0 ? 'NO' : x.RznCausaS52) + (x.DescRzn52 ? ': ' + x.DescRzn52 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 53 ¿Lo descartó porque no existe otra alternativa en el PBS?', text: (x.RznCausaS53 === 1 ? 'SI' : x.RznCausaS53 === 0 ? 'NO' : x.RznCausaS53) + (x.DescRzn53 ? ': ' + x.DescRzn53 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Razón Causa Solicitud 54 ¿Lo descartó porque tiene mejor evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?', text: (x.RznCausaS54 === 1 ? 'SI' : x.RznCausaS54 === 0 ? 'NO' : x.RznCausaS54) + (x.DescRzn54 ? ': ' + x.DescRzn54 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Diagnóstico de Enfermedad Huérfana, Enfermedad rara, Ultra-Huérfana y Olvidada', text: x.DxEnfermedadHuerfana, hint: '', icon: null, classTdItem: ''},
              {label: 'Diagnóstico de VIH', text: x.DxVIH, hint: '', icon: null, classTdItem: ''},
              {label: 'Diagnóstico de Cáncer en cuidado paliativo', text: x.DxCancerPaliativo, hint: '', icon: null, classTdItem: ''},
              {label: 'Diagnóstico de Enfermedad Renal Crónica Estadio V', text: x.DxEnfermedadRenalCronica, hint: '', icon: null, classTdItem: ''}
            ],
            propsBottom: []
          })
        })
      },
      methods: {
        showDireccionamientos (nutricional) {
          this.RegistroDireccionamiento.parent = this.item
          this.RegistroDireccionamiento.item = JSON.parse(JSON.stringify(nutricional))
          this.RegistroDireccionamiento.show = true
        }
      }
    }
</script>

<style scoped>

</style>
