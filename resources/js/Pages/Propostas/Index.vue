<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'

defineProps({
  propostas: Array,
})

function criarProposta() {
  router.get(route('propostas.create'))
}

function formatCurrency(value) {
  return Number(value).toFixed(2)
}

function editar(id) {
  router.get(route('propostas.edit', id))
}

function fechar(id) {
  if (confirm('Tem a certeza que pretende fechar esta proposta?')) {
    router.post(route('propostas.fechar', id))
  }
}
</script>

<template>
  <Head title="Propostas" />

  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Propostas</h1>

        <Button @click="criarProposta">
          Nova Proposta
        </Button>
      </div>

      <!-- Tabela -->
      <div class="rounded-md border">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Data</TableHead>
              <TableHead>Número</TableHead>
              <TableHead>Validade</TableHead>
              <TableHead>Cliente</TableHead>
              <TableHead class="text-right">Valor Total</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead class="text-right">Ações</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-if="propostas.length === 0">
              <TableCell colspan="7" class="text-center text-muted-foreground">
                Nenhuma proposta encontrada.
              </TableCell>
            </TableRow>

            <TableRow v-for="proposta in propostas" :key="proposta.id">
              <TableCell>
                {{ proposta.data_proposta ?? '-' }}
              </TableCell>

              <TableCell>
                {{ proposta.numero }}
              </TableCell>

              <TableCell>
                {{ proposta.validade }}
              </TableCell>

              <TableCell>
                {{ proposta.cliente?.nome }}
              </TableCell>

              

              <TableCell class="text-right">
               {{ formatCurrency(proposta.total) }} €
              </TableCell>

              <TableCell>
                <span
                  class="px-2 py-1 rounded text-xs font-medium"
                  :class="proposta.estado === 'fechado'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700'"
                >
                  {{ proposta.estado }}
                </span>
              </TableCell>

              <TableCell class="text-right space-x-2">
                <Button
                  size="sm"
                  variant="outline"
                  @click="editar(proposta.id)"
                >
                  Editar
                </Button>

                <Button
                  v-if="proposta.estado === 'rascunho'"
                  size="sm"
                  variant="secondary"
                  @click="fechar(proposta.id)"
                >
                  Fechar
                </Button>

                <Button
                  v-if="proposta.estado === 'fechado'"
                  size="sm"
                  variant="outline"
                  as="a"
                  :href="route('propostas.pdf', proposta.id)"
                >
                  PDF
                </Button>

                <Button
                  v-if="proposta.estado === 'fechado'"
                  size="sm"
                  variant="outline"
                  @click="router.post(route('propostas.converter.encomenda', proposta.id))"
                >
                  Converter
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
