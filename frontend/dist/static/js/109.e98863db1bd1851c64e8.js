webpackJsonp([109],{1126:function(t,e,a){"use strict";var s=a(255),i=a.n(s),r=a(249),o=a.n(r),l=a(13),n=a.n(l),v=a(14),c=a.n(v),u=a(58),d=a(12),p=a(40),_=a(248),f=a(247),m=a(259),x=a.n(m),g=a(269),h=a.n(g),b=a(12);e.a={name:"DetallePqrs",components:{ToolbarList:f.default,Loading:u.default,InputFile:function(){return a.e(288).then(a.bind(null,748))},ckeditor:x.a.component},props:["parametros"],data:function(){return{editor:h.a,editorConfig:{toolbar:["heading","|","bold","italic","bulletedList","link","link","numberedList","|","undo","redo"],language:"es"},respuesta:null,payload:null,archivosNuevos:[{}],menuDate:!1,dialogConfirm:null,loading:!1,loadingSubmit:!1,loadingPdf:!1,item:null}},beforeMount:function(){this.formReset()},mounted:function(){null!==this.parametros.entidad&&this.getRegisto(this.parametros.entidad.id)},computed:{computedDateFormatted:function(){return this.formDate(this.respuesta.fecha_respuesta)},verificarEstadoRespuesta:function(){if(this.item.respuesta)return"Confirmada"===this.item.respuesta.estado}},methods:{getRegisto:function(t){var e=this,a=this.$loading.show({container:this.$refs.detallePqrs.$el});this.axios.get("pqrsds/"+t).then(function(t){""!==t.data&&(e.item=t.data.data,t.data.data.respuesta&&e.agregarDatosRespuesta(t.data.data.respuesta),e.respuesta.soluciono_queja=e.item.soluciono_queja),a.hide()}).catch(function(t){a.hide(),e.$store.commit(d.b,{msg:"Hay un error al traer la pqrs. "+t.response,color:"danger"})})},descargarPdf:function(){var t=this;return c()(n.a.mark(function e(){var a,s;return n.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.validador("formDetalleRespuesta");case 2:if(!e.sent){e.next=13;break}return t.loadingPdf=!0,t.respuesta.estado="Registrada",t.addFormData(),a=t.respuesta.id?t.axios.post("pqrsds/"+t.item.id+"/respuesta/"+t.respuesta.id,t.payload):t.axios.post("pqrsds/"+t.item.id+"/respuesta",t.payload),e.next=9,a.then(function(t){return!0}).catch(function(e){return t.loadingPdf=!1,t.$store.commit(b.a,{expression:":",error:e}),!1});case 9:s=e.sent,s&&t.axios.get("firmar-ruta?nombre_ruta=respuesta-pqrs-pdf&id="+t.item.id).then(function(e){window.open(e.data),t.loadingPdf=!1}).catch(function(e){t.loadingPdf=!1,t.$store.commit(d.b,{msg:"Error al generar el archivo. "+e,color:"danger"})}),e.next=14;break;case 13:t.$store.commit(d.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 14:case"end":return e.stop()}},e,t)}))()},formReset:function(){this.respuesta={id:null,texto:null,files:[],tipo:"Positiva",fecha_respuesta:null,anexos:null,soluciono_queja:null}},agregarDatosRespuesta:function(t){this.respuesta.id=t.id,this.respuesta.tipo=t.tipo,this.respuesta.fecha_respuesta=t.fecha_respuesta,this.respuesta.respuesta=t.respuesta},validador:function(t){return this.$validator.validateAll(t).then(function(t){return t})},formDate:function(t){if(!t)return null;var e=t.split("-"),a=o()(e,3);return a[0]+"/"+a[1]+"/"+a[2]},close:function(){this.formReset(),this.$validator.reset(),this.$store.dispatch(p.c,{contexto:this,itemId:this.parametros.tabOrigin})},addFormData:function(){var t=new FormData;t.append("fecha_respuesta",this.respuesta.fecha_respuesta),t.append("respuesta",this.respuesta.respuesta),t.append("tipo",this.respuesta.tipo),t.append("estado",this.respuesta.estado),t.append("soluciono_queja",this.respuesta.soluciono_queja);var e=!0,a=!1,s=void 0;try{for(var r,o=i()(this.respuesta.files);!(e=(r=o.next()).done);e=!0){var l=r.value;t.append("files[]",l)}}catch(t){a=!0,s=t}finally{try{!e&&o.return&&o.return()}finally{if(a)throw s}}this.payload=t},submit:function(t){var e=this;return c()(n.a.mark(function a(){var s;return n.a.wrap(function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,e.validador("formDetalleRespuesta");case 2:if(!a.sent){a.next=10;break}e.loadingSubmit=!0,e.respuesta.estado="Si"===t?"Confirmada":"Registrada",e.addFormData(),s=e.respuesta.id?e.axios.post("pqrsds/"+e.item.id+"/respuesta/"+e.respuesta.id,e.payload):e.axios.post("pqrsds/"+e.item.id+"/respuesta",e.payload),s.then(function(t){e.$store.commit(d.b,{msg:"Se guardo la respuesta correctamente.",color:"success"}),e.$store.commit(_.F,{item:t.data,key:e.parametros.key}),e.$store.dispatch(p.c,{contexto:e,itemId:e.parametros.tabOrigin})}).catch(function(t){e.loadingSubmit=!1,e.dialogConfirm=!1,e.$store.commit(b.a,{expression:":",error:t})}),a.next=11;break;case 10:e.$store.commit(d.b,{msg:"Los campos no pueden estar vacios.",color:"danger"});case 11:case"end":return a.stop()}},a,e)}))()}}}},1989:function(t,e,a){var s=a(1990);"string"==typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);a(456)("68a7a066",s,!0,{})},1990:function(t,e,a){e=t.exports=a(455)(!0),e.push([t.i,".tipo[data-v-6fd013d2]{height:10px;padding:0}.editor[data-v-6fd013d2]{height:500px}.botonFlotante[data-v-6fd013d2]{margin:160px 16px 0 0}","",{version:3,sources:["/home/capresoca/projects/Capresoca-Frontend/src/components/misional/atencionUsuario/pqrs/DetallePqrs.vue"],names:[],mappings:"AACA,uBACE,YAAa,AACb,SAAW,CACZ,AACD,yBACE,YAAc,CACf,AACD,gCACE,qBAAwB,CACzB",file:"DetallePqrs.vue",sourcesContent:["\n.tipo[data-v-6fd013d2] {\n  height: 10px;\n  padding: 0;\n}\n.editor[data-v-6fd013d2] {\n  height: 500px;\n}\n.botonFlotante[data-v-6fd013d2] {\n  margin: 160px 16px 0 0 ;\n}\n"],sourceRoot:""}])},1991:function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-dialog",{attrs:{persistent:"","max-width":"290"},model:{value:t.dialogConfirm,callback:function(e){t.dialogConfirm=e},expression:"dialogConfirm"}},[a("v-card",[a("v-card-title",{staticClass:"headline grey lighten-3"},[t._v("¿Desea confirmar la respuesta?")]),t._v(" "),a("v-divider"),t._v(" "),a("v-card-text",[a("p",[t._v("Si escoge la opción 'Si' la respuesta se guardará y los campos de la respuesta no podrán volver a ser editados.")]),t._v(" "),a("p",[t._v("Si escoge la opción 'No' la respuesta se guardará y seguirá habilitada para edición.")])]),t._v(" "),a("v-divider"),t._v(" "),a("v-card-actions",[a("v-btn",{attrs:{color:"green darken-1",loading:t.loadingSubmit,flat:""},nativeOn:{click:function(e){t.dialogConfirm=!1}}},[t._v("Cancelar")]),t._v(" "),a("v-spacer"),t._v(" "),a("v-btn",{attrs:{color:"green darken-1",loading:t.loadingSubmit,flat:""},nativeOn:{click:function(e){return t.submit("No")}}},[t._v("No")]),t._v(" "),a("v-btn",{attrs:{color:"primary",loading:t.loadingSubmit,flat:""},nativeOn:{click:function(e){return t.submit("Si")}}},[t._v("Si")])],1)],1)],1),t._v(" "),a("v-card",{ref:"formPqrs"},[a("v-container",{staticStyle:{"max-width":"inherit"},attrs:{"grid-list-md":""}},[a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-card",{staticClass:"content-list-p0 pt-0",attrs:{flat:""}},[a("v-list",{attrs:{"two-line":""}},[[a("v-list-tile",[a("v-list-tile-avatar",{attrs:{color:"info"}},[a("v-icon",{attrs:{dark:""}},[t._v("assignment")])],1),t._v(" "),a("v-list-tile-content",[a("v-list-tile-title",[t._v("\n                      Detalle PQRS\n                    ")]),t._v(" "),t.item?a("v-list-tile-sub-title",[t._v("Consecutivo: "+t._s(t.item.consecutivo))]):t._e()],1)],1),t._v(" "),a("v-spacer"),t._v(" "),a("v-divider"),t._v(" "),a("v-tooltip",{attrs:{top:""}},[t.item&&t.item.respuesta?a("v-btn",{staticClass:"botonFlotante",attrs:{slot:"activator",absolute:"",dark:"",small:"",fab:"",top:"",right:"",fixed:"",color:"pink",loading:t.loadingPdf},on:{click:t.descargarPdf},slot:"activator"},[a("v-icon",[t._v("far fa-file-pdf")])],1):t._e(),t._v(" "),a("span",[t._v("Vista previa carta respuesta")])],1)]],2),t._v(" "),a("v-card-text",[a("v-container",{ref:"detallePqrs",staticClass:"pa-0",attrs:{fluid:"","grid-list-xl":""}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-slide-y-transition",{attrs:{group:""}},[t.item?a("v-layout",{key:"seccion1",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-subheader",[t._v("Afiliado")])],1),t._v(" "),a("v-flex",{attrs:{xs2:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Identificación")]),t._v(" "),a("span",[t._v(t._s(t.item.afiliado.tipo_identificacion+" "+t.item.afiliado.identificacion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs10:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Nombre")]),t._v(" "),a("span",{staticStyle:{"text-decoration":"underline",cursor:"pointer"},on:{click:function(e){e.preventDefault(),t.$store.commit("NAV_ADD_ITEM",{ruta:"detalleGeneralAfiliado",titulo:"Detalle afiliado",parametros:{entidad:{id:t.item.afiliado.id},tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}}},[t._v("\n                          "+t._s(t.item.afiliado.nombre_completo)+"\n                        ")])],1)],1)],1):t._e(),t._v(" "),t.item?a("v-layout",{key:"seccion2",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-divider"),t._v(" "),a("v-subheader",[t._v("Entidad")])],1),t._v(" "),a("v-flex",{attrs:{xs2:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Código habilitación")]),t._v(" "),a("span",[t._v(t._s(t.item.entidad.cod_habilitacion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs10:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Nombre")]),t._v(" "),a("span",[t._v(t._s(t.item.entidad.nombre))])],1)],1)],1):t._e(),t._v(" "),t.item?a("v-layout",{key:"seccion3",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-divider"),t._v(" "),a("v-subheader",[t._v("Remitente")])],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Nombres")]),t._v(" "),a("span",[t._v(t._s(t.item.nombres))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Apellidos")]),t._v(" "),a("span",[t._v(t._s("null"!=t.item.apellidos?t.item.apellidos:"Sin apellidos"))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Identificación")]),t._v(" "),a("span",[t._v(t._s(t.item.identificacion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Municipios")]),t._v(" "),a("span",[t._v(t._s(t.item.municipio.nombre))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Dirección")]),t._v(" "),a("span",[t._v(t._s(t.item.direccion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Teléfono")]),t._v(" "),a("span",[t._v(t._s(t.item.telefono))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Email")]),t._v(" "),a("span",[t._v(t._s(t.item.email||"No registra"))])],1)],1)],1):t._e(),t._v(" "),t.item?a("v-layout",{key:"seccion4",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-divider"),t._v(" "),a("v-subheader",[t._v("Petición")])],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Concecutivo externo")]),t._v(" "),a("span",[t._v(t._s(t.item.consecutivo_externo))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Fecha recepción")]),t._v(" "),a("span",[t._v(t._s(t.item.fecha_recepcion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Medio recepción")]),t._v(" "),a("span",[t._v(t._s(t.item.medio_recepcion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Tipo")]),t._v(" "),a("span",[t._v(t._s(t.item.tipo.tipo))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Prioritaria")]),t._v(" "),a("span",[t._v(t._s(t.item.es_prioritaria))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Asignado a")]),t._v(" "),a("span",[t._v(t._s(t.item.funcionario.name))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Plazo asignado")]),t._v(" "),a("span",[t._v(t._s(t.item.plazo))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Días restantes para responder")]),t._v(" "),a("span",[t._v(t._s(t.item.dias_para_responder))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm3:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Teléfono")]),t._v(" "),a("span",[t._v(t._s(t.item.telefono))])],1)],1)],1):t._e(),t._v(" "),t.item?a("v-layout",{key:"seccion5",attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Asunto")]),t._v(" "),a("span",[t._v(t._s(t.item.asunto.descripcion))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Relato de los hechos")]),t._v(" "),a("span",[t._v(t._s(t.item.relato))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Archivos adjuntos")]),t._v(" "),t.item.anexos.length<=0?a("span",[t._v("No hay archivos adjuntos")]):t._e()],1),t._v(" "),t._l(t.item.anexos,function(e,s){return t.item.anexos.length>0?a("v-flex",{key:s,attrs:{xs12:"",sm4:""}},[a("v-layout",{attrs:{"align-center":"","fill-height":""}},[a("v-flex",{attrs:{"d-flex":"",xs10:"",sm8:""}},[a("v-text-field",{key:"archivo",attrs:{label:"Archivo",name:"archivo",disabled:"","prepend-icon":"description","error-messages":t.errors.collect("archivo")},model:{value:e.nombre,callback:function(a){t.$set(e,"nombre",a)},expression:"archivo.nombre"}})],1),t._v(" "),a("v-flex",{attrs:{"d-flex":"",xs2:"",sm4:""}},[a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",color:"success",small:"",href:e.url_signed,target:"_blank"},slot:"activator"},[a("v-icon",[t._v("remove_red_eye")])],1),t._v(" "),a("span",[t._v("Ver archivo")])],1)],1)],1)],1):t._e()})],2):t._e()],1)],1)],1)],1),t._v(" "),t.item?a("v-card",{staticClass:"content-list-p0 pt-0",attrs:{flat:""}},[a("v-list",{attrs:{"two-line":""}},[[a("v-divider"),t._v(" "),a("v-list-tile",[a("v-list-tile-avatar",{attrs:{color:"success"}},[a("v-icon",{attrs:{dark:""}},[t._v("input")])],1),t._v(" "),a("v-list-tile-content",[a("v-list-tile-title",[t._v("\n                      Respuesta\n                    ")]),t._v(" "),a("v-list-tile-sub-title",[t._v("Estado: "+t._s(t.item.respuesta?t.item.respuesta.estado:"Sin respuesta"))])],1)],1),t._v(" "),a("v-spacer"),t._v(" "),a("v-divider")]],2),t._v(" "),t.item.respuesta?a("div",["Confirmada"===t.item.respuesta.estado?a("v-card-text",[a("v-container",{ref:"detallePqrs",staticClass:"pa-0",attrs:{fluid:"","grid-list-xl":""}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-slide-y-transition",{attrs:{group:""}},[t.item?a("v-layout",{key:"seccion1",attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Fecha de respuesta")]),t._v(" "),a("span",[t._v(t._s(t.respuesta.fecha_respuesta))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-list-tile-content",{attrs:{slot:"activator"},slot:"activator"},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Tipo")]),t._v(" "),a("span",[t._v(t._s(t.respuesta.tipo))])],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-subheader",[t._v("Respuesta")]),t._v(" "),a("ckeditor",{attrs:{editor:t.editor,config:t.editorConfig,disabled:""},model:{value:t.respuesta.respuesta,callback:function(e){t.$set(t.respuesta,"respuesta",e)},expression:"respuesta.respuesta"}})],1),t._v(" "),a("v-flex",{staticClass:"pt-1",attrs:{xs12:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("¿Se solucionó la queja?")]),t._v(" "),a("v-radio-group",{attrs:{disabled:t.verificarEstadoRespuesta,row:""},model:{value:t.respuesta.soluciono_queja,callback:function(e){t.$set(t.respuesta,"soluciono_queja",e)},expression:"respuesta.soluciono_queja"}},[a("v-radio",{attrs:{label:"Si",value:1}}),t._v(" "),a("v-radio",{attrs:{label:"No",value:0}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-list-tile-sub-title",{staticClass:"caption grey--text"},[t._v("Archivos adjuntos")]),t._v(" "),t.item.respuesta.anexos.length<=0?a("span",[t._v("No hay archivos adjuntos")]):t._e()],1),t._v(" "),t._l(t.item.respuesta.anexos,function(e,s){return t.item.respuesta.anexos.length>0?a("v-flex",{key:s,attrs:{xs12:"",sm4:""}},[a("v-layout",{attrs:{"align-center":"","fill-height":""}},[a("v-flex",{attrs:{"d-flex":"",xs10:"",sm8:""}},[a("v-text-field",{key:"archivo",attrs:{label:"Archivo",name:"archivo",disabled:"","prepend-icon":"description","error-messages":t.errors.collect("archivo")},model:{value:e.nombre,callback:function(a){t.$set(e,"nombre",a)},expression:"archivo.nombre"}})],1),t._v(" "),a("v-flex",{attrs:{"d-flex":"",xs2:"",sm4:""}},[a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",color:"success",small:"",href:e.url_signed,target:"_blank"},slot:"activator"},[a("v-icon",[t._v("remove_red_eye")])],1),t._v(" "),a("span",[t._v("Ver archivo")])],1)],1)],1)],1):t._e()})],2):t._e()],1)],1)],1):a("v-card-text",[a("v-container",{ref:"detallePqrs",staticClass:"pa-0",attrs:{fluid:"","grid-list-xl":""}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-slide-y-transition",{attrs:{group:""}},[t.item?a("div",{key:"seccion1",attrs:{row:"",wrap:""}},[a("v-form",{attrs:{"data-vv-scope":"formDetalleRespuesta"}},[a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("Fecha de respuesta")]),t._v(" "),a("v-menu",{ref:"menuDate",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:t.menuDate,callback:function(e){t.menuDate=e},expression:"menuDate"}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha","prepend-icon":"event",readonly:"",name:"fecha de respuesta",disabled:t.verificarEstadoRespuesta,"error-messages":t.errors.collect("fecha de respuesta")},slot:"activator",model:{value:t.computedDateFormatted,callback:function(e){t.computedDateFormatted=e},expression:"computedDateFormatted"}}),t._v(" "),a("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){t.menuDate=!1},change:function(){var e=t.$validator.errors.items.findIndex(function(t){return"fecha de respuesta"===t.field});t.$validator.errors.items.splice(-1!==e?e:0,-1!==e?1:0)}},model:{value:t.respuesta.fecha_respuesta,callback:function(e){t.$set(t.respuesta,"fecha_respuesta",e)},expression:"respuesta.fecha_respuesta"}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("Tipo")]),t._v(" "),a("v-radio-group",{attrs:{disabled:t.verificarEstadoRespuesta,row:""},model:{value:t.respuesta.tipo,callback:function(e){t.$set(t.respuesta,"tipo",e)},expression:"respuesta.tipo"}},[a("v-radio",{attrs:{label:"Positiva",value:"Positiva"}}),t._v(" "),a("v-radio",{attrs:{label:"Negativa",value:"Negativa"}}),t._v(" "),a("v-radio",{attrs:{label:"Otra",value:"Otra"}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:""}},[a("v-subheader",[t._v("Respuesta")]),t._v(" "),a("ckeditor",{attrs:{editor:t.editor,config:t.editorConfig},model:{value:t.respuesta.respuesta,callback:function(e){t.$set(t.respuesta,"respuesta",e)},expression:"respuesta.respuesta"}})],1),t._v(" "),a("v-flex",{staticClass:"pt-1",attrs:{xs12:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("¿Se solucionó la queja?")]),t._v(" "),a("v-radio-group",{attrs:{disabled:t.verificarEstadoRespuesta,row:""},model:{value:t.respuesta.soluciono_queja,callback:function(e){t.$set(t.respuesta,"soluciono_queja",e)},expression:"respuesta.soluciono_queja"}},[a("v-radio",{attrs:{label:"Si",value:1}}),t._v(" "),a("v-radio",{attrs:{label:"No",value:0}})],1)],1)],1)],1):t._e(),t._v(" "),t.item?a("v-layout",{key:"seccion2",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-subheader",[t._v("Archivos adjuntos")])],1),t._v(" "),t.item.respuesta.anexos.length<=0?a("span",[t._v("No hay archivos adjuntos")]):t._e(),t._v(" "),t._l(t.item.respuesta.anexos,function(e,s){return t.item.respuesta.anexos.length>0?a("v-flex",{key:s,attrs:{xs12:"",sm4:""}},[a("v-layout",{attrs:{"align-center":"","fill-height":""}},[a("v-flex",{attrs:{"d-flex":"",xs10:"",sm8:""}},[a("v-text-field",{key:"archivo",attrs:{label:"Archivo",name:"archivo",disabled:"","prepend-icon":"description","error-messages":t.errors.collect("archivo")},model:{value:e.nombre,callback:function(a){t.$set(e,"nombre",a)},expression:"archivo.nombre"}})],1),t._v(" "),a("v-flex",{attrs:{"d-flex":"",xs2:"",sm4:""}},[a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",color:"success",small:"",href:e.url_signed,target:"_blank"},slot:"activator"},[a("v-icon",[t._v("remove_red_eye")])],1),t._v(" "),a("span",[t._v("Ver archivo")])],1)],1)],1)],1):t._e()}),t._v(" "),t._l(t.archivosNuevos,function(e,s){return a("v-flex",{key:s,attrs:{xs12:"",sm4:""}},[a("input-file",{ref:"archivoAdjunto",refInFor:!0,staticClass:"mb-3",attrs:{label:"Adjuntar archivo","file-name":t.respuesta.files[s]?t.respuesta.files[s].nombre:null,accept:"application/pdf",hint:"Extenciones aceptadas: pdf",disabled:t.verificarEstadoRespuesta,"prepend-icon":"description",clearable:""},model:{value:t.respuesta.files[s],callback:function(e){t.$set(t.respuesta.files,s,e)},expression:"respuesta.files[index]"}})],1)}),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"primary"},on:{click:function(e){return t.archivosNuevos.push({})}},slot:"activator"},[a("v-icon",{attrs:{dark:""}},[t._v("add")])],1),t._v(" "),a("span",[t._v("Nuevo archivo")])],1)],2):t._e()],1)],1)],1)],1):a("v-card-text",[a("v-container",{ref:"detallePqrs",staticClass:"pa-0",attrs:{fluid:"","grid-list-xl":""}},[a("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),a("v-slide-y-transition",{attrs:{group:""}},["Sugerencia"==t.item.tipo.tipo||"Felicitaciones"==t.item.tipo.tipo?a("v-layout",{key:"noRespuesta",attrs:{row:"",wrap:""}},[a("v-flex",{attrs:{xs12:""}},[a("v-alert",{attrs:{value:!0,type:"info"}},[t._v("\n                        El tipo de PQRS es "+t._s(t.item.tipo.tipo)+" y no requiere respuesta.\n                      ")])],1)],1):t._e(),t._v(" "),t.item&&"Sugerencia"!=t.item.tipo.tipo&&"Felicitaciones"!=t.item.tipo.tipo?a("div",{key:"seccion1",attrs:{row:"",wrap:""}},[a("v-form",{attrs:{"data-vv-scrope":"formDetalleRespuesta"}},[a("v-flex",{attrs:{xs12:"",sm4:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("Fecha de respuesta")]),t._v(" "),a("v-menu",{ref:"menuDate",attrs:{"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","max-width":"290px","min-width":"290px"},model:{value:t.menuDate,callback:function(e){t.menuDate=e},expression:"menuDate"}},[a("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY/MM/DD",expression:"'required|date_format:YYYY/MM/DD'"}],attrs:{slot:"activator",label:"Fecha","prepend-icon":"event",readonly:"",name:"fecha de respuesta",required:"",disabled:t.verificarEstadoRespuesta,"data-vv-delay":"1000","error-messages":t.errors.collect("fecha de respuesta")},slot:"activator",model:{value:t.computedDateFormatted,callback:function(e){t.computedDateFormatted=e},expression:"computedDateFormatted"}}),t._v(" "),a("v-date-picker",{attrs:{locale:"es","no-title":""},on:{input:function(e){t.menuDate=!1}},model:{value:t.respuesta.fecha_respuesta,callback:function(e){t.$set(t.respuesta,"fecha_respuesta",e)},expression:"respuesta.fecha_respuesta"}})],1)],1),t._v(" "),a("v-flex",{attrs:{xs12:"",sm6:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("Tipo")]),t._v(" "),a("v-radio-group",{attrs:{disabled:t.verificarEstadoRespuesta,row:""},model:{value:t.respuesta.tipo,callback:function(e){t.$set(t.respuesta,"tipo",e)},expression:"respuesta.tipo"}},[a("v-radio",{attrs:{label:"Positiva",value:"Positiva"}}),t._v(" "),a("v-radio",{attrs:{label:"Negativa",value:"Negativa"}}),t._v(" "),a("v-radio",{attrs:{label:"Otra",value:"Otra"}})],1)],1),t._v(" "),a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-subheader",[t._v("Respuesta")]),t._v(" "),a("ckeditor",{attrs:{editor:t.editor,config:t.editorConfig},model:{value:t.respuesta.respuesta,callback:function(e){t.$set(t.respuesta,"respuesta",e)},expression:"respuesta.respuesta"}})],1),t._v(" "),a("v-flex",{staticClass:"pt-1",attrs:{xs12:""}},[a("v-subheader",{staticClass:"tipo"},[t._v("¿Se solucionó la queja?")]),t._v(" "),a("v-radio-group",{attrs:{disabled:t.verificarEstadoRespuesta,row:""},model:{value:t.respuesta.soluciono_queja,callback:function(e){t.$set(t.respuesta,"soluciono_queja",e)},expression:"respuesta.soluciono_queja"}},[a("v-radio",{attrs:{label:"Si",value:1}}),t._v(" "),a("v-radio",{attrs:{label:"No",value:0}})],1)],1)],1)],1):t._e(),t._v(" "),t.item&&"Sugerencia"!=t.item.tipo.tipo&&"Felicitaciones"!=t.item.tipo.tipo?a("v-layout",{key:"Seccion2Adjuntos",attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"pa-0",attrs:{xs12:""}},[a("v-subheader",[t._v("Archivos adjuntos")])],1),t._v(" "),t._l(t.archivosNuevos,function(e,s){return a("v-flex",{key:s,attrs:{xs12:"",sm4:""}},[a("input-file",{ref:"archivoAdjunto",refInFor:!0,staticClass:"mb-3",attrs:{label:"Adjuntar archivo","file-name":t.respuesta.files[s]?t.respuesta.files[s].nombre:null,accept:"application/pdf",hint:"Extenciones aceptadas: pdf",disabled:t.verificarEstadoRespuesta,"prepend-icon":"description"},model:{value:t.respuesta.files[s],callback:function(e){t.$set(t.respuesta.files,s,e)},expression:"respuesta.files[index]"}})],1)}),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("v-btn",{attrs:{slot:"activator",fab:"",dark:"",small:"",color:"primary"},on:{click:function(e){return t.archivosNuevos.push({})}},slot:"activator"},[a("v-icon",{attrs:{dark:""}},[t._v("add")])],1),t._v(" "),a("span",[t._v("Nuevo archivo")])],1)],2):t._e()],1)],1)],1)],1):t._e()],1)],1)],1),t._v(" "),a("v-divider"),t._v(" "),t.item&&"Sugerencia"!=t.item.tipo.tipo&&"Felicitaciones"!=t.item.tipo.tipo?a("v-card-actions",{staticClass:"grey lighten-4"},[null===t.item.respuesta||"Confirmada"!==t.item.respuesta.estado?a("v-layout",{attrs:{row:"",wrap:""}},[a("v-flex",{staticClass:"text-xs-left",attrs:{xs6:""}},[a("v-btn",{attrs:{loading:t.loadingSubmit},on:{click:t.formReset}},[t._v("Limpiar")])],1),t._v(" "),a("v-flex",{staticClass:"text-xs-right",attrs:{xs6:""}},[a("v-btn",{on:{click:t.close}},[t._v("Cancelar")]),t._v(" "),a("v-btn",{attrs:{color:"primary",disabled:t.errors.any()},on:{click:function(e){t.dialogConfirm=!0}}},[t._v("Guardar")])],1)],1):t._e()],1):t._e()],1)],1)},i=[],r={render:s,staticRenderFns:i};e.a=r},592:function(t,e,a){"use strict";function s(t){a(1989)}Object.defineProperty(e,"__esModule",{value:!0});var i=a(1126),r=a(1991),o=a(1),l=s,n=o(i.a,r.a,!1,l,"data-v-6fd013d2",null);e.default=n.exports}});
//# sourceMappingURL=109.e98863db1bd1851c64e8.js.map