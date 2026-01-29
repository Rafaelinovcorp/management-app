<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

defineProps({
    entidades: Array, // [{ id, nome }]
    funcoes: Array,   // [{ id, nome }]
})

const form = useForm({
    numero: '',
    entidade_id: '',
    nome: '',
    apelido: '',
    contacto_funcao_id: '',
    telefone: '',
    telemovel: '',
    email: '',
    consentimento_rgpd: false,
    observacoes: '',
    estado: true,
})

/**
 * Permitir apenas números (telefone / telemóvel)
 */
function onlyNumbers(field, maxLength = null) {
    let value = form[field] ?? ''

    value = value.replace(/\D/g, '')

    if (maxLength) {
        value = value.slice(0, maxLength)
    }

    form[field] = value
}

function submit() {
    form.post(route('contactos.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Criar Contacto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Criar Contacto
            </h2>
        </template>

        <div class="max-w-xl mx-auto p-6">
            <div class="bg-white shadow rounded-lg p-6">

                <h1 class="text-2xl font-semibold text-gray-800 mb-6">
                    Criar Contacto
                </h1>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Número -->
                    <div>
                        <InputLabel value="Número" class="mb-1" />
                        <TextInput
                            v-model="form.numero"
                            class="w-full"
                        />
                        <InputError :message="form.errors.numero" class="mt-1" />
                    </div>

                    <!-- Entidade -->
                    <div>
                        <InputLabel value="Entidade" class="mb-1" />
                        <select
                            v-model="form.entidade_id"
                            class="w-full rounded-md border-gray-300 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Selecionar entidade</option>
                            <option
                                v-for="e in entidades"
                                :key="e.id"
                                :value="e.id"
                            >
                                {{ e.nome }}
                            </option>
                        </select>
                        <InputError :message="form.errors.entidade_id" class="mt-1" />
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

                    <!-- Apelido -->
                    <div>
                        <InputLabel value="Apelido" class="mb-1" />
                        <TextInput
                            v-model="form.apelido"
                            class="w-full"
                        />
                        <InputError :message="form.errors.apelido" class="mt-1" />
                    </div>

                    <!-- Função -->
                    <div>
                        <InputLabel value="Função" class="mb-1" />
                        <select
                            v-model="form.contacto_funcao_id"
                            class="w-full rounded-md border-gray-300 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Selecionar função</option>
                            <option
                                v-for="f in funcoes"
                                :key="f.id"
                                :value="f.id"
                            >
                                {{ f.nome }}
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.contacto_funcao_id"
                            class="mt-1"
                        />
                    </div>

                    <!-- Telefone -->
                    <div>
                        <InputLabel value="Telefone" class="mb-1" />
                        <TextInput
                            v-model="form.telefone"
                            class="w-full"
                            inputmode="numeric"
                            maxlength="15"
                            @input="onlyNumbers('telefone', 15)"
                            :class="form.errors.telefone ? 'border-red-500' : ''"
                        />
                        <InputError :message="form.errors.telefone" class="mt-1" />
                    </div>

                    <!-- Telemóvel -->
                    <div>
                        <InputLabel value="Telemóvel" class="mb-1" />
                        <TextInput
                            v-model="form.telemovel"
                            class="w-full"
                            inputmode="numeric"
                            maxlength="9"
                            @input="onlyNumbers('telemovel', 9)"
                            :class="form.errors.telemovel ? 'border-red-500' : ''"
                        />
                        <InputError :message="form.errors.telemovel" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div>
                        <InputLabel value="Email" class="mb-1" />
                        <TextInput
                            v-model="form.email"
                            type="email"
                            class="w-full"
                        />
                        <InputError :message="form.errors.email" class="mt-1" />
                    </div>

                    <!-- RGPD -->
                    <div>
                        <InputLabel value="Consentimento RGPD" class="mb-2" />
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                v-model="form.consentimento_rgpd"
                                class="rounded border-gray-300 text-indigo-600
                                       focus:ring-indigo-500"
                            />
                            Autorizado
                        </label>
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
                        <InputError
                            :message="form.errors.observacoes"
                            class="mt-1"
                        />
                    </div>

                    <!-- Estado -->
                    <div>
                        <InputLabel value="Estado" class="mb-2" />
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                v-model="form.estado"
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
