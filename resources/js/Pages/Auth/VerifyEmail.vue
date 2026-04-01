<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  status: { type: String },
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
  <AppLayout>
    <Head title="Email Verification" />

    <section class="border-t border-editorial-ink/10 bg-editorial-cream py-12 dark:bg-zinc-900 sm:py-16 lg:py-20">
      <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/80 sm:p-10">
          <div class="mb-6 text-center">
            <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
              Verify your email
            </h1>
            <p class="mt-1.5 text-sm text-editorial-slate dark:text-zinc-400">
              Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you. If you didn't receive it, we can send another.
            </p>
          </div>

          <div
            v-if="verificationLinkSent"
            class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-400"
          >
            A new verification link has been sent to the email address you provided.
          </div>

          <form @submit.prevent="submit" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <PrimaryButton
              type="submit"
              class="w-full justify-center rounded-xl bg-editorial-coral py-3 text-sm font-semibold text-white transition hover:bg-[#a83609] focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-offset-2 disabled:opacity-50 sm:w-auto sm:px-6"
              :class="{ 'opacity-75': form.processing }"
              :disabled="form.processing"
            >
              Resend verification email
            </PrimaryButton>
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="text-center text-sm font-medium text-editorial-slate underline hover:text-editorial-coral dark:text-zinc-400"
            >
              Log out
            </Link>
          </form>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
