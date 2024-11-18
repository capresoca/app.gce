<template>  
  <div>
    <!-- inicio formulario modal 1 estudios-->
    <v-dialog v-model="dialog" width="500px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar estudios</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-text-field v-model="educacion.establecimiento"
                              label="Establecimiento educativo" key="establecimiento educativo"
                              name="establecimiento educativo" v-validate="'required'" required
                              :error-messages="errors.collect('establecimiento educativo')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="educacion.titulo"
                              label="Título obtenido" key="título obtenido"
                              name="título obtenido" v-validate="'required'" required
                              :error-messages="errors.collect('título obtenido')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-menu
                  ref="menuDateFechaEducacion"
                  :close-on-content-click="false"
                  v-model="menuDateFechaEducacion"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    ref="prueba"
                    slot="activator"
                    v-model="computedDateFormattedFechaEducacion"
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="educacion.fecha"
                    @input="menuDateFechaEducacion = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>             
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario 1 -->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveEducacion" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    

    <!-- inicio formulario modal 2 familia-->
    <v-dialog v-model="dialog2" width="1000px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar familiar</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6 lg6>
              <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Parentesco"
                  :items="parentescos"
                  name="parentesco"
                  v-validate="'required'"
                  :error-messages="errors.collect('parentesco')"
                  v-model="familia.parentesco"
                ></v-select>                
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.grado"
                              label="Grado" key="grado"
                              name="grado" v-validate="'required|max:10|'" required readonly
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.nombre"
                              label="Nombres y apellidos" key="nombres y apellidos"
                              name="nombres y apellidos" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('nombres y apellidos')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.direccion"
                              label="Dirección" key="dirección de familiar"
                              name="dirección de familiar" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('dirección de familiar')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="familia.telefono"
                              label="Teléfono" key="teléfono de familiar"
                              name="teléfono de familiar" v-validate="'max:50'"
                              :error-messages="errors.collect('teléfono de familiar')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-menu
                  ref="menuDateFechaFamiliar"
                  :close-on-content-click="false"
                  v-model="menuDateFechaFamiliar"
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
                    v-model="computedDateFormattedFechaFamiliar"
                    label="Fecha nacimiento"
                    prepend-icon="event"
                    readonly
                    name="fecha nacimiento de familiar"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha nacimiento de familiar')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="familia.fecha_nacimiento"
                    @input="menuDateFechaFamiliar = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha nacimiento')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>           
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario 2-->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveFamilia">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <!-- inicio formulario modal 3 -->
    <v-dialog v-model="dialog3" width="1000px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar experiencia laboral</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.empresa"
                              label="Empresa" key="empresa"
                              name="empresa" v-validate="'required'" required
                              :error-messages="errors.collect('empresa')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.cargo"
                              label="Cargo" key="cargo"
                              name="cargo" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('cargo')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
               <v-menu
                  ref="menuDateFechaIngreso"
                  :close-on-content-click="false"
                  v-model="menuDateFechaIngreso"
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
                    v-model="computedDateFormattedFechaIngreso"
                    label="Fecha ingreso"
                    prepend-icon="event"
                    readonly
                    name="fecha ingreso"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha ingreso')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="experienciaLaboral.fecha_ingreso"
                    @input="menuDateFechaIngreso = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha ingreso')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>  
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-menu
                  ref="menuDateFechaRetiro"
                  :close-on-content-click="false"
                  v-model="menuDateFechaRetiro"
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
                    v-model="computedDateFormattedFechaRetiro"
                    label="Fecha retiro"
                    prepend-icon="event"
                    readonly
                    name="fecha retiro"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha retiro')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="experienciaLaboral.fecha_retiro"
                    @input="menuDateFechaRetiro = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha retiro')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>               
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.direccion"
                              label="Dirección" key="dirección de empresa"
                              name="dirección de empresa" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('dirección de empresa')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="experienciaLaboral.telefono"
                              label="Teléfono" key="teléfono de empresa"
                              name="teléfono de empresa" v-validate="'required|max:50|'" required
                              :error-messages="errors.collect('teléfono de empresa')"></v-text-field>                 
              </v-flex>


            </v-layout>
          </v-container>
          <!-- fin formulario 3-->

          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveExperiencia" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    
    <!-- fin formulario modal -->
    <v-card ref="loaderRef">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de empleado' : 'Registro de empleado' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text class="no-padding">
      <!-- start -->
        <v-tabs
          v-model="tabs"
          centered
        >
          <v-tab>Información personal</v-tab>
          <v-tab>Estudios y grupo familiar</v-tab>
          <v-tab>Información laboral</v-tab>
        </v-tabs>
        <v-container fluid grid-list-xl >
          <v-tabs-items v-model="tabs">
            <!-- Información personal -->
            <v-tab-item>
              <v-card flat>
                <v-form data-vv-scope="formEmpleadosSC">
                    <v-flex xs12 class="pb-4">
                      <v-toolbar dense class="grey lighten-4 elevation-0" v-show="!mostrar">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Personal </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="mostrarCard(1,'personal')">
                          <v-icon title="mostrar">remove_red_eye</v-icon>
                        </v-btn>
                      </v-toolbar>
                      <v-card v-show="mostrar">
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading"><v-icon>person</v-icon> Personal</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn icon @click="mostrarCard(2,'personal')">
                              <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                          <v-layout row wrap>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Tipo de identificación"
                                :items="tipoDoc"
                                name="tipo de identificacion"
                                v-validate="'required'"
                                :error-messages="errors.collect('tipo de identificacion')"
                                v-model="empleado.tipo_identificacion"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-menu
                                ref="menuDateInicio"
                                :close-on-content-click="false"
                                v-model="menuDateInicio"
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
                                  v-model="computedDateFormattedFechaNacimiento"
                                  label="Fecha nacimiento"
                                  prepend-icon="event"
                                  readonly
                                  name="fecha nacimiento"
                                  v-validate="'required|date_format:YYYY/MM/DD'"
                                  :error-messages="errors.collect('fecha nacimiento')"
                                ></v-text-field>
                                <v-date-picker
                                  v-model="empleado.fecha_nacimiento"
                                  @input="menuDateInicio = false"
                                  @change="() => {
                                    let index = $validator.errors.items.findIndex(x => x.field === 'fecha nacimiento')
                                    $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                  }"
                                  locale='es'
                                  no-title
                                ></v-date-picker>
                              </v-menu>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.numero_identificacion"
                                            label="No. Identificación" key="numero de identificacion"
                                            name="número de identificación"
                                            v-validate="'required|max:20'"
                                            :error-messages="errors.collect('número de identificación')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.edad"
                                            label="Edad" key="identificación accionante"
                                            name="identificación accionante" readonly></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.primer_nombre"
                                            label="Primer nombre" key="primer nombre"
                                            name="primer nombre"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('primer nombre')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-autocomplete
                                label="Lugar de nacimiento"
                                v-model="empleado.lugar_nacimiento"
                                :items="complementos.municipios"
                                item-value="id"
                                item-text="nombre"
                                name="lugar de nacimiento"
                                required
                                v-validate="'required'"
                                :error-messages="errors.collect('lugar de nacimiento')"
                                :filter="filterMunicipios">

                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.nombre"/>
                                      <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                              </v-autocomplete>
                            </v-flex>
                            
                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.segundo_nombre"
                                            label="Segundo nombre" key="segundo nombre"
                                            name="segundo nombre"
                                            :error-messages="errors.collect('segundo nombre')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Sexo"
                                :items="sexo"
                                name="sexo"
                                v-validate="'required'"
                                :error-messages="errors.collect('sexo')"
                                v-model="empleado.sexo"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.primer_apellido"
                                            label="Primer apellido" key="primer apellido"
                                            name="primer apellido"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('primer apellido')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Grupo sanguineo"
                                :items="grupoSanguineo"
                                name="grupo sanguineo"
                                v-validate="'required'"
                                :error-messages="errors.collect('grupo sanguineo')"
                                v-model="empleado.grupo_sanguineo"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.segundo_apellido"
                                            label="Segundo apellido" key="segundo apellido"
                                            name="segundo apellido"
                                            :error-messages="errors.collect('segundo apellido')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="RH"
                                :items="rhs"
                                name="rh"
                                v-validate="'required'"
                                :error-messages="errors.collect('rh')"
                                v-model="empleado.rh"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.direccion"
                                            label="Dirección" key="dirección"
                                            name="dirección"
                                            v-validate="'required|max:200'"
                                            :error-messages="errors.collect('dirección')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Estado civil"
                                :items="estadoCivil"
                                name="estado civil"
                                v-validate="'required'"
                                :error-messages="errors.collect('estado civil')"
                                v-model="empleado.estado_civil"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-autocomplete
                                label="Municipio"
                                v-model="empleado.municipio"
                                :items="complementos.municipios"
                                item-value="id"
                                item-text="nombre"
                                name="municipio"
                                required
                                v-validate="'required'"
                                :error-messages="errors.collect('municipio')"
                                :filter="filterMunicipios">

                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.nombre"/>
                                      <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                              </v-autocomplete>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.nro_hijos"
                                            label="Hijos" key="identificación accionante" type="number"
                                            name="número hijos"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('número hijos')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Vivienda propia"
                                :items="dicotomia"
                                name="vivienda propia"
                                v-validate="'required'"
                                :error-messages="errors.collect('vivienda propia')"
                                v-model="empleado.sw_vivienda_propia"
                              ></v-select>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-autocomplete
                                label="Profesión"
                                v-model="empleado.profesion"
                                :items="complementosProfesiones.profesiones"
                                item-value="profesion"
                                item-text="descripcion"
                                name="profesión"
                                required
                                v-validate="'required'"
                                :error-messages="errors.collect('profesión')"
                                :filter="filterProfesion"> 

                                <template slot="item" slot-scope="data">
                                  <template>
                                    <v-list-tile-content>
                                      <v-list-tile-title v-html="data.item.descripcion"/>
                                    </v-list-tile-content>
                                  </template>
                                </template>
                              </v-autocomplete>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.email"
                                            label="Email" key="email"
                                            name="email" :rules="emailRules"
                                            v-validate="'max:100'"
                                            :error-messages="errors.collect('email')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.talla_camisa"
                                            label="Talla camisa" key="talla camisa"
                                            name="talla camisa"
                                            v-validate="'required|max:10'"
                                            :error-messages="errors.collect('talla camisa')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.telefono"
                                            label="Teléfono" key="teléfono"
                                            name="teléfono"
                                            v-validate="'required|max:20'"
                                            :error-messages="errors.collect('teléfono')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.talla_pantalon"
                                            label="Talla pantalón" key="talla pantalon"
                                            name="talla pantalon"
                                            v-validate="'required|max:10'"
                                            :error-messages="errors.collect('talla pantalon')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.celular"
                                            label="Celular" key="celular"
                                            name="celular"
                                            v-validate="'max:20'"
                                            :error-messages="errors.collect('celular')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6>
                              <v-text-field v-model="empleado.talla_calzado"
                                            label="Talla calzado" key="talla calzado"
                                            name="talla calzado"
                                            v-validate="'required|max:10'"
                                            :error-messages="errors.collect('talla calzado')" ></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6></v-flex>
                            <v-flex xs12 sm6>
                              <v-select
                                item-text="nombre"
                                item-value="nombre"
                                label="Usa gafas"
                                :items="dicotomia"
                                name="usa gafas"
                                v-validate="'required'"
                                :error-messages="errors.collect('usa gafas')"
                                v-model="empleado.sw_usa_gafa"
                              ></v-select>
                            </v-flex>

                          </v-layout>

                        </v-card-text>
                      </v-card>
                    </v-flex>

                    <v-flex xs12 class="pb-4">
                      <v-toolbar dense class="grey lighten-4 elevation-0" v-show="!mostrarDocs">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Documentos </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="mostrarCard(1,'documentos')">
                          <v-icon title="mostrar">remove_red_eye</v-icon>
                        </v-btn>
                      </v-toolbar>
                      <v-card v-show="mostrarDocs">
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Documentos</v-toolbar-title>
                          <v-spacer></v-spacer>
                          <v-btn icon @click="mostrarCard(2,'documentos')">
                            <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
                          </v-btn>
                        </v-toolbar>
                        <v-card-text>
                          <!-- prueba -->
                          <v-layout row wrap>
                            <v-flex xs12 sm6> 
                              <v-card>
                                <v-card-text>
                                  <v-layout row wrap>
                                     <v-flex xs12>
                                        <v-text-field v-model="empleado.libreta_militar"
                                                      label="Libreta militar" key="libreta militar"
                                                      name="libreta militar"
                                                      v-validate="'max:50'"
                                                      :error-messages="errors.collect('libreta militar')" ></v-text-field>
                                        <v-menu
                                          ref="menuDateFechaLibreta"
                                          :close-on-content-click="false"
                                          v-model="menuDateFechaLibreta"
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
                                            v-model="computedDateFormattedFechaExpedicionLibreta"
                                            label="Fecha de expedición"
                                            prepend-icon="event"
                                            readonly
                                            name="fecha de expedicion"
                                            v-validate="'date_format:YYYY/MM/DD'"
                                            :error-messages="errors.collect('fecha de expedicion')"
                                          ></v-text-field>
                                          <v-date-picker
                                            v-model="empleado.fecha_expedicion_libreta"
                                            @input="menuDateFechaLibreta = false"
                                            @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha de expedicion')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                            }"
                                            locale='es'
                                            no-title
                                          ></v-date-picker>
                                        </v-menu>
                                        <v-text-field v-model="empleado.distrito"
                                                      label="Distrito" key="distrito"
                                                      name="distrito"
                                                      v-validate="'max:200'"
                                                      :error-messages="errors.collect('distrito')" ></v-text-field>
                                     </v-flex>
                                  </v-layout>
                                </v-card-text>
                              </v-card>
                            </v-flex>
                            <v-flex xs12 sm6> 
                              <v-card>
                                <v-card-text>
                                  <v-layout row wrap>
                                      <v-flex xs12>
                                        <v-text-field v-model="empleado.certificado_judicial"
                                                      label="Certificado judicial" key="certificado judicial"
                                                      name="certificado judicial"
                                                      v-validate="'max:50'"
                                                      :error-messages="errors.collect('certificado judicial')" ></v-text-field>
                                        <v-menu
                                            ref="menuDateFechaCertificado"
                                            :close-on-content-click="false"
                                            v-model="menuDateFechaCertificado"
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
                                              v-model="computedDateFormattedFechaExpedicionCertificado"
                                              label="Fecha de expedición"
                                              prepend-icon="event"
                                              readonly
                                              name="fecha de expedicion"
                                              v-validate="'date_format:YYYY/MM/DD'"
                                              :error-messages="errors.collect('fecha de expedicion')"
                                            ></v-text-field>
                                            <v-date-picker
                                              v-model="empleado.fecha_expedicion_certificado"
                                              @input="menuDateFechaCertificado = false"
                                              @change="() => {
                                                let index = $validator.errors.items.findIndex(x => x.field === 'fecha de expedicion')
                                                $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                                              locale='es'
                                              no-title
                                            ></v-date-picker>
                                          </v-menu>
                                          <v-menu
                                            ref="menuDateFechaCertificadoVigencia"
                                            :close-on-content-click="false"
                                            v-model="menuDateFechaCertificadoVigencia"
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
                                              v-model="computedDateFormattedFechaVigenciaCertificado"
                                              label="Fecha de vigencia"
                                              prepend-icon="event"
                                              readonly
                                              name="fecha de vigencia"
                                              v-validate="'date_format:YYYY/MM/DD'"
                                              :error-messages="errors.collect('fecha de vigencia')"
                                            ></v-text-field>
                                            <v-date-picker
                                              v-model="empleado.fecha_vigencia_certificado"
                                              @input="menuDateFechaCertificadoVigencia = false"
                                              @change="() => {
                                                let index = $validator.errors.items.findIndex(x => x.field === 'fecha de expedicion')
                                                $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                                              locale='es'
                                              no-title
                                            ></v-date-picker>
                                          </v-menu>
                                     </v-flex>
                                  </v-layout>
                                </v-card-text>
                              </v-card>
                            </v-flex>
                            <v-flex xs12 sm6> 
                              <v-card>
                                <v-card-text>
                                  <v-layout row wrap>
                                     <v-flex xs12>
                                        <v-text-field v-model="empleado.licencia_conduccion"
                                                      label="Licencia conducción" key="licencia conduccion"
                                                      name="licencia conduccion"
                                                      v-validate="'max:50'"
                                                      :error-messages="errors.collect('licencia conduccion')" ></v-text-field>
                                        <v-menu
                                          ref="menuDateFechaLicencia"
                                          :close-on-content-click="false"
                                          v-model="menuDateFechaLicencia"
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
                                            v-model="computedDateFormattedFechaLicencia"
                                            label="Fecha de expedición"
                                            prepend-icon="event"
                                            readonly
                                            name="fecha de expedicion"
                                            v-validate="'date_format:YYYY/MM/DD'"
                                            :error-messages="errors.collect('fecha de expedicion')"
                                          ></v-text-field>
                                          <v-date-picker
                                            v-model="empleado.fecha_expedicion_licencia"
                                            @input="menuDateFechaLicencia = false"
                                            @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha de expedicion')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                            }"
                                            locale='es'
                                            no-title
                                          ></v-date-picker>
                                        </v-menu>
                                        <v-autocomplete
                                          label="Lugar"
                                          v-model="empleado.lugar_expedicion_licencia"
                                          :items="complementos.municipios"
                                          item-value="id"
                                          item-text="nombre"
                                          name="lugar"
                                          required
                                          :error-messages="errors.collect('lugar')"
                                          :filter="filterMunicipios">

                                          <template slot="item" slot-scope="data">
                                            <template>
                                              <v-list-tile-content>
                                                <v-list-tile-title v-html="data.item.nombre"/>
                                                <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                              </v-list-tile-content>
                                            </template>
                                          </template>
                                        </v-autocomplete>
                                        <v-menu
                                          ref="menuDateFechaVigenciaLicencia"
                                          :close-on-content-click="false"
                                          v-model="menuDateFechaVigenciaLicencia"
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
                                            v-model="computedDateFormattedFechaVigenciaLicencia"
                                            label="Vigencia"
                                            prepend-icon="event"
                                            readonly
                                            name="vigencia"
                                            v-validate="'date_format:YYYY/MM/DD'"
                                            :error-messages="errors.collect('vigencia')"
                                          ></v-text-field>
                                          <v-date-picker
                                            v-model="empleado.vigencia"
                                            @input="menuDateFechaVigenciaLicencia = false"
                                            @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'vigencia')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                            }"
                                            locale='es'
                                            no-title
                                          ></v-date-picker>
                                        </v-menu>
                                        <v-text-field v-model="empleado.categoria"
                                                      label="Categoría" key="categoria"
                                                      name="categoria"
                                                      v-validate="'max:10'"
                                                      :error-messages="errors.collect('categoria')" ></v-text-field>
                                     </v-flex>
                                  </v-layout>
                                </v-card-text>
                              </v-card>
                            </v-flex>

                            <v-flex xs12 sm6> 
                              <v-card>
                                <v-card-text>
                                  <v-layout row wrap>
                                      <v-flex xs12>
                                        <v-text-field v-model="empleado.pasaporte"
                                                      label="Pasaporte número" key="identificación accionante"
                                                      name="pasaporte"
                                                      v-validate="'max:50'"
                                                      :error-messages="errors.collect('pasaporte')" ></v-text-field>
                                        <v-menu
                                          ref="menuDateFechaVigenciaPasaporte"
                                          :close-on-content-click="false"
                                          v-model="menuDateFechaVigenciaPasaporte"
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
                                            v-model="computedDateFormattedFechaVigenciaPasaporte"
                                            label="Vigencia"
                                            prepend-icon="event"
                                            readonly
                                            name="fecha vigencia pasaporte"
                                            v-validate="'date_format:YYYY/MM/DD'"
                                            :error-messages="errors.collect('fecha vigencia pasaporte')"
                                          ></v-text-field>
                                          <v-date-picker
                                            v-model="empleado.fecha_vigencia_pasaporte"
                                            @input="menuDateFechaVigenciaPasaporte = false"
                                            @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha vigencia pasaporte')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                            }"
                                            locale='es'
                                            no-title
                                          ></v-date-picker>
                                        </v-menu>
                                     </v-flex>
                                  </v-layout>
                                </v-card-text>
                              </v-card>
                              <v-layout row wrap>
                                <v-flex xs12>
                                  <v-col cols="12" md="6">
                                    <v-textarea
                                      solo
                                      v-model="empleado.observacion"
                                      name="input-7-4"
                                      label="Observaciones"
                                      rows="9"
                                    ></v-textarea>
                                  </v-col>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            
                          </v-layout>
                          <!-- prueba -->
                        </v-card-text>
                      </v-card>
                    </v-flex>
                </v-form>
              </v-card>
            </v-tab-item>
            <!-- Estudios y grupo familiar -->
            <v-tab-item>
              <v-card flat v-show="!mostrarEducacion">
                <v-card-text>
                  <v-toolbar dense short flat>
                    <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    <v-title>Estudios</v-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="mostrarCard(1,'educacion')">
                      <v-icon title="mostrar">remove_red_eye</v-icon>
                    </v-btn>
                  </v-toolbar>
                </v-card-text>
              </v-card>
              <v-card flat>
                <v-card-text>
                  <v-toolbar v-show="mostrarEducacion && !mostrarTablaEducacion" dense short flat>
                    <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    <v-title>Estudios</v-title>
                    <v-spacer></v-spacer>
                    <v-btn icon>
                      <v-icon @click="dialog = true">add</v-icon>
                    </v-btn>
                    <v-btn icon @click="mostrarCard(2,'educacion')">
                      <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
                    </v-btn>
                  </v-toolbar>
                  <v-card v-show="mostrarEducacion && !mostrarTablaEducacion" >
                    <v-card-text>
                      <v-data-table
                        :headers="headersEducacion"
                        :items="dataEducacion.estudio"
                        :loading="tableLoading"
                        :total-items="dataEducacion.length"
                        hide-actions
                        class="elevation-1">
                        <template slot="items" slot-scope="props">
                          <td>{{ props.item.establecimiento}}</td>
                          <td>{{ props.item.titulo}}</td>
                          <td>{{ props.item.fecha}}</td>
                          <td>
                            <v-speed-dial
                              v-model="props.item.show"
                              direction="left"
                              open-on-hover
                              transition="slide-x-transition"
                            >
                              <v-btn
                                slot="activator"
                                v-model="props.item.show"
                                color="blue darken-2"
                                dark
                                fab
                                small
                              >
                                <v-icon>chevron_left</v-icon>
                                <v-icon>close</v-icon>
                              </v-btn>
                              <v-tooltip top>
                                <v-btn
                                  fab
                                  dark
                                  small
                                  color="white"
                                  slot="activator"
                                  @click="eliminarEducacion(props.index)"
                                >
                                  <v-icon color="accent">delete</v-icon>
                                </v-btn>
                                <span>Eliminar</span>
                              </v-tooltip>
                              <v-tooltip top>
                                <v-btn
                                  fab
                                  dark
                                  small
                                  color="white"
                                  slot="activator"
                                  @click="editarEducacion(props.item, props.index)"
                                >
                                  <v-icon color="accent">mode_edit</v-icon>
                                </v-btn>
                                <span>Editar</span>
                              </v-tooltip>
                            </v-speed-dial>
                          </td>
                        </template>
                        <template slot="no-data">
                          <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                            No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                          </v-alert>
                          <v-alert v-else :value="true" color="info" icon="info">
                            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                          </v-alert>
                        </template>
                      </v-data-table>
                    </v-card-text>
                  </v-card>

                  <EmpleadoEstudio :empleado="empleadoID" v-show="mostrarTablaEducacion"></EmpleadoEstudio>

                  <v-layout row wrap>
                    <v-flex xs12> 
                      <v-card>
                        <v-card-text>
                          <v-layout row wrap>
                              <v-flex xs12>
                                <v-text-field v-model="empleado.nombre_conyuge"
                                              label="Nombre cónyuge" key="nombre conyuge"
                                              name="nombre conyuge"
                                              :error-messages="errors.collect('nombre conyuge')" ></v-text-field>
                                 <v-menu
                                  ref="menuDateFechaConyuge"
                                  :close-on-content-click="false"
                                  v-model="menuDateFechaConyuge"
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
                                    v-model="computedDateFormattedFechaConyuge"
                                    label="Fecha de nacimiento"
                                    prepend-icon="event"
                                    readonly
                                    name="fecha de nacimiento conyuge"
                                    v-validate="'date_format:YYYY/MM/DD'"
                                    :error-messages="errors.collect('fecha de nacimiento conyuge')"
                                  ></v-text-field>
                                  <v-date-picker
                                    v-model="empleado.fecha_nacimiento_conyuge"
                                    @input="menuDateFechaConyuge = false"
                                    @change="() => {
                                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha de nacimiento conyuge')
                                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                    }"
                                    locale='es'
                                    no-title
                                  ></v-date-picker>
                                </v-menu>
                                <v-text-field v-model="empleado.edadconyuge"
                                              label="Edad" key="identificación accionante" type="number"
                                              name="edad_C"
                                              :error-messages="errors.collect('edad_C')" ></v-text-field>
                                <v-text-field v-model="empleado.telefono_conyuge"
                                              label="Teléfono contacto" key="telefono conyuge"
                                              name="telefono conyuge"
                                              v-validate="'max:200'"
                                              :error-messages="errors.collect('telefono conyuge')" ></v-text-field>
                                <v-text-field v-model="empleado.empresa_conyuge"
                                              label="Empresa donde trabaja" key="empresa conyuge"
                                              name="empresa conyuge"
                                              v-validate="'max:200'"
                                              :error-messages="errors.collect('empresa conyuge')" ></v-text-field>
                                <v-menu
                                  ref="menuDateFechaIngresoConyuge"
                                  :close-on-content-click="false"
                                  v-model="menuDateFechaIngresoConyuge"
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
                                    v-model="computedDateFormattedFechaIngresoConyuge"
                                    label="Fecha ingreso"
                                    prepend-icon="event"
                                    readonly
                                    name="fecha de ingreso conyuge"
                                    v-validate="'date_format:YYYY/MM/DD'"
                                    :error-messages="errors.collect('fecha de ingreso conyuge')"
                                  ></v-text-field>
                                  <v-date-picker
                                    v-model="empleado.fecha_ingreso_conyuge"
                                    @input="menuDateFechaIngresoConyuge = false"
                                    @change="() => {
                                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha de ingreso conyuge')
                                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                    }"
                                    locale='es'
                                    no-title
                                  ></v-date-picker>
                                </v-menu>
                                <v-text-field v-model="empleado.cargo_actual_conyuge"
                                              label="Cargo" key="cargo conyuge"
                                              name="cargo conyuge"
                                              v-validate="'max:50'"
                                              :error-messages="errors.collect('cargo conyuge')" ></v-text-field>
                              </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                    </v-flex>
                  </v-layout>

                  <v-toolbar v-show="!mostrarFamilia" dense short flat>
                    <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    <v-title>Grupo familiar</v-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="mostrarCard(1,'familia')">
                      <v-icon title="mostrar">remove_red_eye</v-icon>
                    </v-btn>
                  </v-toolbar>
                  <v-card flat>
                    <v-toolbar v-show="mostrarFamilia && !mostrarTablaEducacion" dense short flat>
                      <v-app-bar-nav-icon></v-app-bar-nav-icon>
                      <v-title>Grupo familiar</v-title>
                      <v-spacer></v-spacer>
                      <v-btn icon>
                        <v-icon @click="dialog2 = true">add</v-icon>
                      </v-btn>
                      <v-btn icon @click="mostrarCard(2,'familia')">
                        <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <v-card v-show="mostrarFamilia && !mostrarTablaEducacion" >
                      <v-card-text>
                        <v-data-table
                          :headers="headersFamilia"
                          :items="dataFamilia.familia"
                          :loading="tableLoading"
                          :total-items="dataFamilia.familia.length"
                          hide-actions
                          class="elevation-1">
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item.parentesco}}</td>
                            <td>{{ props.item.grado}}</td>
                            <td>{{ props.item.nombre}}</td>
                            <td>{{ props.item.direccion}}</td>
                            <td>{{ props.item.telefono}}</td>
                            <td>{{ props.item.fecha_nacimiento }}</td>
                            <td>
                              <v-speed-dial
                                v-model="props.item.show"
                                direction="left"
                                open-on-hover
                                transition="slide-x-transition"
                              >
                                <v-btn
                                  slot="activator"
                                  v-model="props.item.show"
                                  color="blue darken-2"
                                  dark
                                  fab
                                  small
                                >
                                  <v-icon>chevron_left</v-icon>
                                  <v-icon>close</v-icon>
                                </v-btn>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="eliminarFamilia(props.index)"
                                  >
                                    <v-icon color="accent">delete</v-icon>
                                  </v-btn>
                                  <span>Eliminar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="editarFamilia(props.item, props.index)"
                                  >
                                    <v-icon color="accent">mode_edit</v-icon>
                                  </v-btn>
                                  <span>Editar</span>
                                </v-tooltip>
                              </v-speed-dial>
                            </td>
                          </template>
                          <template slot="no-data">
                            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                              No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                            </v-alert>
                            <v-alert v-else :value="true" color="info" icon="info">
                              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                            </v-alert>
                          </template>
                        </v-data-table>
                      </v-card-text>
                    </v-card>
                    <EmpleadoFamilia :empleado="empleadoID" v-show="mostrarTablaEducacion"></EmpleadoFamilia>
                  </v-card>

                </v-card-text>
              </v-card>
            </v-tab-item>
            <!-- Información laboral -->
            <v-tab-item>
              <v-card flat v-show="!mostrarLaboral">
                <v-card-text>
                  <v-toolbar dense short flat>
                    <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    <v-title>Experiencia laboral</v-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="mostrarCard(1,'laboral')">
                      <v-icon title="mostrar">remove_red_eye</v-icon>
                    </v-btn>
                  </v-toolbar>
                </v-card-text>
              </v-card>
              <v-card flat>
                <v-card-text>
                  <v-card flat>
                    <v-toolbar v-show="mostrarLaboral && !mostrarTablaEducacion" dense short flat>
                      <v-app-bar-nav-icon></v-app-bar-nav-icon>
                      <v-title>Experiencia laboral</v-title>
                      <v-spacer></v-spacer>
                      <v-btn icon>
                        <v-icon @click="dialog3 = true">add</v-icon>
                      </v-btn>
                      <v-btn icon @click="mostrarCard(2,'laboral')">
                        <v-icon color="green" title="ocultar">remove_red_eye</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <v-card v-show="mostrarLaboral && !mostrarTablaEducacion" >
                      <v-card-text>
                        <v-data-table
                          :headers="headersInfoLaboral"
                          :items="dataExperienciaLaboral.laboral"
                          :loading="tableLoading"
                          :total-items="dataExperienciaLaboral.length"
                          hide-actions
                          class="elevation-1">
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item.empresa}}</td>
                            <td>{{ props.item.cargo}}</td>
                            <td>{{ props.item.fecha_ingreso}}</td>
                            <td>{{ props.item.fecha_retiro}}</td>
                            <td>{{ props.item.direccion}}</td>
                            <td>{{ props.item.telefono }}</td>
                            <td>
                              <v-speed-dial
                                v-model="props.item.show"
                                direction="left"
                                open-on-hover
                                transition="slide-x-transition"
                              >
                                <v-btn
                                  slot="activator"
                                  v-model="props.item.show"
                                  color="blue darken-2"
                                  dark
                                  fab
                                  small
                                >
                                  <v-icon>chevron_left</v-icon>
                                  <v-icon>close</v-icon>
                                </v-btn>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="eliminarExperiencia(props.index)"
                                  >
                                    <v-icon color="accent">delete</v-icon>
                                  </v-btn>
                                  <span>Eliminar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    dark
                                    small
                                    color="white"
                                    slot="activator"
                                    @click="editarExperiencia(props.item, props.index)"
                                  >
                                    <v-icon color="accent">mode_edit</v-icon>
                                  </v-btn>
                                  <span>Editar</span>
                                </v-tooltip>
                              </v-speed-dial>
                            </td>
                          </template>
                          <template slot="no-data">
                            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                              No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                            </v-alert>
                            <v-alert v-else :value="true" color="info" icon="info">
                              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                            </v-alert>
                          </template>
                        </v-data-table>
                      </v-card-text>
                    </v-card>
                    <EmpleadoLaboral :empleado="empleadoID" v-show="mostrarTablaEducacion"></EmpleadoLaboral>
                  </v-card>
                  
                  <v-layout row wrap>
                    <v-flex xs12 sm6 align="center"> 
                      <v-form data-vv-scope="formEmpleadosSC">
                      <v-card>
                        <v-card-text>
                          <v-layout row wrap>
                              <v-flex xs12>
                                 <v-autocomplete
                                  label="Entidad financiera"
                                  v-model="empleado.entidad_financiera"
                                  :items="complementosFondosEntidades.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="entidad financiera"
                                  :error-messages="errors.collect('entidad financiera')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                        <v-list-tile-sub-title v-html="data.item.profesion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                                <v-autocomplete
                                  label="Municipio"
                                  v-model="empleado.municipio_entidad"
                                  :items="complementos.municipios"
                                  item-value="id"
                                  item-text="nombre"
                                  name="municipio entidad"
                                  :error-messages="errors.collect('municipio entidad')"
                                  :filter="filterMunicipios">

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.nombre"/>
                                        <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                                <v-select
                                  item-text="nombre"
                                  item-value="nombre"
                                  label="Clase cuenta"
                                  :items="cuenta"
                                  name="clase cuenta"
                                  v-validate="'required'"
                                  :error-messages="errors.collect('clase cuenta')"
                                  v-model="empleado.clase_cuenta"
                                ></v-select>
                                <v-text-field v-model="empleado.numero_cuenta"
                                              label="Número" key="numero cuenta"
                                              name="numero cuenta"
                                              v-validate="'max:50'"
                                              :error-messages="errors.collect('numero cuenta')" ></v-text-field>
                              </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                      </v-form>
                    </v-flex>
                    <v-flex xs12 sm6> 
                      <v-form data-vv-scope="formEmpleadosSC">
                      <v-card>
                        <v-card-text>
                          <v-layout row wrap>
                              <v-flex xs12>
                                <v-autocomplete
                                  label="Pensiones"
                                  v-model="empleado.pension"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="pensiones"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('pensiones')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                                <v-autocomplete
                                  label="Cesantías"
                                  v-model="empleado.cesantia"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="cesantías"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('cesantías')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                                <v-autocomplete
                                  label="EPS - Salud"
                                  v-model="empleado.eps"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="eps"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('eps')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>

                                <v-autocomplete
                                  label="ARP - Riesgos"
                                  v-model="empleado.arp"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="arp"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('arp')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>

                                <v-autocomplete
                                  label="Fondo aportes"
                                  v-model="empleado.fondo_aporte"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="fondo aporte"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('fondo aporte')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>

                                <v-autocomplete
                                  label="Caja compensasión"
                                  v-model="empleado.caja"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="caja compensasión"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('caja compensasión')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>

                                <v-autocomplete
                                  label="ICBF"
                                  v-model="empleado.icbf"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="icbf"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('icbf')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>

                                <v-autocomplete
                                  label="SENA"
                                  v-model="empleado.sena"
                                  :items="complementosFondos.fondosEntidades"
                                  item-value="fondo"
                                  item-text="descripcion"
                                  name="sena"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('sena')"
                                  :filter="filterBancos"> 

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.descripcion"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                                <v-select
                                  item-text="nombre"
                                  item-value="nombre"
                                  label="Aplica nómina"
                                  :items="dicotomia"
                                  name="aplica nómina"
                                  v-validate="'required'"
                                  :error-messages="errors.collect('aplica nómina')"
                                  v-model="empleado.sw_nomina"
                                ></v-select>
                              </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                      </v-form>
                    </v-flex>
                  </v-layout>

                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-container>
      <!-- end --> 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {EMPLEADO_SC_RELOAD_ITEM} from '@/store/modules/general/tables'
  import moment from 'moment'
  export default {
    name: 'RegistroEmpleado',
    components: {
      EmpleadoEstudio: () => import('@/components/administrativo/TalentoHumano/Empleado/EmpleadoEstudio'),
      EmpleadoFamilia: () => import('@/components/administrativo/TalentoHumano/Empleado/EmpleadoFamilia'),
      EmpleadoLaboral: () => import('@/components/administrativo/TalentoHumano/Empleado/EmpleadoLaboral')
    },
    props: ['parametros'],
    data () {
      return {
        empleadoID: null,
        dialog: false,
        dialog2: false,
        dialog3: false,
        tableLoading: false,
        i: null, // para editar un elemento en las tablas de experiencia labora, familia y educación
        headersEducacion: [
          {
            text: 'Establecimiento educativo',
            align: 'left',
            sortable: false,
            value: 'establecimiento educativo'
          },
          {
            text: 'Título obtenido',
            align: 'left',
            sortable: false,
            value: 'titulo obtenido'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        headersInfoLaboral: [
          {
            text: 'Empresa',
            align: 'left',
            sortable: false,
            value: 'empresa'
          },
          {
            text: 'Cargo',
            align: 'left',
            sortable: false,
            value: 'cargo'
          },
          {
            text: 'F.Ingreso',
            align: 'left',
            sortable: false,
            value: 'fecha_ingreso'
          },
          {
            text: 'F.Retiro',
            align: 'left',
            sortable: false,
            value: 'fecha_retiro'
          },
          {
            text: 'Dirección',
            align: 'left',
            sortable: false,
            value: 'direccion'
          },
          {
            text: 'Teléfono',
            align: 'left',
            sortable: false,
            value: 'telefono'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        headersFamilia: [
          {
            text: 'Parentesco',
            align: 'left',
            sortable: false,
            value: 'parentesco'
          },
          {
            text: 'Grado',
            align: 'left',
            sortable: false,
            value: 'grado'
          },
          {
            text: 'Nombres y apellidos',
            align: 'left',
            sortable: false,
            value: 'nombres'
          },
          {
            text: 'Dirección',
            align: 'left',
            sortable: false,
            value: 'direccion'
          },
          {
            text: 'Teléfono',
            align: 'left',
            sortable: false,
            value: 'telefono'
          },
          {
            text: 'F.Nacimiento',
            align: 'left',
            sortable: false,
            value: 'fecha_nacimiento'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        tipoDoc: [
          {
            tipo_identificacion: '1',
            nombre: 'CC'
          },
          {
            tipo_identificacion: '2',
            nombre: 'Nit'
          },
          {
            tipo_identificacion: '3',
            nombre: 'Tipo documento extranjero VAT'
          }
        ],
        sexo: [
          {
            sexo: '1',
            nombre: 'Masculino'
          },
          {
            sexo: '2',
            nombre: 'Feminino'
          }
        ],
        rhs: [
          {
            rh: '1',
            nombre: 'Positivo'
          },
          {
            rh: '2',
            nombre: 'Negativo'
          }
        ],
        grupoSanguineo: [
          {
            grupo_sanguineo: '1',
            nombre: 'A'
          },
          {
            grupo_sanguineo: '2',
            nombre: 'B'
          },
          {
            grupo_sanguineo: '3',
            nombre: 'AB'
          },
          {
            grupo_sanguineo: '4',
            nombre: 'O'
          }
        ],
        estadoCivil: [
          {
            estado_civil: '1',
            nombre: 'Soltero'
          },
          {
            estado_civil: '2',
            nombre: 'Casado'
          }
        ],
        dicotomia: [
          {
            value: '1',
            nombre: 'Si'
          },
          {
            value: '2',
            nombre: 'No'
          }
        ],
        parentescos: [
          {
            value: '1',
            nombre: 'Hijo'
          },
          {
            value: '2',
            nombre: 'Padre'
          },
          {
            value: '3',
            nombre: 'Hermano'
          },
          {
            value: '4',
            nombre: 'Abuelo'
          },
          {
            value: '5',
            nombre: 'Primo'
          },
          {
            value: '6',
            nombre: 'Otros'
          }
        ],
        cuenta: [
          {
            clase_cuenta: '1',
            nombre: 'Ahorro'
          },
          {
            clase_cuenta: '2',
            nombre: 'Corriente'
          }
        ],
        emailRules: [
          // v => !!v || 'el Email es requerido',
          v => /.+@.+/.test(v) || 'el Email debe ser valido'
        ],
        tabs: null,
        menuDateInicio: false,
        menuDateFechaLibreta: false,
        menuDateFechaCertificado: false,
        menuDateFechaCertificadoVigencia: false,
        menuDateFechaLicencia: false,
        menuDateFechaVigenciaLicencia: false,
        menuDateFechaVigenciaPasaporte: false,
        menuDateFechaConyuge: false,
        menuDateFechaIngresoConyuge: false,
        menuDateFechaEducacion: false,
        menuDateFechaFamiliar: false,
        menuDateFechaIngreso: false,
        menuDateFechaRetiro: false,
        dataExperienciaLaboral: { laboral: [] },
        dataEducacion: { estudio: [] },
        dataFamilia: { familia: [] },
        empleado: {},
        educacion: [],
        familia: [],
        experienciaLaboral: [],
        mostrar: true,
        mostrarDocs: true,
        mostrarEducacion: true,
        mostrarLaboral: true,
        mostrarFamilia: true,
        mostrarTablaEducacion: false,
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterBancos (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion + ' ' + item.fondo)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterProfesion (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion + ' ' + item.profesion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        buscandoCodigo: false,
        stepActual: 1,
        fechaActual: '2018-01-01',
        tabActive: null,
        ordenEdit: false,
        loadingSubmit: false,
        gruposEmpleados: [],
        subGruposEmpleados: [],
        motivosRetiro: [],
        cargos: [],
        gradosProfesional: [],
        fondosDeSalud: [],
        fondosDeCesantia: []
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.empleado)
    },
    created () {
      this.getID()
    },
    watch: {
      'empleado.fecha_nacimiento' () {
        var nacimiento = moment(this.empleado.fecha_nacimiento)
        var hoy = moment()
        this.empleado.edad = hoy.diff(nacimiento, 'years')
      },
      'empleado.fecha_nacimiento_conyuge' (val) {
        var nacimiento = moment(this.empleado.fecha_nacimiento_conyuge)
        var hoy = moment()
        this.empleado.edadconyuge = hoy.diff(nacimiento, 'years')
      },
      'familia.parentesco' () {
        if (this.familia.parentesco === 'Padre' || this.familia.parentesco === 'Hijo') {
          this.familia.grado = 'Primero'
        } else if (this.familia.parentesco === 'Hermano' || this.familia.parentesco === 'Abuelo') {
          this.familia.grado = 'Segundo'
        } else if (this.familia.parentesco === 'Primo') {
          this.familia.grado = 'Cuarto'
        } else if (this.familia.parentesco === 'Otros') {
          this.familia.grado = 'Tercero'
        }
      }
    },
    filers: {
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      computedDateFormattedFechaNacimiento () {
        return this.formDate(this.empleado.fecha_nacimiento)
      },
      computedDateFormattedFechaExpedicionLibreta () {
        return this.formDate(this.empleado.fecha_expedicion_libreta)
      },
      computedDateFormattedFechaExpedicionCertificado () {
        return this.formDate(this.empleado.fecha_expedicion_certificado)
      },
      computedDateFormattedFechaVigenciaCertificado () {
        return this.formDate(this.empleado.fecha_vigencia_certificado)
      },
      computedDateFormattedFechaLicencia () {
        return this.formDate(this.empleado.fecha_expedicion_licencia)
      },
      computedDateFormattedFechaVigenciaLicencia () {
        return this.formDate(this.empleado.vigencia)
      },
      computedDateFormattedFechaVigenciaPasaporte () {
        return this.formDate(this.empleado.fecha_vigencia_pasaporte)
      },
      computedDateFormattedFechaConyuge () {
        return this.formDate(this.empleado.fecha_nacimiento_conyuge)
      },
      computedDateFormattedFechaIngresoConyuge () {
        return this.formDate(this.empleado.fecha_ingreso_conyuge)
      },
      computedDateFormattedFechaEducacion () {
        return this.formDate(this.educacion.fecha)
      },
      computedDateFormattedFechaFamiliar () {
        return this.formDate(this.familia.fecha_nacimiento)
      },
      computedDateFormattedFechaIngreso () {
        return this.formDate(this.experienciaLaboral.fecha_ingreso)
      },
      computedDateFormattedFechaRetiro () {
        return this.formDate(this.experienciaLaboral.fecha_retiro)
      },
      complementosProfesiones () {
        return JSON.parse(JSON.stringify(store.getters.complementosProfesion))
      },
      complementosFondosEntidades () {
        return JSON.parse(JSON.stringify(store.getters.complementosFondosEntidades))
      },
      complementosFondos () {
        return JSON.parse(JSON.stringify(store.getters.complementosFondosF))
      }
    },
    methods: {
      getID () {
        this.empleadoID = this.parametros.entidad.empleado
      },
      mostrarCard (value, strg) {
        if (value === 1 && strg === 'personal') {
          this.mostrar = true
        } else if (value === 2 && strg === 'personal') {
          this.mostrar = false
        } else if (value === 1 && strg === 'documentos') {
          this.mostrarDocs = true
        } else if (value === 2 && strg === 'documentos') {
          this.mostrarDocs = false
        } else if (value === 1 && strg === 'educacion') {
          this.mostrarEducacion = true
        } else if (value === 2 && strg === 'educacion') {
          this.mostrarEducacion = false
        } else if (value === 1 && strg === 'laboral') {
          this.mostrarLaboral = true
        } else if (value === 2 && strg === 'laboral') {
          this.mostrarLaboral = false
        } else if (value === 1 && strg === 'familia') {
          this.mostrarFamilia = true
        } else if (value === 2 && strg === 'familia') {
          this.mostrarFamilia = false
        }
      },
      saveFamilia () {
        if (this.i !== '' && this.i !== null) {
          // this.dataFamilia.familia[this.i] = Object.assign({}, this.familia)
          this.dataFamilia.familia[this.i].parentesco = this.familia.parentesco
          this.dataFamilia.familia[this.i].grado = this.familia.grado
          this.dataFamilia.familia[this.i].nombre = this.familia.nombre
          this.dataFamilia.familia[this.i].direccion = this.familia.direccion
          this.dataFamilia.familia[this.i].telefono = this.familia.telefono
          this.dataFamilia.familia[this.i].fecha_nacimiento = this.familia.fecha_nacimiento
        } else {
          this.dataFamilia.familia.push(this.familia)
        }
        this.formTableReset()
      },
      saveExperiencia () {
        if (this.i !== '' && this.i !== null) {
          // this.dataExperienciaLaboral.laboral[this.i] = Object.assign({}, this.experienciaLaboral)
          this.dataExperienciaLaboral.laboral[this.i].empresa = this.experienciaLaboral.empresa
          this.dataExperienciaLaboral.laboral[this.i].cargo = this.experienciaLaboral.cargo
          this.dataExperienciaLaboral.laboral[this.i].fecha_ingreso = this.experienciaLaboral.fecha_ingreso
          this.dataExperienciaLaboral.laboral[this.i].fecha_retiro = this.experienciaLaboral.fecha_retiro
          this.dataExperienciaLaboral.laboral[this.i].direccion = this.experienciaLaboral.direccion
          this.dataExperienciaLaboral.laboral[this.i].telefono = this.experienciaLaboral.telefono
        } else {
          this.dataExperienciaLaboral.laboral.push(this.experienciaLaboral)
        }
        this.formTableReset()
      },
      saveEducacion () {
        if (this.i !== '' && this.i !== null) {
          // this.dataEducacion.estudio[this.i] = Object.assign({}, this.educacion)
          this.dataEducacion.estudio[this.i].establecimiento = this.educacion.establecimiento
          this.dataEducacion.estudio[this.i].titulo = this.educacion.titulo
          this.dataEducacion.estudio[this.i].fecha = this.educacion.fecha
        } else {
          this.dataEducacion.estudio.push(this.educacion)
        }
        this.formTableReset()
      },
      eliminarFamilia (item) {
        this.dataFamilia.familia.splice(item, 1)
      },
      eliminarExperiencia (item) {
        this.dataExperienciaLaboral.laboral.splice(item, 1)
      },
      eliminarEducacion (item) {
        this.dataEducacion.estudio.splice(item, 1)
      },
      editarFamilia (item, index) {
        this.dialog2 = true
        this.familia = Object.assign({}, item)
        this.i = index
      },
      editarExperiencia (item, index) {
        this.dialog3 = true
        this.experienciaLaboral = Object.assign({}, item)
        this.i = index
      },
      editarEducacion (item, index) {
        this.dialog = true
        this.educacion = Object.assign({}, item)
        this.i = index
      },
      closeTable () {
        this.dialog = false
        this.dialog2 = false
        this.dialog3 = false
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.educacion = {
          establecimiento: null,
          titulo: null,
          fecha: null
        }
        this.experienciaLaboral = {
          empresa: null,
          cargo: null,
          fecha_ingreso: null,
          fecha_retiro: null,
          direccion: null,
          telefono: null
        }
        this.familia = {
          parentesco: null,
          grado: null,
          nombre: null,
          direccion: null,
          telefono: null,
          fecha_nacimiento: null
        }
        this.dialog = false
        this.dialog2 = false
        this.dialog3 = false
      },
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('empleadosc/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.empleado = response.data.data
              this.dataEducacion.estudio = response.data.educacion
              this.dataExperienciaLaboral.laboral = response.data.laboral
              this.dataFamilia.familia = response.data.familia
              this.mostrarTablaEducacion = true
              let nacimiento1 = moment(this.empleado.fecha_nacimiento)
              let hoy1 = moment()
              this.empleado.edad = hoy1.diff(nacimiento1, 'years')

              let nacimiento2 = moment(this.empleado.fecha_nacimiento_conyuge)
              let hoy2 = moment()
              this.empleado.edadconyuge = hoy2.diff(nacimiento2, 'years')

              for (let i = 0; i < this.dicotomia.length; i++) {
                const element = this.dicotomia[i]
                if (this.empleado.sw_vivienda_propia.toString() === element.value) this.empleado.sw_vivienda_propia = element.nombre
                if (this.empleado.sw_usa_gafa.toString() === element.value) this.empleado.sw_usa_gafa = element.nombre
                if (this.empleado.sw_nomina.toString() === element.value) this.empleado.sw_nomina = element.nombre
              }
              for (let i = 0; i < this.tipoDoc.length; i++) {
                const element = this.tipoDoc[i]
                if (this.empleado.tipo_identificacion.toString() === element.tipo_identificacion) this.empleado.tipo_identificacion = element.nombre
              }
              for (let i = 0; i < this.sexo.length; i++) {
                const element = this.sexo[i]
                if (this.empleado.sexo.toString() === element.sexo) this.empleado.sexo = element.nombre
              }
              for (let i = 0; i < this.grupoSanguineo.length; i++) {
                const element = this.grupoSanguineo[i]
                if (this.empleado.grupo_sanguineo.toString() === element.grupo_sanguineo) this.empleado.grupo_sanguineo = element.nombre
              }
              for (let i = 0; i < this.rhs.length; i++) {
                const element = this.rhs[i]
                if (this.empleado.rh.toString() === element.rh) this.empleado.rh = element.nombre
              }
              for (let i = 0; i < this.estadoCivil.length; i++) {
                const element = this.estadoCivil[i]
                if (this.empleado.estado_civil.toString() === element.estado_civil) this.empleado.estado_civil = element.nombre
              }
              for (let i = 0; i < this.cuenta.length; i++) {
                const element = this.cuenta[i]
                if (this.empleado.clase_cuenta.toString() === element.clase_cuenta) this.empleado.clase_cuenta = element.nombre
              }
              for (let i = 0; i < this.dataFamilia.familia.length; i++) {
                // const element = this.dataFamilia.familia[i]
                for (let j = 0; j < this.parentescos.length; j++) {
                  const array = this.parentescos[j]
                  if (this.dataFamilia.familia[i].parentesco.toString() === array.value) this.dataFamilia.familia[i].parentesco = array.nombre
                }
              }
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formEmpleadosSC')) {
          this.loadingSubmit = true
          this.empleado = [].concat(this.empleado, this.dataFamilia, this.dataExperienciaLaboral, this.dataEducacion) // concatenar array empleado, familia, laboral, educacion
          if (this.empleado[0].empleado) {
            this.axios.put('empleadosc/actualizar/' + this.empleado[0].empleado, this.empleado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El empleado se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(EMPLEADO_SC_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('empleadosc/crear', this.empleado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El empleado se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(EMPLEADO_SC_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      formReset () {
        this.mostrarTablaEducacion = false
        this.empleadoID = null
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
</style>
