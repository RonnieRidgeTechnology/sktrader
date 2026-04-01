<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import { MessageSquareQuote, User } from 'lucide-vue-next';

const props = defineProps({ testimonial: Object });

const form = useForm({
  quote: props.testimonial?.quote ?? '',
  name: props.testimonial?.name ?? '',
  role: props.testimonial?.role ?? '',
  company: props.testimonial?.company ?? '',
  sort_order: props.testimonial?.sort_order ?? 0,
  status: props.testimonial != null ? props.testimonial.status : true,
  _method: props.testimonial ? 'PUT' : 'POST',
});
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="testimonial ? 'Edit testimonial' : 'New testimonial'"
        description="Testimonials appear in the homepage carousel. Quote, name, and optional role/company build trust."
      >
        <template #actions>
          <Link
            :href="route('admin.testimonials.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
          <button
            type="submit"
            form="testimonial-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            {{ testimonial ? 'Update' : 'Create' }}
          </button>
        </template>
      </PageHeader>

      <form
        id="testimonial-form"
        class="space-y-8"
        @submit.prevent="form.post(testimonial ? route('admin.testimonials.update', testimonial.id) : route('admin.testimonials.store'))"
      >
        <FormCard
          title="Quote"
          description="The main testimonial text shown to visitors. Keep it concise and impactful."
        >
          <template #vector>
            <MessageSquareQuote class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div>
            <label for="quote" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Quote *</label>
            <textarea
              id="quote"
              v-model="form.quote"
              rows="4"
              required
              class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
              placeholder="What they said about your brand..."
            />
            <p v-if="form.errors.quote" class="mt-1 text-sm text-red-500">{{ form.errors.quote }}</p>
          </div>
        </FormCard>

        <FormCard
          title="Author"
          description="Name is required. Role and company add credibility (e.g. Founder, Fitness Apparel Co.)."
        >
          <template #vector>
            <User class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name *</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. John Smith"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
            </div>
            <div>
              <label for="role" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Role</label>
              <input
                id="role"
                v-model="form.role"
                type="text"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. Founder"
              />
            </div>
            <div>
              <label for="company" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Company</label>
              <input
                id="company"
                v-model="form.company"
                type="text"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. Fitness Apparel Co."
              />
            </div>
          </div>
        </FormCard>

        <DataCard class="p-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6">
              <div class="flex items-center gap-3">
                <label for="sort_order" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Sort order</label>
                <input
                  id="sort_order"
                  v-model.number="form.sort_order"
                  type="number"
                  min="0"
                  class="w-24 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-center text-zinc-900 shadow-sm dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                />
              </div>
              <label class="flex cursor-pointer items-center gap-3">
                <input
                  v-model="form.status"
                  type="checkbox"
                  class="h-4 w-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-700"
                />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active (show on site)</span>
              </label>
            </div>
            <button
              type="submit"
              :disabled="form.processing"
              class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
            >
              {{ testimonial ? 'Update testimonial' : 'Create testimonial' }}
            </button>
          </div>
        </DataCard>
      </form>
    </div>
  </AdminLayout>
</template>
