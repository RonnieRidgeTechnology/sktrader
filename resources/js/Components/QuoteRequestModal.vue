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
  { value: 'Sofas & seating', label: 'Sofas & seating' },
  { value: 'Living room furniture', label: 'Living room furniture' },
  { value: 'Bedroom furniture', label: 'Bedroom furniture' },
  { value: 'Dining furniture', label: 'Dining furniture' },
  { value: 'Custom / made to order', label: 'Custom / made to order' },
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

const inputClass = 'mt-2 block w-full border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] placeholder:text-[#44403c]/50 transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20';
const labelClass = 'flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]';
const errorClass = 'mt-1.5 text-sm text-red-600';
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
          class="absolute inset-0 bg-[#0f0e0d]/75 backdrop-blur-sm"
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
            class="relative flex w-full max-w-lg flex-col overflow-hidden border-2 border-[#1c1917]/15 bg-[#faf8f5] shadow-2xl"
            style="max-height: min(90vh, 640px);"
          >
            <div class="h-1 w-full bg-[#c2410c]" aria-hidden="true" />

            <div class="flex shrink-0 items-start justify-between gap-4 border-b border-[#1c1917]/10 bg-white px-4 py-4 sm:px-6 sm:py-5">
              <div class="min-w-0">
                <h2 id="quote-modal-title" class="font-editorial text-xl font-semibold tracking-tight text-[#1c1917] sm:text-2xl">
                  Enquire
                </h2>
                <p class="mt-1 text-sm text-[#44403c]">
                  Tell us what you’re looking for. We’ll respond within 24 hours.
                </p>
              </div>
              <button
                type="button"
                class="shrink-0 p-2.5 text-[#44403c] transition hover:bg-[#f5f2ed] hover:text-[#1c1917]"
                aria-label="Close"
                @click="close"
              >
                <X class="h-5 w-5" stroke-width="2" />
              </button>
            </div>

            <form class="flex min-h-0 flex-1 flex-col" @submit.prevent="submit">
              <div class="flex-1 overflow-y-auto overscroll-contain px-4 py-5 sm:px-6 sm:py-6" style="-webkit-overflow-scrolling: touch;">
                <div class="absolute -left-[9999px]" aria-hidden="true">
                  <input v-model="honeypot" type="text" name="website" tabindex="-1" autocomplete="off" />
                </div>

                <p v-if="Object.keys(form.errors).length > 0" class="mb-4 rounded border-2 border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                  Please fix the errors below and try again.
                </p>

                <div class="space-y-5">
                  <div>
                    <label for="quote-name" :class="labelClass">
                      <User class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
                      Full name <span class="text-[#c2410c]">*</span>
                    </label>
                    <input
                      id="quote-name"
                      v-model="form.name"
                      type="text"
                      required
                      placeholder="Your name"
                      :class="[inputClass, form.errors.name && 'border-red-500 focus:border-red-500 focus:ring-red-500/20']"
                    />
                    <p v-if="form.errors.name" :class="errorClass">{{ form.errors.name }}</p>
                  </div>

                  <div>
                    <label for="quote-email" :class="labelClass">
                      <Mail class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
                      Email <span class="text-[#c2410c]">*</span>
                    </label>
                    <input
                      id="quote-email"
                      v-model="form.email"
                      type="email"
                      required
                      placeholder="you@example.com"
                      :class="[inputClass, form.errors.email && 'border-red-500 focus:border-red-500 focus:ring-red-500/20']"
                    />
                    <p v-if="form.errors.email" :class="errorClass">{{ form.errors.email }}</p>
                  </div>

                  <div>
                    <label for="quote-whatsapp" :class="labelClass">
                      <Phone class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
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
                      <Package class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
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
                      <MessageSquare class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
                      Your message <span class="text-[#c2410c]">*</span>
                    </label>
                    <textarea
                      id="quote-message"
                      v-model="form.message"
                      rows="4"
                      required
                      placeholder="Tell us about the furniture you’re looking for or any questions..."
                      :class="[inputClass, 'resize-none', form.errors.message && 'border-red-500 focus:border-red-500 focus:ring-red-500/20']"
                    />
                    <p v-if="form.errors.message" :class="errorClass">{{ form.errors.message }}</p>
                  </div>
                </div>
              </div>

              <div class="shrink-0 border-t border-[#1c1917]/10 bg-white px-4 py-4 sm:px-6">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                  <button
                    type="button"
                    class="border-2 border-[#1c1917]/20 bg-white px-5 py-3 text-sm font-semibold uppercase tracking-wider text-[#1c1917] transition hover:bg-[#f5f2ed]"
                    @click="close"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-2 border-2 border-[#c2410c] bg-[#c2410c] px-6 py-3.5 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-[#a83609] hover:border-[#a83609] focus:outline-none focus:ring-2 focus:ring-[#c2410c] focus:ring-offset-2 disabled:opacity-70"
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
