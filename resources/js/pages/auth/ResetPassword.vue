<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { update } from '@/routes/password';

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Reset Password" />

    <div class="min-h-screen bg-slate-50">
        <!-- Top Navigation -->
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-200">
                        &lt;/&gt;
                    </div>
                    <div>
                        <div class="text-xl font-bold tracking-tight text-slate-900">
                            Code<span class="text-blue-600">Help</span>
                        </div>
                        <div class="text-[10px] font-medium uppercase tracking-[0.18em] text-slate-400">
                            Developer Community
                        </div>
                    </div>
                </a>

                <!-- Back to login -->
                <div class="text-sm text-slate-600">
                    Back to
                    <TextLink :href="login()" class="ml-1 font-semibold text-blue-600 hover:text-blue-700">
                        Sign in
                    </TextLink>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="relative overflow-hidden">
            <!-- Decorative background -->
            <div class="pointer-events-none absolute -left-40 -top-40 h-96 w-96 rounded-full bg-blue-200/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-indigo-200/30 blur-3xl"></div>

            <div class="relative mx-auto grid min-h-[calc(100vh-5rem)] max-w-7xl items-center gap-16 px-6 py-12 lg:grid-cols-2 lg:px-8">
                <!-- Left: Branding -->
                <section class="hidden lg:block">
                    <div class="max-w-xl">
                        <div class="mb-8 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">
                            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            Account Recovery
                        </div>

                        <h1 class="text-6xl font-bold leading-[1.05] tracking-tight text-slate-900">
                            Create a new
                            <span class="text-blue-600">password.</span>
                        </h1>

                        <p class="mt-6 max-w-lg text-lg leading-8 text-slate-600">
                            Choose a strong password to keep your CodeHelp account secure and get back to the community.
                        </p>

                        <div class="mt-10 space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                    🔒
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Use a strong password</h3>
                                    <p class="mt-1 text-sm text-slate-500">Mix letters, numbers, and symbols for best security.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                    &lt;/&gt;
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Back to coding</h3>
                                    <p class="mt-1 text-sm text-slate-500">Once reset, you'll be signed in automatically.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                    ✓
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Link is one-time use</h3>
                                    <p class="mt-1 text-sm text-slate-500">For security, this reset link expires after 60 minutes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right: Form Card -->
                <section class="w-full max-w-md justify-self-center">
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 sm:p-10">
                        <!-- Heading -->
                        <div class="mb-8">
                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-200">
                                &lt;/&gt;
                            </div>
                            <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                                Set new password
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Enter and confirm your new password below.
                            </p>
                        </div>

                        <!-- Form -->
                        <Form
                            v-bind="update.form()"
                            :transform="(data) => ({ ...data, token, email })"
                            :reset-on-success="['password', 'password_confirmation']"
                            v-slot="{ errors, processing }"
                            class="flex flex-col gap-5"
                        >
                            <!-- Email (readonly) -->
                            <div class="grid gap-2">
                                <Label for="email" class="font-semibold text-slate-700">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    autocomplete="email"
                                    v-model="inputEmail"
                                    readonly
                                    class="h-12 rounded-xl border-slate-200 bg-slate-100 px-4 text-slate-500"
                                />
                                <InputError :message="errors.email" />
                            </div>

                            <!-- New Password -->
                            <div class="grid gap-2">
                                <Label for="password" class="font-semibold text-slate-700">New Password</Label>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    autofocus
                                    placeholder="Enter new password"
                                    :passwordrules="passwordRules"
                                    class="h-12 rounded-xl border-slate-200 bg-slate-50 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                />
                                <InputError :message="errors.password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="grid gap-2">
                                <Label for="password_confirmation" class="font-semibold text-slate-700">Confirm Password</Label>
                                <PasswordInput
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Confirm new password"
                                    :passwordrules="passwordRules"
                                    class="h-12 rounded-xl border-slate-200 bg-slate-50 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                />
                                <InputError :message="errors.password_confirmation" />
                            </div>

                            <Button
                                type="submit"
                                class="mt-2 h-12 w-full rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 hover:shadow-blue-300"
                                :disabled="processing"
                                data-test="reset-password-button"
                            >
                                <Spinner v-if="processing" />
                                <span v-else>Reset Password</span>
                            </Button>

                            <div class="border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
                                Back to
                                <TextLink :href="login()" class="ml-1 font-semibold text-blue-600 hover:text-blue-700">
                                    Sign in
                                </TextLink>
                            </div>
                        </Form>
                    </div>

                    <p class="mt-5 text-center text-xs leading-5 text-slate-400">
                        By continuing, you agree to use CodeHelp responsibly and respectfully.
                    </p>
                </section>
            </div>
        </main>
    </div>
</template>
