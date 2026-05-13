<template>
    <AppLayout :title="`Клиенты (${clients.total})`">
      <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="flex justify-end mb-4">
            <Link :href="create().url" class="btn btn-primary">
              + Новый клиент
            </Link>
          </div>

          <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
              <!-- Таблица клиентов -->
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Телефон</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="client in clients.data" :key="client.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ client.id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <Link :href="show(client).url" class="text-indigo-600 hover:underline">
                        {{ client.title }}
                      </Link>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ client.email || '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ client.phone || '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                      <Link :href="edit(client).url" class="text-blue-600 hover:text-blue-900">
                        ✏️
                      </Link>
                      <button @click="destroyClient(client)" class="text-red-600 hover:text-red-900">
                        🗑️
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Пагинация -->
              <div class="mt-4">
                <div v-html="clients.links" class="pagination"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup lang="ts">
  import AppLayout from '@/layouts/AppLayout.vue'
  import { Link, router } from '@inertiajs/vue3'
  import { create, edit, show, destroy } from '@/actions/App/Http/Controllers/ClientController'

  defineProps<{
    clients: {
      data: any[]
      total: number
      links: string
    }
  }>()

  defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Клиенты',
                href: '/clients',
            },
        ],
    },
})

  // Удаление клиента
  const destroyClient = (client: any) => {
    if (confirm(`Удалить клиента "${client.title}"?`)) {
      const { url, method } = destroy(client.id)
      router[method](url)
    }
  }
  </script>

  <style scoped>
  .pagination :deep(span, a) {
    @apply px-3 py-1 mx-1 rounded;
  }
  .pagination :deep(.active) {
    @apply bg-indigo-600 text-white;
  }
  </style>
