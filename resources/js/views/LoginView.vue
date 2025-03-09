<script setup>
import { ref, computed, watch } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores";

const auth   = useAuthStore();
const router = useRouter();

const form = ref({
    email: "",
    password: ""
});

const emailError    = ref(null);
const passwordError = ref(null);

// ✅ Email Validation Function
const validateEmail = () => {
    if (!form.value.email) {
        emailError.value = "Email is required.";
    } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
        emailError.value = "Invalid email format.";
    } else {
        emailError.value = null;
    }
};

// ✅ Password Validation Function
const validatePassword = () => {
    if (!form.value.password) {
        passwordError.value = "Password is required.";
    } else if (form.value.password.length < 6) {
        passwordError.value = "Password must be at least 6 characters.";
    } else {
        passwordError.value = null;
    }
};

// ✅ Watcher: Real-time validation
watch(() => form.value.email, validateEmail);
watch(() => form.value.password, validatePassword);

// ✅ Form Submit Function
const handleLogin = async() => {
    validateEmail();
    validatePassword();

    if (!emailError.value && !passwordError.value) {
        const res = await auth.login({
            email : form.value.email,
            password : form.value.password
        });

        if(res?.success){
            console.log(res);

            router.push({name : 'home'});
        }else{
            emailError.value = res?.message;
        }

    }
};
</script>

<template>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full max-w-md">
            <h2 class="text-3xl font-bold mb-6 text-center text-white">
                <span class="bg-gradient-to-r text-transparent from-blue-500 to-purple-500 bg-clip-text">
                    LogIn
                </span>
            </h2>

            <form @submit.prevent="handleLogin">
                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>

                    <div>
                        <input id="email" type="email" v-model="form.email" @blur="validateEmail"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.email && !emailError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': emailError
                            }"
                            class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter your email">
                    </div>
                    <span v-if="emailError" class="text-red-500 text-sm">{{ emailError }}</span>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>

                    <div>
                        <input id="password" type="password" v-model="form.password" @blur="validatePassword"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.password && !passwordError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': passwordError
                            }"
                            class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter your password">
                    </div>
                    <span v-if="passwordError" class="text-red-500 text-sm">{{ passwordError }}</span>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-center">
                    <button type="submit" class="bg-gradient-to-r cursor-pointer from-blue-500 to-purple-500 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        LogIn
                    </button>
                </div>

                <!-- Forgot Password -->
                <div class="text-center mt-4">
                    <a href="#" class="text-gray-600 hover:underline">Forgot password?</a>
                </div>
            </form>
            <p class="text-center text-gray-600 mt-6">Don't have an account? <a href="#" class="text-blue-500 hover:underline">Sign up</a></p>
        </div>
    </div>
</template>

<style scoped>
.error {
    color: red;
    font-size: 14px;
}
</style>
