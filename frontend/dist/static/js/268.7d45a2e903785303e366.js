webpackJsonp([268],{1102:function(e,t,r){"use strict";var o=r(13),n=r.n(o),i=r(14),a=r.n(i),s=r(40),c=r(248),d=r(12);t.a={name:"RegistroInterventor",props:["parametros"],data:function(){return{dialogSave:!1,dialogCodigo:!1,buscandoCodigo:!1,ordenEdit:!1,param:{},interventor:{id:null,tercero:{nombre_completo:"",identificacion_completa:""}}}},components:{Postulador:function(){return r.e(287).then(r.bind(null,747))}},watch:{},methods:{validador:function(e){return this.$validator.validateAll(e).then(function(e){return e})},interventorByCod:function(){var e=this;return a()(n.a.mark(function t(){return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:e.interventor.codigo&&(e.buscandoCodigo=!0,e.axios.get("ce_interventores/codigo/"+e.interventor.codigo).then(function(t){t.data.exists&&(e.interventor=t.data.interventor,e.dialogCodigo=!0),e.buscandoCodigo=!1}).catch(function(t){e.$store.commit(d.a,{expression:" Al intentar consultar codigo. ",error:t})}));case 1:case"end":return t.stop()}},t,e)}))()},submit:function(){var e=this;return a()(n.a.mark(function t(){return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.validador("formInterventor");case 2:if(!t.sent){t.next=6;break}e.interventor.id?e.axios.put("ce_interventores/"+e.interventor.id,e.interventor).then(function(t){e.$store.commit(d.b,{msg:t.data.message,color:"success"}),e.$store.dispatch(s.c,{contexto:e,itemId:e.parametros.tabOrigin}),e.$store.commit(c.P,{item:t.data.data,estado:"editar",key:e.parametros.key})}).catch(function(t){e.$store.commit(d.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}):e.axios.post("ce_interventores",e.interventor).then(function(t){e.$store.commit(d.b,{msg:"El Interventor se creo correctamente",color:"success"}),e.$store.dispatch(s.c,{contexto:e,itemId:e.parametros.tabOrigin}),e.$store.commit(c.P,{item:t.data.data,estado:"crear",key:e.parametros.key})}).catch(function(t){e.$store.commit(d.b,{msg:"Hay un error al guardar el registro. "+t.response,color:"danger"})}),t.next=7;break;case 6:e.$store.commit(d.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 7:case"end":return t.stop()}},t,e)}))()},getRegistro:function(e){var t=this;this.axios.get("ce_interventores/"+e).then(function(e){e.data.exists&&t.edit(e.data.data)}).catch(function(e){t.$store.commit(d.a,{expression:" al traer el interventor. ",error:e})})},edit:function(e){this.ordenEdit=!0,this.interventor=e},editarByCodigo:function(){this.dialogCodigo=!1,this.ordenEdit=!0},cerrarByCodigo:function(){this.formReset(),this.dialogCodigo=!1,this.ordenEdit=!1},formReset:function(){this.dialogCodigo=!1,this.ordenEdit=!1,this.interventor={}},terceroSeleccionado:function(e){null==this.interventor.id&&null!=e&&(this.interventor.tercero=e,this.interventor.gn_tercero_id=e.id,this.interventor.nombre=e.razon_social)}},mounted:function(){null!==this.parametros.entidad&&this.getRegistro(this.parametros.entidad.id),this.$validator.localize("es")}}},1912:function(e,t,r){"use strict";var o=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[r("v-card",[e.ordenEdit?r("v-card-title",{staticClass:"headline grey lighten-2",attrs:{"primary-title":""}},[e._v("\n      Editar Interventor\n    ")]):r("v-card-title",{staticClass:"headline grey lighten-2",attrs:{"primary-title":""}},[e._v("\n      Registrar Interventor\n    ")]),e._v(" "),r("v-card-text",[r("v-form",{attrs:{"data-vv-scope":"formInterventor"}},[r("v-container",{attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{row:"",wrap:""}},[r("v-flex",{attrs:{xs12:"",sm12:"",md2:"",lg2:""}},[r("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:16",expression:"'required|max:16'"}],key:"codigo",attrs:{label:"Codigo",name:"codigo","error-messages":e.errors.collect("codigo"),disabled:e.ordenEdit,hint:e.buscandoCodigo?"Estamos buscando el codigo":"","persistent-hint":"",required:""},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.interventorByCod(t)},blur:e.interventorByCod},model:{value:e.interventor.codigo,callback:function(t){e.$set(e.interventor,"codigo",t)},expression:"interventor.codigo"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:"",sm12:"",md10:"",lg10:""}},[r("postulador",{ref:"postuladorTercero",attrs:{nodata:"Busqueda por identificacion o nombre",required:"",hint:"nombre_completo",itemtext:"identificacion_completa",datatitle:"identificacion_completa",datasubtitle:"nombre_completo",filter:"identificacion_completa,nombre_completo",label:"Tercero",scope:"formInterventor",entidad:"terceros",preicon:"person",value:e.interventor.tercero,clearable:""},on:{change:function(t){return e.interventor.gn_tercero_id=t},objectReturn:function(t){return e.terceroSeleccionado(t)}}})],1),e._v(" "),r("v-flex",{attrs:{xs12:"",sm12:"",md12:"",lg12:""}},[r("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:255",expression:"'required|max:255'"}],key:"nombre",attrs:{label:"Nombre",name:"nombre",required:"","error-messages":e.errors.collect("nombre")},model:{value:e.interventor.nombre,callback:function(t){e.$set(e.interventor,"nombre",t)},expression:"interventor.nombre"}})],1)],1)],1)],1)],1),e._v(" "),r("v-divider"),e._v(" "),r("v-card-actions",[r("v-spacer"),e._v(" "),r("v-btn",{attrs:{color:"primary",flat:""},on:{click:e.submit}},[e._v("\n        Guardar\n      ")])],1)],1),e._v(" "),r("v-dialog",{attrs:{persistent:"","max-width":"290"},model:{value:e.dialogCodigo,callback:function(t){e.dialogCodigo=t},expression:"dialogCodigo"}},[r("v-card",[r("v-card-title",{staticClass:"headline"},[e._v("Codigo del Interventor")]),e._v(" "),r("v-card-text",[e._v("El codigo del Interventor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo")]),e._v(" "),r("v-card-actions",[r("v-spacer"),e._v(" "),r("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.editarByCodigo}},[e._v("Cargar datos")]),e._v(" "),r("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.cerrarByCodigo}},[e._v("Cerrar")])],1)],1)],1)],1)},n=[],i={render:o,staticRenderFns:n};t.a=i},578:function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=r(1102),n=r(1912),i=r(1),a=i(o.a,n.a,!1,null,null,null);t.default=a.exports}});
//# sourceMappingURL=268.7d45a2e903785303e366.js.map