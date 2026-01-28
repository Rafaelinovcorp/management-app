<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const { errors } = usePage().props

const form = ref({
  nif: '',
  nome: '',
  morada: '',
  telefone: '',
  email: '',
  rgpd: false,
})

function submit() {
  router.post(route('clientes.store'), form.value)
}
</script>

<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Criar Cliente</h1>

    <form @submit.prevent="submit" class="space-y-5">
      <!-- NIF -->
      <div>
        <label class="block text-sm font-medium mb-1">NIF</label>
        <input
          v-model="form.nif"
          inputmode="numeric"
          pattern="[0-9]*"
          maxlength="9"
          @input="form.nif = form.nif.replace(/\D/g, '')"
          class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="123456789"
        />

        <p class="text-xs text-gray-500 mt-1">
          O NIF deve ter exatamente 9 dígitos
        </p>
        <p v-if="errors.nif" class="text-red-500 text-sm mt-1">
          {{ errors.nif }}
        </p>
      </div>

      <!-- Nome -->
      <div>
        <label class="block text-sm font-medium mb-1">Nome</label>
        <input
          v-model="form.nome"
          class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Nome do cliente"
        />
        <p v-if="errors.nome" class="text-red-500 text-sm mt-1">
          {{ errors.nome }}
        </p>
      </div>

      <!-- Morada -->
      <div>
        <label class="block text-sm font-medium mb-1">Morada</label>
        <input
          v-model="form.morada"
          class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Morada"
        />
      </div>

      <!-- Telefone -->
      <div>
        <label class="block text-sm font-medium mb-1">Telefone</label>
        <input
          v-model="form.telefone"
          inputmode="numeric"
          minlength="9"
          maxlength="9"
          class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="912345678"
        />
        <p class="text-xs text-gray-500 mt-1">
          O telefone deve ter 9 dígitos
        </p>
        <p v-if="errors.telefone" class="text-red-500 text-sm mt-1">
          {{ errors.telefone }}
        </p>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="email@exemplo.com"
        />
        <p v-if="errors.email" class="text-red-500 text-sm mt-1">
          {{ errors.email }}
        </p>
      </div>

      <!-- RGPD -->
      <div class="flex items-center gap-2">
        <input
          type="checkbox"
          v-model="form.rgpd"
          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        />
        <span class="text-sm">Consentimento RGPD</span>
      </div>

      <!-- Botão -->
      <div class="pt-4">
        <PrimaryButton type="submit">
          Guardar
        </PrimaryButton>
      </div>
    </form>
  </div>
</template>
