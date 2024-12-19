<template>
  <div class="home-arbitros p-8 max-w-5xl mx-auto bg-white rounded-lg shadow-xl">
    <!-- Header -->
    <div class="header flex items-center justify-center pb-8 mb-10 border-b-4 border-green-600">
      <img src="@/assets/logo.png" alt="Logo" class="logo w-56 h-auto mr-8" />
      <h1 class="text-4xl font-bold text-green-600">Árbitros</h1>
    </div>

    <!-- Info del árbitro -->
    <div class="info-arbitro text-center mb-12">
      <h2 class="text-3xl font-semibold text-gray-800 mb-6">Bienvenido, {{ nombreArbitro }}</h2>
      <div class="info-item mb-4 text-lg text-gray-600">
        <strong>ID:</strong> <span class="text-gray-800">{{ idArbitro }}</span>
      </div>
      <div class="info-item mb-4 text-lg text-gray-600">
        <strong>Usuario:</strong> <span class="text-gray-800">{{ usuarioArbitro }}</span>
      </div>
      <div class="info-item mb-6 text-lg text-gray-600">
        <strong>Teléfono:</strong> <span class="text-gray-800">{{ telefonoArbitro }}</span>
      </div>
    </div>

    <!-- Partidos -->
    <div v-if="partidos.length > 0" class="partidos">
      <h2 class="titulo-partidos text-3xl font-semibold text-gray-800 mb-8 text-center">Partidos que vas a arbitrar:</h2>
      <div class="partidos-list space-y-8">
        <div v-for="partido in partidos" :key="partido.ID_partido" class="partido-card bg-gray-50 p-8 rounded-lg shadow-lg">
          <!-- Título con la jornada -->
          <div class="titulo-jornada text-center bg-green-600 text-white py-4 rounded-lg mb-6">
            <h3 class="text-2xl font-semibold">JORNADA {{ partido.Jornada }}</h3>
          </div>

          <div class="partido-info flex justify-between items-start mb-6">
            <!-- Equipo Local -->
            <div class="equipo-info flex items-center w-1/2 space-x-6">
              <img :src="getEquipoLogo(partido.NombreEquipoLocal)" alt="Logo Local" class="equipo-logo w-20 h-20" />
              <div class="equipo-nombre">
                <span class="equipo font-semibold text-xl">{{ partido.NombreEquipoLocal }}</span>
                <div class="entrenador text-sm text-gray-500">Entrenador: {{ partido.EntrenadorLocal }}</div>
                <div class="color-vestimenta text-sm text-gray-500">Vestimenta: {{ partido.ColorVestimentaLocal }}</div>
              </div>
            </div>

            <span class="vs text-3xl text-gray-400 mx-8">vs</span>

            <!-- Equipo Visitante -->
            <div class="equipo-info flex items-center w-1/2 space-x-6">
              <img :src="getEquipoLogo(partido.NombreEquipoVisitante)" alt="Logo Visitante" class="equipo-logo w-20 h-20" />
              <div class="equipo-nombre">
                <span class="equipo font-semibold text-xl">{{ partido.NombreEquipoVisitante }}</span>
                <div class="entrenador text-sm text-gray-500">Entrenador: {{ partido.EntrenadorVisitante }}</div>
                <div class="color-vestimenta text-sm text-gray-500">Vestimenta: {{ partido.ColorVestimentaVisitante }}</div>
              </div>
            </div>
          </div>

          <div class="partido-fecha text-center text-gray-600 text-lg">
            <span>Fecha: {{ formatFecha(partido.Fecha) }}</span>
          </div>

          <!-- Mostrar resultado -->
          <div class="partido-resultado text-center mt-4">
            <strong>Resultado: </strong>
            <span>{{ partido.Resultado }}</span> <!-- Aquí mostramos el resultado o "X" -->
          </div>

          <!-- Botón para insertar resultados -->
          <div class="text-center mt-6">
            <router-link :to="{ name: 'InsertarResultados', params: { partidoId: partido.ID_partido } }" class="btn-insertar bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700">
              Insertar Resultados
            </router-link>
          </div>

        </div>
      </div>
    </div>

    <!-- Si no hay partidos -->
    <div v-else class="no-partidos text-center text-lg text-gray-500 my-10">
      <p>No tienes partidos asignados.</p>
    </div>

    <!-- Botón para volver atrás -->
    <button @click="goBack" class="btn-back bg-green-600 text-white py-4 px-8 rounded-lg w-full mt-10 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
      Volver a la Página Principal
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuarioArbitro: '',
      nombreArbitro: '',
      telefonoArbitro: '',
      idArbitro: '',
      partidos: [] // Para almacenar los partidos del árbitro
    };
  },
  created() {
    // Recuperar datos desde localStorage
    const usuario = localStorage.getItem('usuario');
    const id = localStorage.getItem('ID_arbitro');
    const nombre = localStorage.getItem('nombre');
    const telefono = localStorage.getItem('telefono');

    if (usuario && nombre && telefono && id) {
      this.usuarioArbitro = usuario;
      this.nombreArbitro = nombre;
      this.telefonoArbitro = telefono;
      this.idArbitro = id;  // Recuperar el ID del árbitro

      // Obtener los partidos del árbitro
      this.getPartidos();
    } else {
      // Si no hay datos en localStorage, redirigir al login
      this.$router.push({ name: 'LoginArbitros' });
    }
  },
  methods: {
    async getPartidos() {
      try {
        // Enviar el ID del árbitro al backend para obtener los partidos
        const response = await fetch(`http://localhost/totalgol/backend/get_partidos_arbitro.php?ID_arbitro=${this.idArbitro}`);
        const data = await response.json();

        if (data.status === 'success') {
          this.partidos = data.partidos;
        } else {
          this.partidos = [];
        }
      } catch (error) {
        console.error('Error al obtener los partidos:', error);
      }
    },
    getEquipoLogo(nombreEquipo) {
      // Convertir el nombre del equipo a minúsculas y reemplazar espacios por guiones
      const nombreImagen = nombreEquipo.toLowerCase().replace(/\s+/g, '-');
      
      return require(`@/assets/img/${nombreImagen}.png`);
    },
    formatFecha(fecha) {
      const date = new Date(fecha);
      const opciones = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
      return date.toLocaleDateString('es-ES', opciones);
    },
    goBack() {
      // Redirigir al login o la página anterior
      this.$router.push({ name: 'Home' });
    }
  }
};
</script>
