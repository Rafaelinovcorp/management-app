<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import EncomendaForm from './Partials/EncomendaForm.vue'

const props = defineProps({
  clientes: Array,
  artigos: Array,
  propostaId: Number,
})

const form = useForm({
  data_encomenda: new Date().toISOString().substring(0, 10),
  entidade_id: '',
  estado: 'Rascunho',
  proposta_id: props.propostaId ?? null,
  linhas: [],
})
</script>

<template>
  <Head title="Nova Encomenda" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">
          Nova Encomenda
        </h2>

        <Link
          :href="route('encomendas.index')"
          class="text-sm text-gray-600 hover:underline"
        >
          Voltar
        </Link>
      </div>
    </template>

    <div class="py-6">
      <EncomendaForm
        :form="form"
        :clientes="clientes"
        :artigos="artigos"
        :submitRoute="route('encomendas.store')"
      />
    </div>
  </AuthenticatedLayout>
</template>
