webpackJsonp([167],{1209:function(t,e,a){"use strict";var o=a(8),i=a.n(o),n=a(6),s=a(12),r=a(58);e.a={name:"Obligaciones",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(a.bind(null,247))},Loading:r.default},data:function(){return{obligaciones:[],search:"",tableLoading:!1,items_page:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],pagination:{per_page:15,current_page:1},headers:[{text:"Periodo",align:"left",sortable:!1,value:"periodo"},{text:"Consecutivo",align:"left",sortable:!1,value:"consecutivo"},{text:"Fecha",align:"left",sortable:!1,value:"fecha"},{text:"Objeto",align:"left",sortable:!1,value:"objeto"},{text:"Valor",align:"left",sortable:!1,value:"valor"},{text:"Estado",align:"left",sortable:!1,value:"estado"},{text:"Opciones",align:"left",sortable:!1,value:"id"}]}},created:function(){this.reloadPage()},watch:{itemObligaciones:function(t){"crear"===t.estado?this.obligaciones.splice(0,0,t.item):"editar"===t.estado&&this.obligaciones.splice(this.obligaciones.findIndex(function(e){return e.id===t.item.id}),1,t.item)},"pagination.per_page":function(){this.pagination.current_page=1,this.reloadPage()}},computed:i()({infoComponent:function(){return this.$store.getters.getInfoComponent("obligaciones")}},Object(n.c)({itemObligaciones:function(t){return t.tables.itemObligaciones}}),{pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)}}),methods:{reloadPage:function(){var t=this;this.tableLoading=!0,this.axios.get("pr_obligaciones?per_page="+this.pagination.per_page+"&page="+this.pagination.current_page+"&search=%25"+this.search+"%25").then(function(e){e.data.meta.per_page=t.items_page.find(function(t){return t.value===parseInt(e.data.meta.per_page)})?parseInt(e.data.meta.per_page):-1,t.pagination=e.data.meta,t.obligaciones=e.data.data,t.tableLoading=!1}).catch(function(e){return t.tableLoading=!1,t.$store.commit(s.a,{expression:" al hacer la busqueda de registros. ",error:e}),!1})},buscar:window.lodash.debounce(function(){this.pagination.current_page=1,this.reloadPage()},500),descargarPdf:function(t){var e=this;this.axios.get("firmar-ruta?nombre_ruta=reporte-pr-obligaciones&id="+t).then(function(t){window.open(t.data)}).catch(function(t){e.$store.commit(s.b,{msg:"Error al generar el archivo. "+t,color:"danger"})})}}}},2167:function(t,e,a){var o=a(2168);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);a(456)("460c3cac",o,!0,{})},2168:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Obligaciones.vue",sourceRoot:""}])},2169:function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-card",[a("toolbar-list",{attrs:{info:t.infoComponent,title:"Obligaciones",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear Item"}}),t._v(" "),a("loading",{model:{value:t.tableLoading,callback:function(e){t.tableLoading=e},expression:"tableLoading"}}),t._v(" "),a("v-container",{attrs:{fluid:""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"text-xs-right",attrs:{xs1:"",sm3:"",md6:""}},[a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",flat:"",icon:"",color:"accent"},on:{click:t.reloadPage},slot:"activator"},[a("v-icon",[t._v("cached")])],1),t._v(" "),a("span",[t._v("Actualizar registros")])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:"",md2:""}},[a("v-select",{attrs:{label:"Registros por página",items:t.items_page,"item-text":"text","item-value":"value"},model:{value:t.pagination.per_page,callback:function(e){t.$set(t.pagination,"per_page",e)},expression:"pagination.per_page"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:"",md4:""}},[a("v-text-field",{attrs:{"append-icon":"search",label:"Buscar"},on:{input:t.buscar},model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})],1)],1),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.obligaciones,loading:t.tableLoading,pagination:t.pagination,"total-items":t.obligaciones.length,search:t.search,"hide-actions":""},on:{"update:pagination":function(e){t.pagination=e}},scopedSlots:t._u([{key:"items",fn:function(e){return[a("td",[t._v(t._s(e.item.periodo))]),t._v(" "),a("td",[t._v(t._s(e.item.consecutivo))]),t._v(" "),a("td",[t._v(t._s(e.item.fecha))]),t._v(" "),a("td",[t._v(t._s(e.item.documento))]),t._v(" "),a("td",[t._v(t._s(t._f("numberFormat")(e.item.valor_obligacion,0,"$")))]),t._v(" "),a("td",[t._v(t._s(e.item.estado))]),t._v(" "),a("td",[a("v-speed-dial",{attrs:{direction:"left","open-on-hover":"",transition:"slide-x-transition"},model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-btn",{attrs:{slot:"activator",color:"blue darken-2",dark:"",fab:"",small:""},slot:"activator",model:{value:e.item.show,callback:function(a){t.$set(e.item,"show",a)},expression:"props.item.show"}},[a("v-icon",[t._v("chevron_left")]),t._v(" "),a("v-icon",[t._v("close")])],1),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(a){t.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:t.infoComponent.titulo_registro,parametros:{entidad:e.item,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},slot:"activator"},["Registrada"===e.item.estado?a("v-icon",{attrs:{color:"accent"}},[t._v("mode_edit")]):a("v-icon",{attrs:{color:"accent"}},[t._v("remove_red_eye")])],1),t._v(" "),"Registrada"===e.item.estado?a("span",[t._v("Editar")]):a("span",[t._v("Ver")])],1),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"white"},on:{click:function(a){return t.descargarPdf(e.item.id)}},slot:"activator"},[a("v-icon",{attrs:{color:"accent"}},[t._v("far fa-file-pdf")])],1),t._v(" "),a("span",[t._v("Imprimir")])],1)],1)],1)]}}])},[t._v(" "),a("template",{slot:"no-data"},[t.tableLoading?a("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n            Estamos cargando los registros. "),a("v-icon",[t._v("sentiment_satisfied_alt")])],1):a("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n            Lo sentimos, no tenemos registros para mostrar. "),a("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1),t._v(" "),a("template",{slot:"footer"},[a("td",{staticClass:"text-xs-center",attrs:{colspan:"100%"}},[a("v-pagination",{attrs:{"total-visible":7,length:t.pagination.last_page},on:{input:t.reloadPage},model:{value:t.pagination.current_page,callback:function(e){t.$set(t.pagination,"current_page",e)},expression:"pagination.current_page"}})],1)])],2)],1)],1)],1)},i=[],n={render:o,staticRenderFns:i};e.a=n},663:function(t,e,a){"use strict";function o(t){a(2167)}Object.defineProperty(e,"__esModule",{value:!0});var i=a(1209),n=a(2169),s=a(1),r=o,l=s(i.a,n.a,!1,r,"data-v-56f4aba9",null);e.default=l.exports}});
//# sourceMappingURL=167.9ca933f7c73f446bc720.js.map