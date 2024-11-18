<template>
  <v-card class="elevation-0" style="background-color: transparent !important;">
    <!--<v-layout align-center justify-center row>-->
      <!--<div class="display-3 font-weight-bold amber&#45;&#45;text" style="text-shadow: 0 1px 1px #333, 1px -1px 2px #FF6F00; letter-spacing: 0.1em !important;">GCE</div>-->
      <!--<span class="pt-3 pl-3 display-1 font-weight-medium amber&#45;&#45;text" style="text-shadow: 0 1px 1px #333, 0 -1px 1px #EAEAEA; letter-spacing: 0.1em !important;">Software</span>-->
    <!--</v-layout>-->
    <!--<v-layout align-center justify-center row>-->
      <!--<span class="pt-3 pl-3 headline font-weight-regular amber&#45;&#45;text" style="text-shadow: 0 1px 1px #333, 0 -1px 1px #EAEAEA; letter-spacing: 0.1em !important;">Gestión y Control Empresarial</span>-->
    <!--</v-layout>-->
    <v-layout align-center justify-center row>
      <img src="@/assets/logo-GCE.png"  style="width: 30%;"/>
    </v-layout>
    <v-responsive :aspect-ratio="16/4.5" width="100%" height="100%">
      <v-layout align-center justify-center row fill-height>
        <img src="@/assets/banner.jpg"  style="width: 100%;"/>
      </v-layout>
    </v-responsive>
  </v-card>
  <!--<section style="text-align: center; height: 700px!important;" class="pb-5">-->

    <!--<h2>Bienvenido</h2>-->
    <!--<v-layout column>-->
      <!--<v-flex xs12>-->
        <!--<v-card tile flat >-->
          <!--<v-container fluid grid-list-md>-->
            <!--<v-layout row wrap>-->
              <!--<v-flex xs12 style="height: 300px"-->
              <!--&gt;-->
                <!--<v-card class="elevation-8">-->
                  <!--<v-container>-->
                    <!--<canvas  ref="chart" ></canvas>-->
                  <!--</v-container>-->
                  <!--<v-card-actions>-->
                    <!--<v-spacer></v-spacer>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>favorite</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>bookmark</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>share</v-icon>-->
                    <!--</v-btn>-->
                  <!--</v-card-actions>-->
                <!--</v-card>-->
              <!--</v-flex>-->
              <!--<v-flex xs6-->
              <!--&gt;-->
                <!--<v-card class="elevation-8">-->
                  <!--<v-container>-->
                    <!--<canvas  ref="doughnut" ></canvas>-->
                  <!--</v-container>-->
                  <!--<v-card-actions>-->
                    <!--<v-spacer></v-spacer>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>favorite</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>bookmark</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>share</v-icon>-->
                    <!--</v-btn>-->
                  <!--</v-card-actions>-->
                <!--</v-card>-->
              <!--</v-flex>-->
              <!--<v-flex xs6-->
              <!--&gt;-->
                <!--<v-card class="elevation-8">-->
                  <!--<v-container>-->
                    <!--<canvas  ref="polar" ></canvas>-->
                  <!--</v-container>-->
                  <!--<v-card-actions>-->
                    <!--<v-spacer></v-spacer>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>favorite</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>bookmark</v-icon>-->
                    <!--</v-btn>-->
                    <!--<v-btn icon>-->
                      <!--<v-icon>share</v-icon>-->
                    <!--</v-btn>-->
                  <!--</v-card-actions>-->
                <!--</v-card>-->
              <!--</v-flex>-->
            <!--</v-layout>-->
          <!--</v-container>-->
        <!--</v-card>-->
      <!--</v-flex>-->
    <!--</v-layout>-->
  <!--</section>-->
</template>

