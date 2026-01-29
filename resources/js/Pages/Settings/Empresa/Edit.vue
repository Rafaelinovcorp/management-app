<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const props = defineProps({
    empresa: Object
})

const form = useForm({
    nome: props.empresa?.nome ?? '',
    nif: props.empresa?.nif ?? '',
    morada: props.empresa?.morada ?? '',
    codigo_postal: props.empresa?.codigo_postal ?? '',
    localidade: props.empresa?.localidade ?? '',
    logotipo: null,
})

function submit() {
    form.post(route('empresa.update'), {
        forceFormData: true,
        preserveScroll: true,
    })
}

function onlyNumbers(field) {
    form[field] = form[field].replace(/\D/g, '')
}

function formatCodigoPostal() {
    let value = form.codigo_postal.replace(/\D/g, '')

    if (value.length > 4) {
        value = value.slice(0, 4) + '-' + value.slice(4, 7)
    }

    form.codigo_postal = value
}

</script>

<template>
    <Head title="Configurações · Empresa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Configurações · Empresa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">

                    <!-- HEADER -->
                    <div class="border-b px-6 py-4">
                        <h3 class="text-lg font-semibold">Dados da Empresa</h3>
                        <p class="text-sm text-gray-500">
                            Estes dados são usados no login, documentos PDF e emails.
                        </p>
                    </div>

                    <!-- FORM -->
                    <form @submit.prevent="submit" class="space-y-6 px-6 py-6">

                        <!-- NOME -->
                        <div>
                            <Label>Nome da Empresa</Label>
                            <Input
                                v-model="form.nome"
                                :class="form.errors.nome ? 'border-red-500' : ''"
                            />
                            <p v-if="form.errors.nome" class="mt-1 text-sm text-red-600">
                                {{ form.errors.nome }}
                            </p>
                        </div>

                        <!-- NIF -->
                        <div>
                            <Label>Número Contribuinte</Label>
                            <Input
                                v-model="form.nif"
                                inputmode="numeric"
                                maxlength="9"
                                @input="onlyNumbers('nif')"
                                :class="form.errors.nif ? 'border-red-500' : ''"
                            />

                            <p v-if="form.errors.nif" class="mt-1 text-sm text-red-600">
                                {{ form.errors.nif }}
                            </p>
                        </div>

                        <!-- MORADA -->
                        <div>
                            <Label>Morada</Label>
                            <Input
                                v-model="form.morada"
                                :class="form.errors.morada ? 'border-red-500' : ''"
                            />
                            <p v-if="form.errors.morada" class="mt-1 text-sm text-red-600">
                                {{ form.errors.morada }}
                            </p>
                        </div>

                        <!-- CP + LOCALIDADE -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label>Código Postal</Label>
                                <Input
                                    v-model="form.codigo_postal"
                                    inputmode="numeric"
                                    placeholder="1234-567"
                                    maxlength="8"
                                    @input="formatCodigoPostal"
                                    :class="form.errors.codigo_postal ? 'border-red-500' : ''"
                                />

                                <p v-if="form.errors.codigo_postal" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.codigo_postal }}
                                </p>
                            </div>

                            <div>
                                <Label>Localidade</Label>
                                <Input
                                    v-model="form.localidade"
                                    :class="form.errors.localidade ? 'border-red-500' : ''"
                                />
                                <p v-if="form.errors.localidade" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.localidade }}
                                </p>
                            </div>
                        </div>

                        <!-- LOGO -->
                        <div>
                            <Label>Logotipo da Empresa</Label>
                            <Input
                                type="file"
                                @change="e => form.logotipo = e.target.files[0]"
                            />
                            <p v-if="form.errors.logotipo" class="mt-1 text-sm text-red-600">
                                {{ form.errors.logotipo }}
                            </p>
                        </div>

                        <!-- BOTÃO -->
                        <div class="flex justify-end">
                            <Button :disabled="form.processing">
                                Guardar Alterações
                            </Button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
