webpackJsonp([228],{1165:function(o,t,e){"use strict";var r=e(13),a=e.n(r),i=e(14),n=e.n(i),s=e(40),d=e(248),c=e(12);t.a={name:"RegistroFondosTalentoHumano",props:["parametros"],components:{Postulador:function(){return e.e(287).then(e.bind(null,747))}},data:function(){return{buscandoCodigo:!1,dialogCodigo:!1,ordenEdit:!1,loadingSubmit:!1,tiposFondo:["Cesantias","Salud","Pensión","Riesgos","I.S.S","Privado","Público"],fondo:{tipo_fondo:[]}}},methods:{findByCodigo:function(){var o=this;this.fondo.codigo&&(this.buscandoCodigo=!0,this.axios.get("th_fondos/codigo/"+this.fondo.codigo).then(function(t){t.data.exists&&(o.$store.commit(c.b,{msg:"El codigo del fondo existe",color:"success"}),o.formReset(),o.fondo=t.data.data,o.pasarArrya(t.data.data.tipo_fondo),o.dialogCodigo=!0),o.buscandoCodigo=!1}).catch(function(t){console.log(t),o.buscandoCodigo=!1,o.$store.commit(c.b,{msg:"Hay un error al consultar el codigo. "+t.response,color:"danger"})}))},validador:function(o){return this.$validator.validateAll(o).then(function(o){return o})},submit:function(){var o=this;return n()(a.a.mark(function t(){var e,r;return a.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.validador("formRegistroFondos");case 2:if(!t.sent){t.next=10;break}for(o.loadingSubmit=!0,e="",r=0;r<o.fondo.tipo_fondo.length;r++)r===o.fondo.tipo_fondo.length-1?e+=o.fondo.tipo_fondo[r]:e=e+o.fondo.tipo_fondo[r]+",";o.fondo.tipo_fondo=e,o.fondo.id?o.axios.put("th_fondos/"+o.fondo.id,o.fondo).then(function(t){o.$store.commit(c.b,{msg:"El fondo se actualizo correctamente",color:"success"}),o.formReset(),o.$store.dispatch(s.c,{contexto:o,itemId:o.parametros.tabOrigin}),o.$store.commit(d.L,{item:t.data.data,estado:"editar",key:o.parametros.key})}).catch(function(t){console.log(t),o.$store.commit(c.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}):o.axios.post("th_fondos",o.fondo).then(function(t){o.$store.commit(c.b,{msg:"El fondo se creó correctamente",color:"success"}),o.formReset(),o.$store.dispatch(s.c,{contexto:o,itemId:o.parametros.tabOrigin}),o.$store.commit(d.L,{item:t.data.data,estado:"crear",key:o.parametros.key})}).catch(function(t){console.log(t),o.$store.commit(c.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}),t.next=11;break;case 10:o.$store.commit(c.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 11:case"end":return t.stop()}},t,o)}))()},seleccionTercero:function(o){null!==o&&(this.fondo.tercero=o,this.fondo.nombre=o.nombre_completo,this.fondo.gn_tercero_id=o.id)},editarByCodigo:function(){this.dialogCodigo=!1,this.ordenEdit=!0},cerrarByCodigo:function(){this.formReset(),this.ordenEdit=!1,this.dialogCodigo=!1},pasarArrya:function(o){this.fondo.tipo_fondo=o.split(",")},formReset:function(){this.fondo={tipo_fondo:[]},this.buscandoCodigo=!1,this.dialogCodigo=!1,this.ordenEdit=!1},close:function(){this.formReset(),this.$validator.reset(),this.$store.dispatch(s.c,{contexto:this,itemId:this.parametros.tabOrigin})},getRegistro:function(o){var t=this;this.axios.get("th_fondos/"+o).then(function(o){o.data.exists&&t.edit(o.data.data)}).catch(function(o){t.$store.commit(c.a,{expression:" al traer el subgrupo. ",error:o})})},edit:function(o){this.ordenEdit=!0,this.fondo=o,this.pasarArrya(o.tipo_fondo)}},mounted:function(){null!==this.parametros.entidad&&this.getRegistro(this.parametros.entidad.id),this.$validator.localize("es")}}},2082:function(o,t,e){var r=e(2083);"string"==typeof r&&(r=[[o.i,r,""]]),r.locals&&(o.exports=r.locals);e(456)("327b008a",r,!0,{})},2083:function(o,t,e){t=o.exports=e(455)(!0),t.push([o.i,".subheader{height:10px}","",{version:3,sources:["/home/capresoca/projects/Capresoca-Frontend/src/components/administrativo/TalentoHumano/Fondos/RegistroFondos.vue"],names:[],mappings:"AACA,WACE,WAAa,CACd",file:"RegistroFondos.vue",sourcesContent:["\n.subheader {\n  height: 10px;\n}\n"],sourceRoot:""}])},2084:function(o,t,e){"use strict";var r=function(){var o=this,t=o.$createElement,e=o._self._c||t;return e("div",[e("v-card",[e("v-toolbar",{staticClass:"grey lighten-3 elevation-0 toolbar--dense"},[e("v-toolbar-title",[o._v(" "+o._s(o.ordenEdit?"Edicion de fondo":"Registro de fondo")+" ")])],1),o._v(" "),e("v-card-text",[e("v-form",{attrs:{"data-vv-scope":"formRegistroFondos"}},[e("v-container",{attrs:{fluid:"","grid-list-xl":""}},[e("v-layout",{attrs:{row:"",wrap:""}},[e("v-flex",{staticClass:"pb-4",attrs:{xs12:""}},[e("v-card",[e("v-toolbar",{staticClass:"grey lighten-4 elevation-0",attrs:{dense:""}},[e("v-toolbar-title",{staticClass:"subheading"},[e("v-icon",[o._v("assignment_ind")]),o._v(" General")],1)],1),o._v(" "),e("v-card-text",[e("v-layout",{attrs:{row:"",wrap:""}},[e("v-flex",{attrs:{xs12:"",sm4:""}},[e("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:3",expression:"'required|max:3'"}],key:"codigo",attrs:{label:"Código","prepend-icon":"list",name:"Codigo","error-messages":o.errors.collect("Codigo"),disabled:o.ordenEdit,"persistent-hint":"",hint:o.buscandoCodigo?"Estamos buscando el codigo":"",required:""},on:{keyup:function(t){return!t.type.indexOf("key")&&o._k(t.keyCode,"enter",13,t.key,"Enter")?null:o.findByCodigo(t)},blur:o.findByCodigo},model:{value:o.fondo.codigo,callback:function(t){o.$set(o.fondo,"codigo",t)},expression:"fondo.codigo"}})],1),o._v(" "),e("v-flex",{attrs:{xs12:"",sm4:""}},[e("postulador",{ref:"postulaTercero",attrs:{nodata:"Busqueda por nombre o número de documento.",required:"",hint:"nombre_completo",itemtext:"identificacion_completa",datatitle:"identificacion_completa",datasubtitle:"nombre_completo",filter:"identificacion_completa,nombre_completo",label:"Tercero",scope:"formRegistroFondos",lite:"",entidad:"terceros",preicon:"face",value:o.fondo.tercero,clearable:""},on:{change:function(t){return o.fondo.gn_tercero_id=t},objectReturn:function(t){return o.seleccionTercero(t)}}})],1),o._v(" "),e("v-flex",{attrs:{xs12:"",sm4:""}},[e("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"nombre",attrs:{label:"Nombre",name:"Nombre","prepend-icon":"person","error-messages":o.errors.collect("Nombre"),required:""},model:{value:o.fondo.nombre,callback:function(t){o.$set(o.fondo,"nombre",t)},expression:"fondo.nombre"}})],1),o._v(" "),e("v-flex",{attrs:{xs12:"",sm4:""}},[e("v-select",{attrs:{items:o.tiposFondo,label:"Tipos de fondo",multiple:"",chips:""},model:{value:o.fondo.tipo_fondo,callback:function(t){o.$set(o.fondo,"tipo_fondo",t)},expression:"fondo.tipo_fondo"}})],1),o._v(" "),e("v-flex",{attrs:{xs12:"",sm4:""}},[e("v-subheader",{staticClass:"subheader"},[o._v("Clase")]),o._v(" "),e("v-radio-group",{attrs:{row:""},model:{value:o.fondo.tipo,callback:function(t){o.$set(o.fondo,"tipo",t)},expression:"fondo.tipo"}},[e("v-radio",{key:"privado",attrs:{label:"Privado",value:"Privado"}}),o._v(" "),e("v-radio",{key:"publico",attrs:{label:"Publico",value:"Publico"}})],1)],1)],1)],1)],1)],1)],1)],1)],1)],1),o._v(" "),e("v-divider"),o._v(" "),"Confirmada"!==o.estado&&"Anulada"!==o.estado?e("v-card-actions",{staticClass:"grey lighten-4"},[e("v-layout",{attrs:{row:"",wrap:""}},[e("v-flex",{staticClass:"text-xs-right",attrs:{xs12:""}},[e("v-btn",{on:{click:function(t){return o.close()}}},[o._v("Cancelar")]),o._v(" "),e("v-btn",{attrs:{color:"primary",loading:o.loadingSubmit},on:{click:function(t){return t.preventDefault(),o.submit(t)}}},[o._v("Guardar")])],1)],1)],1):o._e()],1),o._v(" "),e("v-dialog",{attrs:{persistent:"","max-width":"290"},model:{value:o.dialogCodigo,callback:function(t){o.dialogCodigo=t},expression:"dialogCodigo"}},[e("v-card",[e("v-card-title",{staticClass:"headline"},[o._v("Codigo del fondo")]),o._v(" "),e("v-card-text",[o._v("El codigo del fondo ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo")]),o._v(" "),e("v-card-actions",[e("v-spacer"),o._v(" "),e("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:o.editarByCodigo}},[o._v("Cargar datos")]),o._v(" "),e("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:o.cerrarByCodigo}},[o._v("Cerrar")])],1)],1)],1)],1)},a=[],i={render:r,staticRenderFns:a};t.a=i},620:function(o,t,e){"use strict";function r(o){e(2082)}Object.defineProperty(t,"__esModule",{value:!0});var a=e(1165),i=e(2084),n=e(1),s=r,d=n(a.a,i.a,!1,s,null,null);t.default=d.exports}});
//# sourceMappingURL=228.54b5c68214eecefb5d6c.js.map