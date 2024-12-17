ESTILOS BUENOS MAL 
<template>

  <div id="home">
    <!-- Header con logo -->
    <header class="header">
      <img src="@/assets/logo.png" alt="Logo" class="logo" />
      <h1>La app oficial de la Liga de León Aficionado</h1>
    </header>

    <!-- Menú de navegación -->
    <nav class="menu">
      <ul>
        <li><a @click="navigateTo('Competiciones')">Competiciones</a></li>
        <li><a @click="navigateTo('Goleadores')">Goleadores</a></li>
        <li><a @click="navigateTo('Equipos')">Equipos</a></li>
        <li><a @click="navigateTo('Clasificacion')">Clasificación</a></li>
      </ul>
    </nav>

    <!-- Botón Login Árbitros fuera del menú -->
    <div class="login-button">
      <button @click="navigateTo('LoginArbitros')">Login Árbitros</button>
    </div>
    


    <div class="main-container">
      <!-- Contenido principal -->
      <section class="content">
        <h2>Bienvenidos a TotalGol</h2>
        <p>TotalGol es la app oficial de la **Liga de León Aficionado**, diseñada para que disfrutes de todas las noticias, estadísticas y resultados en tiempo real de tu liga favorita.</p>
        
        <p>Con TotalGol podrás seguir de cerca los partidos, los goleadores y las clasificaciones de los equipos. Además, te mantenemos al tanto de todos los eventos importantes para que no te pierdas nada de lo que sucede en la liga.</p>
        <div>
          <!-- Cabecera -->
          <header>
            <h1>Partidos de Fútbol</h1>
          </header>
      
          <main>
            <h2>Lista de Partidos  </h2>
      
            <!-- Cargando partidos -->
            <div v-if="loading" class="loading">Cargando partidos...</div>
      
            <div v-else>
              <!-- Navegación de Jornadas -->
              <div class="navigation">
                <button
                  :disabled="currentJornada === minJornada"
                  @click="prevJornada"
                >
                  ← Jornada Anterior
                </button>
      
                <span>Jornada {{ currentJornada }}</span>
      
                <button
                  :disabled="currentJornada === maxJornada"
                  @click="nextJornada"
                >
                  Jornada Siguiente →
                </button>
              </div>
      
              <!-- Lista de partidos de la jornada actual -->
              <div v-if="partidosPorJornada[currentJornada]">
                <div
                  v-for="partido in partidosPorJornada[currentJornada]"
                  :key="partido.ID_partido"
                  class="partido"
                >
                  <div class="jornada">
                    <strong>Jornada:</strong> {{ partido.Jornada }}
                  </div>
      
                  <div class="fecha">
                    <strong>Fecha:</strong> {{ new Date(partido.Fecha).toLocaleString() }}
                  </div>
      
                  <div class="equipo">
                    <!-- Información del Equipo Local -->
                    <div class="equipo-info">
                      <img
                        :src="getLogoUrl(partido.NombreEquipoLocal)"
                        alt="Logo equipo local"
                        class="equipo-logo"
                      />
                      <div>
                        <strong>Local:</strong> {{ partido.NombreEquipoLocal }}
                        <span>({{ partido.EntrenadorLocal }})</span>
                        <br />
                        <em>Color: {{ partido.ColorVestimentaLocal }}</em>
                      </div>
                    </div>
      
                    <!-- Resultado -->
                    <div class="resultado">
                      <div class="goles">
                        <span class="goles-local">
                          {{ partido.GolesLocal !== null ? partido.GolesLocal : 'X' }}
                        </span>
                        -
                        <span class="goles-visitante">
                          {{ partido.GolesVisitante !== null ? partido.GolesVisitante : 'X' }}
                        </span>
                      </div>
      
                      <!-- Jugadores que marcaron goles para el equipo local -->
                      <div v-if="partido.NombreJugadores && partido.GolesJugadores" class="jugadores">
                        <strong>Goles Local:</strong>
                        <ul>
                          <li
                            v-for="(jugador, index) in jugadoresLocal(partido)"
                            :key="index"
                          >
                            {{ jugador }} ({{ golesLocal(partido)[index] }} gol{{ golesLocal(partido)[index] > 1 ? 'es' : '' }})
                          </li>
                        </ul>
                      </div>
      
                      <!-- Jugadores que marcaron goles para el equipo visitante -->
                      <div v-if="partido.NombreJugadoresVisitante && partido.GolesJugadoresVisitante" class="jugadores">
                        <strong>Goles Visitante:</strong>
                        <ul>
                          <li
                            v-for="(jugador, index) in jugadoresVisitante(partido)"
                            :key="index"
                          >
                            {{ jugador }} ({{ golesVisitante(partido)[index] }} gol{{ golesVisitante(partido)[index] > 1 ? 'es' : '' }})
                          </li>
                        </ul>
                      </div>
                    </div>
      
                    <!-- Información del Equipo Visitante -->
                    <div class="equipo-info">
                      <img
                        :src="getLogoUrl(partido.NombreEquipoVisitante)"
                        alt="Logo equipo visitante"
                        class="equipo-logo"
                      />
                      <div>
                        <strong>Visitante:</strong> {{ partido.NombreEquipoVisitante }}
                        <span>({{ partido.EntrenadorVisitante }})</span>
                        <br />
                        <em>Color: {{ partido.ColorVestimentaVisitante }}</em>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
      
              <!-- Mensaje si no hay partidos -->
              <div v-else class="no-partidos">
                No hay partidos para esta jornada.
              </div>
            </div>
          </main>
        </div>
        <!-- Espacio para la imagen del campo de fútbol -->
        <div class="football-field">
          <img src="@/assets/recursos/campodefutbol.jpg" alt="Campo de Fútbol" class="field-image" />
        </div>

        <div class="video-container">
          <h3>Revive los mejores goles de la historia de la Cultural Leonesa</h3>
          <p>Disfruta de este resumen épico con los goles más impresionantes del equipo de la Cultural Leonesa. ¡Una muestra de su legado en la liga!</p>
          <iframe width="560" height="315" src="https://www.youtube.com/embed/IaPp9fbiqEY?si=KxTKQhKCjKDMgZSx" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>

        <!-- Texto de redes sociales -->
        <div class="social-text">
          <h3>¡Síguenos en nuestras redes sociales!</h3>
        </div>

        <!-- Redes Sociales -->
        <div class="social-icons">
          <a href="https://www.facebook.com" target="_blank">
            <img src="@/assets/rs/face.png" alt="Facebook" class="social-icon" />
          </a>
          <a href="https://www.instagram.com" target="_blank">
            <img src="@/assets/rs/insta.png" alt="Instagram" class="social-icon" />
          </a>
          <a href="https://www.youtube.com" target="_blank">
            <img src="@/assets/rs/you.png" alt="YouTube" class="social-icon" />
          </a>
        </div>

        <!-- Mapa de navegación -->
        <div class="site-map">
          <h3>Mapa del Sitio</h3>
          <ul>
            <li><a href="#competitions">Competiciones</a></li>
            <li><a href="#statistics">Goleadores</a></li>
            <li><a href="#teams">Equipos</a></li>
            <li><a href="#contact">Contacto</a></li>
          </ul>
        </div>

        <!-- Formulario de contacto -->
        <div class="contact-form">
          <h3>Contacto</h3>
          <form @submit.prevent="submitForm">
            <label for="name">Nombre:</label>
            <input type="text" id="name" v-model="contact.name" required />

            <label for="email">Email:</label>
            <input type="email" id="email" v-model="contact.email" required />

            <label for="message">Mensaje:</label>
            <textarea id="message" v-model="contact.message" required></textarea>

            <button type="submit">Enviar</button>
          </form>
        </div>
      </section>

      <!-- Sección del blog de noticias -->
      <aside class="blog-section">
        <h2>Últimas Noticias</h2>
        <div class="blog-post" v-for="noticia in noticias" :key="noticia.id">
          <h3>{{ noticia.titulo }}</h3>
          <p class="fecha">{{ noticia.fecha }}</p>
          <p>{{ noticia.contenido }}</p>
        </div>
      </aside>
  
    

  </div>
  <footer class="footer">
      <p>&copy; 2024 TotalGol. Todos los derechos reservados.</p>
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
      partidosPorJornada: {}, // Almacena los partidos agrupados por jornada
      currentJornada: 1, // Jornada visible
      minJornada: 1, // Jornada mínima (inicial)
      maxJornada: 1, // Jornada máxima (final)
      noticias: [
        { id: 1, titulo: '¡Los Guerreros de León invictos!', fecha: '2024-11-25', contenido: 'El equipo ha ganado todos sus partidos hasta ahora y sigue demostrando su poderío.' },
        { id: 2, titulo: 'El delantero estrella de los Tigres', fecha: '2024-11-26', contenido: 'Con 15 goles, el delantero de los Tigres es el máximo goleador hasta el momento.' },
        { id: 3, titulo: '¡Partido crucial entre los Leones y los Halcones!', fecha: '2024-11-27', contenido: 'Este fin de semana se jugará uno de los partidos más esperados de la temporada.' }
      ],
      contact: { name: '', email: '', message: '' }
    };
  },

  mounted() {
    this.fetchPartidos();
  },

  methods: {
    navigateTo(page) {
      this.$router.push({ name: page });
    },
    submitForm() {
      alert(`Mensaje enviado por ${this.contact.name}`);
      // Lógica para enviar los datos del formulario
    },

    // Obtener los partidos de la API
    async fetchPartidos() {
      try {
        const response = await fetch("http://localhost/totalgol/backend/homepage.php");
        const data = await response.json();

        if (data.status === "success") {
          this.partidos = data.partidos;
          this.groupPartidosPorJornada();
        } else {
          console.error("No se encontraron partidos.");
        }
      } catch (error) {
        console.error("Error al obtener los datos:", error);
      } finally {
        this.loading = false;
      }
    },

    // Agrupar partidos por jornada
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

    // Obtener la URL del logo del equipo
    getLogoUrl(nombreEquipo) {
      const nombreImagen = nombreEquipo.toLowerCase().replace(/\s+/g, '-');
      return require(`@/assets/img/${nombreImagen}.png`);
    },

    // Cambiar a la jornada anterior
    prevJornada() {
      if (this.currentJornada > this.minJornada) {
        this.currentJornada--;
      }
    },

    // Cambiar a la jornada siguiente
    nextJornada() {
      if (this.currentJornada < this.maxJornada) {
        this.currentJornada++;
      }
    },

    // Obtener los jugadores del equipo local
    jugadoresLocal(partido) {
      return partido.NombreJugadores ? partido.NombreJugadores.split(', ') : [];
    },

    // Obtener los goles del equipo local
    golesLocal(partido) {
      return partido.GolesJugadores ? partido.GolesJugadores.split(', ') : [];
    },

    // Obtener los jugadores del equipo visitante
    jugadoresVisitante(partido) {
      return partido.NombreJugadoresVisitante ? partido.NombreJugadoresVisitante.split(', ') : [];
    },

    // Obtener los goles del equipo visitante
    golesVisitante(partido) {
      return partido.GolesJugadoresVisitante ? partido.GolesJugadoresVisitante.split(', ') : [];
    },
  },
};
</script>

