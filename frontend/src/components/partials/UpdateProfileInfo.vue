<template>
    <section class="bg-gray-800 px-16 py-8">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ profileInformation }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ updateProfileMessage }}
            </p>
        </header>

        <form id="send-verification" @submit.prevent="sendVerification">
            <input type="hidden" name="_token" :value="csrfToken">
        </form>

        <form @submit.prevent="submitProfileUpdate" class="mt-6 space-y-6">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="_method" value="patch">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ nameLabel }}</label>
                <input v-model="form.name" id="name" name="name" type="text" class="mt-1 block w-full" required autocomplete="name">
                <div v-if="errors.name" class="mt-2 text-sm text-red-600">
                    <span v-for="error in errors.name" :key="error">{{ error }}</span>
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ emailLabel }}</label>
                <input v-model="form.email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username">
                <div v-if="errors.email" class="mt-2 text-sm text-red-600">
                    <span v-for="error in errors.email" :key="error">{{ error }}</span>
                </div>

                <div v-if="userMustVerifyEmail && !emailVerified" class="mt-2">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ emailUnverified }}

                        <button @click="sendVerification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ resendVerification }}
                        </button>
                    </p>

                    <p v-if="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ verificationLinkSentMessage }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ saveButton }}</button>

                <p v-if="profileUpdated"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">
                    {{ savedMessage }}
                </p>
            </div>
        </form>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                name: '',
                email: ''
            },
            errors: {
                name: null,
                email: null
            },
            csrfToken: '',
            profileInformation: 'Profile Information',
            updateProfileMessage: "Update your account's profile information and email address.",
            nameLabel: 'Name',
            emailLabel: 'Email',
            emailUnverified: 'Your email address is unverified.',
            resendVerification: 'Click here to re-send the verification email.',
            verificationLinkSentMessage: 'A new verification link has been sent to your email address.',
            saveButton: 'Save',
            savedMessage: 'Saved.',
            profileUpdated: false,
            verificationLinkSent: false,
            userMustVerifyEmail: true,
            emailVerified: false,
        };
    },
    methods: {
        async sendVerification() {
            try {
                await axios.post('/verification/send', { _token: this.csrfToken });
                this.verificationLinkSent = true;
            } catch (error) {
                console.error('Error sending verification:', error.response ? error.response.data : error.message);
            }
        },
        async submitProfileUpdate() {
            try {
                const response = await axios.patch('/profile/update', {
                    _token: this.csrfToken,
                    name: this.form.name,
                    email: this.form.email
                });
                this.profileUpdated = true;
                this.errors = {};
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error updating profile:', error.message);
                }
            }
        }
    },
    mounted() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenMeta) {
            this.csrfToken = tokenMeta.getAttribute('content');
        } else {
            console.error('CSRF token not found');
        }

        // Populate form fields with initial values, if available
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        if (nameInput) this.form.name = nameInput.value;
        if (emailInput) this.form.email = emailInput.value;

        // Check if the user must verify email and if it's verified
        this.userMustVerifyEmail = true; // replace with actual check
        this.emailVerified = false; // replace with actual check
    }
};
</script>

<style scoped>
/* Add your styles here */
</style>
