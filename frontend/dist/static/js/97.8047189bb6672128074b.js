webpackJsonp([97],{1219:function(s,t,a){"use strict";t.a={name:"DetalleAsignacionMasiva",props:["parametros"],components:{InputDetailFlex:function(){return new Promise(function(s){s()}).then(a.bind(null,250))}},data:function(){return{proceso:null,loading:!1,headersAfiliados:[{text:"Afiliado",align:"left",sortable:!1},{text:"Fecha afiliación",align:"left",sortable:!1}]}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("asignacionmasiva")}},watch:{},created:function(){this.getData(this.parametros.entidad.id)},methods:{getData:function(s){var t=this;this.loading=!0,this.axios.get("redservicios/asignamasivos/"+s).then(function(s){t.proceso=s.data.data,t.loading=!1}).catch(function(s){t.loading=!1,t.$store.commit("SNACK_ERROR_LIST",{expression:" al traer los datos del proceso de asignación masiva. ",error:s})})}}}},2200:function(s,t,a){var i=a(2201);"string"==typeof i&&(i=[[s.i,i,""]]),i.locals&&(s.exports=i.locals);a(456)("5262c584",i,!0,{})},2201:function(s,t,a){t=s.exports=a(455)(!0),t.push([s.i,"","",{version:3,sources:[],names:[],mappings:"",file:"DetalleAsignacionMasiva.vue",sourceRoot:""}])},2202:function(s,t,a){"use strict";var i=function(){var s=this,t=s.$createElement,a=s._self._c||t;return a("v-card",[a("v-toolbar",{attrs:{dense:""}},[a("v-list-tile",{staticClass:"content-v-list-tile-p0"},[a("v-list-tile-avatar",{attrs:{color:"primary"}},[a("v-icon",{staticClass:"white--text"},[s._v(s._s(s.infoComponent?s.infoComponent.icono:""))])],1),s._v(" "),a("v-list-tile-content",[a("v-list-tile-title",{staticClass:"font-weight-bold title"},[s._v("Proceso de asignación masiva")])],1)],1),s._v(" "),s.proceso?[a("v-spacer"),s._v(" "),a("v-chip",{staticClass:"text-xs-right",attrs:{label:"",color:"white","text-color":"red",dark:"",absolute:"",right:"",top:""}},[a("span",{staticClass:"subheading"},[s._v("N°")]),s._v(" \n        "),a("span",{staticClass:"subheading"},[s._v(s._s(s.proceso.id))])])]:s._e()],2),s._v(" "),a("loading",{model:{value:s.loading,callback:function(t){s.loading=t},expression:"loading"}}),s._v(" "),s.proceso?a("v-container",{attrs:{fluid:"","grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("input-detail-flex",{attrs:{"flex-class":"xs12 sm6 md4 lg3",label:"Tipo de proceso",text:s.proceso.tipo_proceso}}),s._v(" "),a("input-detail-flex",{attrs:{"flex-class":"xs12 sm6 md4 lg3",label:"Fecha registro",text:s.moment(s.proceso.created_at.date).format("YYYY-MM-DD HH-mm"),"prepend-icon":"event"}}),s._v(" "),a("input-detail-flex",{attrs:{"flex-class":"xs12 sm6 md4 lg3",label:"Usuario",text:s.proceso.usuario.name,hint:"<i class='fas fa-phone-alt'></i> "+s.proceso.usuario.phone,"prepend-icon":"fas fa-user"}})],1),s._v(" "),a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm5:"",md5:"",lg4:""}},[a("v-card",[a("v-subheader",{staticClass:"pa-1"},[a("v-icon",{staticClass:"mr-1",attrs:{color:"indigo"}},[s._v("widgets")]),s._v(" Servicios asignados\n          ")],1),s._v(" "),a("v-card-text",{staticClass:"pa-1"},[a("v-layout",{attrs:{row:"",wrap:""}},["Trasladar servicios"===s.proceso.tipo_proceso?[a("v-flex",{attrs:{xs12:""}},[a("v-card",{staticClass:"white--text px-2 py-1",attrs:{color:"purple darken-2"}},[a("div",{staticClass:"body-1"},[s._v(s._s(s.proceso.servicio_anterior.servicio.codigo)+" - "+s._s(s.proceso.servicio_anterior.servicio.nombre))])]),s._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-card",{staticClass:"white--text px-2 py-1",attrs:{color:"cyan darken-2"}},[a("div",{staticClass:"body-1"},[s._v("I.P.S. DE ORIGEN")]),s._v(" "),a("v-divider"),s._v(" "),a("div",{staticClass:"caption font-weight-light"},[s._v(s._s(s.proceso.servicio_anterior.ips.nombre))]),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-marker-alt")]),s._v(" "+s._s(s.proceso.servicio_anterior.ips.municipio.nombre)+" - "+s._s(s.proceso.servicio_anterior.ips.municipio.departamento.nombre)+" "),a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-signs")]),s._v(" "+s._s(s.proceso.servicio_anterior.ips.direccion_sede))],1),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-phone-alt")]),s._v(" "+s._s(s.proceso.servicio_anterior.ips.telefono_sede))],1)],1)],1),s._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-card",{staticClass:"white--text px-2 py-1",attrs:{color:"cyan darken-2"}},[a("div",{staticClass:"body-1"},[s._v("I.P.S. DE DESTINO")]),s._v(" "),a("v-divider"),s._v(" "),a("div",{staticClass:"caption font-weight-light"},[s._v(s._s(s.proceso.servicios_asignados[0].ips.nombre))]),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-marker-alt")]),s._v(" "+s._s(s.proceso.servicios_asignados[0].ips.municipio.nombre)+" - "+s._s(s.proceso.servicios_asignados[0].ips.municipio.departamento.nombre)+" "),a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-signs")]),s._v(" "+s._s(s.proceso.servicios_asignados[0].ips.direccion_sede))],1),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-phone-alt")]),s._v(" "+s._s(s.proceso.servicios_asignados[0].ips.telefono_sede))],1)],1)],1)],1)]:s._l(s.proceso.servicios_asignados,function(t,i){return[a("v-flex",{key:"card"+i,attrs:{xs12:""}},[a("v-card",{staticClass:"white--text px-2 py-1",attrs:{color:"cyan darken-2"}},[a("div",{staticClass:"body-1"},[s._v(s._s(t.servicio.codigo)+" - "+s._s(t.servicio.nombre))]),s._v(" "),a("v-divider"),s._v(" "),a("div",{staticClass:"caption font-weight-light"},[s._v(s._s(t.ips.nombre))]),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-marker-alt")]),s._v(" "+s._s(t.ips.municipio.nombre)+" - "+s._s(t.ips.municipio.departamento.nombre)+" "),a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-map-signs")]),s._v(" "+s._s(t.ips.direccion_sede))],1),s._v(" "),a("div",{staticClass:"caption"},[a("v-icon",{attrs:{size:"small"}},[s._v("fas fa-phone-alt")]),s._v(" "+s._s(t.ips.telefono_sede))],1)],1)],1)]})],2)],1)],1)],1),s._v(" "),a("v-flex",{attrs:{xs12:"",sm7:"",md7:"",lg8:""}},[a("v-card",[a("v-subheader",{staticClass:"pa-1"},[a("v-icon",{staticClass:"mr-1",attrs:{color:"indigo"}},[s._v("fas fa-users")]),s._v(" Afiliados\n          ")],1),s._v(" "),a("v-card-text",{staticClass:"pa-1"},[a("v-data-table",{attrs:{headers:s.headersAfiliados,items:s.proceso.afiliados,"rows-per-page-text":"Registros por página","rows-per-page-items":[20,50,100,500,1e3,2e3,{text:"Todos",value:-1}]},scopedSlots:s._u([{key:"items",fn:function(t){return[a("td",{staticClass:"text-xs-left"},[a("mini-card-detail",{attrs:{data:t.item.mini_afiliado}})],1),s._v(" "),a("td",[s._v("\n                  "+s._s(t.item.fecha_afiliacion?s.moment(t.item.fecha_afiliacion).format("YYYY-MM-DD"):"")+"\n                ")])]}},{key:"pageText",fn:function(t){return[s._v("\n                "+s._s(t.pageStart)+" - "+s._s(t.pageStop)+" de "+s._s(t.itemsLength)+"\n              ")]}}],null,!1,1175082440)},[s._v("\n              disable-initial-sort\n              >\n              ")])],1)],1)],1)],1)],1):s._e()],1)},e=[],o={render:i,staticRenderFns:e};t.a=o},670:function(s,t,a){"use strict";function i(s){a(2200)}Object.defineProperty(t,"__esModule",{value:!0});var e=a(1219),o=a(2202),r=a(1),n=i,c=r(e.a,o.a,!1,n,"data-v-1e609648",null);t.default=c.exports}});
//# sourceMappingURL=97.8047189bb6672128074b.js.map