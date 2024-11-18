export const NAV_DRAWABLE = 'NAV_DRAWABLE'
export const NAV_ADD_ITEM = 'NAV_ADD_ITEM'
export const NAV_REMOVE_ITEM = 'NAV_REMOVE_ITEM'
export const NAV_SET_CURRENT_ITEM = 'NAV_SET_CURRENT_ITEM'
export const NAV_SET_MENU_ITEM_TITLE = 'NAV_SET_MENU_ITEM_TITLE'
export const NAV_NEW_CURRENT_ITEM = 'NAV_NEW_CURRENT_ITEM'
export const NAV_SET_MENU_ITEM = 'NAV_SET_MENU_ITEM'
export const NAV_RESPONSE_CONTENT_ITEM = 'NAV_RESPONSE_CONTENT_ITEM'
export const NAV_RESET_ITEMS = 'NAV_RESET_ITEMS'

export default {
  state: {
    drawable: false,
    items: [
      {
        titulo: 'Bienvenido',
        // ruta: 'terceros',
        ruta: 'home',
        id: '3'
      }
    ],
    currentItem: 'tab-3',
    menuItem: false,
    compuerta: false
  },
  actions: {
    [NAV_RESET_ITEMS]: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        commit(NAV_RESET_ITEMS)
        resolve()
      })
    },
    [NAV_DRAWABLE]: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        commit(NAV_DRAWABLE)
        resolve()
      })
    },
    [NAV_ADD_ITEM]: ({commit, dispatch}, data) => {
      //
      return new Promise((resolve, reject) => {
        commit(NAV_ADD_ITEM, data)
        resolve()
      })
    },
    [NAV_REMOVE_ITEM]: ({commit, dispatch}, data) => {
      return new Promise((resolve, reject) => {
        commit(NAV_REMOVE_ITEM, data)
        resolve()
      })
    },
    [NAV_RESPONSE_CONTENT_ITEM]: ({getters, commit, dispatch}, data) => {
      return new Promise((resolve, reject) => {
        const theItem = getters.NAV_NEW_CURRENT_ITEM({itemId: data.itemId, delegado: data.delegado})
        commit(NAV_REMOVE_ITEM)
        data.contexto.$nextTick(async () => {
          await commit(NAV_SET_CURRENT_ITEM, theItem)
        })
        resolve()
      })
    }
  },
  mutations: {
    [NAV_DRAWABLE]: (state) => {
      state.drawable = !state.drawable
    },
    [NAV_ADD_ITEM]: (state, data) => {
      const datos = JSON.parse(JSON.stringify(data))
      datos.id = Math.random().toString(36).substring(2)
      if (datos.required) {
        state.items.push(datos)
      } else if (datos.parametros && datos.parametros.tabOrigin) {
        state.items.splice(state.items.indexOf(state.items.find(item => item.id === datos.parametros.tabOrigin)) + 1, 0, datos)
      } else if (state.items.find(item => item.ruta === datos.ruta)) {
        if (state.items.find(item => (item.ruta === datos.ruta) && (!item.required))) {
          datos.id = state.items.find(item => (item.ruta === datos.ruta) && (!item.required)).id
        } else {
          state.items.push(datos)
        }
      } else {
        state.items.push(datos)
      }
      state.currentItem = 'tab-' + datos.id
      state.menuItem = false
      state.compuerta = !state.compuerta
    },
    [NAV_REMOVE_ITEM]: (state, itemRemove) => {
      state.items.splice(state.items.indexOf(state.items.find(item => item.id === (itemRemove ? itemRemove.id : state.currentItem.split('tab-')[1]))), 1)
    },
    [NAV_SET_CURRENT_ITEM]: (state, currentItem) => {
      state.currentItem = currentItem
    },
    [NAV_SET_MENU_ITEM_TITLE]: (state, title) => {
      state.items.find(item => ('tab-' + item.id) === state.currentItem).titulo = title
    },
    [NAV_SET_MENU_ITEM]: (state) => {
      state.menuItem = !state.menuItem
    },
    [NAV_RESET_ITEMS]: (state) => {
      state.currentItem = 'tab-3'
      state.items = [
        {
          titulo: 'Bienvenido',
          ruta: 'home',
          id: '3'
        }
      ]
    }
  },
  getters: {
    drawable: state => state.drawable,
    currentItem: state => state.currentItem,
    items: state => state.items,
    [NAV_NEW_CURRENT_ITEM] (state) {
      return ({itemId, indelegado}) => {
        if (!indelegado) {
          if (state.items.find(item => item.id === itemId)) {
            return 'tab-' + itemId
          }
        }
        itemId = state.currentItem.split('tab-')[1]
        const index = state.items.indexOf(state.items.find(item => item.id === itemId))
        return 'tab-' + state.items[index - 1].id
      }
    },
    menuItem: state => state.menuItem
  }
}
