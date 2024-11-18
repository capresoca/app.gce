import axios from 'axios/index'
import Vue from 'vue'
import {AUTH_LOGOUT} from '@/store/modules/general/auth'

export const USER_REQUEST = 'USER_REQUEST'
export const USER_LOGOUT = 'USER_LOGOUT'
export const USER_SUCCESS = 'USER_SUCCESS'
export const USER_ERROR = 'USER_ERROR'
export const USER_PERMISIONS = 'USER_PERMISIONS'
export const USER_DB = 'USER_DB'
export const FAVORITO_ASIGNAR = 'FAVORITO_ASIGNAR'

export default {
  state: {
    status: '',
    profile: {},
    componentes: [],
    rutas: [],
    db: ''
  },
  getters: {
    getInfoComponent: state => (ruta) => {
      return state.componentes.find(componente => componente.ruta === ruta)
    },
    getProfile: state => state.profile,
    getUserName: state => state.profile.name,
    getUserEmail: state => state.profile.email,
    getUserDb: state => state.db,
    isProfileLoaded: state => !!state.profile.name,
    getComponentes: (state) => (search) => {
      // console.log(state.componentes)
      return state.componentes.filter(componente => componente.titulo.toLowerCase().search(search.toLowerCase()) !== -1 || componente.modulo.toLowerCase().search(search.toLowerCase()) !== -1).reduce((value, key) => {
        (value[key['modulo']] = value[key['modulo']] || []).push(key)
        return value
      }, {})
    },
    getComponentesFavoritos: (state) => () => {
      return state.componentes.filter(componente => componente.view_menu && componente.favorito).reduce((value, key) => {
        (value[key['modulo']] = value[key['modulo']] || []).push(key)
        return value
      }, {})
    }
  },
  mutations: {
    [USER_REQUEST]: (state) => {
      state.status = 'loading'
    },
    [USER_SUCCESS]: (state, resp) => {
      state.status = 'success'
      Vue.set(state, 'profile', resp)
    },
    [USER_ERROR]: (state) => {
      state.status = 'error'
    },
    [USER_LOGOUT]: (state) => {
      state.profile = {}
    },
    [USER_PERMISIONS]: (state, data) => {
      state.componentes = data
    },
    [FAVORITO_ASIGNAR]: (state, componenteId) => {
      let componente = state.componentes.find(x => x.id_componente === componenteId)
      if (componente) {
        state.componentes.find(x => x.id_componente === componenteId).favorito = !componente.favorito
      }
    },
    [USER_DB]: (state, db) => {
      state.db = db
    }
  },
  actions: {
    [FAVORITO_ASIGNAR]: ({commit, dispatch}, componenteId) => {
      commit(FAVORITO_ASIGNAR, componenteId)
      axios.post(`favorito/${componenteId}`)
        .catch(() => {
          commit(FAVORITO_ASIGNAR, componenteId)
        })
    },
    [USER_REQUEST]: ({commit, dispatch}) => {
      commit(USER_REQUEST)
      axios.post('me')
        .then((response) => {
          commit(USER_SUCCESS, response.data.user)
          commit(USER_PERMISIONS, response.data.permisos)
          commit(USER_DB, response.data.db)
        }).catch(() => {
          commit()
          dispatch(AUTH_LOGOUT)
        // commit(USER_LOGOUT)
        })
    }
  }
}
