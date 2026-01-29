<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    empresa: Object
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard · Empresa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                    <!-- SE EMPRESA NÃO ESTIVER CONFIGURADA -->
                    <div
                        v-if="!empresa"
                        class="rounded-md border border-yellow-300 bg-yellow-50 p-4 text-yellow-800"
                    >
                        <p class="font-medium">
                            A empresa ainda não está configurada.
                        </p>
                        <p class="text-sm">
                            Configure os dados da empresa para que estes apareçam nos documentos e na aplicação.
                        </p>

                        <div class="mt-4">
                            <Link
                                :href="route('empresa.edit')"
                                class="inline-flex items-center rounded-md bg-yellow-600 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-700"
                            >
                                Configurar Empresa
                            </Link>
                        </div>
                    </div>

                    <!-- INFO EMPRESA -->
                    <div v-else class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Nome da Empresa</p>
                                <p class="text-lg font-medium">{{ empresa.nome }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">NIF</p>
                                <p class="text-lg font-medium">{{ empresa.nif }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Morada</p>
                                <p class="text-lg font-medium">{{ empresa.morada }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Código Postal / Localidade</p>
                                <p class="text-lg font-medium">
                                    {{ empresa.codigo_postal }} {{ empresa.localidade }}
                                </p>
                            </div>
                        </div>

                        <!-- AÇÕES -->
                        <div class="flex justify-end">
                            <Link
                                :href="route('empresa.edit')"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                            >
                                Editar Empresa
                            </Link>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
