webpackJsonp([156],{1203:function(t,e,o){"use strict";e.a={name:"TrasladoPresupuestal",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(o.bind(null,247))},DataTable:function(){return new Promise(function(t){t()}).then(o.bind(null,60))}},data:function(){var t=this;return{dataTableTraslado:{nameItemState:"itemTraladosPresupuestales",route:"pr_traslados",optionsPerPage:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],makeHeaders:[{text:"Periodo",align:"left",sortable:!1,value:"periodo"},{text:"Consecutivo",align:"left",sortable:!1,value:"consecutivo_presupuestal"},{text:"N° resolución",align:"left",sortable:!1,value:"resolucion",component:{component:{template:"\n                      <div>\n                        <span class=\"text-xs-right\">\n                          {{ (value.tipo === 'Gasto') ? value.presupuesto_inicial_gasto.entidad_resolucion.resolucion : value.presupuesto_inicial_ingreso.entidad_resolucion.resolucion }}\n                        </span>\n                      </div>\n                    ",props:["value"]}}},{text:"Tipo",align:"left",sortable:!1,value:"tipo"},{text:"Estado",align:"center",sortable:!1,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0",value:"id"}],filters:[{label:"Tipos",type:"v-autocomplete",items:function(){return t.tipos},vModel:[],multiple:!0,itemText:"text",itemValue:"value",value:"tipo",clearable:!0}]}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("trasladoPresupuestal")},tipos:function(){return[{text:"Ingresos",value:"Ingreso"},{text:"Gastos",value:"Gasto"}]}},methods:{resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&t.options.push("Registrado"!==t.estado?{event:"editar",icon:"remove_red_eye",tooltip:"Visualizar"}:{event:"editar",icon:"edit",tooltip:"Editar"}),t}}}},2149:function(t,e,o){var a=o(2150);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);o(456)("88bcdb14",a,!0,{})},2150:function(t,e,o){e=t.exports=o(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"TrasladoPresupuestal.vue",sourceRoot:""}])},2151:function(t,e,o){"use strict";var a=function(){var t=this,e=this,o=e.$createElement,a=e._self._c||o;return a("div",[a("v-card",[a("toolbar-list",{attrs:{info:e.infoComponent,btnplus:"",btnplustitle:"Crear Traslado",title:"Traslados Presupuestales",subtitle:"Ingresos y gastos"}}),e._v(" "),a("data-table",{on:{resetOption:function(t){return e.resetOptions(t)},editar:function(o){return e.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:t.infoComponent.titulo_registro,parametros:{entidad:o,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},model:{value:e.dataTableTraslado,callback:function(t){e.dataTableTraslado=t},expression:"dataTableTraslado"}})],1)],1)},n=[],s={render:a,staticRenderFns:n};e.a=s},657:function(t,e,o){"use strict";function a(t){o(2149)}Object.defineProperty(e,"__esModule",{value:!0});var n=o(1203),s=o(2151),r=o(1),i=a,l=r(n.a,s.a,!1,i,"data-v-fba12914",null);e.default=l.exports}});
//# sourceMappingURL=156.d27a1c980d82254c3fd6.js.map