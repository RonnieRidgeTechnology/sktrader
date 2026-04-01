<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FormCard from '@/Components/Admin/FormCard.vue';

const props = defineProps({ faq: Object });
const form = useForm({
  question: props.faq?.question ?? '',
  answer: props.faq?.answer ?? '',
  sort_order: props.faq?.sort_order ?? 0,
  status: props.faq != null ? props.faq.status : true,
  _method: props.faq ? 'PUT' : 'POST',
});

function cancel() {
  router.visit(route('admin.faqs.index'), { preserveState: false });
}
</script>
<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="faq ? 'Edit FAQ' : 'New FAQ'"
        :description="faq ? 'Update the question and answer.' : 'Add a new frequently asked question.'"
      >
        <template #actions>
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
            @click="cancel"
          >
            Cancel
          </button>
          <button
            type="submit"
            form="faq-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            {{ faq ? 'Update' : 'Create' }}
          </button>
        </template>
      </PageHeader>
    <form id="faq-form" class="space-y-8" @submit.prevent="form.post(faq ? route('admin.faqs.update', faq.id) : route('admin.faqs.store'))">
      <FormCard title="FAQ content" description="Question and answer shown on the FAQ page.">
        <div class="space-y-5">
          <div>
            <label for="question" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Question *</label>
            <input id="question" v-model="form.question" type="text" required class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white" />
            <p v-if="form.errors.question" class="mt-1 text-sm text-red-500">{{ form.errors.question }}</p>
          </div>
          <div>
            <label for="answer" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Answer *</label>
            <textarea id="answer" v-model="form.answer" rows="4" required class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white" />
            <p v-if="form.errors.answer" class="mt-1 text-sm text-red-500">{{ form.errors.answer }}</p>
          </div>
          <div class="grid gap-5 sm:grid-cols-2">
            <div>
              <label for="sort_order" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Sort order</label>
              <input id="sort_order" v-model.number="form.sort_order" type="number" min="0" class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white" />
            </div>
            <div class="flex items-center gap-3 pt-8">
              <input id="status" v-model="form.status" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-700" />
              <label for="status" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active (show on site)</label>
            </div>
          </div>
        </div>
      </FormCard>
    </form>
    </div>
  </AdminLayout>
</template>
