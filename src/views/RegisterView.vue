<template>
  <div class="register-container">
    <div class="register-card">
      <div class="logo-and-title">
        <img src="/logo1.png" alt="Logo" class="logo">
        <h2 class="app-name">{{ appName }} - Register</h2>
      </div>
      <p class="welcome-message">Create your account to get started.</p>
      <form @submit.prevent="register" class="register-form">
        <div class="form-group">
          <label for="name">Name:</label>
          <input id="name" v-model="name" type="text" required class="form-control" placeholder="Enter your name">
        </div>
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
        <div class="form-group">
          <label for="password_confirmation">Confirm Password:</label>
          <input id="password_confirmation" v-model="password_confirmation" :type="showPassword ? 'text' : 'password'" required class="form-control" placeholder="Confirm your password">
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
      </form>
      <p class="login-link">Already have an account? <router-link to="/login" class="login-link">Log in here</router-link></p>
    
    
    
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const name = ref('');
    const email = ref('');
    const password = ref('');
    const password_confirmation = ref('');
    const router = useRouter();
    const appName = import.meta.env.VITE_APP_NAME;
    const showPassword = ref(false);

    const register = async () => {
      try {
        await axios.post('/register', {
          name: name.value,
          email: email.value,
          password: password.value,
          password_confirmation: password_confirmation.value,
        });

        router.push({ name: 'VueDashboard' });
      } catch (error) {
        console.error('Error registering:', error);
      }
    };

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };

    return {
      name,
      email,
      password,
      password_confirmation,
      register,
      appName,
      showPassword,
      togglePasswordVisibility,
    };
  },
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.register-card {
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
  width: 60px; /* Adjusted smaller size */
  height: auto;
  margin-right: 10px;
}

.app-name {
  font-size: 24px; /* Increased font size */
  font-weight: bold;
  color: #333; /* Adjust text color as needed */
}

.welcome-message {
  font-size: 16px;
  margin-bottom: 20px;
  color: #666; /* Adjust text color as needed */
}

.register-form {
  text-align: left; /* Align form inputs to the left */
}

.form-group {
  margin-bottom: 20px;
}

label {
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 10px; /* Reduced padding */
  font-size: 14px; /* Reduced font size */
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

.login-link {
  margin-top: 15px;
  font-size: 14px;
  color: #666;
}

.login-link .login-link {
  color: #007bff;
  text-decoration: none;
}

.login-link .login-link:hover {
  text-decoration: underline;
  color: #0056b3; /* Hover color for link */
}

.login-link .login-link:focus {
  outline: none;
  text-decoration: underline;
  color: #0056b3; /* Focus color for link */
}
</style>
