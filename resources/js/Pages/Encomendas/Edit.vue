<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import EncomendaForm from './Partials/EncomendaForm.vue'

const props = defineProps({
  encomenda: Object,
  clientes: Array,
  artigos: Array
})

const form = useForm({
  data_encomenda: props.encomenda.data,
  entidade_id: props.encomenda.entidade_id,
  estado: props.encomenda.estado,
  linhas: props.encomenda.linhas.map(l => ({
    artigo_id: l.artigo_id,
    quantidade: l.quantidade,
    preco_unitario: l.preco_unitario,
  })),
})

</script>

<template>
  <Head title="Editar Encomenda" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">
          Editar Encomenda #{{ encomenda.numero }}
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
        :submitRoute="route('encomendas.update', encomenda.id)"
        submitMethod="put"
      />
    </div>
  </AuthenticatedLayout>
</template>
