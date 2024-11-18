// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from '@/router/index'
import VueAxios from 'vue-axios'
import axios from 'axios'
import Vuetify from 'vuetify'
import store from '@/store/index'
import colors from 'vuetify/es5/util/colors'
import '../node_modules/vuetify/src/stylus/main.styl'
import Vuelidate from 'vuelidate'
import moment from 'moment'
import esValidate from 'vee-validate/dist/locale/es'
import VeeValidate, { Validator } from 'vee-validate'
import lodash from 'lodash'
import vueSignature from 'vue-signature'
import VueCodemirror from 'vue-codemirror'
import 'codemirror/lib/codemirror.css'
import 'codemirror/mode/sql/sql.js'
import 'codemirror/theme/solarized.css'

import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import FlagIcon from 'vue-flag-icon'
import {AUTH_REAUTH_REQUEST} from './store/modules/general/auth'
import {USER_REQUEST} from './store/modules/general/user'
import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
import VueNumeric from 'vue-numeric'
import DataTable from '@/components/general/DataTable'
import money from 'v-money'
import '@fortawesome/fontawesome-free/css/all.css'
import VueLodash from 'vue-lodash'
import es from 'vuetify/es5/locale/es'
// global components
import GlobalComponents from './globalComponents'

window.lodash = lodash
Vue.use(VueLodash, {name: 'lodash'})
Vue.use(VueNumeric)
Vue.use(vueSignature)
Vue.use(Loading)
Validator.localize('es', esValidate)
Vue.use(VeeValidate, {locale: 'es'})
Vue.use(Vuelidate)
moment.locale('es')
Vue.prototype.moment = moment
Validator.extend('fechaValida', {
  validate: (value, prop) => new Promise(resolve => {
    let response = {valido: true, mensaje: null}
    if (value) {
      let formato = prop[0] ? prop[0] : 'YYYY-MM-DD'
      if (moment(value, 'YYYY-MM-DD', true).isValid()) value = moment(value, 'YYYY-MM-DD', true).format(formato)
      response = moment(value, formato, true).isValid() ? {valido: true, mensaje: null} : {valido: false, mensaje: `La fecha es invalida, el formato debe ser: ${formato}`}
    }
    return resolve({
      valid: response.valido,
      data: {
        message: response.mensaje
      }
    })
  }),
  getMessage: (field, params, data) => data.message
})
Validator.extend('dateTimeBetween', {
  validate: (value, prop) => new Promise(resolve => {
    let response = {valido: true, mensaje: null}
    if (value) {
      const defaultFormat = 'YYYY-MM-DD HH:mm'
      let formato = prop[2] ? prop[2] : defaultFormat
      let inicio = moment(prop[0]).isValid() ? moment(prop[0]).format(formato) : moment('1900-01-01').format(formato)
      let fin = moment(prop[1]).isValid() ? moment(prop[1]).format(formato) : moment().format(formato)
      response = moment(value).isBetween(inicio, fin, null, '[]') ? {valido: true, mensaje: null} : {valido: false, mensaje: `La fecha debe estar entre ${inicio} y ${fin}`}
    }
    return resolve({
      valid: response.valido,
      data: {
        message: response.mensaje
      }
    })
  }),
  getMessage: (field, params, data) => data.message
})
Validator.extend('fileNameContain', {
  validate: (value, prop) => new Promise(resolve => {
    let response = {valido: true, mensaje: null}
    if (value) {
      if (typeof value === 'string') {
        response = value.indexOf(prop[0]) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `El nombre del archivo debe contener ${prop[0]}`}
      } else {
        response = value.name.indexOf(prop[0]) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `El nombre del archivo debe contener ${prop[0]}`}
      }
    }
    return resolve({
      valid: response.valido,
      data: {
        message: response.mensaje
      }
    })
  }),
  getMessage: (field, params, data) => data.message
})
Validator.extend('fileExtension', {
  validate: (value, prop) => new Promise(resolve => {
    let response = {valido: true, mensaje: null}
    if (value) {
      if (typeof value === 'string') {
        response = value.toLowerCase().indexOf(prop[0].toLowerCase()) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `La extensión del archivo debe ser: ${prop[0]}`}
      } else {
        response = value.name.toLowerCase().indexOf(prop[0].toLowerCase()) > -1 ? {valido: true, mensaje: null} : {valido: false, mensaje: `La extensión del archivo debe ser: ${prop[0]}`}
      }
    }
    return resolve({
      valid: response.valido,
      data: {
        message: response.mensaje
      }
    })
  }),
  getMessage: (field, params, data) => data.message
})
Validator.extend('verify_direccion', {
  getMessage: field => `El campo dirección no debe contener carácteres especiales.`,
  validate: value => {
    var strongRegex = new RegExp('^[a-zA-Z0-9_ ]*$')
    return strongRegex.test(value)
  }
})
Validator.extend('vCieCups', {
  validate: (value, prop) => new Promise((resolve) => {
    if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
      if (prop[0] !== 'null') {
        return resolve(new Promise((resolve) => {
          axios.get(`autorizaciones/valida_edades/${prop[0]}?${prop[1]}=${value.codigo}`)
            .then((response) => {
              resolve(response.data.respuesta ? {valid: false, data: {message: response.data.respuesta}} : {valid: true, data: {message: ''}})
            })
            .catch(e => {
              store.commit('SNACK_ERROR_LIST', {expression: ' al realizar la validación del item seleccionado. ', error: e})
              resolve({valid: false, data: {message: `Error al realizar la validación del item seleccionado.`}})
            })
        })
        )
      } else {
        return resolve({
          valid: false,
          data: {
            message: 'Se requiere seleccionar el afiliado para realizar la validación.'
          }
        })
      }
    }
  }),
  getMessage: (field, params, data) => data.message
})
Validator.extend('vServicioAnio', {
  validate: (value, prop) => new Promise((resolve) => {
    if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
      if ((prop[0] !== null) && (typeof prop[0] !== 'undefined') && (prop[0] !== '')) {
        if ((prop[1] !== null) && (typeof prop[1] !== 'undefined') && (prop[1] !== '')) {
          return resolve(new Promise((resolve) => {
            axios.get(`autorizaciones/valida_autorizacionxanio?idAfiliado=${prop[0]}&cie10=${prop[1]}&refcup=${value.codigo}`)
              .then((response) => {
                if (response.data.advertencia) store.commit('SNACK_SHOW', {msg: response.data.advertencia, color: 'orange'})
                resolve(response.data.respuesta ? {valid: false, data: {message: response.data.respuesta}} : {valid: true, data: {message: ''}})
              })
              .catch(e => {
                store.commit('SNACK_ERROR_LIST', {expression: ' al realizar la validación del item seleccionado. ', error: e})
                resolve({valid: false, data: {message: `Error al realizar la validación del item seleccionado.`}})
              })
          })
          )
        } else {
          return resolve({
            valid: false,
            data: {
              message: 'Se requiere seleccionar el diagnóstico principal.'
            }
          })
        }
      } else {
        return resolve({
          valid: false,
          data: {
            message: 'Se requiere seleccionar el afiliado.'
          }
        })
      }
    }
  }),
  getMessage: (field, params, data) => data.message
})

