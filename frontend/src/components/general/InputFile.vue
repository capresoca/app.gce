<template>
  <div>
    <input
      v-if="!disabled && !forceEmpty"
      :accept="accept"
      type="file"
      :multiple="multiple"
      hidden
      ref="inputAnexo"
      :name="label + 'archivo'"
      v-validate="rules"
      data-vv-delay="500"
      :data-vv-as="label"
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
      :error-messages="errors.collect(label + 'archivox').length ? (errors.collect(label + 'archivox') + '.. ' + hint) : errors.collect(label + 'archivo').length ? (errors.collect(label + 'archivo') + '.. ' + hint) : ''"
      :hint="hint"
      persistent-hint
      :clearable="clearable"
      :disabled="disabled"
      :name="label + 'archivox'"
      :data-vv-as="label"
      required
      v-validate="{required: required}"
    />
  </div>
</template>

<script>
  export default {
    name: 'InputFile',
    props: {
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
      fileName: {
        type: String,
        default: null
      },
      value: null
    },
    data () {
      return {
        namesArchivos: null,
        forceEmpty: false
      }
    },
    watch: {
      'fileName' (val) {
        if (!(typeof val === 'undefined')) this.namesArchivos = val
      },
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
        if (val === null) this.$emit('input', null)
      }
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
        }
      }
    }
  }
</script>

<style scoped>

</style>
