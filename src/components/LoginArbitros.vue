<template>
  <div class="login-container max-w-sm mx-auto p-6 bg-gray-100 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-center text-green-600 mb-6">Iniciar sesión como Árbitro</h2>
    
    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label for="usuario" class="block text-lg font-medium text-gray-700">Usuario</label>
        <input 
          type="text" 
          id="usuario" 
          v-model="usuario" 
          required 
          class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
      </div>
      
      <div>
        <label for="contraseña" class="block text-lg font-medium text-gray-700">Contraseña</label>
        <input 
          type="password" 
          id="contraseña" 
          v-model="contraseña" 
          required 
          class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
      </div>
      
      <button 
        type="submit" 
        class="w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
        Iniciar sesión
      </button>
    </form>
    
    <p v-if="errorMessage" class="text-red-600 text-center mt-4">{{ errorMessage }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuario: '',
      contraseña: '',
      errorMessage: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch('http://localhost/totalgol/backend/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            usuario: this.usuario,
            contraseña: this.contraseña
          })
        });

        const data = await response.json();

        if (data.status === 'success') {
          // Guardar datos en localStorage
          localStorage.setItem('usuario', data.usuario);
          localStorage.setItem('nombre', data.nombre);
          localStorage.setItem('telefono', data.telefono);
          localStorage.setItem('ID_arbitro', data.ID_arbitro); // Guardar ID_arbitro

          // Redirigir al HomePageArbitros
          this.$router.push({ name: 'HomePageArbitros' });
        } else {
          this.errorMessage = data.message || 'Hubo un error al iniciar sesión.';
        }
      } catch (error) {
        this.errorMessage = 'Hubo un error al intentar iniciar sesión.';
      }
    }
  }
};
</script>

