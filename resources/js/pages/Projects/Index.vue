<template>
    <AppLayout title="Проекты">
      <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="flex justify-end mb-4">
            <Link :href="create().url" class="btn btn-primary">
              + Новый проект
            </Link>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="project in projects.data"
              :key="project.id"
              class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition"
            >
              <div class="p-6">
                <div class="flex justify-between items-start">
                  <Link :href="show(project).url" class="text-xl font-semibold text-gray-900 hover:text-indigo-600">
                    {{ project.title }}
                  </Link>
                  <div class="flex space-x-2">
                    <Link :href="edit(project).url" class="text-blue-600 hover:text-blue-900">
                      ✏️
                    </Link>
                    <button @click="destroyProject(project)" class="text-red-600 hover:text-red-900">
                      🗑️
                    </button>
                  </div>
                </div>

                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                  {{ project.description || 'Нет описания' }}
                </p>

                <div class="mt-4 flex justify-between items-center">
                  <span class="text-xs text-gray-500">
                    Клиент: {{ project.client?.title }}
                  </span>
                  <span
                    class="px-2 py-1 text-xs rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': project.status === 'active',
                      'bg-yellow-100 text-yellow-800': project.status === 'paused',
                      'bg-gray-100 text-gray-800': project.status === 'closed',
                    }"
                  >
                    {{ statusText[project.status] }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Пагинация -->
          <div class="mt-6">
            <div v-html="projects.links" class="pagination"></div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup lang="ts">
  import AppLayout from '@/layouts/AppLayout.vue'
  import { Link, router } from '@inertiajs/vue3'
  import { create, edit, show, destroy } from '@/actions/App/Http/Controllers/ProjectController'

  defineProps<{
    projects: {
      data: any[]
      links: string
    }
  }>()

  const statusText: Record<string, string> = {
    active: 'Активный',
    paused: 'Приостановлен',
    closed: 'Закрыт',
  }

  const destroyProject = (project: any) => {
    if (confirm(`Удалить проект "${project.title}"?`)) {
      const { url, method } = destroy(project.id)
      router[method](url)
    }
  }
  </script>
