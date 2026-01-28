<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  code: '',
  recovery_code: '',
})
</script>

<template>
  <Head title="Two Factor Authentication" />

  <form @submit.prevent="form.post(route('two-factor.login'))">
    <div>
      <label>Código de Autenticação</label>
      <input v-model="form.code" type="text" />
    </div>

    <button type="submit">
      Confirmar
    </button>
  </form>
</template>
