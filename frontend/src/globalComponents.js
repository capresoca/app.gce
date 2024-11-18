import DataTable from '@/components/general/DataTable'
import DataTableV2 from '@/components/general/DataTableV2'
import MiniCardDetail from '@/components/general/MiniCardDetail'
import CambiarEstadoTercero from '@/components/administrativo/niif/terceros/CambiarEstadoTercero'
import Loading from '@/components/general/Loading'
import InputDate from '@/components/general/InputDate'
import InputDetailFlex from '@/components/general/InputDetailFlex'
import PostuladorV3 from '@/components/general/PostuladorV3'
import PostuladorV2 from '@/components/general/PostuladorV2'
import ToolbarList from '@/components/general/ToolbarList'
import CkeditorTextarea from '@/components/general/CkeditorTextarea'
import InputNumber from '@/components/general/InputNumber'
const GlobalComponents = {
  install (Vue) {
    Vue.component('Loading', Loading)
    Vue.component('MiniCardDetail', MiniCardDetail)
    Vue.component('CambiarEstadoTercero', CambiarEstadoTercero)
    Vue.component('DataTable', DataTable)
    Vue.component('DataTableV2', DataTableV2)
    Vue.component('InputDate', InputDate)
    Vue.component('InputDate', InputDate)
    Vue.component('InputDetailFlex', InputDetailFlex)
    Vue.component('PostuladorV3', PostuladorV3)
    Vue.component('PostuladorV2', PostuladorV2)
    Vue.component('ToolbarList', ToolbarList)
    Vue.component('CkeditorTextarea', CkeditorTextarea)
    Vue.component('InputNumber', InputNumber)
  }
}
export default GlobalComponents
