<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';


defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-slate-50">
        <!-- Top Navigation -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8"
            >
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-200"
                    >
                        &lt;/&gt;
                    </div>

                    <div>
                        <div
                            class="text-xl font-bold tracking-tight text-slate-900"
                        >
                            Code<span class="text-blue-600">Help</span>
                        </div>

                        <div
                            class="text-[10px] font-medium uppercase tracking-[0.18em] text-slate-400"
                        >
                            Developer Community
                        </div>
                    </div>
                </a>

                <!-- Register -->
                <div class="text-sm text-slate-600">
                    Don't have an account?

                    <TextLink
                        :href="register()"
                        class="ml-1 font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Sign up
                    </TextLink>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="relative overflow-hidden">
            <!-- Decorative background -->
            <div
                class="pointer-events-none absolute -left-40 -top-40 h-96 w-96 rounded-full bg-blue-200/30 blur-3xl"
            ></div>

            <div
                class="pointer-events-none absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-indigo-200/30 blur-3xl"
            ></div>

            <div
                class="relative mx-auto grid min-h-[calc(100vh-5rem)] max-w-7xl items-center gap-16 px-6 py-12 lg:grid-cols-2 lg:px-8"
            >
                <!-- Left: Branding -->
                <section class="hidden lg:block">
                    <div class="max-w-xl">
                        <!-- Badge -->
                        <div
                            class="mb-8 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-blue-600"
                            ></span>

                            A community built for developers
                        </div>

                        <!-- Heading -->
                        <h1
                            class="text-6xl font-bold leading-[1.05] tracking-tight text-slate-900"
                        >
                            Welcome
                            <span class="text-blue-600">back.</span>
                        </h1>

                        <p
                            class="mt-6 max-w-lg text-lg leading-8 text-slate-600"
                        >
                            Continue learning, asking questions, and sharing
                            your knowledge with developers around the
                            community.
                        </p>

                        <!-- Features -->
                        <div class="mt-10 space-y-5">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600"
                                >
                                    ?
                                </div>

                                <div>
                                    <h3
                                        class="font-semibold text-slate-900"
                                    >
                                        Ask better questions
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Get help from developers who understand
                                        your problem.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600"
                                >
                                    &lt;/&gt;
                                </div>

                                <div>
                                    <h3
                                        class="font-semibold text-slate-900"
                                    >
                                        Share your knowledge
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Help others by sharing solutions and
                                        your experience.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"
                                >
                                    ✓
                                </div>

                                <div>
                                    <h3
                                        class="font-semibold text-slate-900"
                                    >
                                        Build together
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Learn from developers and build better
                                        software together.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right: Login Card -->
                <section class="w-full max-w-md justify-self-center">
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 sm:p-10"
                    >
                        <!-- Heading -->
                        <div class="mb-8">
                            <div
                                class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-200"
                            >
                                &lt;/&gt;
                            </div>

                            <h2
                                class="text-3xl font-bold tracking-tight text-slate-900"
                            >
                                Welcome back
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Sign in to continue to your CodeHelp account.
                            </p>
                        </div>

                        <!-- Status -->
                        <div
                            v-if="status"
                            class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700"
                        >
                            {{ status }}
                        </div>

                        <!-- Passkey -->
                        <div class="mb-6">
                            <PasskeyVerify />
                        </div>

                        <!-- Divider -->
                        <div class="mb-6 flex items-center gap-4">
                            <div class="h-px flex-1 bg-slate-200"></div>

                            <span
                                class="text-xs font-medium uppercase tracking-wider text-slate-400"
                            >
                                or continue with email
                            </span>

                            <div class="h-px flex-1 bg-slate-200"></div>
                        </div>

                        <!-- Login Form -->
                        <Form
                            v-bind="store.form()"
                            :reset-on-success="['password']"
                            v-slot="{ errors, processing }"
                            class="flex flex-col gap-6"
                        >
                            <div class="grid gap-5">
                                <!-- Email -->
                                <div class="grid gap-2">
                                    <Label
                                        for="email"
                                        class="font-semibold text-slate-700"
                                    >
                                        Email address
                                    </Label>

                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        required
                                        autofocus
                                        :tabindex="1"
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="errors.email"
                                    />
                                </div>

                                <!-- Password -->
                                <div class="grid gap-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <Label
                                            for="password"
                                            class="font-semibold text-slate-700"
                                        >
                                            Password
                                        </Label>

                                        <TextLink
                                            v-if="canResetPassword"
                                            :href="request()"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-700"
                                            :tabindex="5"
                                        >
                                            Forgot password?
                                        </TextLink>
                                    </div>

                                    <PasswordInput
                                        id="password"
                                        name="password"
                                        required
                                        :tabindex="2"
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="errors.password"
                                    />
                                </div>

                                <!-- Remember -->
                                <div class="flex items-center">
                                    <Label
                                        for="remember"
                                        class="flex cursor-pointer items-center gap-3 text-sm text-slate-600"
                                    >
                                        <Checkbox
                                            id="remember"
                                            name="remember"
                                            :tabindex="3"
                                        />

                                        <span>Remember me</span>
                                    </Label>
                                </div>

                                <!-- Submit -->
                                <Button
                                    type="submit"
                                    class="mt-2 h-12 w-full rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 hover:shadow-blue-300"
                                    :tabindex="4"
                                    :disabled="processing"
                                    data-test="login-button"
                                >
                                    <Spinner v-if="processing" />

                                    <span v-else>Log in to CodeHelp</span>
                                </Button>
                            </div>

                            <!-- Register -->
                            <div
                                class="border-t border-slate-100 pt-6 text-center text-sm text-slate-500"
                            >
                                Don't have an account?

                                <TextLink
                                    :href="register()"
                                    class="ml-1 font-semibold text-blue-600 hover:text-blue-700"
                                    :tabindex="5"
                                >
                                    Create one
                                </TextLink>
                            </div>
                        </Form>
                    </div>

                    <!-- Security message -->
                    <p
                        class="mt-5 text-center text-xs leading-5 text-slate-400"
                    >
                        By continuing, you agree to use CodeHelp responsibly
                        and respectfully.
                    </p>
                </section>
            </div>
        </main>
    </div>
</template>