<style scoped>

/* General */
#home {
  font-family: 'Arial', sans-serif;
  background: linear-gradient(135deg, #4CAF50, #04ecec);
  color: #fff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 50px;
}

/* Header */
.header {
  text-align: center;
  padding: 30px;
  background-color: rgba(0, 0, 0, 0.7);
  width: 100%;
}

.logo {
  width: 500px;
  margin-bottom: 20px;
}

.header h1 {
  font-size: 2.5em;
  color: #fff;
  margin: 10px 0;
}

.header p {
  color: #e0e0e0;
  font-size: 1.2em;
}

/* Menu */
.menu {
  background-color: rgba(233, 241, 117, 0.8);
  border-radius: 20px;
  width: 80%;
  margin: 20px 0;
  padding: 10px 0;
}

.menu ul {
  display: flex;
  justify-content: space-around;
  list-style: none;
  padding: 0;
}

.menu li a {
  color: #333;
  text-decoration: none;
  font-weight: bold;
  font-size: 1.2em;
  padding: 12px 20px;
  border-radius: 5px;
  transition: all 0.3s ease;
}

.menu li a:hover {
  background-color: #3e8e41;
  color: #fff;
  transform: scale(1.05);
}

/* Main container */
.main-container {
  display: flex;
  justify-content: space-between;
  width: 80%;
  margin-bottom: 30px;
}

