webpackJsonp([76],{1290:function(e,t,n){"use strict";var a=n(13),r=n.n(a),i=n(14),o=n.n(i),s=n(7),l=n.n(s),u=n(857),c=n.n(u),d=n(58),m=n(59);t.a={name:"Reportes",components:{ToolbarList:function(){return new Promise(function(e){e()}).then(n.bind(null,247))},VDatetimePicker:function(){return n.e(302).then(n.bind(null,754))},Loading:d.default},data:function(){return{loading:!0,loadingPrueba:!1,reportesBase:[],panel:[],openAll:!1,search:"",selected:null,sentenciaGo:"",urlReporte:null,minDate:"1900-01-01",maxDate:(new Date).toISOString().substr(0,10),filterMunicipios:function(e,t){var n=function(e){return null!=e?e:""},a=n(e.nombre+" "+e.nombre_departamento),r=n(t);return a.toString().toLowerCase().indexOf(r.toString().toLowerCase())>-1}}},computed:{infoComponent:function(){return this.$store.getters.getInfoComponent("reportes")},items:function(){var e=this;return this.search||(this.search=""),this.reportesBase.filter(function(t){return-1!==t.nombre.toLowerCase().search(e.search.toLowerCase())||-1!==t.modulo.nombre.toLowerCase().search(e.search.toLowerCase())}).reduce(function(e,t){return(e[t.modulo.nombre]=e[t.modulo.nombre]||[]).push(t),e},{})},municipios:function(){return m.b.getters.complementosFormAfiliados.municipios}},watch:{openAll:function(e){var t=this;this.panel=e?c()(e).forEach(function(e){t.panel.push(!0)}):[]},items:function(e,t){var n=this;c()(t).length&&c()(e).length&&setTimeout(function(){n.panel=[],c()(e).forEach(function(e){n.panel.push(!0)})},300)}},created:function(){this.getData()},methods:{postuladorInput:function(e,t){t[t.ref]=e?e.id:null},assign:function(e){this.selected=JSON.parse(l()(e)),this.$vuetify.goTo("#flag-form-reporte",{selector:"#flag-form-reporte",duration:300,offset:-120,easing:"easeInOutQuad"})},refresh:function(){var e=this;this.selected.variables.forEach(function(t){e.$nextTick(function(){t[t.ref]=null})}),this.$validator.reset()},getData:function(){var e=this;return o()(r.a.mark(function t(){return r.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:e.loading=!0,e.axios.get("reportes-disponibles").then(function(t){""!==t.data&&(console.log("DISPONIBLES",t.data.data),t.data.data.forEach(function(t){e.reportesBase=window.lodash.uniqBy(e.reportesBase.concat(t.reportes),"id")}),e.reportesBase.forEach(function(t){return t.variables.forEach(function(t){"date"!==t.type&&"period"!==t.type||e.$set(t,"menu",!1),"postulador"===t.type&&e.$set(t,"objEnt",null)})}),e.loading=!1)}).catch(function(t){e.loading=!1,e.$store.commit("SNACK_ERROR_LIST",{expression:" al traer los reportes disponibles. ",error:t})});case 2:case"end":return t.stop()}},t,e)}))()},ejecutarReporte:function(){var e=this;return o()(r.a.mark(function t(){return r.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:e.$validator.validateAll("formPrueba").then(function(t){if(t){e.loadingPrueba=!0;var n=JSON.parse(l()(e.selected.sentencia));e.selected.variables.forEach(function(e){n=n.replace(new RegExp(e.ref,"g"),"number"!==e.type?'"'+e[e.ref]+'"':e[e.ref])}),e.sentenciaGo=n,e.axios.get("firmar-ruta?nombre_ruta=ejecutarReporte").then(function(t){""!==t.data&&(e.urlReporte=t.data,e.axios.post(e.urlReporte,{sentencia:e.sentenciaGo}).then(function(t){var n=new Blob([t.data],{type:"text/plain;charset=utf-8"}),a=document.createElement("a");a.href=URL.createObjectURL(n);var r=new Date;a.download="Reporte-"+r.getFullYear()+"-"+(r.getMonth()+1)+"-"+r.getDate()+".txt",a.click(),URL.revokeObjectURL(e.urlReporte),e.loadingPrueba=!1}).catch(function(t){e.loadingPrueba=!1,e.$store.commit("SNACK_ERROR_LIST",{expression:" al descargar el archivo. ",error:t})}))}).catch(function(t){e.loadingPrueba=!1,e.$store.commit("SNACK_ERROR_LIST",{expression:" al ejecutar el reporte. ",error:t})})}});case 1:case"end":return t.stop()}},t,e)}))()}}}},2444:function(e,t,n){var a=n(2445);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);n(456)("19693d4f",a,!0,{})},2445:function(e,t,n){t=e.exports=n(455)(!0),t.push([e.i,"","",{version:3,sources:[],names:[],mappings:"",file:"Reportes.vue",sourceRoot:""}])},2446:function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-card",[n("loading",{model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}}),e._v(" "),n("toolbar-list",{attrs:{info:e.infoComponent,title:"Reportes",subtitle:"Generación"}}),e._v(" "),n("v-layout",{attrs:{"justify-space-between":""}},[n("v-flex",{attrs:{xs12:"",sm12:"",md4:""}},[n("v-text-field",{staticClass:"pa-2",attrs:{"append-icon":"search",solo:"",clearable:"",label:"Filtrar reportes...",type:"text","hide-details":""},model:{value:e.search,callback:function(t){e.search=t},expression:"search"}}),e._v(" "),Object.entries(e.items).length?n("v-expansion-panel",{attrs:{expand:""},model:{value:e.panel,callback:function(t){e.panel=t},expression:"panel"}},e._l(e.items,function(t,a){return n("v-expansion-panel-content",{key:a},[n("div",{attrs:{slot:"header"},slot:"header"},[e._v("\n            "+e._s(a)+"\n            "),n("v-chip",{attrs:{label:"",small:"",color:"primary","text-color":"white"}},[e._v("\n              "+e._s(t.length)+" reporte"+e._s(1===t.length?"":"s")+"\n            ")])],1),e._v(" "),n("v-card",[n("v-list",[e._l(t,function(a,r){return[n("v-list-tile",{key:r,class:e.selected&&a.id===e.selected.id?"grey lighten-2":"",attrs:{avatar:""},on:{click:function(t){return e.assign(a)}}},[n("v-list-tile-avatar",[n("v-icon",{class:a.modulo.color+" white--text"},[e._v("assignment")])],1),e._v(" "),n("v-list-tile-content",[n("v-list-tile-title",[e._v(e._s(a.nombre))]),e._v(" "),n("v-list-tile-sub-title",[e._v("Actualizado: "+e._s(a.updated_at?e.moment(a.updated_at).format("DD/MM/YYYY [a las ] HH:mm"):""))])],1)],1),e._v(" "),r<t.length-1?n("v-divider",{attrs:{inset:""}}):e._e()]})],2)],1)],1)}),1):n("div",{staticClass:"title grey--text text-xs-center font-weight-light pa-2"},[e._v("\n        No hay coincidencias de busqueda. "),n("v-icon",[e._v("sentiment_very_dissatisfied")])],1)],1),e._v(" "),n("v-flex",{attrs:{xs12:"",sm12:"",md8:"","text-xs-center":""}},[n("div",{attrs:{id:"flag-form-reporte"}}),e._v(" "),n("v-scroll-y-transition",{attrs:{mode:"out-in"}},[e.selected?n("v-card",{key:e.selected.id,staticClass:"pt-4 mx-auto",attrs:{flat:""}},[n("v-card-text",[n("v-avatar",{staticStyle:{height:"0px !important"},attrs:{size:"large"}},[n("v-icon",{attrs:{large:"",color:e.selected.modulo.color}},[e._v("\n                assignment\n              ")])],1),e._v(" "),n("h3",{staticClass:"headline mb-2"},[e._v("\n              "+e._s(e.selected.nombre)+"\n            ")]),e._v(" "),n("div",{staticClass:"blue-grey--text mb-2"},[e._v("Actualizado: "+e._s(e.selected.updated_at?e.moment(e.selected.updated_at).format("DD/MM/YYYY [a las ] HH:mm"):""))]),e._v(" "),n("p",{staticClass:"blue-grey--text subheading font-weight-bold"},[e._v(e._s(e.selected.descripcion))])],1),e._v(" "),n("v-divider"),e._v(" "),n("v-card-text",[n("loading",{model:{value:e.loadingPrueba,callback:function(t){e.loadingPrueba=t},expression:"loadingPrueba"}}),e._v(" "),n("v-form",{attrs:{"data-vv-scope":"formPrueba"},on:{submit:function(t){return t.preventDefault(),e.ejecutarReporte(t)}}},[e.selected&&e.selected.variables&&e.selected.variables.length?n("v-container",{staticClass:"pa-0",attrs:{"grid-list-md":""}},[n("v-layout",{attrs:{row:"",wrap:""}},e._l(e.selected.variables,function(t,a){return n("v-flex",{key:"input"+a,attrs:{xs12:"",sm6:"",md6:1!==e.selected.variables.length,md12:1===e.selected.variables.length}},[t.type&&t.label?["text"===t.type?n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],key:"input-text"+a,attrs:{name:"input-text"+a,label:t.label,"data-vv-as":t.label,"error-messages":e.errors.collect("input-text"+a)},model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,n)},expression:"input[input.ref]"}}):e._e(),e._v(" "),"number"===t.type?n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"decimal|required",expression:"'decimal|required'"}],key:"input-number"+a,attrs:{name:"input-number"+a,label:t.label,type:"number","data-vv-as":t.label,"error-messages":e.errors.collect("input-number"+a)},model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,e._n(n))},expression:"input[input.ref]"}}):e._e(),e._v(" "),"date"===t.type?n("v-menu",{key:"menu-date"+a,ref:"menuDate"+a,refInFor:!0,attrs:{"return-value":t[t.ref],"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","min-width":"290px"},on:{"update:returnValue":function(n){return e.$set(t,t.ref,n)},"update:return-value":function(n){return e.$set(t,t.ref,n)}},model:{value:t.menu,callback:function(n){e.$set(t,"menu",n)},expression:"input.menu"}},[n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY-MM-DD|date_between:"+e.minDate+","+e.maxDate+",true",expression:"'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"}],attrs:{slot:"activator",label:t.label,"prepend-icon":"event",name:"input-menuDate"+a,"data-vv-as":t.label,"error-messages":e.errors.collect("input-menuDate"+a),readonly:""},slot:"activator",model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,n)},expression:"input[input.ref]"}}),e._v(" "),n("v-date-picker",{attrs:{locale:"es-co",max:e.maxDate,min:e.minDate},on:{input:function(n){e.$refs["menuDate"+a][0].save(t[t.ref])},change:function(){var t=e.$validator.errors.items.findIndex(function(e){return e.field==="input-menuDate"+a});e.$validator.errors.items.splice(-1!==t?t:0,-1!==t?1:0)}},model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,n)},expression:"input[input.ref]"}})],1):e._e(),e._v(" "),"dateTime"===t.type?n("v-datetime-picker",{key:"dateTime"+a,attrs:{label:t.label,datetime:e.moment().format("YYYY-MM-DD HH:mm"),timeData:t[t.ref]},on:{input:function(e){t[t.ref]=e}}}):e._e(),e._v(" "),"period"===t.type?n("v-menu",{key:"menu-period"+a,ref:"menuPeriod"+a,refInFor:!0,attrs:{"return-value":t[t.ref],"close-on-content-click":!1,"nudge-right":40,lazy:"",transition:"scale-transition","offset-y":"","full-width":"","min-width":"290px"},on:{"update:returnValue":function(n){return e.$set(t,t.ref,n)},"update:return-value":function(n){return e.$set(t,t.ref,n)}},model:{value:t.menu,callback:function(n){e.$set(t,"menu",n)},expression:"input.menu"}},[n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required|date_format:YYYY-MM|date_between:"+e.moment(e.minDate).format("YYYY-MM")+","+e.moment(e.maxDate).format("YYYY-MM")+",true",expression:"'required|date_format:YYYY-MM|date_between:' + moment(minDate).format('YYYY-MM') + ',' + moment(maxDate).format('YYYY-MM') + ',true'"}],attrs:{slot:"activator",label:t.label,"prepend-icon":"event",name:"input-menuPeriod"+a,"data-vv-as":t.label,"error-messages":e.errors.collect("input-menuPeriod"+a),readonly:""},slot:"activator",model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,n)},expression:"input[input.ref]"}}),e._v(" "),n("v-date-picker",{attrs:{locale:"es-co",max:e.maxDate,min:e.minDate,type:"month"},on:{input:function(n){e.$refs["menuPeriod"+a][0].save(t[t.ref])},change:function(){var t=e.$validator.errors.items.findIndex(function(e){return e.field==="input-menuPeriod"+a});e.$validator.errors.items.splice(-1!==t?t:0,-1!==t?1:0)}},model:{value:t[t.ref],callback:function(n){e.$set(t,t.ref,n)},expression:"input[input.ref]"}})],1):e._e(),e._v(" "),"postulador"===t.type&&"municipios"===t.entidades?n("v-autocomplete",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:t.label,"item-value":"id","item-text":"nombre",items:e.municipios,hint:t.objEnt?"Departamento: "+t.objEnt.nombre_departamento:null,"persistent-hint":"","error-messages":e.errors.collect(t.label),filter:e.filterMunicipios,"return-object":"",clearable:""},on:{change:function(n){return e.postuladorInput(n,t)}},scopedSlots:e._u([{key:"item",fn:function(t){return[[n("v-list-tile-content",[n("v-list-tile-title",{domProps:{innerHTML:e._s(t.item.nombre)}}),e._v(" "),n("v-list-tile-sub-title",{domProps:{innerHTML:e._s(t.item.nombre_departamento)}})],1)]]}}],null,!0),model:{value:t.objEnt,callback:function(n){e.$set(t,"objEnt",n)},expression:"input.objEnt"}}):e._e()]:e._e()],2)}),1)],1):e._e()],1)],1),e._v(" "),n("v-divider"),e._v(" "),n("v-card-actions",[n("v-btn",{on:{click:e.refresh}},[e._v("Limpiar")]),e._v(" "),n("v-spacer"),e._v(" "),n("v-btn",{attrs:{color:"primary"},on:{click:e.ejecutarReporte}},[n("v-icon",[e._v("flash_on")]),e._v("\n              Ejecutar reporte\n            ")],1)],1)],1):n("div",{staticClass:"title grey--text text--lighten-1 font-weight-light pt-4",staticStyle:{"align-self":"center"}},[e._v("\n          Seleccione un reporte\n        ")])],1)],1)],1)],1)},r=[],i={render:a,staticRenderFns:r};t.a=i},693:function(e,t,n){"use strict";function a(e){n(2444)}Object.defineProperty(t,"__esModule",{value:!0});var r=n(1290),i=n(2446),o=n(1),s=a,l=o(r.a,i.a,!1,s,"data-v-1b717766",null);t.default=l.exports},857:function(e,t,n){e.exports={default:n(858),__esModule:!0}},858:function(e,t,n){n(859),e.exports=n(2).Object.entries},859:function(e,t,n){var a=n(9),r=n(256)(!0);a(a.S,"Object",{entries:function(e){return r(e)}})}});
//# sourceMappingURL=76.243aaa7332969b6bdd90.js.map