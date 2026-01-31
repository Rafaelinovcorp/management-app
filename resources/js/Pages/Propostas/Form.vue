<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { computed, ref } from 'vue'

const props = defineProps({
  proposta: Object,
  clientes: Array,
  artigos: Array,
  fornecedores: Array,
})

const isEdit = computed(() => props.proposta !== null)
const isFechada = computed(() => props.proposta?.estado === 'fechado')

/* -------------------------
   Form Cabeçalho
-------------------------- */
const form = useForm({
  cliente_id: props.proposta?.cliente_id ?? '',
  validade: props.proposta?.validade ?? '',
})

function guardarCabecalho() {
  if (isEdit.value) {
    form.put(route('propostas.update', props.proposta.id))
  } else {
    form.post(route('propostas.store'))
  }
}

function formatCurrency(value) {
  return Number(value).toFixed(2)
}


/* -------------------------
   Nova Linha
-------------------------- */
const linha = useForm({
  artigo_id: '',
  fornecedor_id: '',
  quantidade: 1,
  preco_unitario: 0,
  preco_custo: '',
  iva_percentagem: 23,
})

function adicionarLinha() {
  linha.post(route('propostas.linhas.add', props.proposta.id), {
    onSuccess: () => linha.reset(),
  })
}

function removerLinha(id) {
  if (confirm('Remover esta linha?')) {
    router.delete(route('propostas.linhas.remove', id))
  }
}

function fecharProposta() {
  if (confirm('Depois de fechada não poderá editar. Continuar?')) {
    router.post(route('propostas.fechar', props.proposta.id))
  }
}
</script>

<template>
  <Head :title="isEdit ? `Proposta ${proposta.numero}` : 'Nova Proposta'" />

  <AuthenticatedLayout>
    <!-- HEADER (igual ao da imagem) -->
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        {{ isEdit ? 'Editar Proposta' : 'Criar Proposta' }}
      </h2>
    </template>

    <!-- CONTAINER CENTRAL -->
    <div class="max-w-3xl mx-auto p-6 space-y-6">

      <!-- CARD -->
      <div class="bg-white shadow rounded-lg p-6 space-y-6">

        <!-- TÍTULO + AÇÃO -->
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-semibold text-gray-800">
            {{ isEdit ? `Proposta #${proposta.numero}` : 'Criar Proposta' }}
          </h1>
        
          <Button
            v-if="isEdit && !isFechada && proposta.linhas.length > 0"
            variant="secondary"
            @click="fecharProposta"
          >
            Fechar Proposta
          </Button>
        </div>
        
        <!-- FORM CABEÇALHO -->
        <div class="space-y-4">

          <div>
            <Label>Cliente</Label>
            <Select v-model="form.cliente_id" :disabled="isFechada">
              <SelectTrigger>
                <SelectValue placeholder="Selecionar cliente" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="cliente in clientes"
                  :key="cliente.id"
                  :value="cliente.id"
                >
                  {{ cliente.nome }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Label>Validade</Label>
            <Input
              type="date"
              v-model="form.validade"
              :min="new Date().toISOString().split('T')[0]"
              :disabled="isFechada"
            />
          </div>

          <div class="pt-2">
            <Button
              class="w-full"
              :disabled="isFechada"
              @click="guardarCabecalho"
            >
              {{ isEdit ? 'Guardar Proposta' : 'Criar Proposta' }}
            </Button>
          </div>
        </div>
      </div>

      <!-- LINHAS (SÓ SE EDITAR) -->
      <div
        v-if="isEdit"
        class="bg-white shadow rounded-lg p-6 space-y-6"
      >
        <h2 class="text-lg font-semibold text-gray-800">
          Linhas de Artigos
        </h2>

        <!-- NOVA LINHA -->
        <div
          v-if="!isFechada"
          class="space-y-4 bg-gray-50 p-4 rounded border"
        >
          <div>
            <Label>Artigo</Label>
            <Select v-model="linha.artigo_id">
              <SelectTrigger>
                <SelectValue placeholder="Selecionar artigo" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="artigo in artigos"
                  :key="artigo.id"
                  :value="artigo.id"
                >
                  {{ artigo.referencia }} - {{ artigo.nome }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Label>Quantidade</Label>
            <Input type="number" min="1" v-model="linha.quantidade" />
          </div>

          <div>
            <Label>Preço</Label>
            <Input type="number" step="0.01" v-model="linha.preco_unitario" />
          </div>

          <div>
            <Label>IVA %</Label>
            <Input type="number" step="0.01" v-model="linha.iva_percentagem" />
          </div>

          <div>
            <Label>Fornecedor</Label>
            <Select v-model="linha.fornecedor_id">
              <SelectTrigger>
                <SelectValue placeholder="Opcional" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="f in fornecedores"
                  :key="f.id"
                  :value="f.id"
                >
                  {{ f.nome }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <Button class="w-full" @click="adicionarLinha">
            Adicionar Linha
          </Button>
        </div>

        <!-- TABELA -->
        <div class="rounded-md border overflow-hidden">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Artigo</TableHead>
                <TableHead>Qtd</TableHead>
                <TableHead>Total</TableHead>
                <TableHead></TableHead>
              </TableRow>
            </TableHeader>

            <TableBody>
              <TableRow
                v-for="linha in proposta.linhas"
                :key="linha.id"
              >
                <TableCell>{{ linha.artigo?.nome }}</TableCell>
                <TableCell>{{ linha.quantidade }}</TableCell>
                <TableCell>
                  {{ formatCurrency(linha.total) }} €
                </TableCell>
                <TableCell>
                  <Button
                    v-if="!isFechada"
                    size="sm"
                    variant="destructive"
                    @click="removerLinha(linha.id)"
                  >
                    X
                  </Button>
                </TableCell>
              </TableRow>

              <TableRow v-if="proposta.linhas.length === 0">
                <TableCell colspan="4" class="text-center text-muted-foreground">
                  Sem linhas adicionadas
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- TOTAL -->
        <div class="text-right pt-4">
          <p class="text-sm text-gray-500">Total da Proposta</p>
          <p class="text-2xl font-bold">
            {{ formatCurrency(proposta.total) }} €
          </p>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>
