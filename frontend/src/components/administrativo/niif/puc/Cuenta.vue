<template>

  <li>
    <div
      :class="{bold: isFolder}">
      <span @click="toggle"  class="item" v-if="isFolder">
        <v-icon v-if="open">keyboard_arrow_down</v-icon>
        <v-icon v-else>keyboard_arrow_right</v-icon>
      </span>
      <span v-else >
        <v-icon color="white">keyboard_arrow_down</v-icon>
      </span>
      <span>{{model.codigo}} {{model.nombre}}</span>
      <v-btn v-if="parentOpen && canChild"
             @click="addChild(model)"
             flat icon small
             class="pa-0 ma-0 alignleft"
             color="accent">
        <v-icon small>add</v-icon>
      </v-btn>
      <v-btn v-if="parentOpen && model.nf_nivcuenta_id !==1"
             flat icon small
             class="pa-0 ma-0"
             @click="editCuenta(model)"
             color="accent">
        <v-icon small>edit</v-icon>
      </v-btn>
    </div>
    <ul v-if="open && isFolder">
      <cuenta
        v-for="(model, index) in model.children"
        :key="index"
        :model="model"
        :last="last"
        @editcuenta="sendToEdit"
        @addcuenta="sendParent">
      </cuenta>
    </ul>
  </li>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Cuenta',
    props: {
      model: Object,
      last: 0
    },
    data: function () {
      return {
        open: false,
        padre: Object
      }
    },
    computed: {
      isFolder: function () {
        return this.model.children &&
          this.model.children.length
      },
      canChild: function () {
        return this.last !== this.model.nf_nivcuenta_id
      }
    },
    methods: {
      toggle: function () {
        if (this.isFolder) {
          this.open = !this.open
        }
      },
      addChild: function () {
        this.$emit('addcuenta', this)
      },
      sendParent: function (parent) {
        this.$emit('addcuenta', parent)
      },
      editCuenta: function () {
        this.axios.get('niifs/' + this.model.nf_padre_id)
          .then((response) => {
            this.padre = response.data.data
            this.$emit('editcuenta', this)
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      sendToEdit: function (parent) {
        this.$emit('editcuenta', parent)
      },
      parentOpen: function () {
        return this.$parent.open
      }
    }
  }
</script>

<style scoped>
  .item {
    cursor: pointer;
  }
  .add-cuenta {
    cursor: pointer;
  }
  .bold {
    font-weight: bold;
  }
  ul {
    padding-left: 1em;
    line-height: 1.5em;
  }
  li{
    list-style: none;
  }
</style>
