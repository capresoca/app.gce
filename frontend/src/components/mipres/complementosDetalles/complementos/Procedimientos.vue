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
              <v-tooltip left v-if="!itemListo.cup">
                <v-btn icon ripple slot="activator" @click.stop="">
                  <v-icon color="error">warning</v-icon>
                </v-btn>
                <span>El procedimiento no se encuentra registrado en la tabla de CUPS.</span>
              </v-tooltip>
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
      name: 'Procedimientos',
      props: ['item', 'items'],
      components: {
        InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
        RegistroDireccionamientos: () => import('@/components/mipres/direccionamientos/RegistroDireccionamientos')
      },
      data: () => ({
        RegistroDireccionamiento: {
          parent: null,
          item: null,
          type: 'procedimiento',
          show: false
        },
        itemsListos: []
      }),
      created () {
        this.items.forEach(x => {
          this.itemsListos.push({
            title: x.CodCUPS + (x.cup ? ' - ' + x.cup.descripcion : ''),
            subtitle: 'Tipo de Prestación: ' + x.tipo_prestador,
            cup: x.cup,
            consecutivo: x.ConOrden,
            id: x.id,
            TipoTec: 'P',
            TipoMed: null,
            cantidadTotal: x.CantTotal,
            EstJM: x.EstJM,
            EstJMText: x.estado_junta,
            direccionamientos: x.direccionamientos,
            datosBasicos: [
              {label: 'Descripción', text: x.CodCUPS + (x.cup ? ' - ' + x.cup.descripcion : ''), hint: null, icon: null, flexClass: null},
              {label: 'Cantidad', text: x.CanForm !== null ? x.CanForm.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Uso', text: x.CadaFreUso ? x.CadaFreUso + (x.frecuencia_uso ? ' ' + x.frecuencia_uso : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.Cant ? x.Cant + (x.periodo_duracion_tratamiento ? ' ' + x.periodo_duracion_tratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total', text: x.CantTotal !== null ? x.CantTotal.toString() : '', hint: null, icon: null, flexClass: null}
            ],
            propsTop: [
              {label: 'CUPS', text: x.CodCUPS + ' - ' + (x.cup ? x.cup.descripcion : 'El procedimiento no se encuentra registrado en la tabla de CUPS.'), hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Cantidad', text: x.CanForm !== null ? x.CanForm.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Uso', text: x.CadaFreUso ? x.CadaFreUso + (x.frecuencia_uso ? ' ' + x.frecuencia_uso : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.Cant ? x.Cant + (x.periodo_duracion_tratamiento ? ' ' + x.periodo_duracion_tratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total', text: x.CantTotal !== null ? x.CantTotal.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Justificación No PBS', text: x.JustNoPBS, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Indicaciones o recomendaciones para el paciente', text: x.IndRec, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Estado de la Junta de Profesionales', text: x.estado_junta, hint: null, icon: null, flexClass: null}
            ],
            propsTable: [
              {label: 'Código Causa Solicitud 11 ¿Tiene CUPS?', text: x.CausaS11, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 12 ¿Es una combinación de CUPS?', text: x.CausaS12, hint: '', icon: null},
              {label: 'Código Causa Solicitud 2 ¿El procedimiento se encuentra en fase experimental?', text: x.CausaS2, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 3 ¿El Procedimiento se encuentra financiado con recursos de la UPC?', text: x.CausaS3, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 4 ¿Utilizó lo existente en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS4, hint: '', icon: null, classTdItem: ''},
              {label: 'Procedimiento PBS Utilizado', text: x.ProPBSUtilizado + ' - ' + (x.procedimiento_utilizado ? x.procedimiento_utilizado.descripcion : 'El procedimiento no se encuentra registrado en la tabla de CUPS.'), hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 5 ¿Descartó lo existente en el Plan de Beneficios en Salud con cargo a la UPC?', text: x.CausaS5, hint: '', icon: null, classTdItem: ''},
              {label: 'Procedimiento PBS Descartado', text: x.ProPBSDescartado + ' - ' + (x.procedimiento_descartado ? x.procedimiento_descartado.descripcion : 'El procedimiento no se encuentra registrado en la tabla de CUPS.'), hint: '', icon: null, classTdItem: ''},
              {label: 'Código Razón 51 - No existe alternativa ¿Lo descartó porque no existe alternativa en el PBS?', text: (x.RznCausaS51 === 1 ? 'SI' : x.RznCausaS51 === 0 ? 'NO' : x.RznCausaS51) + (x.DescRzn51 ? ': ' + x.DescRzn51 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Código Razón 52 - Evidencia científica disponible ¿Lo descartó porque tiene mejor evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?', text: (x.RznCausaS52 === 1 ? 'SI' : x.RznCausaS52 === 0 ? 'NO' : x.RznCausaS52) + (x.DescRzn52 ? ': ' + x.DescRzn52 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Código Causa Solicitud 6 ¿Existe evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?', text: x.CausaS6, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 7 ¿El Procedimiento prescrito se realizará en Colombia?', text: x.CausaS7, hint: '', icon: null, classTdItem: ''}
            ],
            propsBottom: []
          })
        })
      },
      methods: {
        showDireccionamientos (procedimiento) {
          this.RegistroDireccionamiento.parent = this.item
          this.RegistroDireccionamiento.item = JSON.parse(JSON.stringify(procedimiento))
          this.RegistroDireccionamiento.show = true
        }
      }
    }
</script>

<style scoped>

</style>
