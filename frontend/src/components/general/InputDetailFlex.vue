<template>
  <v-flex :d-flex="flexClass === null" :class="flexClass">
    <v-tooltip top :disabled="!tooltip">
      <v-flex slot="activator" class="pa-0 ma-0 idf-flex-interna">
        <v-list-tile>
          <component v-if="slotPrepend" :is="slotPrepend" v-model="value"></component>
          <v-icon class="pt-3 pr-1" v-else-if="prependIcon">{{prependIcon}}</v-icon>
          <v-tooltip
            class="mr-1"
            v-else-if="prependButton"
            :disabled="!prependButton.tooltip"
            top
            style="top: 8px"
          >
            <v-btn
              slot="activator"
              class="ma-0"
              flat
              icon
              :color="prependButton.color"
              @click.native="$emit('clickPrepend')"
            >
              <v-icon v-text="prependButton.icon"/>
            </v-btn>
            {{prependButton.tooltip}}
          </v-tooltip>
          <v-list-tile-content>
            <v-list-tile-sub-title class="v-messages theme--light" v-html="label"></v-list-tile-sub-title>
            <v-container fluid class="ma-0 pa-0">
              <v-layout align-center justify-space-between row fill-height>
                <v-flex d-flex xs12>
                  <p
                    class="mb-0 text-xs-justify"
                    v-html="text ? text : '&nbsp;'"
                  ></p>
                </v-flex>
                <v-flex v-if="suffix" d-flex xs12>
                  <span class="text-xs-right body-1" v-html="suffix"></span>
                </v-flex>
              </v-layout>
            </v-container>
          </v-list-tile-content>
          <v-list-tile-action v-if="appendIcon || appendButton">
            <v-icon class="pt-3 pr-1" v-if="appendIcon">{{appendIcon}}</v-icon>
            <v-tooltip
              class="ml-1"
              v-else-if="appendButton"
              :disabled="!appendButton.tooltip"
              top
              style="top: 8px"
            >
              <v-btn
                slot="activator"
                class="ma-0"
                flat
                icon
                :color="appendButton.color"
                @click.native="$emit('clickAppend')"
              >
                <v-icon v-text="appendButton.icon"/>
              </v-btn>
              {{appendButton.tooltip}}
            </v-tooltip>
          </v-list-tile-action>
        </v-list-tile>
        <v-divider :style="styleDir"/>
        <p
          class="mb-0 mt-1 text-xs-justify v-messages theme--light"
          :style="styleDir"
          v-html="hint"
        ></p>
      </v-flex>
      <span>{{tooltip}}</span>
    </v-tooltip>
  </v-flex>
</template>

<script>
  export default {
    name: 'input-detail-flex',
    props: {
      slotPrepend: {
        default: null
      },
      value: {
        default: null
      },
      label: {
        type: String,
        default: null
      },
      tooltip: {
        type: String,
        default: null
      },
      underline: {
        type: Boolean,
        default: null
      },
      readonly: {
        type: Boolean,
        default: true
      },
      disabled: {
        type: Boolean,
        default: false
      },
      text: {
        default: ''
      },
      flexClass: {
        type: String,
        default: null
      },
      hint: {
        type: String,
        default: null
      },
      prependIcon: {
        type: String,
        default: null
      },
      appendIcon: {
        type: String,
        default: null
      },
      suffix: {
        type: String,
        default: null
      },
      prependButton: {
        type: Object,
        default: null
      },
      appendButton: {
        type: Object,
        default: null
      }
    },
    computed: {
      styleDir () {
        let ml = this.prependIcon ? 28 : this.prependButton ? 40 : 0
        let mr = this.appendIcon ? 28 : this.appendButton ? 40 : 0
        return `margin-left: ${ml}px; margin-right: ${mr}px; max-width: calc(100% - ${ml + mr}px);`
      }
    }
  }
</script>

<style>
  .idf-flex-interna > div > div.v-list__tile {
    height: fit-content !important;
    padding: 0 !important;
    padding-top: 2px !important;
    padding-bottom: 4px !important;
  }
</style>
