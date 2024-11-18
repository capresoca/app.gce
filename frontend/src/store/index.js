import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/general/user'
import auth from './modules/general/auth'
import nav from './modules/general/nav'
import tables from './modules/general/tables'
import snackbar from './modules/general/snackbar'
import dataTable from './modules/dataTable'
import createPersistedState from 'vuex-persistedstate'
Vue.use(Vuex)
export default new Vuex.Store({
  strict: true,
  modules: {
    user,
    auth,
    nav,
    snackbar,
    tables,
    dataTable
  },
  plugins: [createPersistedState({
    paths: ['nav', 'tables']
  })]
})
