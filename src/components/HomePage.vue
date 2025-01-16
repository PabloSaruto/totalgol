<template>
  <div class="min-h-screen bg-gradient-to-br from-green-500 to-cyan-400 flex flex-col items-center pb-12">
    <!-- Header -->
    <header class="w-full bg-black bg-opacity-70 py-12 text-center">
      <img src="@/assets/logo.png" alt="Logo de Liga" class="w-[300px] mx-auto mb-6" />
      <h1 class="text-4xl text-white font-bold mb-3">Bienvenido a TotalGol</h1>
      <p class="text-lg text-gray-200">La app oficial de la Liga de León Aficionado. Todos los resultados, goleadores y estadísticas, en tiempo real.</p>
    </header>

    <!-- Menú de Navegación -->
    <nav class="w-full bg-yellow-200 bg-opacity-90 py-4 shadow-lg">
      <div class="container mx-auto flex justify-around items-center">
        <a @click="navigateTo('Goleadores')" class="nav-item">Goleadores</a>
        <a @click="navigateTo('Equipos')" class="nav-item">Equipos</a>
        <a @click="navigateTo('Clasificacion')" class="nav-item">Clasificación</a>
      </div>
    </nav>

    <!-- Login Árbitros -->
    <div class="mt-10 text-center">
      <button @click="navigateTo('LoginArbitros')" class="bg-orange-500 text-white hover:bg-orange-600 focus:ring-2 focus:ring-orange-400 font-medium py-2 px-4 rounded"
      >Acceder como Árbitro</button>
    </div>

    <!-- Contenido Principal -->
    <main class="w-full container mx-auto mt-16 px-6 lg:flex lg:justify-between">
      <!-- Partidos -->
      <section class="lg:w-7/12 space-y-8">
        <header>
          <h2 class="text-3xl text-gray-800 font-semibold mb-6">Partidos de Fútbol</h2>
          <p class="text-lg text-gray-600">Sigue los resultados en vivo, estadísticas y goleadores de cada jornada.</p>
        </header>

        <!-- Cargando partidos -->
        <div v-if="loading" class="text-center text-xl text-gray-600">Cargando partidos...</div>

        <!-- Jornada y Partidos -->
        <div v-else>
          <div class="flex justify-between items-center mb-6">
            <button :disabled="currentJornada === minJornada" @click="prevJornada" class="btn-secondary">← Jornada Anterior</button>
            <span class="text-xl font-semibold">Jornada {{ currentJornada }}</span>
            <button :disabled="currentJornada === maxJornada" @click="nextJornada" class="btn-secondary">Jornada Siguiente →</button>
          </div>

          <div v-if="partidosPorJornada[currentJornada]" class="space-y-6">
            <!-- Lista de Partidos -->
            <div v-for="partido in partidosPorJornada[currentJornada]" :key="partido.ID_partido" class="partido-card">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <strong class="text-lg">Jornada:</strong> {{ partido.Jornada }}<br />
                  <strong class="text-lg">Fecha:</strong> {{ new Date(partido.Fecha).toLocaleString() }}
                </div>
               
              </div>
