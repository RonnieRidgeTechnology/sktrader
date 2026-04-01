<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import { formatDate } from '@/utils/formatDate';
import { Inbox, ArrowLeft, Mail, Building2, Phone, Globe, Package, Hash, MessageSquare, Paperclip, Calendar, MapPin } from 'lucide-vue-next';

const props = defineProps({
  inquiry: { type: Object, required: true },
});

function detailRow(label, value, icon) {
  return { label, value: value ?? '—', icon };
}

const rows = [
  detailRow('Name', props.inquiry.name, null),
  detailRow('Company', props.inquiry.company_name, Building2),
  detailRow('Email', props.inquiry.email, Mail),
  detailRow('WhatsApp', props.inquiry.whatsapp, Phone),
  detailRow('Country', props.inquiry.country, Globe),
  detailRow('Product interest', props.inquiry.product_interest, Package),
  detailRow('Quantity / MOQ', props.inquiry.quantity, Hash),
  detailRow('Message', props.inquiry.message, MessageSquare),
  detailRow('Submitted', formatDate(props.inquiry.created_at), Calendar),
  detailRow('IP address', props.inquiry.ip_address, MapPin),
];
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        :title="`Inquiry from ${inquiry.name}`"
        description="Full details of this contact form submission."
      >
        <template #actions>
          <Link
            :href="route('admin.inquiries.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            <ArrowLeft class="h-4 w-4" stroke-width="2" />
            Back to inquiries
          </Link>
        </template>
      </PageHeader>

      <DataCard class="p-0">
        <div class="border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
              <Inbox class="h-6 w-6" stroke-width="2" />
            </div>
            <div>
              <p class="font-semibold text-zinc-900 dark:text-white">{{ inquiry.name }}</p>
              <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ inquiry.email }}</p>
            </div>
          </div>
        </div>
        <dl class="divide-y divide-zinc-200 dark:divide-zinc-700">
          <div
            v-for="(row, idx) in rows"
            :key="idx"
            class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4"
          >
            <dt class="flex min-w-0 shrink-0 items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-44">
              <component v-if="row.icon" :is="row.icon" class="h-4 w-4 shrink-0" stroke-width="2" />
              {{ row.label }}
            </dt>
            <dd class="min-w-0 flex-1 text-sm text-zinc-900 dark:text-white">
              <span v-if="row.label === 'Message'" class="whitespace-pre-wrap">{{ row.value }}</span>
              <span v-else>{{ row.value }}</span>
            </dd>
          </div>
          <div
            v-if="inquiry.attachment_url"
            class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4"
          >
            <dt class="flex min-w-0 shrink-0 items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-44">
              <Paperclip class="h-4 w-4 shrink-0" stroke-width="2" />
              Attachment
            </dt>
            <dd class="min-w-0 flex-1">
              <a
                :href="inquiry.attachment_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 text-sm font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
              >
                <Paperclip class="h-4 w-4" stroke-width="2" />
                Download attachment
              </a>
            </dd>
          </div>
        </dl>
      </DataCard>
    </div>
  </AdminLayout>
</template>
