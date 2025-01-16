<template>
  <div class="insertar-resultados p-8 max-w-5xl mx-auto bg-white rounded-lg shadow-xl">
    <h1 class="text-3xl font-semibold text-green-600 text-center mb-6">Insertar Resultados</h1>

    <div v-if="partido" class="mb-6">
      <h2 class="text-2xl font-semibold text-gray-800 text-center mb-4">
        Partido: {{ partido.NombreEquipoLocal }} vs {{ partido.NombreEquipoVisitante }}
      </h2>
      <p class="text-lg text-gray-700 text-center">
        Jornada: {{ partido.Jornada }} | Fecha: {{ formatFecha(partido.Fecha) }}
      </p>
    </div>

    <form @submit.prevent="submitResultados" class="space-y-6">
      <div class="form-group">
        <label for="golesLocal" class="text-lg text-gray-700">Goles Equipo Local</label>
        <input type="number" v-model="golesLocal" id="golesLocal" class="form-input w-full p-3 border border-gray-300 rounded-lg" placeholder="Ej. 3"  />
      </div>

      <div class="form-group">
        <label for="golesVisitante" class="text-lg text-gray-700">Goles Equipo Visitante</label>
        <input type="number" v-model="golesVisitante" id="golesVisitante" class="form-input w-full p-3 border border-gray-300 rounded-lg" placeholder="Ej. 2"  />
      </div>

    


      <button type="submit" class="bg-blue-600 text-white py-4 px-8 rounded-lg w-full hover:bg-blue-700">
        Guardar Resultados
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      golesLocal: '',
      golesVisitante: '',
      partido: null,
    };
  },
  mounted() {
    this.getPartido();
  },
  methods: {
    async getPartido() {
      const partidoId = this.$route.params.partidoId;
      try {
        const response = await fetch(`http://localhost/totalgol/backend/get_partidos_arbitro.php?partidoId=${partidoId}`);
        const data = await response.json();

        if (data.status === 'success') {
          this.partido = data.partido;
        } else {
          alert('No se pudo obtener la información del partido');
        }
      } catch (error) {
        console.error('Error al obtener los datos del partido:', error);
      }
    },
    formatFecha(fecha) {
      const date = new Date(fecha);
      return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
    },
    async submitResultados() {
      const partidoId = this.$route.params.partidoId;


      const data = {
        partidoId,
        golesLocal: this.golesLocal,
        golesVisitante: this.golesVisitante,
        
      };

      try {
        const response = await fetch('http://localhost/totalgol/backend/insertarpartidos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.status === 'success') {
          this.$router.push({ name: 'HomePageArbitros' });
        } else {
          alert('Hubo un error al insertar los resultados');
        }
      } catch (error) {
        console.error('Error al enviar los resultados:', error);
      }
    },
   
  
  },
};
</script>
