<template>
    <AppLayout title="Создание клиента">
      <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
              <form @submit.prevent="submit">
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Название *</label>
                  <input
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.title }"
                  />
                  <div v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</div>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.email }"
                  />
                  <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Телефон</label>
                  <input
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.phone }"
                  />
                  <div v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</div>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Адрес</label>
                  <textarea
                    v-model="form.address"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  ></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                  <Link :href="index().url" class="btn btn-secondary">
                    Отмена
                  </Link>
                  <button type="submit" class="btn btn-primary" :disabled="processing">
                    {{ processing ? 'Сохранение...' : 'Создать' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup lang="ts">
  import AppLayout from '@/layouts/AppLayout.vue'
  import { Link, router } from '@inertiajs/vue3'
  import { index, store } from '@/actions/App/Http/Controllers/ClientController'
  import { reactive, ref } from 'vue'

  const form = reactive({
    title: '',
    email: '',
    phone: '',
    address: '',
  })

  const errors = ref<Record<string, string>>({})
  const processing = ref(false)

  const submit = () => {
    processing.value = true
    const { url, method } = store()

    router[method](url, form, {
      preserveState: false,
      onSuccess: () => {
        processing.value = false
      },
      onError: (err) => {
        errors.value = err
        processing.value = false
      },
    })
  }
  </script>
