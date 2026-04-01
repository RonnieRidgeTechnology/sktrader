<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Eye, EyeOff } from 'lucide-vue-next';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const showCurrent = ref(false);
const showNew = ref(false);
const showConfirm = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Update Password
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" />
                <div class="relative mt-1">
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrent ? 'text' : 'password'"
                        autocomplete="current-password"
                        class="block w-full rounded-md border-gray-300 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                    />
                    <button
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300"
                        :aria-label="showCurrent ? 'Hide password' : 'Show password'"
                        @click="showCurrent = !showCurrent"
                    >
                        <EyeOff v-if="showCurrent" class="h-4 w-4" stroke-width="2" />
                        <Eye v-else class="h-4 w-4" stroke-width="2" />
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />
                <div class="relative mt-1">
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showNew ? 'text' : 'password'"
                        autocomplete="new-password"
                        class="block w-full rounded-md border-gray-300 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                    />
                    <button
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300"
                        :aria-label="showNew ? 'Hide password' : 'Show password'"
                        @click="showNew = !showNew"
                    >
                        <EyeOff v-if="showNew" class="h-4 w-4" stroke-width="2" />
                        <Eye v-else class="h-4 w-4" stroke-width="2" />
                    </button>
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <div class="relative mt-1">
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirm ? 'text' : 'password'"
                        autocomplete="new-password"
                        class="block w-full rounded-md border-gray-300 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                    />
                    <button
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300"
                        :aria-label="showConfirm ? 'Hide password' : 'Show password'"
                        @click="showConfirm = !showConfirm"
                    >
                        <EyeOff v-if="showConfirm" class="h-4 w-4" stroke-width="2" />
                        <Eye v-else class="h-4 w-4" stroke-width="2" />
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
