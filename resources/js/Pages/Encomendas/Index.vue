<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
  encomendas: Array,
})

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString()
}

const formatMoney = (value) => {
  return `${Number(value).toFixed(2)} €`
}

const deleteEncomenda = (id) => {
  if (confirm('Tem a certeza que pretende eliminar esta encomenda?')) {
    router.delete(route('encomendas.destroy', id))
  }
}
</script>
<template>
  <Head title="Encomendas" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight">
          Encomendas
        </h2>

        <PrimaryButton as-child>
          <Link :href="route('encomendas.create')">
            Nova Encomenda
          </Link>
        </PrimaryButton>
      </div>
    </template>

    <div class="py-6">
      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold">Data</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Número</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Cliente</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Valor Total</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Estado</th>
              <th class="px-4 py-3 text-right text-sm font-semibold">Ações</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr
              v-for="encomenda in encomendas"
              :key="encomenda.id"
              class="hover:bg-gray-50"
            >
              <td class="px-4 py-3">
                {{ formatDate(encomenda.data) }}
              </td>

              <td class="px-4 py-3">
                {{ encomenda.numero }}
              </td>

              <td class="px-4 py-3">
                {{ encomenda.entidade?.nome }}
              </td>

              <td class="px-4 py-3">
                {{ formatMoney(encomenda.valor_total) }}
              </td>

              <td class="px-4 py-3">
                <span
                  v-if="encomenda.estado === 'Rascunho'"
                  class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800"
                >
                  rascunho
                </span>

                <span
                  v-else
                  class="px-2 py-1 text-xs rounded bg-green-100 text-green-800"
                >
                  fechado
                </span>
              </td>

              <!-- AÇÕES -->
              <td class="px-4 py-3 text-right space-x-2">
                <Link
                  class="inline-flex items-center px-3 py-1 border rounded text-sm"
                  :href="route('encomendas.edit', encomenda.id)"
                  v-if="encomenda.estado === 'Rascunho'"
                >
                  Editar
                </Link>

<a
  :href="route('encomendas.pdf', encomenda.id)"
  class="inline-flex items-center px-3 py-1 border rounded text-sm"
>
  PDF
</a>



                <button
                  class="inline-flex items-center px-3 py-1 border rounded text-sm text-red-600"
                  @click="deleteEncomenda(encomenda.id)"
                  v-if="encomenda.estado === 'Rascunho'"
                >
                  Apagar
                </button>
              </td>
            </tr>

            <tr v-if="!encomendas.length">
              <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                Sem encomendas registadas.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
