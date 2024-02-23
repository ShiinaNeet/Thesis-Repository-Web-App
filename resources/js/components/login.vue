<template>
    <div class="h-screen">
        <navigation />

        <div class="flex items-start min-h-[calc(100vh-62px)]">
            <div class="flex items-center w-9/12 py-8 min-h-[calc(100vh-62px)]" style="position: sticky; top:62px;">
                <div
                v-if="activeWindow === 'Login'"
                class="shrink m-auto w-[min(400px,75vw)]"
                id="login-form-wrapper"
                >
                    <h5 class="va-h5">
                        Welcome to Thesis Repository
                    </h5>
                    <h5 class="va-text-secondary">
                        Please login to continue
                    </h5>
                    <div class="mt-10">
                        <va-input
                        v-model="account.login.userId"
                        type="number"
                        label="E-mail Address"
                        class="w-full mb-2 bg-[rgba(255,255,255,0.45)]"
                        :error="account.isInvalid"
                        :disabled="account.isLoading"
                        outline
                        />
                        <va-input
                        v-model="account.login.password"
                        :type="account.isPasswordVisible ? 'text' : 'password'"
                        label="Password"
                        class="w-full mb-3 bg-[rgba(255,255,255,0.45)]"
                        :error="account.isInvalid"
                        :error-messages="account.invalidMessage[0]"
                        :disabled="account.isLoading"
                        outline
                        >
                            <template #appendInner>
                                <va-icon
                                :name="account.isPasswordVisible ? 'visibility_off' : 'visibility'"
                                size="small"
                                color="#154EC1"
                                @click="account.isPasswordVisible = !account.isPasswordVisible"
                                />
                            </template>
                        </va-input>
                    </div>
                    <div class="mt-6 mb-6">
                        <va-button
                        class="w-full"
                        :loading="account.isLoading"
                        :disabled="account.isLoading"
                        @click="loginAccount()"
                        block
                        >
                            Log in
                        </va-button>
                    </div>
                    <div class="flex justify-between">
                        <a
                        class="va-link hover:underline"
                        href="#"
                        @click="forgotPassword.modal = !forgotPassword.modal"
                        >
                            Forgot password?
                        </a>
                        <span class="text-right">
                            No account?
                            <a
                            class="va-link hover:underline"
                            href="javascript:void(0)"
                            @click="
                                account.isValid = false,
                                account.isInvalid = false,
                                account.isLoading = false,
                                account.login.userId = null,
                                account.login.password = null,
                                setActiveWindow('Register')
                            ">
                                Register now
                            </a>
                        </span>
                    </div>
                </div>
                <div
                v-if="activeWindow === 'Register'"
                class="shrink m-auto w-[min(400px,75vw)]"
                id="register-form-wrapper"
                >
                    <h5 class="va-h5">
                        Welcome, Guest User
                    </h5>
                    <h5 class="va-text-secondary">
                        Please register to continue
                    </h5>
                    <div class="mt-10">
                        <va-input
                        v-model="account.register.userId"
                        type="number"
                        label="User ID"
                        class="mb-2 w-full bg-[rgba(255,255,255,0.45)]"
                        requiredMark
                        :error="account.isInvalid && ((this.account.invalidMessage[1] != '') || (account.register.userId == null || account.register.userId == ''))"
                        :error-messages="account.invalidMessage[1]"
                        :success="account.isValid"
                        :messages="account.validMessage"
                        :readonly="account.isValid"
                        @update:modelValue="account.isInvalid = false"
                        outline
                        />
                    </div>
                    <div class="flex">
                        <va-checkbox
                        v-model="account.register.terms.checked"
                        class="mr-2 w-full z-[3]"
                        :error="account.register.terms.isInvalid"
                        :error-messages="account.register.terms.invalidMessage"
                        :disabled="account.register.saved || account.isValid"
                        @update:modelValue="account.register.terms.isInvalid = false"
                        />
                        <label class="absolute pt-[0.15rem] pl-[1.75rem] text-[15px] z-[1]">
                            I have read and agree to the
                            <a
                            class="va-link hover:underline"
                            href="javascript:void(0)"
                            @click="$root.redirectToPage('/terms')">
                                terms and condition
                            </a>
                        </label>
                    </div>
                    <div class="mt-6 mb-6">
                        <va-button
                        class="w-full"
                        :loading="account.isLoading"
                        :disabled="account.register.saved || account.isValid"
                        @click="account.register.saved = true, registerAccount()"
                        block
                        >
                            Register
                        </va-button>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-center">
                            Already have an account?
                            <a
                            class="va-link hover:underline"
                            href="javascript:void(0)"
                            @click="
                                account.isValid = false,
                                account.isInvalid = false,
                                account.isLoading = false,
                                account.register.userId = null,
                                account.register.terms.checked = false,
                                account.register.terms.isInvalid = false,
                                setActiveWindow('Login')
                            ">
                                Log in
                            </a>
                        </span>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#banner picture.va-image__content img {
    border-radius: 4px;
}
</style>

