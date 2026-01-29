<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue'

const props = defineProps({
  entidades: Array,
})

/**
 * COLUNAS DA TABELA (Entidades)
 */
const columns = [
  {
    label: 'Nome',
    value: e => e.nome,
  },
  {
    label: 'Telefone',
    value: e => e.telefone ?? '-',
  },
  {
    label: 'Email',
    value: e => e.email ?? '-',
  },
]

const showDeleteModal = ref(false)
const entidadeSelecionada = ref(null)

/**
 * Editar entidade
 */
function editEntidade(entidade) {
  router.visit(route('entidades.edit', entidade.id))
}

/**
 * Pedir confirmação de delete
 */
function pedirDelete(entidade) {
  entidadeSelecionada.value = entidade
  showDeleteModal.value = true
}

/**
 * Cancelar delete
 */
function cancelarDelete() {
  showDeleteModal.value = false
  entidadeSelecionada.value = null
}

/**
 * Confirmar delete
 */
function confirmarDelete() {
  if (!entidadeSelecionada.value) return

  router.delete(route('entidades.destroy', entidadeSelecionada.value.id), {
    onFinish: () => {
      showDeleteModal.value = false
      entidadeSelecionada.value = null
    },
  })
}

/**
 * Mensagem dinâmica do modal
 */
const deleteMessage = computed(() => {
  if (!entidadeSelecionada.value) return ''

  let tipo = 'Entidade'
  if (entidadeSelecionada.value.is_cliente && entidadeSelecionada.value.is_fornecedor) {
    tipo = 'Cliente / Fornecedor'
  } else if (entidadeSelecionada.value.is_cliente) {
    tipo = 'Cliente'
  } else if (entidadeSelecionada.value.is_fornecedor) {
    tipo = 'Fornecedor'
  }

  return `Tens a certeza que queres apagar ${tipo} "${entidadeSelecionada.value.nome}"? Esta ação não pode ser desfeita.`
})
</script>

<template>
  <Head title="Entidades" />

  <AuthenticatedLayout>
    <!-- HEADER -->
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Entidades
      </h2>
    </template>

    <div class="p-6">
      <!-- TÍTULO + BOTÃO -->
      <div class="flex justify-between items-center mb-4 gap-2">
        <h1 class="text-2xl font-bold">
          Entidades
        </h1>

        <Link :href="route('entidades.create')">
          <PrimaryButton>
            Criar Entidade
          </PrimaryButton>
        </Link>
      </div>

      <!-- TABELA -->
      <DataTable
        :items="entidades"
        :columns="columns"
        @edit="editEntidade"
        @delete="pedirDelete"
      />

      <!-- MODAL DELETE -->
      <ConfirmDeleteModal
        :show="showDeleteModal"
        title="Apagar Entidade"
        :message="deleteMessage"
        @cancel="cancelarDelete"
        @confirm="confirmarDelete"
      />
    </div>
  </AuthenticatedLayout>
</template>
