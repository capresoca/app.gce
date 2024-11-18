<template>
  <data-table
    ref="tablaLogTrasladoAfiliado"
    v-model="dataTable"
    @resetOption="item => resetOptions(item)"
  />
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'DetalleGeneralLogTraslados',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    components: {
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemLogTraslado',
          route: null,
          makeHeaders: [
            {
              text: 'Descripción',
              align: 'left',
              sortable: false,
              value: 'descripcion',
              classData: '',
              width: '220px'
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: true,
              value: 'fecha',
              classData: ''
            },
            {
              text: 'Vigencia',
              align: 'left',
              sortable: false,
              value: 'vigencia',
              classData: ''
            },
            {
              text: 'Detalle',
              align: 'left',
              sortable: false,
              value: 'informacion',
              classData: '',
              width: '220px'
            },
            {
              text: 'Glosado',
              align: 'center',
              sortable: false,
              value: 'estado_procesado',
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    created () {
      this.dataTable.route = `logtraslados?as_afiliado_id=${this.afiliadoId}`
    },
    mounted () {
      this.getData()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('logtraslado')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
      },
      getData () {
        this.$refs.tablaLogTrasladoAfiliado.reloadPage()
      }
    }
  }
</script>

<style scoped>

</style>