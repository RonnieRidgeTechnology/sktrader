<script setup>
import { onMounted, ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Lock, RefreshCcw } from 'lucide-vue-next';

const props = defineProps({
  title: String,
  order: Object,
  postData: Object,
  endpoint: String,
});

const jazzcashForm = ref(null);
const submitting = ref(false);

onMounted(() => {
  // Auto-submit to JazzCash in production via a short delay
  if (props.postData && props.endpoint) {
    submitting.value = true;
    setTimeout(() => {
      jazzcashForm.value.submit();
    }, 2000);
  }
});
</script>

<template>
  <AppLayout :title="title">
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-24 sm:py-32 min-h-[60vh] flex items-center justify-center">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-lg text-center">
        
        <div class="bg-white/5 border border-white/10 rounded-3xl p-8 shadow-luxe backdrop-blur-xl">
             <div class="flex justify-center mb-6">
                  <div class="h-16 w-16 bg-luxe-gold/20 text-luxe-gold rounded-2xl flex items-center justify-center animate-pulse">
                       <Lock class="w-8 h-8" />
                  </div>
             </div>
             <h1 class="font-display text-2xl font-semibold text-luxe-pearl mb-2">Redirecting to JazzCash</h1>
             <p class="text-luxe-myst text-sm mb-6">Please wait while we transfer you to the secure payment portal to finalize your order ({{ order.number }}).</p>
             
             <div class="flex justify-center items-center gap-2 text-luxe-gold font-medium mb-6">
                  <RefreshCcw class="w-5 h-5 animate-spin" />
                  Processing...
             </div>

             <!-- Hidden form auto-submitted -->
             <form ref="jazzcashForm" :action="endpoint" method="POST" class="hidden">
                  <input v-for="(val, key) in postData" :key="key" type="hidden" :name="key" :value="val" />
             </form>
             
             <div class="text-sm text-luxe-mist mt-8 pt-6 border-t border-white/10">
                  <p>If you are not redirected automatically within 5 seconds, <button @click="jazzcashForm.submit()" class="text-luxe-gold hover:underline">click here</button>.</p>
             </div>
        </div>
        
      </div>
    </section>
  </AppLayout>
</template>
