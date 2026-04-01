<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
  LayoutDashboard,
  ShoppingBag,
  User,
  LogOut,
  ChevronRight,
  Menu,
  X,
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const isAdmin = computed(() => user.value?.is_admin === true);

const sidebarOpen = ref(false);

const navItems = [
  { label: 'Dashboard', route: 'account.dashboard', icon: LayoutDashboard },
  { label: 'My Orders', route: 'account.orders.index', icon: ShoppingBag },
  { label: 'Profile', route: 'profile.edit', icon: User },
];
</script>

<template>
  <AppLayout>
    <!-- Account area: sidebar + content (below public header) -->
    <section class="border-t border-editorial-ink/10 bg-editorial-cream dark:bg-zinc-900">
      <div class="mx-auto flex max-w-7xl gap-0 lg:gap-8">
        <!-- Sidebar (desktop) -->
        <aside
          class="hidden w-56 shrink-0 border-r border-editorial-ink/10 bg-white/80 dark:bg-zinc-800/50 lg:block"
          aria-label="Account navigation"
        >
          <nav class="sticky top-20 flex flex-col gap-0.5 p-4">
            <Link
              v-for="item in navItems"
              :key="item.route"
              :href="route(item.route)"
              class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
              :class="(route().current(item.route) || (item.route === 'account.orders.index' && route().current('account.orders.show')))
                ? 'bg-editorial-coral/15 text-editorial-coral dark:bg-editorial-coral/20 dark:text-editorial-coral'
                : 'text-editorial-slate hover:bg-editorial-ink/5 dark:text-zinc-300 dark:hover:bg-zinc-700/50'"
            >
              <component :is="item.icon" class="h-5 w-5 shrink-0" stroke-width="2" />
              {{ item.label }}
            </Link>
            <div class="my-2 border-t border-editorial-ink/10 dark:border-zinc-600" />
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-editorial-slate hover:bg-red-500/10 hover:text-red-600 dark:text-zinc-400 dark:hover:bg-red-500/10 dark:hover:text-red-400"
            >
              <LogOut class="h-5 w-5 shrink-0" stroke-width="2" />
              Log out
            </Link>
            <div v-if="isAdmin" class="my-2 border-t border-editorial-ink/10 dark:border-zinc-600" />
            <Link
              v-if="isAdmin"
              :href="route('admin.dashboard')"
              class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-editorial-coral"
            >
              Admin
              <ChevronRight class="h-4 w-4" />
            </Link>
          </nav>
        </aside>

        <!-- Mobile sidebar overlay -->
        <div
          v-show="sidebarOpen"
          class="fixed inset-0 z-50 bg-black/40 lg:hidden"
          aria-hidden="true"
          @click="sidebarOpen = false"
        />
        <aside
          v-show="sidebarOpen"
          class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] border-r border-editorial-ink/10 bg-white dark:bg-zinc-900 lg:hidden"
          aria-label="Account menu"
        >
          <div class="flex h-14 items-center justify-between border-b border-editorial-ink/10 px-4">
            <span class="font-semibold text-editorial-ink dark:text-white">Account menu</span>
            <button
              type="button"
              class="rounded-lg p-2 text-editorial-slate hover:bg-editorial-ink/5"
              aria-label="Close menu"
              @click="sidebarOpen = false"
            >
              <X class="h-5 w-5" stroke-width="2" />
            </button>
          </div>
          <nav class="flex flex-col gap-0.5 p-4">
            <Link
              v-for="item in navItems"
              :key="item.route"
              :href="route(item.route)"
              class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium"
              :class="route().current(item.route) ? 'bg-editorial-coral/15 text-editorial-coral' : 'text-editorial-slate'"
              @click="sidebarOpen = false"
            >
              <component :is="item.icon" class="h-5 w-5 shrink-0" stroke-width="2" />
              {{ item.label }}
            </Link>
            <div class="my-2 border-t border-editorial-ink/10" />
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-editorial-slate hover:bg-red-500/10 hover:text-red-600"
              @click="sidebarOpen = false"
            >
              <LogOut class="h-5 w-5 shrink-0" stroke-width="2" />
              Log out
            </Link>
          </nav>
        </aside>

        <!-- Main content -->
        <div class="min-w-0 flex-1 px-4 py-8 sm:px-6 lg:px-8">
          <!-- Mobile: menu button -->
          <button
            type="button"
            class="mb-4 flex items-center gap-2 rounded-lg border border-editorial-ink/20 bg-white px-3 py-2 text-sm font-medium text-editorial-ink dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 lg:hidden"
            aria-label="Open account menu"
            @click="sidebarOpen = true"
          >
            <Menu class="h-5 w-5" stroke-width="2" />
            Menu
          </button>
          <slot />
        </div>
      </div>
    </section>
  </AppLayout>
</template>
