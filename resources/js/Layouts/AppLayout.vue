<script setup>
import { ref, computed, onMounted, provide } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import QuoteRequestModal from '@/Components/QuoteRequestModal.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import {
  Building2,
  Briefcase,
  Package,
  Award,
  ListOrdered,
  HelpCircle,
  Mail,
  Factory,
  Shield,
  FileCheck,
  FileText,
  MapPin,
  Instagram,
  Facebook,
  Linkedin,
  Twitter,
  ShoppingCart,
} from 'lucide-vue-next';

const showQuoteModal = ref(false);
const quoteModalProductInterest = ref('');
function openQuoteModal(data = {}) {
  quoteModalProductInterest.value = data.productInterest || '';
  showQuoteModal.value = true;
}
provide('openQuoteModal', openQuoteModal);

const showCartDrawer = ref(false);
function openCartDrawer() {
  showCartDrawer.value = true;
}
provide('openCartDrawer', openCartDrawer);

const THEME_KEY = 'theme';

const footerExploreLinks = [
  { label: 'About', route: 'about', icon: Building2 },
  { label: 'Collections', route: 'services', icon: Briefcase },
  { label: 'Furniture', route: 'products', icon: Package },
  { label: 'Why Choose Us', route: 'why-choose-us', icon: Award },
  { label: 'How It Works', route: 'how-it-works', icon: ListOrdered },
  { label: 'FAQ', route: 'faq', icon: HelpCircle },
  { label: 'Contact', route: 'contact', icon: Mail },
];

const footerLegalLinks = [
  { label: 'Privacy Policy', route: 'privacy-policy', icon: Shield },
  { label: 'Delivery & Returns', route: 'manufacturing-policy', icon: FileCheck },
  { label: 'Terms & Conditions', route: 'terms-and-conditions', icon: FileText },
];

/** Shown in public footer — payment options (icons in /public/images/payments/). */
const footerPaymentMethods = [
  { label: 'PayPal', src: '/images/payments/paypal.svg', alt: 'PayPal' },
  { label: 'Visa', src: '/images/payments/visa.svg', alt: 'Visa' },
  { label: 'Card', src: '/images/payments/card.svg', alt: 'Card payment' },
  { label: 'Master Card', src: '/images/payments/mastercard.svg', alt: 'Mastercard' },
  { label: 'Mobile wallet', src: '/images/payments/mobile-wallet.svg', alt: 'Mobile wallet' },
];

const isDark = ref(false);

