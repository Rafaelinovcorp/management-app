<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'

import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue'

const props = defineProps({
    contactos: Array,
})

/**
 * COLUNAS DA TABELA (Contactos)
 */
const columns = [
    {
        label: 'Nome',
        value: c => c.nome,
    },
    {
        label: 'Apelido',
        value: c => c.apelido,
    },
    {
        label: 'Função',
        value: c => c.funcao?.nome ?? '-',
    },
    {
        label: 'Entidade',
        value: c => c.entidade?.nome ?? '-',
    },
    {
        label: 'Telefone',
        value: c => c.telefone ?? '-',
    },
    {
        label: 'Telemóvel',
        value: c => c.telemovel ?? '-',
    },
]

const showDeleteModal = ref(false)
const contactoSelecionado = ref(null)

/**
 * Editar contacto
 */
function editContacto(contacto) {
    router.visit(route('contactos.edit', contacto.id))
}

/**
 * Pedir confirmação de delete
 */
function pedirDelete(contacto) {
    contactoSelecionado.value = contacto
    showDeleteModal.value = true
}

/**
 * Cancelar delete
 */
function cancelarDelete() {
    showDeleteModal.value = false
    contactoSelecionado.value = null
}

/**
 * Confirmar delete
 */
function confirmarDelete() {
    if (!contactoSelecionado.value) return

    router.delete(
        route('contactos.destroy', contactoSelecionado.value.id),
        {
            onFinish: () => {
                showDeleteModal.value = false
                contactoSelecionado.value = null
            },
        }
    )
}
</script>

<template>
    <Head title="Contactos" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Contactos
            </h2>
        </template>

        <div class="p-6">

            <!-- TÍTULO + BOTÕES -->
            <div class="flex justify-between items-center mb-4 gap-2">
                <h1 class="text-2xl font-bold">
                    Contactos
                </h1>

                <div class="flex gap-3">
                    <!-- CONFIGURAÇÕES FUNÇÕES -->
                    <Link
                        :href="route('contactos.funcoes.index')"
                        class="inline-flex items-center rounded-md
                               bg-gray-100 px-4 py-2 text-sm font-medium
                               text-gray-700 hover:bg-gray-200"
                    >
                        ⚙️ Funções
                    </Link>

                    <!-- CRIAR CONTACTO -->
                    <Link :href="route('contactos.create')">
                        <PrimaryButton>
                            Criar Contacto
                        </PrimaryButton>
                    </Link>
                </div>
            </div>

            <!-- TABELA -->
            <DataTable
                :items="contactos"
                :columns="columns"
                @edit="editContacto"
                @delete="pedirDelete"
            />

            <!-- MODAL DELETE -->
            <ConfirmDeleteModal
                :show="showDeleteModal"
                title="Apagar Contacto"
                message="Tens a certeza que queres apagar este contacto? Esta ação não pode ser desfeita."
                @cancel="cancelarDelete"
                @confirm="confirmarDelete"
            />
        </div>
    </AuthenticatedLayout>
</template>
