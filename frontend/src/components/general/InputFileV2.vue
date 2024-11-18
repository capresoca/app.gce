<template>
  <div>
    <input
      v-if="!disabled && !forceEmpty"
      :accept="accept"
      type="file"
      :multiple="multiple"
      hidden
      ref="inputAnexo"
      @change="onFilePicked($event)"
    />
    <v-textarea
      rows="1"
      auto-grow
      v-model="namesArchivos"
      :label="label"
      :prepend-icon="prependIcon"
      readonly
      @click.native="$refs.inputAnexo.click()"
      :error-messages="errorMessages && errorMessages.length ? errorMessages + (hint ? '... ' + hint : '') : ''"
      :hint="hint"
      persistent-hint
      :clearable="clearable"
      :disabled="disabled"
      :loading="loading"
    ></v-textarea>
  </div>
</template>

<script>
  export default {
    name: 'InputFileV2',
    inject: ['$validator'],
    $_veeValidate: {
      name () {
        return this.name
      },
      value () {
        return this.namesArchivos
      }
    },
    props: {
      name: {
        type: String,
        default: null
      },
      label: {
        type: String,
        default: null
      },
      prependIcon: {
        type: String,
        default: 'attach_file'
      },
      accept: {
        type: String,
        default: null
      },
      multiple: {
        type: Boolean,
        default: false
      },
      clearable: {
        type: Boolean,
        default: false
      },
      loading: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      required: {
        type: Boolean,
        default: false
      },
      hint: {
        type: String,
        default: ''
      },
      value: {
        type: [Object, File, String],
        default: null
      },
      errorMessages: Array
    },
    data () {
      return {
        namesArchivos: null,
        forceEmpty: false
      }
    },
    watch: {
      'value' (val) {
        if (typeof val === 'string') this.namesArchivos = val
        if (!val) {
          this.namesArchivos = null
          this.forceEmpty = true
          setTimeout(() => {
            this.forceEmpty = false
          }, 100)
        }
      },
      'namesArchivos' (val) {
        if (val === null) {
          this.$emit('input', null)
          this.$emit('stringFile', null)
        }
      }
    },
    created () {
      if (typeof this.value === 'string') this.namesArchivos = this.value
    },
    computed: {
      rules () {
        let rules = []
        let required = this.required ? 'required' : ''
        if (required.length) rules.push(required)
        let mimes = this.accept ? 'mimes:' + this.accept : ''
        if (mimes.length) rules.push(mimes)
        return rules.join('|')
      }
    },
    methods: {
      validate (scope) {
        if (this.namesArchivos) {
          return true
        } else {
          return this.$validator.validateAll(scope)
        }
      },
      reset () {
        this.$validator.reset()
      },
      onFilePicked (e) {
        const files = e.target.files
        if (files.length) {
          let names = []
          for (let i = 0; i < files.length; i++) {
            names.push(files[i].name)
          }
          this.namesArchivos = names.join(' || ')
          this.$emit('input', (this.multiple ? files : files[0]))
          this.$emit('stringFile', this.namesArchivos)
        } else {
          this.forceEmpty = true
          setTimeout(() => {
            this.forceEmpty = false
          }, 100)
        }
      }
    }
  }
</script>

<style scoped>

</style>
