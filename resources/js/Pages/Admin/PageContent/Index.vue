<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import { ScrollText, Pencil, ExternalLink } from 'lucide-vue-next';

defineProps({ pages: Array });
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="Page Content"
        description="Edit the main content of every public page. Changes appear on the live site immediately after saving."
      />

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="p in pages"
          :key="p.page_key"
          class="group flex flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-amber-600/50"
        >
          <div class="flex items-start justify-between gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 transition group-hover:bg-amber-100 group-hover:text-amber-700 dark:bg-zinc-700 dark:text-zinc-400 dark:group-hover:bg-amber-900/30 dark:group-hover:text-amber-400">
              <ScrollText class="h-6 w-6" stroke-width="2" />
            </div>
            <span
              v-if="p.has_content"
              class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400"
            >
              Edited
            </span>
          </div>
          <h2 class="mt-4 font-semibold text-zinc-900 dark:text-white">
            {{ p.name }}
          </h2>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            {{ p.page_key }}
          </p>
          <div class="mt-4 flex flex-wrap items-center gap-3">
            <Link
              :href="route('admin.page-content.edit', p.page_key)"
              class="inline-flex items-center gap-2 text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline"
            >
              <Pencil class="h-4 w-4" stroke-width="2" />
              <span>Edit content</span>
            </Link>
            <a
              v-if="p.route"
              :href="route(p.route)"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
              @click.stop
            >
              <ExternalLink class="h-4 w-4" stroke-width="2" />
              <span>View on site</span>
            </a>
          </div>
        </div>
      </div>

      <p class="text-sm text-zinc-500 dark:text-zinc-400">
        Click a page to edit its titles, paragraphs, and sections. For SEO (meta title, description) use
        <Link :href="route('admin.seo.index')" class="font-medium text-zinc-700 underline hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">SEO</Link>.
      </p>
    </div>
  </AdminLayout>
</template>
