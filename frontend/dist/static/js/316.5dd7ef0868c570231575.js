webpackJsonp([316],{1331:function(a,e,t){"use strict";var i=t(249),o=t.n(i),r=t(252),n=t.n(r),l=t(13),s=t.n(l),c=t(14),m=t.n(c),d=t(12),v=t(743);e.a={name:"estudio",props:["empleado"],components:{Confirmar:v.a},data:function(){return{dialogA:{visible:!1,content:null,registroID:null},dialog:!1,tableLoading:!1,headers:[{text:"Parentesco",align:"left",sortable:!1,value:"parentesco"},{text:"Grado",align:"left",sortable:!1,value:"grado"},{text:"Nombres y apellidos",align:"left",sortable:!1,value:"nombres"},{text:"Dirección",align:"left",sortable:!1,value:"direccion"},{text:"Teléfono",align:"left",sortable:!1,value:"telefono"},{text:"F.Nacimiento",align:"left",sortable:!1,value:"fecha_nacimiento"},{text:"Opciones",align:"left",sortable:!1,value:"id"}],tabs:null,menuDateFechaFamiliar:!1,dataFamilia:{familia:[]},mostrar:!0,familia:{},parentescos:[{value:"1",nombre:"Hijo"},{value:"2",nombre:"Padre"},{value:"3",nombre:"Hermano"},{value:"4",nombre:"Abuelo"},{value:"5",nombre:"Primo"},{value:"6",nombre:"Otros"}]}},mounted:function(){},created:function(){this.reloadPage()},watch:{"familia.parentesco":function(){"Padre"===this.familia.parentesco||"Hijo"===this.familia.parentesco?this.familia.grado="Primero":"Hermano"===this.familia.parentesco||"Abuelo"===this.familia.parentesco?this.familia.grado="Segundo":"Primo"===this.familia.parentesco?this.familia.grado="Cuarto":"Otros"===this.familia.parentesco&&(this.familia.grado="Tercero")}},computed:{computedDateFormattedFechaFamiliar:function(){return this.formDate(this.familia.fecha_nacimiento)}},methods:{reloadPage:function(){var a=this;this.tableLoading=!0,this.localLoading=!0,this.axios.get("empleadosfamilia?id="+this.empleado).then(function(e){a.dataFamilia.familia=e.data,a.tableLoading=!1,a.localLoading=!1;for(var t=0;t<a.dataFamilia.familia.length;t++)for(var i=0;i<a.parentescos.length;i++){var o=a.parentescos[i];a.dataFamilia.familia[t].parentesco.toString()===o.value&&(a.dataFamilia.familia[t].parentesco=o.nombre)}}).catch(function(e){return a.tableLoading=!1,a.localLoading=!1,!1})},mostrarCardEd:function(a,e){1===a&&"familia"===e?this.mostrar=!0:2===a&&"familia"===e&&(this.mostrar=!1)},validador:function(a){return this.$validator.validateAll(a).then(function(a){return a})},saveFamiliaCH:function(){var a=this;return m()(s.a.mark(function e(){var t;return s.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.validador("formPago");case 2:if(!e.sent){e.next=10;break}a.familia.empleado=a.empleado,console.log(a.familia),a.localLoading=!0,t=a.familia.empleado_familia?a.axios.put("empleadosfamilia/actualizar/"+a.familia.empleado_familia,a.familia):a.axios.post("empleadosfamilia/crear",a.familia),t.then(function(e){a.localLoading=!1,a.familia.empleado_familia?a.$store.commit(d.b,{msg:"El familiar se actualizó correctamente",color:"success"}):(a.$store.commit(d.b,{msg:"Familiar agregado exitosamente",color:"success"}),a.reloadPage()),a.dialog=!1}).catch(function(e){a.localLoading=!1,a.$store.commit(d.b,{msg:"Hay un error al guardar el registro. "+e.response,color:"danger"})}),e.next=11;break;case 10:a.$store.commit(d.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 11:a.formTableReset();case 12:case"end":return e.stop()}},e,a)}))()},eliminarFamilia:function(a){this.dialogA.content="¿Está seguro de eliminar?",this.dialogA.registroID=a.empleado_familia,this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.content=null,this.dialogA.registroID=null},confirmaAnulacion:function(a){var e=this;this.localLoading=!0,this.axios.delete("empleadosfamilia/eliminar/"+this.dialogA.registroID).then(function(a){e.$store.commit(d.b,{msg:"El familiar se eliminó correctamente",color:"success"}),e.reloadPage(),e.cancelaAnulacion()}).catch(function(a){e.localLoading=!1,e.$store.commit(d.b,{msg:"Hay un error al eliminar el registro. "+a.response,color:"danger"})})},editarFamilia:function(a,e){this.dialog=!0,this.familia=n()({},a),this.i=e},closeTable:function(){this.formTableReset()},formTableReset:function(){this.i=null,this.familia={parentesco:null,grado:null,nombre:null,direccion:null,telefono:null,fecha_nacimiento:null},this.dialog=!1},formDate:function(a){if(!a)return null;var e=a.split("-"),t=o()(e,3);return t[0]+"/"+t[1]+"/"+t[2]}}}},2573:function(a,e,t){"use strict";function i(a){t(2574)}Object.defineProperty(e,"__esModule",{value:!0});var o=t(1331),r=t(2576),n=t(1),l=i,s=n(o.a,r.a,!1,l,"data-v-15309958",null);e.default=s.exports},2574:function(a,e,t){var i=t(2575);"string"==typeof i&&(i=[[a.i,i,""]]),i.locals&&(a.exports=i.locals);t(456)("70faafac",i,!0,{})},2575:function(a,e,t){e=a.exports=t(455)(!0),e.push([a.i,".tipo[data-v-15309958]{height:10px;padding:0}.no-padding[data-v-15309958]{padding:0}","",{version:3,sources:["/home/capresoca/projects/Capresoca-Frontend/src/components/administrativo/TalentoHumano/Empleado/EmpleadoFamilia.vue"],names:[],mappings:"AACA,uBACE,YAAa,AACb,SAAW,CACZ,AACD,6BACE,SAAW,CACZ",file:"EmpleadoFamilia.vue",sourcesContent:["\n.tipo[data-v-15309958] {\n  height: 10px;\n  padding: 0;\n}\n.no-padding[data-v-15309958] {\n  padding: 0;\n}\n"],sourceRoot:""}])},2576:function(a,e,t){"use strict";var i=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",[t("v-dialog",{attrs:{width:"1000px"},model:{value:a.dialog,callback:function(e){a.dialog=e},expression:"dialog"}},[t("v-form",[t("v-card",[t("v-card-title",{staticClass:"grey lighten-3 elevation-0"},[t("span",{staticClass:"title"},[a._v("Agregar familiar")])]),a._v(" "),t("v-container",{attrs:{fluid:"","grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{"item-text":"nombre","item-value":"nombre",label:"Parentesco",items:a.parentescos,name:"parentesco","error-messages":a.errors.collect("parentesco")},model:{value:a.familia.parentesco,callback:function(e){a.$set(a.familia,"parentesco",e)},expression:"familia.parentesco"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:10|",expression:"'required|max:10|'"}],key:"grado",attrs:{label:"Grado",name:"grado",required:"",readonly:"","error-messages":a.errors.collect("descripcion")},model:{value:a.familia.grado,callback:function(e){a.$set(a.familia,"grado",e)},expression:"familia.grado"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:200|",expression:"'required|max:200|'"}],key:"nombres y apellidos",attrs:{label:"Nombres y apellidos",name:"nombres y apellidos",required:"","error-messages":a.errors.collect("nombres y apellidos")},model:{value:a.familia.nombre,callback:function(e){a.$set(a.familia,"nombre",e)},expression:"familia.nombre"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:200|",expression:"'required|max:200|'"}],key:"dirección de familiar",attrs:{label:"Dirección",name:"dirección de familiar",required:"","error-messages":a.errors.collect("dirección de familiar")},model:{value:a.familia.direccion,callback:function(e){a.$set(a.familia,"direccion",e)},expression:"familia.direccion"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"max:50",expression:"'max:50'"}],key:"teléfono de familiar",attrs:{label:"Teléfono",name:"teléfono de familiar","error-messages":a.errors.collect("teléfono de familiar")},model:{value:a.familia.telefono,callback:function(e){a.$set(a.familia,"telefono",e)},expression:"familia.telefono"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-menu",{ref:"menuDateFechaFamiliar",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:a.menuDateFechaFamiliar,callback:function(e){a.menuDateFechaFamiliar=e},expression:"menuDateFechaFamiliar"}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha nacimiento","prepend-icon":"event",readonly:"",name:"fecha nacimiento de familiar","error-messages":a.errors.collect("fecha nacimiento de familiar")},slot:"activator",model:{value:a.computedDateFormattedFechaFamiliar,callback:function(e){a.computedDateFormattedFechaFamiliar=e},expression:"computedDateFormattedFechaFamiliar"}}),a._v(" "),t("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){a.menuDateFechaFamiliar=!1},change:function(){var e=a.$validator.errors.items.findIndex(function(a){return"fecha nacimiento"===a.field});a.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:a.familia.fecha_nacimiento,callback:function(e){a.$set(a.familia,"fecha_nacimiento",e)},expression:"familia.fecha_nacimiento"}})],1)],1)],1)],1),a._v(" "),t("v-card-actions",{staticClass:"grey lighten-4"},[t("v-spacer"),a._v(" "),t("v-btn",{on:{click:a.closeTable}},[a._v("Cancelar")]),a._v(" "),t("v-btn",{attrs:{color:"primary"},on:{click:a.saveFamiliaCH}},[a._v("Guardar")])],1)],1)],1)],1),a._v(" "),t("v-toolbar",{directives:[{name:"show",rawName:"v-show",value:!a.mostrar,expression:"!mostrar"}],attrs:{dense:"",short:"",flat:""}},[t("v-app-bar-nav-icon"),a._v(" "),t("v-title",[a._v("Grupo familiar")]),a._v(" "),t("v-spacer"),a._v(" "),t("v-btn",{attrs:{icon:""},on:{click:function(e){return a.mostrarCardEd(1,"familia")}}},[t("v-icon",{attrs:{title:"mostrar"}},[a._v("remove_red_eye")])],1)],1),a._v(" "),t("v-card",{directives:[{name:"show",rawName:"v-show",value:a.mostrar,expression:"mostrar"}],attrs:{flat:""}},[t("v-card-text",[t("v-toolbar",{attrs:{dense:"",short:"",flat:""}},[t("v-app-bar-nav-icon"),a._v(" "),t("v-title",[a._v("Grupo familiar")]),a._v(" "),t("v-spacer"),a._v(" "),t("v-btn",{attrs:{icon:""}},[t("v-icon",{on:{click:function(e){a.dialog=!0}}},[a._v("add")])],1),a._v(" "),t("v-btn",{attrs:{icon:""},on:{click:function(e){return a.mostrarCardEd(2,"familia")}}},[t("v-icon",{attrs:{color:"green",title:"ocultar"}},[a._v("remove_red_eye")])],1)],1),a._v(" "),t("v-data-table",{staticClass:"elevation-1",attrs:{headers:a.headers,items:a.dataFamilia.familia,loading:a.tableLoading,"total-items":a.dataFamilia.length,"hide-actions":""},scopedSlots:a._u([{key:"items",fn:function(e){return[t("td",[a._v(a._s(e.item.parentesco))]),a._v(" "),t("td",[a._v(a._s(e.item.grado))]),a._v(" "),t("td",[a._v(a._s(e.item.nombre))]),a._v(" "),t("td",[a._v(a._s(e.item.direccion))]),a._v(" "),t("td",[a._v(a._s(e.item.telefono))]),a._v(" "),t("td",[a._v(a._s(e.item.fecha_nacimiento))]),a._v(" "),t("td",[t("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:e.item.show,callback:function(t){a.$set(e.item,"show",t)},expression:"props.item.show"}},[t("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:e.item.show,callback:function(t){a.$set(e.item,"show",t)},expression:"props.item.show"}},[t("v-icon",[a._v("chevron_left")]),a._v(" "),t("v-icon",[a._v("close")])],1),a._v(" "),t("v-tooltip",{attrs:{top:""}},[t("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(t){return a.eliminarFamilia(e.item)}},slot:"activator"},[t("v-icon",{attrs:{color:"accent"}},[a._v("delete")])],1),a._v(" "),t("span",[a._v("Eliminar")])],1),a._v(" "),t("v-tooltip",{attrs:{top:""}},[t("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(t){return a.editarFamilia(e.item)}},slot:"activator"},[t("v-icon",{attrs:{color:"accent"}},[a._v("mode_edit")])],1),a._v(" "),t("span",[a._v("Editar")])],1)],1)],1)]}}])},[a._v(" "),t("template",{slot:"no-data"},[a.tableLoading?t("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[a._v("\n            Estamos cargando los registros. "),t("v-icon",[a._v("sentiment_satisfied_alt")])],1):t("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[a._v("\n            No tenemos registros para mostrar. "),t("v-icon",[a._v("sentiment_very_dissatisfied")])],1)],1)],2)],1)],1),a._v(" "),t("confirmar",{attrs:{value:a.dialogA.visible,content:a.dialogA.content},on:{cancelar:a.cancelaAnulacion,aceptar:function(e){return a.confirmaAnulacion(e)}}})],1)},o=[],r={render:i,staticRenderFns:o};e.a=r},742:function(a,e,t){"use strict";var i=t(13),o=t.n(i),r=t(14),n=t.n(r),l=t(58);e.a={name:"Confirmar",props:{value:{type:Boolean,default:!1},content:{type:String},requiereMotivo:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}},components:{Loading:l.default},data:function(){return{loadingSubmit:!1,motivo:null}},watch:{value:function(a){a||(this.motivo=null,this.$validator.reset())}},methods:{submit:function(){var a=this;return n()(o.a.mark(function e(){return o.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.$validator.validateAll();case 2:if(!e.sent){e.next=5;break}a.loadingSubmit=!0,a.$emit("aceptar",a.motivo);case 5:case"end":return e.stop()}},e,a)}))()},pararCarga:function(){this.loadingSubmit=!1}}}},743:function(a,e,t){"use strict";function i(a){t(744)}var o=t(742),r=t(746),n=t(1),l=i,s=n(o.a,r.a,!1,l,"data-v-3773d9ac",null);e.a=s.exports},744:function(a,e,t){var i=t(745);"string"==typeof i&&(i=[[a.i,i,""]]),i.locals&&(a.exports=i.locals);t(456)("0e7a280b",i,!0,{})},745:function(a,e,t){e=a.exports=t(455)(!0),e.push([a.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Confirmar2.vue",sourceRoot:""}])},746:function(a,e,t){"use strict";var i=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("v-layout",{attrs:{row:"","justify-center":""}},[t("v-dialog",{key:"dialogConfirm",attrs:{persistent:"","max-width":"400"},model:{value:a.value,callback:function(e){a.value=e},expression:"value"}},[t("loading",{model:{value:a.loading,callback:function(e){a.loading=e},expression:"loading"}}),a._v(" "),t("v-card",[t("v-card-text",{staticClass:"title font-weight-light text-xs-center",domProps:{innerHTML:a._s(a.content)}}),a._v(" "),t("v-divider"),a._v(" "),t("v-card-actions",[t("v-btn",{attrs:{flat:""},on:{click:function(e){return e.stopPropagation(),a.$emit("cancelar")}}},[a._v("Cancelar")]),a._v(" "),t("v-spacer"),a._v(" "),t("v-btn",{attrs:{color:"primary",loading:a.loadingSubmit},on:{click:function(e){return e.stopPropagation(),a.submit(e)}}},[a._v("Aceptar")])],1)],1)],1)],1)},o=[],r={render:i,staticRenderFns:o};e.a=r}});
//# sourceMappingURL=316.5dd7ef0868c570231575.js.map