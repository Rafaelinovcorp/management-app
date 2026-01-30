<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

defineProps({
    ivas: Array,
})

const form = useForm({
    referencia: '',
    nome: '',
    descricao: '',
    preco: '',
    iva_id: '',
    foto: null,
    observacoes: '',
    estado: true,
})

/**
 * Apenas números (referência)
 */
function onlyNumbers(field) {
    let value = form[field] ?? ''
    value = value.replace(/\D/g, '')
    form[field] = value
}

/**
 * Apenas números + decimal (preço)
 */
function onlyPrice() {
    let value = form.preco ?? ''

    // remove tudo menos números e ponto
    value = value.replace(/[^0-9.]/g, '')

    // apenas um ponto decimal
    const parts = value.split('.')
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('')
    }

    form.preco = value
}

function submit() {
    form.post(route('artigos.store'), {
        preserveScroll: true,
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Novo Artigo" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Criar Artigo
            </h2>
        </template>

        <div class="max-w-xl mx-auto p-6">
            <div class="bg-white shadow rounded-lg p-6">

                <h1 class="text-2xl font-semibold text-gray-800 mb-6">
                    Criar Artigo
                </h1>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Referência -->
                    <div>
                        <InputLabel value="Referência" class="mb-1" />
                        <TextInput
                            v-model="form.referencia"
                            class="w-full"
                            inputmode="numeric"
                            @input="onlyNumbers('referencia')"
                        />
                        <InputError :message="form.errors.referencia" class="mt-1" />
                    </div>

                    <!-- Nome -->
                    <div>
                        <InputLabel value="Nome" class="mb-1" />
                        <TextInput
                            v-model="form.nome"
                            class="w-full"
                        />
                        <InputError :message="form.errors.nome" class="mt-1" />
                    </div>

                    <!-- Descrição -->
                    <div>
                        <InputLabel value="Descrição" class="mb-1" />
                        <textarea
                            v-model="form.descricao"
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.descricao" class="mt-1" />
                    </div>

                    <!-- Preço -->
                    <div>
                        <InputLabel value="Preço (€)" class="mb-1" />
                        <TextInput
                            v-model="form.preco"
                            class="w-full"
                            inputmode="decimal"
                            @input="onlyPrice"
                        />
                        <InputError :message="form.errors.preco" class="mt-1" />
                    </div>

                    <!-- IVA -->
                    <div>
                        <InputLabel value="IVA" class="mb-1" />
                        <select
                            v-model="form.iva_id"
                            class="w-full rounded-md border-gray-300 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Selecionar IVA</option>
                            <option
                                v-for="iva in ivas"
                                :key="iva.id"
                                :value="iva.id"
                            >
                                {{ iva.nome }} ({{ iva.percentagem }}%)
                            </option>
                        </select>
                        <InputError :message="form.errors.iva_id" class="mt-1" />
                    </div>

                    <!-- Foto -->
                    <div>
                        <InputLabel value="Foto" class="mb-1" />
                        <input
                            type="file"
                            @change="e => form.foto = e.target.files[0]"
                            class="block w-full text-sm text-gray-700
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:bg-gray-100 file:text-gray-700
                                   hover:file:bg-gray-200"
                        />
                        <InputError :message="form.errors.foto" class="mt-1" />
                    </div>

                    <!-- Observações -->
                    <div>
                        <InputLabel value="Observações" class="mb-1" />
                        <textarea
                            v-model="form.observacoes"
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.observacoes" class="mt-1" />
                    </div>

                    <!-- Estado -->
                    <div>
                        <InputLabel value="Estado" class="mb-2" />
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                :checked="form.estado === 'Ativo'"
                                @change="form.estado = $event.target.checked ? 'Ativo' : 'Inativo'"
                                class="rounded border-gray-300 text-indigo-600
                                       focus:ring-indigo-500"
                            />

                            Ativo
                        </label>
                    </div>

                    <!-- BOTÃO -->
                    <div class="pt-4 flex justify-end">
                        <PrimaryButton :disabled="form.processing">
                            <span v-if="!form.processing">Guardar</span>
                            <span v-else>A guardar...</span>
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
