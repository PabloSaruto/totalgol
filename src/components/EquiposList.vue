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
      EQUIPOS
    </h2>

    <!-- Tabla de equipos -->
    <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden">
      <thead>
        <tr class="bg-green-600 text-white text-lg font-semibold">
          <th class="py-3 px-6 text-center rounded-tl-lg">Imagen</th>
          <th class="py-3 px-6 text-left">Nombre del equipo</th>
          <th class="py-3 px-6 text-left">Entrenador</th>
          <th class="py-3 px-6 text-left rounded-tr-lg">Color de vestimenta</th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="equipo in equipos" 
          :key="equipo.ID_equipo" 
          @click="navigateToEquipo(equipo.ID_equipo)"
          class="cursor-pointer hover:bg-gray-100 transition-colors duration-300"
        >
          <td class="py-4 px-6 text-center">
            <img 
              :src="getEquipoImage(equipo.NombreEquipo)" 
              :alt="equipo.NombreEquipo" 
              class="w-16 h-16 rounded-full border-2 border-gray-300 object-cover mx-auto transition-transform transform hover:scale-110" 
            />
          </td>
          <td class="py-4 px-6">{{ equipo.NombreEquipo }}</td>
          <td class="py-4 px-6">{{ equipo.NombreEntrenador }}</td>
          <td class="py-4 px-6">{{ equipo.ColorVestimenta }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script>
export default {
  data() {
    return {
      equipos: []
    };
  },
  mounted() {
    fetch('http://localhost/totalgol/backend/getEquipos.php')
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
    navigateToEquipo(idEquipo) {
      this.$router.push({ name: 'JugadoresEquipo', params: { id: idEquipo } });
    },
    getEquipoImage(nombreEquipo) {
      const nombreImagen = nombreEquipo.toLowerCase().replace(/\s+/g, '-');
      return require(`@/assets/img/${nombreImagen}.png`);
    }
  }
};
</script>

