webpackJsonp([4],{1147:function(t,e,a){"use strict";var o=a(13),s=a.n(o),i=a(14),n=a.n(i),r=a(7),c=a.n(r),l=a(249),d=a.n(l),u=a(58),v=a(12);e.a={name:"DesacatosDetalle",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(a.bind(null,247))},Loading:u.default,InputFile:function(){return a.e(288).then(a.bind(null,748))}},props:{tutelaId:{type:Number,default:0}},data:function(){return{desacatos:[],desacato:{},payload:null,numeroTutela:null,menuDate:!1,dialog:!1,loading:!1,tableLoading:!1,loadingSubmit:!1,headers:[{text:"No. tutela",align:"left",sortable:!1,value:"noTutela"},{text:"Fecha",align:"left",sortable:!1,value:"fecha"},{text:"Descripción",align:"left",sortable:!1,value:"descripcion"},{text:"Opciones",align:"left",sortable:!1,value:"id"}]}},created:function(){this.getData(),this.formReset()},computed:{modalTitulo:function(){return this.desacato.id?"Edición":"Nuevo desacato"},infoComponent:function(){return this.$store.getters.getInfoComponent("desacatosDetalle")},computedDateFormatted:function(){return this.formDate(this.desacato.fecha)}},methods:{getData:function(){var t=this;this.loading=!0,this.tableLoading=!0,this.axios.get("oj_tutelas/"+this.tutelaId).then(function(e){""!==e.data&&(t.desacatos=e.data.data.desacatos,t.numeroTutela=e.data.data.no_tutela,t.loading=!1,t.tableLoading=!1)}).catch(function(e){t.loading=!1,t.tableLoading=!1,t.$store.commit(v.b,{msg:"Error al traer los datos básicos de los desacatos. "+e.response,color:"danger"})})},formReset:function(){this.desacato.archivo&&this.$refs.archivoAdjunto.reset(),this.desacato={id:"",archivo:null,oj_tutela_id:this.tutelaId,fecha:null,desc_desacato:null}},formDate:function(t){if(!t)return null;var e=t.split("-"),a=d()(e,3);return a[0]+"/"+a[1]+"/"+a[2]},edit:function(t){this.dialog=!0,this.desacato=JSON.parse(c()(t))},close:function(){this.dialog=!1,this.loadingSubmit=!1,this.formReset(),this.$validator.reset()},validador:function(t){return this.$validator.validateAll(t).then(function(t){return t})},addFormData:function(){var t=new FormData;t.append("id",this.desacato.id),t.append("oj_tutela_id",this.desacato.oj_tutela_id),t.append("fecha",this.desacato.fecha),t.append("desc_desacato",this.desacato.desc_desacato),t.append("archivo",void 0===this.desacato.archivo?"":this.desacato.archivo),this.payload=t},save:function(){var t=this;return n()(s.a.mark(function e(){var a,o;return s.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$refs.archivoAdjunto.validate("formDesacatos");case 2:return a=e.sent,e.next=5,t.validador("formDesacatos");case 5:if(e.t0=e.sent,!e.t0){e.next=8;break}e.t0=a;case 8:if(!e.t0){e.next=15;break}t.loadingSubmit=!0,t.addFormData(),o=t.desacato.id?t.axios.post("oj_tutdesacatos/"+t.desacato.id,t.payload):t.axios.post("oj_tutdesacatos",t.payload),o.then(function(e){t.desacato.id?(t.$store.commit(v.b,{msg:"El desacato se actualizó correctamente",color:"success"}),t.desacatos.splice(t.desacatos.findIndex(function(t){return t.id===e.data.data.id}),1,e.data.data)):(t.$store.commit(v.b,{msg:"El desacato se creó correctamente",color:"success"}),t.desacatos.splice(0,0,e.data.data)),t.close()}).catch(function(e){t.$store.commit(v.b,{msg:"Hay un error al guardar el registro. "+e.response,color:"danger"}),t.close()}),e.next=16;break;case 15:t.$store.commit(v.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 16:case"end":return e.stop()}},e,t)}))()}}}},2055:function(t,e,a){var o=a(2056);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);a(456)("1cb3368f",o,!0,{})},2056:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DesacatosDetalle.vue",sourceRoot:""}])},2057:function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-dialog",{attrs:{width:"720px"},model:{value:t.dialog,callback:function(e){t.dialog=e},expression:"dialog"}},[a("v-form",{attrs:{"data-vv-scope":"formDesacatos"}},[a("v-card",[a("v-card-title",{staticClass:"grey lighten-3 elevation-0"},[a("span",{staticClass:"title"},[t._v(t._s(t.modalTitulo))])]),t._v(" "),a("v-container",{attrs:{fluid:"","grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:150",expression:"'required|max:150'"}],key:"no. tutela",attrs:{label:"No. Tutela","prepend-icon":"description",disabled:"",name:"no. tutela","error-messages":t.errors.collect("no. tutela")},model:{value:t.numeroTutela,callback:function(e){t.numeroTutela=e},expression:"numeroTutela"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-menu",{ref:"menuDate",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:t.menuDate,callback:function(e){t.menuDate=e},expression:"menuDate"}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha","prepend-icon":"event",readonly:"",name:"fecha","error-messages":t.errors.collect("fecha")},slot:"activator",model:{value:t.computedDateFormatted,callback:function(e){t.computedDateFormatted=e},expression:"computedDateFormatted"}}),t._v(" "),a("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){t.menuDate=!1},change:function(){var e=t.$validator.errors.items.findIndex(function(t){return"fecha"===t.field});t.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:t.desacato.fecha,callback:function(e){t.$set(t.desacato,"fecha",e)},expression:"desacato.fecha"}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-layout",{attrs:{"align-center":"","fill-height":""}},[a("v-flex",{attrs:{"d-flex":"",xs10:"",sm8:""}},[a("input-file",{ref:"archivoAdjunto",staticClass:"mb-3",attrs:{label:"Adjuntar archivo","file-name":t.desacato.archivo?t.desacato.archivo.nombre:null,accept:"application/pdf",hint:"Extenciones aceptadas: pdf","prepend-icon":"attach_file"},model:{value:t.desacato.archivo,callback:function(e){t.$set(t.desacato,"archivo",e)},expression:"desacato.archivo"}})],1),t._v(" "),a("v-flex",{attrs:{"d-flex":"",xs2:"",sm4:""}},[a("v-tooltip",{attrs:{bottom:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",color:"success",small:"",href:t.desacato.archivo?t.desacato.archivo.url_signed:"",target:"_blank"},slot:"activator"},[a("v-icon",[t._v("remove_red_eye")])],1),t._v(" "),a("span",[t._v("Ver archivo")])],1)],1)],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-textarea",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"descripcion",attrs:{label:"Descripción","prepend-icon":"format_align_left",name:"descripcion","error-messages":t.errors.collect("descripcion")},model:{value:t.desacato.desc_desacato,callback:function(e){t.$set(t.desacato,"desc_desacato",e)},expression:"desacato.desc_desacato"}})],1)],1)],1),t._v(" "),a("v-card-actions",{staticClass:"grey lighten-4"},[a("v-spacer"),t._v(" "),a("v-btn",{attrs:{loading:t.loadingSubmit},on:{click:t.close}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{color:"primary",loading:t.loadingSubmit,disabled:t.errors.any()},on:{click:t.save}},[t._v("Guardar")])],1)],1)],1)],1),t._v(" "),a("v-card",[a("toolbar-list",{attrs:{info:t.infoComponent,title:"Desacatos",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear Desacato",btnplustruncate:""},on:{click:function(e){t.dialog=!0}}}),t._v(" "),a("v-container",{attrs:{fluid:""}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.desacatos,loading:t.tableLoading,"rows-per-page-text":"Registros por página"},scopedSlots:t._u([{key:"items",fn:function(e){return[a("td",[t._v(t._s(t.numeroTutela))]),t._v(" "),a("td",[t._v(t._s(e.item.fecha))]),t._v(" "),a("td",[t._v(t._s(e.item.desc_desacato))]),t._v(" "),a("td",[a("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-icon",[t._v("chevron_left")]),t._v(" "),a("v-icon",[t._v("close")])],1),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(a){return t.edit(e.item)}},slot:"activator"},[a("v-icon",{attrs:{color:"accent"}},[t._v("mode_edit")])],1),t._v(" "),a("span",[t._v("Editar")])],1)],1)],1)]}}])},[t._v(" "),a("template",{slot:"no-data"},[t.tableLoading?a("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n            Estamos cargando los registros. "),a("v-icon",[t._v("sentiment_satisfied_alt")])],1):a("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n            Lo sentimos, no tenemos registros para mostrar. "),a("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1)],2)],1)],1)],1)},s=[],i={render:o,staticRenderFns:s};e.a=i},469:function(t,e,a){"use strict";function o(t){a(2055)}Object.defineProperty(e,"__esModule",{value:!0});var s=a(1147),i=a(2057),n=a(1),r=o,c=n(s.a,i.a,!1,r,"data-v-da30613a",null);e.default=c.exports}});
//# sourceMappingURL=4.6cbccf6cdb9f0c6a08da.js.map