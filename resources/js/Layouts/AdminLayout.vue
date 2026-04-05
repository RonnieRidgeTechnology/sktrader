<script setup>
import { computed, inject } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

function fallbackRoute(name, ...params) {
  if (typeof window !== 'undefined' && typeof window.route === 'function') {
    if (arguments.length === 0) return window.route();
    return window.route(name, ...params);
  }
  if (arguments.length === 0) return { current: null };
  return '#';
}
const route = inject('route') ?? fallbackRoute;
import {
  LayoutDashboard,
  Inbox,
  Mail,
  Package,
  FolderTree,
  HelpCircle,
  Search,
  Layout,
  ScrollText,
  Film,
  MessageSquareQuote,
  Settings,
  ExternalLink,
  LogOut,
  ChevronRight,
  User,
  ShoppingBag,
  TrendingUp,
} from 'lucide-vue-next';

const page = usePage();
const flash = computed(() => page.props.flash || {});
const auth = computed(() => page.props.auth || {});
const user = computed(() => auth.value?.user || null);

const navGroups = [
  {
    label: 'Overview',
    items: [
      { route: 'admin.dashboard', label: 'Dashboard', icon: LayoutDashboard },
      { route: 'admin.reports.index', label: 'Reports', icon: TrendingUp },
    ],
  },
  {
    label: 'Content',
    items: [
      { route: 'admin.orders.index', label: 'Orders', icon: ShoppingBag },
      { route: 'admin.inquiries.index', label: 'Inquiries', icon: Inbox },
      { route: 'admin.subscribers.index', label: 'Subscribers', icon: Mail },
      { route: 'admin.categories.index', label: 'Categories', icon: FolderTree },
      { route: 'admin.products.index', label: 'Products', icon: Package },
      { route: 'admin.faqs.index', label: 'FAQs', icon: HelpCircle },
    ],
  },
  {
    label: 'Site',
    items: [
      { route: 'admin.page-content.index', label: 'Page Content', icon: ScrollText },
      { route: 'admin.homepage.index', label: 'Homepage', icon: Layout },
      { route: 'admin.video-reels.index', label: 'Video Reels', icon: Film },
      { route: 'admin.testimonials.index', label: 'Testimonials', icon: MessageSquareQuote },
      { route: 'admin.seo.index', label: 'SEO', icon: Search },
    ],
  },
  {
    label: 'System',
    items: [
      { route: 'admin.settings.index', label: 'Settings', icon: Settings },
    ],
  },
];

function isActive(routeName) {
  if (!route) return false;
  const current = route().current?.() ?? route().current;
  if (!current) return false;
  if (routeName === 'admin.dashboard') return current === 'admin.dashboard';
  const base = routeName.replace(/\.index$/, '');
  return current === routeName || current.startsWith(base + '.');
}

const breadcrumbLabel = computed(() => {
  if (!route) return 'Dashboard';
  const r = route();
  const name = typeof r?.current === 'function' ? r.current() : r?.current;
  if (!name) return 'Dashboard';
  const labels = {
    'admin.dashboard': 'Dashboard',
    'admin.orders.index': 'Orders',
    'admin.orders.show': 'Order details',
    'admin.inquiries.index': 'Inquiries',
    'admin.subscribers.index': 'Subscribers',
    'admin.products.index': 'Products',
    'admin.products.create': 'New product',
    'admin.products.edit': 'Edit product',
    'admin.categories.index': 'Categories',
    'admin.categories.create': 'New category',
    'admin.categories.edit': 'Edit category',
    'admin.faqs.index': 'FAQs',
    'admin.faqs.create': 'New FAQ',
    'admin.faqs.edit': 'Edit FAQ',
    'admin.video-reels.index': 'Video Reels',
    'admin.video-reels.create': 'New video',
    'admin.video-reels.edit': 'Edit video',
    'admin.testimonials.index': 'Testimonials',
    'admin.testimonials.create': 'New testimonial',
    'admin.testimonials.edit': 'Edit testimonial',
    'admin.seo.index': 'SEO',
    'admin.seo.edit': 'Edit SEO',
    'admin.page-content.index': 'Page Content',
    'admin.page-content.edit': 'Edit page',
    'admin.homepage.index': 'Homepage',
    'admin.homepage.edit': 'Edit section',
    'admin.homepage.edit.spotlight': 'Edit: Spotlight',
    'admin.homepage.edit.journey_strip': 'Edit: Journey',
    'admin.homepage.edit.why_pillars': 'Edit: Why us',
    'admin.settings.index': 'Settings',
  };
  return labels[name] || name.replace(/^admin\./, '').replace(/\./g, ' / ');
});
</script>

