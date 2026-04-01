<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  email: { type: String, required: true },
  token: { type: String, required: true },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AppLayout>
    <Head title="Set new password" />

    <section class="border-t border-editorial-ink/10 bg-editorial-cream py-12 dark:bg-zinc-900 sm:py-16 lg:py-20">
      <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/80 sm:p-10">
          <div class="mb-8 text-center">
            <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
              Set new password
            </h1>
            <p class="mt-1.5 text-sm text-editorial-slate dark:text-zinc-400">
              Choose a strong password for your account.
            </p>
          </div>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <InputLabel for="email" value="Email" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="email"
                type="email"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
              <InputLabel for="password" value="New password" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="password"
                type="password"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
                v-model="form.password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
              />
              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
              <InputLabel for="password_confirmation" value="Confirm password" class="text-editorial-ink dark:text-zinc-300" />
              <TextInput
                id="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-xl border-editorial-ink/20 bg-white py-3 pl-4 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/25 dark:border-zinc-600 dark:bg-zinc-700/50 dark:text-zinc-100"
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
                Reset password
              </PrimaryButton>
            </div>

            <p class="text-center">
              <Link :href="route('login')" class="text-sm font-medium text-editorial-coral hover:underline">
                Back to log in
              </Link>
            </p>
          </form>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
