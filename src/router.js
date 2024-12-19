import Vue from 'vue';
import Router from 'vue-router';

// Importar los componentes
import HomePage from './components/HomePage.vue'; // Importa el componente HomePage.vue
import EquiposList from './components/EquiposList.vue'; // Importa el componente EquiposList.vue
import GoleadoresList from './components/GoleadoresList.vue';
import ClasificacionLiga from './components/ClasificacionLiga.vue';
import JugadoresEquipo from './components/JugadoresEquipo.vue';
import LoginArbitros from './components/LoginArbitros.vue';
import HomePageArbitros from './components/HomePageArbitros.vue';
import InsertarResultados from './components/insertarResultados.vue';




Vue.use(Router); // Habilitar Vue Router

const router = new Router({
  mode: 'history',  // Usamos el modo 'history' para que las URLs sean más limpias
  routes: [
    {
      path: '/', // Ruta principal
      name: 'Home', // Nombre de la ruta
      component: HomePage // Cargamos el componente HomePage
    },
    {
      path: '/equipos', // Ruta para Equipos
      name: 'Equipos',  // Nombre de la ruta
      component: EquiposList // Cargamos el componente EquiposList
    },
    {
      path: '/clasificacion', // Ruta para Equipos
      name: 'Clasificacion',  // Nombre de la ruta
      component: ClasificacionLiga // Cargamos el componente ClasificacionLiga
    },
    {
      path: '/equipo/:id',
      name: 'JugadoresEquipo',
      component: JugadoresEquipo,
      props: true  // Pasar parámetros como props
    },
    {
        path: '/goleadores', // Ruta para Equipos
        name: 'Goleadores',  // Nombre de la ruta
        component: GoleadoresList // Cargamos el componente GoleadoresList
      },
      {
        path: '/loginArbitros',
        name: 'LoginArbitros',
        component: LoginArbitros,
        
      },
      {
        path: '/home-arbitros',
        name: 'HomePageArbitros',
        component: HomePageArbitros
      },
      {
        path: '/insertar-resultados/:partidoId',
        name: 'InsertarResultados',
        component: InsertarResultados
      }


      
  ]
});

export default router;
