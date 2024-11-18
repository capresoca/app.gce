<template>
  <div :class="componentClass">
    <v-menu
      v-if="picker"
      ref="fechaInputFlex"
      :close-on-content-click="false"
      v-model="menu"
      :nudge-right="40"
      :return-value.sync="modelPicker"
      lazy
      transition="scale-transition"
      offset-y
      full-width
      min-width="290px"
    >
      <v-icon slot="activator"></v-icon>
      <v-date-picker
        ref="fechaDatePicker"
        locale="es-co"
        v-model="modelPicker"
        @input="() => {
              $refs.fechaInputFlex.originalValue = modelPicker
              $emit('input', moment(modelPicker).format(format))
              menu = false
              }"
        :readonly="readonly"
        :disabled="disabled"
        :min="min"
        :max="max"
      />
    </v-menu>
    <v-text-field
      ref="fechaInputFlexText"
      :value="modelTextField"
      :label="label"
      :mask="mask"
      :placeholder="placeholder"
      return-masked-value
      :disabled="disabled"
      :readonly="readonly"
      :prepend-icon="prependIcon"
      :hint="hint"
      :persistent-hint="!!hint"
      @blur="$emit('input', $refs.fechaInputFlexText.internalValue)"
      @input="$emit('input', $refs.fechaInputFlexText.internalValue)"
      :error-messages="errorMessages"
    >
      <v-icon v-if="picker && prependIcon" @click="menu = true" slot="prepend">{{prependIcon}}</v-icon>
      <v-icon v-else-if="prependIcon" slot="prepend">{{prependIcon}}</v-icon>
    </v-text-field>
  </div>
</template>

<script>
    export default {
      name: 'InputDate',
      inject: ['$validator'],
      props: {
        componentClass: {
          type: String,
          default: null
        },
        disabled: {
          type: Boolean,
          default: false
        },
        label: {
          type: String,
          default: null
        },
        placeholder: {
          type: String,
          default: null
        },
        name: {
          type: String,
          default: null
        },
        min: {
          type: String,
          default: null
        },
        max: {
          type: String,
          default: null
        },
        value: {
          type: String,
          default: null
        },
        format: {
          type: String,
          default: 'YYYY-MM-DD'
        },
        readonly: {
          type: Boolean,
          default: false
        },
        hint: {
          type: String,
          default: null
        },
        picker: {
          type: Boolean,
          default: true
        },
        prependIcon: {
          type: String,
          default: 'event'
        },
        errorMessages: Array
      },
      $_veeValidate: {
        name () {
          return this.name
        },
        value () {
          return this.modelTextField
        }
      },
      data: () => ({
        modelPicker: null,
        modelTextField: null,
        menu: false,
        mask: '##-##-####',
        flagEmit: false
      }),
      watch: {
        'value' (val) {
          this.ejecutar(val)
        }
      },
      created () {
        this.ejecutar(this.value)
      },
      methods: {
        ejecutar (val) {
          this.mask = JSON.parse(JSON.stringify(this.format)).replace(new RegExp(/(D|M|Y)/i, 'g'), '#')
          if (val) {
            if (this.moment(val, 'YYYY-MM-DD', true).isValid()) {
              this.modelPicker = this.moment(val, 'YYYY-MM-DD').format('YYYY-MM-DD')
              this.modelTextField = this.moment(val, 'YYYY-MM-DD').format(this.format)
            } else if (this.moment(val, this.format, true).isValid()) {
              this.modelPicker = this.moment(val, this.format).format('YYYY-MM-DD')
              this.modelTextField = this.moment(val, this.format).format(this.format)
            } else {
              this.modelPicker = null
            }
            if (!this.flagEmit) {
              this.$emit('input', this.modelTextField)
              this.flagEmit = true
            }
          } else {
            this.modelPicker = null
            this.modelTextField = null
          }
        }
      }
    }
</script>

<style scoped>

</style>
