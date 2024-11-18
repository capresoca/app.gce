<template>
  <v-dialog
    v-model="dialog"
    width="900"
    persistent
  >
    <template v-slot:activator="{ on }">
      <v-tooltip top :disabled="!!solicitud.diagnostico_principal && !!solicitud.afiliado">
        <v-btn small color="primary" v-on="on" slot="activator" :disabled="!solicitud.diagnostico_principal || !solicitud.afiliado">
          <v-icon left>add</v-icon>
          Agregar servicio
        </v-btn>
        <span>Seleccione el afiliado y el diagnóstico principal</span>
      </v-tooltip>
    </template>

    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-toolbar-title>
          <v-avatar color="primary" class="mr-2" size="40">
            <v-icon class="white--text">post_add</v-icon>
          </v-avatar>
          {{proceso === 'solicitud' ? 'Consultas, Procedimientos, Medicamentos y/o Suministros' : 'Autorización de servicio' }}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex xs12>
            <postulador-v3
              :disabled="proceso === 'autorización' && enAprobacion"
              no-data-text="Busqueda por descripción o código."
              :hint="servicio && servicio.servicio ? `Código: ${servicio.servicio.codigo}` : ''"
              item-text="descrip"
              label="Servicio"
              route="autorizaciones/servicios"
              model="servicios"
              filter="codigo,descrip"
              prepend-icon="fas fa-first-aid"
              v-model="servicio.servicio"
              name="servicio"
              v-validate="`required|vCieCups:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},refcup|vServicioAnio:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},${solicitud && solicitud.diagnostico_principal ? solicitud.diagnostico_principal.id : null}`"
              :error-messages="errors.collect('servicio')"
              :slot-selection='{
                      template:`
                        <span style="width: 100% !important;">{{value.descrip}}</span>
                      `,
                      props: [`value`]
                     }'
              :slot-data='{
                      template:`
                        <span style="width: 100% !important;">{{value.codigo}} - {{value.descrip}}</span>
                      `,
                      props: [`value`]
                     }'
            ></postulador-v3>

