export const SNACK_SHOW = 'SNACK_SHOW'
export const SNACK_HIDDEN = 'SNACK_HIDDEN'
export const SNACK_ERROR_LIST = 'SNACK_ERROR_LIST'

export default {
  state: {
    show: false,
    message: '',
    color: '',
    timeout: 7000
  },
  mutations: {
    [SNACK_SHOW]: (state, data) => {
      state.show = false
      state.message = data.msg
      state.color = data.color
      state.timeout = 7000
      state.show = true
    },
    [SNACK_HIDDEN]: (state) => {
      state.show = false
      state.message = ''
    },
    [SNACK_ERROR_LIST]: (state, {expression, error}) => {
      state.show = false
      state.color = 'danger'
      if (error.response && error.response.data && (error.response.data.errors || error.response.data.message)) {
        if (error.response.data.message) {
          state.message = error.response.data.message
        } else if (typeof error.response.data.errors === 'string') {
          state.message = error.response.data.errors
        } else {
          let errorList = error.response.data.errors
          let items = []
          errorList && Object.values(errorList).forEach((value, index) => {
            items.push((index + 1) + `: ${value}`)
          })
          state.timeout = Object.keys(errorList).length * 10000
          state.message = (`Hay ${Object.keys(errorList).length} ${Object.keys(errorList).length !== 1 ? 'errores' : 'error'}${expression} <br> ${items.join('<br>')}`)
        }
      } else {
        state.message = `Hay un error${expression} => ${error.response.status}: ` + (error.response.data && error.response.data.message && error.response.data.message.Message ? error.response.data.message.Message : (error.response.data && error.response.data.message) ? error.response.data.message : error.response.statusText)
      }
      state.show = true
    }
  },
  getters: {
    show: state => state.show,
    message: state => state.message,
    snackBarTimeout: state => state.timeout,
    snackBarColor: state => state.color
  }
}
