<template>
  <div>
    <v-dialog v-model="dialog" width="1200px">
      <v-card>
        <v-card-title class="grey lighten-3 elevation-0">
          <span class="title">Detalle aportes</span>
        </v-card-title>
        <v-container fluid grid-list-md>
          <v-layout row wrap>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Base cotización</v-list-tile-sub-title>
                <span>{{ aporte.base_cotizacion }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Cod. Dep. Laboral</v-list-tile-sub-title>
                <span>{{ aporte.cod_dep_laboral }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Cod. Mun. Laboral</v-list-tile-sub-title>
                <span>{{ aporte.cod_mun_laboral }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Col. Exterior</v-list-tile-sub-title>
                <span>{{ aporte.col_exterior }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Correcciones</v-list-tile-sub-title>
                <span>{{ aporte.correcciones }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Cotizacion Obligatoria</v-list-tile-sub-title>
                <span>{{ aporte.cotizacion_obligatoria }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Pila Id</v-list-tile-sub-title>
                <span>{{ aporte.cp_pila_id }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Días Cotizados</v-list-tile-sub-title>
                <span>{{ aporte.dias_cotizados }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Exonerado Pago Salud</v-list-tile-sub-title>
                <span>{{ aporte.exonerado_pago_salud }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Extranjero No Oblig</v-list-tile-sub-title>
                <span>{{ aporte.extranjero_no_oblig }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha_Ingreso</v-list-tile-sub-title>
                <span>{{ aporte.fecha_ingreso }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Retiro</v-list-tile-sub-title>
                <span>{{ aporte.fecha_retiro }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio IGE</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_ige }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin IGE</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_ige }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio IRL</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_irl }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin IRL</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_irl }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio LMA</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_lma }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin LMA</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_lma }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio SLN</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_sln }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin SLN</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_sln }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio VAC_LR</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_vac_lr }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin VAC_LR</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_vac_lr }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio VCT</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_vct }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Fin VCT</v-list-tile-sub-title>
                <span>{{ aporte.fecha_fin_vct }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Inicio VSP</v-list-tile-sub-title>
                <span>{{ aporte.fecha_inicio_vsp }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Identificacion Cotizante</v-list-tile-sub-title>
                <span>{{ aporte.identificacion_cotizante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">IGE</v-list-tile-sub-title>
                <span>{{ aporte.ige }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Ingreso</v-list-tile-sub-title>
                <span>{{ aporte.ingreso }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">LMA</v-list-tile-sub-title>
                <span>{{ aporte.lma }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Primer Nombre</v-list-tile-sub-title>
                <span>{{ aporte.primer_nombre }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Segundo Nombre</v-list-tile-sub-title>
                <span>{{ aporte.segundo_nombre }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Primer Apellido</v-list-tile-sub-title>
                <span>{{ aporte.primer_apellido }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Segundo Apellido</v-list-tile-sub-title>
                <span>{{ aporte.segundo_apellido }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Retiro</v-list-tile-sub-title>
                <span>{{ aporte.retiro }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Salario Basico</v-list-tile-sub-title>
                <span>{{ aporte.salario_basico }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Salario Integral</v-list-tile-sub-title>
                <span>{{ aporte.salario_integral }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Secuencia</v-list-tile-sub-title>
                <span>{{ aporte.secuencia }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">SLN</v-list-tile-sub-title>
                <span>{{ aporte.sln }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Subtipo Cotizante</v-list-tile-sub-title>
                <span>{{ aporte.subtipo_cotizante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">TAE</v-list-tile-sub-title>
                <span>{{ aporte.tae }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tarifa</v-list-tile-sub-title>
                <span>{{ aporte.tarifa }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">TDE</v-list-tile-sub-title>
                <span>{{ aporte.tde }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Cotizante</v-list-tile-sub-title>
                <span>{{ aporte.tipo_cotizante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Documento Cotizante</v-list-tile-sub-title>
                <span>{{ aporte.tipo_documento_cotizante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Registro</v-list-tile-sub-title>
                <span>{{ aporte.tipo_registro }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">VAC_LR</v-list-tile-sub-title>
                <span>{{ aporte.vac_lr }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Valor UPC Adicional</v-list-tile-sub-title>
                <span>{{ aporte.valor_upc_adicional }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">VSP</v-list-tile-sub-title>
                <span>{{ aporte.vsp }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">VST</v-list-tile-sub-title>
                <span>{{ aporte.vst }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">TDE</v-list-tile-sub-title>
                <span>{{ aporte.tde }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12>
              <v-divider/>
              <v-list-tile class="aportante">
                <v-list-tile-avatar color="primary">
                  <v-icon dark>person</v-icon>
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title>
                    Aportante
                  </v-list-tile-title>
                  <v-list-tile-sub-title>Información</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-divider/>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Clase Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.clase_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Ciudad Municipio</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_ciudad_municipio }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Departamento</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_departamento }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Departamento 34</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_departamento_34 }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Formato</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_formato }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Operador</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_operador }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Código Sucursal</v-list-tile-sub-title>
                <span>{{ aporte.aportante.codigo_sucursal }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Correo Electrónico</v-list-tile-sub-title>
                <span>{{ aporte.aportante.correo_electronico }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Días Mora</v-list-tile-sub-title>
                <span>{{ aporte.aportante.dias_mora }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Dígito Verific. Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.digito_verificacion_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Dígito Verific. NIT</v-list-tile-sub-title>
                <span>{{ aporte.aportante.digito_verificacion_nit }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Direccion Correspondencia</v-list-tile-sub-title>
                <span>{{ aporte.aportante.direccion_correspondencia }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Exonerado Pago Salud</v-list-tile-sub-title>
                <span>{{ aporte.aportante.exonerado_pago_salud }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">FAX</v-list-tile-sub-title>
                <span>{{ aporte.aportante.fax }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Archivo</v-list-tile-sub-title>
                <span>{{ aporte.aportante.fecha_archivo }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Matricula Mercantil</v-list-tile-sub-title>
                <span>{{ aporte.aportante.fecha_matricula_mercantil }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Pago</v-list-tile-sub-title>
                <span>{{ aporte.aportante.fecha_pago }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Fecha Pago Asociada</v-list-tile-sub-title>
                <span>{{ aporte.aportante.fecha_pago_asociada }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Forma Presentacion</v-list-tile-sub-title>
                <span>{{ aporte.aportante.forma_presentacion }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Identificacion Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.identificacion_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Modalidad</v-list-tile-sub-title>
                <span>{{ aporte.aportante.modalidad }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Naturaleza Jurídica</v-list-tile-sub-title>
                <span>{{ aporte.aportante.naturaleza_juridica }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">NIT</v-list-tile-sub-title>
                <span>{{ aporte.aportante.nit }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Nombre Sucursal</v-list-tile-sub-title>
                <span>{{ aporte.aportante.nombre_sucursal }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Afiliados</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_afiliados }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Asociada</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_asociada }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Empleados</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_empleados }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Radicación</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_radicacion }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Registro</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_registro }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Número Registros Salida</v-list-tile-sub-title>
                <span>{{ aporte.aportante.numero_registros_salida }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Periodo Pago</v-list-tile-sub-title>
                <span>{{ aporte.aportante.periodo_pago }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Razón Social Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.razon_social_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Teléfono</v-list-tile-sub-title>
                <span>{{ aporte.aportante.telefono }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Documento Aportante</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_documento_aportante }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Persona</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_persona }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Planilla</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_planilla }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Planilla Pensionado</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_planilla_pensionado }}</span>
              </v-list-tile-content>
            </v-flex>

            <v-flex xs12 sm3>
              <v-list-tile-content slot="activator">
                <v-list-tile-sub-title class="caption grey--text">Tipo Registro</v-list-tile-sub-title>
                <span>{{ aporte.aportante.tipo_registro }}</span>
              </v-list-tile-content>
            </v-flex>

          </v-layout>
        </v-container>
        <v-card-actions class="grey lighten-4">
          <v-spacer></v-spacer>
          <v-btn @click="dialog = false">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-container fluid grid-list-xl class="pa-0">
      <v-layout row wrap>
        <v-flex xs1 sm3 md6 class="text-xs-right">
          <v-tooltip top>
            <v-btn
              slot="activator"
              flat
              icon
              color="accent"
              @click="reloadPage"
            >
              <v-icon>cached</v-icon>
            </v-btn>
            <span>Actualizar registros</span>
          </v-tooltip>
        </v-flex>
        <v-flex xs12 sm3 md2>
          <v-select
            label="Registros por página"
            v-model="pagination.per_page"
            :items="items_page"
            item-text="text"
            item-value="value"
          />
        </v-flex>
        <v-flex xs12 sm6 md4>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar"
            @input="buscar"
          />
        </v-flex>
      </v-layout>

      <loading v-model="localLoading" />
      <v-data-table
        :headers="headers"
        :items="aportes"
        :loading="tableLoading"
        :pagination.sync="pagination"
        :total-items="aportes.length"
        :search="search"
        :expand="expand"
        hide-actions
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr @click="props.expanded = !props.expanded">
            <td>{{ props.item.aportante.razon_social_aportante }}</td>
            <td>{{ props.item.aportante.tipo_documento_aportante }} {{ props.item.aportante.identificacion_aportante }}</td>
            <td>{{ props.item.aportante.numero_radicacion }}</td>
            <td>{{ props.item.aportante.periodo_pago }}</td>
            <td>{{ props.item.aportante.fecha_pago }}</td>
            <td>{{ props.item.dias_cotizados }}</td>
            <td>{{ props.item.salario_basico }}</td>
            <td>{{ props.item.base_cotizacion }}</td>
            <td>{{ props.item.tarifa }}</td>
            <td>{{ props.item.cotizacion_obligatoria }}</td>
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
                    @click="verDetalle(props.item)"
                  >
                    <v-icon color="accent">remove_red_eye</v-icon>
                  </v-btn>
                  <span>Ver detalle</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </tr>
        </template>
        <template v-slot:expand="props">
          <v-card flat>
            <v-card-text>
              <v-container fluid grid-list-xl class="pa-0">

                <v-layout row wrap key="seccion1">
                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">Ingreso</v-list-tile-sub-title>
                      <span>{{ props.item.ingreso }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">Retiro</v-list-tile-sub-title>
                      <span>{{ props.item.retiro }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">TDE</v-list-tile-sub-title>
                      <span>{{ props.item.tde }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">TAE</v-list-tile-sub-title>
                      <span>{{ props.item.tae }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">VSP</v-list-tile-sub-title>
                      <span>{{ props.item.vsp }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">VST</v-list-tile-sub-title>
                      <span>{{ props.item.vst }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">SLN</v-list-tile-sub-title>
                      <span>{{ props.item.sln }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">IGE</v-list-tile-sub-title>
                      <span>{{ props.item.ige }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">LMA</v-list-tile-sub-title>
                      <span>{{ props.item.lma }}</span>
                    </v-list-tile-content>
                  </v-flex>

                  <v-flex xs12 sm1>
                    <v-list-tile-content slot="activator">
                      <v-list-tile-sub-title class="caption grey--text">VAC_LR</v-list-tile-sub-title>
                      <span>{{ props.item.vac_lr }}</span>
                    </v-list-tile-content>
                  </v-flex>

                </v-layout>

              </v-container>
            </v-card-text>
          </v-card>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>

        <template slot="footer">
          <td colspan="100%" class="text-xs-center">
            <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
          </td>
        </template>
      </v-data-table>
    </v-container>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'

  export default {
    name: 'DetalleGeneralAportes',
    components: {
      Loading
    },
    props: {
      afiliado: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        aportes: [],
        aporte: {aportante: {}},
        tipoDocumento: null,
        dialog: false,
        expand: false,
        search: '',
        tableLoading: false,
        localLoading: false,
        items_page: [
          {
            text: 15,
            value: 15
          },
          {
            text: 50,
            value: 50
          },
          {
            text: 100,
            value: 100
          }
        ],
        pagination: {
          per_page: 15,
          current_page: 1
        },
        headers: [
          {
            text: 'Razón social',
            align: 'left',
            sortable: false
          },
          {
            text: 'NIT',
            align: 'left',
            sortable: false
          },
          {
            text: 'No. Radicación',
            align: 'left',
            sortable: false
          },
          {
            text: 'Periodo pago',
            align: 'left',
            sortable: false
          },
          {
            text: 'Fecha pago',
            align: 'left',
            sortable: false
          },
          {
            text: 'Días cotizados',
            align: 'left',
            sortable: false
          },
          {
            text: 'Salario básico',
            align: 'left',
            sortable: false
          },
          {
            text: 'Base cotización',
            align: 'left',
            sortable: false
          },
          {
            text: 'Tarifa',
            align: 'left',
            sortable: false
          },
          {
            text: 'Cot. obligatoria',
            align: 'left',
            sortable: false
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    created () {
      this.tipoDocumento = this.complementos.tipdocidentidades.find(item => item.id === this.afiliado.gn_tipdocidentidad_id).abreviatura
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      complementos () {
        return JSON.parse(JSON.stringify(store.getters.complementosPostuladorAfiliado))
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get(`aportes_ach?tipo_documento_cotizante=${this.tipoDocumento}&identificacion_cotizante=${this.afiliado.identificacion}&per_page=${this.pagination.per_page}&page=${this.pagination.current_page}&search=%25${this.search}%25`)
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.aportes = response.data.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      verDetalle (item) {
        this.dialog = true
        this.aporte = item
      }
    }

  }
</script>

<style>
  .v-list__tile {
    padding: 0;
  }

  .aportante {
    margin: 15px 0;
  }

</style>
