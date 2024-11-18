<template>
    <v-card class="grey lighten-4 d-flex cardProveedor">
      <v-card-title class="grey lighten-2" style="background-color: #375d7b !important;">
        <div class="text-xs-center" style="max-width: 100%">
          <v-icon size="86" class="icone" color="white" v-text="icono"></v-icon>
          <span class="white--text titulo body-2" v-text="titulo ? titulo : 'Datos Básicos Proveedor'"></span>
        </div>
<!--        <span class="title" v-text="'Datos Básicos Proveedor: '"></span>-->
        <!--<v-spacer></v-spacer>-->
        <!--<v-icon  medium color="teal">verified_user</v-icon> proveedor.tercero-->
      </v-card-title>
      <v-container fluid grid-list-md class="pr-0 fondo">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 xl12>
            <v-card class="grey lighten-4 elevation-0 cardIn" style="max-width: 100% !important;">
              <v-layout row wrap>
                <v-flex xs12>
                  <v-container primary-title class="pa-1 pt-1">
                    <div>
                      <div>
                        <v-icon size="20" v-text="'fas fa-user-circle'"></v-icon>
                        <span class="font-weight-medium" v-text="nombre ? nombre : 'No Registra'"></span>
                        <!--<span v-else v-text="proveedor.tercero.razon_social"></span>-->
                      </div>
                      <div>
                        <v-icon size="20" v-text="'fas fa-hashtag'"></v-icon>
                        <span class="font-weight-medium" v-text="identificacion ? identificacion : 'No Registra'"></span>
                      </div>
                      <div>
                        <v-icon size="20" v-text="'fas fa-phone-square-alt'"></v-icon>
                        <span class="font-weight-medium" v-text="telefono ? telefono : 'No registra'"></span>
                      </div>
                      <div>
                        <v-icon size="20" v-text="'fas fa-mobile-alt'"></v-icon>
                        <span class="font-weight-medium" v-text="celular ? celular : 'No registra'"></span>
                      </div>
                    </div>
                  </v-container>
                </v-flex>
<!--                <v-flex xs6 sm6 md5 xl5 class="pb-1">-->
<!--                  <div class="text-xs-center" style="max-width: 100%">-->
<!--                    <v-icon size="86" color="#375d7b" v-text="'fas fa-id-card-alt'"></v-icon>-->
<!--                  </div>-->
<!--                </v-flex>-->
              </v-layout>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
</template>

<script>
  export default {
    name: 'TarjetaDetalle',
    props: {
      telefono: {
        type: String,
        default: null
      },
      celular: {
        type: String,
        default: null
      },
      identificacion: {
        type: String,
        default: null
      },
      nombre: {
        type: String,
        default: null
      },
      titulo: {
        type: String,
        default: null
      },
      icono: {
        type: String,
        default: 'fas fa-id-card-alt'
      }
    }
  }
</script>

<style scoped>
  .cardProveedor {
    max-width: 100% !important;
    width: 100%;
  }

  .titulo {
    display: none !important;
  }

  .fondo {
    background: linear-gradient(to right bottom, rgba(255,255,255,0), rgba(90, 120, 180, 0.35)), url("http://www.educa.org.do/wp-content/uploads/2014/02/polygon-26677-2560x1600.jpg") !important;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }

  .cardIn {
    width: 330px !important;
    background: transparent !important;
  }

  @media (max-width: 600px) {
    .cardProveedor {
      max-width: 100% !important;
      width: 100%;
      display: block !important;
    }
    .cardProveedor .v-card__title {
      padding-top: 10px;
      padding-bottom: 10px;
    }
    .icone {
      font-size: 25px !important;
    }
    .titulo {
      display: inline-block !important;
      padding-left: 5px !important;
      font-size: 1.08em !important;
    }
  }
</style>
