webpackJsonp([320],{1234:function(t,e,a){"use strict";var o=a(7),r=a.n(o),i=a(59),s=a(58),n=a(12);e.a={name:"RegistroOtros",props:["value"],components:{Loading:s.default,PostuladorV2:function(){return new Promise(function(t){t()}).then(a.bind(null,61))},dataTable:function(){return new Promise(function(t){t()}).then(a.bind(null,60))}},data:function(){return{showFormRegistro:!1,loading:!1,item:null,makeItem:{otro:null,codigo:null,id:null,descripcion:null,rs_otrosentidad_id:null,rs_contratos_id:null,tarifa:null,tarifa_entidad:null},dataTable:{nameItemState:null,route:null,makeHeaders:[{text:"Código",align:"left",sortable:!0,value:"codigo"},{text:"Descripción",align:"left",sortable:!0,value:"descripcion"},{text:"Tarifa ofertada",align:"right",sortable:!1,value:"tarifa_entidad",classData:"text-xs-right",component:{component:{template:"<span className=\"white--text caption\">{{'$'}}{{value.tarifa_entidad | numberFormat(2)}}</span>",props:["value"]}}},{text:"Tarifa contratada",align:"right",sortable:!1,value:"tarifa",classData:"text-xs-right",component:{component:{template:"<span className=\"white--text caption\">{{'$'}}{{value.tarifa | numberFormat(2)}}</span>",props:["value"]}}},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"text-xs-center"}]}}},watch:{"item.otro.id":function(t){t&&(this.item.rs_otrosentidad_id=this.item.otro.id,this.item.tarifa_entidad=this.item.otro.tarifa,this.item.tarifa=JSON.parse(r()(this.item.otro.tarifa)))},showFormRegistro:function(t){!t&&this.refreshOtro()}},computed:{estadosBasicos:function(){return i.b.state.estadosBasicos}},created:function(){this.refreshOtro()},mounted:function(){this.dataTable.route="contratos/"+this.value.id+"/otros"},methods:{otroEditar:function(t){var e=this;this.refreshOtro();var a=JSON.parse(r()(t));console.log("el temporal",a),this.item={otro:{id:a.rs_otrosentidad_id,tarifa:a.tarifa,codigo:a.codigo,nombre:a.descripcion},id:a.id,rs_contratos_id:a.rs_contratos_id,rs_otrosentidad_id:a.rs_otrosentidad_id,tarifa:a.tarifa,tarifa_entidad:a.tarifa_entidad},this.$vuetify.goTo("#flag-top",{selector:"#flag-top",duration:300,offset:-120,easing:"easeInOutQuad"}),this.showFormRegistro=!0,setTimeout(function(){e.$refs.postuladorOtro.assign(e.item.otro),e.$refs.postuladorOtro.focus()},300)},otroBorrar:function(t){var e=this;this.loading=!0,this.showFormRegistro=!1,this.axios.post("contratos/remove-otro/"+t.id).then(function(t){e.$refs.tablaOtros.reloadPage(),e.loading=!1}).catch(function(t){e.loading=!1,e.$store.commit(n.a,{expression:" al borrar el medicamento. ",error:t})})},resetOptions:function(t){return t.options=[],this.value.actaInicio||t.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),this.value.actaInicio||t.options.push({event:"borrar",icon:"delete",tooltip:"Borrar"}),t},submitOtro:function(){var t=this;this.$validator.validateAll().then(function(e){e&&(t.loading=!0,t.item.rs_contratos_id=t.value.id,t.axios.post("contratos/"+t.value.id+"/add-otro",t.item).then(function(e){t.$refs.tablaOtros.reloadPage(),t.refreshOtro(),t.$refs.postuladorOtro.focus(),t.loading=!1}).catch(function(e){t.loading=!1,t.$store.commit(n.a,{expression:" al registrar el item. ",error:e})}))})},refreshOtro:function(){this.item=JSON.parse(r()(this.makeItem)),this.$validator.reset()}}}},2254:function(t,e,a){"use strict";function o(t){a(2255)}Object.defineProperty(e,"__esModule",{value:!0});var r=a(1234),i=a(2257),s=a(1),n=o,l=s(r.a,i.a,!1,n,"data-v-14fab6cc",null);e.default=l.exports},2255:function(t,e,a){var o=a(2256);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);a(456)("f922f6ae",o,!0,{})},2256:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroOtros.vue",sourceRoot:""}])},2257:function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-layout",{attrs:{row:"",wrap:"",id:"flag-top"}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-card",[a("v-toolbar",{staticClass:"elevation-0",attrs:{dense:""}},[a("v-toolbar-title",{domProps:{innerHTML:t._s("Otros bienes y servicios")}}),t._v(" "),a("small",{staticClass:"mt-2 ml-1"},[t._v(" Registro y gestión")]),t._v(" "),a("v-spacer"),t._v(" "),t.showFormRegistro||t.value.actaInicio?t._e():a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",right:"",small:"",color:"accent"},on:{click:function(e){t.showFormRegistro=!0}},slot:"activator"},[a("v-icon",[t._v("add")])],1),t._v(" "),a("span",[t._v("Crear item")])],1)],1),t._v(" "),a("v-scroll-y-transition",[t.showFormRegistro?a("div",[a("v-card-text",[a("v-form",[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{md10:"",sm8:"",xs12:""}},[a("postulador-v2",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],ref:"postuladorOtro",attrs:{"no-data":"Busqueda por código o nombre.","item-text":"nombre",filter:"codigo,nombre",label:"Bienes y servicios",entidad:"entidades/"+t.value.entidad.id+"/otros","no-btn-create":"","no-btn-edit":"",name:"Otro",rules:"required","error-messages":t.errors.collect("Otro"),"min-characters-search":4,"slot-data":{template:"\n                    <v-list-tile>\n                      <v-list-tile-content>\n                        <v-list-tile-title>{{value.nombre}}</v-list-tile-title>\n                        <v-list-tile-sub-title class=caption> Código: {{ value.codigo }}</v-list-tile-sub-title>\n                      </v-list-tile-content>\n                    </v-list-tile>\n                    ",props:["value"]}},model:{value:t.item.otro,callback:function(e){t.$set(t.item,"otro",e)},expression:"item.otro"}})],1),t._v(" "),a("v-flex",{attrs:{md2:"",sm4:"",xs12:""}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|min_value:0",expression:"'required|min_value:0'"}],attrs:{label:"Tarifa",name:"Tarifa",min:"0",type:"number","error-messages":t.errors.collect("Tarifa")},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.submitMedicamento(e)}},model:{value:t.item.tarifa,callback:function(e){t.$set(t.item,"tarifa",t._n(e))},expression:"item.tarifa"}})],1)],1)],1)],1),t._v(" "),a("v-divider"),t._v(" "),a("v-card-actions",[a("v-spacer"),t._v(" "),a("v-btn",{attrs:{flat:""},on:{click:function(e){e.stopPropagation(),t.showFormRegistro=!1}}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{color:"primary"},on:{click:function(e){return e.stopPropagation(),t.submitOtro(e)}}},[t._v("Registrar")])],1)],1):t._e()])],1)],1),t._v(" "),a("v-container",{staticClass:"ma-0 pa-0",attrs:{fluid:""}},[t.dataTable.route?a("data-table",{ref:"tablaOtros",on:{resetOption:function(e){return t.resetOptions(e)},editar:function(e){return t.otroEditar(e)},borrar:function(e){return t.otroBorrar(e)}},model:{value:t.dataTable,callback:function(e){t.dataTable=e},expression:"dataTable"}}):t._e()],1)],1)},r=[],i={render:o,staticRenderFns:r};e.a=i}});
//# sourceMappingURL=320.2b4540499d9f60ddcf81.js.map