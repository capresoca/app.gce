webpackJsonp([177],{1557:function(t,e,a){var o=a(1558);"string"==typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);a(456)("0369df11",o,!0,{})},1558:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Cxps.vue",sourceRoot:""}])},1559:function(t,e,a){"use strict";var o=function(){var t=this,e=this,a=e.$createElement,o=e._self._c||a;return o("div",[o("v-dialog",{attrs:{persistent:"","max-width":"300"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[o("v-card",[o("v-card-text",{staticClass:"subheading font-weight-light"},[e._v("¿Realmente desea cambiar el estado de esta cuenta?")]),e._v(" "),o("v-card-actions",[o("v-spacer"),e._v(" "),o("v-btn",{attrs:{flat:""},nativeOn:{click:function(t){e.dialog=!1}}},[e._v("No")]),e._v(" "),o("v-btn",{attrs:{color:"primary",flat:""},on:{click:function(t){"Anulado"==e.cxp.estado?e.descAnulado(e.cxp):e.actualizarEstado(e.cxp)}}},[e._v("Si")])],1)],1)],1),e._v(" "),o("v-dialog",{attrs:{persistent:"","max-width":"400"},model:{value:e.dialog2,callback:function(t){e.dialog2=t},expression:"dialog2"}},[o("v-card",[o("v-card-title",{staticClass:"grey lighten-3"},[o("v-card-text",{staticClass:"title"},[e._v("¿Por qué anula esta cuenta?")])],1),e._v(" "),o("v-container",{attrs:{fluid:""}},[o("v-layout",{attrs:{row:"",wrap:""}},[o("v-flex",{attrs:{xs12:""}},[o("v-textarea",{attrs:{outline:"",color:"primary",name:"Detalle de anulación"},model:{value:e.detalle_anulacion,callback:function(t){e.detalle_anulacion=t},expression:"detalle_anulacion"}},[o("div",{attrs:{slot:"label"},slot:"label"},[e._v("\n                Detalle de anulación\n              ")])])],1)],1)],1),e._v(" "),o("v-card-actions",[o("v-spacer"),e._v(" "),o("v-btn",{attrs:{color:"primary",flat:""},on:{click:function(t){return e.actualizarEstado(e.cxp,e.detalle_anulacion)}}},[e._v("Enviar")])],1)],1)],1),e._v(" "),o("v-card",[o("toolbar-list",{attrs:{info:e.infoComponent,btnplus:"",btnplustitle:"Crear CXP",title:"Cuentas Por Pagar",subtitle:"CXP"}}),e._v(" "),o("data-table",{on:{resetOption:function(t){return e.resetOptions(t)},editar:function(a){return e.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:"Registrado"!==a.estado?"Visualizando CXP No. "+e.str_pad(a.consecutivo,8):t.infoComponent.titulo_registro,parametros:{entidad:a,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})},confirmar:function(t){return e.preguntarCambiarEstado(t,"Confirmado")},desconfirmar:function(t){return e.preguntarCambiarEstado(t,"Registrado")},anular:function(t){return e.preguntarCambiarEstado(t,"Anulado")},imprimir:function(t){return e.imprimir(t)}},model:{value:e.dataTableCxp,callback:function(t){e.dataTableCxp=t},expression:"dataTableCxp"}})],1)],1)},n=[],i={render:o,staticRenderFns:n};e.a=i},501:function(t,e,a){"use strict";function o(t){a(1557)}Object.defineProperty(e,"__esModule",{value:!0});var n=a(985),i=a(1559),r=a(1),s=o,l=r(n.a,i.a,!1,s,"data-v-4eda59ba",null);e.default=l.exports},985:function(t,e,a){"use strict";var o=a(7),n=a.n(o),i=a(12),r=a(248);e.a={name:"Cxps",components:{ToolbarList:function(){return new Promise(function(t){t()}).then(a.bind(null,247))},DataTable:function(){return new Promise(function(t){t()}).then(a.bind(null,60))}},data:function(){return{dataTableCxp:{nameItemState:"itemCxp",route:"pg_cxps",optionsPerPage:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],makeHeaders:[{text:"Consecutivo",align:"left",sortable:!1,value:"consecutivo",component:{component:{template:'\n                  <div>\n                    <span v-text="str_pad(value.consecutivo, 8)"></span>\n                  </div>\n                ',props:["value"]}}},{text:"No. Factura",align:"left",sortable:!1,value:"no_factura",component:{component:{template:'\n                  <div>\n                    <v-chip label outline color="red" class="pl-2 pr-2" v-text="value.no_factura"></v-chip>\n                  </div>\n                ',props:["value"]}}},{text:"Proveedor",align:"left",sortable:!1,value:"identificacion_completa",component:{component:{template:"\n                <div>\n                  <span\n                  >\n                  <b v-text=\"value.identificacion_completa + ' - '\"></b>\n                  {{value.nombre_completo}}\n                  </span>\n                </div>\n                ",props:["value"]}}},{text:"Valor",align:"left",sortable:!1,value:"valor_moneda"},{text:"Fecha Causación",align:"left",sortable:!1,value:"fecha_causacion"},{text:"Estado",align:"left",sortable:!1,value:"estado"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}],filters:[{label:"Módulo de enlace CXP",type:"v-autocomplete",items:function(){return[{text:"CXP",value:"CXP"},{text:"Inventarios",value:"Inventarios"},{text:"Saldo Inicial",value:"Saldo Inicial"},{text:"Activos Fijos",value:"Activos Fijos"}]},vModel:[],multiple:!0,itemText:"text",itemValue:"value",value:"estado",clearable:!0}]},cxps:[],tableLoading:!1,transition:"slide-x-transition",direction:"left",fab:!1,show:!1,dialog:!1,dialog2:!1,detalle_anulacion:null}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("cxps")}},methods:{resetOptions:function(t){return t.options=[],this.infoComponent&&this.infoComponent.permisos.agregar&&t.options.push("Registrado"===t.estado?{event:"editar",icon:"edit",tooltip:"Editar"}:{event:"editar",icon:"fas fa-eye",tooltip:"Visualizar",size:"20px"}),this.infoComponent&&this.infoComponent.permisos.ver&&"Registrado"===t.estado&&t.options.push({event:"confirmar",icon:"check",tooltip:"Confirmar"}),this.infoComponent&&this.infoComponent.permisos.ver&&"Confirmado"===t.estado&&"Saldo Inicial"!==t.modulo&&t.options.push({event:"desconfirmar",icon:"remove_circle",tooltip:"Desconfirmar"}),this.infoComponent&&this.infoComponent.permisos.ver&&"Registrado"===t.estado&&t.options.push({event:"anular",icon:"report_off",tooltip:"Anular"}),this.infoComponent&&this.infoComponent.permisos.ver&&t.options.push({event:"imprimir",icon:"far fa-file-pdf",tooltip:"Imprimir"}),t},imprimir:function(t){var e=this;console.log("Abriendo DOM");var a=t.id;this.axios.get("reportecxps?id="+a).then(function(t){var e=t.data;window.open(e,"_blank").focus()}).catch(function(t){e.$store.commit(i.b,{msg:t.response.data.msg,color:"danger"})})},preguntarCambiarEstado:function(t,e){this.dialog=!0,this.cxp=JSON.parse(n()(t)),this.cxp.estado=e},descAnulado:function(t){this.dialog2=!0},actualizarEstado:function(t,e){var a=this;this.dialog=!1,this.dialog2=!1,delete t.show,t.detalle_anulacion=e,this.axios.put("pg_cxps/"+t.id,t).then(function(e){a.$store.commit(i.b,{msg:"Se realizó el cambio de estado a "+t.estado,color:"success"}),a.$store.commit(r.D,{item:e.data.cxp,estado:"editar",key:null})}).catch(function(t){a.dialog=!1,a.$store.commit(i.b,{msg:"Existe un error al actualizar el estado. "+t.response,color:"danger"})})}}}}});
//# sourceMappingURL=177.aa2bdcb2ecf1e4151cd6.js.map