webpackJsonp([203],{1101:function(t,e,n){"use strict";e.a={name:"Interventores",components:{DataTable:function(){return new Promise(function(t){t()}).then(n.bind(null,60))},ToolbarList:function(){return new Promise(function(t){t()}).then(n.bind(null,247))}},data:function(){return{dataTable:{nameItemState:"itemInterventor",route:"ce_interventores",makeHeaders:[{text:"Codigo",align:"left",sortable:!1,value:"codigo"},{text:"Nombre",align:"left",sortable:!1,value:"nombre"},{text:"Tercero",align:"left",sortable:!1,value:"tercero.razon_social"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}]}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("interventores")}},methods:{resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&t.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),t}}}},1909:function(t,e,n){var o=n(1910);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);n(456)("24106e94",o,!0,{})},1910:function(t,e,n){e=t.exports=n(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Interventores.vue",sourceRoot:""}])},1911:function(t,e,n){"use strict";var o=function(){var t=this,e=this,n=e.$createElement,o=e._self._c||n;return o("v-card",[o("toolbar-list",{attrs:{info:e.infoComponent,btnplus:"",btnplustitle:"Crear interventores",title:"Interventores",subtitle:"Gestion de interventores"}}),e._v(" "),o("data-table",{on:{resetOption:function(t){return e.resetOptions(t)},editar:function(n){return e.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:"Editar interventor",parametros:{entidad:n,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},model:{value:e.dataTable,callback:function(t){e.dataTable=t},expression:"dataTable"}})],1)},r=[],a={render:o,staticRenderFns:r};e.a=a},577:function(t,e,n){"use strict";function o(t){n(1909)}Object.defineProperty(e,"__esModule",{value:!0});var r=n(1101),a=n(1911),i=n(1),s=o,l=i(r.a,a.a,!1,s,"data-v-ea5c32f0",null);e.default=l.exports}});
//# sourceMappingURL=203.13cde7d4b07a6f384dd1.js.map