z
              <!-- Equipos -->
              <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                  <img :src="getLogoUrl(partido.NombreEquipoLocal)" alt="Logo equipo local" class="team-logo" />
                  <div>
                    <strong class="text-lg">{{ partido.NombreEquipoLocal }}</strong><br />
                    <span class="text-sm text-gray-600">Color: {{ partido.ColorVestimentaLocal }}</span>
                  </div>
                </div>
                <div class="text-center text-xl font-bold">
                  <span class="text-green-500">{{ partido.GolesLocal }}</span> - <span class="text-red-500">{{ partido.GolesVisitante }}</span>
                </div>
                <div class="flex items-center space-x-4">
                  <img :src="getLogoUrl(partido.NombreEquipoVisitante)" alt="Logo equipo visitante" class="team-logo" />
                  <div>
                    <strong class="text-lg">{{ partido.NombreEquipoVisitante }}</strong><br />
                    <span class="text-sm text-gray-600">Color: {{ partido.ColorVestimentaVisitante }}</span>
                  </div>
                </div>
              </div>

              <!-- Goleadores -->
              <div v-if="partido.NombreJugadores && partido.GolesJugadores" class="mt-4">
                <strong class="text-lg">Goles:</strong>
                <ul>
                  <li v-for="(jugador, index) in jugadoresLocal(partido)" :key="index">
                    {{ jugador }} ({{ golesLocal(partido)[index] }} gol{{ golesLocal(partido)[index] > 1 ? 'es' : '' }})
                  </li>
                </ul>
              </div>

              <div v-if="partido.NombreJugadoresVisitante && partido.GolesJugadoresVisitante" class="mt-4">
                <strong class="text-lg">Goles Visitante:</strong>
                <ul>
                  <li v-for="(jugador, index) in jugadoresVisitante(partido)" :key="index">
                    {{ jugador }} ({{ golesVisitante(partido)[index] }} gol{{ golesVisitante(partido)[index] > 1 ? 'es' : '' }})
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div v-else class="text-center text-xl text-gray-600">No hay partidos para esta jornada.</div>
        </div>
      </section>

      <!-- Video y Imagen -->
      <div class="lg:w-4/12 lg:flex lg:flex-col lg:items-center lg:space-y-8">
        <div class="max-w-sm mx-auto">
          <h3 class="text-2xl text-gray-800 font-semibold mb-4">Revive los mejores goles de la Liga</h3>
          <iframe width="100%" height="315" src="https://www.youtube.com/embed/IaPp9fbiqEY?si=KxTKQhKCjKDMgZSx" title="YouTube video player" frameborder="0" allowfullscreen class="rounded-lg shadow-lg"></iframe>
        </div>
        <div>
          <img src="@/assets/recursos/campodefutbol.jpg" alt="Campo de fútbol" class="rounded-lg shadow-lg max-w-full" />
        </div>
      </div>
    </main>

    <!-- Footer con Redes Sociales -->
    <footer class="w-full bg-black bg-opacity-70 py-8 text-center text-white">
      <h3 class="text-xl mb-6">¡Síguenos en nuestras redes sociales!</h3>
      <div class="flex justify-center gap-6">
        <a href="https://www.facebook.com" target="_blank" class="social-icon"><img src="@/assets/rs/face.png" alt="Facebook" /></a>
        <a href="https://www.instagram.com" target="_blank" class="social-icon"><img src="@/assets/rs/insta.png" alt="Instagram" /></a>
        <a href="https://www.youtube.com" target="_blank" class="social-icon"><img src="@/assets/rs/you.png" alt="YouTube" /></a>
      </div>
      <div class="mt-6">
        <h4 class="text-lg mb-3">Mapa del Sitio</h4>
        <ul class="flex justify-center gap-8 text-lg">
          <li><a href="#competitions" class="footer-link">Competiciones</a></li>
          <li><a href="#statistics" class="footer-link">Goleadores</a></li>
          <li><a href="#teams" class="footer-link">Equipos</a></li>
          <li><a href="#contact" class="footer-link">Contacto</a></li>
        </ul>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: "HomePage",
  data() {
    return {
      loading: true,
      partidos: [],
      partidosPorJornada: {},
      currentJornada: 1,
      minJornada: 1,
      maxJornada: 1,
    };
  },
  mounted() {
    this.fetchPartidos();
  },
  methods: {
    navigateTo(page) {
      this.$router.push({ name: page });
    },
    async fetchPartidos() {
      try {
        const response = await fetch("http://localhost/totalgol/backend/homepage.php");
        const data = await response.json();
        if (data.status === "success") {
          this.partidos = data.partidos;
          this.groupPartidosPorJornada();
        }
      } catch (error) {
        console.error("Error al obtener los partidos:", error);
      } finally {
        this.loading = false;
      }
    },
    groupPartidosPorJornada() {
      this.partidosPorJornada = this.partidos.reduce((acc, partido) => {
        const jornada = partido.Jornada;
        if (!acc[jornada]) {
          acc[jornada] = [];
        }
        acc[jornada].push(partido);
        return acc;
      }, {});
      const jornadas = Object.keys(this.partidosPorJornada).map(Number);
      this.minJornada = Math.min(...jornadas);
      this.maxJornada = Math.max(...jornadas);
      this.currentJornada = this.minJornada;
    },
    getLogoUrl(nombreEquipo) {
      const nombreImagen = nombreEquipo.toLowerCase().replace(/\s+/g, '-');
      return require(`@/assets/img/${nombreImagen}.png`);
    },
    prevJornada() {
      if (this.currentJornada > this.minJornada) {
        this.currentJornada--;
      }
    },
    nextJornada() {
      if (this.currentJornada < this.maxJornada) {
        this.currentJornada++;
      }
    },
    jugadoresLocal(partido) {
      return partido.NombreJugadores ? partido.NombreJugadores.split(', ') : [];
    },
    golesLocal(partido) {
      return partido.GolesJugadores ? partido.GolesJugadores.split(', ') : [];
    },
    jugadoresVisitante(partido) {
      return partido.NombreJugadoresVisitante ? partido.NombreJugadoresVisitante.split(', ') : [];
    },
    golesVisitante(partido) {
      return partido.GolesJugadoresVisitante ? partido.GolesJugadoresVisitante.split(', ') : [];
    },
  },
};
</script>

<style>
/* Tailwind already provides utility classes for layout and responsiveness.
Here are custom styles for specific elements. */
.nav-item {
  @apply text-lg font-medium text-gray-800 hover:text-green-600 transition-all ease-in-out duration-300;
}
.btn-primary {
  @apply bg-green-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-green-600 transition-all ease-in-out duration-300;
}
.btn-secondary {
  @apply bg-blue-500 text-white py-2 px-6 rounded-lg text-lg font-semibold hover:bg-blue-600 transition-all ease-in-out duration-300;
}
.partido-card {
  @apply bg-white p-6 rounded-lg shadow-lg border border-gray-200;
}
.team-logo {
  @apply w-16 h-16 rounded-full border-2 border-gray-200 object-cover;
}
.social-icon {
  @apply w-12 h-12 rounded-full p-2 hover:scale-110 transition-transform ease-in-out;
}
.footer-link {
  @apply text-gray-600 hover:text-green-500 transition-all ease-in-out duration-300;
}
</style>
