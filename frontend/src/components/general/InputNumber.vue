<template>
  <div data-v-e4bc25a2x=""
       aria-required="true" :aria-invalid="isError ? 'true' : 'false'"
       class="v-input v-text-field theme--light"
       :class="(disabled ? 'v-input--is-disabled ' : '') + (isFocus ? 'v-input--is-focused ' : '') + (isNull ? '' : 'v-input--is-label-active v-input--is-dirty ') + (isError ? 'v-input--has-state error--text' : isFocus ? 'v-input--has-state primary--text' : '')">
    <div v-if="prependIcon" class="v-input__prepend-outer">
      <div class="v-input__icon v-input__icon--prepend">
        <i aria-hidden="true" class="v-prependIcon material-icons theme--light" :class="(disabled ? 'v-prependIcon--disabled' : '') + (isError ? 'error--text' : isFocus ? 'primary--text' : '')">{{prependIcon}}</i>
      </div>
    </div>
    <div class="v-input__control">
      <div class="v-input__slot">
        <div class="v-text-field__slot">
          <label v-if="label" class="v-label theme--light" :class="(disabled ? 'v-label--is-disabled ' : '') + (isFocus || !isNull ? 'v-label--active ' : '') + (isError ? 'error--text' : isFocus ? 'primary--text' : '')" style="left: 0px; right: auto; position: absolute;">{{label}}</label>
          <input v-if="disabled || readonly" :disabled="disabled" :readonly="readonly" type="text" v-model="localValue">
          <money v-else id="maskedinput" :disabled="disabled" aria-hidden="true" v-bind="{precision: precisionx}" :class="editing" ref="maskedInput" type="text" v-model.lazy="localValue"></money>
        </div>
      </div>
      <div class="v-text-field__details">
        <div class="v-messages" :class="isError ? 'error--text' : ''">
          <div class="v-messages__wrapper">
            <div class="v-messages__message">{{errorMessages[0] || hint}}</div>
      </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'InputNumber',
    props: {
      value: {
        type: [String, Number],
        default: '0'
      },
      label: {
        type: String,
        default: ''
      },
      hint: {
        type: String,
        default: ''
      },
      prependIcon: {
        type: String,
        default: ''
      },
      errorMessages: {
        type: Array,
        default: []
      },
      precision: {
        type: Number,
        default: 0
      },
      disabled: {
        type: Boolean,
        default: false
      },
      readonly: {
        type: Boolean,
        default: false
      }
    },
    inject: ['$validator'],
    $_veeValidate: {
      name () {
        return this.name
      },
      value () {
        return this.value
      }
    },
    data () {
      return {
        localValue: 0,
        editing: false,
        focus: false,
        precisionx: 0
      }
    },
    watch: {
      'precision' (val) {
        this.precisionx = val
      },
      'value' (val, prev) {
        !prev && (this.precisionx = this.precision > 0 ? this.precision : 0)
        this.calcular()
      },
      async 'localValue' (val) {
        this.$emit('input', val ? val.toString() : (0).toString())
      }
    },
    computed: {
      isFocus () {
        return this.focus
      },
      isError () {
        return this.errorMessages && this.errorMessages.length > 0
      },
      isNull () {
        return this.value === null
      }
    },
    created () {
      this.precisionx = this.precision > 0 ? this.precision : 0
      this.calcular()
      document.addEventListener('focusin', this.focusIn)
      document.addEventListener('click', this.focusOut)
    },
    beforeDestroy () {
      document.addEventListener('focusin', this.focusIn)
      document.addEventListener('click', this.focusOut)
    },
    methods: {
      calcular () {
        if (this.value !== null && typeof this.value !== 'undefined') {
          if (!(this.value.toString().indexOf('.') > -1)) {
            let theVal = this.precisionx === 0 ? this.value.toString() : (this.value.toString() + '.' + ('0').repeat(this.precisionx))
            this.localValue = theVal
          } else {
            let valSplit = this.value.toString().split('.')
            let cantidadDecimales = valSplit[1].length
            if (cantidadDecimales < this.precisionx) {
              this.localValue = this.value.toString() + ('0').repeat((this.precisionx - cantidadDecimales))
            }
            if (cantidadDecimales === this.precisionx) {
              this.localValue = this.value.toString()
            }
            if (cantidadDecimales > this.precisionx) {
              this.precisionx = cantidadDecimales
              this.localValue = this.value.toString()
            }
          }
        }
      },
      focusIn (event) {
        this.focus = (event.target.id === 'maskedinput')
      },
      focusOut (event) {
        this.focus = !(event.target.id !== 'maskedinput')
      }
    }
  }
</script>

<style>
</style>
