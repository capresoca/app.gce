webpackJsonp([357],{1305:function(t,e,i){"use strict";var n=i(7),a=i.n(n);e.a={name:"Procedimientos",props:["item","items"],components:{InputDetailFlex:function(){return new Promise(function(t){t()}).then(i.bind(null,250))},RegistroDireccionamientos:function(){return i.e(293).then(i.bind(null,860))}},data:function(){return{RegistroDireccionamiento:{parent:null,item:null,type:"procedimiento",show:!1},itemsListos:[]}},created:function(){var t=this;this.items.forEach(function(e){t.itemsListos.push({title:e.CodCUPS+(e.cup?" - "+e.cup.descripcion:""),subtitle:"Tipo de Prestación: "+e.tipo_prestador,cup:e.cup,consecutivo:e.ConOrden,id:e.id,TipoTec:"P",TipoMed:null,cantidadTotal:e.CantTotal,EstJM:e.EstJM,EstJMText:e.estado_junta,direccionamientos:e.direccionamientos,datosBasicos:[{label:"Descripción",text:e.CodCUPS+(e.cup?" - "+e.cup.descripcion:""),hint:null,icon:null,flexClass:null},{label:"Cantidad",text:null!==e.CanForm?e.CanForm.toString():"",hint:null,icon:null,flexClass:null},{label:"Frecuencia de Uso",text:e.CadaFreUso?e.CadaFreUso+(e.frecuencia_uso?" "+e.frecuencia_uso:""):null,hint:null,icon:null,flexClass:null},{label:"Duración Tratamiento",text:e.Cant?e.Cant+(e.periodo_duracion_tratamiento?" "+e.periodo_duracion_tratamiento:""):null,hint:null,icon:null,flexClass:null},{label:"Cantidad Total",text:null!==e.CantTotal?e.CantTotal.toString():"",hint:null,icon:null,flexClass:null}],propsTop:[{label:"CUPS",text:e.CodCUPS+" - "+(e.cup?e.cup.descripcion:"El procedimiento no se encuentra registrado en la tabla de CUPS."),hint:null,icon:null,flexClass:"xs12"},{label:"Cantidad",text:null!==e.CanForm?e.CanForm.toString():"",hint:null,icon:null,flexClass:null},{label:"Frecuencia de Uso",text:e.CadaFreUso?e.CadaFreUso+(e.frecuencia_uso?" "+e.frecuencia_uso:""):null,hint:null,icon:null,flexClass:null},{label:"Duración Tratamiento",text:e.Cant?e.Cant+(e.periodo_duracion_tratamiento?" "+e.periodo_duracion_tratamiento:""):null,hint:null,icon:null,flexClass:null},{label:"Cantidad Total",text:null!==e.CantTotal?e.CantTotal.toString():"",hint:null,icon:null,flexClass:null},{label:"Justificación No PBS",text:e.JustNoPBS,hint:null,icon:null,flexClass:"xs12"},{label:"Indicaciones o recomendaciones para el paciente",text:e.IndRec,hint:null,icon:null,flexClass:"xs12"},{label:"Estado de la Junta de Profesionales",text:e.estado_junta,hint:null,icon:null,flexClass:null}],propsTable:[{label:"Código Causa Solicitud 11 ¿Tiene CUPS?",text:e.CausaS11,hint:"",icon:null,classTdItem:""},{label:"Código Causa Solicitud 12 ¿Es una combinación de CUPS?",text:e.CausaS12,hint:"",icon:null},{label:"Código Causa Solicitud 2 ¿El procedimiento se encuentra en fase experimental?",text:e.CausaS2,hint:"",icon:null,classTdItem:""},{label:"Código Causa Solicitud 3 ¿El Procedimiento se encuentra financiado con recursos de la UPC?",text:e.CausaS3,hint:"",icon:null,classTdItem:""},{label:"Código Causa Solicitud 4 ¿Utilizó lo existente en el Plan de Beneficios en Salud con cargo a la UPC?",text:e.CausaS4,hint:"",icon:null,classTdItem:""},{label:"Procedimiento PBS Utilizado",text:e.ProPBSUtilizado+" - "+(e.procedimiento_utilizado?e.procedimiento_utilizado.descripcion:"El procedimiento no se encuentra registrado en la tabla de CUPS."),hint:"",icon:null,classTdItem:""},{label:"Código Causa Solicitud 5 ¿Descartó lo existente en el Plan de Beneficios en Salud con cargo a la UPC?",text:e.CausaS5,hint:"",icon:null,classTdItem:""},{label:"Procedimiento PBS Descartado",text:e.ProPBSDescartado+" - "+(e.procedimiento_descartado?e.procedimiento_descartado.descripcion:"El procedimiento no se encuentra registrado en la tabla de CUPS."),hint:"",icon:null,classTdItem:""},{label:"Código Razón 51 - No existe alternativa ¿Lo descartó porque no existe alternativa en el PBS?",text:(1===e.RznCausaS51?"SI":0===e.RznCausaS51?"NO":e.RznCausaS51)+(e.DescRzn51?": "+e.DescRzn51:""),hint:"",icon:null,classTdItem:"pl-5"},{label:"Código Razón 52 - Evidencia científica disponible ¿Lo descartó porque tiene mejor evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?",text:(1===e.RznCausaS52?"SI":0===e.RznCausaS52?"NO":e.RznCausaS52)+(e.DescRzn52?": "+e.DescRzn52:""),hint:"",icon:null,classTdItem:"pl-5"},{label:"Código Causa Solicitud 6 ¿Existe evidencia científica disponible sobre seguridad, eficacia y efectividad clínica?",text:e.CausaS6,hint:"",icon:null,classTdItem:""},{label:"Código Causa Solicitud 7 ¿El Procedimiento prescrito se realizará en Colombia?",text:e.CausaS7,hint:"",icon:null,classTdItem:""}],propsBottom:[]})})},methods:{showDireccionamientos:function(t){this.RegistroDireccionamiento.parent=this.item,this.RegistroDireccionamiento.item=JSON.parse(a()(t)),this.RegistroDireccionamiento.show=!0}}}},2491:function(t,e,i){"use strict";function n(t){i(2492)}Object.defineProperty(e,"__esModule",{value:!0});var a=i(1305),l=i(2494),o=i(1),s=n,c=o(a.a,l.a,!1,s,"data-v-1375fe36",null);e.default=c.exports},2492:function(t,e,i){var n=i(2493);"string"==typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);i(456)("4231b698",n,!0,{})},2493:function(t,e,i){e=t.exports=i(455)(!0),e.push([t.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Procedimientos.vue",sourceRoot:""}])},2494:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("registro-direccionamientos",{attrs:{parent:t.RegistroDireccionamiento.parent,item:t.RegistroDireccionamiento.item,type:t.RegistroDireccionamiento.type},model:{value:t.RegistroDireccionamiento.show,callback:function(e){t.$set(t.RegistroDireccionamiento,"show",e)},expression:"RegistroDireccionamiento.show"}}),t._v(" "),i("v-expansion-panel",{staticClass:"v-expansion-panel-pi"},t._l(t.itemsListos,function(e,n){return i("v-expansion-panel-content",{key:"itemListo_"+n,staticClass:"v-expansion-header-dark"},[i("div",{attrs:{slot:"header"},slot:"header"},[i("v-list-tile",[i("v-list-tile-avatar",{staticClass:"white--text",attrs:{color:"primary"}},[t._v("\n            "+t._s(e.consecutivo)+"\n          ")]),t._v(" "),i("v-list-tile-content",{staticClass:"truncate-content"},[i("v-list-tile-title",{domProps:{innerHTML:t._s(e.title)}}),t._v(" "),i("v-list-tile-sub-title",[t._v(t._s(e.subtitle))])],1),t._v(" "),i("v-list-tile-action",[e.cup?t._e():i("v-tooltip",{attrs:{left:""}},[i("v-btn",{attrs:{slot:"activator",icon:"",ripple:""},on:{click:function(t){t.stopPropagation()}},slot:"activator"},[i("v-icon",{attrs:{color:"error"}},[t._v("warning")])],1),t._v(" "),i("span",[t._v("El procedimiento no se encuentra registrado en la tabla de CUPS.")])],1),t._v(" "),!t.item.NoPrescripcion||e.EstJM&&(1===e.EstJM||3===e.EstJM)?i("v-btn",{staticClass:"px-2",attrs:{color:"success",small:""},on:{click:function(i){return i.stopPropagation(),t.showDireccionamientos(e)}}},[i("v-icon",{staticClass:"mr-1"},[t._v("style")]),t._v("\n              Direccionamientos\n            ")],1):i("v-tooltip",{attrs:{top:""}},[i("v-btn",{staticClass:"px-2",attrs:{slot:"activator",color:"error",small:""},on:{click:function(t){t.stopPropagation()}},slot:"activator"},[i("v-icon",{staticClass:"mr-1"},[t._v("warning")]),t._v("\n                Direccionamientos\n              ")],1),t._v(" "),i("span",[t._v(t._s(e.EstJMText))])],1)],1)],1)],1),t._v(" "),i("v-card",[i("v-card-text",[i("v-container",{staticClass:"pa-0 ma-0",attrs:{fluid:"","grid-list-lg":""}},[i("v-layout",{attrs:{row:"",wrap:""}},[t._l(e.propsTop,function(e,a){return null!==e.text?i("input-detail-flex",{key:"prop"+n+"_"+a,attrs:{"flex-class":e.flexClass,label:e.label,text:0===e.text?"NO":1===e.text?"SI":e.text,hint:e.hint,"prepend-icon":e.icon}}):t._e()}),t._v(" "),i("v-flex",{staticClass:"pa-0 ma-0",attrs:{xs12:""}},[i("v-data-table",{staticClass:"elevation-0",attrs:{items:e.propsTable,"hide-actions":"","hide-headers":""},scopedSlots:t._u([{key:"items",fn:function(e){return[i("td",{staticClass:"text-xs-justify",class:e.item.classTdItem,domProps:{innerHTML:t._s(e.item.label)}}),t._v(" "),i("td",{staticClass:"text-xs-justify",domProps:{innerHTML:t._s(0===e.item.text?"NO":1===e.item.text?"SI":e.item.text)}})]}}],null,!0)}),t._v(" "),i("v-divider")],1),t._v(" "),t._l(e.propsBottom,function(e,a){return null!==e.text?i("input-detail-flex",{key:"propB"+n+"_"+a,attrs:{"flex-class":e.flexClass,label:e.label,text:0===e.text?"NO":1===e.text?"SI":e.text,hint:e.hint,"prepend-icon":e.icon}}):t._e()})],2)],1)],1)],1)],1)}),1)],1)},a=[],l={render:n,staticRenderFns:a};e.a=l}});
//# sourceMappingURL=357.bc7e6d7fb7b8c584e0fe.js.map