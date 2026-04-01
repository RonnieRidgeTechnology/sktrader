<script setup>
import { computed, inject } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const route = inject('route');
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import {
  Inbox,
  Mail,
  Package,
  FolderTree,
  MessageSquareQuote,
  Film,
  HelpCircle,
  ArrowRight,
  Plus,
  Layout,
  ScrollText,
  Search,
  Settings,
  ShoppingBag,
  TrendingUp,
  TrendingDown,
  BarChart3,
  Target,
} from 'lucide-vue-next';

const props = defineProps({
  stats: Object,
  recentInquiries: Array,
  recentOrders: { type: Array, default: () => [] },
  analytics: Object,
});

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const stats = props.stats || {};
const recent = props.recentInquiries || [];
const recentOrders = props.recentOrders || [];
const analytics = props.analytics || {};
const last7Days = analytics.inquiriesLast7Days || [];
const topInterests = analytics.topProductInterests || [];
const categoryCounts = analytics.categoryProductCounts || [];
const maxDayCount = Math.max(1, ...last7Days.map((d) => d.count));

const statCards = [
  { key: 'inquiries', label: 'Inquiries', icon: Inbox, route: 'admin.inquiries.index', color: 'bg-blue-500/15 text-blue-600 dark:text-blue-400', desc: 'Contact form submissions' },
  { key: 'subscribers', label: 'Subscribers', icon: Mail, route: 'admin.subscribers.index', color: 'bg-teal-500/15 text-teal-600 dark:text-teal-400', desc: 'Newsletter signups' },
  { key: 'orders', label: 'Orders', icon: ShoppingBag, route: 'admin.orders.index', color: 'bg-orange-500/15 text-orange-600 dark:text-orange-400', desc: 'Checkout orders (all payment methods)' },
  { key: 'products', label: 'Products', icon: Package, route: 'admin.products.index', color: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400', desc: 'Product catalog' },
  { key: 'categories', label: 'Categories', icon: FolderTree, route: 'admin.categories.index', color: 'bg-amber-500/15 text-amber-600 dark:text-amber-400', desc: 'Product categories' },
  { key: 'testimonials', label: 'Testimonials', icon: MessageSquareQuote, route: 'admin.testimonials.index', color: 'bg-violet-500/15 text-violet-600 dark:text-violet-400', desc: 'Customer quotes' },
  { key: 'video_reels', label: 'Video Reels', icon: Film, route: 'admin.video-reels.index', color: 'bg-rose-500/15 text-rose-600 dark:text-rose-400', desc: 'Factory & promo videos' },
  { key: 'faqs', label: 'FAQs', icon: HelpCircle, route: 'admin.faqs.index', color: 'bg-zinc-500/15 text-zinc-600 dark:text-zinc-400', desc: 'Frequently asked questions' },
];

const quickActions = [
  { label: 'Add product', route: 'admin.products.create', icon: Plus },
  { label: 'Edit site content', route: 'admin.page-content.index', icon: ScrollText },
  { label: 'Edit homepage', route: 'admin.homepage.index', icon: Layout },
  { label: 'Manage SEO', route: 'admin.seo.index', icon: Search },
  { label: 'Settings', route: 'admin.settings.index', icon: Settings },
];

function go(routeName) {
  router.visit(route(routeName), { preserveState: false });
}

function goOrder(id) {
  router.visit(route('admin.orders.show', id), { preserveState: false });
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <!-- Welcome: premium header -->
      <div class="rounded-2xl border border-zinc-200/80 bg-white px-6 py-8 shadow-[0_1px_3px_rgba(0,0,0,0.04)] dark:border-zinc-700/80 dark:bg-zinc-900/80 sm:px-8 sm:py-10">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-3xl">
              Welcome back{{ user?.name ? `, ${user.name}` : '' }}
            </h1>
            <p class="mt-2 text-zinc-600 dark:text-zinc-400">
              Here’s your overview. Use the quick actions or cards below to manage content.
            </p>
          </div>
          <div class="flex flex-wrap gap-3">
            <button
              v-for="action in quickActions"
              :key="action.route"
              type="button"
              class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-amber-300 hover:bg-amber-50 hover:text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-amber-600 dark:hover:bg-amber-900/20"
              @click="go(action.route)"
            >
              <component :is="action.icon" class="h-4 w-4" stroke-width="2" />
              {{ action.label }}
            </button>
          </div>
        </div>
      </div>

      <!-- Recent store orders (checkout) -->
      <DataCard>
        <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
          <div class="flex items-center gap-2">
            <ShoppingBag class="h-5 w-5 text-orange-500 dark:text-orange-400" stroke-width="2" />
            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Recent orders</h2>
          </div>
          <button
            type="button"
            class="text-sm font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
            @click="go('admin.orders.index')"
          >
            View all
          </button>
        </div>
        <div v-if="recentOrders.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
          <button
            v-for="ord in recentOrders"
            :key="ord.id"
            type="button"
            class="flex w-full items-center justify-between px-6 py-4 text-left transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
            @click="goOrder(ord.id)"
          >
            <div class="min-w-0 flex-1">
              <p class="font-mono text-sm font-semibold text-zinc-900 dark:text-white">{{ ord.number }}</p>
              <p class="truncate text-sm text-zinc-500 dark:text-zinc-400">
                {{ ord.guest_name || 'Guest' }} · {{ ord.items_count }} {{ ord.items_count === 1 ? 'item' : 'items' }} · {{ ord.status }}
              </p>
            </div>
            <span class="ml-4 shrink-0 text-sm font-medium text-zinc-700 dark:text-zinc-300">
              {{ new Intl.NumberFormat('en-ZM', { style: 'currency', currency: ord.currency || 'ZMW' }).format(ord.total) }}
            </span>
          </button>
        </div>
        <div v-else class="px-6 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
          No orders in the database yet. After a customer completes checkout, they will appear here and under Orders.
        </div>
      </DataCard>

      <!-- Analytics row: inquiries trend + 7-day chart -->
      <div class="grid gap-6 lg:grid-cols-3">
        <DataCard class="lg:col-span-2">
          <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
            <div class="flex items-center gap-2">
              <BarChart3 class="h-5 w-5 text-amber-500 dark:text-amber-400" stroke-width="2" />
              <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Inquiries (last 7 days)</h2>
            </div>
            <div v-if="analytics.inquiriesTrendPercent !== undefined" class="flex items-center gap-1.5">
              <component
                :is="analytics.inquiriesTrendPercent >= 0 ? TrendingUp : TrendingDown"
                class="h-4 w-4"
                :class="analytics.inquiriesTrendPercent >= 0 ? 'text-emerald-500' : 'text-red-500'"
                stroke-width="2"
              />
              <span
                class="text-sm font-semibold"
                :class="analytics.inquiriesTrendPercent >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'"
              >
                {{ analytics.inquiriesTrendPercent >= 0 ? '+' : '' }}{{ analytics.inquiriesTrendPercent }}%
              </span>
              <span class="text-xs text-zinc-500 dark:text-zinc-400">vs last month</span>
            </div>
          </div>
          <div class="px-6 py-5">
            <div class="flex items-end justify-between gap-2" style="min-height: 120px;">
              <template v-for="(day, i) in last7Days" :key="day.date">
                <div class="flex flex-1 flex-col items-center gap-2">
                  <div
                    class="w-full max-w-[40px] rounded-t-lg bg-amber-500/80 transition-all duration-300 hover:bg-amber-500 dark:bg-amber-500/90"
                    :style="{ height: maxDayCount ? `${(day.count / maxDayCount) * 80 + 8}px` : '8px' }"
                    :title="`${day.label}: ${day.count}`"
                  />
                  <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ day.label }}</span>
                </div>
              </template>
            </div>
          </div>
        </DataCard>

        <DataCard>
          <div class="flex items-center gap-2 border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
            <Target class="h-5 w-5 text-amber-500 dark:text-amber-400" stroke-width="2" />
            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Top interests</h2>
          </div>
          <div class="px-6 py-4">
            <ul v-if="topInterests.length" class="space-y-3">
              <li
                v-for="(item, idx) in topInterests"
                :key="idx"
                class="flex items-center justify-between gap-2 text-sm"
              >
                <span class="min-w-0 truncate font-medium text-zinc-800 dark:text-zinc-200">{{ item.name }}</span>
                <span class="shrink-0 rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">{{ item.count }}</span>
              </li>
            </ul>
            <p v-else class="py-4 text-center text-sm text-zinc-500 dark:text-zinc-400">No product interests yet.</p>
          </div>
        </DataCard>
      </div>

      <!-- Stats grid -->
      <div>
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Overview</h2>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Content and lead counts</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <button
            v-for="card in statCards"
            :key="card.key"
            type="button"
            class="group block text-left"
            @click="go(card.route)"
          >
            <DataCard class="h-full transition-all duration-200 hover:shadow-lg">
              <div class="flex items-start justify-between p-5">
                <div class="flex items-center gap-4">
                  <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl" :class="card.color">
                    <component :is="card.icon" class="h-6 w-6" stroke-width="2" />
                  </span>
                  <div class="min-w-0">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ stats[card.key] ?? 0 }}</p>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ card.label }}</p>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">{{ card.desc }}</p>
                  </div>
                </div>
                <ArrowRight class="h-5 w-5 shrink-0 text-zinc-400 transition group-hover:translate-x-1 group-hover:text-amber-500 dark:group-hover:text-amber-400" stroke-width="2" />
              </div>
            </DataCard>
          </button>
        </div>
      </div>

      <!-- Second row: category breakdown + recent inquiries -->
      <div class="grid gap-6 lg:grid-cols-3">
        <DataCard v-if="categoryCounts.length" class="lg:col-span-1">
          <div class="flex items-center gap-2 border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
            <FolderTree class="h-5 w-5 text-amber-500 dark:text-amber-400" stroke-width="2" />
            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Products by category</h2>
          </div>
          <ul class="px-6 py-4 space-y-2">
            <li
              v-for="cat in categoryCounts"
              :key="cat.id"
              class="flex items-center justify-between text-sm"
            >
              <span class="truncate text-zinc-700 dark:text-zinc-300">{{ cat.name }}</span>
              <span class="shrink-0 font-medium text-zinc-500 dark:text-zinc-400">{{ cat.products_count }}</span>
            </li>
          </ul>
        </DataCard>

        <DataCard :class="categoryCounts.length ? 'lg:col-span-2' : 'lg:col-span-3'">
          <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Recent inquiries</h2>
            <button
              type="button"
              class="text-sm font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
              @click="go('admin.inquiries.index')"
            >
              View all
            </button>
          </div>
          <div v-if="recent.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
            <button
              v-for="inq in recent"
              :key="inq.id"
              type="button"
              class="flex w-full items-center justify-between px-6 py-4 text-left transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
              @click="go('admin.inquiries.index')"
            >
              <div class="min-w-0 flex-1">
                <p class="truncate font-medium text-zinc-900 dark:text-white">{{ inq.name }}</p>
                <p class="truncate text-sm text-zinc-500 dark:text-zinc-400">{{ inq.email }} · {{ inq.product_interest || 'General' }}</p>
              </div>
              <span class="ml-4 shrink-0 text-xs text-zinc-400 dark:text-zinc-500">
                {{ new Date(inq.created_at).toLocaleDateString() }}
              </span>
            </button>
          </div>
          <div v-else class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
            No inquiries yet. They will appear here when customers submit the contact form.
          </div>
        </DataCard>
      </div>
    </div>
  </AdminLayout>
</template>
