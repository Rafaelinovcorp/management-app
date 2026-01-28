<script setup>
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

defineProps({
  items: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['edit', 'delete'])
</script>

<template>
  <div class="rounded-md border">
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>NIF</TableHead>
          <TableHead>Nome</TableHead>
          <TableHead>Email</TableHead>
          <TableHead class="text-right">Ações</TableHead>
        </TableRow>
      </TableHeader>

      <TableBody>
        <TableRow v-for="item in items" :key="item.id">
          <TableCell>{{ item.nif }}</TableCell>
          <TableCell>{{ item.nome }}</TableCell>
          <TableCell>{{ item.email }}</TableCell>

          <TableCell class="text-right">
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="sm">⋮</Button>
              </DropdownMenuTrigger>

              <DropdownMenuContent>
                <DropdownMenuItem @click="emit('edit', item)">
                  Editar
                </DropdownMenuItem>

                <DropdownMenuItem
                  class="text-red-500"
                  @click="emit('delete', item)"
                >
                  Apagar
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>
