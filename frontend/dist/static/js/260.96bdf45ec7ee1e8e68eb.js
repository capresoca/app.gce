webpackJsonp([260],{1157:function(e,a,i){"use strict";var t=i(13),d=i.n(t),o=i(14),r=i.n(o),n=i(40),s=i(248),c=i(12);a.a={name:"RegistroUnidadMedidaInventario",props:["parametros"],data:function(){return{buscandoCodigo:!1,dialogCodigo:!1,ordenEdit:!1,unidadmedida:{}}},methods:{findByCodigo:function(){var e=this;this.unidadmedida.codigo&&(this.buscandoCodigo=!0,this.axios.get("in_unidades_medida/codigo/"+this.unidadmedida.codigo).then(function(a){a.data.exists&&(e.$store.commit(c.b,{msg:"El codigo de la unidad de medida existe",color:"success"}),e.formReset(),e.unidadmedida=a.data.data,e.dialogCodigo=!0),e.buscandoCodigo=!1}).catch(function(a){e.buscandoCodigo=!1,e.$store.commit(c.b,{msg:"Hay un error al guardar el registro. "+a.response,color:"danger"})}))},validador:function(e){return this.$validator.validateAll(e).then(function(e){return e})},submit:function(){var e=this;return r()(d.a.mark(function a(){return d.a.wrap(function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,e.validador("formRegistroAlmacenInvetarios");case 2:if(!a.sent){a.next=6;break}e.unidadmedida.id?e.axios.put("in_unidades_medida/"+e.unidadmedida.id,e.unidadmedida).then(function(a){e.$store.commit(c.b,{msg:"El unidad de medida se actualizo correctamente",color:"success"}),e.formReset(),e.$store.dispatch(n.c,{contexto:e,itemId:e.parametros.tabOrigin}),e.$store.commit(s._29,{item:a.data.data,estado:"editar",key:e.parametros.key})}).catch(function(a){e.$store.commit(c.b,{msg:"Hay un error al guardar el registro. "+a.response,color:"danger"})}):e.axios.post("in_unidades_medida",e.unidadmedida).then(function(a){e.$store.commit(c.b,{msg:"El unidad de medida se creó correctamente",color:"success"}),e.formReset(),e.$store.dispatch(n.c,{contexto:e,itemId:e.parametros.tabOrigin}),e.$store.commit(s._29,{item:a.data.data,estado:"crear",key:e.parametros.key})}).catch(function(a){console.log(a),e.$store.commit(c.b,{msg:"Hay un error al guardar el registro. "+a.response,color:"danger"})}),a.next=7;break;case 6:e.$store.commit(c.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 7:case"end":return a.stop()}},a,e)}))()},editarByCodigo:function(){this.dialogCodigo=!1,this.ordenEdit=!0},cerrarByCodigo:function(){this.formReset(),this.ordenEdit=!1,this.dialogCodigo=!1},formReset:function(){this.unidadmedida={},this.buscandoCodigo=!1,this.dialogCodigo=!1,this.ordenEdit=!1},getRegistro:function(e){var a=this;this.axios.get("in_unidades_medida/"+e).then(function(e){e.data.exists&&a.edit(e.data.data)}).catch(function(e){a.$store.commit(c.a,{expression:" al traer el unidadmedida. ",error:e})})},edit:function(e){this.ordenEdit=!0,this.unidadmedida=e}},mounted:function(){null!==this.parametros.entidad&&this.getRegistro(this.parametros.entidad.id),this.$validator.localize("es")}}},2072:function(e,a,i){"use strict";var t=function(){var e=this,a=e.$createElement,i=e._self._c||a;return i("div",[i("v-card",[i("v-card-title",{staticClass:"headline grey lighten-2",attrs:{"primary-title":""}},[e._v("\n    "+e._s(e.ordenEdit?"Edicion de unidad de medida de invetarios":"Registro de unidad de medida de invetarios")+"\n    ")]),e._v(" "),i("v-card-text",[i("v-form",{attrs:{"data-vv-scope":"formRegistroAlmacenInvetarios"}},[i("v-container",{attrs:{fluid:"","grid-list-xl":""}},[i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:"",sm12:"",md3:"",lg2:""}},[i("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"codigo",attrs:{label:"Codigo",name:"Codigo","error-messages":e.errors.collect("Codigo"),disabled:e.ordenEdit,"persistent-hint":"",hint:e.buscandoCodigo?"Estamos buscando el codigo":"",required:""},on:{keyup:function(a){return!a.type.indexOf("key")&&e._k(a.keyCode,"enter",13,a.key,"Enter")?null:e.findByCodigo(a)},blur:e.findByCodigo},model:{value:e.unidadmedida.codigo,callback:function(a){e.$set(e.unidadmedida,"codigo",a)},expression:"unidadmedida.codigo"}})],1),e._v(" "),i("v-flex",{attrs:{xs12:"",sm12:"",md6:"",lg8:""}},[i("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"nombre",attrs:{label:"Nombre",name:"Nombre","error-messages":e.errors.collect("Nombre"),required:""},model:{value:e.unidadmedida.nombre,callback:function(a){e.$set(e.unidadmedida,"nombre",a)},expression:"unidadmedida.nombre"}})],1)],1),e._v(" "),i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:"",sm12:"",md12:"",lg12:""}},[i("v-btn",{attrs:{color:"success"},on:{click:e.submit}},[e._v("\n              Guardar\n              ")])],1)],1)],1)],1)],1)],1),e._v(" "),i("v-dialog",{attrs:{persistent:"","max-width":"290"},model:{value:e.dialogCodigo,callback:function(a){e.dialogCodigo=a},expression:"dialogCodigo"}},[i("v-card",[i("v-card-title",{staticClass:"headline"},[e._v("Codigo del subgrupo")]),e._v(" "),i("v-card-text",[e._v("El codigo del subgrupo ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo")]),e._v(" "),i("v-card-actions",[i("v-spacer"),e._v(" "),i("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.editarByCodigo}},[e._v("Cargar datos")]),e._v(" "),i("v-btn",{attrs:{color:"green darken-1",flat:""},on:{click:e.cerrarByCodigo}},[e._v("Cerrar")])],1)],1)],1)],1)},d=[],o={render:t,staticRenderFns:d};a.a=o},612:function(e,a,i){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var t=i(1157),d=i(2072),o=i(1),r=o(t.a,d.a,!1,null,null,null);a.default=r.exports}});
//# sourceMappingURL=260.96bdf45ec7ee1e8e68eb.js.map