<style scoped>
#login-form-wrapper::after,
#register-form-wrapper::after {
    content: "";
    position: absolute;
    top: 30px;
    bottom: 30px;
    right: 0px;
    border-right: 1px solid #DEE5F2;
}
.mapouter {
    position: relative;
    height: 250px;
    background: #FFF;
}
.maprouter a {
    color: #FFF!important;
    position: absolute!important;
    top: 0!important;
    z-index: 0!important;
}
.gmap_canvas {
    overflow:hidden;
    height: 250px;
}
.gmap_canvas iframe {
    position: relative;
    z-index: 2;
}
</style>

<script>
import navmenu from '@/components/navbar.vue';

export default {
    data () {
        return {
            account: {
                isValid: false,
                isInvalid: false,
                isLoading: false,
                validMessage: "",
                invalidMessage: [
                    "Please check your credentials and try again.",
                    "",
                ],
                isPasswordVisible: false,
                login: {
                    userId: null,
                    password: null,
                },
                register: {
                    userId: null,
                    invalidMessage: "The User ID field is required.",
                    terms: {
                        checked: false,
                        isInvalid: false,
                        invalidMessage: "You must agree to the terms to register.",
                    },
                    saved: false,
                },
            },
            activeWindow: 'Login',
            loginDelay: 250,
        };
    },
    components: {
        navigation: navmenu,
    },
    methods: {
        formatMobileNumber(mobile) {
            const mobileWithoutLeadingZero = mobile.substring(1);
            return `(` + this.$root.config.contactCountryCode + `) ${mobileWithoutLeadingZero.slice(0, 3)} ${mobileWithoutLeadingZero.slice(3, 6)} ${mobileWithoutLeadingZero.slice(6)}`;
        },
        registerAccount() {
            this.account.isLoading = true;
            this.account.isValid = false;
            this.account.isInvalid = false;

            if (this.account.register.userId && this.account.register.terms.checked) {
                axios({
                    method: 'POST',
                    type: 'JSON',
                    url: '/register',
                    data: {
                        userId: this.account.register.userId,
                    },
                }).then(response => {
                    this.account.isLoading = false;

                    if (response.data.status == 1) {
                        // this.$root.prompt(response.data.text);
                        this.account.isValid = true;
                        this.account.validMessage = response.data.text;
                        this.account.isInvalid = false;

                        this.account.register.saved = false;
                    } else {
                        this.account.invalidMessage[1] = response.data.text;
                        this.account.isInvalid = true;

                        this.account.register.saved = false;
                    }
                }).catch(error => {
                    // this.$root.prompt(error.response.data.message);
                    this.account.invalidMessage[1] = error.response.data.message;
                    this.account.isLoading = false;
                    this.account.isInvalid = true;

                    this.account.register.saved = false;
                });
            } else {
                this.account.isLoading = false;

                this.account.invalidMessage[1] = this.account.register.invalidMessage;
                this.account.isInvalid = true;

                if (!this.account.register.terms.checked) this.account.register.terms.isInvalid = true;

                this.account.register.saved = false;
            }
        },
        loginAccount() {
            this.account.isLoading = true;
            this.account.isInvalid = false;

            axios({
                method: 'POST',
                type: 'JSON',
                url: '/login',
                data: {
                    userId: this.account.login.userId,
                    password: this.account.login.password,
                }
            }).then(response => {
                this.account.isLoading = false;

                if (response.data.status == 1) {
                    setTimeout(() => {
                        window.location = response.data.redirect;
                    }, this.loginDelay);
                } else {
                    // this.$root.prompt(response.data.text);
                    this.account.isInvalid = true;
                }
            }).catch(error => {
                // this.$root.prompt(error.response.data.message);
                this.account.isLoading = false;
                this.account.isInvalid = true;
            });
        },
        setActiveWindow(window) {
            this.activeWindow = window;

            if (window === 'Login' && this.account.isValid) {
                this.account.isValid = false;
                this.account.validMessage = "";
                this.account.register.userId = null;
            }
        },
        getForgotPassword(){
            this.forgotPassword.isLoading = true;
            this.forgotPassword.invalidMessage[0] && (this.forgotPassword.invalidMessage[0] = "This User ID is not associated to any account.");

            if (this.forgotPassword.userId) {
                axios({
                    method: 'POST',
                    type: 'JSON',
                    url: '/password',
                    data: {
                        userId: this.forgotPassword.userId,
                    }
                }).then(response => {
                    this.forgotPassword.isLoading = false;

                    if (response.data.status == 1) {
                        // this.$root.prompt(response.data.text);
                        this.forgotPassword.isValid = true;
                        this.forgotPassword.validMessage = "Password reset success, check your email for the password.";
                        this.forgotPassword.isInvalid = false;
                    } else this.forgotPassword.isInvalid = true;
                }).catch(error => {
                    // this.$root.prompt(error.response.data.message);
                    this.forgotPassword.invalidMessage[0] = error.response.data.message;
                    this.forgotPassword.isLoading = false;
                    this.forgotPassword.isInvalid = true;
                });
            } else {
                this.forgotPassword.isLoading = false;
                this.forgotPassword.isInvalid = true;
            }
        },
    },
}
</script>
