<script setup>
import { ref, computed, onMounted, provide } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import QuoteRequestModal from '@/Components/QuoteRequestModal.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import {
  Building2,
  HelpCircle,
  Mail,
  Shield,
  FileCheck,
  FileText,
  Instagram,
  Facebook,
  Linkedin,
  Twitter,
  ShoppingCart,
  Search,
  Sparkles,
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
  { label: 'All Products', route: 'products', icon: Sparkles },
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
const searchOpen = ref(false);
const searchQuery = ref('');

function openSearch() {
  searchOpen.value = true;
}
function closeSearch() {
  searchOpen.value = false;
  searchQuery.value = '';
}

const newsletterForm = useForm({ email: '' });
const newsletterSuccess = computed(() => usePage().props.flash?.newsletter_success ?? null);

function submitNewsletter() {
  newsletterForm.post(route('newsletter.subscribe'), { preserveScroll: true });
}

// Nav should show ONLY main categories (top-level).
const navCategories = computed(() => usePage().props.navCategories || []);
const categoryNavItems = computed(() => {
  const list = navCategories.value;
  const parents = list.filter((c) => !c.parent_id);
  return parents.map((p) => ({ label: p.name, categorySlug: p.slug }));
});

// Nav: Home, About, each category as its own link, Contact
const navLinks = computed(() => {
  const fixed = [{ label: 'Home', route: 'home' }];
  const categoryLinks = categoryNavItems.value.map((item) => ({
    label: item.label,
    route: 'products',
    categorySlug: item.categorySlug,
  }));
  // Keep top nav concise: if many categories exist, we still show them but styling is compact.
  return [...fixed, ...categoryLinks, { label: 'About', route: 'about' }, { label: 'Contact', route: 'contact' }];
});

const currentCategorySlug = computed(() => usePage().props.filterCategory ?? null);

function isProductsActive() {
  return route().current() === 'products' || route().current() === 'category.show' || route().current() === 'products.show';
}
function isCategoryActive(slug) {
  return (route().current() === 'products' || route().current() === 'category.show') && currentCategorySlug.value === slug;
}
</script>

