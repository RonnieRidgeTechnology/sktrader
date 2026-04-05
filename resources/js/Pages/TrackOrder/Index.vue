<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Search, MapPin, Package, ExternalLink, RefreshCcw } from 'lucide-vue-next';

const props = defineProps({
  title: String,
  trackingResult: {
    type: Object,
    default: null,
  },
});

const form = useForm({
  order_number: '',
  guest_email: '',
});

function track() {
  form.post(route('track.track'), {
    preserveState: true,
  });
}
</script>

<template>
  <AppLayout :title="title">
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-24 sm:py-32">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-4xl">
        <div class="text-center">
             <h1 class="font-display text-4xl font-semibold tracking-tight text-luxe-pearl sm:text-5xl">
               Track Your Order
             </h1>
             <p class="mt-4 text-lg text-luxe-mist max-w-2xl mx-auto">
               Enter your Order Number and Email Address to see the current status and tracking details of your package.
             </p>
        </div>

        <div class="mt-12 bg-white/5 border border-white/10 rounded-3xl p-6 sm:p-10 shadow-luxe backdrop-blur-xl">
             <form @submit.prevent="track" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5 items-end">
                  <div class="lg:col-span-2">
                       <label for="order_number" class="block text-sm font-medium text-luxe-pearl mb-2">Order Number</label>
                       <input 
                            id="order_number"
                            v-model="form.order_number"
                            type="text" 
                            required
                            placeholder="e.g. ORD-000001"
                            class="block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                       />
                  </div>
                  <div class="lg:col-span-2">
                       <label for="guest_email" class="block text-sm font-medium text-luxe-pearl mb-2">Email Address</label>
                       <input 
                            id="guest_email"
                            v-model="form.guest_email"
                            type="email" 
                            required
                            placeholder="Used during checkout"
                            class="block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                       />
                  </div>
                  <div class="lg:col-span-1">
                       <button type="submit" :disabled="form.processing" class="w-full luxe-btn luxe-btn-primary flex items-center justify-center gap-2 h-[50px]">
                            <Search v-if="!form.processing" class="w-4 h-4" />
                            <RefreshCcw v-else class="w-4 h-4 animate-spin" />
                            Track
                       </button>
                  </div>
             </form>
             <p v-if="form.errors.order_number" class="mt-2 text-sm text-red-500">{{ form.errors.order_number }}</p>
             <p v-if="form.errors.guest_email" class="mt-2 text-sm text-red-500">{{ form.errors.guest_email }}</p>
        </div>

        <div v-if="trackingResult" class="mt-12">
             <div class="bg-luxe-strong border-luxe-gold/20 border rounded-3xl p-6 sm:p-10 shadow-luxe-gold">
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6 mb-6">
                       <div>
                            <h2 class="text-2xl font-display font-semibold text-luxe-pearl flex items-center gap-2">
                                 <Package class="h-6 w-6 text-luxe-gold" />
                                 Order {{ trackingResult.number }}
                            </h2>
                            <p class="text-luxe-mist mt-1">Placed on {{ trackingResult.created_at }}</p>
                       </div>
                       <span class="inline-flex items-center rounded-full bg-luxe-gold/10 px-4 py-1.5 text-sm font-medium text-luxe-gold ring-1 ring-inset ring-luxe-gold/20 capitalize">
                            {{ trackingResult.status }}
                       </span>
                  </div>

                  <div class="grid gap-8 md:grid-cols-2">
                       <div>
                            <h3 class="text-lg font-medium text-luxe-pearl mb-4">Shipping Info</h3>
                            <ul class="space-y-4">
                                 <li>
                                      <span class="block text-sm text-luxe-mist">Courier</span>
                                      <span class="font-medium text-luxe-pearl">{{ trackingResult.courier_name || 'Not assigned yet' }}</span>
                                 </li>
                                 <li>
                                      <span class="block text-sm text-luxe-mist">Tracking Number</span>
                                      <span class="font-medium text-luxe-pearl">{{ trackingResult.tracking_number || 'N/A' }}</span>
                                 </li>
                                 <li v-if="trackingResult.tracking_url">
                                      <a :href="trackingResult.tracking_url" target="_blank" class="inline-flex items-center gap-1.5 text-luxe-gold hover:text-luxe-gold/80 transition text-sm font-medium">
                                           Track on courier site <ExternalLink class="h-4 w-4" />
                                      </a>
                                 </li>
                            </ul>
                       </div>
                       
                       <div>
                            <h3 class="text-lg font-medium text-luxe-pearl mb-4">Items Summary</h3>
                            <ul class="space-y-2 mb-4 bg-black/20 rounded-xl p-4">
                                 <li v-for="(item, idx) in trackingResult.items" :key="idx" class="text-sm text-luxe-pearl flex justify-between">
                                      <span>{{ item.quantity }}x {{ item.name }}</span>
                                 </li>
                            </ul>
                            <div class="flex justify-between items-center text-luxe-pearl font-medium text-lg pt-2">
                                 <span>Total Amount</span>
                                 <span>{{ trackingResult.currency }} {{ Number(trackingResult.total).toFixed(2) }}</span>
                            </div>
                       </div>
                  </div>
             </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
