webpackJsonp([159],{1208:function(e,t,r){"use strict";var a=r(23),o=r.n(a),s=r(13),n=r.n(s),i=r(14),l=r.n(i),d=r(249),c=r.n(d),u=r(7),p=r.n(u),v=r(12),m=r(58),f=r(40),b=r(248),_=r(59),h=r(251),g=r(254);t.a={name:"RegistroRp",props:["parametros"],components:{Loading:m.default,PostuladorV2:function(){return new Promise(function(e){e()}).then(r.bind(null,61))},InputDetailFlex:function(){return new Promise(function(e){e()}).then(r.bind(null,250))},InputNumber:g.default},data:function(){return{detalleTercero:function(){return r.e(291).then(r.bind(null,768))},rubrosCreados:"",readOnly:!1,openTable:!1,noAplica:!1,menuDate:!1,dialog:!1,dialogAnular:!1,loadingSubmit:!1,tableLoading:!1,localLoading:!1,rp:null,detalle:null,cdpRubro:null,presupuestos:[],contratos:[],cdps:[],rubros:[],tableCDP:[],headersCDP:[{text:"Consecutivo",align:"left",sortable:!1,value:"consecutivo"},{text:"Objeto",align:"left",sortable:!1,value:"objeto"},{text:"Fecha vencimiento",align:"left",sortable:!1,value:"fechaVence"},{text:"Valor",align:"left",sortable:!1,value:"valor"},{text:"Total Ejecutado",align:"left",sortable:!1,value:"suma_valores_ejecutados"},{text:"Estado",align:"left",sortable:!1,value:"estado"}],headers:[{text:"Rubro",align:"left",sortable:!1,value:"rubro"},{text:"Nombre",align:"left",sortable:!1,value:"nombre"},{text:"Tipo gasto",align:"left",sortable:!1,value:"tipoGasto"},{text:"Valor",align:"left",sortable:!1,value:"valor"},{text:"Opciones",align:"left",sortable:!1,value:"id"}]}},beforeMount:function(){this.formReset(),this.resetDialog(),this.validadorPostulador(),this.loadLocalStorage()},mounted:function(){null!==this.parametros.entidad&&this.getRegistro(this.parametros.entidad.id),null===this.rp.id&&this.obtenerPeriodos(),null!==this.rp.estado&&"Registrado"!==this.rp.estado||(this.obtenerContratos(),this.obtenerCdps());var e={custom:{rubro:{not_in:"Este rubro ya fue registrado."}}};this.$validator.localize("es",e)},computed:{complementos:function(){return _.b.getters.complementosPresupuesto},iconoEstado:function(){return"Registrado"===this.rp.estado?"stars":"Confirmado"===this.rp.estado?"check_circle":"Anulado"===this.rp.estado?"remove_circle":void 0},colorEstado:function(){return"Registrado"===this.rp.estado?"primary":"Confirmado"===this.rp.estado?"teal":"Anulado"===this.rp.estado?"red":void 0},computedDateFormatted:function(){return this.formDate(this.rp.fecha)}},watch:{"rp.tipo":function(e){"No Aplica"===e&&(this.noAplica=!0,this.rp.minuta=null),"Contrato"===e&&(this.noAplica=!1,this.rp.cdp=null),this.$validator.reset()},"rp.minuta":function(e){e?e.cdp&&(this.rp.cdp=e.cdp,this.rp.pr_cdp_id=e.cdp.id):(this.rp.cdp=null,this.rp.pr_cdp_id=null)},"rp.cdp":function(e){e?(this.tableCDP.push(e),this.openTable=!0,this.rp.fecha_vence=e.fecha_vence,this.rp.observaciones=e.objecto):(this.rubros=[],this.rp.detalles=[],this.rp.observaciones=null,this.tableCDP=[],this.openTable=!1,this.rp.fecha_vence=null,this.rp.valor_compromiso=null)}},methods:{getRegistro:function(e){var t=this,r=this.$loading.show({container:this.$refs.cargar});this.axios.get("pr_rps/"+e).then(function(e){if(""!==e.data){t.readOnly="Registrado"!==e.data.data.estado,t.rp=e.data.data}r.hide()}).catch(function(){r.hide(),t.$store.commit(v.b,{msg:"Hay un error al traer el registro. ",color:"danger"})})},formReset:function(){this.rp={id:null,ce_proconminuta_id:null,estado:null,fecha:null,consecutivo:null,fecha_vence:null,gn_tercero_id:null,observaciones:null,periodo:null,plazo:null,pr_cdp_id:null,pr_entidad_resolucion_id:null,pr_strgasto_id:null,tipo:null,valor_compromiso:null,detalles:[],tercero:null,cdp:null,minuta:null,entidad_resolucion:null}},resetDialog:function(){this.dialog=!1,this.detalle={id:null,pr_detcdp_id:null,pr_rubro_id:null,pr_tipo_gasto_id:null,tipo_gasto:null,valor:0,rubro:null},this.rubrosCreados=this.getRubrosUsados()},saveLocalStorage:function(){this.rp.fecha&&localStorage.setItem("fechaRp",p()(this.rp.fecha))},loadLocalStorage:function(){localStorage.getItem("fechaRp")&&(this.rp.fecha=JSON.parse(localStorage.getItem("fechaRp")))},openDialog:function(){this.dialog=!0,this.rubrosCreados=this.getRubrosUsados()},formDate:function(e){if(!e)return null;var t=e.split("-"),r=c()(t,3);return r[0]+"/"+r[1]+"/"+r[2]},calcularCompromiso:function(){var e=0;this.rp.detalles.forEach(function(t){e+=t.valor}),this.rp.valor_compromiso=e},getRubrosUsados:function(){var e=this;return this.rp.detalles.map(function(t){if(e.detalle.rubro&&(null==e.detalle.rubro.id||e.detalle.rubro.id!==t.rubro.id))return t.rubro.id})},agregarDetalle:function(e){var t=this;return l()(n.a.mark(function r(){return n.a.wrap(function(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,t.validador("formRpDialog");case 2:if(!r.sent){r.next=9;break}"number"!=typeof e.index?t.rp.detalles.push(e):t.rp.detalles.splice(t.rp.detalles.findIndex(function(r){return t.rp.detalles.indexOf(r)===e.index}),1,e),t.calcularCompromiso(),t.dialog=!1,t.resetDialog(),r.next=10;break;case 9:t.$store.commit(v.b,{msg:"Hay uno o más campos vacios.",color:"danger"});case 10:case"end":return r.stop()}},r,t)}))()},editarDetalle:function(e,t){this.detalle=t,this.detalle.index=e,this.obtenerCdpRubro(),this.dialog=!0,this.rubrosCreados=this.getRubrosUsados()},eliminarDetalle:function(e){this.rp.detalles.splice(e,1),this.calcularCompromiso(),this.dialog=!1},filterRubros:function(e,t){var r=function(e){return null!=e?e:""},a=r(e.nombre+" "+e.codigo+e.codigo.split(".").join("")),o=r(t);return a.toString().toLowerCase().indexOf(o.toString().toLowerCase())>-1},validadorPostulador:function(){var e=this;h.a.extend("rubroValido",{validate:function(t,r){return new o.a(function(r){var a={};if(null!==t&&void 0!==t&&""!==t)return a=e.rubrosCreados.includes(t.id)?{valido:!1,mensaje:"Rubro ya usado"}:{valido:!0,mensaje:null},r({valid:a.valido,data:{message:a.mensaje}})})},getMessage:function(e,t,r){return r.message}})},llenarTablaDetalles:function(e){var t=this;this.rp.detalles=[],e.forEach(function(e){t.rp.detalles.push({id:null,pr_detcdp_id:e.id,pr_tipo_gasto_id:e.pr_tipo_gasto_id,pr_rubro_id:e.rubro.id,tipo_gasto:e.tipo_gasto,valor:0,rubro:e.rubro})})},validador:function(e){return this.$validator.validateAll(e).then(function(e){return e})},confirmar:function(){var e=this;return l()(n.a.mark(function t(){return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.validador("formRp");case 2:if(!t.sent){t.next=7;break}e.rp.estado="Confirmado",e.submit(),t.next=8;break;case 7:e.$store.commit(v.b,{msg:"Hay uno o más campos vacios.",color:"danger"});case 8:case"end":return t.stop()}},t,e)}))()},anular:function(){var e=this;return l()(n.a.mark(function t(){return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.validador("formRp");case 2:if(!t.sent){t.next=8;break}e.dialogAnular=!1,e.rp.estado="Anulado",e.submit(),t.next=9;break;case 8:e.$store.commit(v.b,{msg:"Hay uno o más campos vacios.",color:"danger"});case 9:case"end":return t.stop()}},t,e)}))()},submit:function(){var e=this;return l()(n.a.mark(function t(){var r,a,o,s;return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.rp.detalles.filter(function(e){return 0===e.valor}),t.next=3,e.validador("formRp");case 3:if(t.t0=t.sent,!t.t0){t.next=6;break}t.t0=0===r.length;case 6:if(!t.t0){t.next=15;break}e.localLoading=!0,a=e.rp.estado,e.rp.estado=e.rp.id||null!==a?a:"Registrado",o=e.rp.id?"editar":"crear",s=e.rp.id?e.axios.put("pr_rps/"+e.rp.id,e.rp):e.axios.post("pr_rps",e.rp),s.then(function(t){e.$store.commit(v.b,{msg:"Item "+(e.rp.id?"actualizado":"creado")+" satisfactoriamente",color:"success"}),e.$store.commit(b._15,{item:t.data.data,estado:o,key:e.parametros.key}),e.$store.dispatch(f.c,{contexto:e,itemId:e.parametros.tabOrigin}),e.$validator.reset(),e.formReset(),e.resetDialog()}).catch(function(t){e.localLoading=!1,e.$store.commit(v.a,{expression:" al guardar el registro. ",error:t})}),t.next=16;break;case 15:r.length>=1?e.$store.commit(v.b,{msg:"Existen detalles con valores igual a 0.",color:"danger"}):e.$store.commit(v.b,{msg:"Hay uno o más campos vacios.",color:"danger"});case 16:case"end":return t.stop()}},t,e)}))()},obtenerPresupuesto:function(e){var t=this,r=this.$loading.show({container:this.$refs.formPresupuesto.$el});this.axios.get("rps/"+e).then(function(e){""!==e.data&&(t.rp.entidad_resolucion=e.data.data.entidad_resolucion,t.rp.pr_entidad_resolucion_id=e.data.data.entidad_resolucion?e.data.data.entidad_resolucion.id:null,t.rp.periodo=t.rp.entidad_resolucion.periodo),r.hide()}).catch(function(e){r.hide(),t.$store.commit(v.b,{msg:"Hay un error al traer el registro. "+e,color:"danger"})})},obtenerPeriodos:function(){var e=this;this.axios.get("rps_periodos_cdps ").then(function(t){e.presupuestos=t.data,e.obtenerPresupuesto(e.presupuestos[0])}).catch(function(t){e.$store.commit(v.a,{expression:" al obtener presupuesto. ",error:t})})},obtenerContratos:function(){var e=this;this.axios.get("contratos_cdps").then(function(t){""!==t.data&&(e.contratos=t.data.data)}).catch(function(t){e.$store.commit(v.b,{msg:"Hay un error al traer el los contratos. "+t,color:"danger"})})},obtenerCdps:function(){var e=this;this.axios.get("cdps").then(function(t){""!==t.data&&(e.cdps=t.data.data)}).catch(function(t){e.$store.commit(v.b,{msg:"Hay un error al traer el los contratos. "+t,color:"danger"})})},obtenerRubrosContrato:function(e){var t=this;if(e){this.tableCDP=[];var r=this.$loading.show({container:this.$refs.formPresupuesto.$el});this.axios.get("contrato_with_cdp_rubros/"+e.pr_cdp_id).then(function(e){t.rubros=e.data.rubros,t.llenarTablaDetalles(e.data.detalles),r.hide()}).catch(function(e){r.hide(),t.$store.commit(v.b,{msg:"Hay un error al traer el los contratos. "+e,color:"danger"})})}},obtenerRubrosCdp:function(e){var t=this;if(e){this.tableCDP=[];var r=this.$loading.show({container:this.$refs.formPresupuesto.$el});this.axios.get("contrato_with_cdp_rubros/"+e.id).then(function(e){t.rubros=e.data.rubros,t.llenarTablaDetalles(e.data.detalles),r.hide()}).catch(function(e){r.hide(),t.$store.commit(v.b,{msg:"Hay un error al traer el los contratos. "+e,color:"danger"})})}},obtenerCdpRubro:function(){var e=this,t=this.$loading.show({container:this.$refs.dialogRp.$el});this.axios.get("cdp_rubro?cdpId="+this.rp.pr_cdp_id+"&rubroId="+this.detalle.pr_rubro_id).then(function(r){""!==r.data&&(e.cdpRubro=r.data.data),t.hide()}).catch(function(r){t.hide(),e.$store.commit(v.b,{msg:"Hay un error al traer el registro. "+r,color:"danger"})})}}}},2164:function(e,t,r){var a=r(2165);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);r(456)("3aa37396",a,!0,{})},2165:function(e,t,r){t=e.exports=r(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroRp.vue",sourceRoot:""}])},2166:function(e,t,r){"use strict";var a=function(){var e=this,t=this,r=t.$createElement,a=t._self._c||r;return a("div",[a("v-dialog",{attrs:{persistent:"",width:"500px"},model:{value:t.dialog,callback:function(e){t.dialog=e},expression:"dialog"}},[a("v-form",{attrs:{"data-vv-scope":"formRpDialog"},on:{submit:function(e){return e.preventDefault(),t.agregarDetalle(t.detalle)}}},[a("v-card",{ref:"dialogRp"},[a("v-card-title",{staticClass:"grey lighten-3 elevation-0"},[a("span",{staticClass:"title"},[t._v("Agregar rubro")])]),t._v(" "),a("v-container",{attrs:{fluid:"","grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required|rubroValido",expression:"'required|rubroValido'"}],attrs:{label:"Rubro",items:t.rubros,filter:t.filterRubros,hint:t.detalle.rubro?t.detalle.rubro.codigo:"","persistent-hint":"","item-text":"nombre","item-value":"id",name:"rubro","no-data-text":"No existen datos","prepend-icon":"subtitles",required:"",required:"","return-object":"",readonly:"","error-messages":t.errors.collect("rubro")},on:{change:function(e){t.detalle.pr_rubro_id=e?e.id:null,t.obtenerCdpRubro()}},scopedSlots:t._u([{key:"item",fn:function(e){return[[a("v-list-tile-content",[a("v-list-tile-title",{domProps:{innerHTML:t._s(e.item.nombre)}}),t._v(" "),a("v-list-tile-sub-title",{domProps:{innerHTML:t._s(e.item.codigo)}})],1)]]}}]),model:{value:t.detalle.rubro,callback:function(e){t.$set(t.detalle,"rubro",e)},expression:"detalle.rubro"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"tipo de gasto",attrs:{value:t.detalle.tipo_gasto?t.detalle.tipo_gasto.nombre:null,readonly:"",label:"Tipo de gasto","prepend-icon":"compare_arrows",name:"tipo de gasto",required:""}})],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("input-detail-flex",{attrs:{label:"Valor disponible",prependIcon:"money",text:t.cdpRubro?t.currency(t.cdpRubro.valor_disponible):0}})],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required|max_value:"+(t.cdpRubro?t.cdpRubro.valor_disponible:null),expression:"'required|max_value:' + (cdpRubro ? cdpRubro.valor_disponible: null)"}],attrs:{label:"Saldo",name:"saldo",precision:0,"prepend-icon":"attach_money","error-messages":t.errors.collect("saldo")},model:{value:t.detalle.valor,callback:function(e){t.$set(t.detalle,"valor",t._n(e))},expression:"detalle.valor"}})],1)],1)],1),t._v(" "),a("v-card-actions",{staticClass:"grey lighten-4"},[a("v-spacer"),t._v(" "),a("v-btn",{on:{click:t.resetDialog}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{type:"submit",color:"primary"}},[t._v("Guardar")])],1)],1)],1)],1),t._v(" "),a("v-dialog",{attrs:{persistent:"","max-width":"400"},model:{value:t.dialogAnular,callback:function(e){t.dialogAnular=e},expression:"dialogAnular"}},[a("v-card",[a("v-card-title",{staticClass:"grey lighten-3"},[a("v-card-text",{staticClass:"title"},[t._v("¿Por qué anula el RP?")])],1),t._v(" "),a("v-container",{attrs:{fluid:""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-textarea",{attrs:{outline:"",color:"primary",name:"Detalle de anulación"},model:{value:t.rp.concepto_anulacionint,callback:function(e){t.$set(t.rp,"concepto_anulacionint",e)},expression:"rp.concepto_anulacionint"}},[a("div",{attrs:{slot:"label"},slot:"label"},[t._v("\n                  Detalle de anulación\n                ")])])],1)],1)],1),t._v(" "),a("v-card-actions",[a("v-spacer"),t._v(" "),a("v-btn",{attrs:{flat:""},nativeOn:{click:function(e){t.dialogAnular=!1}}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{color:"primary",flat:""},on:{click:t.anular}},[t._v("Enviar")])],1)],1)],1),t._v(" "),a("v-card",{ref:"formPresupuesto"},[a("v-toolbar",{staticClass:"grey lighten-3 elevation-0 toolbar--dense"},[a("v-toolbar-title",[t._v(" Registro RP ")]),t._v(" "),a("v-spacer"),t._v(" "),a("v-chip",{attrs:{label:"",color:"white","text-color":"red",dark:"",absolute:"",right:"",top:""}},[a("span",{staticClass:"subheading"},[t._v("N°")]),t._v(" \n          "),a("span",{staticClass:"subheading",domProps:{textContent:t._s(null==t.rp.consecutivo?"00":t.str_pad(t.rp.consecutivo,8,"0","STR_PAD_LEFT"))}})]),t._v(" "),this.rp.id?a("v-chip",{attrs:{color:t.colorEstado,"text-color":"white"}},[a("v-avatar",[a("v-icon",[t._v(t._s(t.iconoEstado))])],1),t._v("\n          "+t._s(t.rp.estado)+"\n        ")],1):t._e()],1),t._v(" "),a("v-container",{staticStyle:{"max-width":"inherit"},attrs:{"grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-form",{attrs:{"data-vv-scope":"formRp"}},[a("v-container",{attrs:{fluid:"","grid-list-md":""}},["Anulado"===t.rp.estado?a("v-layout",[a("v-flex",{attrs:{xs12:""}},[a("v-alert",{attrs:{value:!0,type:"error"}},[a("strong",[t._v("Concepto anulación:")]),t._v(" "+t._s(t.rp.concepto_anulacionint)+"\n                    ")])],1)],1):t._e(),t._v(" "),a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pb-4",attrs:{xs12:""}},[a("v-card",[a("v-toolbar",{staticClass:"grey lighten-4 elevation-0",attrs:{dense:""}},[a("v-toolbar-title",{staticClass:"subheading"},[a("v-icon",[t._v("assignment_ind")]),t._v(" Presupuesto")],1)],1),t._v(" "),a("v-card-text",[a("v-layout",{attrs:{row:"",wrap:""}},[null!==t.parametros.entidad?a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-text-field",{key:"periodo",attrs:{"prepend-icon":"calendar_today",label:"Periodo",readonly:""},model:{value:t.rp.periodo,callback:function(e){t.$set(t.rp,"periodo",e)},expression:"rp.periodo"}})],1):a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:t.presupuestos,name:"periodo",label:"Periodo","no-data-text":"No hay resoluciones disponibles","error-messages":t.errors.collect("periodo"),required:"","prepend-icon":"calendar_today"},on:{change:function(e){return t.obtenerPresupuesto(e)}},model:{value:t.rp.periodo,callback:function(e){t.$set(t.rp,"periodo",e)},expression:"rp.periodo"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-text-field",{key:"unidad ejecturoa",attrs:{value:t.rp.entidad_resolucion?t.rp.entidad_resolucion.codigo:null,"prepend-icon":"account_balance",label:"Unidad ejecutora",readonly:""}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-text-field",{key:"nombre",attrs:{value:t.rp.entidad_resolucion?t.rp.entidad_resolucion.nombre:null,"prepend-icon":"account_balance",label:"Nombre",readonly:""}})],1)],1)],1)],1)],1),t._v(" "),a("v-flex",{staticClass:"pb-4",attrs:{xs12:""}},[a("v-card",[a("v-toolbar",{staticClass:"grey lighten-4 elevation-0",attrs:{dense:""}},[a("v-toolbar-title",{staticClass:"subheading"},[a("v-icon",[t._v("assignment_ind")]),t._v(" General")],1)],1),t._v(" "),a("v-card-text",{attrs:{fluid:"","grid-list-xl":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm12:""}},[t.readOnly?a("v-subheader",{staticClass:"pa-0 ma-0",domProps:{textContent:t._s("Documento: "+t.rp.tipo)}}):a("v-radio-group",{attrs:{label:"Documento",row:"",readonly:t.readOnly},model:{value:t.rp.tipo,callback:function(e){t.$set(t.rp,"tipo",e)},expression:"rp.tipo"}},[a("v-radio",{attrs:{disabled:"",label:"Orden de compra",value:"Orden de Compra"}}),t._v(" "),a("v-radio",{attrs:{label:"Contrato",value:"Contrato"}}),t._v(" "),a("v-radio",{attrs:{label:"No Aplica",value:"No Aplica"}})],1)],1),t._v(" "),"Confirmado"!==t.rp.estado&&"Anulado"!==t.rp.estado?a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-menu",{ref:"menuDate",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:t.menuDate,callback:function(e){t.menuDate=e},expression:"menuDate"}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha","prepend-icon":"event",readonly:"",name:"fecha activo","error-messages":t.errors.collect("fecha activo")},slot:"activator",model:{value:t.computedDateFormatted,callback:function(e){t.computedDateFormatted=e},expression:"computedDateFormatted"}}),t._v(" "),a("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){t.menuDate=!1},change:function(){var r=t.$validator.errors.items.findIndex(function(e){return"fecha activo"===e.field});t.$validator.errors.items.splice(-1!==r?r:0,-1!==r?1:0),e.saveLocalStorage()}},model:{value:t.rp.fecha,callback:function(e){t.$set(t.rp,"fecha",e)},expression:"rp.fecha"}})],1)],1):a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-text-field",{key:"fecha",attrs:{"prepend-icon":"event",label:"Fecha",name:"fecha",readonly:"","error-messages":t.errors.collect("fecha")},model:{value:t.computedDateFormatted,callback:function(e){t.computedDateFormatted=e},expression:"computedDateFormatted"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm8:""}},[a("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{"no-data":"Busqueda por nombre o número de documento.",hint:"identificacion_completa","item-text":"nombre_completo","data-title":"identificacion_completa","data-subtitle":"nombre_completo",label:"Tercero",detail:t.detalleTercero,entidad:"terceros",name:"Tercero",preicon:"person",rules:"required","error-messages":t.errors.collect("Tercero"),readonly:t.readOnly,clearable:!t.readOnly,"no-btn-create":t.readOnly,"no-btn-edit":t.readOnly},on:{changeid:function(e){return t.rp.gn_tercero_id=e}},model:{value:t.rp.tercero,callback:function(e){t.$set(t.rp,"tercero",e)},expression:"rp.tercero"}})],1)],1),t._v(" "),"Contrato"===t.rp.tipo?[a("v-expand-transition",["Contrato"===t.rp.tipo?a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm12:""}},[null===t.rp.estado||"Registrado"===t.rp.estado?a("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{items:t.contratos,"item-text":"objeto","item-value":"pr_cdp_id",name:"contrato",label:"Contrato","no-data-text":"No hay contratos disponibles","error-messages":t.errors.collect("contrato"),"prepend-icon":"receipt","return-object":"",readonly:t.readOnly,clearable:!t.readOnly},on:{change:function(e){t.rp.ce_proconminuta_id=e?e.id:null,t.obtenerRubrosContrato(e)}},scopedSlots:t._u([{key:"item",fn:function(e){return[[a("v-list-tile-content",[a("v-list-tile-title",{domProps:{innerHTML:t._s(e.item.numero_contrato)}}),t._v(" "),a("v-list-tile-sub-title",{domProps:{innerHTML:t._s(e.item.objeto)}})],1)]]}}],null,!1,2698219139),model:{value:t.rp.minuta,callback:function(e){t.$set(t.rp,"minuta",e)},expression:"rp.minuta"}}):a("v-textarea",{attrs:{"auto-grow":"",rows:"1",hint:t.rp.minuta?"Número Contrato: "+t.rp.minuta.numero_contrato:"","persistent-hint":"","prepend-icon":"receipt",label:"Contrato",value:t.rp.minuta?t.rp.minuta.objeto:null,readonly:""}})],1)],1):t._e()],1)]:t._e(),t._v(" "),!t.noAplica||null!==t.rp.estado&&"Registrado"!==t.rp.estado?t._e():[a("v-expand-transition",[!t.noAplica||null!==t.rp.estado&&"Registrado"!==t.rp.estado?t._e():a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm12:""}},[a("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"CDP",items:t.cdps,filter:t.filterRubros,hint:t.rp.cdp?"Consecutivo: "+t.rp.cdp.consecutivo:null,"persistent-hint":"","item-text":"objecto","item-value":"id",readonly:t.readOnly,name:"cdp","no-data-text":"No existen datos","prepend-icon":"subtitles","return-object":"","error-messages":t.errors.collect("cdp"),clearable:""},on:{change:function(e){t.rp.pr_cdp_id=e?e.id:null,t.obtenerRubrosCdp(e)}},scopedSlots:t._u([{key:"item",fn:function(e){return[[a("v-list-tile-content",[a("v-list-tile-title",{domProps:{innerHTML:t._s("Consecutivo: "+e.item.consecutivo)}}),t._v(" "),a("v-list-tile-sub-title",{domProps:{innerHTML:t._s(e.item.objecto)}})],1)]]}}],null,!1,2908407166),model:{value:t.rp.cdp,callback:function(e){t.$set(t.rp,"cdp",e)},expression:"rp.cdp"}})],1)],1)],1)],t._v(" "),[a("v-slide-y-transition",[t.openTable?a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-subheader",{staticClass:"pl-1"},[t._v("CDP")]),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headersCDP,items:t.tableCDP,"no-data-text":"No ha escogido ningún CDP","hide-actions":""},scopedSlots:t._u([{key:"items",fn:function(e){return[a("td",[t._v(t._s(e.item.consecutivo))]),t._v(" "),a("td",[t._v(t._s(e.item.objecto))]),t._v(" "),a("td",{staticClass:"text-xs-center"},[t._v(t._s(e.item.fecha_vence))]),t._v(" "),a("td",{staticClass:"text-xs-right"},[t._v(t._s(t.currency(e.item.valor_cdp)))]),t._v(" "),a("td",{staticClass:"text-xs-right"},[t._v(t._s(t.currency(e.item.suma_valores_ejecutados)))]),t._v(" "),a("td",{staticClass:"text-xs-center"},[t._v(t._s(e.item.estado))])]}}],null,!1,3407011429)})],1)],1):t._e()],1)],t._v(" "),a("v-layout",[a("v-flex",{attrs:{xs12:""}},[a("v-textarea",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"observaciones",attrs:{"prepend-icon":"reorder","auto-grow":"",rows:"1",label:"Observaciones",name:"observaciones",required:"",readonly:t.readOnly,"error-messages":t.errors.collect("observaciones")},model:{value:t.rp.observaciones,callback:function(e){t.$set(t.rp,"observaciones",e)},expression:"rp.observaciones"}})],1)],1)],2)],1)],1)],1),t._v(" "),[a("v-slide-y-transition",[t.rp.detalles.length?a("v-layout",[a("v-flex",{staticClass:"pb-4",attrs:{xs12:""}},[[a("loading",{model:{value:t.localLoading,callback:function(e){t.localLoading=e},expression:"localLoading"}}),t._v(" "),a("v-card",[a("v-toolbar",{staticClass:"grey lighten-4 elevation-0",attrs:{dense:""}},[a("v-toolbar-title",{staticClass:"subheading"},[t._v("Detalles")]),t._v(" "),a("v-spacer")],1),t._v(" "),a("v-data-table",{attrs:{headers:t.headers,items:t.rp.detalles,loading:t.tableLoading,"hide-actions":"","rows-per-page-text":"Registros por página","rows-per-page-items":[5,10,25,{text:"Todos",value:-1}]},scopedSlots:t._u([{key:"items",fn:function(e){return[a("td",[t._v(t._s(e.item.rubro.codigo))]),t._v(" "),a("td",[t._v(t._s(e.item.rubro.nombre))]),t._v(" "),a("td",[t._v(t._s(e.item.tipo_gasto.nombre))]),t._v(" "),a("td",[t._v(t._s(t._f("numberFormat")(e.item.valor,0,"$")))]),t._v(" "),a("td",[t.readOnly?t._e():a("v-tooltip",{attrs:{top:""},model:{value:e.item.show,callback:function(r){t.$set(e.item,"show",r)},expression:"props.item.show"}},[a("v-btn",{attrs:{slot:"activator",flat:"",fab:"",dark:"",small:"",color:"white"},on:{click:function(r){return t.editarDetalle(e.index,e.item)}},slot:"activator"},[a("v-icon",{attrs:{color:"accent"}},[t._v("mode_edit")])],1),t._v(" "),a("span",[t._v("Modificar")])],1)],1)]}},{key:"pageText",fn:function(e){return[t._v("\n                                "+t._s(e.pageStart)+" - "+t._s(e.pageStop)+" de "+t._s(e.itemsLength)+"\n                              ")]}},{key:"footer",fn:function(){return[a("td",{attrs:{colspan:t.headers.length}},[a("v-spacer"),t._v(" "),a("strong",[t._v("Valor compromiso: "+t._s(t._f("numberFormat")(t.rp.valor_compromiso,0,"$")))])],1)]},proxy:!0}],null,!1,2141476840)},[t._v(" "),a("template",{slot:"no-data"},[t.tableLoading?a("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n                                  Estamos cargando los registros. "),a("v-icon",[t._v("sentiment_satisfied_alt")])],1):a("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n                                  Lo sentimos, no tenemos registros para mostrar. "),a("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1)],2)],1)]],2)],1):t._e()],1)]],2)],1)],1)],1)],1),t._v(" "),a("v-divider"),t._v(" "),"Confirmado"!==t.rp.estado&&"Anulado"!==t.rp.estado?a("v-card-actions",{staticClass:"grey lighten-4"},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"text-xs-left",attrs:{xs6:""}},[t.rp.id?a("v-btn",{attrs:{dark:"",color:"danger",loading:t.loadingSubmit},on:{click:function(e){t.dialogAnular=!0}}},[t._v("Anular")]):t._e()],1),t._v(" "),a("v-flex",{staticClass:"text-xs-right",attrs:{xs6:""}},["Confirmado"!==t.rp.estado&&"Anulado"!==t.rp.estado?a("v-btn",{attrs:{color:"primary",loading:t.loadingSubmit},on:{click:function(e){return e.preventDefault(),t.submit(e)}}},[t._v("Guardar")]):t._e(),t._v(" "),a("v-btn",{attrs:{slot:"activator",color:"success"},on:{click:t.confirmar},slot:"activator"},[t.rp.id||null!==t.rp.estado?a("span",[t._v("Confirmar")]):a("span",[t._v("Guardar y Confirmar")])])],1)],1)],1):t._e()],1)],1)},o=[],s={render:a,staticRenderFns:o};t.a=s},662:function(e,t,r){"use strict";function a(e){r(2164)}Object.defineProperty(t,"__esModule",{value:!0});var o=r(1208),s=r(2166),n=r(1),i=a,l=n(o.a,s.a,!1,i,"data-v-39567642",null);t.default=l.exports}});
//# sourceMappingURL=159.f80a7c8f98620c1c7470.js.map