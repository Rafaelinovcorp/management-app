<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    funcao: Object,
})

const form = useForm({
    nome: props.funcao.nome ?? '',
    ativo: props.funcao.ativo ?? true,
})

function submit() {
    form.put(route('contactos.funcoes.update', props.funcao.id))
}
</script>

<template>
    <Head title="Editar Função" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Editar Função de Contacto
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg p-6">

                    <h1 class="text-2xl font-semibold mb-6">
                        Editar Função
                    </h1>

                    <form @submit.prevent="submit" class="space-y-5">

                        <!-- Nome -->
                        <div>
                            <InputLabel value="Nome da Função" class="mb-1" />
                            <TextInput
                                v-model="form.nome"
                                class="w-full"
                            />
                            <InputError
                                :message="form.errors.nome"
                                class="mt-1"
                            />
                        </div>

                        <!-- Estado -->
                        <div>
                            <InputLabel value="Estado" class="mb-2" />

                            <label class="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    v-model="form.ativo"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Ativa
                            </label>

                            <p
                                v-if="!form.ativo"
                                class="mt-2 text-sm text-yellow-700"
                            >
                                ⚠️ Contactos associados a esta função deixarão de aparecer nas listagens.
                            </p>
                        </div>

                        <!-- AÇÕES -->
                        <div class="pt-4 flex justify-between">
                            <Link
                                :href="route('contactos.funcoes.index')"
                                class="text-sm text-gray-600 hover:underline"
                            >
                                ← Voltar
                            </Link>

                            <PrimaryButton :disabled="form.processing">
                                <span v-if="!form.processing">Guardar Alterações</span>
                                <span v-else>A guardar...</span>
                            </PrimaryButton>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