<template>
  <div class="admin-panel min-h-screen bg-zinc-100 dark:bg-zinc-950">
    <!-- Sidebar: premium dark with subtle gradient -->
    <aside
      class="admin-sidebar fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-zinc-200/60 bg-white shadow-xl dark:border-zinc-800/80 dark:bg-zinc-900/95"
    >
      <div class="flex h-16 shrink-0 items-center border-b border-zinc-200/80 px-5 dark:border-zinc-800/80">
        <Link
          :href="route('admin.dashboard')"
          class="flex items-center gap-2.5 text-left"
          :preserve-state="false"
          :preserve-scroll="false"
        >
          <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500 text-white shadow-lg shadow-amber-500/25">
            <LayoutDashboard class="h-5 w-5" stroke-width="2" />
          </span>
          <span class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white">Admin</span>
        </Link>
      </div>
      <nav class="flex-1 overflow-y-auto py-5">
        <div v-for="group in navGroups" :key="group.label" class="px-3 pb-5">
          <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
            {{ group.label }}
          </p>
          <ul class="space-y-1">
            <li v-for="item in group.items" :key="item.route">
              <Link
                :href="route(item.route)"
                class="admin-nav-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium transition-all duration-200"
                :class="isActive(item.route)
                  ? 'bg-amber-500/15 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 shadow-sm'
                  : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white'"
                :preserve-state="false"
                :preserve-scroll="false"
              >
                <component :is="item.icon" class="h-5 w-5 shrink-0 opacity-90" stroke-width="2" />
                <span>{{ item.label }}</span>
                <ChevronRight v-if="isActive(item.route)" class="ml-auto h-4 w-4 text-amber-600 dark:text-amber-400" stroke-width="2.5" />
              </Link>
            </li>
          </ul>
        </div>
      </nav>
    </aside>

    <!-- Main -->
    <main class="min-h-screen pl-64">
      <!-- Top bar: clean with soft shadow -->
      <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-zinc-200/80 bg-white/90 px-6 shadow-sm backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-900/90">
        <div class="flex min-w-0 items-center gap-4">
          <nav class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400" aria-label="Breadcrumb">
            <Link
              :href="route('admin.dashboard')"
              class="transition hover:text-amber-600 dark:hover:text-amber-400"
              :preserve-state="false"
              :preserve-scroll="false"
            >
              Dashboard
            </Link>
            <template v-if="$slots.breadcrumb">
              <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-600">/</span>
              <slot name="breadcrumb" />
            </template>
            <template v-else-if="breadcrumbLabel !== 'Dashboard'">
              <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-600">/</span>
              <span class="font-semibold text-zinc-900 dark:text-white">{{ breadcrumbLabel }}</span>
            </template>
          </nav>
        </div>
        <div class="flex items-center gap-2">
          <Link
            :href="route('profile.edit')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-amber-300 hover:bg-amber-50/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-amber-600 dark:hover:bg-amber-900/20"
          >
            <User class="h-4 w-4" stroke-width="2" />
            Profile
          </Link>
          <Link
            :href="route('home')"
            target="_blank"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-amber-300 hover:bg-amber-50/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-amber-600 dark:hover:bg-amber-900/20"
          >
            <ExternalLink class="h-4 w-4" stroke-width="2" />
            View site
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            <LogOut class="h-4 w-4" stroke-width="2" />
            Logout
          </Link>
        </div>
      </header>

      <!-- Content -->
      <div class="p-6 lg:p-8">
        <!-- Flash messages -->
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 translate-y-2"
          leave-active-class="transition duration-150 ease-in"
          leave-to-class="opacity-0"
        >
          <div
            v-if="flash?.success"
            class="mb-6 flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-800 dark:bg-emerald-900/25"
          >
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ flash.success }}</p>
          </div>
        </Transition>
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 translate-y-2"
          leave-active-class="transition duration-150 ease-in"
          leave-to-class="opacity-0"
        >
          <div
            v-if="flash?.error"
            class="mb-6 flex items-center justify-between rounded-2xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/25"
          >
            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ flash.error }}</p>
          </div>
        </Transition>

        <slot />
      </div>
    </main>
  </div>
</template>