.content {
  width: 70%;
  font-size: 1.1em;
}

.content h2 {
  font-size: 2.5em;
  margin-bottom: 20px;
}

.content p {
  margin-bottom: 15px;
  line-height: 1.8;
}

.football-field img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  margin-top: 20px;
}

/* Blog */
.blog-section {
  width: 28%;
}

.blog-section h2 {
  font-size: 2em;
}

.blog-post {
  background-color: #333;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 8px;
}

.blog-post h3 {
  font-size: 1.5em;
}

.blog-post .fecha {
  font-size: 0.9em;
  color: #aaa;
}

.blog-post p {
  font-size: 1.1em;
  line-height: 1.6;
}

/* Footer */
.footer {
  width: 100%;
  background-color: #333;
  padding: 20px;
  text-align: center;
  margin-top: 50px;
}

.footer p {
  color: #fff;
  font-size: 1.1em;
}

/* Botón Login Árbitros */
.login-button {
  margin-top: 20px;
  text-align: center;
}

.login-button button {
  background-color: #4CAF50;
  color: white;
  font-size: 1.2em;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.login-button button:hover {
  background-color: #45a049;
}
/* Redes Sociales */
.social-icons {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
}

.social-icon {
  width: 80px; /* Ajusta el tamaño del icono */
  height: 80px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.social-icon:hover {
  transform: scale(1.2); /* Agranda el icono un poco al pasar el ratón */
}







/* Estilos generales */
body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 0;
}

/* Cabecera */
header {
  background-color: #333;
  color: #fff;
  padding: 10px 20px;
  text-align: center;
}

/* Estilos del main */
main {
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
  color: #333;
}

/* Navegación */
.navigation {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.navigation button {
  padding: 5px 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.navigation button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

/* Estilos de los partidos */
.partido {
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 10px;
  padding: 10px;
  background: #9ab4e6;
}

.partido:nth-child(odd) {
  background: rgb(162, 163, 230);
}

.equipo {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.equipo-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.equipo-logo {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid #ddd;
}

/* Estilos de la fecha y jornada */
.fecha,
.jornada {
  font-size: 0.9em;
  color: #666;
}

/* Mensajes de carga */
.loading {
  text-align: center;
  font-size: 1.2em;
  color: #333;
}

/* Mensaje sin partidos */
.no-partidos {
  text-align: center;
  font-size: 1em;
  color: #666;
}

/* Resultado del partido */
.resultado {
  font-size: 1.2em;
  font-weight: bold;
  color: #333;
}

.goles {
  font-size: 1.5em;
  font-weight: bold;
  color: #333;
}

.goles-local {
  color: green;
}

.goles-visitante {
  color: red;
}
</style>