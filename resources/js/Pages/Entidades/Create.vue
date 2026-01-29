<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const form = useForm({
  nif: '',
  nome: '',
  morada: '',
  telefone: '',
  email: '',
  is_cliente: false,
  is_fornecedor: false,
})

function submit() {
  form.post(route('entidades.store'))
}
</script>

<template>
  <Head title="Criar Entidade" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Criar Entidade
      </h2>
    </template>

    <div class="max-w-xl mx-auto p-6">
      <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">
          Criar Entidade
        </h1>

        <form @submit.prevent="submit" class="space-y-5">

          <!-- NIF -->
          <div>
            <InputLabel value="NIF" class="mb-1" />
            <TextInput
              v-model="form.nif"
              class="w-full"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="9"
              @input="form.nif = form.nif.replace(/\D/g, '')"
            />
            <InputError :message="form.errors.nif" class="mt-1" />
          </div>

          <!-- Nome -->
          <div>
            <InputLabel value="Nome" class="mb-1" />
            <TextInput
              v-model="form.nome"
              class="w-full"
            />
            <InputError :message="form.errors.nome" class="mt-1" />
          </div>

          <!-- Morada -->
          <div>
            <InputLabel value="Morada" class="mb-1" />
            <TextInput
              v-model="form.morada"
              class="w-full"
            />
            <InputError :message="form.errors.morada" class="mt-1" />
          </div>

          <!-- Telefone -->
          <div>
            <InputLabel value="Telefone" class="mb-1" />
            <TextInput
              v-model="form.telefone"
              class="w-full"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="9"
              @input="form.telefone = form.telefone.replace(/\D/g, '')"
            />
            <InputError :message="form.errors.telefone" class="mt-1" />
          </div>

          <!-- Email -->
          <div>
            <InputLabel value="Email" class="mb-1" />
            <TextInput
              v-model="form.email"
              type="email"
              class="w-full"
            />
            <InputError :message="form.errors.email" class="mt-1" />
          </div>

          <!-- Tipo -->
          <div>
            <InputLabel value="Tipo de Entidade" class="mb-2" />

            <div class="flex gap-6">
              <label class="flex items-center gap-2 text-sm">
                <input
                  type="checkbox"
                  v-model="form.is_cliente"
                  class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                />
                Cliente
              </label>

              <label class="flex items-center gap-2 text-sm">
                <input
                  type="checkbox"
                  v-model="form.is_fornecedor"
                  class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                />
                Fornecedor
              </label>
            </div>

            <InputError
              v-if="!form.is_cliente && !form.is_fornecedor && form.hasErrors"
              message="Seleciona pelo menos um tipo."
              class="mt-1"
            />
          </div>

          <!-- Botão -->
          <div class="pt-4 flex justify-end">
            <PrimaryButton :disabled="form.processing">
              <span v-if="!form.processing">Guardar</span>
              <span v-else>A guardar...</span>
            </PrimaryButton>
          </div>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
