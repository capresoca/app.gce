import axios from 'axios/index'
import {USER_REQUEST, USER_LOGOUT} from '@/store/modules/general/user'
import {NAV_RESET_ITEMS} from './nav'

export const AUTH_REQUEST = 'AUTH_REQUEST'
export const AUTH_SUCCESS = 'AUTH_SUCCESS'
export const AUTH_ERROR = 'AUTH_ERROR'
export const AUTH_LOGOUT = 'AUTH_LOGOUT'
export const AUTH_REAUTH_REQUEST = 'AUTH_REAUTH_REQUEST'

export default {
  state: {
    errorUser: null,
    token: localStorage.getItem('token') || '',
    status: '',
    hasLoandOnce: '',
    reauth: false
  },
  actions: {
    [AUTH_REQUEST]: ({commit, dispatch}, user) => {
      if (user.changeUser) {
        dispatch(NAV_RESET_ITEMS)
      }
      return new Promise((resolve, reject) => {
        commit(AUTH_REQUEST)
        axios.post('login', user)
          .then((response) => {
            localStorage.setItem('token', response.data.access_token)
            localStorage.setItem('token_type', response.data.token_type)
            commit(AUTH_SUCCESS, response.data)
            dispatch(USER_REQUEST)
            resolve()
          })
          .catch(error => {
            commit(AUTH_ERROR, error)
            localStorage.removeItem('token')
            reject(error)
          })
      })
    },
    [AUTH_LOGOUT]: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        commit(AUTH_LOGOUT)
        commit(USER_LOGOUT)
        localStorage.removeItem('token')
        localStorage.removeItem('vuex')
        localStorage.removeItem('token_type')
        resolve()
      })
    }
  },
  getters: {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status,
    reauth: state => state.reauth
  },
  mutations: {
    [AUTH_REQUEST]: (state) => {
      state.status = 'loading'
    },
    [AUTH_SUCCESS]: (state, resp) => {
      state.errorUser = null
      state.status = 'success'
      state.token = resp.access_token
      state.hasLoadedOnce = true
      axios.defaults.headers.common['Authorization'] = localStorage.getItem('token_type') + ' ' + localStorage.getItem('token')
      // state.reauth = false
    },
    [AUTH_ERROR]: (state, error) => {
      state.errorUser = error.response
      state.status = 'error'
      state.hasLoadedOnce = true
    },
    [AUTH_LOGOUT]: (state) => {
      state.token = ''
    },
    [AUTH_REAUTH_REQUEST]: (state, value) => {
      // console.log('el value ' + value)
      // console.log('antes ' + state.reauth)
      // localStorage.removeItem('token')
      // localStorage.removeItem('token_type')
      state.reauth = value
      // console.log('despues ' + state.reauth)
    }
  }
}
