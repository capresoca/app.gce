webpackJsonp([52],{1360:function(t,e,a){"use strict";var i=a(13),o=a.n(i),n=a(14),r=a.n(n),s=a(249),l=a.n(s),c=a(252),u=a.n(c),v=a(12),d=a(58),f=a(743);e.a={name:"festivos",components:{Loading:d.default,Confirmar:f.a,ToolbarList:function(){return new Promise(function(t){t()}).then(a.bind(null,247))}},data:function(){return{dialogA:{visible:!1,content:null,registroID:null},menuDateFecha:!1,festivos:[],festivo:{},search:"",dialog:!1,tableLoading:!1,localLoading:!1,items_page:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],pagination:{per_page:15,current_page:1},headers:[{text:"Festivo",align:"left",sortable:!1,value:""},{text:"Descripción",align:"left",sortable:!1,value:""},{text:"Año",align:"left",sortable:!1,value:""},{text:"Fecha",align:"left",sortable:!1,value:""},{text:"Opciones",align:"left",sortable:!1,value:""}]}},beforeMount:function(){this.formReset()},mounted:function(){},created:function(){this.reloadPage()},watch:{"pagination.per_page":function(){this.pagination.current_page=1,this.reloadPage()}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("diasFestivos")},modalTitulo:function(){return this.festivo.dia_festivo?"Editar día festivo: "+this.festivo.dia_festivo:"Nuevo día festivo"},pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)},computedDateFormattedFecha:function(){return this.formDate(this.festivo.fecharaw)}},methods:{reloadPage:function(){var t=this;this.tableLoading=!0,this.localLoading=!0,this.axios.get("diafestivo?per_page="+this.pagination.per_page+"&page="+this.pagination.current_page+"&search=%25"+this.search+"%25").then(function(e){e.data.meta.per_page=t.items_page.find(function(t){return t.value===parseInt(e.data.meta.per_page)})?parseInt(e.data.meta.per_page):-1,t.pagination=e.data.meta,t.festivos=e.data.data,t.tableLoading=!1,t.localLoading=!1}).catch(function(e){return t.tableLoading=!1,t.localLoading=!1,!1})},buscar:window.lodash.debounce(function(){this.pagination.current_page=1,this.reloadPage()},500),formReset:function(){this.festivo={descripcion:null,fecha:null,ano:null}},eliminar:function(t){this.dialogA.content="¿Está seguro de eliminar?",this.dialogA.registroID=t,this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.content=null,this.dialogA.registroID=null},confirmaAnulacion:function(t){var e=this;this.localLoading=!0,this.axios.delete("diafestivo/eliminar/"+this.dialogA.registroID).then(function(t){e.$store.commit(v.b,{msg:"El día festivo se eliminó correctamente",color:"success"}),e.reloadPage(),e.cancelaAnulacion(),e.localLoading=!1,e.$refs.confirmo.pararCarga()}).catch(function(t){e.localLoading=!1,e.$store.commit(v.b,{msg:"Hay un error al eliminar el registro. "+t.response,color:"danger"})})},editar:function(t){this.dialog=!0,this.festivo=u()({},t)},close:function(){this.dialog=!1,this.formReset(),this.$validator.reset()},formDate:function(t){if(!t)return null;var e=t.split("-"),a=l()(e,3);return a[0]+"/"+a[1]+"/"+a[2]},validador:function(t){return this.$validator.validateAll(t).then(function(t){return t})},save:function(){var t=this;return r()(o.a.mark(function e(){var a;return o.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.validador("formPago");case 2:if(!e.sent){e.next=9;break}t.dialog=!1,t.localLoading=!0,a=t.festivo.dia_festivo?t.axios.put("diafestivo/actualizar/"+t.festivo.dia_festivo,t.festivo):t.axios.post("diafestivo/crear",t.festivo),a.then(function(e){t.localLoading=!1,t.festivo.dia_festivo?t.$store.commit(v.b,{msg:"El día festivo se actualizó correctamente",color:"success"}):(t.$store.commit(v.b,{msg:"Registro exitoso",color:"success"}),t.reloadPage()),t.dialog=!1}).catch(function(e){t.localLoading=!1,t.$store.commit(v.b,{msg:"Hay un error al guardar el registro. "+e.response,color:"danger"})}),e.next=10;break;case 9:t.$store.commit(v.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 10:t.formReset();case 11:case"end":return e.stop()}},e,t)}))()}},filters:{anio:function(t){var e=new Date(t),a=e.getMonth(),i=e.getDate(),o=e.getFullYear();return 11===a&&31===i&&(o+=1),o}}}},2671:function(t,e,a){var i=a(2672);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(456)("6e371e1a",i,!0,{})},2672:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"TBDiaFestivo.vue",sourceRoot:""}])},2673:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-dialog",{attrs:{width:"500px"},model:{value:t.dialog,callback:function(e){t.dialog=e},expression:"dialog"}},[a("v-form",{attrs:{"data-vv-scope":"formPago"}},[a("v-card",[a("v-card-title",{staticClass:"grey lighten-3 elevation-0"},[a("span",{staticClass:"title"},[t._v(t._s(t.modalTitulo))])]),t._v(" "),a("v-container",{attrs:{fluid:"","grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:200|",expression:"'required|max:200|'"}],key:"descripcion",attrs:{label:"Descripción",name:"descripcion",required:"","error-messages":t.errors.collect("descripcion")},model:{value:t.festivo.descripcion,callback:function(e){t.$set(t.festivo,"descripcion",e)},expression:"festivo.descripcion"}}),t._v(" "),a("v-menu",{ref:"menuDateFecha",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:t.menuDateFecha,callback:function(e){t.menuDateFecha=e},expression:"menuDateFecha"}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],ref:"prueba",attrs:{slot:"activator",label:"Fecha","prepend-icon":"event",readonly:"",name:"fecha","error-messages":t.errors.collect("fecha")},slot:"activator",model:{value:t.computedDateFormattedFecha,callback:function(e){t.computedDateFormattedFecha=e},expression:"computedDateFormattedFecha"}}),t._v(" "),a("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){t.menuDateFecha=!1},change:function(){var e=t.$validator.errors.items.findIndex(function(t){return"fecha"===t.field});t.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:t.festivo.fecharaw,callback:function(e){t.$set(t.festivo,"fecharaw",e)},expression:"festivo.fecharaw"}})],1)],1)],1)],1),t._v(" "),a("v-card-actions",{staticClass:"grey lighten-4"},[a("v-spacer"),t._v(" "),a("v-btn",{on:{click:t.close}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{color:"primary",disabled:t.errors.any()},on:{click:t.save}},[t._v("Guardar")])],1)],1)],1)],1),t._v(" "),a("v-card",[a("toolbar-list",{attrs:{info:t.infoComponent,title:"Auditoria de Prestaciones Económicas",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear registro",btnplustruncate:""},on:{click:function(e){t.dialog=!0}}}),t._v(" "),a("v-container",{attrs:{fluid:""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"text-xs-right",attrs:{xs1:"",sm3:"",md6:""}},[a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",flat:"",icon:"",color:"accent"},on:{click:t.reloadPage},slot:"activator"},[a("v-icon",[t._v("cached")])],1),t._v(" "),a("span",[t._v("Actualizar registros")])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:"",md2:""}},[a("v-select",{attrs:{label:"Registros por página",items:t.items_page,"item-text":"text","item-value":"value"},model:{value:t.pagination.per_page,callback:function(e){t.$set(t.pagination,"per_page",e)},expression:"pagination.per_page"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:"",md4:""}},[a("v-text-field",{attrs:{"append-icon":"search",label:"Buscar"},on:{input:t.buscar},model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})],1)],1),t._v(" "),a("loading",{model:{value:t.localLoading,callback:function(e){t.localLoading=e},expression:"localLoading"}}),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.festivos,loading:t.tableLoading,pagination:t.pagination,"total-items":t.festivos.length,search:t.search,"hide-actions":""},on:{"update:pagination":function(e){t.pagination=e}},scopedSlots:t._u([{key:"items",fn:function(e){return[a("td",[t._v(t._s(e.item.dia_festivo))]),t._v(" "),a("td",[t._v(t._s(e.item.descripcion))]),t._v(" "),a("td",[t._v(t._s(t._f("anio")(e.item.fecharaw)))]),t._v(" "),a("td",[t._v(t._s(e.item.fecha))]),t._v(" "),a("td",[a("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-icon",[t._v("chevron_left")]),t._v(" "),a("v-icon",[t._v("close")])],1),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(a){return t.eliminar(e.item.dia_festivo)}},slot:"activator"},[a("v-icon",{attrs:{color:"accent"}},[t._v("delete")])],1),t._v(" "),a("span",[t._v("Eliminar")])],1),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(a){return t.editar(e.item)}},slot:"activator"},[a("v-icon",{attrs:{color:"accent"}},[t._v("mode_edit")])],1),t._v(" "),a("span",[t._v("Editar")])],1)],1)],1)]}}])},[t._v(" "),a("template",{slot:"no-data"},[t.tableLoading?a("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n            Estamos cargando los registros. "),a("v-icon",[t._v("sentiment_satisfied_alt")])],1):a("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n            Lo sentimos, no tenemos registros para mostrar. "),a("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1),t._v(" "),a("template",{slot:"footer"},[a("td",{staticClass:"text-xs-center",attrs:{colspan:"100%"}},[a("v-pagination",{attrs:{"total-visible":7,length:t.pagination.last_page},on:{input:t.reloadPage},model:{value:t.pagination.current_page,callback:function(e){t.$set(t.pagination,"current_page",e)},expression:"pagination.current_page"}})],1)])],2)],1)],1),t._v(" "),a("confirmar",{ref:"confirmo",attrs:{value:t.dialogA.visible,content:t.dialogA.content},on:{cancelar:t.cancelaAnulacion,aceptar:function(e){return t.confirmaAnulacion(e)}}})],1)},o=[],n={render:i,staticRenderFns:o};e.a=n},734:function(t,e,a){"use strict";function i(t){a(2671)}Object.defineProperty(e,"__esModule",{value:!0});var o=a(1360),n=a(2673),r=a(1),s=i,l=r(o.a,n.a,!1,s,"data-v-235d7954",null);e.default=l.exports},742:function(t,e,a){"use strict";var i=a(13),o=a.n(i),n=a(14),r=a.n(n),s=a(58);e.a={name:"Confirmar",props:{value:{type:Boolean,default:!1},content:{type:String},requiereMotivo:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}},components:{Loading:s.default},data:function(){return{loadingSubmit:!1,motivo:null}},watch:{value:function(t){t||(this.motivo=null,this.$validator.reset())}},methods:{submit:function(){var t=this;return r()(o.a.mark(function e(){return o.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$validator.validateAll();case 2:if(!e.sent){e.next=5;break}t.loadingSubmit=!0,t.$emit("aceptar",t.motivo);case 5:case"end":return e.stop()}},e,t)}))()},pararCarga:function(){this.loadingSubmit=!1}}}},743:function(t,e,a){"use strict";function i(t){a(744)}var o=a(742),n=a(746),r=a(1),s=i,l=r(o.a,n.a,!1,s,"data-v-3773d9ac",null);e.a=l.exports},744:function(t,e,a){var i=a(745);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(456)("0e7a280b",i,!0,{})},745:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Confirmar2.vue",sourceRoot:""}])},746:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-layout",{attrs:{row:"","justify-center":""}},[a("v-dialog",{key:"dialogConfirm",attrs:{persistent:"","max-width":"400"},model:{value:t.value,callback:function(e){t.value=e},expression:"value"}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-card",[a("v-card-text",{staticClass:"title font-weight-light text-xs-center",domProps:{innerHTML:t._s(t.content)}}),t._v(" "),a("v-divider"),t._v(" "),a("v-card-actions",[a("v-btn",{attrs:{flat:""},on:{click:function(e){return e.stopPropagation(),t.$emit("cancelar")}}},[t._v("Cancelar")]),t._v(" "),a("v-spacer"),t._v(" "),a("v-btn",{attrs:{color:"primary",loading:t.loadingSubmit},on:{click:function(e){return e.stopPropagation(),t.submit(e)}}},[t._v("Aceptar")])],1)],1)],1)],1)},o=[],n={render:i,staticRenderFns:o};e.a=n}});
//# sourceMappingURL=52.c2dba18d06539ef59585.js.map