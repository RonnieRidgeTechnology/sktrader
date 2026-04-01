<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const showPassword = ref(false);

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AppLayout>
    <Head title="Create account" />

    <section class="border-t border-editorial-ink/10 bg-editorial-cream py-12 dark:bg-zinc-900 sm:py-16 lg:py-20">
      <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/80 sm:p-10">
          <div class="mb-8 text-center">
            <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
              Create your account
            </h1>
            <p class="mt-1.5 text-sm text-editorial-slate dark:text-zinc-400">
              Sign up to track orders, save your details and enjoy a faster checkout.
            </p>
          </div>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <InputLabel for="name" value="Full name" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="name"
                type="text"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
                placeholder="Your name"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
              <InputLabel for="email" value="Email" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="email"
                type="email"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.email"
                required
                autocomplete="username"
                placeholder="you@example.com"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
              <InputLabel for="password" value="Password" class="text-editorial-ink dark:text-zinc-300" />
              <div class="relative mt-2">
                <input
                  id="password"
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
                  placeholder="••••••••"
                  class="block w-full rounded-xl border border-editorial-ink/20 bg-white py-3 pl-4 pr-12 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:outline-none focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-2 text-editorial-slate hover:bg-editorial-ink/5 dark:text-zinc-400 dark:hover:bg-zinc-600"
                  :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                  <svg v-if="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                  <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
              </div>
              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
              <InputLabel for="password_confirmation" value="Confirm password" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
              />
              <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
              <PrimaryButton
                type="submit"
                class="w-full justify-center rounded-xl bg-editorial-coral py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#a83609] focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-zinc-800"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
              >
                <span v-if="form.processing">Creating account…</span>
                <span v-else>Create account</span>
              </PrimaryButton>
            </div>

            <p class="text-center text-sm text-editorial-slate dark:text-zinc-400">
              Already have an account?
              <Link :href="route('login')" class="font-semibold text-editorial-coral hover:underline">Log in</Link>
            </p>
          </form>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
