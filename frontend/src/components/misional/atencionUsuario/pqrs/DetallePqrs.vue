<template>
  <div>
    <v-dialog v-model="dialogConfirm" persistent max-width="290">
      <v-card>
        <v-card-title class="headline grey lighten-3">¿Desea confirmar la respuesta?</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <p>Si escoge la opción 'Si' la respuesta se guardará y los campos de la respuesta no podrán volver a ser editados.</p>
          <p>Si escoge la opción 'No' la respuesta se guardará y seguirá habilitada para edición.</p>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn color="green darken-1" :loading="loadingSubmit" flat @click.native="dialogConfirm = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" :loading="loadingSubmit" flat @click.native="submit('No')">No</v-btn>
          <v-btn color="primary" :loading="loadingSubmit" flat @click.native="submit('Si')">Si</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formPqrs">
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-card flat class="content-list-p0 pt-0">
              <v-list two-line>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar color="info">
                      <v-icon dark>assignment</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Detalle PQRS
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-if="item">Consecutivo: {{ item.consecutivo }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>

                  <v-divider/>
                  <v-tooltip top>
                    <v-btn absolute dark small fab top right fixed color="pink"
                           v-if="item && item.respuesta"
                           slot="activator"
                           :loading="loadingPdf"
                           @click="descargarPdf"
                           class="botonFlotante" >
                      <v-icon>far fa-file-pdf</v-icon>
                    </v-btn>
                    <span>Vista previa carta respuesta</span>
                  </v-tooltip>

                </template>
              </v-list>
              <v-card-text>
                <v-container fluid grid-list-xl class="pa-0" ref="detallePqrs">
                  <loading v-model="loading" />
                  <v-slide-y-transition group>

                    <v-layout row wrap v-if="item" key="seccion1">
                      <v-flex xs12 class="pa-0">
                        <v-subheader>Afiliado</v-subheader>
                      </v-flex>
                      <v-flex xs2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Identificación</v-list-tile-sub-title>
                          <span>{{`${item.afiliado.tipo_identificacion} ${item.afiliado.identificacion}`}}</span>
                        </v-list-tile-content>
                      </v-flex>
                      <v-flex xs10>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Nombre</v-list-tile-sub-title>
                          <span style="text-decoration: underline; cursor: pointer" @click.prevent="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: item.afiliado.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">
                            {{ item.afiliado.nombre_completo }}
                          </span>
                        </v-list-tile-content>
                      </v-flex>
                    </v-layout>

                    <v-layout row wrap v-if="item" key="seccion2">
                      <v-flex xs12 class="pa-0">
                        <v-divider/>
                        <v-subheader>Entidad</v-subheader>
                      </v-flex>
                      <v-flex xs2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Código habilitación</v-list-tile-sub-title>
                          <span>{{item.entidad.cod_habilitacion}}</span>
                        </v-list-tile-content>
                      </v-flex>
                      <v-flex xs10>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Nombre</v-list-tile-sub-title>
                          <span>{{item.entidad.nombre}}</span>
                        </v-list-tile-content>
                      </v-flex>
                    </v-layout>

                    <v-layout row wrap v-if="item" key="seccion3">
                      <v-flex xs12 class="pa-0">
                        <v-divider/>
                        <v-subheader>Remitente</v-subheader>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Nombres</v-list-tile-sub-title>
                          <span>{{ item.nombres }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Apellidos</v-list-tile-sub-title>
                          <span>{{ item.apellidos != 'null' ? item.apellidos : 'Sin apellidos' }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Identificación</v-list-tile-sub-title>
                          <span>{{ item.identificacion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Municipios</v-list-tile-sub-title>
                          <span>{{ item.municipio.nombre }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Dirección</v-list-tile-sub-title>
                          <span>{{ item.direccion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Teléfono</v-list-tile-sub-title>
                          <span>{{ item.telefono }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Email</v-list-tile-sub-title>
                          <span>{{ item.email || 'No registra' }}</span>
                        </v-list-tile-content>
                      </v-flex>

                    </v-layout>

                    <v-layout row wrap v-if="item" key="seccion4">
                      <v-flex xs12 class="pa-0">
                        <v-divider/>
                        <v-subheader>Petición</v-subheader>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Concecutivo externo</v-list-tile-sub-title>
                          <span>{{ item.consecutivo_externo }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Fecha recepción</v-list-tile-sub-title>
                          <span>{{ item.fecha_recepcion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Medio recepción</v-list-tile-sub-title>
                          <span>{{ item.medio_recepcion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Tipo</v-list-tile-sub-title>
                          <span>{{ item.tipo.tipo }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Prioritaria</v-list-tile-sub-title>
                          <span>{{ item.es_prioritaria }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Asignado a</v-list-tile-sub-title>
                          <span>{{ item.funcionario.name }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Plazo asignado</v-list-tile-sub-title>
                          <span>{{ item.plazo }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días restantes para responder</v-list-tile-sub-title>
                          <span>{{ item.dias_para_responder }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Teléfono</v-list-tile-sub-title>
                          <span>{{ item.telefono }}</span>
                        </v-list-tile-content>
                      </v-flex>

                    </v-layout>

                    <v-layout row wrap v-if="item" key="seccion5">
                      <v-flex xs12>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Asunto</v-list-tile-sub-title>
                          <span>{{ item.asunto.descripcion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Relato de los hechos</v-list-tile-sub-title>
                          <span>{{item.relato}}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12>
                        <v-list-tile-sub-title class="caption grey--text">Archivos adjuntos</v-list-tile-sub-title>
                        <span v-if="item.anexos.length <= 0 ">No hay archivos adjuntos</span>
                      </v-flex>

                      <v-flex xs12 sm4 v-if="item.anexos.length > 0" v-for="(archivo, index) in item.anexos" :key="index">
                        <v-layout align-center fill-height>
                          <v-flex d-flex xs10 sm8>
                            <v-text-field v-model="archivo.nombre"
                                          label="Archivo" key="archivo"
                                          name="archivo" disabled prepend-icon="description"
                                          :error-messages="errors.collect('archivo')" ></v-text-field>

                          </v-flex>

                          <v-flex d-flex xs2 sm4>
                            <v-tooltip top>
                              <v-btn
                                fab
                                color="success"
                                small
                                :href="archivo.url_signed"
                                target="_blank"
                                slot="activator"
                              >
                                <v-icon>remove_red_eye</v-icon>
                              </v-btn>
                              <span>Ver archivo</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                  </v-slide-y-transition>
                </v-container>
              </v-card-text>
            </v-card>
            <v-card flat class="content-list-p0 pt-0" v-if="item">
              <v-list two-line>
                <template>
                  <v-divider/>
                  <v-list-tile>
                    <v-list-tile-avatar color="success">
                      <v-icon dark>input</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Respuesta
                      </v-list-tile-title>
                      <v-list-tile-sub-title>Estado: {{ item.respuesta ? item.respuesta.estado : 'Sin respuesta' }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <div v-if="item.respuesta"> <!--Tiene respuesta-->
                <v-card-text v-if="item.respuesta.estado === 'Confirmada'"> <!--Tiene Respuesta y esta confirmada-->
                  <v-container fluid grid-list-xl class="pa-0" ref="detallePqrs">
                    <loading v-model="loading" />
                    <v-slide-y-transition group>

                      <v-layout row wrap v-if="item" key="seccion1">

                        <v-flex xs12 sm6>
                          <v-list-tile-content slot="activator">
                            <v-list-tile-sub-title class="caption grey--text">Fecha de respuesta</v-list-tile-sub-title>
                            <span>{{respuesta.fecha_respuesta}}</span>
                          </v-list-tile-content>
                        </v-flex>

                        <v-flex xs12 sm6>
                          <v-list-tile-content slot="activator">
                            <v-list-tile-sub-title class="caption grey--text">Tipo</v-list-tile-sub-title>
                            <span>{{respuesta.tipo}}</span>
                          </v-list-tile-content>
                        </v-flex>

                        <v-flex xs12>
                          <v-subheader>Respuesta</v-subheader>
                          <ckeditor v-model="respuesta.respuesta" :editor="editor" :config="editorConfig" disabled></ckeditor>
                        </v-flex>

                        <v-flex xs12 class="pt-1">
                          <v-subheader class="tipo">¿Se solucionó la queja?</v-subheader>
                          <v-radio-group v-model="respuesta.soluciono_queja" :disabled="verificarEstadoRespuesta" row>
                            <v-radio label="Si" :value="1"></v-radio>
                            <v-radio label="No" :value="0"></v-radio>
                          </v-radio-group>
                        </v-flex>

                        <v-flex xs12>
                          <v-list-tile-sub-title class="caption grey--text">Archivos adjuntos</v-list-tile-sub-title>
                          <span v-if="item.respuesta.anexos.length <= 0 ">No hay archivos adjuntos</span>
                        </v-flex>

                        <v-flex xs12 sm4 v-if="item.respuesta.anexos.length > 0" v-for="(archivo, index) in item.respuesta.anexos" :key="index">
                          <v-layout align-center fill-height>
                            <v-flex d-flex xs10 sm8>
                              <v-text-field v-model="archivo.nombre"
                                            label="Archivo" key="archivo"
                                            name="archivo" disabled prepend-icon="description"
                                            :error-messages="errors.collect('archivo')" ></v-text-field>

                            </v-flex>
                            <v-flex d-flex xs2 sm4>
                              <v-tooltip top>
                                <v-btn
                                  fab
                                  color="success"
                                  small
                                  :href="archivo.url_signed"
                                  target="_blank"
                                  slot="activator"
                                >
                                  <v-icon>remove_red_eye</v-icon>
                                </v-btn>
                                <span>Ver archivo</span>
                              </v-tooltip>
                            </v-flex>
                          </v-layout>
                        </v-flex>


                      </v-layout>
                    </v-slide-y-transition>
                  </v-container>
                </v-card-text >
                <v-card-text v-else> <!--Tiene respuesta pero no esta confirmada-->
                  <v-container fluid grid-list-xl class="pa-0" ref="detallePqrs">
                    <loading v-model="loading" />
                    <v-slide-y-transition group>

                      <div row wrap v-if="item" key="seccion1">

                        <v-form data-vv-scope="formDetalleRespuesta">
                          <v-flex xs12 sm4>
                            <v-subheader class="tipo">Fecha de respuesta</v-subheader>

                            <v-menu
                              ref="menuDate"
                              :close-on-content-click="false"
                              v-model="menuDate"
                              :nudge-right="40"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="computedDateFormatted"
                                label="Fecha"
                                prepend-icon="event"
                                readonly
                                name="fecha de respuesta"
                                :disabled="verificarEstadoRespuesta"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha de respuesta')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="respuesta.fecha_respuesta"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha de respuesta')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>

                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-subheader class="tipo">Tipo</v-subheader>
                            <v-radio-group v-model="respuesta.tipo" :disabled="verificarEstadoRespuesta" row>
                              <v-radio label="Positiva" value="Positiva"></v-radio>
                              <v-radio label="Negativa" value="Negativa"></v-radio>
                              <v-radio label="Otra" value="Otra"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12>
                            <v-subheader>Respuesta</v-subheader>
                            <ckeditor v-model="respuesta.respuesta" :editor="editor" :config="editorConfig"></ckeditor>
                          </v-flex>

                          <v-flex xs12 class="pt-1">
                            <v-subheader class="tipo">¿Se solucionó la queja?</v-subheader>
                            <v-radio-group v-model="respuesta.soluciono_queja" :disabled="verificarEstadoRespuesta" row>
                              <v-radio label="Si" :value="1"></v-radio>
                              <v-radio label="No" :value="0"></v-radio>
                            </v-radio-group>
                          </v-flex>

                        </v-form>

                      </div>

                      <v-layout row wrap v-if="item" key="seccion2">
                        <v-flex xs12 class="pa-0">
                          <v-subheader>Archivos adjuntos</v-subheader>
                        </v-flex>

                        <span v-if="item.respuesta.anexos.length <= 0 ">No hay archivos adjuntos</span>

                        <v-flex xs12 sm4 v-if="item.respuesta.anexos.length > 0" v-for="(archivo, index) in item.respuesta.anexos" :key="index">
                          <v-layout align-center fill-height>
                            <v-flex d-flex xs10 sm8>
                              <v-text-field v-model="archivo.nombre"
                                            label="Archivo" key="archivo"
                                            name="archivo" disabled prepend-icon="description"
                                            :error-messages="errors.collect('archivo')" ></v-text-field>

                            </v-flex>
                            <v-flex d-flex xs2 sm4>
                              <v-tooltip top>
                                <v-btn
                                  fab
                                  color="success"
                                  small
                                  :href="archivo.url_signed"
                                  target="_blank"
                                  slot="activator"
                                >
                                  <v-icon>remove_red_eye</v-icon>
                                </v-btn>
                                <span>Ver archivo</span>
                              </v-tooltip>
                            </v-flex>
                          </v-layout>
                        </v-flex>


                        <v-flex xs12 sm4 v-for="(i, index) in archivosNuevos" :key="index">
                          <input-file
                            ref="archivoAdjunto"
                            label="Adjuntar archivo"
                            v-model="respuesta.files[index]"
                            :file-name="respuesta.files[index] ? respuesta.files[index].nombre : null"
                            accept="application/pdf"
                            :hint="'Extenciones aceptadas: pdf'"
                            class="mb-3"
                            :disabled="verificarEstadoRespuesta"
                            prepend-icon="description"
                            clearable
                          />
                        </v-flex>
                        <v-tooltip top>
                          <v-btn fab dark small color="primary" slot="activator" @click="archivosNuevos.push({})">
                            <v-icon dark>add</v-icon>
                          </v-btn>
                          <span>Nuevo archivo</span>
                        </v-tooltip>
                      </v-layout>
                    </v-slide-y-transition>
                  </v-container>
                </v-card-text>
              </div>
              <v-card-text v-else> <!--No tiene respuesta-->
                <v-container fluid grid-list-xl class="pa-0" ref="detallePqrs">
                  <loading v-model="loading" />
                  <v-slide-y-transition group>

                    <v-layout row wrap v-if="item.tipo.tipo == 'Sugerencia' || item.tipo.tipo == 'Felicitaciones'" key="noRespuesta">
                      <v-flex xs12>
                        <v-alert :value="true" type="info">
                          El tipo de PQRS es {{ item.tipo.tipo }} y no requiere respuesta.
                        </v-alert>
                      </v-flex>
                    </v-layout>

                    <div row wrap v-if="item && (item.tipo.tipo != 'Sugerencia' && item.tipo.tipo != 'Felicitaciones')" key="seccion1">

                      <v-form data-vv-scrope="formDetalleRespuesta">

                        <v-flex xs12 sm4>
                          <v-subheader class="tipo">Fecha de respuesta</v-subheader>

                          <v-menu
                            ref="menuDate"
                            :close-on-content-click="false"
                            v-model="menuDate"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                          >
                            <v-text-field
                              slot="activator"
                              v-model="computedDateFormatted"
                              label="Fecha"
                              prepend-icon="event"
                              readonly
                              name="fecha de respuesta"
                              required
                              :disabled="verificarEstadoRespuesta"
                              data-vv-delay="1000"
                              v-validate="'required|date_format:YYYY/MM/DD'"
                              :error-messages="errors.collect('fecha de respuesta')"
                            ></v-text-field>
                            <v-date-picker
                              v-model="respuesta.fecha_respuesta"
                              @input="menuDate = false"
                              locale='es'
                              no-title
                            ></v-date-picker>
                          </v-menu>

                        </v-flex>

                        <v-flex xs12 sm6>
                          <v-subheader class="tipo">Tipo</v-subheader>
                          <v-radio-group v-model="respuesta.tipo" :disabled="verificarEstadoRespuesta" row>
                            <v-radio label="Positiva" value="Positiva"></v-radio>
                            <v-radio label="Negativa" value="Negativa"></v-radio>
                            <v-radio label="Otra" value="Otra"></v-radio>
                          </v-radio-group>
                        </v-flex>

                        <v-flex xs12 class="pa-0">
                          <v-subheader>Respuesta</v-subheader>
                          <ckeditor v-model="respuesta.respuesta" :editor="editor" :config="editorConfig"></ckeditor>
                        </v-flex>
                        <v-flex xs12 class="pt-1">
                          <v-subheader class="tipo">¿Se solucionó la queja?</v-subheader>
                          <v-radio-group v-model="respuesta.soluciono_queja" :disabled="verificarEstadoRespuesta" row>
                            <v-radio label="Si" :value="1"></v-radio>
                            <v-radio label="No" :value="0"></v-radio>
                          </v-radio-group>
                        </v-flex>

                      </v-form>

                    </div>

                    <v-layout row wrap v-if="item && (item.tipo.tipo != 'Sugerencia' && item.tipo.tipo != 'Felicitaciones')" key="Seccion2Adjuntos">
                      <v-flex xs12 class="pa-0">
                        <v-subheader>Archivos adjuntos</v-subheader>
                      </v-flex>

                      <v-flex xs12 sm4 v-for="(i, index) in archivosNuevos" :key="index">
                        <input-file
                          ref="archivoAdjunto"
                          label="Adjuntar archivo"
                          v-model="respuesta.files[index]"
                          :file-name="respuesta.files[index] ? respuesta.files[index].nombre : null"
                          accept="application/pdf"
                          :hint="'Extenciones aceptadas: pdf'"
                          class="mb-3"
                          :disabled="verificarEstadoRespuesta"
                          prepend-icon="description"
                        />
                      </v-flex>
                      <v-tooltip top>
                        <v-btn fab dark small color="primary" slot="activator" @click="archivosNuevos.push({})">
                          <v-icon dark>add</v-icon>
                        </v-btn>
                        <span>Nuevo archivo</span>
                      </v-tooltip>

                    </v-layout>
                  </v-slide-y-transition>
                </v-container>
              </v-card-text>

            </v-card>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4" v-if="item && (item.tipo.tipo != 'Sugerencia' && item.tipo.tipo != 'Felicitaciones')">
        <v-layout row wrap v-if="item.respuesta === null || item.respuesta.estado !== 'Confirmada'">
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn @click="dialogConfirm = true" color="primary" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {DETALLE_PQRS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import ToolbarList from '@/components/general/ToolbarList'
  import CKEditor from '@ckeditor/ckeditor5-vue'
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
  import {SNACK_ERROR_LIST} from '../../../../store/modules/general/snackbar'

  export default {
    name: 'DetallePqrs',
    components: {
      ToolbarList,
      Loading,
      InputFile: () => import('@/components/general/InputFile'),
      ckeditor: CKEditor.component
    },
    props: ['parametros'],
    data () {
      return {
        editor: ClassicEditor,
        editorConfig: {
          toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'link', 'link', 'numberedList', '|', 'undo', 'redo' ],
          language: 'es'
        },
        respuesta: null,
        payload: null,
        archivosNuevos: [{}],
        menuDate: false,
        dialogConfirm: null,
        loading: false,
        loadingSubmit: false,
        loadingPdf: false,
        item: null
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    computed: {
      computedDateFormatted () {
        return this.formDate(this.respuesta.fecha_respuesta)
      },
      verificarEstadoRespuesta () {
        if (this.item.respuesta) return this.item.respuesta.estado === 'Confirmada'
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.detallePqrs.$el
        })
        this.axios.get('pqrsds/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.item = response.data.data
              if (response.data.data.respuesta) this.agregarDatosRespuesta(response.data.data.respuesta)
              this.respuesta.soluciono_queja = this.item.soluciono_queja
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la pqrs. ' + e.response, color: 'danger'})
          })
      },
      async descargarPdf () {
        if (await this.validador('formDetalleRespuesta')) {
          this.loadingPdf = true
          this.respuesta.estado = 'Registrada'
          this.addFormData()
          let send = !this.respuesta.id ? this.axios.post(`pqrsds/${this.item.id}/respuesta`, this.payload) : this.axios.post(`pqrsds/${this.item.id}/respuesta/${this.respuesta.id}`, this.payload)
          let respuesta = await send.then((res) => {
            return true
          }).catch(e => {
            this.loadingPdf = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `:`, error: e})
            return false
          })
          if (respuesta) {
            this.axios.get(`firmar-ruta?nombre_ruta=respuesta-pqrs-pdf&id=${this.item.id}`)
              .then(res => {
                window.open(res.data)
                this.loadingPdf = false
              })
              .catch(e => {
                this.loadingPdf = false
                this.$store.commit(SNACK_SHOW, {msg: 'Error al generar el archivo. ' + e, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      formReset () {
        this.respuesta = {
          id: null,
          texto: null,
          files: [],
          tipo: 'Positiva',
          fecha_respuesta: null,
          anexos: null,
          soluciono_queja: null
        }
      },
      agregarDatosRespuesta (respuesta) {
        this.respuesta.id = respuesta.id
        this.respuesta.tipo = respuesta.tipo
        this.respuesta.fecha_respuesta = respuesta.fecha_respuesta
        this.respuesta.respuesta = respuesta.respuesta
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      addFormData () {
        let data = new FormData()
        data.append('fecha_respuesta', this.respuesta.fecha_respuesta)
        data.append('respuesta', this.respuesta.respuesta)
        data.append('tipo', this.respuesta.tipo)
        data.append('estado', this.respuesta.estado)
        data.append('soluciono_queja', this.respuesta.soluciono_queja)
        for (let file of this.respuesta.files) {
          data.append('files[]', file)
        }

        this.payload = data
      },
      async submit (estado) {
        if (await this.validador('formDetalleRespuesta')) {
          this.loadingSubmit = true
          estado === 'Si' ? this.respuesta.estado = 'Confirmada' : this.respuesta.estado = 'Registrada'
          this.addFormData()
          let send = !this.respuesta.id ? this.axios.post(`pqrsds/${this.item.id}/respuesta`, this.payload) : this.axios.post(`pqrsds/${this.item.id}/respuesta/${this.respuesta.id}`, this.payload)
          send.then((res) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se guardo la respuesta correctamente.', color: 'success'})
            this.$store.commit(DETALLE_PQRS_RELOAD_ITEM, {item: res.data, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.loadingSubmit = false
            this.dialogConfirm = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `:`, error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .editor {
    height: 500px;
  }

  .botonFlotante {
    margin: 160px 16px 0 0 ;
  }
</style>
