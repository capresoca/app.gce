webpackJsonp([64],{1045:function(e,t,o){"use strict";var i=o(840);t.a={name:"Vendedores",components:{RegistroVendedores:i.a},data:function(){return{search:"",buscandoCodigo:!1,exist:!1,ordenEdit:!1,dialogSave:!1,dialogCodigo:!1,tableLoading:!1,pagination:{},param:{},listaVendedores:[],vendedor:{},headers:[{text:"Codigo",align:"left",sortable:!1,value:"codigo"},{text:"Tipo identificacion",align:"left",sortable:!1,value:"tercero.tipo_id.abreviatura"},{text:"Identificacion",align:"left",sortable:!1,value:"tercero.identificacion"},{text:"Nombre",align:"left",sortable:!1,value:"nombre"},{text:"Opciones",align:"center",sortable:!1,value:"id"}],desserts:[{value:!1,name:"Frozen Yogurt",calories:159,fat:6,carbs:24,protein:4,iron:"1%"}]}},computed:{pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)}},beforeCreate:function(){var e=this;this.tableLoading=!0,this.axios.get("vendedor/").then(function(t){e.listaVendedores=t.data.data,e.pagination.totalItems=e.listaVendedores.length,e.tableLoading=!1}).catch(function(e){return!1})},methods:{returnDialogo:function(e){console.log(e),"new"===e.tipo?this.listaVendedores.splice(0,0,e.data):this.listaVendedores.splice(e.indice,1,e.data)},edit:function(e){console.log(e.item),this.param={vendedor:e.item,indice:e.index}}},mounted:function(){}}},1734:function(e,t,o){var i=o(1735);"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);o(456)("7415ef44",i,!0,{})},1735:function(e,t,o){t=e.exports=o(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Vendedores.vue",sourceRoot:""}])},1736:function(e,t,o){"use strict";var i=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("v-card",[o("v-toolbar",{staticClass:"elevation-0",attrs:{dense:""}},[o("v-icon",[e._v("face")]),e._v(" "),o("v-toolbar-title",[e._v("Vendedores")]),e._v(" "),o("small",{staticClass:"mt-2 ml-1"},[e._v("Registro y gestion de vendedores")]),e._v(" "),o("v-spacer"),e._v(" "),o("v-tooltip",{attrs:{top:""}},[o("v-btn",{attrs:{slot:"activator",fab:"",right:"",small:"",color:"accent"},on:{click:function(t){e.dialogSave=!0}},slot:"activator"},[o("v-icon",[e._v("add")])],1),e._v(" "),o("span",[e._v("Registrar vendedores")])],1)],1),e._v(" "),o("v-card-title",[o("v-text-field",{attrs:{"append-icon":"search",label:"Buscar","single-line":"","hide-details":"",flat:""},model:{value:e.search,callback:function(t){e.search=t},expression:"search"}})],1),e._v(" "),o("v-data-table",{staticClass:"elevation-1",attrs:{headers:e.headers,items:e.listaVendedores,loading:e.tableLoading,pagination:e.pagination,"hide-actions":"",search:e.search,"rows-per-page-text":"Registros por página","rows-per-page-items":[10,25,50,{text:"Todos",value:-1}]},on:{"update:pagination":function(t){e.pagination=t}},scopedSlots:e._u([{key:"items",fn:function(t){return[o("td",[e._v(e._s(t.item.codigo))]),e._v(" "),o("td",[e._v(e._s(t.item.tercero.tipo_id.abreviatura))]),e._v(" "),o("td",[e._v(e._s(t.item.tercero.identificacion))]),e._v(" "),o("td",[e._v(e._s(t.item.nombre))]),e._v(" "),o("td",{staticClass:"text-xs-center"},[o("v-tooltip",{attrs:{top:""}},[o("v-btn",{staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(o){return e.edit(t)}},slot:"activator"},[o("v-icon",{attrs:{color:"accent"}},[e._v("edit")])],1),e._v(" "),o("span",[e._v("Editar")])],1)],1)]}},{key:"pageText",fn:function(t){return[e._v("\n      "+e._s(t.pageStart)+" - "+e._s(t.pageStop)+" de "+e._s(t.itemsLength)+"\n    ")]}}])},[e._v(" "),o("template",{slot:"no-data"},[e.tableLoading?o("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[e._v("\n        Lo sentimos, no tenemos registros para mostrar. "),o("v-icon",[e._v("sentiment_very_dissatisfied")])],1):o("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[e._v("\n        Estamos cargando los registros. "),o("v-icon",[e._v("sentiment_satisfied_alt")])],1)],1),e._v(" "),e._v(" "),o("v-alert",{attrs:{slot:"no-results",value:!0,color:"error",icon:"warning"},slot:"no-results"},[e._v('\n      No se encontraron resultados de "'+e._s(e.search)+'"\n    ')])],2),e._v(" "),o("v-card-actions",{staticClass:"text-xs-center pt-2 d-block"},[o("v-pagination",{attrs:{length:e.pages},model:{value:e.pagination.page,callback:function(t){e.$set(e.pagination,"page",t)},expression:"pagination.page"}})],1),e._v(" "),o("v-container",[o("div",{staticClass:"text-xs-center"},[o("registro-vendedores",{attrs:{open:e.dialogSave,parametros:e.param},on:{dialog:function(t){return e.dialogSave=t},objectReturn:function(t){return e.returnDialogo(t)}}})],1)])],1)},a=[],r={render:i,staticRenderFns:a};t.a=r},540:function(e,t,o){"use strict";function i(e){o(1734)}Object.defineProperty(t,"__esModule",{value:!0});var a=o(1045),r=o(1736),n=o(1),s=i,d=n(a.a,r.a,!1,s,"data-v-baeea6c6",null);t.default=d.exports},777:function(e,t,o){"use strict";var i=o(13),a=o.n(i),r=o(14),n=o.n(r),s=o(12);t.a={name:"RegistroVendedores",components:{Postulador:function(){return o.e(287).then(o.bind(null,747))}},props:["open","parametros"],data:function(){return{search:"",buscandoCodigo:!1,exist:!1,dialogSave:!1,ordenEdit:!1,dialogCodigo:!1,indice:0,vendedor:{tercero:{identificacion_completa:"",identificacion:"",nombre_completo:"",tipo_id:{}}}}},watch:{"vendedor.tercero":function(e){e&&(this.vendedor.nombre=e.nombre_completo)},"$store.state.nav.currentItem":function(e){e&&(this.dialogSave=!1)},open:function(e){this.dialogSave=e},parametros:function(e){e.vendedor&&(this.edit(e.vendedor),console.log(e.indice),e.indice&&(this.indice=e.indice))}},methods:{terceroSeleccionado:function(e){var t=this;null!==e&&!1===this.dialogSave?null!==e.id&&""!==e.id&&void 0!==e.id&&(this.dialogSave=!0,this.axios.get("clientes/tercero/"+e.id).then(function(e){e.data.exists?t.vendedor.tercero=e.data.tercero:t.$store.commit(s.b,{msg:"No podemos recuperar los datos del tercero. Intente de nuevo",color:"danger"})}).catch(function(e){t.$store.commit(s.a,{expression:" Al intentar consultar codigo. ",error:e})})):null!==e&&(this.vendedor.tercero=e)},edit:function(e){this.dialogSave=!0,this.ordenEdit=!0,this.exist=!0,this.vendedor=e},vendedorByCod:function(){var e=this;return n()(a.a.mark(function t(){return a.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:e.vendedor.codigo&&(e.buscandoCodigo=!0,e.axios.get("vendedor/codigo/"+e.vendedor.codigo).then(function(t){if(t.data.exists)e.vendedor=t.data.vendedor,e.dialogCodigo=!0,e.buscandoCodigo=!1;else{e.buscandoCodigo=!1;var o=e.vendedor.codigo;e.vendedor={id:"",codigo:o,tercero:{identificacion:"",identificacion_completa:"",nombre_completo:"",tipo_id:{}}},e.dialogCodigo=!1,e.exist=!0}}).catch(function(t){e.$store.commit(s.a,{expression:" Al intentar consultar codigo. ",error:t})}));case 1:case"end":return t.stop()}},t,e)}))()},editarByCodigo:function(){this.dialogCodigo=!1,this.dialogSave=!0,this.ordenEdit=!0,this.exist=!0},cerrarByCodigo:function(){this.formReset(),this.dialogCodigo=!1,this.ordenEdit=!1,this.$emit("dialog",!1)},cerrarModal:function(){this.formReset(),this.dialogSave=!1,this.$emit("dialog",!1)},validador:function(e){return this.$validator.validateAll(e).then(function(e){return e})},submit:function(){var e=this;return n()(a.a.mark(function t(){return a.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.validador("formVendedor");case 2:if(!t.sent){t.next=6;break}e.vendedor.id?e.axios.put("vendedor/"+e.vendedor.id,e.vendedor).then(function(t){e.$store.commit(s.b,{msg:"El vendedor se actualizo correctamente",color:"success"}),e.dialogSave=!1,e.$emit("objectReturn",{data:t.data.data,indice:e.indice,tipo:"updated"}),e.formReset()}).catch(function(t){e.$store.commit(s.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}):e.axios.post("vendedor",e.vendedor).then(function(t){e.$store.commit(s.b,{msg:"El vendedor se creó correctamente",color:"success"}),e.dialogSave=!1,e.$emit("objectReturn",{data:t.data.data,indice:e.indice,tipo:"new"}),e.formReset()}).catch(function(t){e.$store.commit(s.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}),t.next=7;break;case 6:e.$store.commit(s.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 7:case"end":return t.stop()}},t,e)}))()},formReset:function(){this.vendedor={tercero:{identificacion:"",identificacion_completa:"",nombre_completo:"",tipo_id:{}}},this.indice=0,this.dialogCodigo=!1,this.ordenEdit=!1,this.exist=!1}},mounted:function(){this.$validator.localize("es")}}},840:function(e,t,o){"use strict";function i(e){o(841)}var a=o(777),r=o(843),n=o(1),s=i,d=n(a.a,r.a,!1,s,"data-v-288a403a",null);t.a=d.exports},841:function(e,t,o){var i=o(842);"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);o(456)("658466f8",i,!0,{})},842:function(e,t,o){t=e.exports=o(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RegistroVendedores.vue",sourceRoot:""}])},843:function(e,t,o){"use strict";var i=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("v-dialog",{attrs:{width:"500",persistent:""},model:{value:e.dialogSave,callback:function(t){e.dialogSave=t},expression:"dialogSave"}},[o("v-form",{attrs:{"data-vv-scope":"formVendedor"}},[o("v-card",[e.ordenEdit?o("v-card-title",{staticClass:"headline grey lighten-2",attrs:{"primary-title":""}},[e._v("\n          Editar vendedor\n        ")]):o("v-card-title",{staticClass:"headline grey lighten-2",attrs:{"primary-title":""}},[e._v("\n          Registrar vendedor\n        ")]),e._v(" "),o("v-card-text",[o("v-container",{attrs:{fluid:"","grid-list-xl":""}},[o("v-layout",{attrs:{row:"",wrap:""}},[o("v-flex",{attrs:{xs12:"",sm4:"",md4:"",lg4:""}},[o("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:10",expression:"'required|max:10'"}],key:"codigo",attrs:{label:"Codigo",disabled:e.ordenEdit,name:"codigo","error-messages":e.errors.collect("codigo"),hint:e.buscandoCodigo?"Estamos buscando el codigo":"","persistent-hint":"",required:""},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.vendedorByCod(t)},blur:e.vendedorByCod},model:{value:e.vendedor.codigo,callback:function(t){e.$set(e.vendedor,"codigo",t)},expression:"vendedor.codigo"}})],1),e._v(" "),o("v-flex",{attrs:{xs12:"",sm8:"",md8:"",lg8:""}},[o("postulador",{ref:"postulaTercero",attrs:{nodata:"Busqueda por nombre o número de documento.",required:"",hint:"nombre_completo",itemtext:"identificacion",datatitle:"identificacion_completa",datasubtitle:"nombre_completo",filter:"identificacion_completa,nombre_completo",label:"Tercero",scope:"formPrincipal",entidad:"terceros",preicon:"person",value:e.vendedor.tercero,clearable:""},on:{change:function(t){return e.vendedor.gn_tercero_id=t},objectReturn:function(t){return e.terceroSeleccionado(t)}}})],1),e._v(" "),o("v-flex",{attrs:{xs12:"",sm12:"",md12:"",lg12:""}},[o("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:255",expression:"'required|max:255'"}],key:"nombre",attrs:{label:"Nombre",name:"nombre","error-messages":e.errors.collect("nombre"),required:""},model:{value:e.vendedor.nombre,callback:function(t){e.$set(e.vendedor,"nombre",t)},expression:"vendedor.nombre"}})],1)],1)],1)],1),e._v(" "),o("v-divider"),e._v(" "),o("v-card-actions",[o("v-spacer"),e._v(" "),o("v-btn",{attrs:{color:"primary",flat:"",disabled:e.errors.any()},on:{click:e.submit}},[e._v("\n          Guardar\n          ")]),e._v(" "),o("v-btn",{attrs:{color:"primary",flat:""},on:{click:e.cerrarModal}},[e._v("\n          Cerrar\n          ")])],1)],1)],1)],1),e._v(" "),o("v-dialog",{attrs:{persistent:"","max-width":"290"},model:{value:e.dialogCodigo,callback:function(t){e.dialogCodigo=t},expression:"dialogCodigo"}},[o("v-card",[o("v-card-title",{staticClass:"headline"},[e._v("Codigo del vendedor")]),e._v(" "),o("v-card-text",[e._v("El codigo del vendedor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo")]),e._v(" "),o("v-card-actions",[o("v-spacer"),e._v(" "),o("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.editarByCodigo}},[e._v("Cargar datos")]),e._v(" "),o("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.cerrarByCodigo}},[e._v("Cerrar")])],1)],1)],1)],1)},a=[],r={render:i,staticRenderFns:a};t.a=r}});
//# sourceMappingURL=64.f72777f95786200da36c.js.map