<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'

const page = usePage()
const user = computed(() => page.props.auth.user)

const enabling = ref(false)
const confirming = ref(false)
const code = ref('')

function enable() {
  enabling.value = true
  router.post('/user/two-factor-authentication', {}, {
    onSuccess: () => confirming.value = true,
    onFinish: () => enabling.value = false,
  })
}

function confirm() {
  router.post('/user/confirmed-two-factor-authentication', {
    code: code.value,
  })
}

function disable() {
  router.delete('/user/two-factor-authentication')
}
</script>

<template>
  <section class="mt-6 space-y-4">
    <h2 class="text-lg font-medium">Two Factor Authentication</h2>

    <!-- NÃO ATIVO -->
    <div v-if="!user.two_factor_confirmed_at">
      <Button @click="enable" :disabled="enabling">
        Enable 2FA
      </Button>
    </div>

    <!-- CONFIRMAÇÃO -->
    <div v-if="confirming">
      <p class="text-sm text-gray-600 mb-2">
        Scan the QR code with your authenticator app and enter the code.
      </p>

      <div v-html="page.props.auth.qrCodeSvg" class="mb-4"></div>

      <input
        v-model="code"
        placeholder="6-digit code"
        class="border p-2 w-full mb-2"
      />

      <Button @click="confirm">
        Confirm
      </Button>
    </div>

    <!-- ATIVO -->
    <div v-if="user.two_factor_confirmed_at">
      <Button variant="destructive" @click="disable">
        Disable 2FA
      </Button>
    </div>
  </section>
</template>
