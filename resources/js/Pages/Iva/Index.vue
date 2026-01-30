<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'

import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue'

const props = defineProps({
    ivas: Array,
})

const columns = [
    { label: 'Nome', value: i => i.nome },
    { label: 'Percentagem', value: i => `${i.percentagem}%` },
    { label: 'Artigos', value: i => i.artigos_count },
]

const showDeleteModal = ref(false)
const ivaSelecionado = ref(null)

function editIva(iva) {
    router.visit(route('iva.edit', iva.id))
}

function pedirDelete(iva) {
    if (iva.artigos_count > 0) return
    ivaSelecionado.value = iva
    showDeleteModal.value = true
}

function confirmarDelete() {
    router.delete(route('iva.destroy', ivaSelecionado.value.id), {
        onFinish: () => {
            showDeleteModal.value = false
            ivaSelecionado.value = null
        },
    })
}
</script>

<template>
    <Head title="IVA" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                IVA
            </h2>
        </template>

        <div class="p-6">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">IVA</h1>

                <Link :href="route('iva.create')">
                    <PrimaryButton>
                        Novo IVA
                    </PrimaryButton>
                </Link>
            </div>

            <DataTable
                :items="ivas"
                :columns="columns"
                @edit="editIva"
                @delete="pedirDelete"
            >
                <template #actions="{ item }">
                    <button
                        v-if="item.artigos_count > 0"
                        class="text-gray-400 text-sm cursor-not-allowed"
                    >
                        Em uso
                    </button>
                </template>
            </DataTable>

            <ConfirmDeleteModal
                :show="showDeleteModal"
                title="Apagar IVA"
                message="Este IVA será apagado permanentemente."
                @cancel="showDeleteModal = false"
                @confirm="confirmarDelete"
            />
        </div>
    </AuthenticatedLayout>
</template>
