<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'

import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue'

const props = defineProps({
    artigos: Array,
})

/**
 * COLUNAS DA TABELA (Artigos)
 */
const columns = [
    {
        label: 'Referência',
        value: a => a.referencia,
    },
    {
        label: 'Nome',
        value: a => a.nome,
    },
    {
        label: 'Preço',
        value: a => `${a.preco} €`,
    },
    {
        label: 'Estado',
        value: a => a.estado,
    },
]

const showDeleteModal = ref(false)
const artigoSelecionado = ref(null)

/**
 * Editar artigo
 */
function editArtigo(artigo) {
    router.visit(route('artigos.edit', artigo.id))
}

/**
 * Pedir confirmação de delete
 */
function pedirDelete(artigo) {
    artigoSelecionado.value = artigo
    showDeleteModal.value = true
}

/**
 * Cancelar delete
 */
function cancelarDelete() {
    showDeleteModal.value = false
    artigoSelecionado.value = null
}

/**
 * Confirmar delete
 */
function confirmarDelete() {
    if (!artigoSelecionado.value) return

    router.delete(
        route('artigos.destroy', artigoSelecionado.value.id),
        {
            onFinish: () => {
                showDeleteModal.value = false
                artigoSelecionado.value = null
            },
        }
    )
}
</script>

<template>
    <Head title="Artigos" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Artigos
            </h2>
        </template>

        <div class="p-6">

            <!-- TÍTULO + BOTÕES -->
            <div class="flex justify-between items-center mb-4 gap-2">
                <h1 class="text-2xl font-bold">
                    Artigos
                </h1>

                <div class="flex gap-3">

                <Link
                  :href="route('iva.index')"
                  class="inline-flex items-center rounded-md
                         bg-gray-100 px-4 py-2 text-sm font-medium
                         text-gray-700 hover:bg-gray-200"
                >
                  ⚙️ IVA
                </Link>

                    <!-- CRIAR ARTIGO -->
                    <Link :href="route('artigos.create')">
                        <PrimaryButton>
                            Novo Artigo
                        </PrimaryButton>
                    </Link>
                </div>
            </div>

            <!-- TABELA -->
            <DataTable
                :items="artigos"
                :columns="columns"
                @edit="editArtigo"
                @delete="pedirDelete"
            />

            <!-- MODAL DELETE -->
            <ConfirmDeleteModal
                :show="showDeleteModal"
                title="Apagar Artigo"
                message="Tens a certeza que queres apagar este artigo? Esta ação não pode ser desfeita."
                @cancel="cancelarDelete"
                @confirm="confirmarDelete"
            />
        </div>
    </AuthenticatedLayout>
</template>