Vue.use(money, {precision: 2, decimal: ',', thousands: '.'})
Vue.use(VueCodemirror)
Vue.use(Vuetify, {
  lang: {
    locales: { es },
    current: 'es'
  },
  iconfont: 'fa, mdi',
  theme: {
    primary: colors.blue.base,
    success: colors.green.base,
    warning: colors.yellow.base,
    danger: colors.red.base,
    secondary: colors.grey.darken1,
    accent: colors.pink.accent2,
    error: colors.red.accent3
  }
})
Vue.use(FlagIcon)

axios.defaults.baseURL = 'http://' + window.location.hostname + (window.location.port === '8080' ? ':8000' : ':7000') + '/api'
const CancelToken = axios.CancelToken
const source = CancelToken.source()
axios.interceptors.request.use(function (config) {
  // Do something before request is sent
  config.cancelToken = source.token
  return config
})

// axios.defaults.baseURL = 'http://35.192.57.156/api'
Vue.config.productionTip = false
axios.interceptors.response.use(undefined, (error) => {
  // Do something with response error
  const originalRequest = error.config
  if (error.response.status === 401) {
    return axios.post('refresh')
      .then(data => {
        localStorage.setItem('token', data.data.access_token)
        axios.defaults.headers.common['Authorization'] = localStorage.getItem('token_type') + ' ' + localStorage.getItem('token')
        originalRequest.headers['Authorization'] = localStorage.getItem('token_type') + ' ' + localStorage.getItem('token')
        return axios(originalRequest)
      }).catch(e => {
        store.commit(AUTH_REAUTH_REQUEST, true)
      })
  }
  if (error.response.status === 403) {
    // delete originalRequest.cancelToken
    // source.cancel('Operation canceled by the user.')
    store.commit(AUTH_REAUTH_REQUEST, true)
  }
  return Promise.reject(error)
})

if (localStorage.getItem('token') && localStorage.getItem('token_type')) {
  axios.defaults.headers.common['Authorization'] = localStorage.getItem('token_type') + ' ' + localStorage.getItem('token')
  store.dispatch(USER_REQUEST)
}
window.toProperty = (obj, array) => {
  while (array.length) {
    obj = obj[array.shift()]
  }
  return obj
}

window.fileDownload = async (url, fileName) => {
  return new Promise((resolve) => {
    if (fileName) {
      axios.get(url, {responseType: 'blob'})
        .then((response) => {
          let reader = new FileReader()
          reader.onload = function (event) {
            let save = document.createElement('a')
            save.href = event.target.result
            save.target = '_blank'
            save.download = fileName
            save.dispatchEvent(new MouseEvent('click', {
              'view': window,
              'bubbles': true,
              'cancelable': true
            }))
          }
          reader.readAsDataURL(response.data)
          resolve(true)
        }).catch(e => {
          store.commit(SNACK_ERROR_LIST, {expression: ' al intentar descargar el archivo. ', error: e})
          resolve(false)
        })
    } else {
      let save = document.createElement('a')
      save.href = url
      // save.target = '_blank'
      save.dispatchEvent(new MouseEvent('click', {
        'view': window,
        'bubbles': true,
        'cancelable': true
      }))
      resolve(true)
    }
  })
}
Vue.use(VueAxios, axios)
/* eslint-disable no-new */

