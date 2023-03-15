<script lang="ts" setup>
  import {ref} from "vue";
  import axios from "axios";

  const form = ref({
    email: null,
    password: null,
  });

  axios.defaults.withCredentials = true;

  const user = ref();

  async function onLogin() {
    await axios.get("http://localhost:8000/sanctum/csrf-cookie");
    await axios.post("http://localhost:8000/api/v1/auth/login", {
      email: form.value.email,
      password: form.value.password,
    });
    let data = await axios.get("http://localhost:8000/api/v1/auth/user/detail/1");
    user.value = data;
  }

  useHead({
    title: 'Login - Qaama.My.Id'
  });
</script>

<template>
  <div>
    Page: Login
    {{ user }}
    <form @submit.prevent="onLogin">
      <label for="email">Email</label>
      <input type="text" v-model="form.email">
      <label for="password">password</label>
      <input type="password" v-model="form.password">
      <button>Login</button>
    </form>
  </div>
</template>

<style scoped></style>
