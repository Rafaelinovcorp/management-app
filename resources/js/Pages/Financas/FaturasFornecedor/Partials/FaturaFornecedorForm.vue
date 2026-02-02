<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  form: Object,
  fornecedores: Array,
  submitRoute: String,
  submitMethod: {
    type: String,
    default: 'post',
  },
})

const addLinha = () => {
  props.form.linhas.push({
    descricao: '',
    quantidade: 1,
    preco_unitario: 0,
    iva_percentagem: 23,
  })
}

const removeLinha = (index) => {
  props.form.linhas.splice(index, 1)
}
</script>

<template>
  <form
    @submit.prevent="
      submitMethod === 'post'
        ? form.post(submitRoute)
        : form.put(submitRoute)
    "
    class="space-y-6"
  >
    <!-- Data -->
    <div>
      <InputLabel value="Data" />
      <TextInput
        type="date"
        v-model="form.data_fatura"
        class="mt-1 block w-full"
      />
      <InputError :message="form.errors.data_fatura" />
    </div>

    <!-- Fornecedor -->
    <div>
      <InputLabel value="Fornecedor" />
      <select
        v-model.number="form.fornecedor_id"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
      >
        <option value="">Selecione o fornecedor</option>
        <option
          v-for="f in fornecedores"
          :key="f.id"
          :value="f.id"
        >
          {{ f.nome }}
        </option>
      </select>
      <InputError :message="form.errors.fornecedor_id" />
    </div>

    <!-- Linhas -->
    <div>
      <h3 class="font-semibold mb-2">Linhas da Fatura</h3>

      <div
        v-for="(linha, index) in form.linhas"
        :key="index"
        class="grid grid-cols-1 md:grid-cols-6 gap-2 mb-2"
      >
        <TextInput v-model="linha.descricao" placeholder="Descrição" />

        <TextInput
          type="number"
          min="1"
          v-model.number="linha.quantidade"
        />

        <TextInput
          type="number"
          step="0.01"
          v-model.number="linha.preco_unitario"
        />

        <TextInput
          type="number"
          step="0.01"
          v-model.number="linha.iva_percentagem"
        />

        <button
          type="button"
          class="text-red-600"
          @click="removeLinha(index)"
        >
          Remover
        </button>
      </div>

      <PrimaryButton type="button" @click="addLinha">
        + Adicionar linha
      </PrimaryButton>
    </div>

    <!-- Estado -->
    <div>
      <InputLabel value="Estado" />
      <select v-model="form.estado" class="mt-1 block w-full">
        <option value="Rascunho">Rascunho</option>
        <option value="Fechado">Fechado</option>
      </select>
    </div>

    <div class="flex justify-end">
      <PrimaryButton :disabled="form.processing">
        Guardar
      </PrimaryButton>
    </div>
  </form>
</template>
