<template>
  <div class="bg-gray-50 p-10 rounded-lg shadow-lg max-w-7xl mx-auto">
    <!-- Logo para regresar a la página principal -->
    <div class="text-center mb-12">
      <router-link to="/" class="inline-block">
        <img src="@/assets/logo.png" alt="Logo" class="w-[300px] mx-auto cursor-pointer transition-transform duration-300 transform hover:scale-105" />
      </router-link>
    </div>

    <!-- Título -->
    <h2 class="text-center text-4xl text-green-600 font-semibold mb-10 tracking-wide shadow-md">CLASIFICACIÓN</h2>

    <!-- Tabla de clasificación -->
    <table class="min-w-full border-collapse table-auto bg-white rounded-lg shadow-md overflow-hidden">
      <thead>
        <tr>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6 rounded-tl-lg">Posición</th>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6">Imagen</th>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6">Nombre del equipo</th>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6">Entrenador</th>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6">Color de vestimenta</th>
          <th class="bg-green-600 text-white text-lg font-semibold py-3 px-6 rounded-tr-lg">Puntos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(equipo, index) in equipos" :key="equipo.ID_equipo" :class="getPodiumClass(index)">
          <td class="py-4 px-6 text-lg text-center">{{ index + 1 }}</td>
          <td class="py-4 px-6 text-center">
            <!-- Imagen del equipo -->
            <img 
              :src="getEquipoImage(equipo.NombreEquipo)" 
              :alt="equipo.NombreEquipo" 
              class="w-16 h-16 rounded-full object-cover mx-auto shadow-lg border-2 border-gray-300 transform hover:scale-110 transition-transform duration-300" />
          </td>
          <td class="py-4 px-6 text-lg">{{ equipo.NombreEquipo }}</td>
          <td class="py-4 px-6 text-lg">{{ equipo.NombreEntrenador }}</td>
          <td class="py-4 px-6 text-lg">{{ equipo.ColorVestimenta }}</td>
          <td class="py-4 px-6 text-lg">{{ equipo.puntos }}</td>
        </tr>
      </tbody>
    </table>
    <button @click="goBack" class="btn-back bg-yellow-600 text-white py-4 px-8 rounded-lg w-full mt-10 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
      Volver a la Página Principal
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      equipos: []  // Inicializamos equipos como un array vacío
    };
  },
  mounted() {
    // Llamamos al backend para obtener la clasificación
    fetch('http://localhost/totalgol/backend/getClasificacion.php')
      .then(response => response.json())
      .then(data => {
        if (Array.isArray(data)) {
          this.equipos = data;
        } else {
          console.error("Error: La respuesta no contiene los equipos esperados.");
        }
      })
      .catch(error => {
        console.error("Hubo un problema con la llamada a la API:", error);
      });
  },
  methods: {
    // Método para obtener la imagen del equipo basada en su nombre
    getEquipoImage(nombreEquipo) {
      const nombreImagen = nombreEquipo.toLowerCase().replace(/\s+/g, '-');  // Convertir espacios a guiones
      return require(`@/assets/img/${nombreImagen}.png`);  // Retornar la ruta a la imagen
    },
    goBack() {
      // Volver a la página principal
      this.$router.push({ name: 'Home' });
    },

    // Método para obtener el estilo del podium
    getPodiumClass(index) {
      if (index === 0) {
        return 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-bold';  // Primer lugar (Oro)
      } else if (index === 1) {
        return 'bg-gradient-to-r from-gray-300 to-gray-500 text-white font-bold'; // Segundo lugar (Plata)
      } else if (index === 2) {
        return 'bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold';  // Tercer lugar (Bronce)
      } else if (index >= 7 && index <= 9) {
        return 'bg-red-600 text-white font-bold'; // Descenso (posiciones 8-10)
      } else {
        return '';  // Sin estilo especial para otros lugares
      }
    }
  }
};
</script>