<template>
  <div class="flex min-h-[100dvh] min-h-screen w-full min-w-0 flex-col overflow-x-hidden bg-luxe-obsidian text-luxe-pearl font-sans antialiased selection:bg-luxe-gold/30">
    <!-- Sticky Header - Luxe -->
    <header
      class="sticky top-0 z-50 w-full border-b border-white/10 bg-black/40 pt-[env(safe-area-inset-top,0px)] backdrop-blur-2xl"
    >
      <div class="luxe-container flex h-14 items-center justify-between gap-2 sm:h-16 sm:gap-3">
        <!-- Logo -->
        <Link :href="route('home')" class="group flex min-w-0 shrink items-center gap-3" aria-label="SK Traders – Home">
          <img
            :src="zuaaz.header_logo_url || '/images/logo.png'"
            alt="SK Traders"
            class="h-8 w-auto max-w-[7.5rem] object-contain opacity-95 transition group-hover:opacity-100 xs:max-w-none xs:h-9"
            width="130"
            height="40"
          />
          <div class="hidden min-w-0 xs:block">
            <p class="luxe-kicker leading-none">SK Traders</p>
            <p class="mt-1 truncate text-xs text-luxe-mist/80">Watches · Perfumes · Serums</p>
          </div>
        </Link>

        <!-- Desktop Nav -->
        <nav class="hidden flex-1 items-center justify-center gap-1.5 lg:flex">
          <template v-for="(link, index) in navLinks" :key="link.categorySlug ? link.categorySlug : link.route + '-' + index">
            <Link
              v-if="link.categorySlug"
              :href="route('category.show', { slug: link.categorySlug })"
              class="rounded-2xl px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition"
              :class="isCategoryActive(link.categorySlug) ? 'bg-white/10 text-luxe-pearl' : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else-if="route().current(link.route)"
              :href="route(link.route)"
              class="rounded-2xl bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-luxe-pearl"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else
              :href="route(link.route)"
              class="rounded-2xl px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist transition hover:bg-white/5 hover:text-luxe-pearl"
            >
              {{ link.label }}
            </Link>
          </template>
        </nav>

        <div class="flex items-center gap-2">
          <button type="button" class="luxe-btn-icon" aria-label="Search" @click="openSearch">
            <Search class="h-5 w-5" stroke-width="2" />
          </button>
          <button type="button" class="luxe-btn-icon relative" :aria-label="`Cart${cartCount ? ` (${cartCount} items)` : ''}`" @click="openCartDrawer">
            <ShoppingCart class="h-5 w-5" stroke-width="2" />
            <span v-if="cartCount > 0" class="absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-luxe-gold px-1.5 text-[10px] font-bold text-black">
              {{ cartCount > 99 ? '99+' : cartCount }}
            </span>
          </button>
          <Link
            v-if="authUser"
            :href="route('account.dashboard')"
            class="hidden lg:inline-flex luxe-btn luxe-btn-ghost"
          >
            My Account
          </Link>
          <Link
            v-else
            :href="route('login')"
            class="hidden lg:inline-flex luxe-btn luxe-btn-ghost"
          >
            Sign In
          </Link>
          <button
            type="button"
            class="hidden lg:inline-flex luxe-btn luxe-btn-primary"
            @click="openQuoteModal()"
          >
            Enquire
          </button>

          <!-- Mobile menu button -->
          <button
            type="button"
            class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl hover:bg-white/10 lg:hidden"
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
        class="max-h-[min(70dvh,calc(100dvh-3.5rem))] overflow-y-auto overscroll-contain border-t border-white/10 bg-black/50 pb-[max(1rem,env(safe-area-inset-bottom))] backdrop-blur-2xl lg:hidden"
        aria-hidden="!navOpen"
      >
        <nav class="flex flex-col gap-2 px-3 py-3 sm:px-4 sm:py-4">
          <template v-for="(link, index) in navLinks" :key="link.categorySlug ? link.categorySlug : link.route + '-' + index">
            <Link
              v-if="link.categorySlug"
              :href="route('category.show', { slug: link.categorySlug })"
              class="min-h-[48px] rounded-2xl px-4 py-3 text-xs font-semibold uppercase tracking-[0.25em]"
              :class="isCategoryActive(link.categorySlug) ? 'bg-white/10 text-luxe-pearl' : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
              @click="navOpen = false"
            >
              {{ link.label }}
            </Link>
            <Link
              v-else
              :href="route(link.route)"
              class="min-h-[48px] rounded-2xl px-4 py-3 text-xs font-semibold uppercase tracking-[0.25em]"
              :class="route().current(link.route) ? 'bg-white/10 text-luxe-pearl' : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
              @click="navOpen = false"
            >
              {{ link.label }}
            </Link>
          </template>
          <Link
            v-if="authUser"
            :href="route('account.dashboard')"
            class="mt-2 luxe-btn luxe-btn-ghost w-full"
            @click="navOpen = false"
          >
            My Account
          </Link>
          <Link
            v-else
            :href="route('login')"
            class="mt-2 luxe-btn luxe-btn-ghost w-full"
            @click="navOpen = false"
          >
            Sign In
          </Link>
          <button
            type="button"
            class="mt-2 luxe-btn luxe-btn-primary w-full"
            @click="navOpen = false; openQuoteModal()"
          >
            Enquire
          </button>
        </nav>
      </div>
    </header>

    <!-- Search overlay (UI-only for now) -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="searchOpen" class="fixed inset-0 z-[120] bg-black/70 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Search" @click.self="closeSearch">
          <div class="luxe-container pt-[max(5rem,env(safe-area-inset-top,0px)+3rem)] pb-[env(safe-area-inset-bottom,0px)]">
            <div class="luxe-surface-strong rounded-2xl p-4 sm:rounded-3xl sm:p-8">
              <div class="flex items-center justify-between gap-3">
                <p class="luxe-kicker">Search</p>
                <button type="button" class="luxe-btn-icon" aria-label="Close search" @click="closeSearch">✕</button>
              </div>
              <div class="mt-4">
                <input v-model="searchQuery" type="search" class="luxe-input" placeholder="Search products (coming next)…" />
                <p class="mt-3 text-sm text-luxe-mist/80">
                  This is a premium UI shell. In the next pass we can wire it to backend search.
                </p>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <main class="min-w-0 flex-1 bg-luxe-obsidian pb-[max(5.5rem,env(safe-area-inset-bottom,0px)+4rem)] text-luxe-pearl lg:pb-0">
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

    <!-- Footer – Luxe -->
    <footer class="relative overflow-hidden border-t border-white/10 bg-black/60">
      <div class="absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container py-10 pb-[max(2.5rem,env(safe-area-inset-bottom,0px)+1.5rem)] sm:py-16 sm:pb-16">
        <div class="grid gap-10 sm:grid-cols-2 sm:gap-12 lg:grid-cols-12 lg:gap-x-12 lg:gap-y-14">
          <!-- Brand + Social -->
          <div class="min-w-0 lg:col-span-4">
            <Link :href="route('home')" class="inline-block transition-opacity hover:opacity-90">
              <img :src="zuaaz.footer_logo_url || '/images/logo.png'" alt="SK Traders" class="h-12 w-auto opacity-90" width="140" height="48" />
            </Link>
            <p class="mt-4 max-w-xs text-base leading-relaxed text-luxe-pearl/90">
              {{ zuaaz.tagline }}
            </p>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-luxe-mist/80 sm:max-w-xs">
              Watches · Perfumes · Skincare serums
            </p>
            <div v-if="hasSocialLinks" class="mt-6">
              <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-luxe-mist/80">Social</h3>
              <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-2" role="list">
                <li v-if="zuaaz.contact?.instagram">
                  <a :href="zuaaz.contact.instagram" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-luxe-pearl/90 transition hover:text-luxe-gold">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl"><Instagram class="h-4 w-4" stroke-width="2" /></span><span>Instagram</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.facebook">
                  <a :href="zuaaz.contact.facebook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-luxe-pearl/90 transition hover:text-luxe-gold">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl"><Facebook class="h-4 w-4" stroke-width="2" /></span><span>Facebook</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.linkedin">
                  <a :href="zuaaz.contact.linkedin" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-luxe-pearl/90 transition hover:text-luxe-gold">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl"><Linkedin class="h-4 w-4" stroke-width="2" /></span><span>LinkedIn</span>
                  </a>
                </li>
                <li v-if="zuaaz.contact?.twitter">
                  <a :href="zuaaz.contact.twitter" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base text-luxe-pearl/90 transition hover:text-luxe-gold">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl"><Twitter class="h-4 w-4" stroke-width="2" /></span><span>X (Twitter)</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- Explore -->
          <div class="lg:col-span-2">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-luxe-mist/80">Explore</h3>
            <ul class="mt-4 space-y-2.5" role="list">
              <li v-for="item in footerExploreLinks" :key="item.route">
                <Link :href="route(item.route)" class="inline-flex items-center gap-2.5 text-base text-luxe-pearl/90 transition hover:text-luxe-gold hover:underline">
                  <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl">
                    <component :is="item.icon" class="h-4 w-4" stroke-width="2" />
                  </span>
                  {{ item.label }}
                </Link>
              </li>
            </ul>
          </div>
          <!-- Legal + Newsletter -->
          <div class="lg:col-span-6">
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-luxe-mist/80">Legal &amp; Policies</h3>
            <ul class="mt-4 space-y-2.5" role="list">
              <li v-for="item in footerLegalLinks" :key="item.route">
                <Link :href="route(item.route)" class="inline-flex items-center gap-2.5 text-base text-luxe-pearl/90 transition hover:text-luxe-gold hover:underline">
                  <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl">
                    <component :is="item.icon" class="h-4 w-4" stroke-width="2" />
                  </span>
                  {{ item.label }}
                </Link>
              </li>
            </ul>
            <div class="mt-8 luxe-surface rounded-3xl p-6">
              <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-luxe-mist/80">Newsletter</h3>
              <p class="mt-2 text-sm leading-relaxed text-luxe-pearl/80">Get early access to drops, limited releases, and offers.</p>
              <form @submit.prevent="submitNewsletter" class="mt-4 flex min-w-0 flex-col gap-3 sm:flex-row sm:items-stretch">
                <div class="relative min-w-0 flex-1">
                  <input v-model="newsletterForm.email" type="email" required placeholder="Enter your email" class="luxe-input" />
                </div>
                <button type="submit" :disabled="newsletterForm.processing" class="luxe-btn luxe-btn-primary">
                  {{ newsletterForm.processing ? '…' : 'Subscribe' }}
                </button>
              </form>
              <p v-if="newsletterForm.errors.email" class="mt-2 text-sm text-red-300">{{ newsletterForm.errors.email }}</p>
              <p v-if="newsletterSuccess" class="mt-2 text-sm font-medium text-emerald-300">{{ newsletterSuccess }}</p>
            </div>
          </div>
        </div>
        <div class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row">
          <p class="text-center text-sm text-luxe-mist/80 sm:text-left">
            {{ zuaaz.footer_copyright || `© ${new Date().getFullYear()} ${zuaaz.name}. All rights reserved.` }}
          </p>
          <div class="flex flex-wrap justify-center gap-6 text-sm">
            <Link :href="route('privacy-policy')" class="inline-flex items-center gap-2 text-luxe-mist transition hover:text-luxe-gold">
              <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl">
                <Shield class="h-3.5 w-3.5" stroke-width="2" />
              </span>
              Privacy
            </Link>
            <Link :href="route('terms-and-conditions')" class="inline-flex items-center gap-2 text-luxe-mist transition hover:text-luxe-gold">
              <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-luxe-pearl">
                <FileText class="h-3.5 w-3.5" stroke-width="2" />
              </span>
              Terms
            </Link>
          </div>
        </div>
      </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a
      v-if="zuaaz.whatsapp?.enabled !== false"
      :href="whatsappPrimary"
      target="_blank"
      rel="noopener noreferrer"
      class="fixed z-50 flex h-14 w-14 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/10 text-white shadow-luxe backdrop-blur-xl transition-all duration-200 hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-luxe-gold/70 focus:ring-offset-2 focus:ring-offset-black"
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
