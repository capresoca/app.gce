webpackJsonp([295],{1060:function(e,t,n){"use strict";var r=n(59);t.a={name:"DetalleEntidad",props:["item"],components:{DetalleTercero:function(){return n.e(291).then(n.bind(null,768))},InputDetailFlex:function(){return new Promise(function(e){e()}).then(n.bind(null,250))}},computed:{complementos:function(){return r.b.getters.complementosFormPagadores}}}},1773:function(e,t,n){var r=n(1774);"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);n(456)("32eb42b5",r,!0,{})},1774:function(e,t,n){t=e.exports=n(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DetallePagador.vue",sourceRoot:""}])},1775:function(e,t,n){"use strict";var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-card",[n("v-container",{attrs:{fluid:"","grid-list-xl":""}},[n("v-layout",{key:"seccion1",attrs:{row:"",wrap:""}},[n("input-detail-flex",{attrs:{label:"Tipo aportante",text:e.item.as_tipaportante_id&&e.complementos&&e.complementos.tipaportantes.find(function(t){return t.id===e.item.as_tipaportante_id}).nombre}})],1),e._v(" "),n("v-layout",[n("v-flex",{attrs:{xs12:"",sm12:"",md12:""}},[e.item.tercero?n("detalle-tercero",{attrs:{item:e.item.tercero}}):n("v-subheader",[e._v("Tercero no disponible... "),n("v-icon",[e._v("sentiment_very_dissatisfied")])],1)],1)],1)],1)],1)},o=[],i={render:r,staticRenderFns:o};t.a=i},861:function(e,t,n){"use strict";function r(e){n(1773)}Object.defineProperty(t,"__esModule",{value:!0});var o=n(1060),i=n(1775),a=n(1),s=r,c=a(o.a,i.a,!1,s,"data-v-10878c64",null);t.default=c.exports}});
//# sourceMappingURL=295.4a9e8157850296e3d3f9.js.map