<template>
    <v-form data-vv-scope="forTabCorporativa">
      <v-card>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm12>
              <file-input-archive
                :title="'Cargar Imagen Coporativa'"
                v-model="value.logo"
                mime-types="image/jpeg,image/png"
                :multiple="false"></file-input-archive>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-form>
</template>

<script>
  import FileInputArchive from '../../general/FileInput'
  export default {
    name: 'FormCorporativa',
    props: ['value'],
    components: {FileInputArchive}
  }
</script>

<style scoped>

</style>
