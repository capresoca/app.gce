webpackJsonp([110],{1138:function(t,a,e){"use strict";var i=e(8),n=e.n(i),o=e(6),s=e(12),r=e(58);a.a={name:"SegundaInstancia",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(e.bind(null,247))},Loading:r.default},data:function(){return{liquidaciones:[],liquidacion:{},loadingSubmit:!1,search:"",tableLoading:!1,items_page:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],pagination:{per_page:15,current_page:1},headers:[{text:"Código",align:"left",sortable:!1,value:"codigo"},{text:"Afiliado",align:"left",sortable:!1,value:"afilidado"},{text:"Tipo",align:"left",sortable:!1,value:"tipo"},{text:"Fecha inicio",align:"left",sortable:!1,value:"fechaInicio"},{text:"Fecha fin",align:"left",sortable:!1,value:"fechaFin"},{text:"Estado",align:"left",sortable:!1,value:"estado"},{text:"Opciones",align:"left",sortable:!1,value:"id"}]}},created:function(){this.reloadPage()},watch:{itemLiquidacion:function(t){"crear"===t.estado?this.liquidaciones.splice(0,0,t.item):"editar"===t.estado&&this.liquidaciones.splice(this.liquidaciones.findIndex(function(a){return a.id===t.item.id}),1,t.item)},"pagination.per_page":function(t){this.pagination.current_page=1,this.reloadPage()}},computed:n()({infoComponent:function(){return this.$store.getters.getInfoComponent("primeraInstancia")}},Object(o.c)({itemLiquidacion:function(t){return t.tables.itemLiquidacion}}),{pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)}}),methods:{reloadPage:function(){var t=this;this.tableLoading=!0,this.axios.get("incapacidades?per_page="+this.pagination.per_page+"&page="+this.pagination.current_page+"&search=%25"+this.search+"%25").then(function(a){a.data.meta.per_page=t.items_page.find(function(t){return t.value===parseInt(a.data.meta.per_page)})?parseInt(a.data.meta.per_page):-1,t.pagination=a.data.meta,t.liquidaciones=a.data.data,t.tableLoading=!1}).catch(function(a){return t.tableLoading=!1,t.$store.commit(s.b,{msg:"Error al traer los registros. "+a,color:"danger"}),!1})},buscar:window.lodash.debounce(function(){this.pagination.current_page=1,this.reloadPage()},500),generarReporte:function(t){var a=this;this.axios.get("firmar-ruta?nombre_ruta=certificado-presupuestal&id="+t).then(function(t){var a=t.data;window.open(a,"_blank").focus()}).catch(function(t){a.$store.commit(s.b,{msg:"Error al Imprimir. "+t.response,color:"danger"})})},imprimirIncapacidad:function(t){var a=this;this.axios.get("firmar-ruta?nombre_ruta=solicitud_incapacidad&id="+t.id).then(function(t){""!==t.data&&window.open(t.data,"_blank")}).catch(function(t){a.$store.commit("SNACK_ERROR_LIST",{expression:" al generar el documento. ",error:t})})}}}},2028:function(t,a,e){var i=e(2029);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);e(456)("0187751c",i,!0,{})},2029:function(t,a,e){a=t.exports=e(455)(!0),a.push([t.i,".estado[data-v-2ea4e48c]{height:10px;padding:0}","",{version:3,sources:["/home/capresoca/projects/Capresoca-Frontend/src/components/misional/atencionUsuario/liquidacionIncapacidades/SegundaInstancia.vue"],names:[],mappings:"AACA,yBACE,YAAa,AACb,SAAW,CACZ",file:"SegundaInstancia.vue",sourcesContent:["\n.estado[data-v-2ea4e48c] {\n  height: 10px;\n  padding: 0;\n}\n"],sourceRoot:""}])},2030:function(t,a,e){"use strict";var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("v-card",[e("toolbar-list",{attrs:{info:t.infoComponent,title:"Liquidación incapacidades",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear liquidación"}}),t._v(" "),e("loading",{model:{value:t.tableLoading,callback:function(a){t.tableLoading=a},expression:"tableLoading"}}),t._v(" "),e("v-container",{attrs:{fluid:""}},[e("v-layout",{attrs:{row:"",wrap:""}},[e("v-flex",{staticClass:"text-xs-right",attrs:{xs1:"",sm3:"",md6:""}},[e("v-tooltip",{attrs:{top:""}},[e("v-btn",{attrs:{slot:"activator",flat:"",icon:"",color:"accent"},on:{click:t.reloadPage},slot:"activator"},[e("v-icon",[t._v("cached")])],1),t._v(" "),e("span",[t._v("Actualizar registros")])],1)],1),t._v(" "),e("v-flex",{attrs:{xs12:"",sm3:"",md2:""}},[e("v-select",{attrs:{label:"Registros por página",items:t.items_page,"item-text":"text","item-value":"value"},model:{value:t.pagination.per_page,callback:function(a){t.$set(t.pagination,"per_page",a)},expression:"pagination.per_page"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:"",sm6:"",md4:""}},[e("v-text-field",{attrs:{"append-icon":"search",label:"Buscar"},on:{input:t.buscar},model:{value:t.search,callback:function(a){t.search=a},expression:"search"}})],1)],1),t._v(" "),e("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.liquidaciones,loading:t.tableLoading,pagination:t.pagination,"total-items":t.liquidaciones.length,search:t.search,"hide-actions":""},on:{"update:pagination":function(a){t.pagination=a}},scopedSlots:t._u([{key:"items",fn:function(a){return[e("td",[t._v(t._s(a.item.consecutivo))]),t._v(" "),e("td",[e("mini-card-detail",{attrs:{data:a.item.mini_afiliado}})],1),t._v(" "),e("td",[t._v(t._s(a.item.tipo))]),t._v(" "),e("td",[t._v(t._s(a.item.fecha_inicio))]),t._v(" "),e("td",[t._v(t._s(a.item.fecha_fin))]),t._v(" "),e("td",[t._v(t._s(a.item.estado))]),t._v(" "),e("td",[e("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:a.item.show,callback:function(e){t.$set(a.item,"show",e)},expression:"props.item.show"}},[e("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:a.item.show,callback:function(e){t.$set(a.item,"show",e)},expression:"props.item.show"}},[e("v-icon",[t._v("chevron_left")]),t._v(" "),e("v-icon",[t._v("close")])],1),t._v(" "),e("v-tooltip",{attrs:{top:""}},[e("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(e){t.$store.commit("NAV_ADD_ITEM",{ruta:"detalleIncapacidades",titulo:"Detalle Incapacidades",parametros:{entidad:a.item,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},slot:"activator"},[e("v-icon",{attrs:{color:"accent"}},[t._v("find_in_page")])],1),t._v(" "),e("span",[t._v("Ver detalle")])],1),t._v(" "),"Aprobada"===a.item.estado?e("v-tooltip",{attrs:{top:""}},[e("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(e){return t.generarReporte(a.item.pr_solicitud_cp_id)}},slot:"activator"},[e("v-icon",{attrs:{color:"accent"},domProps:{textContent:t._s("far fa-file-pdf")}})],1),t._v(" "),e("span",[t._v("Solicitud CDP")])],1):t._e(),t._v(" "),e("v-tooltip",{attrs:{top:""}},[e("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(e){return t.imprimirIncapacidad(a.item)}},slot:"activator"},[e("v-icon",{attrs:{color:"primary"},domProps:{textContent:t._s("far fa-file-pdf")}})],1),t._v(" "),e("span",[t._v("Solicitud de incapacidad")])],1)],1)],1)]}}])},[t._v(" "),e("template",{slot:"no-data"},[t.tableLoading?e("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n              Estamos cargando los registros. "),e("v-icon",[t._v("sentiment_satisfied_alt")])],1):e("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n              Lo sentimos, no tenemos registros para mostrar. "),e("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1),t._v(" "),e("template",{slot:"footer"},[e("td",{staticClass:"text-xs-center",attrs:{colspan:"100%"}},[e("v-pagination",{attrs:{"total-visible":7,length:t.pagination.last_page},on:{input:t.reloadPage},model:{value:t.pagination.current_page,callback:function(a){t.$set(t.pagination,"current_page",a)},expression:"pagination.current_page"}})],1)])],2)],1)],1)],1)},n=[],o={render:i,staticRenderFns:n};a.a=o},602:function(t,a,e){"use strict";function i(t){e(2028)}Object.defineProperty(a,"__esModule",{value:!0});var n=e(1138),o=e(2030),s=e(1),r=i,c=s(n.a,o.a,!1,r,"data-v-2ea4e48c",null);a.default=c.exports}});
//# sourceMappingURL=110.1fce855de2fde102b0b7.js.map