webpackJsonp([298],{1058:function(e,a,i){"use strict";function t(e){i(1771)}Object.defineProperty(a,"__esModule",{value:!0});var n=i(1059),o=i(1776),r=i(1),l=t,s=r(n.a,o.a,!1,l,"data-v-0472733b",null);a.default=s.exports},1059:function(e,a,i){"use strict";var t=i(7),n=i.n(t),o=i(13),r=i.n(o),l=i(14),s=i.n(l),d=i(23),u=i.n(d),c=i(58),m=i(844),v=i(59),p=i(12),f=i(248),_=i(251);a.a={name:"RegistroAfiliacionSubsidiado",props:["afitramite","parametros"],components:{Loading:c.default,FormInfoBasicaAfiliado:m.a,PostuladorV2:function(){return new Promise(function(e){e()}).then(i.bind(null,61))},RegistroDecautramites:function(){return i.e(333).then(i.bind(null,890))},RegistroAnetramites:function(){return i.e(309).then(i.bind(null,894))},RegistroBeneficiarios:function(){return i.e(308).then(i.bind(null,902))}},data:function(){return{detallePagador:function(){return i.e(295).then(i.bind(null,861))},detalleAfiliado:function(){return i.e(290).then(i.bind(null,750))},filterMunicipios:function(e,a){var i=function(e){return null!=e?e:""},t=i(e.nombre+" "+e.nombre_departamento),n=i(a);return t.toString().toLowerCase().indexOf(n.toString().toLowerCase())>-1},afiliadoId:null,value:null,traslado:null,ips:{nombre_razon_social:null,identificacion:null},afiliado_pagador:{gn_municipio_id:null,arl:null,as_afiliado_id:null,as_arl_id:null,as_formtrasmov_id:null,as_pagadore_id:null,fecha_vinculacion:null,ibc:null,id:null,pagador:null},validStep:{One:!0,Two:!0,Three:!0,Four:!0},loadingSubmit:!1,stepActual:1,filterCiius:function(e,a){var i=function(e){return null!=e?e:""},t=i(e.codigo+" "+e.nombre),n=i(a);return t.toString().toLowerCase().indexOf(n.toString().toLowerCase())>-1},decauttramites:[]}},watch:{afitramite:function(e){this.value=e},"value.afiliado":function(e){e&&(this.value.ficha_sisben=e.ficha_sisben,this.value.puntaje_sisben=e.puntaje_sisben,this.value.afiliado.as_regimen_id=this.value.as_regimen_id,this.value.afiliado.as_tipafiliado_id=this.value.as_tipafiliado_id),null===e&&(this.value.as_afiliado_id=null)},"value.nucleo_familiar.length":function(e){e>-1&&this.value.id>0&&this.$store.commit(f.c,{item:{afiliado:this.value.afiliado.nombre_completo,beneficiarios:this.value.nucleo_familiar.length,estado:this.value.estado,id:this.value.id,identificacion:this.value.afiliado.identificacion_completa,recien_nacido:this.value.recien_nacido},estado:"editar",key:null})}},beforeMount:function(){var e=this;_.a.extend("afiliadoValidoAfiliacionS",{validate:function(a,i){return new u.a(function(i){if(null!==a&&void 0!==a&&""!==a){var t=e.value.as_afiliado_id?e.value.afiliado&&(1===e.value.afiliado.estado&&e.value.afiliado.id!==e.value.idAfiliadoValido||1!==e.value.afiliado.estado&&e.value.afiliado.id===e.value.idAfiliadoValido)?{valido:!0,mensaje:null}:{valido:!1,mensaje:"El afiliado seleccionado no es valido para este trámite, su estado debe ser: "+e.complementos.estadosAfiliado.find(function(e){return 1===e.id}).nombre+"."}:{valido:!0,mensaje:null};return i({valid:t.valido,data:{message:t.mensaje}})}})},getMessage:function(e,a,i){return i.message}})},mounted:function(){this.value=this.afitramite},computed:{complementos:function(){return v.b.getters.complementosFormAfiliacion},isEditable:function(){return this.value&&null!==this.value.id}},methods:{goFinalizar:function(){var e=this;return s()(r.a.mark(function a(){var i;return r.a.wrap(function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,e.$validator.validateAll("formStep1");case 2:if(!a.sent){a.next=9;break}return a.next=5,e.valueClean();case 5:i=a.sent,e.$emit("endsubmit",i),a.next=11;break;case 9:e.stepActual="1",e.next();case 11:case"end":return a.stop()}},a,e)}))()},resetAF:function(){return this.value.afiliado_pagador},valueClean:function(){var e=JSON.parse(n()(this.value));return delete e.afiliado,delete e.anexos,delete e.ips,delete e.declaraciones,delete e.nucleo_familiar,delete e.pagador,delete e.clase_cotizante,delete e.fecha_radicacion_array,e},submitAfitramite:function(){var e=this;return s()(r.a.mark(function a(){var i,t;return r.a.wrap(function(a){for(;;)switch(a.prev=a.next){case 0:return e.loadingSubmit=!0,i=e.value.id?"editar":"crear",a.next=4,e.valueClean();case 4:return t=a.sent,a.abrupt("return",new u.a(function(a,o){("editar"===i?e.axios.put("formafiliaciones/"+t.id,t):e.axios.post("formafiliaciones",t)).then(function(t){var o=e.value.nf_ciiu_id;null!==t.data.formafiliacion.afiliado_pagador&&void 0!==t.data.formafiliacion.afiliado_pagador||(t.data.formafiliacion.afiliado_pagador=JSON.parse(n()(e.resetAF()))),t.data.formafiliacion.idAfiliadoValido=t.data.formafiliacion.as_afiliado_id,e.value=t.data.formafiliacion,e.value.nf_ciiu_id=o,e.$emit("change",e.value),e.$store.commit(f.c,{item:t.data.formafiliacion_o,estado:i,key:e.parametros.key}),a(!0)}).catch(function(a){e.$store.commit(p.b,{msg:"Hay un error al guardar el registro del trámite de afiliación. "+a,color:"danger"}),e.loadingSubmit=!1,o(a)})}));case 6:case"end":return a.stop()}},a,e)}))()},next:function(){var e=this;return s()(r.a.mark(function a(){return r.a.wrap(function(a){for(;;)switch(a.prev=a.next){case 0:e.stepActual=parseInt(e.stepActual),e.loadingSubmit=!0,a.t0=e.stepActual,a.next=1===a.t0?5:2===a.t0?19:3===a.t0?22:25;break;case 5:return a.next=7,e.$validator.validateAll("formStep1");case 7:if(!a.sent){a.next=16;break}return e.validStep.One=!0,a.next=11,e.submitAfitramite();case 11:if(!a.sent){a.next=14;break}e.stepActual++,e.loadingSubmit=!1;case 14:a.next=18;break;case 16:e.validStep.One=!1,e.loadingSubmit=!1;case 18:return a.abrupt("break",25);case 19:case 22:return e.stepActual++,e.loadingSubmit=!1,a.abrupt("break",25);case 25:case"end":return a.stop()}},a,e)}))()}}}},1771:function(e,a,i){var t=i(1772);"string"==typeof t&&(t=[[e.i,t,""]]),t.locals&&(e.exports=t.locals);i(456)("37c3ecb0",t,!0,{})},1772:function(e,a,i){a=e.exports=i(455)(!0),a.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroAfiliacionS.vue",sourceRoot:""}])},1776:function(e,a,i){"use strict";var t=function(){var e=this,a=e.$createElement,i=e._self._c||a;return e.value?i("v-stepper",{attrs:{vertical:""},model:{value:e.stepActual,callback:function(a){e.stepActual=a},expression:"stepActual"}},[i("loading",{model:{value:e.loadingSubmit,callback:function(a){e.loadingSubmit=a},expression:"loadingSubmit"}}),e._v(" "),i("v-stepper-step",{attrs:{editable:e.isEditable,complete:e.stepActual>1,step:"1",rules:[function(){return e.validStep.One}]}},[e._v("\n      Datos básicos\n      "),e.validStep.One?i("small",[e._v("Trámite y afiliado")]):i("small",[e._v("Hay datos por diligenciar.")])]),e._v(" "),i("v-stepper-content",{attrs:{step:1}},[i("v-card",{attrs:{color:"grey lighten-1"}},[i("v-container",{attrs:{fluid:"","grid-list-xl":""}},[i("v-form",{attrs:{"data-vv-scope":"formStep1"}},[i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:""}},[i("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required|afiliadoValidoAfiliacionS:"+e.value.recien_nacido,expression:"'required|afiliadoValidoAfiliacionS:' + value.recien_nacido"}],attrs:{"no-data":"Busqueda por nombre o número de documento.",hint:"identificacion_completa","item-text":"nombre_completo","data-title":"identificacion_completa","data-subtitle":"nombre_completo",label:"Afiliado",detail:e.detalleAfiliado,entidad:"afiliados",preicon:"person",name:"Afiliado","no-btn-edit":"",rules:"required|afiliadoValidoAfiliacionS:"+e.value.recien_nacido,"error-messages":e.errors.collect("Afiliado"),"slot-append":{template:'<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>',props:["value"]},"slot-prepend":e.value.afiliado?{template:'<mini-card-detail :data="value.mini_afiliado" alone-icon/>',props:["value"]}:null},on:{changeid:function(a){return e.value.as_afiliado_id=a}},model:{value:e.value.afiliado,callback:function(a){e.$set(e.value,"afiliado",a)},expression:"value.afiliado"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:""}},[i("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Régimen",items:e.complementos.regimenes,"item-value":"id","item-text":"nombre",name:"régimen",required:"","error-messages":e.errors.collect("régimen")},model:{value:e.value.as_regimene_id,callback:function(a){e.$set(e.value,"as_regimene_id",a)},expression:"value.as_regimene_id"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:""}},[i("v-text-field",{attrs:{label:"Tipo afiliado",value:e.complementos.tipafiliados&&e.value.as_tipafiliado_id&&e.complementos.tipafiliados.find(function(a){return a.id===e.value.as_tipafiliado_id}).nombre,disabled:""}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:""}},[i("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Municipio afiliación",items:e.complementos.municipios,"item-value":"id","item-text":"nombre",name:"Municipio afiliación",required:"","error-messages":e.errors.collect("Municipio afiliación"),filter:e.filterMunicipios,"persistent-hint":"",hint:e.value.gn_municipio_id&&e.complementos.municipios?e.complementos.municipios.find(function(a){return a.id===e.value.gn_municipio_id}).nombre_departamento:""},scopedSlots:e._u([{key:"item",fn:function(a){return[[i("v-list-tile-content",[i("v-list-tile-title",{domProps:{innerHTML:e._s(a.item.nombre)}}),e._v(" "),i("v-list-tile-sub-title",{domProps:{innerHTML:e._s(a.item.nombre_departamento)}})],1)]]}}],null,!1,817490016),model:{value:e.value.gn_municipio_id,callback:function(a){e.$set(e.value,"gn_municipio_id",a)},expression:"value.gn_municipio_id"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:""}},[i("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{disabled:!e.value.gn_municipio_id,"no-data":"Busqueda por NIT, razon social u código de habilitación.",hint:"identificacion","item-text":"nombre_razon_social","data-title":"identificacion","data-subtitle":"nombre_razon_social",label:"IPS",entidad:"entidades_contrato",preicon:"location_city",name:"ips",rules:"required","error-messages":e.errors.collect("ips"),"route-params":"rs_tipentidade_id=1&afiliado="+e.value.as_afiliado_id+"&municipio="+e.value.gn_municipio_id,"no-btn-create":"","min-characters-search":3},on:{changeid:function(a){return e.value.rs_ips_id=a}},model:{value:e.ips,callback:function(a){e.ips=a},expression:"ips"}})],1),e._v(" "),i("v-flex",{directives:[{name:"show",rawName:"v-show",value:1===e.value.as_regimene_id,expression:"value.as_regimene_id === 1"}],attrs:{xs12:"",sm12:"",md6:""}},[i("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Tipo cotizante",items:e.complementos.clasecotizantes,"item-value":"id","item-text":"descripcion",name:"Tipo cotizante",required:"","error-messages":e.errors.collect("Tipo cotizante"),disabled:1!==e.value.as_regimene_id,clearable:""},scopedSlots:e._u([{key:"item",fn:function(a){return[[i("v-list-tile-content",{staticClass:"pl-1 pt-0"},[i("v-list-tile-title",{domProps:{innerHTML:e._s(a.item.descripcion)}})],1)]]}}],null,!1,1464197477),model:{value:e.value.as_clasecotizante_id,callback:function(a){e.$set(e.value,"as_clasecotizante_id",a)},expression:"value.as_clasecotizante_id"}})],1),e._v(" "),i("v-flex",{directives:[{name:"show",rawName:"v-show",value:1===e.value.as_regimene_id,expression:"value.as_regimene_id === 1"}],attrs:{xs12:"",sm12:"",md6:""}},[i("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Actividad económica",items:e.complementos.ciius,"item-value":"id","item-text":"nombre",name:"Actividad económica",required:"","error-messages":e.errors.collect("Actividad económica"),disabled:1!==e.value.as_regimene_id,filter:e.filterCiius},scopedSlots:e._u([{key:"item",fn:function(a){return[[i("v-list-tile-content",{staticClass:"pl-1 pt-0"},[i("v-list-tile-title",{domProps:{innerHTML:e._s(a.item.codigo)}}),e._v(" "),i("v-list-tile-sub-title",{domProps:{innerHTML:e._s(a.item.nombre)}})],1)]]}}],null,!1,261594106),model:{value:e.value.nf_ciiu_id,callback:function(a){e.$set(e.value,"nf_ciiu_id",a)},expression:"value.nf_ciiu_id"}})],1)],1),e._v(" "),i("v-toolbar",{directives:[{name:"show",rawName:"v-show",value:1===e.value.as_regimene_id,expression:"value.as_regimene_id === 1"}],attrs:{dense:""}},[i("v-icon",[e._v("supervisor_account")]),e._v(" "),i("v-toolbar-title",{domProps:{innerHTML:e._s("Relación con el aportante")}})],1),e._v(" "),i("v-layout",{directives:[{name:"show",rawName:"v-show",value:1===e.value.as_regimene_id,expression:"value.as_regimene_id === 1"}],attrs:{rpw:"",wrap:""}},[i("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[i("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{"prepend-icon":"location_city",label:"ARL",items:e.complementos.arls,"item-value":"id","item-text":"nombre",name:"ARL",required:"","error-messages":e.errors.collect("ARL"),disabled:1!==e.value.as_regimene_id},model:{value:e.value.afiliado_pagador.as_arl_id,callback:function(a){e.$set(e.value.afiliado_pagador,"as_arl_id",a)},expression:"value.afiliado_pagador.as_arl_id"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"required|fechaValida",expression:"'required|fechaValida'"}],attrs:{label:"Fecha vinvulación (Año-Mes-Día)",format:"YYYY-MM-DD",name:"Fecha vinvulación","error-messages":e.errors.collect("Fecha vinvulación"),disabled:1!==e.value.as_regimene_id},model:{value:e.value.afiliado_pagador.fecha_vinculacion,callback:function(a){e.$set(e.value.afiliado_pagador,"fecha_vinculacion",a)},expression:"value.afiliado_pagador.fecha_vinculacion"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("input-number",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"IBC",icon:"attach_money",precision:0,name:"IBC","error-messages":e.errors.collect("IBC"),disabled:1!==e.value.as_regimene_id},model:{value:e.value.afiliado_pagador.ibc,callback:function(a){e.$set(e.value.afiliado_pagador,"ibc",a)},expression:"value.afiliado_pagador.ibc"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:""}},[i("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{"no-data":"Busqueda por nombre o número de documento.",hint:"identificacion","item-text":"razon_social","data-title":"identificacion","data-subtitle":"razon_social",label:"Aportante",detail:e.detallePagador,entidad:"pagadores",preicon:"person",name:"Aportante",rules:"required","error-messages":e.errors.collect("Aportante"),disabled:1!==e.value.as_regimene_id},on:{changeid:function(a){return e.value.afiliado_pagador.as_pagadore_id=a}},model:{value:e.value.afiliado_pagador.pagador,callback:function(a){e.$set(e.value.afiliado_pagador,"pagador",a)},expression:"value.afiliado_pagador.pagador"}})],1)],1)],1)],1)],1),e._v(" "),i("v-btn",{attrs:{color:"primary"},nativeOn:{click:function(a){return e.next(a)}}},[e._v("Siguiente")])],1),e._v(" "),i("div",{attrs:{id:"form-beneficiario"}}),e._v(" "),i("v-stepper-step",{attrs:{editable:e.isEditable,complete:e.stepActual>2,step:"2",rules:[function(){return e.validStep.Two}]}},[e._v("\n      Beneficiarios\n      "),e.validStep.Two?i("small",[e._v("Datos de los nucleo_familiar")]):i("small",[e._v("Hay datos por diligenciar.")])]),e._v(" "),i("v-stepper-content",{attrs:{step:2}},[i("registro-beneficiarios",{attrs:{"root-path":"formafiliaciones"},model:{value:e.value,callback:function(a){e.value=a},expression:"value"}}),e._v(" "),i("v-btn",{attrs:{flat:""},nativeOn:{click:function(a){e.stepActual--}}},[e._v("Anterior")]),e._v(" "),i("v-btn",{attrs:{color:"primary"},nativeOn:{click:function(a){return e.next(a)}}},[e._v("Siguiente")])],1),e._v(" "),i("div",{attrs:{id:"form-anexo"}}),e._v(" "),i("v-stepper-step",{attrs:{editable:e.isEditable,complete:e.stepActual>3,step:"3",rules:[function(){return e.validStep.Three}]}},[e._v("\n      Declaraciones y autorizaciones\n      "),e.validStep.Three?i("small",[e._v("Lista de chequeo")]):i("small",[e._v("Hay datos por diligenciar.")])]),e._v(" "),i("v-stepper-content",{attrs:{step:3}},[i("registro-decautramites",{attrs:{ownerid:e.value.id,"root-path":"formafiliaciones"},model:{value:e.decauttramites,callback:function(a){e.decauttramites=a},expression:"decauttramites"}}),e._v(" "),i("v-btn",{attrs:{flat:""},nativeOn:{click:function(a){e.stepActual--}}},[e._v("Anterior")]),e._v(" "),i("v-btn",{attrs:{color:"primary"},nativeOn:{click:function(a){return e.next(a)}}},[e._v("Siguiente")])],1),e._v(" "),i("v-stepper-step",{attrs:{editable:e.isEditable,complete:e.stepActual>4,step:"4",rules:[function(){return e.validStep.Four}]}},[e._v("\n      Anexos\n      "),e.validStep.Four?i("small",[e._v("Lista de chequeo")]):i("small",[e._v("Hay datos por diligenciar.")])]),e._v(" "),i("v-stepper-content",{attrs:{step:4}},[i("registro-anetramites",{attrs:{"root-path":"formafiliaciones"},model:{value:e.value,callback:function(a){e.value=a},expression:"value"}}),e._v(" "),i("v-btn",{attrs:{flat:""},nativeOn:{click:function(a){e.stepActual--}}},[e._v("Anterior")]),e._v(" "),i("v-btn",{attrs:{color:"primary"},nativeOn:{click:function(a){return e.goFinalizar(a)}}},[e._v("Finalizar")])],1)],1):e._e()},n=[],o={render:t,staticRenderFns:n};a.a=o},778:function(e,a,i){"use strict";var t=i(23),n=i.n(t),o=i(59),r=i(251),l=i(12);a.a={name:"FormInfoBasicaAfiliado",props:["value","tiposIdentidad","afitramitern"],components:{},filters:{},data:function(){return{filterMunicipiosExpedicion:function(e,a){var i=function(e){return null!=e?e:""},t=i(e.nombre+" "+e.nombre_departamento),n=i(a);return t.toString().toLowerCase().indexOf(n.toString().toLowerCase())>-1},filterTiposIdentidad:function(e,a){var i=function(e){return null!=e?e:""},t=i(e.abreviatura+" "+e.nombre),n=i(a);return t.toString().toLowerCase().indexOf(n.toString().toLowerCase())>-1},ips:null,minDateI:"1900-01-01",maxDateI:"",menuDate:!1,menuDatex:!1,minDate:"1900-01-01",maxDate:(new Date).toISOString().substr(0,10),maxDateF:(new Date).toISOString().substr(0,10),maxDateFI:(new Date).toISOString().substr(0,10)}},watch:{value:function(){console.log(this.value),this.$validator.reset()},menuDate:function(e){var a=this;e&&this.$nextTick(function(){return a.$refs.picker.activePicker="YEAR"})},"value.fecha_expedicion":function(e){if(void 0!==e&&null!==e){var a=e,i=a.split("/");this.maxDate=i[2]+"-"+i[1]+"-"+i[0];var t=this.maxDate;this.maxDate=this.moment(t).subtract(this.edad_minima_valida,"months").format("YYYY-MM-DD"),this.maxDateI=this.moment(t).subtract(this.edad_minima_valida,"months").format("DD/MM/YYYY")}},tipo_id:function(e){e&&(console.log(e.edad_minima),this.value.fecha_expedicion||void 0!==this.value.fecha_expedicion||"0000-00-00"!==this.value.fecha_expedicion||null!==this.value.fecha_expedicion||(this.maxDate=this.moment().subtract(e.edad_minima,"months").format("YYYY-MM-DD")),this.minDate=this.moment().subtract(e.edad_maxima,"months").subtract(e.plazo_cambio,"days").format("YYYY-MM-DD"),this.minDateI=this.moment().subtract(e.edad_maxima,"months").subtract(e.plazo_cambio,"days").format("DD/MM/YYYY"),this.maxDateFI=this.moment(this.maxDateF).format("DD/MM/YYYY"))}},beforeMount:function(){var e=this;r.a.extend("uniqueAfiliado",{validate:function(a,i){return new n.a(function(t){null!==a&&void 0!==a&&""!==a&&e.axios.get("afiliados?id="+i[0]+"&identificacion="+a).then(function(e){return t({valid:!(200===e.status&&e.data&&e.data.data&&e.data.data.length&&a!==i[1]),data:{message:"ya se encuentra registrado un afiliado con ésta identificación."}})}).catch(function(a){e.$store.commit(l.a,{expression:" al intentar validar la identificación del afiliado. ",error:a})})})},getMessage:function(e,a,i){return i.message}})},mounted:function(){console.log(this.afitramitern)},computed:{idsTipoIdentidad:function(){var e=[];return this.tiposIdentidad&&this.tiposIdentidad.forEach(function(a){e.push(a.id)}),e},complementos:function(){return o.b.getters.complementosFormInfoBasicaAfiliado},tipo_id:function(){var e=this;return this.complementos.tipdocidentidades&&this.complementos.tipdocidentidades.length&&this.value&&this.value.gn_tipdocidentidad_id?this.complementos.tipdocidentidades.find(function(a){return a.id===e.value.gn_tipdocidentidad_id}):null},longitudDocumento:function(){var e=this;return this.complementos.tipdocidentidades&&this.complementos.tipdocidentidades.length&&this.value&&this.value.gn_tipdocidentidad_id?this.complementos.tipdocidentidades.find(function(a){return a.id===e.value.gn_tipdocidentidad_id}).longitud:null}},methods:{validate:function(){return this.$validator.validateAll()}}}},844:function(e,a,i){"use strict";function t(e){i(845)}var n=i(778),o=i(847),r=i(1),l=t,s=r(n.a,o.a,!1,l,"data-v-fb9b5dd0",null);a.a=s.exports},845:function(e,a,i){var t=i(846);"string"==typeof t&&(t=[[e.i,t,""]]),t.locals&&(e.exports=t.locals);i(456)("8c4d331a",t,!0,{})},846:function(e,a,i){a=e.exports=i(455)(!0),a.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"FormInfoBasicaAfiliado.vue",sourceRoot:""}])},847:function(e,a,i){"use strict";var t=function(){var e=this,a=e.$createElement,i=e._self._c||a;return i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Tipo identificación",items:e.complementos&&e.complementos.tipdocidentidades?e.complementos.tipdocidentidades.filter(function(a){return a.id===e.idsTipoIdentidad.find(function(e){return a.id===e})}):null,"item-value":"id","item-text":"nombre",name:"gn_tipocidentidad_id","data-vv-as":"Tipo identificación",required:"","error-messages":e.errors.collect("gn_tipocidentidad_id"),filter:e.filterTiposIdentidad},scopedSlots:e._u([{key:"item",fn:function(a){return[a.item?[i("v-list-tile-content",[i("v-list-tile-title",{domProps:{innerHTML:e._s(a.item.abreviatura)}}),e._v(" "),i("v-list-tile-sub-title",{domProps:{innerHTML:e._s(a.item.nombre)}})],1)]:e._e()]}}]),model:{value:e.value.gn_tipdocidentidad_id,callback:function(a){e.$set(e.value,"gn_tipdocidentidad_id",a)},expression:"value.gn_tipdocidentidad_id"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"uniqueAfiliado:"+(e.value.afiliado?e.value.afiliado.id+","+e.value.afiliado.identificacion:null)+"|required|alpha_num|max:"+e.longitudDocumento,expression:"'uniqueAfiliado:' + (value.afiliado ? value.afiliado.id + ',' + value.afiliado.identificacion : null) + '|required|alpha_num|max:' + longitudDocumento"}],attrs:{label:"Identificación",name:"Identificación",required:"","error-messages":e.errors.collect("Identificación"),maxlength:e.longitudDocumento,counter:e.longitudDocumento},model:{value:e.value.identificacion,callback:function(a){e.$set(e.value,"identificacion",a)},expression:"value.identificacion"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY|date_between:"+e.minDateI+","+e.maxDateFI+",true",expression:"'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateI + ',' + maxDateFI + ',true')"}],attrs:{label:"Fecha expedicion (Día/Mes/Año)",format:"DD/MM/YYYY",name:"Fecha expedicion",min:e.minDate,max:e.maxDateF,"error-messages":e.errors.collect("Fecha expedicion")},model:{value:e.value.fecha_expedicion,callback:function(a){e.$set(e.value,"fecha_expedicion",a)},expression:"value.fecha_expedicion"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("input-date",{directives:[{name:"validate",rawName:"v-validate",value:"required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY|date_between:"+e.minDateI+","+e.maxDateI+",true",expression:"'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateI + ',' + maxDateI + ',true')"}],attrs:{label:"Fecha nacimiento (Día/Mes/Año)",format:"DD/MM/YYYY",name:"Fecha nacimiento",min:e.minDate,max:e.maxDate,"error-messages":e.errors.collect("Fecha nacimiento")},model:{value:e.value.fecha_nacimiento,callback:function(a){e.$set(e.value,"fecha_nacimiento",a)},expression:"value.fecha_nacimiento"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"value.apellido1",expression:"'value.apellido1'"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Primer apellido",name:"Primer apellido",required:"","error-messages":e.errors.collect("Primer apellido")},model:{value:e.value.apellido1,callback:function(a){e.$set(e.value,"apellido1",a)},expression:"value.apellido1"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"value.apellido2",expression:"'value.apellido2'"}],attrs:{label:"Segundo apellido"},model:{value:e.value.apellido2,callback:function(a){e.$set(e.value,"apellido2",a)},expression:"value.apellido2"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"value.nombre1",expression:"'value.nombre1'"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Primer nombre",name:"Primer nombre",required:"","error-messages":e.errors.collect("Primer nombre")},model:{value:e.value.nombre1,callback:function(a){e.$set(e.value,"nombre1",a)},expression:"value.nombre1"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md3:""}},[i("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"value.nombre2",expression:"'value.nombre2'"}],attrs:{label:"Segundo nombre"},model:{value:e.value.nombre2,callback:function(a){e.$set(e.value,"nombre2",a)},expression:"value.nombre2"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:"",md2:""}},[i("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Sexo",items:e.complementos.sexos,"item-value":"id","item-text":"nombre",name:"Sexo",required:"","error-messages":e.errors.collect("Sexo")},model:{value:e.value.gn_sexo_id,callback:function(a){e.$set(e.value,"gn_sexo_id",a)},expression:"value.gn_sexo_id"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm6:""}},[i("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{"no-data":"Busqueda por NIT, razon social u código de habilitación.",hint:"identificacion","item-text":"nombre_razon_social","data-title":"identificacion","data-subtitle":"nombre_razon_social",label:"IPS",entidad:"entidades_contrato",preicon:"location_city",name:"ips",rules:"required","error-messages":e.errors.collect("ips"),"route-params":"rs_tipentidade_id=1&afiliado="+(null!==e.value.responsable_id&&void 0!==e.value.responsable_id?e.value.responsable_id:e.value.as_afiliado_id),"no-btn-create":"","min-characters-search":3},on:{changeid:function(a){return e.value.rs_entidade_id=a}},model:{value:e.ips,callback:function(a){e.ips=a},expression:"ips"}})],1)],1)},n=[],o={render:t,staticRenderFns:n};a.a=o}});
//# sourceMappingURL=298.3c406508d716536ab753.js.map