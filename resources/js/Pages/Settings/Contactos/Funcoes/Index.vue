<script setup>
import { router, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import DataTable from '@/Components/DataTable.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
    funcoes: Array,
})

/**
 * Colunas da tabela
 */
const columns = [
    {
        label: 'Nome',
        value: f => f.nome,
    },
    {
        label: 'Estado',
        value: f => f.ativo ? 'Ativa' : 'Inativa',
    },
]

/**
 * Editar função
 */
function editFuncao(funcao) {
    router.visit(route('contactos.funcoes.edit', funcao.id))
}
</script>

<template>
    <Head title="Funções de Contacto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Funções de Contacto
            </h2>
        </template>

        <div class="p-6">

            <!-- TÍTULO + BOTÃO -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">
                    Funções
                </h1>

                <Link :href="route('contactos.funcoes.create')">
                    <PrimaryButton>
                        Nova Função
                    </PrimaryButton>
                </Link>
            </div>

            <!-- TABELA -->
            <DataTable
                :items="funcoes"
                :columns="columns"
                @edit="editFuncao"
            />

        </div>
    </AuthenticatedLayout>
</template>