function applyTheme(dark) {
  isDark.value = !!dark;
  if (dark) {
    document.documentElement.classList.add('dark');
    localStorage.setItem(THEME_KEY, 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem(THEME_KEY, 'light');
  }
}

function toggleTheme() {
  applyTheme(!isDark.value);
}

onMounted(() => {
  isDark.value = document.documentElement.classList.contains('dark');
});

const page = usePage();
const zuaaz = computed(() => page.props.zuaaz || {});
const authUser = computed(() => page.props.auth?.user ?? null);
const cartCount = computed(() => page.props.cartCount ?? 0);
const hasSocialLinks = computed(() => {
  const c = zuaaz.value.contact || {};
  return !!(c.instagram || c.facebook || c.linkedin || c.twitter);
});
const whatsappPrimary = computed(() => {
  const num = (zuaaz.value.whatsapp?.primary || '+260970000000').replace(/\D/g, '');
  return `https://wa.me/${num}`;
});

const navOpen = ref(false);

const newsletterForm = useForm({ email: '' });
const newsletterSuccess = computed(() => usePage().props.flash?.newsletter_success ?? null);

function submitNewsletter() {
  newsletterForm.post(route('newsletter.subscribe'), { preserveScroll: true });
}

// All categories flattened for nav (parents then their children, in order)
const navCategories = computed(() => usePage().props.navCategories || []);
const categoryNavItems = computed(() => {
  const list = navCategories.value;
  const parents = list.filter((c) => !c.parent_id);
  const childrenByParent = {};
  list.forEach((c) => {
    if (c.parent_id) {
      if (!childrenByParent[c.parent_id]) childrenByParent[c.parent_id] = [];
      childrenByParent[c.parent_id].push(c);
    }
  });
  const flat = [];
  parents.forEach((p) => {
    flat.push({ label: p.name, categorySlug: p.slug });
    (childrenByParent[p.id] || []).forEach((child) => {
      flat.push({ label: child.name, categorySlug: child.slug });
    });
  });
  return flat;
});

// Nav: Home, About, each category as its own link, Contact
const navLinks = computed(() => {
  const fixed = [
    { label: 'Home', route: 'home' },
    { label: 'About', route: 'about' },
  ];
  const categoryLinks = categoryNavItems.value.map((item) => ({
    label: item.label,
    route: 'products',
    categorySlug: item.categorySlug,
  }));
  return [...fixed, ...categoryLinks, { label: 'Contact', route: 'contact' }];
});

const currentCategorySlug = computed(() => usePage().props.filterCategory ?? null);

function isProductsActive() {
  return route().current() === 'products' || route().current() === 'products.show';
}
function isCategoryActive(slug) {
  return route().current() === 'products' && currentCategorySlug.value === slug;
}
</script>

<template>
  <div class="min-h-screen min-w-0 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 font-sans antialiased selection:bg-amber-200/50 dark:selection:bg-amber-500/30">
    <!-- Sticky Header - Premium -->
    <header
      class="sticky top-0 z-50 w-full border-b border-zinc-200/80 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md shadow-sm"
    >
      <div class="mx-auto flex h-14 min-h-0 w-full max-w-7xl items-center justify-between gap-2 px-3 sm:h-16 sm:gap-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <Link :href="route('home')" class="flex min-w-0 shrink items-center gap-2 transition-opacity hover:opacity-90 sm:gap-3" aria-label="Atlantic Garden Furniture – Home">
          <img
            :src="zuaaz.header_logo_url || '/images/logo.png'"
            alt="Atlantic Garden Furniture – Sofas & furniture store Zambia"
            class="h-7 w-auto max-w-[120px] object-contain dark:invert-0 sm:h-9 sm:max-w-none invert"
            width="120"
            height="36"
          />
          <span class="sr-only">{{ zuaaz.name || 'Atlantic Garden Furniture' }}</span>
        </Link>

        <!-- Desktop Nav: Home, About, each category, Contact -->
        <nav class="hidden flex-1 items-center justify-center gap-1 md:flex lg:gap-2">
          <template v-for="(link, index) in navLinks" :key="link.categorySlug ? link.categorySlug : link.route + '-' + index">
            <Link
              v-if="link.categorySlug"
              :href="route('products', { category: link.categorySlug })"
              class="rounded-none px-2.5 py-2.5 text-sm font-medium uppercase tracking-wider transition-all duration-200 hover:scale-[1.02] hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-white"
              :class="isCategoryActive(link.categorySlug) ? 'font-semibold text-zinc-900 dark:text-white ring-2 ring-amber-400/30 dark:ring-amber-500/20' : 'text-zinc-600 dark:text-zinc-400'"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else-if="route().current(link.route)"
              :href="route(link.route)"
              class="rounded-none px-2.5 py-2.5 text-sm font-semibold uppercase tracking-wider text-zinc-900 dark:text-white ring-2 ring-amber-400/30 dark:ring-amber-500/20"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else
              :href="route(link.route)"
              class="rounded-none px-2.5 py-2.5 text-sm font-medium uppercase tracking-wider text-zinc-600 dark:text-zinc-400 transition-all duration-200 hover:scale-[1.02] hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-white"
            >
              {{ link.label }}
            </Link>
          </template>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
          <!-- Theme toggle -->
          <button
            type="button"
            @click="toggleTheme"
            :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
            class="rounded-none p-2.5 text-zinc-600 transition-all duration-200 hover:scale-105 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white"
          >
            <!-- Sun icon (show in dark mode = click to go light) -->
            <svg v-if="isDark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <!-- Moon icon (show in light mode = click to go dark) -->
            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>
          <button
            type="button"
            class="relative flex min-h-[44px] min-w-[44px] shrink-0 items-center justify-center rounded-none p-2.5 text-zinc-600 transition-all duration-200 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white"
            :aria-label="`Cart${cartCount ? ` (${cartCount} items)` : ''}`"
            @click="openCartDrawer"
          >
            <ShoppingCart class="h-5 w-5" stroke-width="2" />
            <span
              v-if="cartCount > 0"
              class="absolute -right-0.5 -top-0.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-editorial-coral px-1.5 text-[10px] font-bold text-white"
            >
              {{ cartCount > 99 ? '99+' : cartCount }}
            </span>
          </button>
          <Link
            v-if="authUser"
            :href="route('account.dashboard')"
            class="hidden shrink-0 rounded-none border-2 border-editorial-ink/20 bg-white px-4 py-2.5 text-sm font-semibold text-editorial-ink transition-all duration-200 hover:border-editorial-coral hover:bg-editorial-paper dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:border-editorial-coral dark:hover:bg-zinc-700 sm:inline-flex"
          >
            My Account
          </Link>
          <Link
            v-else
            :href="route('login')"
            class="hidden shrink-0 rounded-none border-2 border-editorial-ink/20 bg-white px-4 py-2.5 text-sm font-semibold text-editorial-ink transition-all duration-200 hover:border-editorial-coral hover:bg-editorial-paper dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:border-editorial-coral dark:hover:bg-zinc-700 sm:inline-flex"
          >
            Sign In
          </Link>
          <button
            type="button"
            class="hidden shrink-0 rounded-none border-2 border-zinc-900 bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-premium transition-all duration-200 hover:scale-[1.02] hover:bg-zinc-800 hover:border-zinc-800 dark:border-amber-500 dark:bg-amber-500 dark:text-zinc-900 dark:hover:bg-amber-400 dark:hover:border-amber-400 sm:inline-flex"
            @click="openQuoteModal()"
          >
            Enquire
          </button>

          <!-- Mobile menu button (min 44px touch target) -->
          <button
            type="button"
            class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-none p-2 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800 md:hidden"
            aria-label="Toggle menu"
            aria-expanded="navOpen"
            @click="navOpen = !navOpen"
          >
            <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!navOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu: scrollable, full width, touch-friendly -->
      <div
        v-show="navOpen"
        class="max-h-[calc(100vh-3.5rem)] overflow-y-auto overscroll-contain border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 md:hidden"
        aria-hidden="!navOpen"
      >
        <nav class="flex flex-col gap-0.5 px-3 py-3 sm:px-4">
          <template v-for="(link, index) in navLinks" :key="link.categorySlug ? link.categorySlug : link.route + '-' + index">
            <Link
              v-if="link.categorySlug"
              :href="route('products', { category: link.categorySlug })"
              class="min-h-[44px] rounded-none px-3 py-3 text-sm font-medium uppercase tracking-wider"
              :class="isCategoryActive(link.categorySlug) ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400'"
              @click="navOpen = false"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else
              :href="route(link.route)"
              class="min-h-[44px] rounded-none px-3 py-3 text-sm font-medium uppercase tracking-wider"
              :class="route().current(link.route) ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400'"
              @click="navOpen = false"
            >
              {{ link.label }}
            </Link>
          </template>
          <Link
            v-if="authUser"
            :href="route('account.dashboard')"
            class="mt-2 flex min-h-[48px] w-full items-center justify-center rounded-none border-2 border-editorial-ink/20 bg-white px-4 py-3 text-center text-sm font-semibold text-editorial-ink dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
            @click="navOpen = false"
          >
            My Account
          </Link>
          <Link
            v-else
            :href="route('login')"
            class="mt-2 flex min-h-[48px] w-full items-center justify-center rounded-none border-2 border-editorial-ink/20 bg-white px-4 py-3 text-center text-sm font-semibold text-editorial-ink dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
            @click="navOpen = false"
          >
            Sign In
          </Link>
          <button
            type="button"
            class="mt-2 flex min-h-[48px] w-full items-center justify-center rounded-none border-2 border-zinc-900 bg-zinc-900 px-4 py-3 text-center text-sm font-semibold text-white dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900"
            @click="navOpen = false; openQuoteModal()"
          >
            Enquire
          </button>
        </nav>
      </div>
    </header>

    <main class="min-w-0 flex-1">
      <slot />
    </main>

    <!-- Enquiry modal -->
    <QuoteRequestModal
      :show="showQuoteModal"
      :initial-product-interest="quoteModalProductInterest"
      @close="showQuoteModal = false"
    />

    <!-- Cart drawer (slides from right on Add to cart / cart icon) -->
    <CartDrawer :open="showCartDrawer" @close="showCartDrawer = false" />

    <!-- Footer – Concept styling (dark, cream text, coral accents) -->
    <footer class="relative overflow-hidden border-t border-[#1c1917] bg-[#1c1917]">
      <div class="absolute left-0 right-0 top-0 h-px bg-white/10" aria-hidden="true" />
      <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
        <div class="grid gap-10 sm:grid-cols-2 sm:gap-12 lg:grid-cols-12 lg:gap-x-12 lg:gap-y-14">
          <!-- Brand + Social -->
          <div class="min-w-0 lg:col-span-4">
            <Link :href="route('home')" class="inline-block transition-opacity hover:opacity-90">
              <img :src="zuaaz.footer_logo_url || '/images/logo.png'" alt="Atlantic Garden Furniture – Sofas & furniture Zambia" class="h-[80px] w-auto invert" width="110" height="80" />
            </Link>
            <p class="mt-4 max-w-xs text-base leading-relaxed text-[#f5f2ed]/90">
              {{ zuaaz.tagline }}
            </p>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60 sm:max-w-xs">
              Sofas & furniture · Lusaka showroom · Nationwide delivery · Zambia
            </p>
            <div v-if="hasSocialLinks" class="mt-6">
              <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">Social Media</h3>
              <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-2" role="list">
                <li v-if="zuaaz.contact?.instagram">
                  <a :href="zuaaz.contact.instagram" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c]">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white"><Instagram class="h-4 w-4" stroke-width="2" /></span><span>Instagram</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.facebook">
                  <a :href="zuaaz.contact.facebook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c]">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white"><Facebook class="h-4 w-4" stroke-width="2" /></span><span>Facebook</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.linkedin">
                  <a :href="zuaaz.contact.linkedin" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c]">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white"><Linkedin class="h-4 w-4" stroke-width="2" /></span><span>LinkedIn</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.twitter">
                  <a :href="zuaaz.contact.twitter" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c]">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white"><Twitter class="h-4 w-4" stroke-width="2" /></span><span>X (Twitter)</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- Explore -->
          <div class="lg:col-span-2">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">Explore</h3>
            <ul class="mt-4 space-y-2.5" role="list">
              <li v-for="item in footerExploreLinks" :key="item.route">
                <Link :href="route(item.route)" class="inline-flex items-center gap-2.5 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c] hover:underline">
                  <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                    <component :is="item.icon" class="h-4 w-4" stroke-width="2" />
                  </span>
                  {{ item.label }}
                </Link>
              </li>
            </ul>
          </div>
          <!-- Visit & Delivery -->
          <div class="lg:col-span-2">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">Visit &amp; Delivery</h3>
            <div class="mt-4 space-y-4">
              <div class="flex gap-3">
                <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                  <MapPin class="h-4 w-4" stroke-width="2" />
                </span>
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-[#f5f2ed]/60">Showroom</p>
                  <p class="mt-1 text-base leading-snug text-[#f5f2ed]/90">{{ zuaaz.address?.office || 'Lusaka, Zambia' }}</p>
                </div>
              </div>
              <div v-if="zuaaz.address?.manufacturing" class="flex gap-3">
                <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                  <Factory class="h-4 w-4" stroke-width="2" />
                </span>
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-[#f5f2ed]/60">Delivery</p>
                  <p class="mt-1 text-base leading-snug text-[#f5f2ed]/90">{{ zuaaz.address.manufacturing }}</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Legal + Newsletter -->
          <div class="lg:col-span-4">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">Legal &amp; Policies</h3>
            <ul class="mt-4 space-y-2.5" role="list">
              <li v-for="item in footerLegalLinks" :key="item.route">
                <Link :href="route(item.route)" class="inline-flex items-center gap-2.5 text-base text-[#f5f2ed]/90 transition hover:text-[#c2410c] hover:underline">
                  <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                    <component :is="item.icon" class="h-4 w-4" stroke-width="2" />
                  </span>
                  {{ item.label }}
                </Link>
              </li>
            </ul>
            <div class="mt-8 border-2 border-white/20 bg-white/5 p-5">
              <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">Newsletter</h3>
              <p class="mt-2 text-sm leading-relaxed text-[#f5f2ed]/80">Get updates on new sofas and furniture collections.</p>
              <form @submit.prevent="submitNewsletter" class="mt-4 flex min-w-0 flex-col gap-3 sm:flex-row sm:items-stretch">
                <div class="relative min-w-0 flex-1">
                  <input
                    v-model="newsletterForm.email"
                    type="email"
                    required
                    placeholder="Enter your email"
                    class="w-full border-2 border-white/20 bg-[#0f0e0d] py-3 pl-4 pr-4 text-sm text-[#f5f2ed] transition placeholder-[#f5f2ed]/50 focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/30"
                  />
                </div>
                <button
                  type="submit"
                  :disabled="newsletterForm.processing"
                  class="shrink-0 border-2 border-[#c2410c] bg-[#c2410c] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#a83609] focus:outline-none focus:ring-2 focus:ring-[#c2410c] focus:ring-offset-2 focus:ring-offset-[#1c1917] disabled:opacity-70"
                >
                  {{ newsletterForm.processing ? '…' : 'Subscribe' }}
                </button>
              </form>
              <p v-if="newsletterForm.errors.email" class="mt-2 text-sm text-red-400">{{ newsletterForm.errors.email }}</p>
              <p v-if="newsletterSuccess" class="mt-2 text-sm font-medium text-emerald-400">{{ newsletterSuccess }}</p>
            </div>
          </div>
        </div>
        <div class="mt-12 border-t border-white/10 pt-8">
          <div class="text-center sm:text-left">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">We accept</h3>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-[#f5f2ed]/70">
              We receive payments via PayPal, Visa, card, Mastercard, and mobile wallet — secure checkout on every order.
            </p>
            <ul class="mt-5 flex flex-wrap justify-center gap-3 sm:justify-start" role="list">
              <li v-for="m in footerPaymentMethods" :key="m.label">
                <figure class="flex w-[5.75rem] flex-col items-center gap-2 rounded-lg border border-white/20 bg-white px-2.5 py-3 shadow-sm">
                  <img
                    :src="m.src"
                    :alt="m.alt"
                    width="88"
                    height="44"
                    class="h-11 w-full max-h-11 object-contain object-center"
                    loading="lazy"
                    decoding="async"
                  />
                  <figcaption class="text-center text-[10px] font-semibold uppercase leading-tight tracking-wide text-zinc-800">
                    {{ m.label }}
                  </figcaption>
                </figure>
              </li>
            </ul>
          </div>
        </div>
        <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row">
          <p class="text-center text-sm text-[#f5f2ed]/50 sm:text-left">
            {{ zuaaz.footer_copyright || `© ${new Date().getFullYear()} ${zuaaz.name}. All rights reserved.` }}
          </p>
          <div class="flex flex-wrap justify-center gap-6 text-sm">
            <Link :href="route('privacy-policy')" class="inline-flex items-center gap-2 text-[#f5f2ed]/70 transition hover:text-[#c2410c]">
              <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                <Shield class="h-3.5 w-3.5" stroke-width="2" />
              </span>
              Privacy
            </Link>
            <Link :href="route('terms-and-conditions')" class="inline-flex items-center gap-2 text-[#f5f2ed]/70 transition hover:text-[#c2410c]">
              <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white">
                <FileText class="h-3.5 w-3.5" stroke-width="2" />
              </span>
              Terms
            </Link>
          </div>
        </div>
      </div>
    </footer>

    <!-- Floating WhatsApp - Premium (safe area for notched phones) -->
    <a
      v-if="zuaaz.whatsapp?.enabled !== false"
      :href="whatsappPrimary"
      target="_blank"
      rel="noopener noreferrer"
      class="fixed z-50 flex h-14 w-14 shrink-0 items-center justify-center rounded-none bg-[#128C7E] text-white shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#128C7E] focus:ring-offset-2 sm:hover:scale-110"
      style="bottom: max(1rem, env(safe-area-inset-bottom, 1rem)); right: max(1rem, env(safe-area-inset-right, 1rem));"
      aria-label="Chat on WhatsApp"
    >
      <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
    </a>
  </div>
</template>

<style scoped>
/* Products sub-menu: modern scrollbar */
.products-submenu-list {
  scrollbar-gutter: stable;
  scrollbar-width: thin;
  scrollbar-color: rgb(212 212 212) rgb(243 244 246);
}
.dark .products-submenu-list {
  scrollbar-color: rgb(63 63 70) rgb(39 39 42);
}
.products-submenu-list::-webkit-scrollbar {
  width: 6px;
}
.products-submenu-list::-webkit-scrollbar-track {
  background: rgb(243 244 246);
  border-radius: 3px;
}
.products-submenu-list::-webkit-scrollbar-thumb {
  background: rgb(212 212 212);
  border-radius: 3px;
}
.products-submenu-list::-webkit-scrollbar-thumb:hover {
  background: rgb(163 163 163);
}
.dark .products-submenu-list::-webkit-scrollbar-track {
  background: rgb(39 39 42);
}
.dark .products-submenu-list::-webkit-scrollbar-thumb {
  background: rgb(63 63 70);
}
.dark .products-submenu-list::-webkit-scrollbar-thumb:hover {
  background: rgb(82 82 91);
}
</style>
