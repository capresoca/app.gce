webpackJsonp([36],{1238:function(t,i,e){"use strict";var a=e(8),o=e.n(a),n=e(6),r=e(2271);i.a={name:"RipsValidados",components:{DialogDetalleRadicacion:r.a,ToolbarList:function(){return new Promise(function(t){t()}).then(e.bind(null,247))}},data:function(){return{dataTable:{nameItemState:"tableRipsRadicados",route:"rs_rips_radicados",advanceFilters:!0,makeHeaders:[{text:"Código",align:"left",sortable:!1,value:"cod_radicacion",component:{template:'\n                    <v-list-tile class="content-v-list-tile-p0">\n                      <v-list-tile-content>\n                        <v-list-tile-title class="body-2">RIP: {{value.cod_radicacion}}</v-list-tile-title>\n                        <v-list-tile-title class="caption grey--text">Interno: {{value.cod_radicacion_ct}}</v-list-tile-title>\n                      </v-list-tile-content>\n                    </v-list-tile>\n                  ',props:["value"]}},{text:"Entidad",align:"left",sortable:!1,component:{template:'\n                    <v-list-tile class="content-v-list-tile-p0">\n                      <v-list-tile-content>\n                        <v-list-tile-title class="body-2" v-if="value.rs_entidad">{{value.rs_entidad.tipo.tipo}}: {{value.rs_entidad.nombre}}</v-list-tile-title>\n                        <v-list-tile-sub-title class="caption grey--text" v-if="value.rs_entidad">Código habilitación: {{value.rs_entidad.cod_habilitacion}}</v-list-tile-sub-title>\n                      </v-list-tile-content>\n                    </v-list-tile>\n                  ',props:["value"]}},{text:"Modalidad",align:"center",sortable:!0,value:"tipo_facturacion",classData:"text-xs-center"},{text:"Estado",align:"left",sortable:!0,value:"estado_radicacion"},{text:"Opciones",align:"center",sortable:!1,actions:!0,singlesActions:!0,classData:"text-xs-center"}]},entidadesFiltro:[],entidadRouteString:null,filterEntidades:function(t,i){var e=function(t){return null!=t?t:""},a=e(t.cod_habilitacion+" "+t.nombre),o=e(i);return a.toString().toLowerCase().indexOf(o.toString().toLowerCase())>-1},dialogEliminar:!1,eliminarRip:{titulo:"",mensaje:""},dialogdescarga:!1}},watch:{entidadRouteString:function(t){this.dataTable.route=t?"rs_rips_radicados?rs_entidad_id="+t:"rs_rips_radicados"}},computed:o()({},Object(n.c)({currentUser:function(t){return t.user.profile}}),{infoComponent:function(){return this.$store.getters.getInfoComponent("rips")},accesoUpdateTipoFact:function(){return this.currentUser&&this.currentUser.roles&&this.currentUser.roles.find(function(t){return 1===t.id||48===t.id})}}),created:function(){this.getEntidadesRIPS()},methods:{detalleRadicacion:function(t){this.$refs.dialogDetalleRadicacion.open(t.id)},getEntidadesRIPS:function(){var t=this;this.axios.get("entidades_filters").then(function(i){console.log("response rips",i),t.entidadesFiltro=i.data.data}).catch(function(i){t.$store.commit("SNACK_ERROR_LIST",{expression:" al traer las entidades con RIPS validados. ",error:i})})},resetOptions:function(t){return t.options=[],t.options.push({event:"descargarRips",color:"cyan",icon:"cloud_download",tooltip:"Descargar Rips"}),"Confirmado"===t.estado_radicacion&&t.options.push({event:"detalleRadicacion",color:"info",icon:"find_in_page",tooltip:"Detalle radicación"}),"Validado"===t.estado_radicacion&&this.accesoUpdateTipoFact&&t.options.push({event:"updateTipoFacturacion",color:"teal",icon:"fas fa-exchange-alt",tooltip:"Cambiar Modalidad"}),t},descargarRips:function(t){var i=this;this.dialogdescarga=!0,this.axios({url:"descargar-rips-validados/"+t.id,method:"GET",responseType:"blob"}).then(function(e){i.dialogdescarga=!1;var a=window.URL.createObjectURL(new Blob([e.data])),o=document.createElement("a");o.href=a,o.setAttribute("download","rips_validados_"+t.cod_radicacion+".zip"),document.body.appendChild(o),o.click()}).catch(function(t){i.dialogdescarga=!1,513===t.response.status?i.$store.commit("SNACK_SHOW",{msg:"El directorio de archivos RIPS fue eliminado del servidor. Contacte con el administrador",color:"error"}):i.$store.commit("SNACK_SHOW",{msg:"Algo salio mal al descargar el archivo "+t.response.message,color:"error"})})},updateTipoFacturacion:function(t){var i=this,e={id:t.id,tipo_facturacion:"Evento"===t.tipo_facturacion?"Capita":"Evento"};this.axios.put("rs_rips_radicados/"+t.id,e).then(function(t){i.$store.commit("SNACK_SHOW",{msg:"Se modificó la modalidad del RIP #"+t.data.cod_radicacion+".",color:"success"}),i.$store.commit("reloadTable","tableRipsRadicados")}).catch(function(t){i.$store.commit("SNACK_ERROR_LIST",{expression:" actualizar el registro. ",error:t})})}}}},1239:function(t,i,e){"use strict";i.a={name:"DialogDetalleRadicacion",props:{btnOpen:{type:Boolean,default:!0}},data:function(){return{dialog:!1,loading:!1,radicado:null}},watch:{dialog:function(t){var i=this;t||setTimeout(function(){i.refresh()},400)}},methods:{open:function(t){var i=this;this.dialog=!0,this.loading=!0,this.axios.get("autorizaciones/medicos",t).then(function(t){i.loading=!1}).catch(function(t){i.loading=!1,i.dialog=!1,i.$store.commit("SNACK_ERROR_LIST",{expression:" al consultar el radicado. ",error:t})})}}}},2269:function(t,i,e){var a=e(2270);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);e(456)("7b789e7b",a,!0,{})},2270:function(t,i,e){i=t.exports=e(455)(!0),i.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"RipsValidados.vue",sourceRoot:""}])},2271:function(t,i,e){"use strict";function a(t){e(2272)}var o=e(1239),n=e(2274),r=e(1),s=a,l=r(o.a,n.a,!1,s,"data-v-398540c4",null);i.a=l.exports},2272:function(t,i,e){var a=e(2273);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);e(456)("49fa5080",a,!0,{})},2273:function(t,i,e){i=t.exports=e(455)(!0),i.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DialogDetalleRadicacion.vue",sourceRoot:""}])},2274:function(t,i,e){"use strict";var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-dialog",{attrs:{persistent:"",width:"720"},scopedSlots:t._u([t.btnOpen?{key:"activator",fn:function(i){var a=i.on;return[e("v-btn",t._g({attrs:{flat:"",icon:"",color:"primary"}},a),[e("v-icon",[t._v("find_in_page")]),t._v("\n      Ver detalle\n    ")],1)]}}:null],null,!0),model:{value:t.dialog,callback:function(i){t.dialog=i},expression:"dialog"}},[t._v(" "),e("v-card",[e("loading",{model:{value:t.loading,callback:function(i){t.loading=i},expression:"loading"}}),t._v(" "),e("v-toolbar",{attrs:{dense:""}},[e("v-avatar",{attrs:{color:"primary",size:"40"}},[e("v-icon",{staticClass:"white--text"},[t._v("find_in_page")])],1),t._v(" "),e("v-toolbar-title",[t._v("Detalle de radicado "),e("strong",[t._v(t._s(t.radicado?t.radicado.cod_radicacion:""))])]),t._v(" "),e("v-spacer"),t._v(" "),e("v-btn",{attrs:{flat:"",icon:""},on:{click:function(i){t.dialog=!1}}},[e("v-icon",[t._v("close")])],1)],1),t._v(" "),e("v-container",{attrs:{fluid:"","grid-list-sm":""}},[e("v-layout",{attrs:{row:"",wrap:""}})],1),t._v(" "),e("v-divider"),t._v(" "),e("v-card-actions",[e("v-spacer"),t._v(" "),e("v-btn",{attrs:{flat:""},on:{click:function(i){t.dialog=!1}}},[t._v("\n        Cerrar\n      ")])],1)],1)],1)},o=[],n={render:a,staticRenderFns:o};i.a=n},2275:function(t,i,e){"use strict";var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-card",[e("toolbar-list",{attrs:{info:t.infoComponent,title:"RIPS Validados"}}),t._v(" "),e("data-table-v2",{on:{resetOption:function(i){return t.resetOptions(i)},descargarRips:function(i){return t.descargarRips(i)},detalleRadicacion:function(i){return t.detalleRadicacion(i)},updateTipoFacturacion:function(i){return t.updateTipoFacturacion(i)}},model:{value:t.dataTable,callback:function(i){t.dataTable=i},expression:"dataTable"}},[e("template",{slot:"filters"},[e("v-flex",{attrs:{xs12:"",sm12:"",md6:"",lg4:""}},[e("v-autocomplete",{attrs:{label:"Entidad","item-value":"id","item-text":"nombre",items:t.entidadesFiltro,"persistent-hint":"",hint:t.entidadRouteString&&t.entidadesFiltro.find(function(i){return i.id===t.entidadRouteString})?"Código: "+t.entidadesFiltro.find(function(i){return i.id===t.entidadRouteString}).cod_habilitacion:"",clearable:"",filter:t.filterEntidades},scopedSlots:t._u([{key:"item",fn:function(i){return[e("div",{staticStyle:{width:"100% !important"}},[e("v-list-tile",{staticClass:"content-v-list-tile-p0"},[e("v-list-tile-content",[e("v-list-tile-title",[t._v(t._s(i.item.nombre))]),t._v(" "),e("v-list-tile-sub-title",{staticClass:"caption"},[t._v("Código: "+t._s(i.item.cod_habilitacion))])],1)],1)],1)]}}]),model:{value:t.entidadRouteString,callback:function(i){t.entidadRouteString=i},expression:"entidadRouteString"}})],1)],1)],2),t._v(" "),e("dialog-detalle-radicacion",{ref:"dialogDetalleRadicacion",attrs:{"btn-open":!1}}),t._v(" "),e("v-dialog",{attrs:{"hide-overlay":"",persistent:"",width:"300"},model:{value:t.dialogdescarga,callback:function(i){t.dialogdescarga=i},expression:"dialogdescarga"}},[e("v-card",{attrs:{color:"primary",dark:""}},[e("v-card-text",[t._v("\n        Por favor espere, estamos descargando los archivos RIPS\n        "),e("v-progress-linear",{staticClass:"mb-0",attrs:{indeterminate:"",color:"white"}})],1)],1)],1)],1)},o=[],n={render:a,staticRenderFns:o};i.a=n},677:function(t,i,e){"use strict";function a(t){e(2269)}Object.defineProperty(i,"__esModule",{value:!0});var o=e(1238),n=e(2275),r=e(1),s=a,l=r(o.a,n.a,!1,s,"data-v-680954de",null);i.default=l.exports}});
//# sourceMappingURL=36.2467f2a59f0808c6f3d8.js.map