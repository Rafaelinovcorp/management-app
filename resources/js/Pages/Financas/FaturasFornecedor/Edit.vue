<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FaturaFornecedorForm from './Partials/FaturaFornecedorForm.vue'

const props = defineProps({
  fatura: Object,
  fornecedores: Array,
})

const form = useForm({
  data: props.fatura.data,
  fornecedor_id: props.fatura.fornecedor_id,
  estado: props.fatura.estado,
  linhas: props.fatura.linhas,
})
</script>

<template>
  <Head title="Editar Fatura de Fornecedor" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">
          Editar Fatura #{{ fatura.numero }}
        </h2>
        <Link :href="route('faturas-fornecedor.index')" class="text-sm underline">
          Voltar
        </Link>
      </div>
    </template>

    <div class="py-6">
      <FaturaFornecedorForm
        :form="form"
        :fornecedores="fornecedores"
        :submitRoute="route('faturas-fornecedor.update', fatura.id)"
        submitMethod="put"
      />
    </div>
  </AuthenticatedLayout>
</template>
