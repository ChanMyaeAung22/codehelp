<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
     layout: {
        title: 'Create your account',
        description:
            'Join CodeHelp and start learning, asking, and sharing with developers.',
    }
});
</script>

<template>
    <Head title="Create account" />

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

                <!-- Login -->
                <div class="text-sm text-slate-600">
                    Already have an account?

                    <TextLink
                        :href="login()"
                        class="ml-1 font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Log in
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

                            Join the developer community
                        </div>

                        <!-- Heading -->
                        <h1
                            class="text-6xl font-bold leading-[1.05] tracking-tight text-slate-900"
                        >
                            Start
                            <span class="text-blue-600">building.</span>
                        </h1>

                        <p
                            class="mt-6 max-w-lg text-lg leading-8 text-slate-600"
                        >
                            Create your CodeHelp account and connect with
                            developers who are learning, building, and solving
                            real coding problems.
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
                                        Get answers and guidance from developers
                                        who understand your problem.
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
                                        Share solutions, experience, and useful
                                        programming knowledge.
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
                                        Learn together
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Learn from other developers and build
                                        better software together.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right: Register Card -->
                <section
                    class="w-full max-w-md justify-self-center"
                >
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
                                Create your account
                            </h2>

                            <p
                                class="mt-2 text-sm leading-6 text-slate-500"
                            >
                                Join CodeHelp and start learning, asking, and
                                sharing with developers.
                            </p>
                        </div>

                        <!-- Register Form -->
                        <Form
                            v-bind="store.form()"
                            :reset-on-success="[
                                'password',
                                'password_confirmation',
                            ]"
                            v-slot="{ errors, processing }"
                            class="flex flex-col gap-6"
                        >
                            <div class="grid gap-5">
                                <!-- Name -->
                                <div class="grid gap-2">
                                    <Label
                                        for="name"
                                        class="font-semibold text-slate-700"
                                    >
                                        Name
                                    </Label>

                                    <Input
                                        id="name"
                                        type="text"
                                        required
                                        autofocus
                                        :tabindex="1"
                                        autocomplete="name"
                                        name="name"
                                        placeholder="Your full name"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="errors.name"
                                    />
                                </div>

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
                                        required
                                        :tabindex="2"
                                        autocomplete="email"
                                        name="email"
                                        placeholder="you@example.com"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="errors.email"
                                    />
                                </div>

                                <!-- Password -->
                                <div class="grid gap-2">
                                    <Label
                                        for="password"
                                        class="font-semibold text-slate-700"
                                    >
                                        Password
                                    </Label>

                                    <PasswordInput
                                        id="password"
                                        required
                                        :tabindex="3"
                                        autocomplete="new-password"
                                        name="password"
                                        placeholder="Create a password"
                                        :passwordrules="passwordRules"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="errors.password"
                                    />
                                </div>

                                <!-- Confirm Password -->
                                <div class="grid gap-2">
                                    <Label
                                        for="password_confirmation"
                                        class="font-semibold text-slate-700"
                                    >
                                        Confirm password
                                    </Label>

                                    <PasswordInput
                                        id="password_confirmation"
                                        required
                                        :tabindex="4"
                                        autocomplete="new-password"
                                        name="password_confirmation"
                                        placeholder="Confirm your password"
                                        :passwordrules="passwordRules"
                                        class="h-12 rounded-xl border-slate-200 bg-slate-50 transition focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                    />

                                    <InputError
                                        :message="
                                            errors.password_confirmation
                                        "
                                    />
                                </div>

                                <!-- Submit -->
                                <Button
                                    type="submit"
                                    class="mt-2 h-12 w-full rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 hover:shadow-blue-300"
                                    :tabindex="5"
                                    :disabled="processing"
                                    data-test="register-user-button"
                                >
                                    <Spinner v-if="processing" />

                                    <span v-else>
                                        Create my CodeHelp account
                                    </span>
                                </Button>
                            </div>

                            <!-- Login -->
                            <div
                                class="border-t border-slate-100 pt-6 text-center text-sm text-slate-500"
                            >
                                Already have an account?

                                <TextLink
                                    :href="login()"
                                    class="ml-1 font-semibold text-blue-600 hover:text-blue-700"
                                    :tabindex="6"
                                >
                                    Log in
                                </TextLink>
                            </div>
                        </Form>
                    </div>

                    <!-- Security message -->
                    <p
                        class="mt-5 text-center text-xs leading-5 text-slate-400"
                    >
                        By creating an account, you agree to use CodeHelp
                        responsibly and respectfully.
                    </p>
                </section>
            </div>
        </main>
    </div>
</template>