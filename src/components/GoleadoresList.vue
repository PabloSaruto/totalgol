<template>
  <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
    <!-- Logo para regresar a la página principal -->
    <div class="text-center mb-8">
      <router-link to="/" class="logo-link">
        <img src="@/assets/logo.png" alt="Logo" class="logo w-96 h-auto mx-auto cursor-pointer" />
      </router-link>
    </div>
  
    <h2 class="titulo text-4xl text-green-600 font-semibold text-center mb-8">GOLEADORES</h2>
      
    <!-- Tabla de goleadores -->
    <table class="goleador-table w-full border-collapse shadow-md bg-red-100">
      <thead>
        <tr class="bg-green-600 text-white">
          <th class="py-4 px-6 text-left font-bold text-lg rounded-tl-lg">Nombre del Goleador</th>
          <th class="py-4 px-6 text-left font-bold text-lg">Equipo</th>
          <th class="py-4 px-6 text-left font-bold text-lg rounded-tr-lg">Total de Goles</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="goleador in goleadores" :key="goleador.ID_jugador" class="hover:bg-teal-100 transition-all">
          <td class="py-3 px-6 text-lg border-t border-gray-300">{{ goleador.NombreJugador }}</td>
          <td class="py-3 px-6 text-lg border-t border-gray-300">{{ goleador.NombreEquipo }}</td>
          <td class="py-3 px-6 text-lg border-t border-gray-300">{{ goleador.TotalGoles }}</td>
        </tr>
      </tbody>
    </table>
    
    <!-- Botón para volver atrás -->
    <button @click="goBack" class="btn-back bg-yellow-600 text-white py-4 px-8 rounded-lg w-full mt-10 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
      Volver a la Página Principal
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      goleadores: []  // Inicializamos goleadores como un array vacío
    };
  },
  mounted() {
    fetch('http://localhost/totalgol/backend/getGoleadores.php')  // Cambiar a la URL correcta
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
  methods: {
    goBack() {
      // Volver a la página principal
      this.$router.push({ name: 'Home' });
    }
  }
};
</script>
