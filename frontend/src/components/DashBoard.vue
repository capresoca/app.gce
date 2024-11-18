<template>
  <section>
    <v-layout >
      <v-flex xs12 sm8 offset-sm2>
        <v-card class="elevation-24">
          <v-img
            src="../../../static/fondo-casanare.png"
            class="white--text"
            height="300px"
          >
            <v-container fill-height fluid align-content-end>
              <v-layout fill-height>
                <v-flex xs12 flexbox>
                  <span class="headline" v-if="false">Sistemas Inteligentes</span>
                  <v-container align-content-center>
                    <v-flex style="text-align: center">
                      <v-progress-circular
                        :size="200"
                        :width="50"
                        :rotate="-90"
                        :value="progress"
                        color="primary"
                        button
                      >
                        {{ progress }} %
                      </v-progress-circular>
                    </v-flex>
                  </v-container>
                </v-flex>
              </v-layout>
            </v-container>

          </v-img>
          <v-container fluid grid-list-lg>
            <v-layout row>
              <v-flex >
                <div>
                  <div class="headline">Trabajando para mejorar</div>
                  <div>Tiempo restante para lanzamiento:</div>
                  <!--                  <div>Tiempo restante para lanzamiento:</div>-->
                  <v-container  fluid>
                    <v-layout  row wrap >
                      <v-flex   xs3>
                        <v-card dark  color="blue darken-1" >
                          <v-container>
                            <v-card-text  class="title text-xs-center">
                              {{days}}
                            </v-card-text>
                            <v-card-text class="text-xs-center ">Dias</v-card-text>
                          </v-container>
                        </v-card>
                      </v-flex>
                      <v-flex xs3>
                        <v-card dark  color="blue darken-1">
                          <v-container>
                            <v-card-text class="title text-xs-center">
                              {{hours}}
                            </v-card-text>
                            <v-card-text class="text-xs-center ">Horas</v-card-text>
                          </v-container>
                        </v-card>
                      </v-flex>

                      <v-flex xs3>
                        <v-card dark  color="blue darken-1" >
                          <v-container>
                            <v-card-text class="title text-xs-center">
                              {{minutes}}
                            </v-card-text>
                            <v-card-text class="text-xs-center ">Minutos</v-card-text>
                          </v-container>
                        </v-card>
                      </v-flex>

                      <v-flex xs3>
                        <v-card dark  color="blue darken-1" >
                          <v-container>
                            <v-card-text class="title text-xs-center">
                              {{seconds}}
                            </v-card-text>
                            <v-card-text class="text-xs-center">Segundos</v-card-text>
                          </v-container>
                        </v-card>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat href="/main" color="primary">Ver más</v-btn>
          </v-card-actions>
        </v-card>

      </v-flex>

    </v-layout>
  </section>
</template>

<script>
  export default {
    name: 'DashBoard',
    data: () => ({
      startTime: 'April 9, 2018 14:55:00',
      endTime: 'March 1, 2020 23:59:59',
      days: 1,
      hours: 1,
      minutes: 1,
      seconds: 1,
      progress: 100,
      // isActive: false,
      timeinterval: undefined
    }),
    methods: {
      updateTimer () {
        console.log()
        if (
          this.seconds > 0 ||
          this.minutes > 0 ||
          this.hours > 0 ||
          this.days > 0
        ) {
          this.getTimeRemaining()
          this.updateProgressBar()
        } else {
          clearTimeout(this.timeinterval)
          // this.times[3].time = this.times[2].time = this.times[1].time = this.times[0].time = 0
          this.progress = 0
        }
      },
      getTimeRemaining () {
        let t = Date.parse(new Date(this.endTime)) - Date.parse(new Date())
        if (t >= 0) {
          this.seconds = Math.floor(t / 1000 % 60) // seconds
          this.minutes = Math.floor(t / 1000 / 60 % 60) // minutes
          this.hours = Math.floor(t / (1000 * 60 * 60) % 24) // hours
          this.days = Math.floor(t / (1000 * 60 * 60 * 24)) // days
        } else {
          this.seconds = this.minutes = this.hours = this.days = 0
          this.progress = 0
        }
      },
      updateProgressBar () {
        let startTime = Date.parse(new Date(this.startTime))
        let currentTime = Date.parse(new Date())
        let endTime = Date.parse(new Date(this.endTime))
        let interval = parseFloat(
          (currentTime - startTime) / (endTime - startTime) * 100
        ).toFixed(2)
        this.progress = parseInt(interval)
        // console.log(interval)
      }
    },
    created () {
      this.updateTimer()
      this.timeinterval = setInterval(this.updateTimer, 1000)
    }
  }
</script>

<style>
  main {
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b8e1fc+0,a9d2f3+10,90bae4+25,90bcea+37,90bff0+50,6ba8e5+51,a2daf5+83,bdf3fd+100;Blue+Gloss+%231 */
    background: #b8e1fc; /* Old browsers */
    background: -moz-linear-gradient(-45deg, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(-45deg, #b8e1fc 0%,#a9d2f3 10%,#90bae4 25%,#90bcea 37%,#90bff0 50%,#6ba8e5 51%,#a2daf5 83%,#bdf3fd 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(135deg, #b8e1fc 0%,#a9d2f3 10%,#90bae4 25%,#90bcea 37%,#90bff0 50%,#6ba8e5 51%,#a2daf5 83%,#bdf3fd 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b8e1fc', endColorstr='#bdf3fd',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    padding: 0;
    margin: 0;
  }

</style>
