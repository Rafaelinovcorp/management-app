<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FaturaFornecedorForm from './Partials/FaturaFornecedorForm.vue'

defineProps({
  fornecedores: Array,
})

const form = useForm({
  data_fatura: new Date().toISOString().substring(0, 10),
  fornecedor_id: '',
  estado: 'Rascunho',
  linhas: [],
})
</script>

<template>
  <Head title="Nova Fatura de Fornecedor" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Nova Fatura</h2>
        <Link
          :href="route('faturas-fornecedor.index')"
          class="text-sm underline"
        >
          Voltar
        </Link>
      </div>
    </template>

    <div class="py-6">
      <FaturaFornecedorForm
        :form="form"
        :fornecedores="fornecedores"
        :submitRoute="route('faturas-fornecedor.store')"
      />
    </div>
  </AuthenticatedLayout>
</template>
