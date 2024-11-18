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
      name: 'ServiciosComplementarios',
      props: ['item', 'items'],
      components: {
        InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
        RegistroDireccionamientos: () => import('@/components/mipres/direccionamientos/RegistroDireccionamientos')
      },
      data: () => ({
        RegistroDireccionamiento: {
          parent: null,
          item: null,
          type: 'servicio complementario',
          show: false
        },
        itemsListos: []
      }),
      created () {
        this.items.forEach(x => {
          this.itemsListos.push({
            title: (x.CodSerComp ? x.CodSerComp : '') + (x.DescSerComp ? ' - ' + x.DescSerComp : ''),
            subtitle: 'Tipo de Prestación: ' + (x.TipoPrestador ? x.TipoPrestador : 'No registra.'),
            consecutivo: x.ConOrden,
            id: x.id,
            TipoTec: 'S',
            TipoMed: null,
            cantidadTotal: x.CantTotal,
            EstJM: x.EstJM,
            EstJMText: x.EstadoJunta,
            direccionamientos: x.direccionamientos,
            datosBasicos: [
              {label: 'Descripción', text: (x.CodSerComp ? x.CodSerComp : '') + (x.DescSerComp ? ' - ' + x.DescSerComp : ''), hint: null, icon: null, flexClass: null},
              {label: 'Cantidad', text: x.CanForm !== null ? x.CanForm.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Uso', text: x.CadaFreUso ? x.CadaFreUso + (x.FrecuenciaUso ? ' ' + x.FrecuenciaUso : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.Cant ? x.Cant + (x.DuracionTratamiento ? ' ' + x.DuracionTratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total', text: x.CantTotal !== null ? x.CantTotal.toString() : '', hint: null, icon: null, flexClass: null}
            ],
            propsTop: [
              // {label: 'Código Servicio Complementario', text: x.CodSerComp, hint: null, icon: null, flexClass: 'xs12'},
              // {label: 'Descripción Servicio Complementario', text: x.DescSerComp, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Cantidad', text: x.CanForm !== null ? x.CanForm.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Frecuencia de Uso', text: x.CadaFreUso ? x.CadaFreUso + (x.FrecuenciaUso ? ' ' + x.FrecuenciaUso : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Duración Tratamiento', text: x.Cant ? x.Cant + (x.DuracionTratamiento ? ' ' + x.DuracionTratamiento : '') : null, hint: null, icon: null, flexClass: null},
              {label: 'Cantidad Total', text: x.CantTotal !== null ? x.CantTotal.toString() : '', hint: null, icon: null, flexClass: null},
              {label: 'Justificación No PBS', text: x.JustNoPBS, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Indicaciones o recomendaciones para el paciente', text: x.IndRec, hint: null, icon: null, flexClass: 'xs12'},
              {label: 'Estado de la Junta de Profesionales', text: x.EstadoJunta, hint: null, icon: null, flexClass: null}
            ],
            propsTable: [
              {label: 'Código Causa Solicitud 1 ¿El uso del servicio es cosmético o suntuario?', text: x.CausaS1, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 2 ¿El servicio se prestará en Colombia?', text: x.CausaS2, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 3 ¿El servicio está registrado por la autoridad competente?', text: x.CausaS3, hint: '', icon: null, classTdItem: ''},
              {label: 'Código Causa Solicitud 4 ¿El servicio corresponde a la condición clínica y diagnóstico del paciente?', text: (x.CausaS4 === 1 ? 'SI' : x.CausaS4 === 0 ? 'NO' : x.CausaS4) + (x.DescCausaS4 ? ': ' + x.DescCausaS4 : ''), hint: '', icon: null, classTdItem: 'pl-5'},
              {label: 'Código Causa Solicitud 5 ¿Existe evidencia disponible sobre seguridad, eficacia y efectividad?', text: x.CausaS5, hint: '', icon: null, classTdItem: ''}
            ],
            propsBottom: []
          })
        })
      },
      methods: {
        showDireccionamientos (complementario) {
          this.RegistroDireccionamiento.parent = this.item
          this.RegistroDireccionamiento.item = JSON.parse(JSON.stringify(complementario))
          this.RegistroDireccionamiento.show = true
        }
      }
    }
</script>

<style scoped>

</style>
