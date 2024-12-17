<!-- src/App.vue -->
 
<template>
  <div id="app">
    <!-- Aquí se renderiza el componente del router -->
    <router-view></router-view>  
  </div>
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>
/* Estilos generales para la aplicación */
#app {
  font-family: 'Arial', sans-serif;
}
</style>
