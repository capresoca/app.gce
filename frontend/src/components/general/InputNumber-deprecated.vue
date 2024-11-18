<template>
  <div data-v-e4bc25a2x=""
       aria-required="true" :aria-invalid="isError ? 'true' : 'false'"
       class="v-input v-text-field theme--light"
       :class="(isDisabled ? 'v-input--is-disabled ' : '') + (isFocus ? 'v-input--is-focused ' : '') + (isNull ? '' : 'v-input--is-label-active v-input--is-dirty ') + (isError ? 'v-input--has-state error--text' : isFocus ? 'v-input--has-state primary--text' : '')">
    <div v-if="icon" class="v-input__prepend-outer">
      <div class="v-input__icon v-input__icon--prepend">
        <i aria-hidden="true" class="v-icon material-icons theme--light" :class="(isDisabled ? 'v-icon--disabled' : '') + (isError ? 'error--text' : isFocus ? 'primary--text' : '')">{{icon}}</i>
      </div>
    </div>
    <div class="v-input__control">
      <div class="v-input__slot">
        <div class="v-text-field__slot">
          <label v-if="label" for="testingx" class="v-label theme--light" :class="(isDisabled ? 'v-label--is-disabled ' : '') + (isFocus || !isNull ? 'v-label--active ' : '') + (isError ? 'error--text' : isFocus ? 'primary--text' : '')" style="left: 0px; right: auto; position: absolute;">{{label}}</label>
          <vue-numeric
            @blur="focus = false"
            @focus="focus = true"
            id="testingx"
            @input="$emit('input', valor)"
            @keyup="$emit('input', valor)"
            :name="name"
            :precision="precision"
            separator="."
            type="text"
            :value="value"
            v-model="valor"
            :disabled="isDisabled"
          />
        </div>
      </div>
      <div class="v-text-field__details">
        <div class="v-messages" :class="isError ? 'error--text' : ''">
          <div class="v-messages__wrapper">
            <div v-if="isError" class="v-messages__message">{{errorMessages[0]}}</div>
      </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import VueNumeric from 'vue-numeric'
  export default {
    name: 'InputNumber',
    props: ['value', 'label', 'icon', 'errorMessages', 'name', 'precision', 'disabled'],
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        valor: 0,
        focus: false
      }
    },
    components: {
      VueNumeric
    },
    watch: {
      'value' (val) {
        if (val !== null && !isNaN(val)) {
          this.valor = val
          this.$emit('input', this.valor)
        } else {
          this.valor = 0
          this.$emit('input', val)
        }
      }
    },
    computed: {
      isFocus () {
        return this.focus
      },
      isError () {
        return this.errorMessages && this.errorMessages.length > 0
      },
      isDisabled () {
        return this.disabled === true
      },
      isNull () {
        return this.valor === null
      },
      showBtnplus () {
        return typeof this.btnplus !== 'undefined'
      },
      showBtnplusTruncate () {
        return typeof this.btnplustruncate !== 'undefined'
      }
    },
    methods: {
    }
  }
</script>

<style scoped>

</style>
