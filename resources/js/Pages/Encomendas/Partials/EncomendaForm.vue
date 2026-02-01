<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  form: Object,
  clientes: Array,
  artigos: Array,
  submitRoute: String,
  submitMethod: {
    type: String,
    default: 'post',
  },
})

const addLinha = () => {
  props.form.linhas.push({
    artigo_id: '',
    fornecedor_id: '',
    quantidade: 1,
    preco_unitario: 0,
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
    <!-- Dados base -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Data -->
      <div>
        <InputLabel value="Data" />
        <input
          type="date"
          v-model="form.data_encomenda"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                 focus:border-indigo-500 focus:ring-indigo-500"
        />
        <InputError :message="form.errors.data_encomenda" />
      </div>

      <!-- Cliente -->
      <div>
        <InputLabel value="Cliente" />
        <select
          v-model="form.entidade_id"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                 focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option value="">Selecione o cliente</option>
          <option
            v-for="cliente in clientes"
            :key="cliente.id"
            :value="cliente.id"
          >
            {{ cliente.nome }}
          </option>
        </select>
        <InputError :message="form.errors.entidade_id" />
      </div>
    </div>

    <!-- Linhas -->
    <div>
      <h3 class="font-semibold mb-2">Linhas de Artigos</h3>

      <div
        v-for="(linha, index) in form.linhas"
        :key="index"
        class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end"
      >
        <!-- Artigo -->
        <div class="md:col-span-2">
          <InputLabel value="Artigo" />
          <select
            v-model="linha.artigo_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
          >
            <option value="">Selecione o artigo</option>
            <option
              v-for="artigo in artigos"
              :key="artigo.id"
              :value="artigo.id"
            >
              {{ artigo.referencia }} - {{ artigo.nome }}
            </option>
          </select>
        </div>

        <!-- Quantidade -->
        <div>
          <InputLabel value="Qtd" />
          <input
            type="number"
            min="1"
            v-model.number="linha.quantidade"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <!-- Preço -->
        <div>
          <InputLabel value="Preço" />
          <input
            type="number"
            step="0.01"
            v-model.number="linha.preco_unitario"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <!-- Remover -->
        <div class="flex justify-center">
          <button
            type="button"
            class="text-red-600 hover:underline text-sm"
            @click="removeLinha(index)"
          >
            Remover
          </button>
        </div>
      </div>

      <PrimaryButton type="button" @click="addLinha">
        + Adicionar linha
      </PrimaryButton>

      <InputError :message="form.errors.linhas" />
    </div>

    <!-- Estado -->
    <div>
      <InputLabel value="Estado" />
      <select
        v-model="form.estado"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
               focus:border-indigo-500 focus:ring-indigo-500"
      >
        <option value="Rascunho">Rascunho</option>
        <option value="Fechado">Fechado</option>
      </select>
      <InputError :message="form.errors.estado" />
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
      <PrimaryButton :disabled="form.processing">
        Guardar
      </PrimaryButton>
    </div>
  </form>
</template>
