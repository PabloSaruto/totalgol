<template>
  <div class="container mx-auto p-6 bg-gray-50 rounded-lg shadow-xl">
    <!-- Logo -->
    <div class="flex justify-center mb-8">
      <router-link to="/" class="logo-link">
        <img src="@/assets/logo.png" alt="Logo" class="w-80 h-auto transition-transform transform hover:scale-105" />
      </router-link>
    </div>

    <!-- Título -->
    <h2 class="text-4xl font-semibold text-center text-green-600 mb-12 tracking-wide shadow-md">
      GOLEADORES
    </h2>

    <!-- Tabla de goleadores -->
    <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden">
      <thead>
        <tr class="bg-green-600 text-white text-lg font-semibold">
          <th class="py-3 px-6 text-center rounded-tl-lg">Nombre del Goleador</th>
          <th class="py-3 px-6 text-center">Equipo</th>
          <th class="py-3 px-6 text-center rounded-tr-lg">Total de Goles</th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="goleador in goleadores" 
          :key="goleador.ID_jugador" 
          class="cursor-pointer bg-red-500 hover:bg-teal-300 transition-colors duration-300 text-white"
        >
          <td class="py-4 px-6 text-center text-lg border-t border-gray-300">{{ goleador.NombreJugador }}</td>
          <td class="py-4 px-6 text-center text-lg border-t border-gray-300">{{ goleador.NombreEquipo }}</td>
          <td class="py-4 px-6 text-center text-lg border-t border-gray-300">{{ goleador.TotalGoles }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Botón para volver atrás -->
    <router-link to="/">
      <button class="btn-back bg-yellow-600 text-white py-4 px-8 rounded-lg w-full mt-10 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
        Volver a la Página Principal
      </button>
    </router-link>
  </div>
</template>

<script>
export default {
  data() {
    return {
      goleadores: [] // Inicializamos goleadores como un array vacío
    };
  },
  mounted() {
    window.scrollTo(0, 0);

    fetch('http://localhost/totalgol/backend/getGoleadores.php')
      .then(response => response.json())
      .then(data => {
        if (Array.isArray(data)) {
          this.goleadores = data;
        } else {
          console.error("Error: La respuesta no contiene los goleadores esperados.");
        }
      })
      .catch(error => {
        console.error("Hubo un problema con la llamada a la API:", error);
      });
  },
};
</script>
