<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const form = useForm({
    nome: '',
    percentagem: '',
})

function onlyNumbers() {
    form.percentagem = form.percentagem.replace(/[^0-9.]/g, '')
}

function submit() {
    form.post(route('iva.store'))
}
</script>

<template>
    <Head title="Criar IVA" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Criar IVA
            </h2>
        </template>

        <div class="max-w-md mx-auto p-6">
            <div class="bg-white shadow rounded-lg p-6">

                <form @submit.prevent="submit" class="space-y-5">

                    <div>
                        <InputLabel value="Nome" />
                        <TextInput v-model="form.nome" class="w-full" />
                        <InputError :message="form.errors.nome" />
                    </div>

                    <div>
                        <InputLabel value="Percentagem (%)" />
                        <TextInput
                            v-model="form.percentagem"
                            inputmode="decimal"
                            @input="onlyNumbers"
                            class="w-full"
                        />
                        <InputError :message="form.errors.percentagem" />
                    </div>

                    <div class="flex justify-end pt-4">
                        <PrimaryButton :disabled="form.processing">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
