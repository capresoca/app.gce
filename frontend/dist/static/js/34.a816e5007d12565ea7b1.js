webpackJsonp([34],{1339:function(e,a,t){"use strict";var o=t(2607),i=t(2611);a.a={name:"DetalleContratosEmpleado",components:{RegistroLiquidacion:i.a,RegistroContratoEmpleado:o.a},props:["parametros"],data:function(){return{infoComponent:null,dialog:!1,loading:!1,empleado:null,dataTable:{nameItemState:"tableContratosEmpleado",route:null,makeHeaders:[{text:"# Contrato",align:"center",sortable:!1,value:"contrato_empleado",classData:"text-xs-center"},{text:"Fecha",align:"left",sortable:!1,value:"fecha_inicio",width:"200px",component:{template:'\n              <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">\n                <v-list-tile-content class="truncate-content">\n                  <v-list-tile-title class="body-2" v-text="\'Inicio: \' + value.fecha_inicio"></v-list-tile-title>\n                  <v-list-tile-title class="body-2" v-text="\'Fin: \' + (!value.fecha_final ? \'####-##-##\' : value.fecha_final)"></v-list-tile-title>\n                </v-list-tile-content>\n              </v-list-tile>\n            ',props:["value"]}},{text:"Cargo",align:"left",sortable:!1,value:"contrato_empleado",component:{template:"\n              <span v-text=\"value.tbcargo ? value.tbcargo.descripcion : 'N/A'\"></span>\n            ",props:["value"]}},{text:"Tipo Contrato",align:"left",sortable:!1,value:"contrato_empleado",component:{template:"\n              <span v-text=\"value.tb_tipocontrato ? value.tb_tipocontrato.descripcion : 'N/A'\"></span>\n            ",props:["value"]}},{text:"Estado",align:"left",sortable:!1,value:"estado",component:{template:"\n              <span v-text=\"value.estado === 1 ? 'Vigente' : 'No Vigente'\"></span>\n            ",props:["value"]}},{text:"Salario Base",align:"left",sortable:!1,value:"base_prima",component:{template:'\n              <span v-text="currency(value.base_prima)"></span>\n            ',props:["value"]}},{text:"Opciones",align:"center",sortable:!1,actions:!0,singlesActions:!0,classData:"text-xs-center"}]}}},watch:{dialog:function(e){!1===e&&this.close()}},created:function(){if(null!==this.parametros.entidad){var e=this.clone(this.parametros.entidad);delete e.show,delete e.permisos,this.empleado=e,this.dataTable.route="talentohumano/contratos-empleados?empleado="+(this.empleado?this.empleado.empleado:null),this.infoComponent=this.clone(this.parametros.entidad.permisos)}console.log("MAS",this.parametros)},mounted:function(){this.$store.commit("reloadTable","tableContratosEmpleado")},methods:{resetOptions:function(e){return e.options=[],e.permisos=this.infoComponent,this.infoComponent&&this.infoComponent.permisos.agregar?(e.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),e.options.push({event:"detallesExtraLaboral",icon:"fas fa-file-invoice-dollar",tooltip:"Extras Laborales",color:"teal"}),1===e.estado&&e.options.push({event:"detalleLiquidacion",icon:"fas fa-handshake",tooltip:"Liquidar",color:"red darken-2"})):0===e.estado&&(e.permisos.agregar=0,e.options.push({event:"detallesExtraLaboral",icon:"fas fa-file-invoice-dollar",tooltip:"Extras Laborales",color:"teal"})),e},openDialog:function(){this.$refs.registroContratoEmpleado.assign(this.empleado,"uno")},updateOrCreate:function(e){var a=this,t=e;(t.contrato_empleado?this.axios.put("talentohumano/contratos-empleados/"+t.contrato_empleado,t):this.axios.post("talentohumano/contratos-empleados",t)).then(function(e){console.log("response",e),a.$store.commit("reloadTable","tableContratosEmpleado"),a.$store.commit("SNACK_SHOW",{msg:"Se "+(t.contrato_empleado?"actualizo el contrato #"+t.contrato_empleado:"registro el contrato")+" èxitosamente",color:"success"})}).catch(function(e){a.$store.commit("SNACK_ERROR_LIST",{expression:" al registrar el contrato.",error:e})})},actualizar:function(e){var a=this,t=this.clone(e);this.axios.put("talentohumano/contratos-empleados/"+t.contrato_empleado,t).then(function(e){a.$store.commit("reloadTable","tableContratosEmpleado"),a.$store.commit("SNACK_SHOW",{msg:"Se realizo el registro éxitosamente",color:"success"})}).catch(function(e){a.$store.commit("SNACK_ERROR_LIST",{expression:" al registrar la liquidación.",error:e})})},openLiquidar:function(e){this.$refs.liquidacionContrato.assign(e)},editarItem:function(e){this.$refs.registroContratoEmpleado.assign(e,"dos")},close:function(){this.$validator.reset(),this.dialog=!1}}}},1340:function(e,a,t){"use strict";a.a={name:"RegistroContratoEmpleado",data:function(){return{show:!1,minDate:"1900-01-01",maxDate:(new Date).toISOString().substr(0,10),data:null,datos:null,complementos:null,complementoscontratos:null,estados:null}},watch:{show:function(e){var a=this;e||setTimeout(function(){a.datos=null,a.formReset(),a.$validator.reset()},500)}},created:function(){this.formReset(),this.getComplementos()},computed:{ModalTitulo:function(){return this.data?(this.data.contrato_empleado?"Edición del ":"Nuevo ")+" Contrato "+(this.data.contrato_empleado?"# "+this.data.contrato_empleado:""):""}},methods:{getComplementos:function(){var e=this;this.axios.get("talentohumano/complementos").then(function(a){console.log("DAD",a),e.complementos=a.data.data,e.complementoscontratos=e.clone(e.complementos.complementoscontratos),e.estados=e.complementoscontratos.estados}).catch(function(a){e.$store.commit("SNACK_ERROR_LIST",{expression:" al cargar los complementos.",error:a})})},assign:function(e){var a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;switch(console.log("propss",e),a){case"uno":this.datos=e,this.data.empleado=this.datos.empleado;break;case"dos":this.data=e}this.show=!0},save:function(){var e=this;this.$validator.validateAll().then(function(a){a&&(e.$emit("updateOrCreate",e.data),e.show=!1)})},formReset:function(){this.data={contrato_empleado:null,area:null,base_prima:0,base_vacaciones:0,basico_actual:0,cargo:null,causal_despido:0,centro_trabajo:null,configuracion_salarial:null,dependencia:null,dias_no_laborados:0,empleado:null,estado:null,fecha_afiliacion_seguro:null,fecha_final:null,fecha_inicio:null,fecha_liquidacion:null,forma_pago:null,indemnizacion_contra:0,indemnizacion_favor:0,jornada_laboral:null,sub_tipo_cotizante:null,sw_antiguo_regimen:0,sw_salario_integral:0,tipo_contrato:null,tipo_cotizante:null,tipo_liquidacion:null,tipo_pago:null}}}}},1341:function(e,a,t){"use strict";a.a={name:"RegistroLiquidacion",data:function(){return{show:!1,minDate:"1900-01-01",maxDate:(new Date).toISOString().substr(0,10),data:null,datos:null,complementoscontratos:null,estados:null}},components:{InputNumber:function(){return new Promise(function(e){e()}).then(t.bind(null,254))}},watch:{show:function(e){var a=this;e||setTimeout(function(){a.datos=null,a.formReset(),a.$validator.reset()},500)}},created:function(){this.formReset(),this.getComplementos()},computed:{ModalTitulo:function(){return this.data?"Liquidación del contrato "+(this.data.contrato_empleado?"# "+this.data.contrato_empleado:""):""}},methods:{getComplementos:function(){var e=this;this.axios.get("talentohumano/complementos").then(function(a){console.log("DAD",a);var t=a.data.data;e.complementoscontratos=e.clone(t.complementoscontratos)}).catch(function(a){e.$store.commit("SNACK_ERROR_LIST",{expression:" al cargar los complementos.",error:a})})},assign:function(e){console.log("propss",e),this.data=e,this.show=!0},save:function(){var e=this;this.$validator.validateAll().then(function(a){a&&(e.$emit("updated",e.data),e.show=!1)})},formReset:function(){this.data={contrato_empleado:null,base_prima:0,base_vacaciones:0,basico_actual:0,causal_despido:null,dias_no_laborados:0,empleado:null,estado:null,fecha_liquidacion:null,fecha_inicio:null,indemnizacion_favor:0,indemnizacion_contra:0}}}}},2605:function(e,a,t){var o=t(2606);"string"==typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);t(456)("9116ee24",o,!0,{})},2606:function(e,a,t){a=e.exports=t(455)(!0),a.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DetalleContratosEmpleado.vue",sourceRoot:""}])},2607:function(e,a,t){"use strict";function o(e){t(2608)}var i=t(1340),s=t(2610),n=t(1),r=o,l=n(i.a,s.a,!1,r,"data-v-1e7692db",null);a.a=l.exports},2608:function(e,a,t){var o=t(2609);"string"==typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);t(456)("a8acd448",o,!0,{})},2609:function(e,a,t){a=e.exports=t(455)(!0),a.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroContratoEmpleado.vue",sourceRoot:""}])},2610:function(e,a,t){"use strict";var o=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("v-dialog",{attrs:{persistent:"","max-width":"900"},model:{value:e.show,callback:function(a){e.show=a},expression:"show"}},[t("v-card",[t("toolbar-list",{attrs:{info:"",title:e.ModalTitulo,subtitle:"Registro y gestión"}}),e._v(" "),t("v-form",{ref:"formContratos"},[t("v-container",{attrs:{fluid:"","grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg4:""}},[t("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"required|fechaValida:YYYY-MM-DD",expression:"'required|fechaValida:YYYY-MM-DD'"}],attrs:{label:"Fecha Inicio (Año-Mes-Día)",format:"YYYY-MM-DD",min:e.minDate,max:e.maxDate,name:"fecha inicio","error-messages":e.errors.collect("fecha inicio")},model:{value:e.data.fecha_inicio,callback:function(a){e.$set(e.data,"fecha_inicio",a)},expression:"data.fecha_inicio"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg4:""}},[t("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"fechaValida:YYYY-MM-DD",expression:"'fechaValida:YYYY-MM-DD'"}],attrs:{disabled:!e.data.fecha_inicio,label:"Fecha Fin (Año-Mes-Día)",format:"YYYY-MM-DD",min:e.data.fecha_inicio,name:"fecha fin","error-messages":e.errors.collect("fecha fin")},model:{value:e.data.fecha_final,callback:function(a){e.$set(e.data,"fecha_final",a)},expression:"data.fecha_final"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg4:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.estados,"item-value":"id","item-text":"nombre",name:"estado",label:"Estado","error-messages":e.errors.collect("estado"),clearable:""},model:{value:e.data.estado,callback:function(a){e.$set(e.data,"estado",a)},expression:"data.estado"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg4:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementos?e.complementos.cargos:[],"item-value":"cargo","item-text":"descripcion",name:"cargo",label:"Cargo","error-messages":e.errors.collect("cargo"),clearable:""},model:{value:e.data.cargo,callback:function(a){e.$set(e.data,"cargo",a)},expression:"data.cargo"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md3:"",lg3:""}},[t("v-switch",{staticClass:"mr-4",attrs:{color:"success",label:"Salario Integral"},model:{value:e.data.sw_salario_integral,callback:function(a){e.$set(e.data,"sw_salario_integral",a)},expression:"data.sw_salario_integral"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md5:"",lg5:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementos?e.complementos.configuracionsalarial:[],"item-value":"configuracion_salarial","item-text":"descripcion",name:"configuración salarial",label:"Configuración Salarial","error-messages":e.errors.collect("configuración salarial"),clearable:""},model:{value:e.data.configuracion_salarial,callback:function(a){e.$set(e.data,"configuracion_salarial",a)},expression:"data.configuracion_salarial"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementos?e.complementos.tiposcontratos:[],"item-value":"tipo_contrato","item-text":"descripcion",name:"tipo contrato",label:"Tipo Contrato","error-messages":e.errors.collect("tipo contrato"),clearable:""},model:{value:e.data.tipo_contrato,callback:function(a){e.$set(e.data,"tipo_contrato",a)},expression:"data.tipo_contrato"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.tipliquidaciones:[],"item-value":"id","item-text":"nombre",name:"tipo de liquidación",label:"Tipo de Liquidación","error-messages":e.errors.collect("tipo de liquidación"),clearable:""},model:{value:e.data.tipo_liquidacion,callback:function(a){e.$set(e.data,"tipo_liquidacion",a)},expression:"data.tipo_liquidacion"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md6:"",lg6:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.tippagos:[],"item-value":"id","item-text":"nombre",name:"tipo pago",label:"Tipo Pago","error-messages":e.errors.collect("tipo pago"),clearable:""},model:{value:e.data.tipo_pago,callback:function(a){e.$set(e.data,"tipo_pago",a)},expression:"data.tipo_pago"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md3:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementos?e.complementos.centroscostos:[],"item-value":"centro_costo","item-text":"descripcion",name:"centro de trabajo",label:"Centro de trabajo","error-messages":e.errors.collect("centro de trabajo"),clearable:""},model:{value:e.data.centro_trabajo,callback:function(a){e.$set(e.data,"centro_trabajo",a)},expression:"data.centro_trabajo"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md3:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{disabled:!e.data.centro_trabajo,items:e.complementos?e.complementos.areas.filter(function(a){return a.centro_costo===e.data.centro_trabajo}):[],"item-value":"area","item-text":"descripcion",name:"área",label:"Área","error-messages":e.errors.collect("área"),clearable:""},model:{value:e.data.area,callback:function(a){e.$set(e.data,"area",a)},expression:"data.area"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md6:"",lg6:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{disabled:!e.data.area,items:e.complementos?e.complementos.dependencias.filter(function(a){return a.area===e.data.area}):[],"item-value":"dependencia","item-text":"descripcion",name:"dependencia",label:"Dependencia","error-messages":e.errors.collect("dependencia"),clearable:""},model:{value:e.data.dependencia,callback:function(a){e.$set(e.data,"dependencia",a)},expression:"data.dependencia"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.jornadas:[],"item-value":"id","item-text":"nombre",name:"jornada",label:"Jornada","error-messages":e.errors.collect("Jornada"),clearable:""},model:{value:e.data.jornada_laboral,callback:function(a){e.$set(e.data,"jornada_laboral",a)},expression:"data.jornada_laboral"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.tiposcotizantes:[],"item-value":"id","item-text":"nombre",name:"tipo cotizante",label:"Tipo Cotizante","error-messages":e.errors.collect("tipo cotizante"),clearable:""},model:{value:e.data.tipo_cotizante,callback:function(a){e.$set(e.data,"tipo_cotizante",a)},expression:"data.tipo_cotizante"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{disabled:!e.data.tipo_cotizante,items:e.complementoscontratos?e.complementoscontratos.subtipos_cotizantes:[],"item-value":"id","item-text":"nombre",name:"subtipo cotizante",label:"Subtipo Cotizante","error-messages":e.errors.collect("subtipo cotizante"),clearable:""},model:{value:e.data.sub_tipo_cotizante,callback:function(a){e.$set(e.data,"sub_tipo_cotizante",a)},expression:"data.sub_tipo_cotizante"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"fechaValida:YYYY-MM-DD",expression:"'fechaValida:YYYY-MM-DD'"}],attrs:{label:"Fecha Afiliación Seguro (Año-Mes-Día)",format:"YYYY-MM-DD",max:e.maxDate,name:"fecha afiliación seguro","error-messages":e.errors.collect("fecha afiliación seguro")},model:{value:e.data.fecha_afiliacion_seguro,callback:function(a){e.$set(e.data,"fecha_afiliacion_seguro",a)},expression:"data.fecha_afiliacion_seguro"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.formasdepagos:[],"item-value":"id","item-text":"nombre",name:"forma de pago",label:"Forma de Pago","error-messages":e.errors.collect("forma de pago"),clearable:""},model:{value:e.data.forma_pago,callback:function(a){e.$set(e.data,"forma_pago",a)},expression:"data.forma_pago"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:"",lg3:""}},[t("v-switch",{staticClass:"mr-4",attrs:{color:"success",label:"Régimen Antiguo"},model:{value:e.data.sw_antiguo_regimen,callback:function(a){e.$set(e.data,"sw_antiguo_regimen",a)},expression:"data.sw_antiguo_regimen"}})],1)],1)],1)],1),e._v(" "),t("v-card-actions",{staticClass:"grey lighten-3"},[t("v-spacer"),e._v(" "),t("v-btn",{attrs:{flat:""},on:{click:function(a){e.show=!1}}},[e._v("Cancelar")]),e._v(" "),t("v-btn",{attrs:{color:"primary",flat:""},on:{click:function(a){return a.preventDefault(),e.save(a)}}},[e._v("Guardar")])],1)],1)],1)},i=[],s={render:o,staticRenderFns:i};a.a=s},2611:function(e,a,t){"use strict";function o(e){t(2612)}var i=t(1341),s=t(2614),n=t(1),r=o,l=n(i.a,s.a,!1,r,"data-v-d0713eb8",null);a.a=l.exports},2612:function(e,a,t){var o=t(2613);"string"==typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);t(456)("2d7c6a3c",o,!0,{})},2613:function(e,a,t){a=e.exports=t(455)(!0),a.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroLiquidacion.vue",sourceRoot:""}])},2614:function(e,a,t){"use strict";var o=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("v-dialog",{attrs:{persistent:"","max-width":"500"},model:{value:e.show,callback:function(a){e.show=a},expression:"show"}},[t("v-card",[t("toolbar-list",{attrs:{info:"",title:e.ModalTitulo}}),e._v(" "),t("v-form",{ref:"formContratos"},[t("v-container",{attrs:{fluid:"","grid-list-sm":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"fechaValida:YYYY-MM-DD",expression:"'fechaValida:YYYY-MM-DD'"}],attrs:{label:"Fecha Liquidación (Año-Mes-Día)",format:"YYYY-MM-DD",min:e.data.fecha_inicio,name:"fecha liquidación","error-messages":e.errors.collect("fecha liquidación")},model:{value:e.data.fecha_liquidacion,callback:function(a){e.$set(e.data,"fecha_liquidacion",a)},expression:"data.fecha_liquidacion"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:e.complementoscontratos?e.complementoscontratos.causales_despido:[],"item-value":"id","item-text":"nombre",name:"causal",label:"Causal","error-messages":e.errors.collect("causal"),clearable:""},model:{value:e.data.causal_despido,callback:function(a){e.$set(e.data,"causal_despido",a)},expression:"data.causal_despido"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],key:"días no laborados",attrs:{label:"Días No Laborados",name:"días no laborados","prepend-icon":"attach_money",precision:0,"error-messages":e.errors.collect("días no laborados")},model:{value:e.data.dias_no_laborados,callback:function(a){e.$set(e.data,"dias_no_laborados",e._n(a))},expression:"data.dias_no_laborados"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],key:"indemnización favor",attrs:{label:"Indemnización Favor",name:"indemnización favor","prepend-icon":"attach_money",precision:2,"error-messages":e.errors.collect("indemnización favor")},model:{value:e.data.indemnizacion_favor,callback:function(a){e.$set(e.data,"indemnizacion_favor",e._n(a))},expression:"data.indemnizacion_favor"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],key:"indemnización contra",attrs:{label:"Indemnización Contra",name:"indemnización contra","prepend-icon":"attach_money",precision:2,"error-messages":e.errors.collect("indemnización contra")},model:{value:e.data.indemnizacion_contra,callback:function(a){e.$set(e.data,"indemnizacion_contra",e._n(a))},expression:"data.indemnizacion_contra"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],key:"base prima",attrs:{label:"Base Prima",name:"base prima","prepend-icon":"attach_money",precision:2,"error-messages":e.errors.collect("base prima")},model:{value:e.data.base_prima,callback:function(a){e.$set(e.data,"base_prima",e._n(a))},expression:"data.base_prima"}})],1),e._v(" "),t("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[t("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],key:"base vacaciones",attrs:{label:"Base Vacaciones",name:"base vacaciones","prepend-icon":"attach_money",precision:2,"error-messages":e.errors.collect("base vacaciones")},model:{value:e.data.base_vacaciones,callback:function(a){e.$set(e.data,"base_vacaciones",e._n(a))},expression:"data.base_vacaciones"}})],1)],1)],1)],1),e._v(" "),t("v-card-actions",{staticClass:"grey lighten-3"},[t("v-spacer"),e._v(" "),t("v-btn",{attrs:{flat:""},on:{click:function(a){e.show=!1}}},[e._v("Cancelar")]),e._v(" "),t("v-btn",{attrs:{color:"primary",flat:""},on:{click:function(a){return a.preventDefault(),e.save(a)}}},[e._v("Guardar")])],1)],1)],1)},i=[],s={render:o,staticRenderFns:i};a.a=s},2615:function(e,a,t){"use strict";var o=function(){var e=this,a=this,t=a.$createElement,o=a._self._c||t;return o("v-card",[o("registro-contrato-empleado",{ref:"registroContratoEmpleado",on:{updateOrCreate:function(e){return a.updateOrCreate(e)}}}),a._v(" "),o("registro-liquidacion",{ref:"liquidacionContrato",on:{updated:function(e){return a.actualizar(e)}}}),a._v(" "),o("loading",{model:{value:a.loading,callback:function(e){a.loading=e},expression:"loading"}}),a._v(" "),o("toolbar-list",{attrs:{info:a.infoComponent,iconom:"fas fa-file-archive",title:"Contratos Empleado",subtitle:"Mantenimiento",btnplus:"",btnplustitle:"Crear Contrato",btnplustruncate:""},on:{click:a.openDialog}}),a._v(" "),o("data-table-v2",{ref:"tablaDetallesContratoEmpleado",on:{resetOption:function(e){return a.resetOptions(e)},editar:function(e){return a.editarItem(e)},detallesExtraLaboral:function(t){return a.$store.commit("NAV_ADD_ITEM",{ruta:"detalleExtralabora",titulo:"Registro Detalles Laborares CC. "+(t.scempleado?t.scempleado.numero_identificacion:" #"),parametros:{entidad:t,tabOrigin:e.$store.state.nav.currentItem.split("tab-")[1]}})},detalleLiquidacion:function(e){return a.openLiquidar(e)}},model:{value:a.dataTable,callback:function(e){a.dataTable=e},expression:"dataTable"}})],1)},i=[],s={render:o,staticRenderFns:i};a.a=s},720:function(e,a,t){"use strict";function o(e){t(2605)}Object.defineProperty(a,"__esModule",{value:!0});var i=t(1339),s=t(2615),n=t(1),r=o,l=n(i.a,s.a,!1,r,"data-v-75fb3300",null);a.default=l.exports}});
//# sourceMappingURL=34.a816e5007d12565ea7b1.js.map