<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps({
  canResetPassword: { type: Boolean },
  status: { type: String },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const showPassword = ref(false);

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <AppLayout>
    <Head title="Log in" />

    <section class="border-t border-editorial-ink/10 bg-editorial-cream py-12 dark:bg-zinc-900 sm:py-16 lg:py-20">
      <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/80 sm:p-10">
          <div class="mb-8 text-center">
            <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
              Welcome back
            </h1>
            <p class="mt-1.5 text-sm text-editorial-slate dark:text-zinc-400">
              Sign in to your account to view orders and manage your profile.
            </p>
          </div>

          <div
            v-if="status"
            class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-400"
          >
            {{ status }}
          </div>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <InputLabel for="email" value="Email" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="email"
                type="email"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.email"
                required
                autofocus
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
                  autocomplete="current-password"
                  placeholder="••••••••"
                  class="block w-full rounded-xl border border-editorial-ink/20 bg-white py-3 pl-4 pr-12 text-editorial-ink placeholder-editorial-slate/60 focus:border-editorial-coral focus:outline-none focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-2 text-editorial-slate hover:bg-editorial-ink/5 dark:text-zinc-400 dark:hover:bg-zinc-600"
                  :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                  <EyeOff v-if="showPassword" class="h-5 w-5" stroke-width="1.75" />
                  <Eye v-else class="h-5 w-5" stroke-width="1.75" />
                </button>
              </div>
              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
              <label class="flex cursor-pointer items-center gap-3">
                <Checkbox
                  name="remember"
                  v-model:checked="form.remember"
                  class="border-editorial-ink/30 text-editorial-coral focus:ring-editorial-coral"
                />
                <span class="text-sm text-editorial-slate dark:text-zinc-400">Remember me</span>
              </label>
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm font-medium text-editorial-coral hover:underline"
              >
                Forgot password?
              </Link>
            </div>

            <div class="pt-2">
              <PrimaryButton
                type="submit"
                class="w-full justify-center rounded-xl bg-editorial-coral py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#a83609] focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-zinc-800"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
              >
                <span v-if="form.processing">Signing in…</span>
                <span v-else>Log in</span>
              </PrimaryButton>
            </div>

            <p class="text-center text-sm text-editorial-slate dark:text-zinc-400">
              Don't have an account?
              <Link :href="route('register')" class="font-semibold text-editorial-coral hover:underline">Sign up</Link>
            </p>
          </form>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
