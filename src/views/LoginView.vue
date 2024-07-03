<template>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-and-title">
        <img src="/logo1.png" alt="Logo" class="logo">
        <h2 class="app-name">{{ appName }} - Login</h2>
      </div>
      <p class="welcome-message">Welcome back! Please log in to continue.</p>
      <form @submit.prevent="login" class="login-form">
        <div class="form-group">
          <label for="email">Email:</label>
          <input id="email" v-model="email" type="email" required class="form-control" placeholder="Enter your email">
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <div class="password-input">
            <input id="password" v-model="password" :type="showPassword ? 'text' : 'password'" required class="form-control" placeholder="Enter your password">
            <span @click="togglePasswordVisibility" class="toggle-password">
              {{ showPassword ? 'Hide' : 'Show' }}
            </span>
          </div>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading">Logging in...</span>
          <span v-else>Login</span>
        </button>
      </form>
      <p class="register-link">Not yet registered? <router-link to="/register" class="register-link">Create an account</router-link></p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const email = ref('');
const password = ref('');
const router = useRouter();
const appName = import.meta.env.VITE_APP_NAME;
const showPassword = ref(false);
const loading = ref(false);

const authStore = useAuthStore();

const login = async () => {
  loading.value = true;
  try {
    const credentials = {
      email: email.value,
      password: password.value,
    };

    await authStore.login(credentials);
    alert('Successfully logged in!');
  } catch (error) {
    alert('Error logging in.');
    console.error('Error logging in:', error);
  } finally {
    loading.value = false;
  }
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

</script>


<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.login-card {
  width: 400px;
  background-color: #f9f9f9;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.logo-and-title {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

.logo {
  width: 60px;
  height: auto;
  margin-right: 10px;
}

.app-name {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.welcome-message {
  font-size: 16px;
  margin-bottom: 20px;
}

.login-form {
  text-align: left;
}

.form-group {
  margin-bottom: 20px;
}

label {
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 10px; 
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.password-input {
  position: relative;
}

.toggle-password {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #666;
}

.toggle-password:hover {
  text-decoration: underline;
  color: #333;
}

.btn {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btn:hover {
  background-color: #0056b3;
}

.btn:active {
  background-color: #0056b3;
}

.btn:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5);
}

.register-link {
  margin-top: 15px;
  font-size: 14px;
  color: #666;
}

.register-link .register-link {
  color: #007bff;
  text-decoration: none;
}

.register-link .register-link:hover {
  text-decoration: underline;
  color: #0056b3;
}

.register-link .register-link:focus {
  outline: none;
  text-decoration: underline;
  color: #0056b3;
}
</style>
