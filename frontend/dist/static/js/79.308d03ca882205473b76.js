webpackJsonp([79],{1328:function(t,e,n){"use strict";var a=n(247),o=n(60),i=n(743),r=n(12);e.a={name:"Empleados",components:{ToolbarList:a.default,DataTable:o.default,Confirmar:i.a},data:function(){return{dialogA:{visible:!1,content:null,registroID:null},dataTableAlmacen:{nameItemState:"itemEmpleadoSC",route:"empleadosc",optionsPerPage:[{text:15,value:15},{text:50,value:50},{text:100,value:100}],makeHeaders:[{text:"Empleado",align:"left",sortable:!1,value:"empleado"},{text:"Primer nombre",align:"left",sortable:!1,value:"primer_nombre"},{text:"Primer apellido",align:"left",sortable:!1,value:"primer_apellido"},{text:"Número de identificación",align:"left",sortable:!1,value:"numero_identificacion"},{text:"Opciones",align:"center",sortable:!1,actions:!0,classData:"justify-center layout px-0"}]}}},methods:{resetOptions:function(t){return t.options=[],t.permisos=this.infoComponent?this.infoComponent:null,this.infoComponent&&this.infoComponent.permisos.agregar&&(t.options.push({event:"editar",icon:"edit",tooltip:"Editar"}),t.options.push({event:"eliminar",icon:"delete_forever",tooltip:"Eliminar"})),t.options.push({event:"detalleContratos",icon:"fas fa-table",tooltip:"Ver Contratos"}),t},eliminar:function(t){this.dialogA.content="¿Está seguro de eliminar?",this.dialogA.registroID=t.empleado,this.dialogA.visible=!0},cancelaAnulacion:function(){this.dialogA.visible=!1,this.dialogA.content=null,this.dialogA.registroID=null},confirmaAnulacion:function(t){var e=this;this.axios.delete("empleadosc/eliminar/"+this.dialogA.registroID).then(function(){e.$store.commit(r.b,{msg:"Se elimino el empleado correctamente.",color:"success"}),e.$refs.childComponent.reloadPage(),e.cancelaAnulacion(),e.$refs.confirmo.pararCarga()}).catch(function(t){e.$store.commit(r.a,{expression:" al intentar anular el trámite de afiliación. ",error:t})})}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("scempleados")}}}},2566:function(t,e,n){"use strict";var a=function(){var t=this,e=this,n=e.$createElement,a=e._self._c||n;return a("v-card",[a("toolbar-list",{attrs:{info:e.infoComponent,btnplus:"",btnplustitle:"Agregar empleado",title:"Empleado",subtitle:"Registro y gestión"}}),e._v(" "),a("data-table",{ref:"childComponent",on:{resetOption:function(t){return e.resetOptions(t)},editar:function(n){return e.$store.commit("NAV_ADD_ITEM",{ruta:t.infoComponent.ruta_registro,titulo:"Editar empleados",parametros:{entidad:n,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})},eliminar:function(t){return e.eliminar(t)},detalleContratos:function(n){return e.$store.commit("NAV_ADD_ITEM",{ruta:"detalleContratosEmpleado",titulo:"Contratos Empleado CC."+n.numero_identificacion,parametros:{entidad:n,tabOrigin:t.$store.state.nav.currentItem.split("tab-")[1]}})}},model:{value:e.dataTableAlmacen,callback:function(t){e.dataTableAlmacen=t},expression:"dataTableAlmacen"}}),e._v(" "),a("confirmar",{ref:"confirmo",attrs:{value:e.dialogA.visible,content:e.dialogA.content},on:{cancelar:e.cancelaAnulacion,aceptar:function(t){return e.confirmaAnulacion(t)}}})],1)},o=[],i={render:a,staticRenderFns:o};e.a=i},717:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=n(1328),o=n(2566),i=n(1),r=i(a.a,o.a,!1,null,null,null);e.default=r.exports},742:function(t,e,n){"use strict";var a=n(13),o=n.n(a),i=n(14),r=n.n(i),l=n(58);e.a={name:"Confirmar",props:{value:{type:Boolean,default:!1},content:{type:String},requiereMotivo:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}},components:{Loading:l.default},data:function(){return{loadingSubmit:!1,motivo:null}},watch:{value:function(t){t||(this.motivo=null,this.$validator.reset())}},methods:{submit:function(){var t=this;return r()(o.a.mark(function e(){return o.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$validator.validateAll();case 2:if(!e.sent){e.next=5;break}t.loadingSubmit=!0,t.$emit("aceptar",t.motivo);case 5:case"end":return e.stop()}},e,t)}))()},pararCarga:function(){this.loadingSubmit=!1}}}},743:function(t,e,n){"use strict";function a(t){n(744)}var o=n(742),i=n(746),r=n(1),l=a,s=r(o.a,i.a,!1,l,"data-v-3773d9ac",null);e.a=s.exports},744:function(t,e,n){var a=n(745);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);n(456)("0e7a280b",a,!0,{})},745:function(t,e,n){e=t.exports=n(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Confirmar2.vue",sourceRoot:""}])},746:function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-layout",{attrs:{row:"","justify-center":""}},[n("v-dialog",{key:"dialogConfirm",attrs:{persistent:"","max-width":"400"},model:{value:t.value,callback:function(e){t.value=e},expression:"value"}},[n("loading",{model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}}),t._v(" "),n("v-card",[n("v-card-text",{staticClass:"title font-weight-light text-xs-center",domProps:{innerHTML:t._s(t.content)}}),t._v(" "),n("v-divider"),t._v(" "),n("v-card-actions",[n("v-btn",{attrs:{flat:""},on:{click:function(e){return e.stopPropagation(),t.$emit("cancelar")}}},[t._v("Cancelar")]),t._v(" "),n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"primary",loading:t.loadingSubmit},on:{click:function(e){return e.stopPropagation(),t.submit(e)}}},[t._v("Aceptar")])],1)],1)],1)],1)},o=[],i={render:a,staticRenderFns:o};e.a=i}});
//# sourceMappingURL=79.308d03ca882205473b76.js.map