<script>
  // import {AUTH_REAUTH_REQUEST} from '../store/modules/general/auth'
  // import Chart from 'chart.js'
  // import {USER_REQUEST} from '@/store/modules/general/user'

  export default {
    name: 'hello',
    props: ['algo']
    // data () {
    //   return {
    //     msg: 'Welcome',
    //     active: null,
    //     picker: '',
    //     pickerConfig: {
    //       isLandScape: true,
    //       reactive: true
    //     },
    //     text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    //     graficoPqrs: null,
    //     graficoMedicamentos: null,
    //     graficoPolar: null,
    //     pqrs: {
    //       type: 'line',
    //       data: {
    //         labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
    //         datasets: [{
    //           label: '2017',
    //           backgroundColor: 'rgba(14, 109, 205, 0.2)',
    //           borderColor: 'rgba(14, 109, 205, 1)',
    //           data: [21, 4, 10, 18, 12, 22],
    //           fill: false
    //         }, {
    //           label: '2018',
    //           fill: false,
    //           backgroundColor: 'rgba(205, 14, 14, 0.2)',
    //           borderColor: 'rgba(205, 14, 14, 1)',
    //           data: [12, 5, 17, 12, 17, 15]
    //         }]
    //       },
    //       options: {
    //         responsive: true,
    //         title: {
    //           display: true,
    //           text: 'PQRS'
    //         },
    //         tooltips: {
    //           mode: 'index',
    //           intersect: false
    //         },
    //         hover: {
    //           mode: 'nearest',
    //           intersect: true
    //         },
    //         scales: {
    //           xAxes: [{
    //             display: true,
    //             scaleLabel: {
    //               display: true,
    //               labelString: 'Meses'
    //             }
    //           }],
    //           yAxes: [{
    //             display: true,
    //             scaleLabel: {
    //               display: true,
    //               labelString: '# de PQRS'
    //             }
    //           }]
    //         }
    //       }
    //     },
    //     medicamentos: {
    //       type: 'doughnut',
    //       data: {
    //         datasets: [{
    //           data: [12, 45, 78, 23, 12],
    //           backgroundColor: [
    //             'rgba(255,99,132,1)',
    //             'rgba(54, 162, 235, 1)',
    //             'rgba(255, 206, 86, 1)',
    //             'rgba(75, 192, 192, 1)',
    //             'rgba(153, 102, 255, 1)'
    //           ],
    //           label: 'Dataset 1'
    //         }],
    //         labels: [
    //           'Red Salud',
    //           'ESE Salud Yopal',
    //           'ESE HORO',
    //           'ESE JLL',
    //           'Clinica Casanare'
    //         ]
    //       },
    //       options: {
    //         responsive: true,
    //         legend: {
    //           position: 'left'
    //         },
    //         title: {
    //           display: true,
    //           text: 'Contratación'
    //         },
    //         animation: {
    //           animateScale: true,
    //           animateRotate: true
    //         }
    //       }
    //     },
    //     polar: {
    //       type: 'polarArea',
    //       data: {
    //         datasets: [{
    //           data: [30, 80, 50, 90, 22],
    //           backgroundColor: [
    //             'rgba(255,99,132,1)',
    //             'rgba(54, 162, 235, 1)',
    //             'rgba(255, 206, 86, 1)',
    //             'rgba(75, 192, 192, 1)',
    //             'rgba(153, 102, 255, 1)'
    //           ],
    //           label: 'My dataset' // for legend
    //         }],
    //         labels: [
    //           'Mani',
    //           'Paz de Ariporo',
    //           'aguazul',
    //           'Yopal',
    //           'Nunchia'
    //         ]
    //       },
    //       options: {
    //         responsive: true,
    //         legend: {
    //           position: 'right'
    //         },
    //         title: {
    //           display: true,
    //           text: 'Caracterización Población'
    //         },
    //         scale: {
    //           ticks: {
    //             beginAtZero: true
    //           },
    //           reverse: false
    //         },
    //         animation: {
    //           animateRotate: false,
    //           animateScale: true
    //         }
    //       }
    //     }
    //   }
    // },
    // methods: {
    //   mensaje (mensaje) {
    //     alert(mensaje)
    //   },
    //   dibujarPqrs () {
    //     this.$refs.chart.height = 50
    //     this.graficoPqrs = new Chart(this.$refs.chart, {
    //       type: this.pqrs.type,
    //       data: this.pqrs.data,
    //       options: this.pqrs.options
    //     })
    //   },
    //   dibujarDoughnut () {
    //     this.$refs.doughnut.height = 100
    //     this.graficoMedicamentos = new Chart(this.$refs.doughnut, {
    //       type: this.medicamentos.type,
    //       data: this.medicamentos.data,
    //       options: this.medicamentos.options
    //     })
    //   },
    //   dibujarPolar () {
    //     this.$refs.polar.height = 100
    //     this.graficoPolar = new Chart(this.$refs.polar, {
    //       type: this.polar.type,
    //       data: this.polar.data,
    //       options: this.polar.options
    //     })
    //   }
    // },
    // watch: {
    //   algo (value) {
    //     // console.log(value)
    //   }
    // },
    // created () {
    // },
    // mounted () {
    //   // console.log('montado!! ' + this.$store.getters.isAuthenticated)
    //   // this.$store.dispatch(USER_REQUEST)
    //   if (this.$store.getters.isAuthenticated) {
    //     this.$store.dispatch(USER_REQUEST)
    //   } else {
    //     this.$store.commit(AUTH_REAUTH_REQUEST, true)
    //   }
    //   this.dibujarPqrs()
    //   this.dibujarDoughnut()
    //   // this.dibujarPolar()
    // }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
