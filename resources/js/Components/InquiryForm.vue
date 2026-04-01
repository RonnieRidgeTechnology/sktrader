<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { User, Mail, Phone, Package, MessageSquare } from 'lucide-vue-next';

const props = defineProps({ initialProductInterest: { type: String, default: '' } });
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

watch(() => props.initialProductInterest, (v) => { form.product_interest = v || ''; }, { immediate: false });

function submit() {
  form.website = honeypot.value;
  form.post(route('inquiry.store'), {
    onSuccess: () => form.reset(),
  });
}
</script>

<template>
  <form id="quote" class="inquiry-form min-w-0 space-y-6" @submit.prevent="submit">
    <div class="absolute -left-[9999px] top-0" aria-hidden="true">
      <label for="website">Website</label>
      <input id="website" v-model="honeypot" type="text" name="website" tabindex="-1" autocomplete="off" />
    </div>

    <p v-if="Object.keys(form.errors).length > 0" class="rounded border-2 border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
      Please fix the errors below and try again.
    </p>

    <div class="space-y-5">
      <div>
        <label for="name" class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]">
          <User class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
          Full name <span class="text-[#c2410c]">*</span>
        </label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          required
          placeholder="Your name"
          class="mt-2 block w-full border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] placeholder:text-[#44403c]/50 transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20"
          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500/20': form.errors.name }"
        />
        <p v-if="form.errors.name" class="mt-1.5 text-sm text-red-600">{{ form.errors.name }}</p>
      </div>

      <div>
        <label for="email" class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]">
          <Mail class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
          Email <span class="text-[#c2410c]">*</span>
        </label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          required
          placeholder="you@example.com"
          class="mt-2 block w-full border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] placeholder:text-[#44403c]/50 transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20"
          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500/20': form.errors.email }"
        />
        <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
      </div>

      <div>
        <label for="whatsapp" class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]">
          <Phone class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
          Phone or WhatsApp
        </label>
        <input
          id="whatsapp"
          v-model="form.whatsapp"
          type="tel"
          placeholder="e.g. +260 97 123 4567"
          class="mt-2 block w-full border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] placeholder:text-[#44403c]/50 transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20"
        />
      </div>

      <div>
        <label for="product_interest" class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]">
          <Package class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
          What are you interested in?
        </label>
        <select
          id="product_interest"
          v-model="form.product_interest"
          class="mt-2 block w-full border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20"
        >
          <option v-for="opt in interestOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
      </div>

      <div>
        <label for="message" class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-[#1c1917]">
          <MessageSquare class="h-4 w-4 text-[#c2410c]" stroke-width="2" />
          Your message <span class="text-[#c2410c]">*</span>
        </label>
        <textarea
          id="message"
          v-model="form.message"
          rows="4"
          required
          placeholder="Tell us about the furniture you’re looking for, your space, or any questions..."
          class="mt-2 block w-full resize-none border-2 border-[#1c1917]/15 bg-white px-4 py-3.5 text-[#1c1917] placeholder:text-[#44403c]/50 transition focus:border-[#c2410c] focus:outline-none focus:ring-2 focus:ring-[#c2410c]/20"
          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500/20': form.errors.message }"
        />
        <p v-if="form.errors.message" class="mt-1.5 text-sm text-red-600">{{ form.errors.message }}</p>
      </div>
    </div>

    <button
      type="submit"
      :disabled="form.processing"
      class="w-full border-2 border-[#c2410c] bg-[#c2410c] px-6 py-4 text-base font-semibold uppercase tracking-wider text-white transition hover:bg-[#a83609] hover:border-[#a83609] focus:outline-none focus:ring-2 focus:ring-[#c2410c] focus:ring-offset-2 disabled:opacity-70"
    >
      {{ form.processing ? 'Sending…' : 'Send enquiry' }}
    </button>
  </form>
</template>

<style scoped>
.inquiry-form {
  border: 2px solid rgba(28, 25, 23, 0.12);
  background: #faf8f5;
  padding: 1.5rem;
}
@media (min-width: 640px) {
  .inquiry-form {
    padding: 2rem 2.5rem;
  }
}
</style>
