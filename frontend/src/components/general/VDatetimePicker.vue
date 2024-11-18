<template>
  <v-menu lazy
    :close-on-content-click="false" v-model="menu"
    transition="scale-transition" offset-y :nudge-left="1" full-width
    max-width="600px" min-width="290px">
    <v-text-field
      slot="activator" :label="label" prepend-icon="today"
      :disabled="disabled" v-model="actualDatetime" :readonly="readonly" :value="timeData"
    ></v-text-field>
    <template>
      <v-layout row wrap class="lWidth">
        <v-flex xs12 sm12 md6 class="d_flex1">
          <v-date-picker
            ref="date"
            v-model="dateModel"
            locale='es'
            no-title
            scrollable
            actions
            :readonly="readonly"
            :disabled="disabled"
            :show-current="showCurrent"
            :max="max ? max.split(' ')[0] : ''"
            :min="min ? min.split(' ')[0] : ''"
            @input="checkHours"
          ></v-date-picker>
        </v-flex>
        <v-flex xs12 sm12 md6>
          <v-time-picker
            ref="timer"
            v-model="timeModel"
            no-title
            scrollable
            :readonly="readonly"
            :disabled="disabled"
            format="24hr"
            :min="min ? (dateModel === min.split(' ')[0] ? min.split(' ')[1] : '') : ''"
            :max="max ? (dateModel === max.split(' ')[0] ? max.split(' ')[1] : '') : ''"
            actions
            @input="checkMinutes"
          ></v-time-picker>
        </v-flex>
      </v-layout>
    </template>
  </v-menu>
</template>

<script>
    export default {
      name: 'VDatetimePicker',
      props: {
        readonly: {
          type: Boolean,
          default: false
        },
        disabled: {
          type: Boolean,
          default: false
        },
        timeData: {
          type: String,
          default: ''
        },
        datetime: {
          type: String,
          required: true
        },
        label: {
          type: String,
          default: ''
        },
        max: {
          type: String,
          default: ''
        },
        min: {
          type: String,
          default: ''
        },
        showCurrent: {
          type: String,
          default: ''
        }
      },
      data () {
        return {
          dateModel: '',
          timeModel: '',
          menu: false,
          selectedTab: 'calendar'
        }
      },
      watch: {
        menu (val) {
          if (val) {
            // console.log('val2', val)
            this.selectedTab = 'calendar'
            if (this.$refs.timer) this.$refs.timer.selectingHour = true
            // console.log('val3', this.selectedTab)
          }
        },
        'timeData' (val) {
          // console.log('val', val)
          if (val) {
            let num1Date = val.split(' ')[0]
            this.dateModel = num1Date
            let num2Time = val.split(' ')[1]
            this.timeModel = num2Time
          }
        }
      },
      computed: {
        actualDatetime () { return this.dateModel + ' ' + this.timeModel }
        // + (this.timeModel !== '00' ? ':00' : '')
      },
      methods: {
        checkMinutes (val) {
          // console.log('valtiem', val)
          if (this.$refs.timer.selectingHour === false) {
            this.timeModel = val
            this.$refs.timer.selectingHour = true
            this.selectedTab = 'calendar'
            this.$emit('input', this.actualDatetime)
            this.menu = false
          }
        },
        checkHours (val) {
          // console.log('huie', val)
          this.dateModel = val
          this.selectedTab = 'timer'
          this.$emit('input', this.actualDatetime)
          this.menu = false
        }
      },
      created () {
        this.dateModel = this.datetime.split(' ')[0]
        this.timeModel = this.datetime.split(' ')[1]
        this.$emit('input', this.actualDatetime)
      }
    }
</script>

<style scoped>
  div.d_flex1 > div.v-card {
    height: 290px;
    width: 290px !important;
  }

  @media (max-width: 959px) {
    .lWidth {
      width: 290px;
    }
  }

</style>
