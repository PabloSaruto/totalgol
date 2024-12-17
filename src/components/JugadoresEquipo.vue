<template>
  <div class="container mx-auto p-6 bg-gray-50 min-h-screen flex justify-center items-center flex-col">
    <!-- Logo para regresar a la página principal -->
    <div class="mb-8">
      <router-link to="/" class="logo-link">
        <img src="@/assets/logo.png" alt="Logo" class="logo w-64 h-auto cursor-pointer" />
      </router-link>
    </div>

    <!-- Información del equipo y jugadores en una tarjeta -->
    <div class="card bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
      <div class="equipo-info text-center mb-8 pb-6 border-b-2 border-gray-200">
        <img :src="getEquipoImage()" alt="Escudo del equipo" class="equipo-imagen w-32 h-32 rounded-full border-4 border-green-500 mb-6 mx-auto object-cover transition-transform transform hover:scale-110" />
        <h2 class="text-4xl font-semibold text-gray-800 mb-2">{{ equipo.NombreEquipo }}</h2>
        <h3 class="text-xl text-gray-600">Entrenador/a: {{ equipo.NombreEntrenador }}</h3>
        <h4 class="text-2xl text-orange-600 font-bold mt-4">Puntos: {{ equipo.Puntos }}</h4>
      </div>

      <!-- Listado de jugadores -->
      <div v-if="jugadores.length > 0" class="jugadores-lista">
        <h2 class="titulo text-2xl text-green-600 uppercase mb-4 tracking-wide">Jugadores</h2>
        <ul class="list-none p-0">
          <li v-for="jugador in jugadores" :key="jugador.ID_jugador" class="jugador-item text-lg py-3 px-5 bg-green-100 rounded-xl shadow-md mb-4 hover:bg-green-200 transition-all cursor-pointer">
            {{ jugador.NombreJugador }}
          </li>
        </ul>
      </div>

      <p v-else class="no-jugadores text-xl text-gray-600 mt-6">No hay jugadores para este equipo.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      equipo: {
        NombreEquipo: '',
        NombreEntrenador: '',
        Puntos: 0
      },
      jugadores: []
    };
  },
  mounted() {
    const idEquipo = this.$route.params.id;
    fetch(`http://localhost/totalgol/backend/getEquiposJugadores.php?id=${idEquipo}`)
      .then(response => response.json())
      .then(data => {
        this.equipo.NombreEquipo = data.NombreEquipo;
        this.equipo.NombreEntrenador = data.NombreEntrenador;
        this.equipo.Puntos = data.Puntos; // Añadir puntos desde la respuesta
        this.jugadores = data.jugadores || [];
      })
      .catch(error => {
        console.error("Error al obtener los datos:", error);
      });
  },
  methods: {
    getEquipoImage() {
      const nombreImagen = this.equipo.NombreEquipo.toLowerCase().replace(/\s+/g, '-');
      return require(`@/assets/img/${nombreImagen}.png`);
    }
  }
};
</script>

<style scoped>
/* Aquí puedes agregar estilos adicionales si los necesitas, pero la mayoría de la personalización se hace con las clases de Tailwind */
</style>
