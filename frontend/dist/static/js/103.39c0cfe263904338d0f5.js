webpackJsonp([103],{1087:function(t,e,i){"use strict";var o=i(60),n=i(12);e.a={name:"SolicitudesAutorizacion",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(i.bind(null,247))},DataTable:o.default},data:function(){return{dataTable:{nameItemState:"itemAuSolicitudAutorizacion",route:"autsolicitudes",makeHeaders:[{text:"Identificación afiliado",align:"left",sortable:!1,value:"identificacion"},{text:"Nombre afiliado",align:"left",sortable:!0,value:"nombre_completo"},{text:"Fecha solicitud",align:"left",sortable:!1,value:"fecha",component:{component:{template:"<span>{{moment(value.fecha).format('DD/MM/YYYY  HH:mm')}}</span>",props:["value"]}}},{text:"Estado",align:"center",sortable:!0,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,singlesActions:!0,classData:"text-xs-center"}]}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("solicitudesAutorizacion")}},methods:{imprimir:function(t){var e=this;this.axios.get("firmar-ruta?nombre_ruta=autsolicitude&id="+t.id).then(function(t){""!==t.data&&window.open(t.data,"_blank")}).catch(function(t){e.$store.commit(n.a,{expression:" al generar el documento. ",error:t})})},resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&"Confirmada"===t.estado&&t.options.push({event:"estudiar",icon:"ballot",tooltip:"Estudiar"}),this.infoComponent&&this.infoComponent.permisos.imprimir&&"Registrada"!==t.estado&&t.options.push({event:"imprimir",icon:"far fa-file-pdf",color:"primary",tooltip:"Imprimir"}),t.options.push({event:"detalle",color:"success",icon:"find_in_page",tooltip:"Detalle"}),t}}}},1873:function(t,e,i){var o=i(1874);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);i(456)("72672b34",o,!0,{})},1874:function(t,e,i){e=t.exports=i(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"SolicitudesAutorizacion.vue",sourceRoot:""}])},1875:function(t,e,i){"use strict";var o=function(){var t=this,e=this,i=e.$createElement,o=e._self._c||i;return o("v-card",[o("toolbar-list",{attrs:{info:e.infoComponent,title:"Solicitudes de Autorización",subtitle:"Gestión"}}),e._v(" "),o("data-table",{on:{resetOption:function(t){return e.resetOptions(t)},imprimir:function(t){return e.imprimir(t)},detalle:function(t){return e.$store.commit("NAV_ADD_ITEM",{ruta:"detalleSolicitudAutorizacion",titulo:"Detalle Solicitud Autorización",parametros:{entidad:t,tabOrigin:e.$store.state.nav.currentItem.split("tab-")[1]}})},estudiar:function(i){return e.$store.commit("NAV_ADD_ITEM",{ruta:"estudioSolicitudAutorizacion",titulo:"Estudio Solicitud Autotización",parametros:{entidad:i,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},model:{value:e.dataTable,callback:function(t){e.dataTable=t},expression:"dataTable"}})],1)},n=[],a={render:o,staticRenderFns:n};e.a=a},563:function(t,e,i){"use strict";function o(t){i(1873)}Object.defineProperty(e,"__esModule",{value:!0});var n=i(1087),a=i(1875),r=i(1),s=o,u=r(n.a,a.a,!1,s,"data-v-176345fa",null);e.default=u.exports}});
//# sourceMappingURL=103.39c0cfe263904338d0f5.js.map