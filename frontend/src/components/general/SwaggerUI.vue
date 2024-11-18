<template>
  <div>
    <v-toolbar>
      <v-icon>{{infoComponent ? infoComponent.icono : ''}}</v-icon>
      <v-toolbar-title>Documentacion del API </v-toolbar-title>
      <small class="mt-2 ml-1"> Backend Version 0.1.0</small>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-card>
      <v-container
        fluid
        grid-list-xs
      >
        <v-layout row wrap pt-2 pr-2>
          <v-flex xs12>
            <section id="swagger-ui"></section>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import Swagger from 'swagger-ui'
  import 'swagger-ui/dist/swagger-ui.css'
  export default {
    name: 'SwaggerUI',
    data: () => ({
      SwaggerUI: null,
      token: localStorage.getItem('token')

    }),
    methods: {
      traerDatosOpenApi () {
        this.axios.get('storage/api-docs')
          .then(response => {
            this.montarSwagger(response.data)
          })
          .catch(error => {
            console.log('error' + error)
          })
      },
      montarSwagger (openApiJson) {
        this.SwaggerUI = new Swagger({
          dom_id: '#swagger-ui',
          spec: openApiJson,
          layout: 'BaseLayout',
          filter: true

        })
        this.SwaggerUI.preauthorizeApiKey('JWT', this.token)
      }
    },
    mounted () {
      console.log('Component mounted.')
      this.traerDatosOpenApi()
    },
    components: {
      Swagger
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('apiDocs')
      }
    }
  }
</script>

<style >
  .information-container{
    display: none;
  }
</style>
