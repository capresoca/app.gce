webpackJsonp([53,289],{1063:function(e,t,i){"use strict";var a=i(59),l=i(60),o=i(12),n=i(749);t.a={name:"Traslados",components:{ToolbarList:function(){return new Promise(function(e){e()}).then(i.bind(null,247))},Confirmar:n.default,DataTable:l.default},data:function(){var e=this;return{filterMunicipios:function(e,t){var i=function(e){return null!=e?e:""},a=i(e.nombre+" "+e.nombre_departamento),l=i(t);return a.toString().toLowerCase().indexOf(l.toString().toLowerCase())>-1},dialogA:{visible:!1,content:null,registroID:null},traslateAffiliatumItems:{nameItemState:"tableItemTrasladosAfiliados",route:null,advanceFilters:!0,optionsPerPage:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],makeHeaders:[{text:"Afiliado",align:"left",sortable:!1,value:"identificacion",classData:"",component:{template:'<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>',props:["value"]}},{text:"Municipio Afiliado",align:"left",sortable:!1,value:"ficha_sisben",component:{template:'\n              <v-list>\n                <v-list-tile-content>\n                  <v-list-tile-title v-html="value.ubicacion"/>\n                </v-list-tile-content>\n               </v-list>',props:["value"]}},{text:"E.P.S.",align:"left",sortable:!1,value:"nombreEps"},{text:"Fecha",align:"left",sortable:!0,value:"fecha_traslado"},{text:"Estado",align:"center",sortable:!0,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}],filters:[{label:"Estado",type:"v-autocomplete",items:function(){return e.complementos.estadosTrasmovs},vModel:[],multiple:!0,itemText:"text",itemValue:"value",value:"estado",clearable:!0},{label:"Fecha",type:"v-range",items:{menuDate:!1,type:"date",range:!0,itemMin:{label:"Inicial",vModel:null,clearable:!0,value:"fecha_traslado"},itemMax:{menuDate:!1,label:"Final",vModel:null,clearable:!0,value:"fecha_traslado"}}}]},dataTable:{nameItemState:"itemTrastramite",route:"formtrasmov",optionsPerPage:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],makeHeaders:[{text:"Afiliado",align:"left",sortable:!1,value:"identificacion",classData:"",component:{component:{template:'<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>',props:["value"]}}},{text:"E.P.S.",align:"left",sortable:!1,value:"nombreEps"},{text:"Fecha",align:"left",sortable:!0,value:"fecha_traslado"},{text:"Estado",align:"center",sortable:!0,value:"estado",classData:"text-xs-center"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}],filters:[{label:"Estado",type:"v-autocomplete",items:function(){return e.complementos.estadosTrasmovs},vModel:[],multiple:!0,itemText:"text",itemValue:"value",value:"estado",clearable:!0},{label:"Fecha",type:"v-range",items:{menuDate:!1,type:"date",range:!0,itemMin:{label:"Inicial",vModel:null,clearable:!0,value:"fecha_traslado"},itemMax:{menuDate:!1,label:"Final",vModel:null,clearable:!0,value:"fecha_traslado"}}}]},rutaBase:"",filtroRuta:{tipo_id:null,identificacion:null,nombre1:null,nombre2:null,apellido1:null,apellido2:null,gn_municipio_id:null,ficha:null},filters:[{label:"Estado",type:"v-autocomplete",items:function(){return e.complementos.estadosTrasmovs},vModel:[],multiple:!0,itemText:"text",itemValue:"value",value:"estado",clearable:!0},{label:"Fecha",type:"v-range",items:{menuDate:!1,type:"date",range:!0,itemMin:{label:"Fecha Inicial",vModel:null,clearable:!0,value:"fecha_traslado"},itemMax:{menuDate:!1,label:"Fecha Final",vModel:null,clearable:!0,value:"fecha_traslado"}}}]}},created:function(){this.traslateAffiliatumItems.route="formtrasmov",this.rutaBase=this.clone(this.traslateAffiliatumItems.route)},computed:{complementosID:function(){var e=this.clone(a.b.getters.complementosFormAfiliados);return e.tipdocidentidades&&(e.tipdocidentidades=e.tipdocidentidades.filter(function(e){return 12!==e.id})),e},complementos:function(){return a.b.getters.complementosTablaTraslados},infoComponent:function(){return this.$store.getters.getInfoComponent("tramitesTraslado")}},methods:{resetOptions:function(e){return e.options=[],this.infoComponent&&this.infoComponent.permisos.imprimir&&e.options.push({event:"imprimir",icon:"far fa-file-pdf",tooltip:"Imprimir"}),this.infoComponent&&this.infoComponent.permisos.agregar&&"Registrado"===e.estado&&e.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),this.infoComponent&&this.infoComponent.permisos.anular&&("Registrado"===e.estado||"Radicado"===e.estado)&&e.options.push({event:"anular",icon:"delete",tooltip:"Anular"}),e},aplicaFiltros:function(){var e=this.rutaBase;this.filtroRuta.tipo_id&&(e=e+(e.indexOf("?")>-1?"&":"?")+"tipo_id="+this.filtroRuta.tipo_id),this.filtroRuta.identificacion&&(e=e+(e.indexOf("?")>-1?"&":"?")+"identificacion="+this.filtroRuta.identificacion),this.filters[1].items.itemMin.vModel&&(e=e+(e.indexOf("?")>-1?"&":"?")+"fechaMin="+this.filters[1].items.itemMin.vModel),this.filters[1].items.itemMax.vModel&&(e=e+(e.indexOf("?")>-1?"&":"?")+"fechaMax="+this.filters[1].items.itemMax.vModel),this.filters[0].vModel.length&&(e=e+(e.indexOf("?")>-1?"&":"?")+"estados="+this.filters[0].vModel),this.filtroRuta.nombre1&&(e=e+(e.indexOf("?")>-1?"&":"?")+"nombre1="+this.filtroRuta.nombre1),this.filtroRuta.nombre2&&(e=e+(e.indexOf("?")>-1?"&":"?")+"nombre2="+this.filtroRuta.nombre2),this.filtroRuta.apellido1&&(e=e+(e.indexOf("?")>-1?"&":"?")+"apellido1="+this.filtroRuta.apellido1),this.filtroRuta.apellido2&&(e=e+(e.indexOf("?")>-1?"&":"?")+"apellido2="+this.filtroRuta.apellido2),this.filtroRuta.gn_municipio_id&&(e=e+(e.indexOf("?")>-1?"&":"?")+"gn_municipio_id="+this.filtroRuta.gn_municipio_id),this.filtroRuta.ficha&&(e=e+(e.indexOf("?")>-1?"&":"?")+"ficha="+this.filtroRuta.ficha),this.traslateAffiliatumItems.route=e},registroAnular:function(e){this.dialogA.content="¿Está seguro de anular el trámite de traslado de <strong>"+e.afiliado+"</strong>?",this.dialogA.registroID=e.id,this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.content=null,this.dialogA.registroID=null},confirmaAnulacion:function(e){var t=this;this.axios.post("formtrasmovs",{estado:"Anulado",detalle_anulacion:e,id:this.dialogA.registroID}).then(function(){t.tableItemTrasladosAfiliados.find(function(e){return e.id===t.dialogA.registroID}).estado="Anulado",t.$store.commit(o.b,{msg:"El trámite de traslado se anuló satisfactoriamente.",color:"success"}),t.cancelaAnulacion()}).catch(function(e){t.$store.commit(o.a,{expression:" al intentar anular el trámite de traslado. ",error:e})})}}}},1784:function(e,t,i){var a=i(1785);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);i(456)("be812bd4",a,!0,{})},1785:function(e,t,i){t=e.exports=i(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Traslados.vue",sourceRoot:""}])},1786:function(e,t,i){"use strict";var a=function(){var e=this,t=this,i=t.$createElement,a=t._self._c||i;return a("v-card",[a("confirmar",{attrs:{value:t.dialogA.visible,content:t.dialogA.content},on:{cancelar:t.cancelaAnulacion,aceptar:function(e){return t.confirmaAnulacion(e)}}}),t._v(" "),a("toolbar-list",{attrs:{info:t.infoComponent,title:"Trámites de traslados",subtitle:"Registro y gestión",btnplus:"",btnplustitle:"Crear trámite"}}),t._v(" "),a("data-table-v2",{ref:"tableItemTrasladosAfiliados",attrs:{"con-buscador":!1,"con-registro":!1,"con-columnas":!1},on:{resetOption:function(e){return t.resetOptions(e)},imprimir:function(e){return t.filePrint("formulario-tramite-traslado&id="+e.id)},editar:function(i){return t.$store.commit("NAV_ADD_ITEM",{ruta:e.infoComponent.ruta_registro,titulo:e.infoComponent.titulo_registro,parametros:{entidad:i,tabOrigin:e.$store.state.nav.currentItem.split("tab-")[1]}})},anular:function(e){return t.registroAnular(e)},"apply-filters":t.aplicaFiltros},model:{value:t.traslateAffiliatumItems,callback:function(e){t.traslateAffiliatumItems=e},expression:"traslateAffiliatumItems"}},[a("template",{slot:"filters"},[a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-select",{attrs:{label:"Tipo documento","item-text":"abreviatura","item-value":"id",items:t.complementosID.tipdocidentidades,chips:"","deletable-chips":"","small-chips":"",clearable:""},model:{value:t.filtroRuta.tipo_id,callback:function(e){t.$set(t.filtroRuta,"tipo_id",e)},expression:"filtroRuta.tipo_id"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{attrs:{label:"Identificación",clearable:""},model:{value:t.filtroRuta.identificacion,callback:function(e){t.$set(t.filtroRuta,"identificacion",e)},expression:"filtroRuta.identificacion"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}}),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.nombre1",expression:"'filtroRuta.nombre1'"}],attrs:{label:"Primer Nombre",clearable:""},model:{value:t.filtroRuta.nombre1,callback:function(e){t.$set(t.filtroRuta,"nombre1",e)},expression:"filtroRuta.nombre1"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.nombre2",expression:"'filtroRuta.nombre2'"}],attrs:{label:"Segundo Nombre",clearable:""},model:{value:t.filtroRuta.nombre2,callback:function(e){t.$set(t.filtroRuta,"nombre2",e)},expression:"filtroRuta.nombre2"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.apellido1",expression:"'filtroRuta.apellido1'"}],attrs:{label:"Primer Apellido",clearable:""},model:{value:t.filtroRuta.apellido1,callback:function(e){t.$set(t.filtroRuta,"apellido1",e)},expression:"filtroRuta.apellido1"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{directives:[{name:"upper",rawName:"v-upper",value:"filtroRuta.apellido2",expression:"'filtroRuta.apellido2'"}],attrs:{label:"Segundo Apellido",clearable:""},model:{value:t.filtroRuta.apellido2,callback:function(e){t.$set(t.filtroRuta,"apellido2",e)},expression:"filtroRuta.apellido2"}})],1),t._v(" "),"v-select"===t.filters[0].type||"v-autocomplete"===t.filters[0].type?a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[a("v-layout",{attrs:{"align-center":""}},[a(t.filters[0].type,{key:"filter0",tag:"component",attrs:{label:t.filters[0].label,multiple:t.filters[0].multiple,"item-text":t.filters[0].itemText,"item-value":t.filters[0].itemValue,items:t.filters[0].items(),clearable:t.filters[0].clearable},scopedSlots:t._u([{key:"selection",fn:function(e){var i=e.item;e.index;return[a("v-chip",{attrs:{small:""}},[a("span",[t._v(t._s(i[t.filters[0].itemText]))])])]}}],null,!1,4236200057),model:{value:t.filters[0].vModel,callback:function(e){t.$set(t.filters[0],"vModel",e)},expression:"filters[0].vModel"}})],1)],1):t._e(),t._v(" "),"v-range"===t.filters[1].type&&"date"===t.filters[1].items.type?a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[a("v-layout",{attrs:{"align-center":""}},[a("v-flex",{attrs:{xs6:""}},[a("v-menu",{key:"fechaMenu1",ref:t.filters[1].items.itemMin.label+1,staticClass:"mt-0",attrs:{"close-on-content-click":!1,"nudge-right":40,"return-value":t.filters[1].items.itemMin.vModel,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","min-width":"290px"},on:{"update:returnValue":function(e){return t.$set(t.filters[1].items.itemMin,"vModel",e)},"update:return-value":function(e){return t.$set(t.filters[1].items.itemMin,"vModel",e)}},model:{value:t.filters[1].items.itemMin.menuDate,callback:function(e){t.$set(t.filters[1].items.itemMin,"menuDate",e)},expression:"filters[1].items.itemMin.menuDate"}},[a("v-text-field",{key:"fechaText1",attrs:{slot:"activator",label:t.filters[1].items.itemMin.label,clearable:"",readonly:""},slot:"activator",model:{value:t.filters[1].items.itemMin.vModel,callback:function(e){t.$set(t.filters[1].items.itemMin,"vModel",e)},expression:"filters[1].items.itemMin.vModel"}}),t._v(" "),a("v-date-picker",{key:"fechaPicker1",attrs:{locale:"es-co"},on:{input:function(e){t.$refs[t.filters[1].items.itemMin.label+1][0].save(t.filters[1].items.itemMin.vModel)}},model:{value:t.filters[1].items.itemMin.vModel,callback:function(e){t.$set(t.filters[1].items.itemMin,"vModel",e)},expression:"filters[1].items.itemMin.vModel"}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs6:""}},[a("v-menu",{key:"fechaMenuMax1",ref:t.filters[1].items.itemMax.label+1,staticClass:"mt-0",attrs:{"close-on-content-click":!1,"nudge-right":40,"return-value":t.filters[1].items.itemMax.vModel,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","min-width":"290px"},on:{"update:returnValue":function(e){return t.$set(t.filters[1].items.itemMax,"vModel",e)},"update:return-value":function(e){return t.$set(t.filters[1].items.itemMax,"vModel",e)}},model:{value:t.filters[1].items.itemMax.menuDate,callback:function(e){t.$set(t.filters[1].items.itemMax,"menuDate",e)},expression:"filters[1].items.itemMax.menuDate"}},[a("v-text-field",{key:"fechaTextMax1",attrs:{slot:"activator",label:t.filters[1].items.itemMax.label,clearable:"",readonly:""},slot:"activator",model:{value:t.filters[1].items.itemMax.vModel,callback:function(e){t.$set(t.filters[1].items.itemMax,"vModel",e)},expression:"filters[1].items.itemMax.vModel"}}),t._v(" "),a("v-date-picker",{key:"fechaPickerMax1",attrs:{locale:"es-co"},on:{input:function(e){t.$refs[t.filters[1].items.itemMax.label+1][0].save(t.filters[1].items.itemMax.vModel)}},model:{value:t.filters[1].items.itemMax.vModel,callback:function(e){t.$set(t.filters[1].items.itemMax,"vModel",e)},expression:"filters[1].items.itemMax.vModel"}})],1)],1)],1)],1):t._e(),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md6:""}},[a("v-autocomplete",{attrs:{label:"Municipio Afiliado",items:t.complementosID.municipios,"item-value":"id","item-text":"nombre",filter:t.filterMunicipios,clearable:""},scopedSlots:t._u([{key:"item",fn:function(e){return[[a("v-list-tile-content",[a("v-list-tile-title",{domProps:{innerHTML:t._s(e.item.nombre)}}),t._v(" "),a("v-list-tile-sub-title",{domProps:{innerHTML:t._s(e.item.nombre_departamento)}})],1)]]}}]),model:{value:t.filtroRuta.gn_municipio_id,callback:function(e){t.$set(t.filtroRuta,"gn_municipio_id",e)},expression:"filtroRuta.gn_municipio_id"}})],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm12:"",md3:""}},[a("v-text-field",{attrs:{label:"Ficha sisbén",clearable:""},model:{value:t.filtroRuta.ficha,callback:function(e){t.$set(t.filtroRuta,"ficha",e)},expression:"filtroRuta.ficha"}})],1)],1)],2)],1)},l=[],o={render:a,staticRenderFns:l};t.a=o},551:function(e,t,i){"use strict";function a(e){i(1784)}Object.defineProperty(t,"__esModule",{value:!0});var l=i(1063),o=i(1786),n=i(1),r=a,s=n(l.a,o.a,!1,r,"data-v-5b1d0758",null);t.default=s.exports},749:function(e,t,i){"use strict";function a(e){i(765)}Object.defineProperty(t,"__esModule",{value:!0});var l=i(751),o=i(767),n=i(1),r=a,s=n(l.a,o.a,!1,r,"data-v-163e0f86",null);t.default=s.exports},751:function(e,t,i){"use strict";var a=i(13),l=i.n(a),o=i(14),n=i.n(o),r=i(58);t.a={name:"Confirmar",props:{value:{type:Boolean,default:!1},content:{type:String},requiereMotivo:{type:Boolean,default:!0},loading:{type:Boolean,default:!1}},components:{Loading:r.default},data:function(){return{motivo:null}},watch:{value:function(e){e||(this.motivo=null,this.$validator.reset())}},methods:{submit:function(){var e=this;return n()(l.a.mark(function t(){return l.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$validator.validateAll();case 2:if(!t.sent){t.next=4;break}e.$emit("aceptar",e.motivo);case 4:case"end":return t.stop()}},t,e)}))()}}}},765:function(e,t,i){var a=i(766);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);i(456)("f5fd1682",a,!0,{})},766:function(e,t,i){t=e.exports=i(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Confirmar.vue",sourceRoot:""}])},767:function(e,t,i){"use strict";var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-layout",{attrs:{row:"","justify-center":""}},[i("v-dialog",{key:"dialogConfirm",attrs:{persistent:"","max-width":"400"},model:{value:e.value,callback:function(t){e.value=t},expression:"value"}},[i("loading",{model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}}),e._v(" "),i("v-card",[i("v-card-text",{staticClass:"title font-weight-light text-xs-center",domProps:{innerHTML:e._s(e.content)}}),e._v(" "),e.requiereMotivo?i("v-container",{attrs:{fluid:"","grid-list-xs":""}},[i("v-layout",{attrs:{row:"",wrap:""}},[i("v-flex",{attrs:{xs12:""}},[i("v-textarea",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{name:"motivo",label:"Motivo",hint:"Motivo por el cual se anula el registro.","persistent-hint":"",required:"","error-messages":e.errors.collect("motivo")},model:{value:e.motivo,callback:function(t){e.motivo=t},expression:"motivo"}})],1)],1)],1):e._e(),e._v(" "),i("v-divider"),e._v(" "),i("v-card-actions",[i("v-btn",{attrs:{flat:""},on:{click:function(t){return t.stopPropagation(),e.$emit("cancelar")}}},[e._v("Cancelar")]),e._v(" "),i("v-spacer"),e._v(" "),i("v-btn",{attrs:{color:"primary"},on:{click:function(t){return t.stopPropagation(),e.submit(t)}}},[e._v("Aceptar")])],1)],1)],1)],1)},l=[],o={render:a,staticRenderFns:l};t.a=o}});
//# sourceMappingURL=53.60082749a5d5e654249f.js.map