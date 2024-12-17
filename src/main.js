import Vue from 'vue';
import App from './App.vue';
import router from './router'; // Importamos el archivo router.js
import './assets/tailwind.css'
Vue.config.productionTip = false;

new Vue({
  render: h => h(App),
  router // Aquí añadimos el router
}).$mount('#app');
