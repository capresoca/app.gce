<template>
  <v-card>
    <loading v-model="loading"></loading>
    <v-toolbar dense>
      <v-icon>assignment_late</v-icon>
      <v-toolbar-title class="text-capitalize">{{ (!item.id ? 'Nueva ' : 'Edición de ') + 'solicitud de autorización' }}</v-toolbar-title>
    </v-toolbar>
    <v-container fluid grid-list-sm>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>person</v-icon>
                Datos del afiliado
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <dialog-red-servicios v-if="item && item.afiliado" :afiliado-id="item.afiliado.id"></dialog-red-servicios>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <v-flex xs12 :sm8="item && item.afiliado" :md9="item && item.afiliado">
                  <postulador-v3
                    no-data-text="Busqueda por nombre o número de documento."
                    :hint="`${item && item.afiliado ? item.afiliado.identificacion_completa : ''}`"
                    item-text="nombre_completo"
                    label="Afiliado"
                    route="afiliados"
                    model="afiliados"
                    model-state="itemAfiliado"
                    filter="nombre_completo,identificacion_completa"
                    prepend-icon="person"
                    v-model="item.afiliado"
                    name="afiliadox"
                    v-validate="'required|afiliadoValidoAutorizacion'"
                    :error-messages="errors.collect('afiliadox')"
                    clearable
                    :slot-append='{
                      template:`<span class="pt-2 caption">{{value ? "Estado: " + value.estado_afiliado.codigo + " - " + value.estado_afiliado.nombre : ""}}</span>`,
                      props: [`value`]
                     }'
                    :slot-prepend='{
                      template:`<mini-card-detail :data="value ? value.mini_afiliado : null" alone-icon></mini-card-detail>`,
                      props: [`value`]
                    }'
                    :slot-data='{
                      template: `
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.identificacion_completa}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{value.nombre_completo}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                    }'
                    @input="val => item.as_afiliado_id = val ? val.id : null"
                  ></postulador-v3>
<!--                  <postulador-v2-->
<!--                    no-data="Búsqueda por nombre o número de documento."-->
<!--                    hint="identificacion_completa"-->
<!--                    item-text="nombre_completo"-->
<!--                    data-title="identificacion_completa"-->
<!--                    data-subtitle="nombre_completo"-->
<!--                    label="Afiliado"-->
<!--                    entidad="afiliados"-->
<!--                    v-model="item.afiliado"-->
<!--                    @changeid="val => item.as_afiliado_id = val"-->
<!--                    @change="saveLocalStorage"-->
<!--                    name="Afiliado"-->
<!--                    clearable-->
<!--                    rules="required|afiliadoValidoAutorizacion"-->
<!--                    v-validate="'required|afiliadoValidoAutorizacion'"-->
<!--                    :error-messages="errors.collect('Afiliado')"-->
<!--                    :slot-append='{-->
<!--                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,-->
<!--                      props: [`value`]-->
<!--                     }'-->
<!--                    :slot-prepend='item && item.afiliado && item.afiliado.mini_afiliado ? {-->
<!--                                template:`<mini-card-detail :data="value ? value.mini_afiliado : null" alone-icon></mini-card-detail>`,-->
<!--                                props: [`value`]-->
<!--                               } : null'-->
<!--                    no-btn-edit-->
<!--                    no-btn-create-->
<!--                  />-->
                </v-flex>
                <template v-if="item && item.afiliado">
                  <input-detail-flex
                    flex-class="xs12 sm4 md3"
                    label="Regimen"
                    underline
                    :text="item.afiliado.regimen.codigo + ' - ' + item.afiliado.regimen.nombre"
                  ></input-detail-flex>
                  <input-detail-flex
                    flex-class="xs12 sm6 md4"
                    label="Municipio residencia"
                    underline
                    :text="complementos.municipios && item && item.datosAfiliado && item.datosAfiliado.gn_municipio_id && complementos.municipios.find(x => x.id === item.datosAfiliado.gn_municipio_id).nombre"
                  ></input-detail-flex>
                  <input-detail-flex
                    flex-class="xs12 sm6 md4"
                    label="Zona"
                    underline
                    :text="complementos.zonas && item && item.datosAfiliado && item.datosAfiliado.gn_zona_id && complementos.zonas.find(x => x.id === item.datosAfiliado.gn_zona_id).nombre"
                  ></input-detail-flex>
                  <v-flex xs12 sm6 md4 v-if="item.datosAfiliado.gn_zona_id === 2">
                    <v-autocomplete
                      key="autocomple-vereda"
                      label="Vereda"
                      v-model="item.datosAfiliado.gn_vereda_id"
                      :items="veredas"
                      item-value="id"
                      item-text="nombre"
                      name="Vereda"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Vereda')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md4 v-if="item.datosAfiliado.gn_zona_id === 1">
                    <v-autocomplete
                      key="autocomple-barrio"
                      label="Barrio"
                      v-model="item.datosAfiliado.gn_barrio_id"
                      :items="barrios"
                      item-value="id"
                      item-text="nombre"
                      name="Barrio"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Barrio')"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 md6>
                    <v-text-field
                      key="input-direccion"
                      v-upper="'item.datosAfiliado.direccion'"
                      label="Dirección"
                      v-model="item.datosAfiliado.direccion"
                      name="Dirección"
                      required
                      v-validate="'required|verify_direccion'"
                      :error-messages="errors.collect('Dirección')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-text-field
                      key="input-correo"
                      label="Correo electrónico"
                      v-model="item.datosAfiliado.correo_electronico"
                      name="Correo electrónico"
                      v-validate="'required|email'"
                      data-vv-validate-on="input|change|custom|blur"
                      :error-messages="errors.collect('Correo electrónico')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-text-field
                      key="input-celular"
                      label="Celular"
                      v-model="item.datosAfiliado.celular"
                      name="Celular"
                      v-number="'item.datosAfiliado.celular'"
                      data-vv-validate-on="input|change|custom|focus|blur"
                      v-validate="'required|numeric'"
                      :error-messages="errors.collect('Celular')"
                    />
                  </v-flex>
                </template>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
<!--        <v-flex xs12 sm6 md3>-->
<!--          <v-select-->
<!--            label="Origen autorización"-->
<!--            v-model="item.origen_autorizacion_id"-->
<!--            :items="complementosAnexo.origenesautorizacion"-->
<!--            item-value="codigo"-->
<!--            item-text="descrip"-->
<!--            name="origen autorización"-->
<!--            v-validate="'required'"-->
<!--            :error-messages="errors.collect('origen autorización')"-->
<!--          ></v-select>-->
<!--        </v-flex>-->
        <v-flex xs12 sm6 md6 v-if="item && Number(item.origen_autorizacion_id) === 3">
          <v-select
            key="selecttutela"
            label="Tutela"
            v-model="item.oj_tutela_id"
            :items="tutelasAfiliado"
            item-value="id"
            name="origen autorización"
            v-validate="'required'"
            :error-messages="errors.collect('origen autorización')"
            :hint="tutelasAfiliado && item && item.oj_tutela_id ? 'Fecha notificación: ' + tutelasAfiliado.find(x => x.id === item.oj_tutela_id).fecha_notificacion : ''"
          >
            <template slot="item" slot-scope="data">
              <v-list-tile-content class="truncate-content">
                <v-list-tile-title class="caption">{{data.item.tipo_tutela}} - {{data.item.no_tutela}}</v-list-tile-title>
                <v-list-tile-sub-title class="caption">Fecha notificación: {{data.item.fecha_notificacion}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
            <template slot="selection" slot-scope="data">
              <span>{{data.item.tipo_tutela}} - {{data.item.no_tutela}}</span>
            </template>
          </v-select>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-select
            label="Vía solicitud"
            v-model="item.via_solicitud_id"
            :items="complementosAnexo.viasSolicitud"
            item-value="codigo"
            item-text="descrip"
            name="vía solicitud"
            v-validate="'required'"
            :error-messages="errors.collect('vía solicitud')"
            @change="saveLocalStorage"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <input-date
            v-model="item.fecha_orden"
            label="Fecha orden médica"
            format="YYYY-MM-DD"
            name="fecha orden médica"
            :min="minDate"
            :max="maxDate"
            v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
            :error-messages="errors.collect('fecha orden médica')"
            @change="saveLocalStorage"
          ></input-date>
        </v-flex>
        <v-flex xs12 sm6 md6>
          <postulador-v3
            no-data-text="Búsqueda por razon social, identificación o código de habilitación."
            :hint="`${item && item.entidad_origen ? 'Código habilitación: ' + item.entidad_origen.cod_habilitacion : ''}`"
            item-text="nombre"
            label="Prestador"
            route="entidades"
            model="entidades"
            filter="nombre,cod_habilitacion"
            prepend-icon="location_city"
            v-model="item.entidad_origen"
            name="prestador"
            v-validate="'required'"
            :error-messages="errors.collect('prestador')"
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
            @input="val => item.entidad_origen_id = val ? val.id : null"
          ></postulador-v3>
<!--          <postulador-v2-->
<!--            no-data="Búsqueda por razon social u código de habilitación."-->
<!--            hint="tercero.identificacion_completa"-->
<!--            item-text="nombre"-->
<!--            data-title="tercero.identificacion_completa"-->
<!--            data-subtitle="nombre"-->
<!--            label="Prestador"-->
<!--            entidad="entidades"-->
<!--            preicon="location_city"-->
<!--            @changeid="val => item.entidad_origen_id = val"-->
<!--            v-model="item.entidad_origen"-->
<!--            name="prestador"-->
<!--            rules="required"-->
<!--            v-validate="'required'"-->
<!--            :error-messages="errors.collect('prestador')"-->
<!--            no-btn-create-->
<!--            :min-characters-search="3"-->
<!--            @change="saveLocalStorage"-->
<!--          />-->
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-select
            label="Origen atención"
            v-model="item.origen_atencion_id"
            :items="complementosAnexo.origenatencion"
            item-value="id"
            item-text="descripcion"
            name="origen atención"
            v-validate="'required'"
            :error-messages="errors.collect('origen atención')"
            @change="saveLocalStorage"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-select
            label="Tipo de servicios solicitados"
            v-model="item.tipo_servicio_solicitado_id"
            :items="complementosAnexo.tiposServiciosSolicitados"
            item-value="codigo"
            item-text="descrip"
            name="tipo de servicios solicitados"
            v-validate="'required'"
            :error-messages="errors.collect('tipo de servicios solicitados')"
            @change="saveLocalStorage"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Atención prioritaria"
            v-model="item.prioridad_atencion"
            :true-value="1"
            :false-value="0"
            @change="saveLocalStorage"
          ></v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-select
            label="Ubicación paciente"
            v-model="item.ubicacion_paciente_id"
            :items="complementosAnexo.refubics"
            item-value="codigo"
            item-text="descrip"
            name="ubicación paciente"
            v-validate="'required'"
            :error-messages="errors.collect('ubicación paciente')"
            @change="saveLocalStorage"
          ></v-select>
        </v-flex>
        <template v-if="item && item.ubicacion_paciente_id === '3'">
          <v-flex xs12 sm6 md3>
            <v-text-field
              key="vtextservicio"
              label="Servicio"
              name="servicio hospitalización"
              v-model="item.servicio_hospitalizacion"
              v-validate="'required'"
              :error-messages="errors.collect('servicio hospitalización')"
              @change="saveLocalStorage"
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-text-field
              key="vtextcama"
              label="Cama"
              name="cama"
              v-model="item.cama_hospitalizacion"
              v-validate="'required'"
              :error-messages="errors.collect('cama')"
              @change="saveLocalStorage"
            ></v-text-field>
          </v-flex>
        </template>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>fas fa-first-aid</v-icon>
                Impresión diagnóstica
              </v-toolbar-title>
            </v-toolbar>
            <v-container fluid grid-list-sm class="py-1 px-2">
              <v-layout row wrap>
                <v-flex xs12>
                  <postulador-v3
                    no-data-text="Búsqueda por descripción o código."
                    :hint="`${item && item.diagnostico_principal ? 'Código: ' + item.diagnostico_principal.codigo : ''}`"
                    item-text="descripcion"
                    label="Diagnóstico Principal"
                    route="rs_cie10s"
                    model="rs_cie10s"
                    filter="codigo,descripcion"
                    prepend-icon="local_hospital"
                    v-model="item.diagnostico_principal"
                    name="diagnóstico principal"
                    v-validate="'required|vCieCups:' + item.as_afiliado_id + ',cie10'"
                    :error-messages="errors.collect('diagnóstico principal')"
                    :slot-data='{
                        template: `
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                              <v-list-tile-sub-title>Código: {{value.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        `,
                        props: [`value`]
                      }'
                    @input="val => item.cie10_principal_id = val ? val.id : null">
                  </postulador-v3>
<!--                  <postulador-v2-->
<!--                    ref="postuladorCie10P"-->
<!--                    no-data="Búsqueda por descripción o código."-->
<!--                    hint="codigo"-->
<!--                    item-text="descripcion"-->
<!--                    data-title="descripcion"-->
<!--                    data-subtitle="codigo"-->
<!--                    label="Diagnóstico Principal"-->
<!--                    entidad="rs_cie10s"-->
<!--                    preicon="local_hospital"-->
<!--                    v-model="item.diagnostico_principal"-->
<!--                    @changeid="val => item.cie10_principal_id = val"-->
<!--                    name="diagnóstico principal"-->
<!--                    :rules="'required|vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    v-validate="'required|vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    :error-messages="errors.collect('diagnóstico principal')"-->
<!--                    no-btn-edit-->
<!--                    no-btn-create-->
<!--                    @change="saveLocalStorage"-->
<!--                  />-->
                </v-flex>
                <v-flex xs12>
                  <postulador-v3
                    no-data-text="Búsqueda por descripción o código."
                    :hint="`${item && item.cie10_rel1 ? 'Código: ' + item.cie10_rel1.codigo : ''}`"
                    item-text="descripcion"
                    label="Diagnóstico relacionado 1"
                    route="rs_cie10s"
                    model="rs_cie10s"
                    filter="codigo,descripcion"
                    prepend-icon="queue"
                    v-model="item.cie10_rel1"
                    name="diagnóstico relacionado 1"
                    v-validate="'vCieCups:' + item.as_afiliado_id + ',cie10'"
                    :error-messages="errors.collect('diagnóstico relacionado 1')"
                    :slot-data='{
                        template: `
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                              <v-list-tile-sub-title>Código: {{value.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        `,
                        props: [`value`]
                      }'
                    @input="val => item.cie10_rel1_id = val ? val.id : null">
                  </postulador-v3>
<!--                  <postulador-v2-->
<!--                    ref="postuladorCie10S"-->
<!--                    no-data="Búsqueda por descripción o código."-->
<!--                    hint="codigo"-->
<!--                    item-text="descripcion"-->
<!--                    data-title="descripcion"-->
<!--                    data-subtitle="codigo"-->
<!--                    label="Diagnóstico relacionado 1"-->
<!--                    entidad="rs_cie10s"-->
<!--                    preicon="queue"-->
<!--                    v-model="item.cie10_rel1"-->
<!--                    @changeid="val => item.cie10_rel1_id = val"-->
<!--                    :rules="'vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    v-validate="'vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    name="diagnóstico relacionado 1"-->
<!--                    :error-messages="errors.collect('diagnóstico relacionado 1')"-->
<!--                    clearable-->
<!--                    no-btn-edit-->
<!--                    no-btn-create-->
<!--                    @change="saveLocalStorage"-->
<!--                  />-->
                </v-flex>
                <v-flex xs12>
                  <postulador-v3
                    no-data-text="Búsqueda por descripción o código."
                    :hint="`${item && item.cie10_rel2 ? 'Código: ' + item.cie10_rel2.codigo : ''}`"
                    item-text="descripcion"
                    label="Diagnóstico relacionado 2"
                    route="rs_cie10s"
                    model="rs_cie10s"
                    filter="codigo,descripcion"
                    prepend-icon="queue"
                    v-model="item.cie10_rel2"
                    name="diagnóstico relacionado 2"
                    v-validate="'vCieCups:' + item.as_afiliado_id + ',cie10'"
                    :error-messages="errors.collect('diagnóstico relacionado 2')"
                    :slot-data='{
                        template: `
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                              <v-list-tile-sub-title>Código: {{value.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        `,
                        props: [`value`]
                      }'
                    @input="val => item.cie10_rel2_id = val ? val.id : null">
                  </postulador-v3>
<!--                  <postulador-v2-->
<!--                    ref="postuladorCie10T"-->
<!--                    no-data="Búsqueda por descripción o código."-->
<!--                    hint="codigo"-->
<!--                    item-text="descripcion"-->
<!--                    data-title="descripcion"-->
<!--                    data-subtitle="codigo"-->
<!--                    label="Diagnóstico Relacionado 2"-->
<!--                    entidad="rs_cie10s"-->
<!--                    preicon="queue"-->
<!--                    v-model="item.cie10_rel2"-->
<!--                    @changeid="val => item.cie10_rel2_id = val"-->
<!--                    :rules="'vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    v-validate="'vCieCups:' + item.as_afiliado_id + ',cie10'"-->
<!--                    name="diagnóstico relacionado 2"-->
<!--                    :error-messages="errors.collect('diagnóstico relacionado 2')"-->
<!--                    clearable-->
<!--                    no-btn-edit-->
<!--                    no-btn-create-->
<!--                    @change="saveLocalStorage"-->
<!--                  />-->
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs12>
          <input-file-v2
            label="Historia clínica"
            v-model="historia_clinica"
            accept="application/pdf"
            :hint="'Extenciones aceptadas: pdf'"
            prepend-icon="fas fa-file-pdf"
            name="historia clínica"
            v-validate="'fileExtension:pdf'"
            :error-messages="errors.collect('historia clínica')"
            @change="saveLocalStorage"
          ></input-file-v2>
        </v-flex>
        <v-flex xs12>
<!--          <v-textarea-->
<!--            label="Justificación clínica"-->
<!--            v-model="item.justificacion_clinica"-->
<!--            name="justificación clínica"-->
<!--            :counter="255"-->
<!--            v-validate="'required|min:30|max:255'"-->
<!--            :error-messages="errors.collect('justificación clínica')"-->
<!--            rows="1"-->
<!--            auto-grow-->
<!--          ></v-textarea>-->
<!--          <div-->
<!--            data-v-0b574909=""-->
<!--            class="v-input v-textarea v-text-field v-input&#45;&#45;has-state theme&#45;&#45;light"-->
<!--            :class="errors.first('justificación clínica') ? 'error&#45;&#45;text' : esActivo ? 'primary&#45;&#45;text v-input&#45;&#45;is-focused' : ''"-->
<!--            aria-required="true"-->
<!--            aria-invalid="true"-->
<!--          >-->
<!--            <div class="v-input__control">-->
<!--              <div class="v-input__slot">-->
<!--                <div class="v-text-field__slot">-->
<!--                  <label aria-hidden="true" class="v-label v-label&#45;&#45;active theme&#45;&#45;light" :class="errors.first('justificación clínica') ? 'error&#45;&#45;text' : esActivo ? 'primary&#45;&#45;text' : ''" style="left: 0px; right: auto; position: absolute;">Justificación clínica</label>-->
<!--                  <textarea aria-label="Justificación clínica" name="justificación clínica" rows="2" v-model="item.justificacion_clinica" @focus="esActivo = true" @blur="esActivo = false"></textarea>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="v-text-field__details">-->
<!--                <div class="v-messages theme&#45;&#45;light" :class="errors.first('justificación clínica') ? 'error&#45;&#45;text' : esActivo ? 'primary&#45;&#45;text' : ''">-->
<!--                  <div class="v-messages__wrapper">-->
<!--                    <div class="v-messages__message">{{errors.first('justificación clínica')}}</div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
          <v-textarea
            label="Justificación clínica"
            v-model="item.justificacion_clinica"
            @change="saveLocalStorage"
          ></v-textarea>
        </v-flex>
        <v-flex xs12 sm12 md6>
          <postulador-v3
            no-data-text="Busqueda por nombre, código o especialidad."
            item-text="nombre_completo"
            label="Médico"
            route="autorizaciones/medicos"
            model="medicos"
            filter="codigo,descripcion,especialidad.descripcion"
            prepend-icon="fas fa-user-md"
            v-model="item.medico_solicitante"
            name="médico"
            v-validate="'required'"
            :error-messages="errors.collect('médico')"
            clearable
            @click:append-outer="openCreateMedico"
            :slot-append-outer='{
                      template:`
                        <v-tooltip top>
                          <v-btn
                            class="ma-0"
                            slot="activator"
                            flat
                            icon
                            >
                              <v-icon color="accent">add</v-icon>
                          </v-btn>
                          <span>Crear médico</span>
                        </v-tooltip>
                      `,
                      props: [`value`]
                     }'
            :slot-selection='{
                      template:`
                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.codigo}} - {{value.descripcion}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{value.especialidad.descripcion}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                     }'
            :slot-data='{
                      template:`
                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                          <v-list-tile-content>
                            <v-list-tile-title>{{value.codigo}} - {{value.descripcion}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{value.especialidad.descripcion}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      `,
                      props: [`value`]
                     }'
            @input="val => item.medico_solicitante_id = val ? val.id : null"
          ></postulador-v3>

<!--          <postulador-v2-->
<!--            ref="postuladormedico"-->
<!--            no-data="Busqueda por nombre, código o especialidad."-->
<!--            label="Médico"-->
<!--            preicon="fas fa-user-md"-->
<!--            entidad="medicos"-->
<!--            route="autorizaciones/medicos"-->
<!--            v-model="item.medico_solicitante"-->
<!--            @changeid="val => item.medico_solicitante_id = val"-->
<!--            name="médico"-->
<!--            rules="required"-->
<!--            v-validate="'required'"-->
<!--            :error-messages="errors.collect('médico')"-->
<!--            no-btn-edit-->
<!--            clearable-->
<!--            btn-create-truncate-->
<!--            @change="saveLocalStorage"-->
<!--            @create="openCreateMedico"-->
<!--            :slot-selection='{-->
<!--                      template:`-->
<!--                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">-->
<!--                          <v-list-tile-content>-->
<!--                            <v-list-tile-title>{{value.codigo}} - {{value.descripcion}}</v-list-tile-title>-->
<!--                            <v-list-tile-sub-title>{{value.especialidad.descripcion}}</v-list-tile-sub-title>-->
<!--                          </v-list-tile-content>-->
<!--                        </v-list-tile>-->
<!--                      `,-->
<!--                      props: [`value`]-->
<!--                     }'-->
<!--            :slot-data='{-->
<!--                      template:`-->
<!--                        <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">-->
<!--                          <v-list-tile-content>-->
<!--                            <v-list-tile-title>{{value.codigo}} - {{value.descripcion}}</v-list-tile-title>-->
<!--                            <v-list-tile-sub-title>{{value.especialidad.descripcion}}</v-list-tile-sub-title>-->
<!--                          </v-list-tile-content>-->
<!--                        </v-list-tile>-->
<!--                      `,-->
<!--                      props: [`value`]-->
<!--                     }'-->
<!--          />-->
        </v-flex>
        <v-flex xs12 sm12 md6>
          <v-autocomplete
            ref="autocompleteespecialidad"
            :disabled="!item.medico_solicitante"
            label="Especialidad"
            v-model="item.au_especialidad_id"
            :items="complementosMedico.especialidadesAutorizacion"
            item-value="id"
            name="especialidad"
            required
            v-validate="'required'"
            :error-messages="errors.collect('especialidad')"
            prepend-icon="fas fa-award"
            @change="saveLocalStorage"
            :filter="filterEspecialidad">
            <template slot="item" slot-scope="data">
              <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                <v-list-tile-title>{{data.item.descripcion}}</v-list-tile-title>
                <v-list-tile-sub-title>Nivel: {{data.item.nivel}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
            <template slot="selection" slot-scope="data">
              <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                <v-list-tile-content class="truncate-content">
                  <v-list-tile-title>{{data.item.descripcion}}</v-list-tile-title>
                  <v-list-tile-sub-title>Nivel: {{data.item.nivel}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-toolbar-title class="subheading">
                <v-icon left>fas fa-briefcase-medical</v-icon>
                Servicios registrados
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <dialog-registro-servicios
                proceso="solicitud"
                :solicitud="item"
                @asignar="val => asignar(val)"
              ></dialog-registro-servicios>
            </v-toolbar>
            <v-data-table
              :headers="headersServicios"
              :items="item.detalles"
              hide-actions
            >
              <template v-slot:items="props">
                <td>{{ props.item.servicio.codigo }}</td>
                <td>{{ props.item.servicio.descrip }}</td>
                <td class="text-xs-center">{{ props.item.cant }}</td>
                <td class="text-xs-center">
                  <v-tooltip top>
                    <v-btn flat icon color="error" slot="activator" @click.stop="item.detalles.splice(props.index, 1)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Borrar</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-btn @click="limpiarLocalStorage">Limpiar</v-btn>
      <v-spacer/>
      <v-fade-transition>
        <span class="subheading error--text mr-4" v-if="visibleTextErrorForm">
        <v-icon color="error">warning</v-icon>
        Existen campos por diligenciar en el formulario.
      </span>
      </v-fade-transition>
      <v-btn @click="guardarConfirmar" color="success">Guardar y Confirmar</v-btn>
    </v-card-actions>
    <dialog-registro-medico ref="dialogRegistroMedico" :btn-open="false" @created="medico => item.medico_solicitante = medico"></dialog-registro-medico>
    <confirmar
      :value="dialogA.visible"
      :content="dialogA.content"
      :loading="dialogA.loading"
      :requiere-motivo="false"
      @cancelar="cancelaConfirmacion"
      @aceptar="aceptaConfirmacion"
    ></confirmar>
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import { Validator } from 'vee-validate'
  import DialogRedServicios from '@/components/misional/redservicios/DialogRedServicios'
  import DialogRegistroMedico from '@/components/misional/atencionUsuario/medicos/DialogRegistroMedico'
  import DialogRegistroServicios from '@/components/misional/atencionUsuario/autorizaciones/registroAutorizacion/DialogRegistroServicio'
  export default {
    name: 'RegistroSolicitudAutorizacion',
    props: ['parametros'],
    components: {
      DialogRegistroServicios,
      DialogRedServicios,
      DialogRegistroMedico,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      Confirmar: () => import('@/components/general/Confirmar'),
      InputFileV2: () => import('@/components/general/InputFileV2'),
      Loading
    },
    data: () => ({
      esActivo: false,
      visibleTextErrorForm: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      loading: false,
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      filterEspecialidad (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.descripcion)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      idAfiliadoValido: null,
      item: null,
      makeAutorizacion: {
        id: null,
        as_afiliado_id: null,
        entidad_origen_id: null,
        cie10_principal_id: null,
        cie10_rel1_id: null,
        cie10_rel2_id: null,
        fecha_orden: null,
        observaciones: null,
        historia_clinica: null,
        justificacion_clinica: null,
        origen_autorizacion_id: '1',
        origen_atencion_id: null,
        medico_solicitante_id: null,
        ubicacion_paciente_id: null,
        servicio_hospitalizacion: null,
        cama_hospitalizacion: null,
        oj_tutela_id: null,
        via_solicitud_id: null,
        tipo_servicio_solicitado_id: null,
        prioridad_atencion: 0,
        au_especialidad_id: null,
        detalles: [],
        // Objetos
        afiliado: null,
        entidad_origen: null,
        diagnostico_principal: null,
        cie10_rel1: null,
        cie10_rel2: null,
        medico_solicitante: null,
        tutela: null,
        datosAfiliado: {
          gn_municipio_id: null,
          gn_zona_id: null,
          gn_vereda_id: null,
          gn_barrio_id: null,
          direccion: null,
          correo_electronico: null,
          celular: null
        }
      },
      historia_clinica: null,
      dialogA: {
        visible: false,
        loading: false,
        content: null,
        registroID: null
      },
      tutelasAfiliado: [],
      veredas: [],
      barrios: [],
      loadingComplementos: false,
      complementosAnexo: {
        origenatencion: []
      },
      headersServicios: [
        {
          text: 'Código',
          align: 'left',
          sortable: false
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: false
        },
        {
          text: 'Cantidad',
          align: 'center',
          sortable: false
        },
        {
          text: 'Opciones',
          align: 'center',
          sortable: false
        }
      ]
    }),
    watch: {
      'item.medico_solicitante' (val) {
        this.item.au_especialidad_id = val ? val.au_especialidad_id : null
      },
      'item.origen_autorizacion_id' (val) {
        if (val && Number(val) === 3 && !!this.item.as_afiliado_id) {
          this.getTutelas()
        }
      },
      'item.datosAfiliado.gn_municipio_id' (val) {
        if (val) {
          this.item.datosAfiliado.gn_zona_id && this.item.datosAfiliado.gn_zona_id === 2 ? this.getVeredas() : this.getBarrios()
        }
      },
      'item.datosAfiliado.gn_zona_id' (val) {
        if (val) {
          this.item.datosAfiliado.gn_municipio_id && val === 2 ? this.getVeredas() : this.getBarrios()
        }
      },
      'item.afiliado' (val) {
        if (val) {
          this.item.diagnostico_principal = null
          this.item.cie10_rel1 = null
          this.item.cie10_rel2 = null
          // if (this.$refs.postuladorCie10P) this.$refs.postuladorCie10P.reset()
          // if (this.$refs.postuladorCie10S) this.$refs.postuladorCie10S.reset()
          // if (this.$refs.postuladorCie10T) this.$refs.postuladorCie10T.reset()
          this.item.datosAfiliado.gn_municipio_id = val.gn_municipio_id
          this.item.datosAfiliado.gn_zona_id = val.gn_zona_id
          this.item.datosAfiliado.gn_vereda_id = val.gn_vereda_id
          this.item.datosAfiliado.gn_barrio_id = val.gn_barrio_id
          this.item.datosAfiliado.direccion = val.direccion
          this.item.datosAfiliado.correo_electronico = val.correo_electronico
          this.item.datosAfiliado.celular = val.celular
        }
      },
      'item.diagnostico_principal' (val) {
        if (val) {
          this.saveLocalStorage()
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAutorizacion
      },
      complementosMedico () {
        return store.getters.complementosFormRegistroMedico
      }
    },
    created () {
      this.refresh()
      this.getComplementos()
      this.loadLocalStorage()
      if (this.parametros.entidad && this.parametros.entidad.id) {
        this.getData()
      }
    },
    beforeMount () {
      Validator.extend('afiliadoValidoAutorizacion', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = value
              ? (value.estado === 3)
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 3).nombre}.`}
              : {valido: true, mensaje: null}
            return resolve({
              valid: response.valido,
              data: {
                message: response.mensaje
              }
            })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    methods: {
      getTutelas () {
        this.loading = true
        this.axios.get(`tutelas_afiliado/${this.item.as_afiliado_id}`)
          .then((response) => {
            console.log('data tutelas', response)
            this.tutelasAfiliado = response.data.data
            if (!response.data.data.length) this.$store.commit('SNACK_SHOW', {msg: 'El afiliado no tiene tutelas activas.', color: 'warning'})
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer las tutelas del afiliado. `, error: e})
          })
      },
      openCreateMedico () {
        this.$refs.dialogRegistroMedico.open()
      },
      getComplementos () {
        this.loadingComplementos = true
        this.axios.get(`anexos_complementos`)
          .then((response) => {
            this.complementosAnexo = response.data.data
            this.loadingComplementos = false
          })
          .catch(e => {
            this.loadingComplementos = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los complementos del anexo. `, error: e})
          })
      },
      async getData () {
        this.loading = true
        this.axios.get(`${'autorizaciones'}/${this.parametros.entidad.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.idAfiliadoValido = JSON.parse(JSON.stringify(response.data.data.as_afiliado_id))
              response.data.data.datosAfiliado = JSON.parse(JSON.stringify(this.makeAutorizacion.datosAfiliado))
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los datos del proceso de autorización. `, error: e})
          })
      },
      refresh () {
        this.item = JSON.parse(JSON.stringify(this.makeAutorizacion))
        this.idAfiliadoValido = null
        if (this.$refs.historiaClinica) this.$refs.historiaClinica.reset()
        this.$validator.reset()
      },
      async guardarConfirmar () {
        if (await this.validado()) {
          this.dialogA.content = 'Si decide Aceptar, el formulario se procesará y no podrá editar estos datos.'
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        setTimeout(() => {
          this.dialogA.content = null
        }, 400)
      },
      async aceptaConfirmacion () {
        await this.submit()
        this.cancelaConfirmacion()
      },
      getBase64 (file) {
        return new Promise((resolve, reject) => {
          const reader = new FileReader()
          reader.readAsDataURL(file)
          reader.onload = () => resolve(reader.result)
          reader.onerror = error => reject(error)
        })
      },
      async validado () {
        let errorForm = await this.$validator.validateAll()
        let errorDetails = this.item.detalles.length
        if (!errorForm) {
          this.visibleTextErrorForm = true
          setTimeout(() => {
            this.visibleTextErrorForm = false
          }, 8000)
        }
        if (!errorDetails) {
          this.$store.commit('SNACK_SHOW', {msg: 'No hay servicios registrados.', color: 'orange'})
        }
        return (errorForm && errorDetails)
      },
      async submit () {
        if (await this.validado()) {
          let data = {
            id: this.item.id,
            nSolicitud: null,
            fecha: null,
            hora: null,
            fOrdMed: this.item.fecha_orden,
            codigoIps: this.item.entidad_origen.cod_habilitacion,
            orgAten: this.item.origen_atencion_id,
            serSol: this.item.tipo_servicio_solicitado_id,
            prior: this.item.prioridad_atencion,
            ubic: this.item.ubicacion_paciente_id,
            serv: this.item.ubicacion_paciente_id === '3' ? this.item.servicio_hospitalizacion : null,
            cama: this.item.ubicacion_paciente_id === '3' ? this.item.cama_hospitalizacion : null,
            jusCli: this.item.justificacion_clinica,
            gn_archivo_id: null,
            dPrin: this.item.diagnostico_principal.codigo,
            dRel1: this.item.cie10_rel1 ? this.item.cie10_rel1.codigo : null,
            dRel2: this.item.cie10_rel2 ? this.item.cie10_rel2.codigo : null,
            as_afiliado_id: this.item.afiliado.id,
            fS: null,
            gs_usuario_id: null,
            fS1: null,
            oriAut: this.item.origen_autorizacion_id,
            viaSol: this.item.via_solicitud_id,
            au_medico_id: this.item.medico_solicitante.id,
            lesp: this.item.au_especialidad_id,
            tInd: null,
            tNum: null,
            tExt: null,
            tCel: null,
            docs: null,
            usuReg: null,
            ind: 1,
            oj_tutela_id: Number(this.item.origen_autorizacion_id) === 3 ? this.item.oj_tutela_id : null,
            datos_afiliado: {
              gn_vereda_id: this.item.datosAfiliado.gn_zona_id === 2 ? this.item.datosAfiliado.gn_vereda_id : null,
              gn_barrio_id: this.item.datosAfiliado.gn_zona_id === 1 ? this.item.datosAfiliado.gn_barrio_id : null,
              direccion: this.item.datosAfiliado.direccion,
              correo_electronico: this.item.datosAfiliado.correo_electronico,
              celular: this.item.datosAfiliado.celular
            },
            detalles: this.item.detalles
          }
          this.loading = true
          this.dialogA.loading = true
          if (this.historia_clinica instanceof File) {
            this.item.historia_clinica = {
              id: null,
              name: this.historia_clinica.name,
              size: this.historia_clinica.size,
              type: this.historia_clinica.type,
              string: await this.getBase64(this.historia_clinica)
            }
            data.historia_clinica = this.item.historia_clinica
          }
          let typeRequest = data.id ? 'editar' : 'crear'
          let request = typeRequest === 'editar' ? this.axios.put(`autorizaciones/anexo3/${data.id}`, data) : this.axios.post('autorizaciones/anexo3', data)
          request
            .then(response => {
              this.$store.commit('reloadTable', 'tableAutorizacionesFuncionarios')
              this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
              this.$store.commit('SNACK_SHOW', {msg: 'La solicitud de autorización se ha guardado correctamente.', color: 'success'})
              this.loading = false
              this.dialogA.loading = false
              this.limpiarLocalStorage()
            }).catch(e => {
              this.loading = false
              this.dialogA.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: `al guardar el registro de la autorización.`, error: e})
            })
        }
      },
      getVeredas () {
        if (this.item.datosAfiliado.gn_municipio_id && this.item.datosAfiliado.gn_zona_id === 2) {
          if (!(this.veredas.length && (this.item.datosAfiliado.gn_municipio_id === this.veredas[0].gn_municipio_id))) {
            this.axios.get('complementos/veredas/' + this.item.datosAfiliado.gn_municipio_id)
              .then((response) => {
                this.veredas = response.data.data
              })
              .catch(e => {
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las veredas. ', error: e})
              })
          }
        }
      },
      getBarrios () {
        if (this.item.datosAfiliado.gn_municipio_id && this.item.datosAfiliado.gn_zona_id === 1) {
          if (!(this.barrios.length && (this.item.datosAfiliado.gn_municipio_id === this.barrios[0].gn_municipio_id))) {
            this.axios.get('complementos/barrios/' + this.item.datosAfiliado.gn_municipio_id)
              .then((response) => {
                this.barrios = response.data.data
              })
              .catch(e => {
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los barrios. ', error: e})
              })
          }
        }
      },
      asignar (val) {
        this.item.detalles.push(val)
        // this.saveLocalStorage()
      },
      saveLocalStorage () {
        localStorage.setItem('data', JSON.stringify(this.item))
      },
      loadLocalStorage () {
        if (localStorage.getItem('data')) { this.item = JSON.parse(localStorage.getItem('data')) }
      },
      limpiarLocalStorage () {
        this.refresh()
        this.saveLocalStorage()
      }
    }
  }
</script>

<style scoped>

</style>