Vue.mixin({
  methods: {
    sum_by: (valor, str) => {
      return lodash.sumBy(valor, str)
    },
    currency: (valor, decimal = 2) => {
      return new Intl.NumberFormat('es-CO', {style: 'currency', currency: 'COP', minimumFractionDigits: decimal}).format(valor)
    },
    async filePrint (ruta, externo = true) {
      return new Promise(resolve => {
        axios.get(`firmar-ruta?nombre_ruta=${ruta}`)
          .then(response => {
            window.open(response.data, externo ? '_blank' : '_self')
            resolve(true)
          })
          .catch(e => {
            store.commit(SNACK_ERROR_LIST, {expression: ' al generar el archivo. ', error: e})
            resolve(false)
          })
      })
    },
    goRuta (url, target = '_blank') {
      window.open(url, target)
    },
    async fileDownload (ruta) {
      return new Promise(resolve => {
        axios.get(`firmar-ruta?nombre_ruta=${ruta}`)
          .then(response => {
            window.fileDownload(response.data)
            resolve(true)
          })
          .catch(e => {
            store.commit(SNACK_ERROR_LIST, {expression: ' al descargar el archivo. ', error: e})
            resolve(false)
          })
      })
    },
    str_pad (input, padLength, padString, padType) {
      let half = ''
      let padToGo
      let _strPadRepeater = (s, len) => {
        let collect = ''
        while (collect.length < len) { collect += s }
        collect = collect.substr(0, len)
        return collect
      }
      input += ''
      padString = padString !== undefined ? padString : '0'
      if (padType !== 'STR_PAD_LEFT' && padType !== 'STR_PAD_RIGHT' && padType !== 'STR_PAD_BOTH') padType = 'STR_PAD_LEFT'
      if ((padToGo = padLength - input.length) > 0) {
        switch (padType) {
          case 'STR_PAD_LEFT':
            input = _strPadRepeater(padString, padToGo) + input
            break
          case 'STR_PAD_RIGHT':
            input = input + _strPadRepeater(padString, padToGo)
            break
          case 'STR_PAD_BOTH':
            half = _strPadRepeater(padString, Math.ceil(padToGo / 2))
            input = half + input + half
            input = input.substr(0, padLength)
            break
        }
      }
      return input
    },
    clone (data) {
      return JSON.parse(JSON.stringify(data))
    },
    formDate (date) {
      if (!date) return null
      var [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    formDatePicker (date) {
      if (!date) return null
      var [day, month, year] = date.split('./')
      return `${year}-${month}-${day}`
    }
  }
})
Vue.use(GlobalComponents)
new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: {
    App,
    DataTable
  }
})

Vue.filter('textTruncate', (text, count) => {
  return text.length > count ? text.substr(0, (count - 3)) + '...' : text
})

Vue.filter('numberFormat', (value, decimals, character, separators) => {
  decimals = decimals >= 0 ? parseInt(decimals, 0) : 2
  separators = separators || ['.', '.', ',']
  // Number.isInteger(value)
  var number = (value % 1 === 0) ? (parseFloat(value) || 0).toFixed(decimals) : (value).toFixed(decimals)
  if (number.length <= (4 + decimals)) return number.replace('.', separators[separators.length - 1])
  var parts = number.split(/[-.]/)
  value = parts[parts.length > 1 ? parts.length - 2 : 0]
  var result = value.substr(value.length - 3, 3) + (parts.length > 1 ? separators[separators.length - 1] + parts[parts.length - 1] : '')
  var start = value.length - 6
  var idx = 0
  while (start > -3) {
    result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start)) + separators[idx] + result
    idx = (++idx) % 2
    start -= 3
  }
  return (character === '$' ? '$ ' : '') + (parts.length === 3 ? '-' : '') + result + (character === '%' ? ' %' : '')
})

Vue.directive('upper', {
  update: (el, binding, vnode) => {
    let arrObjectString = binding.value.split('.')
    let prop = arrObjectString.pop()
    let obj = window.toProperty(vnode.context, arrObjectString)
    if (vnode.fnContext.$set(obj, prop, obj[prop])) vnode.fnContext.$set(obj, prop, obj[prop].toUpperCase())
  }
})

Vue.directive('number', {
  update: async (el, binding, vnode) => {
    let arrObjectString = binding.value.split('.')
    let prop = arrObjectString.pop()
    let obj = window.toProperty(vnode.context, arrObjectString)
    let numeros = '0123456789'
    if (obj[prop]) {
      vnode.fnContext.$set(obj, prop, await obj[prop].split('').filter(x => numeros.includes(x)).join(''))
    }
  }
})

// eslint-disable-next-line
// function coFormatNumber (price) {
//   let formatter = new Intl.NumberFormat('es-CO', {style: 'currency', currency: 'COP'})
//   return formatter.format(price)
// }

window.io = require('socket.io-client')
