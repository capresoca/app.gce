webpackJsonp([351],{1435:function(t,a,e){"use strict";function i(t){e(1436)}Object.defineProperty(a,"__esModule",{value:!0});var n=e(947),o=e(1438),s=e(1),r=i,l=s(n.a,o.a,!1,r,null,null);a.default=l.exports},1436:function(t,a,e){var i=e(1437);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);e(456)("0b326fe8",i,!0,{})},1437:function(t,a,e){a=t.exports=e(455)(!0),a.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DetalleGeneralAtencionesServicios.vue",sourceRoot:""}])},1438:function(t,a,e){"use strict";var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("v-container",{staticClass:"pa-0",attrs:{fluid:"","grid-list-xl":""}},[e("v-layout",{attrs:{row:"",wrap:""}},[e("v-flex",{staticClass:"text-xs-right",attrs:{xs1:"",sm3:"",md6:""}},[e("v-tooltip",{attrs:{top:""}},[e("v-btn",{attrs:{slot:"activator",flat:"",icon:"",color:"accent"},on:{click:t.reloadPage},slot:"activator"},[e("v-icon",[t._v("cached")])],1),t._v(" "),e("span",[t._v("Actualizar registros")])],1)],1),t._v(" "),e("v-flex",{attrs:{xs12:"",sm3:"",md2:""}},[e("v-select",{attrs:{label:"Registros por página",items:t.items_page,"item-text":"text","item-value":"value"},model:{value:t.pagination.per_page,callback:function(a){t.$set(t.pagination,"per_page",a)},expression:"pagination.per_page"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:"",sm6:"",md4:""}},[e("v-text-field",{attrs:{"append-icon":"search",label:"Buscar"},on:{input:t.buscar},model:{value:t.search,callback:function(a){t.search=a},expression:"search"}})],1)],1),t._v(" "),e("loading",{model:{value:t.localLoading,callback:function(a){t.localLoading=a},expression:"localLoading"}}),t._v(" "),e("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.atencionesYServicios,loading:t.tableLoading,pagination:t.pagination,"total-items":t.atencionesYServicios.length,search:t.search,expand:t.expand,"hide-actions":""},on:{"update:pagination":function(a){t.pagination=a}},scopedSlots:t._u([{key:"items",fn:function(a){return[e("tr",{on:{click:function(t){a.expanded=!a.expanded}}},[e("td",[t._v(t._s(a.item.codigo))]),t._v(" "),e("td",[t._v(t._s(a.item.Servicio))]),t._v(" "),e("td",[t._v(t._s(a.item.cAut))]),t._v(" "),e("td",[t._v(t._s(a.item.fecha))]),t._v(" "),e("td",[t._v(t._s(a.item.tipoServicio))])])]}}])},[t._v(" "),e("template",{slot:"no-data"},[t.tableLoading?e("v-alert",{attrs:{value:!0,color:"info",icon:"info"}},[t._v("\n          Estamos cargando los registros. "),e("v-icon",[t._v("sentiment_satisfied_alt")])],1):e("v-alert",{attrs:{value:!0,color:"error",icon:"warning"}},[t._v("\n          Lo sentimos, no tenemos registros para mostrar. "),e("v-icon",[t._v("sentiment_very_dissatisfied")])],1)],1),t._v(" "),e("template",{slot:"footer"},[e("td",{staticClass:"text-xs-center",attrs:{colspan:"100%"}},[e("v-pagination",{attrs:{"total-visible":7,length:t.pagination.last_page},on:{input:t.reloadPage},model:{value:t.pagination.current_page,callback:function(a){t.$set(t.pagination,"current_page",a)},expression:"pagination.current_page"}})],1)])],2)],1)],1)},n=[],o={render:i,staticRenderFns:n};a.a=o},947:function(t,a,e){"use strict";var i=e(12),n=e(58);a.a={name:"DetalleGeneralAportes",components:{Loading:n.default},props:{afiliado:{type:Object,default:null}},data:function(){return{atencionesYServicios:[],tableLoading:!1,localLoading:!1,items_page:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],pagination:{per_page:15,current_page:1},headers:[{text:"Código",align:"left",sortable:!1},{text:"Servicio",align:"left",sortable:!1},{text:"Cant. Aut.",align:"left",sortable:!1},{text:"Fecha",align:"left",sortable:!1},{text:"Tipo Servicio",align:"left",sortable:!1}]}},created:function(){this.reloadPage()},watch:{"pagination.per_page":function(){this.pagination.current_page=1,this.reloadPage()}},computed:{pages:function(){return null==this.pagination.rowsPerPage||null==this.pagination.totalItems?0:Math.ceil(this.pagination.totalItems/this.pagination.rowsPerPage)}},methods:{reloadPage:function(){var t=this;this.tableLoading=!0,this.localLoading=!0,this.axios.get("afiliado/"+this.afiliado.id+"/atencionesyservicios").then(function(a){t.atencionesYServicios=a.data.data,t.tableLoading=!1,t.localLoading=!1}).catch(function(a){t.tableLoading=!1,t.localLoading=!1,t.$store.commit(i.b,{msg:"Hay un error al traer los registros. "+a.response,color:"danger"})})},buscar:window.lodash.debounce(function(){this.pagination.current_page=1,this.reloadPage()},500)}}}});
//# sourceMappingURL=351.c318b1b513f5486b0511.js.map