webpackJsonp([354],{1313:function(t,e,i){"use strict";var a=i(252),o=i.n(a),n=i(13),s=i.n(n),l=i(14),c=i.n(l),r=i(7),d=i.n(r),v=i(58),u=i(59),m=i(12);e.a={name:"RegistroNotificaciones",props:{item:{type:Object,default:null},value:{type:Boolean,default:!1}},components:{Loading:v.default,Confirmar:function(){return i.e(289).then(i.bind(null,749))},InputDate:function(){return new Promise(function(t){t()}).then(i.bind(null,253))},InputDetailFlex:function(){return new Promise(function(t){t()}).then(i.bind(null,250))}},data:function(){return{dialogA:{loading:!1,visible:!1,content:null,item:null},newNotificacion:!1,datosBasicos:[],dialog:null,data:null,makeDialog:{show:!1,loading:!1},makeData:{id:null,mp_direccionamiento_id:null,tipo:null,notificacion_exitosa:0,aceptada:0,observaciones:null}}},watch:{value:function(t){var e=this;t||setTimeout(function(){return e.resetAll()},500)},newNotificacion:function(t){var e=this;t||setTimeout(function(){return e.resetData()},500)}},computed:{complementos:function(){return u.b.getters.complementosRegistroNotificaionDireccionamiento},infoComponent:function(){return this.$store.getters.getInfoComponent("direccionamientos")}},created:function(){this.resetAll()},methods:{resetAll:function(){this.newNotificacion=!1,this.dialog=JSON.parse(d()(this.makeDialog)),this.resetData()},resetData:function(){this.data=JSON.parse(d()(this.makeData)),this.$validator.reset()},anularDireccionamiento:function(t){this.dialogA.item=JSON.parse(d()(t)),this.dialogA.content="El direccionamiento No. "+this.dialogA.item.item.IdDireccionamiento+" será eliminado y el registro no se podrá ver ni cambiar de estado. </br><strong>¿Está seguro de continuar?</strong>",this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.loading=!1,this.dialogA.content=null,this.dialogA.item=null},aceptaAnulacion:function(){var t=this;return c()(s.a.mark(function e(){return s.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:t.dialogA.loading=!0,t.axios.delete("direccionamientos/"+t.dialogA.item.item.id).then(function(){t.$store.commit(m.b,{msg:"El direccionamiento se anuló correctamente.",color:"success"}),t.item.direccionamientos.splice(t.dialogA.item.index,1),t.cancelaAnulacion()}).catch(function(e){var i="";e.response.data.message&&(e.response.data.message.ModelState||e.response.data.message.Message)&&(e.response.data.errors=e.response.data.message.ModelState,i=" en el servidor MIPRES"),t.dialogA.loading=!1,t.$store.commit(m.a,{expression:i+" al realizar la anulación del direccionamiento. ",error:e})});case 2:case"end":return e.stop()}},e,t)}))()},submitNotificacion:function(){var t=this;this.$validator.validateAll().then(function(e){e&&(t.dialog.loading=!0,t.data.mp_direccionamiento_id=t.item.id,t.axios.post("notificaciones-mipres",t.data).then(function(e){console.log("response de notificacion",e),t.item.notificaciones.push(e.data.data),t.$store.commit(m.b,{msg:"La notificación se registró satisfactoriamente.",color:"success"}),t.dialog.loading=!1,t.resetAll()}).catch(function(e){var i="";e.response.data.message&&(e.response.data.message.ModelState||e.response.data.message.Message)&&(e.response.data.errors=e.response.data.message.Errors&&e.response.data.message.Errors.length?o()({},e.response.data.message.Errors):e.response.data.message.ModelState,i=" en el servidor MIPRES"),t.dialog.loading=!1,t.$store.commit(m.a,{expression:i+" al realizar el registro de la notificaión. ",error:e})}))})}}}},2518:function(t,e,i){"use strict";function a(t){i(2519)}Object.defineProperty(e,"__esModule",{value:!0});var o=i(1313),n=i(2521),s=i(1),l=a,c=s(o.a,n.a,!1,l,"data-v-20c1188c",null);e.default=c.exports},2519:function(t,e,i){var a=i(2520);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);i(456)("c15c52b0",a,!0,{})},2520:function(t,e,i){e=t.exports=i(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroNotificaciones.vue",sourceRoot:""}])},2521:function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-container",{staticClass:"pa-0 ma-0",attrs:{fluid:"","grid-list-sm":""}},[i("v-dialog",{attrs:{persistent:"","max-width":"1000px"},model:{value:t.value,callback:function(e){t.value=e},expression:"value"}},[i("loading",{model:{value:t.dialog.loading,callback:function(e){t.$set(t.dialog,"loading",e)},expression:"dialog.loading"}}),t._v(" "),t.item?i("v-card",[i("v-toolbar",{attrs:{dense:""}},[i("v-toolbar-title",[i("v-list-tile",[i("v-icon",{staticClass:"mr-2",staticStyle:{"margin-left":"-14px !important"},attrs:{left:""}},[t._v("phone_in_talk")]),t._v(" "),i("v-list-tile-content",[t.item.prescripcion||t.item.tutela?i("v-list-tile-title",[t._v(t._s(t.item.prescripcion?t.item.prescripcion.afiliado.nombre_completo:t.item.tutela.afiliado.nombre_completo))]):i("v-list-tile-title",[t._v("Paciente: "+t._s(t.item.TipoIDPaciente)+t._s(t.item.NoIDPaciente))]),t._v(" "),i("v-list-tile-sub-title",{staticClass:"caption"},[t._v("Direccionamiento: "+t._s(t.item.IdDireccionamiento))])],1)],1)],1),t._v(" "),i("v-spacer"),t._v(" "),i("v-btn",{attrs:{flat:"",icon:"",disabled:t.dialog.loading},on:{click:function(e){return t.$emit("input",!1)}}},[i("v-icon",[t._v("close")])],1)],1),t._v(" "),i("v-container",{attrs:{"grid-list-md":""}},[i("v-layout",{attrs:{wrap:""}},[t.datosBasicos?i("v-flex",{attrs:{xs12:""}},[i("v-expansion-panel",{staticClass:"v-expansion-panel-pi"},[i("v-expansion-panel-content",{staticClass:"v-expansion-header-dark"},[i("div",{attrs:{slot:"header"},slot:"header"},[i("v-list-tile",[i("v-icon",{staticClass:"mr-2",attrs:{color:"primary"}},[t._v("info")]),t._v(" Datos básicos del direccionamiento\n                  ")],1)],1),t._v(" "),i("v-container",{staticClass:"pa-2",attrs:{"grid-list-sm":""}},[i("v-layout",{attrs:{row:"",wrap:""}},t._l(t.datosBasicos,function(e,a){return null!==e.text?i("input-detail-flex",{key:"ib_"+a,attrs:{"flex-class":e.flexClass,label:e.label,text:0===e.text?"NO":1===e.text?"SI":e.text,hint:e.hint,"prepend-icon":e.icon}}):t._e()}),1)],1)],1)],1)],1):t._e(),t._v(" "),i("v-flex",{attrs:{xs12:""}},[i("v-slide-y-transition",[t.newNotificacion?t._e():i("v-card",{staticClass:"elevation-0"},[i("v-card-title",{staticClass:"pa-0"},[i("span",{staticClass:"title"},[t._v("Notificaciones registradas")]),t._v(" "),i("v-spacer"),t._v(" "),t.infoComponent&&t.infoComponent.permisos&&t.infoComponent.permisos.agregar?i("v-tooltip",{attrs:{left:""}},[i("v-btn",{attrs:{slot:"activator",fab:"",right:"",small:"",color:"accent",href:t.item.prescripcion&&t.item.prescripcion.afiliado.celular||t.item.tutela&&t.item.tutela.afiliado.celular?"tel:"+(t.item.prescripcion?t.item.prescripcion.afiliado.celular:t.item.tutela.afiliado.celular):""},on:{click:function(e){t.newNotificacion=!0}},slot:"activator"},[i("v-icon",[t._v("phone")])],1),t._v(" "),i("span",{domProps:{textContent:t._s("Crear notificación")}})],1):t._e()],1),t._v(" "),i("v-divider"),t._v(" "),t.item.notificaciones?i("v-list",{attrs:{"two-line":""}},[t._l(t.item.notificaciones,function(e,a){return[i("v-list-tile",{on:{click:function(t){}}},[i("v-list-tile-content",[i("v-list-tile-title",{staticClass:"subheading font-weight-bold"},[t._v(t._s(e.tipo))]),t._v(" "),i("p",{staticClass:"text-xs-justify ma-0 mt-1 caption"},[t._v(t._s(e.observaciones))])],1),t._v(" "),i("v-list-tile-action",[i("v-tooltip",{attrs:{left:""}},[i("v-btn",{attrs:{slot:"activator",flat:"",small:"",icon:"",color:e.aceptada?"success":"danger"},slot:"activator"},[i("v-icon",[t._v(t._s(e.aceptada?"check":"close"))])],1),t._v(" "),i("span",[t._v("Aceptada")])],1),t._v(" "),i("v-tooltip",{attrs:{left:""}},[i("v-btn",{attrs:{slot:"activator",flat:"",small:"",icon:"",color:e.notificacion_exitosa?"success":"danger"},slot:"activator"},[i("v-icon",[t._v(t._s(e.notificacion_exitosa?"check":"close"))])],1),t._v(" "),i("span",[t._v("Exitosa")])],1)],1)],1),t._v(" "),i("v-divider")]})],2):i("div",{staticClass:"text-xs-center grey--text lighten-2 mt-2"},[t._v("No hay notificaciones registradas")])],1)],1),t._v(" "),i("v-slide-y-transition",{attrs:{mide:"out-in"}},[i("v-card",{directives:[{name:"show",rawName:"v-show",value:t.newNotificacion,expression:"newNotificacion"}],staticClass:"elevation-0 mt-3"},[i("v-card-title",{staticClass:"pa-0"},[i("span",{staticClass:"title"},[t._v("Nueva notificación")])]),t._v(" "),i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:"",sm6:"",md6:""}},[i("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Tipo notificación",items:t.complementos.tipos,"item-value":"text","item-text":"value",name:"tipo","error-messages":t.errors.collect("tipo")},model:{value:t.data.tipo,callback:function(e){t.$set(t.data,"tipo",e)},expression:"data.tipo"}})],1),t._v(" "),i("v-flex",{attrs:{dflex:""}},[i("v-switch",{attrs:{color:"accent",label:"Aceptada","true-value":1,"false-value":0},model:{value:t.data.aceptada,callback:function(e){t.$set(t.data,"aceptada",e)},expression:"data.aceptada"}})],1),t._v(" "),i("v-flex",{attrs:{dflex:""}},[i("v-switch",{attrs:{color:"accent",label:"Exitosa","true-value":1,"false-value":0},model:{value:t.data.notificacion_exitosa,callback:function(e){t.$set(t.data,"notificacion_exitosa",e)},expression:"data.notificacion_exitosa"}})],1),t._v(" "),i("v-flex",{attrs:{xs12:""}},[i("v-textarea",{attrs:{label:"Observaciones",rows:"1","auto-grow":""},model:{value:t.data.observaciones,callback:function(e){t.$set(t.data,"observaciones",e)},expression:"data.observaciones"}})],1)],1)],1)],1)],1)],1)],1),t._v(" "),i("v-divider"),t._v(" "),t.newNotificacion?i("v-card-actions",[i("v-btn",{attrs:{disabled:t.dialog.loading},on:{click:function(e){t.newNotificacion=!1}}},[t._v("Cancelar")]),t._v(" "),i("v-spacer"),t._v(" "),i("v-btn",{staticClass:"white--text",attrs:{loading:t.dialog.loading,disabled:t.dialog.loading,color:"blue darken-1"},on:{click:t.submitNotificacion}},[t._v("\n          registrar\n        ")])],1):i("v-card-actions",[i("v-spacer"),t._v(" "),i("v-btn",{attrs:{disabled:t.dialog.loading},on:{click:function(e){return t.$emit("input",!1)}}},[t._v("Cerrar")])],1)],1):t._e()],1),t._v(" "),i("confirmar",{attrs:{value:t.dialogA.visible,loading:t.dialogA.loading,content:t.dialogA.content,"requiere-motivo":!1},on:{cancelar:t.cancelaAnulacion,aceptar:t.aceptaAnulacion}})],1)},o=[],n={render:a,staticRenderFns:o};e.a=n}});
//# sourceMappingURL=354.4fea4f2c0a0ca14f875f.js.map