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
                        label="User ID"
                        class="w-full mb-2 bg-[rgba(255,255,255,0.45)]"
                        :error="account.isInvalid"
                        :error-messages="account.invalidMessage[0]"
                        :disabled="account.isLoading"
                        immediate-validation
                        outline
                        />
                        <va-input
                        v-model="account.login.password"
                        :type="account.isPasswordVisible ? 'text' : 'password'"
                        label="Password"
                        class="w-full mb-3 bg-[rgba(255,255,255,0.45)]"
                        :error="account.isInvalid && (account.login.password === '' || account.login.password === null)"
                        :error-messages="account.invalidMessage[0]"
                        :disabled="account.isLoading"
                        immediate-validation
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
                    <!-- <div class="flex-col bg-red-200 py-4 justify-center mt-4">
                        <h3 class="font-bold text-black-900 justify-center flex-center" v-for="invalid in account.invalidMessage">{{ invalid }}</h3>
                    </div> -->
                    <div class="mt-3">
                        <va-input
                        v-model="account.register.userId"
                        label="User ID"
                        class="w-full mb-3 bg-[rgba(255,255,255,0.45)]"
                        :disabled="account.isLoading"
                        outline
                        :error="account.register.isValidUserID"
                        :error-messages="account.register.userIDError"
                        />
                    </div>
                    <div class="mt-1">
                        <va-input
                        v-model="account.register.password"
                        :type="account.register.isPasswordVisible ? 'text' : 'password'"
                        label="Password"
                        class="w-full mb-3 bg-[rgba(255,255,255,0.45)]"
                        :disabled="account.isLoading"
                        outline
                        :error="account.register.invalidPassword"
                        @keyup="account.register.invalidPassword = false"
                        >
                            <template #appendInner>
                                <va-icon
                                :name="account.register.isPasswordVisible ? 'visibility_off' : 'visibility'"
                                size="small"
                                color="#154EC1"
                                @click="account.register.isPasswordVisible = !account.register.isPasswordVisible"
                                />
                            </template>
                        </va-input>
                        <div class="text-sm" v-if="account.register.invalidPassword">
                            <p class="text-red-700"> {{ account.register.passwordError }}</p>
                        </div>
                    </div>
                    <div class="flex">
                        <va-checkbox
                        v-model="account.register.terms.checked"
                        class="mr-2 w-full z-[3]"
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
                    <div v-if="account.register.terms.isInvalid" class="text-red-500 text-sm">
                        {{ account.register.terms.invalidMessage }}
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
                    "",
                ],
                isPasswordVisible: false,
                login: {
                    userId: 0,
                    password: null,
                },
                register: {
                    userId: 0,
                    password: "",
                    passwordmatch:null,
                    invalidMessage: false,
                    invalidPassword: false,
                    isValidUserID: false,
                    idErrorMessage:"Wrong Login credential",
                    passwordMisMatch: [ "The password does not match.",
                                        "The User ID field is required.",
                                      ],
                    isPasswordVisible:false,
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
        registerAccount() {
            this.account.isLoading = true;
            this.account.isValid = false;
            this.account.isInvalid = false;
            if(this.account.register.terms.checked === false){
                this.account.register.terms.isInvalid = true;
                this.account.register.terms.invalidMessage = "Accepting the terms and Conditions are required."
                this.account.isLoading = false;
                this.account.register.saved = false;
                this.account.isInvalid = false;
                return;
            }
            if(this.account.register.userId === 0){
                this.account.register.isValidUserID = false;
                this.account.register.idErrorMessage = "Incorrect Account Credential";
            }
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/register',
                data: {
                    userID: this.account.register.userId,
                    password: this.account.register.password,
                },
            }).then(response => {
                this.account.isLoading = false;

                if (response.data.status == 1) {
                    this.account.isValid = true;
                    this.account.validMessage = response.data.text;
                    this.account.isInvalid = false;
                    this.account.register.saved = false;
                    this.setActiveWindow('Login');
                } else {
                    this.account.invalidMessage[1] = response.data.text;
                    this.account.isInvalid = true;
                    
                    this.$root.prompt(response.data.text);
                       
                    this.account.register.saved = false;
                }
            }).catch(error => {
                let resDataError = Object.keys(error.response.data.errors);
                console.log(resDataError);
                if (resDataError[0]=== "userID") {
                        this.account.register.isValidUserID = true;
                        this.account.register.userIDError = "Invalid User ID";
                }
                if (resDataError.includes('password')) {
                        this.account.register.invalidPassword = true;
                        this.account.register.passwordError = "Invalid Password";
                }     

                this.account.isLoading = false;
                this.account.isInvalid = true;
                this.account.register.terms.isInvalid = false;
                this.account.register.saved = false;
                this.account.register.invalidMessage = false;
            });
        },
        loginAccount() {
            this.account.isLoading = true;
            this.account.isInvalid = false;
            if(this.account.login.userId === 0)
            {
                
                this.account.login.invalidUserID = true;
                this.account.login.invalidUserIDMessage = "Password cannot be 0";
            }
            if(this.account.login.password === null)
            {
                this.account.login.invalidPassword = true;
            }

            axios({
                method: 'POST',
                type: 'JSON',
                url: '/login',
                data: {
                    userID: this.account.login.userId,
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
                let dataerror = Object.keys(error.response.data.errors)
                dataerror.forEach(key =>{
                    if(key === "userID"){
                        console.log("user id error");
                    }
                })
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
