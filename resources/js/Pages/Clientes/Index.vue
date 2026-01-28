<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  clientes: Array,
})

const showDeleteModal = ref(false)
const clienteSelecionado = ref(null)

function editCliente(cliente) {
  router.visit(route('clientes.edit', cliente.id))
}

function pedirDelete(cliente) {
  clienteSelecionado.value = cliente
  showDeleteModal.value = true
}

function cancelarDelete() {
  showDeleteModal.value = false
  clienteSelecionado.value = null
}

function confirmarDelete() {
  if (!clienteSelecionado.value) return

  router.delete(
    route('clientes.destroy', clienteSelecionado.value.id),
    {
      onFinish: () => {
        showDeleteModal.value = false
        clienteSelecionado.value = null
      },
    }
  )
}

const deleteMessage = computed(() => {
  if (!clienteSelecionado.value) return ''
  return `Tens a certeza que queres apagar o cliente "${clienteSelecionado.value.nome}"? Esta ação não pode ser desfeita.`
})
</script>

<template>
  <Head title="Clientes" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Clientes
      </h2>
    </template>

    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Clientes</h1>

        <Link :href="route('clientes.create')">
          <PrimaryButton>
            Criar Cliente
          </PrimaryButton>
        </Link>
      </div>

      <DataTable
        :items="clientes"
        @edit="editCliente"
        @delete="pedirDelete"
      />

      <!-- Modal de confirmação de delete -->
      <ConfirmDeleteModal
        :show="showDeleteModal"
        title="Apagar Cliente"
        :message="deleteMessage"
        @cancel="cancelarDelete"
        @confirm="confirmarDelete"
      />
    </div>
  </AuthenticatedLayout>
</template>
