<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
  faturas: Array,
})

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString()
}

const formatMoney = (value) => {
  return `${Number(value).toFixed(2)} €`
}

const deleteFatura = (id) => {
  if (confirm('Eliminar esta fatura?')) {
    router.delete(route('faturas-fornecedor.destroy', id))
  }
}
</script>

<template>
  <Head title="Faturas de Fornecedor" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">
          Faturas de Fornecedor
        </h2>

        <PrimaryButton as-child>
          <Link :href="route('faturas-fornecedor.create')">
            Nova Fatura
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
              <th class="px-4 py-3 text-left text-sm font-semibold">Fornecedor</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Total</th>
              <th class="px-4 py-3 text-left text-sm font-semibold">Estado</th>
              <th class="px-4 py-3 text-right text-sm font-semibold">Ações</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr
              v-for="fatura in faturas"
              :key="fatura.id"
              class="hover:bg-gray-50"
            >
              <td class="px-4 py-3">{{ formatDate(fatura.data) }}</td>
              <td class="px-4 py-3">{{ fatura.numero }}</td>
              <td class="px-4 py-3">{{ fatura.fornecedor?.nome }}</td>
              <td class="px-4 py-3">{{ formatMoney(fatura.valor_total) }}</td>

              <td class="px-4 py-3">
                <span
                  v-if="fatura.estado === 'Rascunho'"
                  class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800"
                >
                  rascunho
                </span>
                <span
                  v-else
                  class="px-2 py-1 text-xs rounded bg-green-100 text-green-800"
                >
                  fechada
                </span>
              </td>

              <td class="px-4 py-3 text-right space-x-2">
                <Link
                  v-if="fatura.estado === 'Rascunho'"
                  :href="route('faturas-fornecedor.edit', fatura.id)"
                  class="px-3 py-1 border rounded text-sm"
                >
                  Editar
                </Link>

                <a
                  :href="route('faturas-fornecedor.pdf', fatura.id)"
                  class="px-3 py-1 border rounded text-sm"
                >
                  PDF
                </a>

                <button
                  v-if="fatura.estado === 'Rascunho'"
                  @click="deleteFatura(fatura.id)"
                  class="px-3 py-1 border rounded text-sm text-red-600"
                >
                  Apagar
                </button>
              </td>
            </tr>

            <tr v-if="!faturas.length">
              <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                Sem faturas registadas.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
