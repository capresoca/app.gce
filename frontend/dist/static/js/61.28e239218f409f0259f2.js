webpackJsonp([61],{1319:function(a,e,t){"use strict";var i=t(249),o=t.n(i),n=t(13),r=t.n(n),s=t(14),l=t.n(s),c=t(252),d=t.n(c),u=t(7),v=t.n(u),m=t(12),f=t(59),p=t(58),g=t(743);e.a={name:"vigenciaTraslado",components:{Loading:p.default,Confirmar:g.a,ToolbarList:function(){return new Promise(function(a){a()}).then(t.bind(null,247))}},data:function(){return{dialogA:{visible:!1,content:null,registroID:null},fechaInicial:"",fechaFinal:"",fechaMinimaNovedad:"",fechaMinimaAfiliacion:"",menuDateFechaInicial:!1,menuDateFechaFinal:!1,menuDateFechaNovedad:!1,menuDateFechaAfiliacion:!1,swabierto:["No","Si"],tipo:["Traslados","Novedades"],trasladosVigentes:[],traslados:{},search:"",dialog:!1,tableLoading:!1,localLoading:!1,codigosUsados:"",nombresUsados:"",items_page:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],pagination:{per_page:15,current_page:1},headers:[{text:"Cons. vigencia",align:"left",sortable:!1,value:"consecutivo_vigencia"},{text:"Descripción",align:"left",sortable:!1,value:"descripcion"},{text:"Fecha inicio",align:"left",sortable:!1,value:"fecha_inicio"},{text:"Fecha fin",align:"left",sortable:!1,value:"fecha_fin"},{text:"Tipo",align:"left",sortable:!1,value:"tipo"},{text:"Abierto",align:"left",sortable:!1,value:"sw_abierto"},{text:"Cons. liquidacion",align:"left",sortable:!1,value:"consecutivo_liquidacion"},{text:"Opciones",align:"left",sortable:!1,value:"consecutivo_vigencia"}]}},beforeMount:function(){this.formReset()},mounted:function(){var a={custom:{codigo:{not_in:"Este código ya fue registrado."},nombre:{not_in:"Este nombre ya fue registrado."}}};this.$validator.localize("es",a)},created:function(){this.reloadPage()},watch:{"pagination.per_page":function(){this.pagination.current_page=1,this.reloadPage()}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("vigenciaTraslado")},modalTitulo:function(){return this.traslados.consecutivo_vigencia?"Editar vigencia: "+this.traslados.consecutivo_vigencia:"Nueva vigencia de traslado"},complementos:function(){return JSON.parse(v()(f.b.getters.complementosPeriodoLiquidacion))},pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)},computedDateFormattedFechaInicial:function(){return this.formDate(this.traslados.fecha_inicio)},computedDateFormattedFechaFinal:function(){return this.formDate(this.traslados.fecha_fin)},computedDateFormattedFechaMinimaNovedad:function(){return this.formDate(this.traslados.fecha_minima_novedad)},computedDateFormattedFechaMinimaAfiliacion:function(){return this.formDate(this.traslados.fecha_minima_afiliacion)}},methods:{reloadPage:function(){var a=this;this.tableLoading=!0,this.localLoading=!0,this.axios.get("vigencia?per_page="+this.pagination.per_page+"&page="+this.pagination.current_page+"&search=%25"+this.search+"%25").then(function(e){e.data.meta.per_page=a.items_page.find(function(a){return a.value===parseInt(e.data.meta.per_page)})?parseInt(e.data.meta.per_page):-1,a.pagination=e.data.meta,a.trasladosVigentes=e.data.data,a.tableLoading=!1,a.localLoading=!1}).catch(function(e){return a.tableLoading=!1,a.localLoading=!1,!1})},buscar:window.lodash.debounce(function(){this.pagination.current_page=1,this.reloadPage()},500),formReset:function(){this.traslados={consecutivo_vigencia:"",descripcion:null,fecha_inicio:null,fecha_fin:null,fecha_minima_afiliacion:null,fecha_minima_novedad:null,tipo:null,sw_abierto:null}},eliminar:function(a){this.dialogA.content="¿Está seguro de eliminar?",this.dialogA.registroID=a,this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.content=null,this.dialogA.registroID=null},confirmaAnulacion:function(a){var e=this;this.localLoading=!0,this.axios.delete("vigencia/eliminar/"+this.dialogA.registroID).then(function(a){e.$store.commit(m.b,{msg:"La vigencia de traslado se eliminó correctamente",color:"success"}),e.reloadPage(),e.cancelaAnulacion(),e.localLoading=!1,e.$refs.confirmo.pararCarga()}).catch(function(a){e.localLoading=!1,e.$store.commit(m.b,{msg:"Hay un error al eliminar el registro. "+a.response,color:"danger"})})},editar:function(a){this.dialog=!0,this.traslados=d()({},a),1===a.sw_abierto?this.traslados.swabierto="Si":this.traslados.swabierto="No",1===a.tipo?this.traslados.tipo="Novedades":this.traslados.tipo="Traslados"},close:function(){this.dialog=!1,this.formReset(),this.$validator.reset()},validador:function(a){return this.$validator.validateAll(a).then(function(a){return a})},save:function(){var a=this;return l()(r.a.mark(function e(){var t;return r.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.validador("formPago");case 2:if(!e.sent){e.next=9;break}a.dialog=!1,a.localLoading=!0,t=a.traslados.consecutivo_vigencia?a.axios.put("vigencia/actualizar/"+a.traslados.consecutivo_vigencia,a.traslados):a.axios.post("vigencia/crear",a.traslados),t.then(function(e){a.localLoading=!1,a.traslados.consecutivo_vigencia?a.$store.commit(m.b,{msg:"La vigencia de traslado se actualizó correctamente",color:"success"}):(console.log("la response de vig tras",e.data.tipo),"error"===e.data.tipo?a.$store.commit(m.b,{msg:e.data.message,color:"error"}):a.$store.commit(m.b,{msg:"Registro exitoso",color:"success"}),a.reloadPage()),a.dialog=!1}).catch(function(e){a.localLoading=!1,a.$store.commit(m.b,{msg:"Hay un error al guardar el registro. "+e.response,color:"danger"})}),e.next=10;break;case 9:a.$store.commit(m.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 10:a.formReset();case 11:case"end":return e.stop()}},e,a)}))()},formDate:function(a){if(!a)return null;var e=a.split("-"),t=o()(e,3);return t[0]+"/"+t[1]+"/"+t[2]}}}},2539:function(a,e,t){var i=t(2540);"string"==typeof i&&(i=[[a.i,i,""]]),i.locals&&(a.exports=i.locals);t(456)("7277ec7e",i,!0,{})},2540:function(a,e,t){e=a.exports=t(455)(!0),e.push([a.i,"","",{version:3,sources:[],names:[],mappings:"",file:"VigenciaTraslado.vue",sourceRoot:""}])},2541:function(a,e,t){"use strict";var i=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",[t("v-dialog",{attrs:{width:"1000px"},model:{value:a.dialog,callback:function(e){a.dialog=e},expression:"dialog"}},[t("v-form",{attrs:{"data-vv-scope":"formPago"}},[t("v-card",[t("v-card-title",{staticClass:"grey lighten-3 elevation-0"},[t("span",{staticClass:"title"},[a._v(a._s(a.modalTitulo))])]),a._v(" "),t("v-container",{attrs:{fluid:"","grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-menu",{ref:"menuDateRangoInicial",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:a.menuDateFechaInicial,callback:function(e){a.menuDateFechaInicial=e},expression:"menuDateFechaInicial"}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha inicial","prepend-icon":"event",readonly:"",name:"fecha inicial","error-messages":a.errors.collect("fecha inicial")},slot:"activator",model:{value:a.computedDateFormattedFechaInicial,callback:function(e){a.computedDateFormattedFechaInicial=e},expression:"computedDateFormattedFechaInicial"}}),a._v(" "),t("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){a.menuDateFechaInicial=!1},change:function(){var e=a.$validator.errors.items.findIndex(function(a){return"fecha inicial"===a.field});a.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:a.traslados.fecha_inicio,callback:function(e){a.$set(a.traslados,"fecha_inicio",e)},expression:"traslados.fecha_inicio"}})],1)],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-menu",{ref:"menuDateRangoFinal",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:a.menuDateFechaFinal,callback:function(e){a.menuDateFechaFinal=e},expression:"menuDateFechaFinal"}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],ref:"prueba",attrs:{slot:"activator",label:"Fecha final","prepend-icon":"event",readonly:"",name:"fecha final","error-messages":a.errors.collect("fecha final")},slot:"activator",model:{value:a.computedDateFormattedFechaFinal,callback:function(e){a.computedDateFormattedFechaFinal=e},expression:"computedDateFormattedFechaFinal"}}),a._v(" "),t("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){a.menuDateFechaFinal=!1},change:function(){var e=a.$validator.errors.items.findIndex(function(a){return"fecha final"===a.field});a.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:a.traslados.fecha_fin,callback:function(e){a.$set(a.traslados,"fecha_fin",e)},expression:"traslados.fecha_fin"}})],1)],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|max:500|",expression:"'required|max:500|'"}],key:"descripcion",attrs:{label:"Descripción",name:"descripcion",required:"","error-messages":a.errors.collect("descripcion")},model:{value:a.traslados.descripcion,callback:function(e){a.$set(a.traslados,"descripcion",e)},expression:"traslados.descripcion"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Tipo",items:a.tipo,name:"tipo","error-messages":a.errors.collect("tipo")},model:{value:a.traslados.tipo,callback:function(e){a.$set(a.traslados,"tipo",e)},expression:"traslados.tipo"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-menu",{ref:"menuDateRangoNovedad",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:a.menuDateFechaNovedad,callback:function(e){a.menuDateFechaNovedad=e},expression:"menuDateFechaNovedad"}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"date_format:YYYY/MM/DD",expression:"'date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha mínima novedad","prepend-icon":"event",readonly:"",name:"fecha novedad","error-messages":a.errors.collect("fecha novedad")},slot:"activator",model:{value:a.computedDateFormattedFechaMinimaNovedad,callback:function(e){a.computedDateFormattedFechaMinimaNovedad=e},expression:"computedDateFormattedFechaMinimaNovedad"}}),a._v(" "),t("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){a.menuDateFechaNovedad=!1},change:function(){var e=a.$validator.errors.items.findIndex(function(a){return"fecha novedad"===a.field});a.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:a.traslados.fecha_minima_novedad,callback:function(e){a.$set(a.traslados,"fecha_minima_novedad",e)},expression:"traslados.fecha_minima_novedad"}})],1)],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-menu",{ref:"menuDateRangoAfiliacion",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:a.menuDateFechaAfiliacion,callback:function(e){a.menuDateFechaAfiliacion=e},expression:"menuDateFechaAfiliacion"}},[t("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"date_format:YYYY/MM/DD",expression:"'date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha mínima afiliación","prepend-icon":"event",readonly:"",name:"fecha afiliacion","error-messages":a.errors.collect("fecha afiliacion")},slot:"activator",model:{value:a.computedDateFormattedFechaMinimaAfiliacion,callback:function(e){a.computedDateFormattedFechaMinimaAfiliacion=e},expression:"computedDateFormattedFechaMinimaAfiliacion"}}),a._v(" "),t("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){a.menuDateFechaAfiliacion=!1},change:function(){var e=a.$validator.errors.items.findIndex(function(a){return"fecha afiliacion"===a.field});a.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:a.traslados.fecha_minima_afiliacion,callback:function(e){a.$set(a.traslados,"fecha_minima_afiliacion",e)},expression:"traslados.fecha_minima_afiliacion"}})],1)],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Abierto",items:a.swabierto,name:"swabierto","error-messages":a.errors.collect("swabierto")},model:{value:a.traslados.swabierto,callback:function(e){a.$set(a.traslados,"swabierto",e)},expression:"traslados.swabierto"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",md6:"",lg6:""}},[t("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Consecutivo liquidación",items:a.complementos.periodoliquidacion,"item-value":"consecutivo_liquidacion","item-text":"descripcion",name:"liquidacion",required:"","error-messages":a.errors.collect("liquidacion"),filter:a.filterMunicipios},scopedSlots:a._u([{key:"item",fn:function(e){return[[t("v-list-tile-content",[t("v-list-tile-title",{domProps:{innerHTML:a._s(e.item.descripcion)}}),a._v(" "),t("v-list-tile-sub-title",{domProps:{innerHTML:a._s(e.item.consecutivo_liquidacion)}})],1)]]}}]),model:{value:a.traslados.consecutivo_liquidacion,callback:function(e){a.$set(a.traslados,"consecutivo_liquidacion",e)},expression:"traslados.consecutivo_liquidacion"}})],1)],1)],1),a._v(" "),t("v-card-actions",{staticClass:"grey lighten-4"},[t("v-spacer"),a._v(" "),t("v-btn",{on:{click:a.close}},[a._v("Cancelar")]),a._v(" "),t("v-btn",{attrs:{color:"primary",disabled:a.errors.any()},on:{click:a.save}},[a._v("Guardar")])],1)],1)],1)],1),a._v(" "),t("v-card",[t("toolbar-list",{attrs:{info:a.infoComponent,title:"Vigencia traslado",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear registro",btnplustruncate:""},on:{click:function(e){a.dialog=!0}}}),a._v(" "),t("v-container",{attrs:{fluid:""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{staticClass:"text-xs-right",attrs:{xs1:"",sm3:"",md6:""}},[t("v-tooltip",{attrs:{top:""}},[t("v-btn",{attrs:{slot:"activator",flat:"",icon:"",color:"accent"},on:{click:a.reloadPage},slot:"activator"},[t("v-icon",[a._v("cached")])],1),a._v(" "),t("span",[a._v("Actualizar registros")])],1)],1),a._v(" "),t("v-flex",{attrs:{xs12:"",sm3:"",md2:""}},[t("v-select",{attrs:{label:"Registros por página",items:a.items_page,"item-text":"text","item-value":"value"},model:{value:a.pagination.per_page,callback:function(e){a.$set(a.pagination,"per_page",e)},expression:"pagination.per_page"}})],1),a._v(" "),t("v-flex",{attrs:{xs12:"",sm6:"",md4:""}},[t("v-text-field",{attrs:{"append-icon":"search",label:"Buscar"},on:{input:a.buscar},model:{value:a.search,callback:function(e){a.search=e},expression:"search"}})],1)],1),a._v(" "),t("loading",{model:{value:a.localLoading,callback:function(e){a.localLoading=e},expression:"localLoading"}}),a._v(" "),t("v-data-table",{staticClass:"elevation-1",attrs:{headers:a.headers,items:a.trasladosVigentes,loading:a.tableLoading,pagination:a.pagination,"total-items":a.trasladosVigentes.length,search:a.search,"hide-actions":""},on:{"update:pagination":function(e){a.pagination=e}},scopedSlots:a._u([{key:"items",fn:function(e){return[t("td",[a._v(a._s(e.item.consecutivo_vigencia))]),a._v(" "),t("td",[a._v(a._s(e.item.descripcion))]),a._v(" "),t("td",[a._v(a._s(e.item.fecha_inicio))]),a._v(" "),t("td",[a._v(a._s(e.item.fecha_fin))]),a._v(" "),1==e.item.tipo?t("td",[a._v(a._s("Novedades"))]):a._e(),a._v(" "),2==e.item.tipo?t("td",[a._v(a._s("Traslados"))]):a._e(),a._v(" "),0==e.item.sw_abierto?t("td",[a._v(a._s("NO"))]):a._e(),a._v(" "),1==e.item.sw_abierto?t("td",[a._v(a._s("SI"))]):a._e(),a._v(" "),t("td",[a._v(a._s(e.item.periodo_liquidacion.descripcion))]),a._v(" "),t("td",[t("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:e.item.show,callback:function(t){a.$set(e.item,"show",t)},expression:"props.item.show"}},[t("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:e.item.show,callback:function(t){a.$set(e.item,"show",t)},expression:"props.item.show"}},[t("v-icon",[a._v("chevron_left")]),a._v(" "),t("v-icon",[a._v("close")])],1),a._v(" "),t("v-tooltip",{attrs:{top:""}},[t("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(t){return a.eliminar(e.item.consecutivo_vigencia)}},slot:"activator"},[t("v-icon",{attrs:{color:"accent"}},[a._v("delete")])],1),a._v(" "),t("span",[a._v("Eliminar")])],1),a._v(" "),t("v-tooltip",{attrs:{top:""}},[t("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(t){return a.editar(e.item)}},slot:"activator"},[t("v-icon",{attrs:{color:"accent"}},[a._v("mode_edit")])],1),a._v(" "),t("span",[a._v("Editar")])],1)],1)],1)]}}])},[a._v(" "),t("template",{slot:"no-data"},[a.tableLoading?t("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[a._v("\n            Estamos cargando los registros. "),t("v-icon",[a._v("sentiment_satisfied_alt")])],1):t("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[a._v("\n            Lo sentimos, no tenemos registros para mostrar. "),t("v-icon",[a._v("sentiment_very_dissatisfied")])],1)],1),a._v(" "),t("template",{slot:"footer"},[t("td",{staticClass:"text-xs-center",attrs:{colspan:"100%"}},[t("v-pagination",{attrs:{"total-visible":7,length:a.pagination.last_page},on:{input:a.reloadPage},model:{value:a.pagination.current_page,callback:function(e){a.$set(a.pagination,"current_page",e)},expression:"pagination.current_page"}})],1)])],2)],1)],1),a._v(" "),t("confirmar",{ref:"confirmo",attrs:{value:a.dialogA.visible,content:a.dialogA.content},on:{cancelar:a.cancelaAnulacion,aceptar:function(e){return a.confirmaAnulacion(e)}}})],1)},o=[],n={render:i,staticRenderFns:o};e.a=n},708:function(a,e,t){"use strict";function i(a){t(2539)}Object.defineProperty(e,"__esModule",{value:!0});var o=t(1319),n=t(2541),r=t(1),s=i,l=r(o.a,n.a,!1,s,"data-v-3f8986e8",null);e.default=l.exports},742:function(a,e,t){"use strict";var i=t(13),o=t.n(i),n=t(14),r=t.n(n),s=t(58);e.a={name:"Confirmar",props:{value:{type:Boolean,default:!1},content:{type:String},requiereMotivo:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}},components:{Loading:s.default},data:function(){return{loadingSubmit:!1,motivo:null}},watch:{value:function(a){a||(this.motivo=null,this.$validator.reset())}},methods:{submit:function(){var a=this;return r()(o.a.mark(function e(){return o.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.$validator.validateAll();case 2:if(!e.sent){e.next=5;break}a.loadingSubmit=!0,a.$emit("aceptar",a.motivo);case 5:case"end":return e.stop()}},e,a)}))()},pararCarga:function(){this.loadingSubmit=!1}}}},743:function(a,e,t){"use strict";function i(a){t(744)}var o=t(742),n=t(746),r=t(1),s=i,l=r(o.a,n.a,!1,s,"data-v-3773d9ac",null);e.a=l.exports},744:function(a,e,t){var i=t(745);"string"==typeof i&&(i=[[a.i,i,""]]),i.locals&&(a.exports=i.locals);t(456)("0e7a280b",i,!0,{})},745:function(a,e,t){e=a.exports=t(455)(!0),e.push([a.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Confirmar2.vue",sourceRoot:""}])},746:function(a,e,t){"use strict";var i=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("v-layout",{attrs:{row:"","justify-center":""}},[t("v-dialog",{key:"dialogConfirm",attrs:{persistent:"","max-width":"400"},model:{value:a.value,callback:function(e){a.value=e},expression:"value"}},[t("loading",{model:{value:a.loading,callback:function(e){a.loading=e},expression:"loading"}}),a._v(" "),t("v-card",[t("v-card-text",{staticClass:"title font-weight-light text-xs-center",domProps:{innerHTML:a._s(a.content)}}),a._v(" "),t("v-divider"),a._v(" "),t("v-card-actions",[t("v-btn",{attrs:{flat:""},on:{click:function(e){return e.stopPropagation(),a.$emit("cancelar")}}},[a._v("Cancelar")]),a._v(" "),t("v-spacer"),a._v(" "),t("v-btn",{attrs:{color:"primary",loading:a.loadingSubmit},on:{click:function(e){return e.stopPropagation(),a.submit(e)}}},[a._v("Aceptar")])],1)],1)],1)],1)},o=[],n={render:i,staticRenderFns:o};e.a=n}});
//# sourceMappingURL=61.28e239218f409f0259f2.js.map