<!--            <postulador-v2-->
<!--              :disabled="proceso === 'autorización' && enAprobacion"-->
<!--              ref="postuladorservicios"-->
<!--              no-data="Busqueda por descripción o código."-->
<!--              label="Servicio"-->
<!--              preicon="fas fa-first-aid"-->
<!--              entidad="servicios"-->
<!--              route="autorizaciones/servicios"-->
<!--              v-model="servicio.servicio"-->
<!--              name="servicio"-->
<!--              :rules="`required|vCieCups:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},refcup|vServicioAnio:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},${solicitud && solicitud.diagnostico_principal ? solicitud.diagnostico_principal.id : null}`"-->
<!--              :v-validate="`required|vCieCups:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},refcup|vServicioAnio:${solicitud && solicitud.afiliado ? solicitud.afiliado.id : null},${solicitud && solicitud.diagnostico_principal ? solicitud.diagnostico_principal.id : null}`"-->
<!--              :error-messages="errors.collect('servicio')"-->
<!--              no-btn-edit-->
<!--              no-btn-create-->
<!--              clearable-->
<!--              :hint-text="servicio && servicio.servicio ? `Código: ${servicio.servicio.codigo}` : ''"-->
<!--              :slot-selection='{-->
<!--                      template:`-->
<!--                        <span style="width: 100% !important;">{{value.descrip}}</span>-->
<!--                      `,-->
<!--                      props: [`value`]-->
<!--                     }'-->
<!--              :slot-data='{-->
<!--                      template:`-->
<!--                        <span style="width: 100% !important;">{{value.codigo}} - {{value.descrip}}</span>-->
<!--                      `,-->
<!--                      props: [`value`]-->
<!--                     }'-->
<!--            />-->
          </v-flex>
          <template v-if="servicio.servicio">
            <v-flex xs12 sm6 md3>
              <v-text-field
                :disabled="proceso === 'autorización' && enAprobacion"
                :label="proceso === 'autorización' ? 'Cantidad solicitada' : 'Cantidad'"
                name="cantidad"
                :min="1"
                type="number"
                v-model.number="servicio.cant"
                v-validate="'required|min_value:1'"
                :error-messages="errors.collect('cantidad')"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 md3>
              <v-select
                key="selectcomplejidad"
                label="Complejidad"
                v-model="servicio.nivel"
                :items="complementosServicio.complejidades"
                item-value="codigo"
                item-text="descrip"
                name="complejidad"
                v-validate="'required'"
                :error-messages="errors.collect('complejidad')"
                :disabled="complementosServicio.complejidades && complementosServicio.complejidades.length === 1"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md6>
              <v-autocomplete
                key="autocompletecobertura"
                label="Cobertura POSS"
                v-model="servicio.cober"
                :items="complementosServicio.coberturas"
                item-value="codigo"
                name="cobertura"
                required
                v-validate="'required'"
                :error-messages="errors.collect('cobertura')"
                :filter="filterCoberturas"
                :disabled="complementosServicio.coberturas && complementosServicio.coberturas.length === 1"
              >
                <template slot="item" slot-scope="data">
                <span style="width: 100% !important;">
                  {{data.item.codigo}} - {{data.item.descrip}}
                </span>
                </template>
                <template slot="selection" slot-scope="data">
                <span style="width: 100% !important;">
                  {{data.item.codigo}} - {{data.item.descrip}}
                </span>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6 md3>
              <v-select
                key="selectmodalidad"
                label="Modalidad"
                v-model="servicio.modSer"
                :items="complementosServicio.modalidadesServicio"
                item-value="codigo"
                item-text="descrip"
                name="modalidad"
                v-validate="'required'"
                :error-messages="errors.collect('modalidad')"
                :disabled="complementosServicio.modalidadesServicio && complementosServicio.modalidadesServicio.length === 1"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md3>
              <v-select
                key="tipoqx"
                label="Tipo Qx"
                v-model="servicio.tipModSer"
                :items="complementosServicio.tiposQx"
                item-value="codigo"
                item-text="descrip"
                name="tipo Qx"
                v-validate="'required'"
                :error-messages="errors.collect('tipo Qx')"
                :disabled="complementosServicio.tiposQx && complementosServicio.tiposQx.length === 1"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md6>
              <v-autocomplete
                label="Especialidad"
                v-model="servicio.espec"
                :items="complementosServicio.especialidades"
                item-value="id"
                name="especialidad"
                required
                v-validate="'required'"
                :error-messages="errors.collect('especialidad')"
                :filter="filterEspecialidades"
                :disabled="complementosServicio.especialidades && complementosServicio.especialidades.length === 1"
              >
                <template slot="item" slot-scope="data">
                    <span style="width: 100% !important;">
                      {{data.item.id}} - {{data.item.descripcion}}
                    </span>
                </template>
                <template slot="selection" slot-scope="data">
                    <span style="width: 100% !important;">
                      {{data.item.id}} - {{data.item.descripcion}}
                    </span>
                </template>
              </v-autocomplete>
            </v-flex>
          </template>
          <v-flex xs12>
            <v-textarea
              label="Observaciones"
              v-model="servicio.obs"
              rows="1"
              auto-grow
            />
          </v-flex>
        </v-layout>
        <v-layout row wrap v-if="proceso === 'autorización' && servicio.servicio">
          <v-switch
            label="Servicio contratado"
            v-model="servicio.contratado"
            :true-value="1"
            :false-value="0"
            :disabled="!serviciosContratados.length"
          ></v-switch>
          <template v-if="servicio.contratado">
            <v-flex xs12 >
              <v-autocomplete
                key="autocompletePrestador"
                label="Prestador"
                name="prestador"
                v-model="servicio.servicioContratado"
                :items="serviciosContratados"
                required
                v-validate="'required'"
                :error-messages="errors.collect('prestador')"
                :filter="filterPrestador"
                :hint="servicio.servicioContratado ? 'Contrato:' + servicio.servicioContratado.plan_cobertura.contrato.numero_contrato : ''"
                persistent-hint
              >
                <template slot="item" slot-scope="data">
                  <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                    <v-list-tile-sub-title class="caption">{{data.item.plan_cobertura.contrato.entidad.nombre}}</v-list-tile-sub-title>
                    <v-list-tile-sub-title class="caption">Contrato: {{data.item.plan_cobertura.contrato.numero_contrato}}</v-list-tile-sub-title>
                    <v-list-tile-sub-title class="caption">Plan cobertura: {{data.item.plan_cobertura.nombre}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </template>
                <template slot="selection" slot-scope="data">
                  <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                    <v-list-tile-content class="truncate-content">
                      <v-list-tile-sub-title>{{data.item.plan_cobertura.contrato.entidad.nombre}}</v-list-tile-sub-title>
                      <v-list-tile-sub-title>Plan cobertura: {{data.item.plan_cobertura.nombre}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </template>
              </v-autocomplete>
            </v-flex>
            <template v-if="servicio.servicioContratado">
              <v-flex xs12 sm6 md3>
                <v-text-field
                  :disabled="!servicio.cant"
                  label="Cantidad autorizada"
                  name="cantidad autorizada"
                  :min="1"
                  :max="servicio.cant"
                  type="number"
                  v-model.number="servicio.cAut"
                  v-validate="'required|min_value:1|max_value:' + servicio.cant"
                  :error-messages="errors.collect('cantidad autorizada')"
                ></v-text-field>
              </v-flex>
              <input-detail-flex
                flex-class="xs12 sm6 md3"
                label="Valor unitario"
                :text="currency(servicio.servicioContratado.tarifa)"
              ></input-detail-flex>
              <input-detail-flex
                flex-class="xs12 sm6 md3"
                label="Valor total"
                :text="servicio.cAut && servicio.servicioContratado.tarifa ? currency(servicio.servicioContratado.tarifa * servicio.cAut) : ''"
              ></input-detail-flex>
              <v-flex xs12 sm6 md3>
                <input-number
                  label="Valor copago"
                  key="valor copago"
                  name="valor copago"
                  prepend-icon="attach_money"
                  :precision="0"
                  v-model.number="servicio.copago"
                  v-validate="'required|min_value:0'"
                  :error-messages="errors.collect('valor copago')"
                ></input-number>
              </v-flex>
<!--              <v-flex xs12 sm6 md3>-->
<!--                <v-select-->
<!--                  key="selectaplicacopagos"-->
<!--                  label="Aplica copago"-->
<!--                  v-model="servicio.si_copago"-->
<!--                  :items="complementosServicio.aplicaCopagos"-->
<!--                  name="copago"-->
<!--                  v-validate="'required'"-->
<!--                  :error-messages="errors.collect('copago')"-->
<!--                  :disabled="complementosServicio.aplicaCopagos && complementosServicio.aplicaCopagos.length === 1"-->
<!--                ></v-select>-->
<!--              </v-flex>-->
            </template>
          </template>
          <template v-else>
<!--            <input-detail-flex-->
<!--              flex-class="xs12"-->
<!--              label="Prestador"-->
<!--              :text="solicitud.entidad_ips.nombre"-->
<!--              :hint="'Código habilitación: ' + solicitud.entidad_ips.cod_habilitacion"-->
<!--            ></input-detail-flex>-->
            <v-flex xs12>
              <postulador-v3
                no-data-text="Búsqueda por razon social, identificación o código de habilitación."
                :hint="`${servicio && servicio.entidadNoContratada ? 'Código habilitación: ' + servicio.entidadNoContratada.cod_habilitacion + ' - Municipio: ' + servicio.entidadNoContratada.municipio.nombre : ''}`"
                item-text="nombre"
                label="Prestador"
                route="entidades"
                model="entidades"
                filter="nombre,cod_habilitacion"
                prepend-icon="fas fa-clinic-medical"
                v-model="servicio.entidadNoContratada"
                name="prestador autorizado"
                v-validate="'required'"
                :error-messages="errors.collect('prestador autorizado')"
                :slot-data='{
                  template: `
                    <v-list-tile class="content-v-list-tile-p0">
                      <v-list-tile-content>
                        <v-list-tile-title>{{value.nombre}}</v-list-tile-title>
                        <v-list-tile-sub-title>Código habilitación: {{value.cod_habilitacion}}</v-list-tile-sub-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  `,
                  props: [`value`]
                }'
              ></postulador-v3>
<!--              <postulador-v2-->
<!--                ref="refEntidadNoContratada"-->
<!--                no-data="Busqueda por NIT, razon social u código de habilitación."-->
<!--                hint="municipio.nombre"-->
<!--                item-text="nombre"-->
<!--                data-title="nombre"-->
<!--                data-subtitle="municipio.nombre"-->
<!--                label="Prestador"-->
<!--                entidad="entidades"-->
<!--                preicon="fas fa-clinic-medical"-->
<!--                v-model="servicio.entidadNoContratada"-->
<!--                name="prestador autorizado"-->
<!--                rules="required"-->
<!--                v-validate="'required'"-->
<!--                :error-messages="errors.collect('prestador autorizado')"-->
<!--                no-btn-create-->
<!--                :min-characters-search="3"-->
<!--              />-->
            </v-flex>
            <v-flex xs12 sm6 md3>
              <v-text-field
                :disabled="!servicio.cant"
                label="Cantidad autorizada"
                name="cantidad autorizada"
                :min="1"
                :max="servicio.cant"
                type="number"
                v-model.number="servicio.cAut"
                v-validate="'required|min_value:1|max_value:' + servicio.cant"
                :error-messages="errors.collect('cantidad autorizada')"
              ></v-text-field>
            </v-flex>
            <!-- <input-detail-flex
              v-if="servicio.valorTemporalServicio && !serviciosContratados.length"
              flex-class="xs12 sm6 md3"
              label="Valor unitario 2"
              :text="currency(servicio.valorTemporalServicio)"
            ></input-detail-flex> -->
            <input-detail-flex
              v-if="servicio.valorTemporalServicio && !serviciosContratados.length"
              flex-class="xs12 sm6 md3"
              label="Valor unitario"
              :text="currency(servicio.valor)"
            ></input-detail-flex>
            <v-flex xs12 sm6 md3 v-else>
              <input-number
                label="Valor unitario"
                key="valor unitario"
                name="valor unitario"
                prepend-icon="attach_money"
                :precision="0"
                v-model.number="servicio.valor"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('valor unitario')"
              ></input-number>
            </v-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md3"
              label="Valor total"
              :text="servicio.cAut && servicio.valor ? currency(servicio.valor * servicio.cAut) : ''"
            ></input-detail-flex>
            <v-flex xs12 sm6 md3>
              <input-number
                label="Valor copago"
                key="valor copago"
                name="valor copago"
                prepend-icon="attach_money"
                :precision="0"
                v-model.number="servicio.copago"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('valor copago')"
              ></input-number>
            </v-flex>
<!--            <v-flex xs12 sm6 md3>-->
<!--              <v-select-->
<!--                key="selectaplicacopagos"-->
<!--                label="Aplica copago"-->
<!--                v-model="servicio.si_copago"-->
<!--                :items="complementosServicio.aplicaCopagos"-->
<!--                name="copago"-->
<!--                v-validate="'required'"-->
<!--                :error-messages="errors.collect('copago')"-->
<!--                :disabled="complementosServicio.aplicaCopagos && complementosServicio.aplicaCopagos.length === 1"-->
<!--              ></v-select>-->
<!--            </v-flex>-->
          </template>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cerrar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          flat
          color="primary"
          @click="agregar"
        >
          Agregar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
    export default {
      name: 'DialogRegistroServicios',
      props: {
        proceso: {
          type: String,
          default: 'solicitud'
        },
        solicitud: {
          type: Object,
          default: null
        }
      },
      components: {
        InputNumber: () => import('@/components/general/InputNumber'),
        InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
        PostuladorV2: () => import('@/components/general/PostuladorV2')
      },
      data: () => ({
        tarifaConsultada: 0,
        loading: false,
        dialog: false,
        enAprobacion: false,
        servicio: null,
        makeServicio: {
          id: null,
          au_anexot3s_id: null,
          codigo: null,
          descrip: null,
          cant: null,
          cAut: null,
          nivel: null,
          modSer: null,
          tipModSer: null,
          cober: null,
          espec: null,
          obs: null,
          cont: null,
          valor: null,
          pAut: null,
          si_copago: null,
          copago: 0,
          ind: 1,
          valorTemporalServicio: null,
          // objetos
          servicio: null,
          servicioContratado: null,
          contratado: 0,
          entidadNoContratada: null
        },
        serviciosContratados: [],
        complementosServicio: null,
        makeComplementosServicio: {
          complejidades: [],
          coberturas: [],
          modalidadesServicio: [],
          tiposQx: [],
          especialidades: [],
          aplicaCopagos: []
        },
        filterCoberturas (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.descrip)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterEspecialidades (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codHab + ' ' + item.descripcion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterPrestador (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.plan_cobertura.contrato.numero_contrato + ' ' + item.plan_cobertura.contrato.entidad.cod_habilitacion + ' ' + item.plan_cobertura.contrato.entidad.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }),
      watch: {
        'servicio.entidadNoContratada' (val) {
          if (val) {
            this.servicio.pAut = val.id
            this.servicio.entidad = val
            this.servicio.contratado = 0
          }
        },
        'servicio.contratado' (val) {
          if (!val && !!this.servicio.servicio) {
            this.servicio.servicioContratado = null
            this.servicio.valorTemporalServicio = JSON.parse(JSON.stringify(this.servicio.servicio.valor))
            this.servicio.cont = null
            // this.servicio.valor = this.servicio.servicio.valor
            this.servicio.valor = this.tarifaConsultada // cambio a tarifaConsultada
            this.servicio.entidadNoContratada = JSON.parse(JSON.stringify(this.solicitud.entidad_ips))
          }
        },
        'servicio.servicioContratado' (val) {
          if (val) {
            this.servicio.cont = val.plan_cobertura.id
            this.servicio.valor = val.tarifa
            // this.servicio.valor = this.tarifaConsultada // cambio a tarifaConsultada
            this.servicio.pAut = val.plan_cobertura.contrato.rs_entidad_id
            this.servicio.entidad = val.plan_cobertura.contrato.entidad
          }
        },
        'servicio.servicio' (val) {
          if (val) {
            if (!this.enAprobacion) {
              this.servicio.codigo = val.codigo
              this.servicio.descrip = val.descrip
              this.servicio.nivel = null
              this.servicio.modSer = null
              this.servicio.tipModSer = null
              this.servicio.cober = null
              this.servicio.espec = null
              this.reloadComplementosServicio()
            }
          } else {
            this.servicio.codigo = null
            this.servicio.descrip = null
            this.refreshComplementos()
          }
        },
        'dialog' (val) {
          if (!val) {
            setTimeout(() => {
              this.refresh()
            }, 400)
          } else {
            if (this.proceso === 'autorización' && this.servicio.servicio) {
              setTimeout(() => {
                this.reloadComplementosServicio()
              }, 400)
            }
          }
        }
      },
      created () {
        this.refresh()
      },
      methods: {
        agregar () {
          this.$validator.validateAll().then(result => {
            if (result) {
              this.$emit(this.enAprobacion ? 'aprobar' : 'asignar', this.servicio)
              if (this.proceso === 'autorización') this.dialog = false
              this.refresh()
            }
          })
        },
        reloadComplementosServicio () {
          this.loading = true
          this.axios.get(`autorizaciones/refcups/${this.servicio.codigo}`)
            .then(async response => {
              this.complementosServicio.complejidades = response.data.nivelatencion
              this.complementosServicio.coberturas = response.data.coberturas
              this.complementosServicio.modalidadesServicio = response.data.modalidades
              this.complementosServicio.tiposQx = response.data.quirurgicos
              this.complementosServicio.especialidades = response.data.especialidades
              this.asignarTarifa(response.data.tarifa, this.solicitud.entidad_ips)
              if (this.proceso === 'solicitud') {
                this.servicio.nivel = response.data.nivelatencion.length === 1 ? response.data.nivelatencion[0].codigo : null
                this.servicio.modSer = response.data.modalidades.length === 1 ? response.data.modalidades[0].codigo : null
                this.servicio.tipModSer = response.data.quirurgicos.length === 1 ? response.data.quirurgicos[0].codigo : null
                this.servicio.cober = response.data.coberturas.length === 1 ? response.data.coberturas[0].codigo : null
                this.servicio.espec = response.data.especialidades.length === 1 ? response.data.especialidades[0].id : null
                this.loading = false
              } else {
                if (!this.enAprobacion) {
                  this.servicio.au_anexot3s_id = this.solicitud.id
                  this.servicio.nivel = response.data.nivelatencion.length === 1 ? response.data.nivelatencion[0].codigo : null
                  this.servicio.modSer = response.data.modalidades.length === 1 ? response.data.modalidades[0].codigo : null
                  this.servicio.tipModSer = response.data.quirurgicos.length === 1 ? response.data.quirurgicos[0].codigo : null
                  this.servicio.cober = response.data.coberturas.length === 1 ? response.data.coberturas[0].codigo : null
                  this.servicio.espec = response.data.especialidades.length === 1 ? response.data.especialidades[0].id : null
                }
                this.serviciosContratados = []
                // await this.axios.get(`autorizaciones/contrato/${this.servicio.codigo}?afiliado_id=${this.solicitud.afiliado.id}`)
                await this.axios.get(`autorizaciones/contrato/${this.servicio.codigo}?regimen=${this.solicitud.afiliado.as_regimene_id}`)
                  .then((response) => {
                    this.serviciosContratados = response.data
                    this.servicio.contratado = 1
                    this.loading = false
                  })
                  .catch(e => {
                    if (e.response.status === 404) {
                      this.servicio.cont = null
                      this.servicio.valorTemporalServicio = JSON.parse(JSON.stringify(this.servicio.servicio.valor))
                      // this.servicio.valor = this.servicio.servicio.valor
                      this.servicio.valor = this.tarifaConsultada // cambio a tarifaConsultada
                      this.servicio.entidadNoContratada = JSON.parse(JSON.stringify(this.solicitud.entidad_ips))
                      this.$store.commit('SNACK_SHOW', {msg: e.response.data.message, color: 'info'})
                    } else {
                      this.$store.commit('SNACK_ERROR_LIST', {expression: ` al recuperar los planes de cobertura. `, error: e})
                    }
                    this.loading = false
                  })
                // await this.axios.get(`autorizaciones/copagos/${this.servicio.id}`)
                //   .then((response) => {
                //     this.servicio.si_copago = response.data.data.length === 1 ? response.data.data[0] : null
                //     this.complementosServicio.aplicaCopagos = response.data.data
                //     this.loading = false
                //   })
                //   .catch(e => {
                //     this.loading = false
                //     this.$store.commit('SNACK_ERROR_LIST', {expression: ` al validar la aplicación de copago para el servicio. `, error: e})
                //   })
              }
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los complementos del servicio. `, error: e})
            })
        },
        asignarItem (item) {
          this.enAprobacion = true
          this.servicio = item
          // this.$refs.postuladorservicios.assign(this.servicio.servicio)
          this.dialog = true
        },
        refresh () {
          this.servicio = JSON.parse(JSON.stringify(this.makeServicio))
          this.enAprobacion = false
          this.refreshComplementos()
        },
        refreshComplementos () {
          this.complementosServicio = JSON.parse(JSON.stringify(this.makeComplementosServicio))
          this.$validator.reset()
        },
        asignarTarifa (tarifas, servicio) {
          for (var indice in tarifas) {
            if (tarifas[indice].nombre === this.solicitud.entidad_ips.nombre) {
              // console.log('se asignara a', tarifas[indice].nombre)
              // console.log('el valor de', tarifas[indice].tarifa)
              this.tarifaConsultada = tarifas[indice].tarifa
              // console.log('la variable de tarifa', this.tarifaConsultada)
            }
          }
        }
      }
    }
</script>

<style scoped>

</style>
