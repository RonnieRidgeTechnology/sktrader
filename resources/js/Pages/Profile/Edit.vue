<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AccountLayout from '@/Layouts/AccountLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.is_admin === true);
const Layout = computed(() => (isAdmin.value ? AdminLayout : AccountLayout));
</script>

<template>
    <Head title="Profile" />

    <component :is="Layout">
        <template v-if="isAdmin" #breadcrumb>
            <span class="text-sm text-zinc-500 dark:text-zinc-400">Admin / Profile</span>
        </template>

        <div :class="isAdmin ? 'space-y-6' : 'space-y-6'">
            <div class="mx-auto max-w-3xl space-y-6 sm:px-0">
                <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50 lg:p-8">
                    <h2 class="font-editorial text-lg font-semibold text-editorial-ink dark:text-white">Profile information</h2>
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="mt-4 max-w-xl" />
                </div>
                <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50 lg:p-8">
                    <h2 class="font-editorial text-lg font-semibold text-editorial-ink dark:text-white">Update password</h2>
                    <UpdatePasswordForm class="mt-4 max-w-xl" />
                </div>
                <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50 lg:p-8">
                    <h2 class="font-editorial text-lg font-semibold text-editorial-ink dark:text-white">Delete account</h2>
                    <DeleteUserForm class="mt-4 max-w-xl" />
                </div>
            </div>
        </div>
    </component>
</template>
