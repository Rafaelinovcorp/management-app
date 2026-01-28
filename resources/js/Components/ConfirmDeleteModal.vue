<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
  show: Boolean,
  title: String,
  message: String,
})

const emit = defineEmits(['confirm', 'cancel'])
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-xl font-bold mb-4">
        {{ title }}
      </h2>

      <p class="text-gray-600 mb-6">
        {{ message }}
      </p>

      <div class="flex justify-end gap-3">
        <button
          class="px-4 py-2 rounded-md border"
          @click="$emit('cancel')"
        >
          Cancelar
        </button>

        <PrimaryButton
          class="bg-red-600 hover:bg-red-700"
          @click="$emit('confirm')"
        >
          Apagar
        </PrimaryButton>
      </div>
    </div>
  </div>
</template>
