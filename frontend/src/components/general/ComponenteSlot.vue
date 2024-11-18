<template>
  <div>
    <h2>Componente con Slots</h2>
    <slot name="name" :todo="seleccion ? seleccion.variables : null">{{seleccion ? seleccion.nombre : ''}}</slot>
  </div>
</template>

<script>
    export default {
      name: 'ComponenteSlot',
      props: ['seleccion'],
      mounted () {
        console.log('el tal', this.seleccion)
      }
    }
</script>

<style scoped>

</style>
