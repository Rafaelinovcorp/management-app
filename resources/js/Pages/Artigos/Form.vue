<script setup>
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import {
  Select,
  SelectTrigger,
  SelectContent,
  SelectItem,
  SelectValue
} from '@/Components/ui/select'

const props = defineProps({
  artigo: Object,
  ivas: Array,
  submitUrl: String,
  method: String,
})

const form = useForm({
  referencia: props.artigo?.referencia ?? '',
  nome: props.artigo?.nome ?? '',
  descricao: props.artigo?.descricao ?? '',
  preco: props.artigo?.preco ?? '',
  iva_id: props.artigo?.iva_id ?? '',
  foto: null,
  observacoes: props.artigo?.observacoes ?? '',
  estado: props.artigo?.estado ?? 'Ativo',
})

function submit() {
  form.submit(props.method, props.submitUrl)
}
</script>

<template>
  <form @submit.prevent="submit" class="space-y-6 max-w-xl">

    <Input v-model="form.referencia" placeholder="Referência" />
    <Input v-model="form.nome" placeholder="Nome" />

    <Textarea v-model="form.descricao" placeholder="Descrição" />

    <Input type="number" step="0.01" v-model="form.preco" placeholder="Preço" />

    <!-- IVA -->
    <Select v-model="form.iva_id">
      <SelectTrigger>
        <SelectValue placeholder="Selecionar IVA" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem
          v-for="iva in ivas"
          :key="iva.id"
          :value="iva.id"
        >
          {{ iva.nome }} ({{ iva.percentagem }}%)
        </SelectItem>
      </SelectContent>
    </Select>

    <Input type="file" @change="e => form.foto = e.target.files[0]" />

    <Textarea v-model="form.observacoes" placeholder="Observações" />

    <Select v-model="form.estado">
      <SelectTrigger>
        <SelectValue />
      </SelectTrigger>
      <SelectContent>
        <SelectItem value="Ativo">Ativo</SelectItem>
        <SelectItem value="Inativo">Inativo</SelectItem>
      </SelectContent>
    </Select>

    <Button type="submit">
      Guardar
    </Button>

  </form>
</template>
