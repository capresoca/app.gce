webpackJsonp([42],{1381:function(t,e,i){var a=i(1382);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);i(456)("67f78b48",a,!0,{})},1382:function(t,e,i){e=t.exports=i(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Afiliados.vue",sourceRoot:""}])},1383:function(t,e,i){"use strict";function a(t){i(1384)}var l=i(932),n=i(1386),o=i(1),s=a,r=o(l.a,n.a,!1,s,"data-v-7321ff96",null);e.a=r.exports},1384:function(t,e,i){var a=i(1385);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);i(456)("946c4070",a,!0,{})},1385:function(t,e,i){e=t.exports=i(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"AllAfiliados.vue",sourceRoot:""}])},1386:function(t,e,i){"use strict";var a=function(){var t=this,e=this,i=e.$createElement,a=e._self._c||i;return a("v-card",{attrs:{flat:""}},[a("data-table-v2",{ref:"tableItemAffiliate",attrs:{"con-buscador":!1,"con-registro":!1,"con-columnas":!1},on:{resetOption:function(t){return e.resetOptions(t)},editar:function(i){return e.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:t.infoComponent.titulo_registro,parametros:{entidad:i,tabOrigin:e.$store.state.nav.currentItem.split("tab-")[1]}})},detalle:function(t){return e.$store.commit("NAV_ADD_ITEM",{ruta:"detalleGeneralAfiliado",titulo:"Detalle afiliado",parametros:{entidad:t,tabOrigin:e.$store.state.nav.currentItem.split("tab-")[1]}})},"apply-filters":e.aplicaFiltros},model:{value:e.affiliateDataTable,callback:function(t){e.affiliateDataTable=t},expression:"affiliateDataTable"}},[a("template",{slot:"filters"},[a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-select",{attrs:{label:"Tipo documento","item-text":"abreviatura","item-value":"id",items:e.complementos.tipdocidentidades,chips:"","deletable-chips":"","small-chips":"",clearable:""},model:{value:e.filtroRuta.tipo_id,callback:function(t){e.$set(e.filtroRuta,"tipo_id",t)},expression:"filtroRuta.tipo_id"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{attrs:{label:"Identificación",clearable:""},model:{value:e.filtroRuta.identificacion,callback:function(t){e.$set(e.filtroRuta,"identificacion",t)},expression:"filtroRuta.identificacion"}})],1),e._v(" "),"v-range"===e.filters.type&&"number"===e.filters.items.type?a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[a("v-layout",{attrs:{"align-center":""}},[a("v-text-field",{attrs:{type:"number",label:e.filters.items.itemMin.label,clearable:""},model:{value:e.filters.items.itemMin.vModel,callback:function(t){e.$set(e.filters.items.itemMin,"vModel",e._n(t))},expression:"filters.items.itemMin.vModel"}}),e._v(" "),a("v-text-field",{attrs:{type:"number",label:e.filters.items.itemMax.label,clearable:""},model:{value:e.filters.items.itemMax.vModel,callback:function(t){e.$set(e.filters.items.itemMax,"vModel",e._n(t))},expression:"filters.items.itemMax.vModel"}})],1)],1):e._e(),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.nombre1",expression:"'filtroRuta.nombre1'"}],attrs:{label:"Primer Nombre",clearable:""},model:{value:e.filtroRuta.nombre1,callback:function(t){e.$set(e.filtroRuta,"nombre1",t)},expression:"filtroRuta.nombre1"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.nombre2",expression:"'filtroRuta.nombre2'"}],attrs:{label:"Segundo Nombre",clearable:""},model:{value:e.filtroRuta.nombre2,callback:function(t){e.$set(e.filtroRuta,"nombre2",t)},expression:"filtroRuta.nombre2"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.apellido1",expression:"'filtroRuta.apellido1'"}],attrs:{label:"Primer Apellido",clearable:""},model:{value:e.filtroRuta.apellido1,callback:function(t){e.$set(e.filtroRuta,"apellido1",t)},expression:"filtroRuta.apellido1"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.apellido2",expression:"'filtroRuta.apellido2'"}],attrs:{label:"Segundo Apellido",clearable:""},model:{value:e.filtroRuta.apellido2,callback:function(t){e.$set(e.filtroRuta,"apellido2",t)},expression:"filtroRuta.apellido2"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[a("v-autocomplete",{attrs:{label:"Municipio Afiliado",items:e.complementos.municipios,"item-value":"id","item-text":"nombre",filter:e.filterMunicipios,clearable:""},scopedSlots:e._u([{key:"item",fn:function(t){return[[a("v-list-tile-content",[a("v-list-tile-title",{domProps:{innerHTML:e._s(t.item.nombre)}}),e._v(" "),a("v-list-tile-sub-title",{domProps:{innerHTML:e._s(t.item.nombre_departamento)}})],1)]]}}]),model:{value:e.filtroRuta.gn_municipio_id,callback:function(t){e.$set(e.filtroRuta,"gn_municipio_id",t)},expression:"filtroRuta.gn_municipio_id"}})],1),e._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{attrs:{label:"Ficha sisbén",clearable:""},model:{value:e.filtroRuta.ficha,callback:function(t){e.$set(e.filtroRuta,"ficha",t)},expression:"filtroRuta.ficha"}})],1)],1)],2)],1)},l=[],n={render:a,staticRenderFns:l};e.a=n},1399:function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-card",[i("toolbar-list",{attrs:{info:t.infoComponent,title:"Afiliados",subtitle:"gestión"}}),t._v(" "),i("v-tabs",{attrs:{grow:"","fixed-tabs":"",color:"cyan",dark:""}},[i("v-tabs-slider",{attrs:{color:"accent"}}),t._v(" "),i("v-tab",{attrs:{href:"#tab-1"}},[i("v-icon",{attrs:{left:""}},[t._v("fas fa-clipboard-list")]),t._v("\n        Incidencias\n      ")],1),t._v(" "),i("v-tab",{attrs:{href:"#tab-2"}},[i("v-icon",{attrs:{left:""}},[t._v("fas fa-users")]),t._v("\n        Afiliados\n      ")],1),t._v(" "),i("v-tab-item",{attrs:{value:"tab-1"}},[i("v-card",{attrs:{flat:""}},[i("incidencias")],1)],1),t._v(" "),i("v-tab-item",{attrs:{value:"tab-2"}},[i("all-afiliados")],1)],1)],1)},l=[],n={render:a,staticRenderFns:l};e.a=n},478:function(t,e,i){"use strict";function a(t){i(1381)}Object.defineProperty(e,"__esModule",{value:!0});var l=i(931),n=i(1399),o=i(1),s=a,r=o(l.a,n.a,!1,s,"data-v-465517f0",null);e.default=r.exports},931:function(t,e,i){"use strict";var a=i(60),l=i(1383);e.a={name:"Afiliados",components:{AllAfiliados:l.a,ToolbarList:function(){return new Promise(function(t){t()}).then(i.bind(null,247))},Incidencias:function(){return i.e(341).then(i.bind(null,1387))},DataTable:a.default},data:function(){return{dataTable:{nameItemState:"itemAfiliado",route:"afiliados?estado_afiliado:codigo=!PR",makeHeaders:[{text:"Afiliado",align:"left",sortable:!1,value:"identificacion",component:{component:{template:'<mini-card-detail :data="value.mini_afiliado"/>',props:["value"]}}},{text:"Ficha SISBEN",align:"left",sortable:!1,value:"ficha_sisben"},{text:"Puntaje SISBEN",align:"center",sortable:!0,value:"puntaje_sisben",classData:"text-xs-center"},{text:"Régimen",align:"left",sortable:!0,value:"regimen"},{text:"Estado",align:"center",sortable:!0,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}],filters:[{label:"Puntaje sisben",type:"v-range",items:{type:"number",range:!0,itemMin:{label:"Mínimo",vModel:null,clearable:!0,value:"puntaje_sisben"},itemMax:{label:"Maximo",vModel:null,clearable:!0,value:"puntaje_sisben"}}}]}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("afiliados")}},methods:{resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&t.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),this.infoComponent&&this.infoComponent.permisos.ver&&t.options.push({event:"detalle",icon:"find_in_page",tooltip:"Ver detalle"}),t}}}},932:function(t,e,i){"use strict";var a=i(59);e.a={name:"AllAfiliados",data:function(){return{filterMunicipios:function(t,e){var i=function(t){return null!=t?t:""},a=i(t.nombre+" "+t.nombre_departamento),l=i(e);return a.toString().toLowerCase().indexOf(l.toString().toLowerCase())>-1},affiliateDataTable:{nameItemState:"tableItemAffiliate",route:null,advanceFilters:!0,makeHeaders:[{text:"Afiliado",align:"left",sortable:!1,value:"identificacion",component:{template:'<mini-card-detail :data="value.mini_afiliado"/>',props:["value"]}},{text:"Municipio",align:"left",sortable:!1,value:"ficha_sisben",component:{template:'\n              <v-list>\n                <v-list-tile-content>\n                  <v-list-tile-title v-html="value.ubicacion"/>\n                </v-list-tile-content>\n               </v-list>',props:["value"]}},{text:"Ficha SISBEN",align:"left",sortable:!0,value:"puntaje_sisben",classData:"text-xs-center"},{text:"Puntaje SISBEN",align:"center",sortable:!0,value:"puntaje_sisben",classData:"text-xs-center"},{text:"Régimen",align:"left",sortable:!0,value:"regimen"},{text:"Estado",align:"center",sortable:!0,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,singlesActions:!0,classData:"justify-center layout px-0"}]},rutaBase:"",filtroRuta:{tipo_id:null,identificacion:null,nombre1:null,nombre2:null,apellido1:null,apellido2:null,gn_municipio_id:null,ficha:null},filters:{label:"Puntaje sisben",type:"v-range",items:{type:"number",range:!0,itemMin:{label:"Puntaje Mínimo Sisben",vModel:null,clearable:!0,value:"puntaje_sisben"},itemMax:{label:"Puntaje Máximo Sisben",vModel:null,clearable:!0,value:"puntaje_sisben"}}}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("afiliados")},complementos:function(){var t=this.clone(a.b.getters.complementosFormAfiliados);return t.tipdocidentidades&&(t.tipdocidentidades=t.tipdocidentidades.filter(function(t){return 12!==t.id})),t}},created:function(){this.affiliateDataTable.advanceFilters=!0,this.affiliateDataTable.route="afiliados?estado_afiliado:codigo=!PR",this.rutaBase=this.clone(this.affiliateDataTable.route)},methods:{resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&t.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),this.infoComponent&&this.infoComponent.permisos.ver&&t.options.push({event:"detalle",icon:"find_in_page",tooltip:"Ver detalle",color:"teal"}),t},aplicaFiltros:function(){var t=this.rutaBase;this.filtroRuta.tipo_id&&(t=t+(t.indexOf("?")>-1?"&":"?")+"tipo_id="+this.filtroRuta.tipo_id),this.filtroRuta.identificacion&&(t=t+(t.indexOf("?")>-1?"&":"?")+"identificacion="+this.filtroRuta.identificacion),this.filters.items.itemMin.vModel&&(t=t+(t.indexOf("?")>-1?"&":"?")+"rangMin="+this.filters.items.itemMin.vModel),this.filters.items.itemMax.vModel&&(t=t+(t.indexOf("?")>-1?"&":"?")+"rangMax="+this.filters.items.itemMax.vModel),this.filtroRuta.nombre1&&(t=t+(t.indexOf("?")>-1?"&":"?")+"nombre1="+this.filtroRuta.nombre1),this.filtroRuta.nombre2&&(t=t+(t.indexOf("?")>-1?"&":"?")+"nombre2="+this.filtroRuta.nombre2),this.filtroRuta.apellido1&&(t=t+(t.indexOf("?")>-1?"&":"?")+"apellido1="+this.filtroRuta.apellido1),this.filtroRuta.apellido2&&(t=t+(t.indexOf("?")>-1?"&":"?")+"apellido2="+this.filtroRuta.apellido2),this.filtroRuta.gn_municipio_id&&(t=t+(t.indexOf("?")>-1?"&":"?")+"gn_municipio_id="+this.filtroRuta.gn_municipio_id),this.filtroRuta.ficha&&(t=t+(t.indexOf("?")>-1?"&":"?")+"ficha="+this.filtroRuta.ficha),this.affiliateDataTable.route=t}}}}});
//# sourceMappingURL=42.92c9171cc12a59b36154.js.map