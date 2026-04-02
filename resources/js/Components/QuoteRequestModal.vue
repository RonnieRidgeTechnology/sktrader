<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { User, Mail, Phone, Package, MessageSquare, X, Send } from 'lucide-vue-next';

const props = defineProps({
  show: { type: Boolean, default: false },
  initialProductInterest: { type: String, default: '' },
});

const emit = defineEmits(['close']);

const honeypot = ref('');

const interestOptions = [
  { value: '', label: 'Select an option (optional)' },
  { value: 'Watches', label: 'Watches' },
  { value: 'Perfumes', label: 'Perfumes' },
  { value: 'Skin care serums', label: 'Skin care serums' },
  { value: 'Gift set / bundle', label: 'Gift set / bundle' },
  { value: 'General enquiry', label: 'General enquiry' },
];

const form = useForm({
  name: '',
  email: '',
  whatsapp: '',
  product_interest: props.initialProductInterest || '',
  message: '',
  company_name: '',
  country: '',
  quantity: '',
  attachment: null,
  website: '',
});

watch(() => props.initialProductInterest, (v) => { form.product_interest = v || ''; });
watch(() => props.show, (v) => {
  if (v) form.product_interest = props.initialProductInterest || form.product_interest;
});

function submit() {
  form.website = honeypot.value;
  form.post(route('inquiry.store'), {
    onSuccess: () => {
      form.reset();
      emit('close');
    },
  });
}

function close() {
  emit('close');
}

function onBackdropClick(e) {
  if (e.target === e.currentTarget) close();
}

const inputClass = 'mt-2 block w-full rounded-3xl border border-white/10 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/60 shadow-luxe-sm transition focus:border-luxe-gold/50 focus:outline-none focus:ring-2 focus:ring-luxe-gold/30';
const labelClass = 'flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.28em] text-luxe-mist';
const errorClass = 'mt-1.5 text-sm text-red-400';
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-[100] flex items-end justify-center p-0 sm:items-center sm:p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="quote-modal-title"
      >
        <div
          class="absolute inset-0 bg-black/75 backdrop-blur-md"
          @click="onBackdropClick"
        />

        <Transition
          enter-active-class="duration-250 ease-out"
          enter-from-class="opacity-0 scale-[0.98]"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="duration-150 ease-in"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-[0.98]"
        >
          <div
            v-if="show"
            class="relative flex w-full max-w-lg flex-col overflow-hidden rounded-t-3xl border border-white/12 bg-luxe-obsidian shadow-luxe-lg sm:rounded-5xl"
            style="max-height: min(92dvh, calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 1rem), 640px);"
          >
            <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
            <div class="pointer-events-none absolute inset-0 opacity-[0.08] mix-blend-overlay [background-image:url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2260%22%20height%3D%2260%22%3E%3Cfilter%20id%3D%22n%22%3E%3CfeTurbulence%20type%3D%22fractalNoise%22%20baseFrequency%3D%220.8%22%20numOctaves%3D%222%22%20stitchTiles%3D%22stitch%22/%3E%3C/filter%3E%3Crect%20width%3D%2260%22%20height%3D%2260%22%20filter%3D%22url(%23n)%22%20opacity%3D%221%22/%3E%3C/svg%3E')]" aria-hidden="true" />

            <div class="relative flex shrink-0 items-start justify-between gap-4 border-b border-white/10 bg-black/35 px-4 py-4 backdrop-blur-2xl sm:px-6 sm:py-5">
              <div class="min-w-0">
                <p class="luxe-kicker">Enquiry</p>
                <h2 id="quote-modal-title" class="luxe-title mt-2 text-xl font-semibold tracking-tight sm:text-2xl">
                  Enquire
                </h2>
                <p class="mt-2 text-sm text-luxe-pearl/75">
                  Tell us what you’re looking for. We’ll respond within 24 hours.
                </p>
              </div>
              <button
                type="button"
                class="luxe-btn-icon shrink-0"
                aria-label="Close"
                @click="close"
              >
                <X class="h-5 w-5" stroke-width="2" />
              </button>
            </div>

            <form class="flex min-h-0 flex-1 flex-col" @submit.prevent="submit">
              <div class="relative flex-1 overflow-y-auto overscroll-contain px-4 py-5 sm:px-6 sm:py-6" style="-webkit-overflow-scrolling: touch;">
                <div class="absolute -left-[9999px]" aria-hidden="true">
                  <input v-model="honeypot" type="text" name="website" tabindex="-1" autocomplete="off" />
                </div>

                <p v-if="Object.keys(form.errors).length > 0" class="mb-4 rounded-3xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                  Please fix the errors below and try again.
                </p>

                <div class="space-y-5">
                  <div>
                    <label for="quote-name" :class="labelClass">
                      <User class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                      Full name <span class="text-luxe-gold">*</span>
                    </label>
                    <input
                      id="quote-name"
                      v-model="form.name"
                      type="text"
                      required
                      placeholder="Your name"
                      :class="[inputClass, form.errors.name && 'border-red-500/60 focus:border-red-500 focus:ring-red-500/30']"
                    />
                    <p v-if="form.errors.name" :class="errorClass">{{ form.errors.name }}</p>
                  </div>

                  <div>
                    <label for="quote-email" :class="labelClass">
                      <Mail class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                      Email <span class="text-luxe-gold">*</span>
                    </label>
                    <input
                      id="quote-email"
                      v-model="form.email"
                      type="email"
                      required
                      placeholder="you@example.com"
                      :class="[inputClass, form.errors.email && 'border-red-500/60 focus:border-red-500 focus:ring-red-500/30']"
                    />
                    <p v-if="form.errors.email" :class="errorClass">{{ form.errors.email }}</p>
                  </div>

                  <div>
                    <label for="quote-whatsapp" :class="labelClass">
                      <Phone class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                      Phone or WhatsApp
                    </label>
                    <input
                      id="quote-whatsapp"
                      v-model="form.whatsapp"
                      type="tel"
                      placeholder="e.g. +260 97 123 4567"
                      :class="inputClass"
                    />
                  </div>

                  <div>
                    <label for="quote-product" :class="labelClass">
                      <Package class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                      What are you interested in?
                    </label>
                    <select
                      id="quote-product"
                      v-model="form.product_interest"
                      :class="inputClass"
                    >
                      <option v-for="opt in interestOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                  </div>

                  <div>
                    <label for="quote-message" :class="labelClass">
                      <MessageSquare class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                      Your message <span class="text-luxe-gold">*</span>
                    </label>
                    <textarea
                      id="quote-message"
                      v-model="form.message"
                      rows="4"
                      required
                      placeholder="Tell us what you’re looking for (watch / perfume / serum), your budget range, and preferences (style, scent profile, or routine goals)..."
                      :class="[inputClass, 'resize-none', form.errors.message && 'border-red-500/60 focus:border-red-500 focus:ring-red-500/30']"
                    />
                    <p v-if="form.errors.message" :class="errorClass">{{ form.errors.message }}</p>
                  </div>
                </div>
              </div>

              <div class="relative shrink-0 border-t border-white/10 bg-black/35 px-4 py-4 backdrop-blur-2xl sm:px-6">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                  <button
                    type="button"
                    class="luxe-btn luxe-btn-ghost"
                    @click="close"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="luxe-btn luxe-btn-primary"
                  >
                    <Send class="h-4 w-4" stroke-width="2" />
                    {{ form.processing ? 'Sending…' : 'Send enquiry' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>
