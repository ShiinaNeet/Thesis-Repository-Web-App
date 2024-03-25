<template>
    <div class="mx-5 mb-2 py-5 px-2.5 pb-2.5 bg-white rounded text-wrap">
        
        <div class="grid place-content-end ">
            <div class="flex flex-center justify-content py-2">
                <VaButton
                icon="add"
                color="primary"
                outlined
                preset="secondary"
                border-color="primary"
                @click="createAccount.modal=true"
                
                >
                    ADD
                </VaButton>
            </div>
        </div>
        <va-data-table
        id="data-table"
        :items="accounts"
        :columns="acc.tblColumns"
        :per-page="$root.config.tblPerPage"
        :current-page="$root.config.tblCurrPage"
        no-data-html="No account(s) to show"
        :filter="filter"
        @filtered="filtered = $event.items"
        animated
        striped
        class=""
        :loading="gettingAccounts"
        >
            <template #headerAppend>
                    <tr class="table-crud__slot ">
                        <th
                        v-for="key in Object.keys(createAccount.data)"
                        :key="key"
                        class="py-0 pr-1 flex flex-col col-auto justify-center"
                        >
                            <va-input
                            class="flex"
                            v-if="key.includes('userID')"
                            v-model="filter"
                            placeholder="Search..."
                            />  
                        </th>
                    </tr>
            </template>
            <template #cell(user_type)="{ value }">
                <va-badge 
                :text="acc.types[value].label"
                :color="acc.types[value].color"
                />
                <!-- {{ acc.types[value].label }} -->
            </template>
            <template #cell(email)="{ value }">
                <div v-if="value === ''">
                    <label>No Email Address</label>
                </div>
                
                <div class="text-wrap" v-else>
                    {{ value }} 
                </div>
                
            </template>
            <template #cell(created_at)="{ value }">
               {{ formatDate(value,'MMM. DD, YYYY','Invalid Date Format') }}
            </template>
            <template #cell(id)="{ rowData }">
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                :class="rowData.userID === $root.auth.userID ? 'opacity-[0!important]' : ''"
                title="Edit"
                preset="plain"
                icon="edit"
                :disabled="rowData.deleted_at || rowData.userID === $root.auth.userID ? true : false"
                @click="editAccount.data = { ...rowData }, editAccount.modal = !editAccount.modal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                :class="rowData.userID === $root.auth.userID ? 'opacity-[0!important]' : ''"
                title="Generate New Password"
                preset="plain"
                icon="password"
                :disabled="rowData.deleted_at || rowData.userID === $root.auth.userID ? true : false"
                @click="editAccount.data = { ...rowData },editAccount.passwordModal = !editAccount.passwordModal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                :class="rowData.userID === $root.auth.userID ? 'opacity-[0!important]' : ''"
                title="Toggle Status"
                preset="plain"
                :icon="rowData.deleted_at ? 'lock' : 'lock_open'"
                @click="editAccount.data = { ...rowData }, editAccount.statusModal = !editAccount.statusModal"
                />
                
            </template>

            <template #bodyAppend>
            <tr v-if="$root.tblPagination(accounts)">
                <td
                id="pagination"
                :colspan="acc.tblColumns.length"
                >
                    <div class="flex pt-[10px] select-none">
                        <va-pagination
                        class="justify-center"
                        v-model="$root.config.tblCurrPage"
                        :pages="filter == '' ? $root.tblPagination(accounts) : (pages, $root.config.tblCurrPage = 1)"
                        input
                        />
                    </div>
                </td>
            </tr>
            </template>
        </va-data-table>
    </div>
    <!-- Create -->
    <VaModal
    v-model="createAccount.modal"
    no-outside-dismiss
    blur
    :mobile-fullscreen=true
    hide-default-actions
    size="small"
    close-button
    >
        <div class="w-full h-full">
            <div class="header"> 
                <h1>Add Account</h1>
            </div>
            <div 
            v-if="createAccount.repasswordErrorMessage"
            class="my-2 py-5 bg-red-300 rounded-lg text-black flex flex-cventer justify-center"
            >
                <h1 class="justify-center flex-center">Password Mismatch! Please Retry!</h1>
            </div>
            <div class="flex flex-col text-wrap py-5 text-red-600">
                <VaInput
                class="py-2"
                v-model="createAccount.data.userID"
                placeholder="User ID"
                label="User ID"
                preset="bordered"
                type="number"
                immediate-validation
                :error="createAccount.userIDEmpty"
                :error-messages="createAccount.userIDErrorMessage"
                />
                <VaInput
                class="py-2"
                v-model="createAccount.data.email"
                placeholder="Email Addresss"
                label="Email"
                preset="bordered"
                immediate-validation
                :error="createAccount.emailEmpty"
                :error-messages="createAccount.emailErrorMessage"
                />
                <VaSelect
                class="py-2"
                v-model="createAccount.data.user_type"
                placeholder="User Role"
                :options="userTypes"
                preset="bordered"
                value-by="id"
                text-by="name"
                />
                <VaInput
                class="py-2"
                v-model="createAccount.data.password"
                placeholder="Password"
                label="Password"
                preset="bordered"
                immediate-validation
                :error="createAccount.passwordEmpty"
                :error-messages="createAccount.passwordErrorMessage[0]"
                />
                <VaInput
                class="py-2"
                immediate-validation
                v-model="createAccount.data.repassword"
                placeholder="Password"
                label="Password"
                preset="bordered"
                :error="createAccount.repasswordEmpty"                
                />
            </div>
            <div class="flex flex-center justify-center">
                <VaButton
                @click="createNewAccount(), createAccount.saved = true, createAccount.passwordMismatch = false"
                class="mx-3"
                :loading="createAccount.saved"
                >
                    Save
                </VaButton>
                <VaButton
                @click="createAccount.modal = !createAccount.modal"
                preset="primary"
                class="mx-3"
                >
                    Cancel
                </VaButton>
            </div>
        </div>
    </VaModal>
    <!-- Edit Modal -->
    <VaModal
    v-model="editAccount.modal"
    no-outside-dismiss
    blur
    :mobile-fullscreen=true
    hide-default-actions
    size="small"
    close-button
    >
        <div class="w-full h-full">
            <div class="header"> 
                <h1>Edit Account</h1>
            </div>
            <div class="flex flex-col text-wrap py-5 text-red-600">
                <VaInput
                class="py-2"
                v-model="editAccount.data.userID"
                placeholder="User ID"
                label="User ID"
                preset="bordered"
                type="number"
                immediate-validation
                :error="editAccount.userIDEmpty"
                :error-messages="editAccount.userIDErrorMessage"
                />
                <VaInput
                class="py-2"
                v-model="editAccount.data.email"
                placeholder="Email Addresss"
                label="Email"
                preset="bordered"
                immediate-validation
                :error="editAccount.emailEmpty"
                :error-messages="editAccount.emailErrorMessage"
                />
                <VaSelect
                class="py-2"
                v-model="editAccount.data.user_type"
                placeholder="User Role"
                :options="userTypes"
                preset="bordered"
                value-by="id"
                text-by="name"
                />
            </div>
            <div class="flex flex-center justify-center">
                <VaButton
                @click="editAccount.isLoading = true,UpdateAccount(), editAccount.saved = true, editAccount.passwordMismatch = false"
                class="mx-3"
                :loading="editAccount.isLoading"
                
                >
                    Save
                </VaButton>
                
                <VaButton
                @click="editAccount.modal = !editAccount.modal"
                preset="primary"
                class="mx-3"
                >
                    Cancel
                </VaButton>
            </div>
        </div>
    </VaModal>
    <!-- Password Modal -->
    <VaModal
    v-model="editAccount.passwordModal"
    no-outside-dismiss
    blur
    :mobile-fullscreen=true
    hide-default-actions
    size="auto"
    close-button
    >
        <div class="w-full h-full">
            <div class="header"> 
                <h1 
                class="py-5 text-2xl uppercase flex-center justify-center"
                >Generate New Password </h1>
                <div
                class=" w-full"
                v-if="editAccount.isPasswordWindow == false"
                >
                    <VaInput
                    :placeholder="editAccount.data.userID"
                    label="User ID"
                    readonly
                    preset="bordered"
                    class="flex w-full pb-5"
                    />
                    <br />
                    <VaInput
                   
                    :placeholder="editAccount.data.email"
                    label="Email"
                    readonly
                    preset="bordered"
                    class="flex w-full"
                    />
                </div>
                <div v-else-if="editAccount.isPasswordWindow == true"
                class="flex-center justify-center"
                >
                    <div class="text-wrap">
                        <VaInput
                        v-model="editAccount.newPassword"
                        label="New Password"
                        readonly
                        preset="bordered"
                        class="flex w-full"
                        :success="editAccount.newpasswordSuccess"
                        :messages="editAccount.newPasswordMessage"
                        />
                    </div>
                </div>
                
            </div>
            <div class="flex flex-col text-wrap py-5 text-red-600">
                
            </div>
            <div class="flex flex-center justify-center">
                <VaButton
                @click="editAccount.isLoading = true, GeneratePassword(), editAccount.saved = true"
                class="mx-3"
                :loading="editAccount.isLoading"
                v-if="editAccount.isPasswordWindow == false"
                >
                    Generate
                </VaButton>
                
                <VaButton
                @click="editAccount.passwordModal = !editAccount.passwordModal, editAccount.isPasswordWindow = false"
                preset="primary"
                class="mx-3"
                >
                    Cancel
                </VaButton>
            </div>
        </div>
    </VaModal>

    <va-modal
    v-model="editAccount.statusModal"
    @cancel="editAccount.data = { ...editAccount.data = {...newAccount} }"
    noPadding
    size="auto"
    >
        <template #content>
            <div class="w-full p-5">
                <div class="va-title mb-3">
                    Account Status
                </div>
                
                <va-input
                type="textarea"
                :model-value="editAccount.data.userID"
                class="w-full mb-2 force-noresize"
                readonly
                autosize
                />
                
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editAccount.data = { ...newAccount }, editAccount.statusModal = !editAccount.statusModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        :icon="!editAccount.data.deleted_at ? 'lock' : 'lock_open'"
                        :loading="editAccount.saved"
                        :disabled="editAccount.saved"
                        @click="editAccount.saved = true,handleButtonClick()"
                        >
                            <p class="font-normal">{{ !editAccount.data.deleted_at ? "Deactivate" : "Activate" }}</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>
</template>



<script>

import formatDate from '@/functions/formatdate.js';

const newAccount = {
    userID: null,
    email: "",
    user_type: "",
    deleted_at: null,
    created_at: formatDate(new Date().getTime(), 'YYYY-MM-DD HH:mm:ss'),
    id: null,
};

export default {
    data () {
        const acc = {
            tblColumns: [
                { key: "userID", label: "User ID", width: "15%", sortable: true },
                { key: "email", label: "Email", width: "55%", sortable: false },
                { key: "user_type", label: "Role", width: "10%", sortable: false },
                { key: "created_at", label: "Registered Date", width: "10%",  sortable: true },
                { key: "id", label: "Action", width: "10%", sortable: false }
            ],
            createNew: [
                { key: "userID", label: "User ID", width: "15%", sortable: true },
                { key: "email", label: "Email", width: "55%", sortable: false },
                { key: "user_type", label: "Role", width: "10%", sortable: false },
                { key: "created_at", label: "Registered Date", width: "15%",  sortable: false },
                { key: "actionid", label: "Action", width: "5%", sortable: false }
            ],
            types: [
                { label: "Administrator", value: 0, color: "warning" },
                { label: "AssistantLibrarian", value: 1, color: "primary" },
                { label: "Librarian", value: 2, color: "info"},
                { label: "Student", value: 3, color: "success" }
            ]
        };

        return {
            acc,
            gettingAccounts: false,
            accounts: [],
            account: {},
            createAccount: {
                modal: false,
                userIDEmpty: false,
                emailEmpty: false,
                userTypeEmpty: false,
                passwordEmpty: false,
                passwordMismatch: false,
                emailErrorMessage:"",
                passwordErrorMessage:"",
                userIDErrorMessage:"",
                user_typeErrorMessage:"",
                repasswordErrorMessage:"",
                saved: false,
                data:{...newAccount},
                
            },
            editAccount: {
                isPasswordWindow:false,
                modal: false,
                userIDEmpty: false,
                emailEmpty: false,
                userTypeEmpty: false,
                passwordEmpty: false,
                passwordMismatch: false,
                newPassword:null,
                newpasswordSuccess: false,
                emailErrorMessage:"",
                passwordErrorMessage:"",
                userIDErrorMessage:"",
                user_typeErrorMessage:"",
                repasswordErrorMessage:"",
                newPasswordMessage:"",
                statusModal: false,
                saved: false,
                isLoading:false,
                data: {},
                
            },
            filtered: null,
            filter: "",
            fmode: 1,
            userTypes: [
                { id: 0, name: "Administrator" },
                { id: 1, name: "Assistant Librarian" },
                { id: 2, name: "Librarian" },
                { id: 3, name: "Student" }
            ],
        };
    },
    computed: {
        pages() {
            return this.$root.config.tblPerPage && this.$root.config.tblPerPage !== 0
            ? Math.ceil(this.filtered.length / this.$root.config.tblPerPage)
            : this.filtered.length;
        },
    },
    mounted () {
        this.getAccounts();
    },
    computed: {
        isNewAccount() {
            return Object.keys(this.createAccount.data).every(
                (key) => {
                    const value = this.createAccount.data[key];
                    if (key === 'deleted_at' || key === 'id') {
                        return value === null;
                    }
                    if (key === 'user_type') {
                        return typeof value === 'number';
                    }
                    return !!value;
                }
            );
        }
    },
    methods: {
        handleButtonClick() {
            this.editAccount.saved = true;

            if (this.editAccount.data.deleted_at === null) {
                this.toggleHelpStatus();
            } else {
                this.enableHelpStatus();
            }
        },
        toggleHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/account/disable',
                data: { id: this.editAccount.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editAccount.data = { ...newAccount };
                    this.editAccount.statusModal = false;
                    this.editAccount.saved = false;

                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        enableHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/account/enable',
                data: { id: this.editAccount.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editAccount.data = { ...newAccount };
                    this.editAccount.statusModal = false;
                    this.editAccount.saved = false;

                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        toggleAccountStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/account/delete',
                data: { id: this.editAccount.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editAccount.data = { ...newAccount };
                    this.editAccount.saved = false;
                    this.editAccount.statusModal = false;

                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        createNewAccount(){
            this.createAccount.userIDEmpty= false,
            this.createAccount.emailEmpty= false,
            this.createAccount.userTypeEmpty= false,
            this.createAccount.passwordEmpty= false,
            this.createAccount.passwordMismatch= false,
            axios({
            method: 'POST',
            type: 'JSON',
            url: '/account/save',
            data: this.createAccount.data,
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.createAccount.data = { ...newAccount };
                    this.createAccount.saved = false;
                    this.createAccount.modal = false;
                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                let resDataError = Object.keys(error.response.data.errors);

                resDataError.forEach(key => {
                    if (key === 'userID') {
                        this.createAccount.userIDEmpty = true;
                        this.createAccount.userIDErrorMessage = error.response.data.errors.userID[0];
                    }
                    if (key === 'email') {
                        this.createAccount.emailEmpty = true;
                        this.createAccount.emailErrorMessage = error.response.data.errors.email[0];
                    }
                    if (key === 'user_type') {
                       
                        this.createAccount.user_typeErrorMessage = error.response.data.errors.user_type[0];
                    }
                    if (key === 'password') {
                        this.createAccount.passwordEmpty = true;
                        this.createAccount.passwordErrorMessage = error.response.data.errors.password;
                    }
                    if (key === 'repassword') {
                        this.createAccount.passwordMismatch = true;
                        this.createAccount.repasswordErrorMessage = error.response.data.errors.repassword;
                    }
                    this.createAccount.saved = false;    
                });

            }
            );
        },
        UpdateAccount() {
            this.editAccount.isLoading = true;
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/account/update',
                data: this.editAccount.data
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editAccount.data = { ...newAccount },
                    this.editAccount.modal = false,
                    this.editAccount.saved = false,
                    this.editAccount.isLoading = false;
                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
                this.editAccount.isLoading = false;
            }).catch(error => {
                let resDataError = Object.keys(error.response.data.errors);

                if (resDataError.filter(key => key == 'userID').length) {
                    this.createAccount.userIDEmpty = true;
                }
                if (resDataError.filter(key => key == 'user_type').length) {
                    this.createAccount.userTypeEmpty = true;
                }
                this.$root.prompt(error.response.data.message);
                

                this.editAccount.isLoading = false;
            });
            
        },
        GeneratePassword() {
            this.editAccount.isLoading = true;
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/account/generate_password',
                data: this.editAccount.data,
            }).then(response => {
                if (response.data.status == 1) {
                    // this.$root.prompt(response.data.text);
                    this.editAccount.data = { ...newAccount },
                    this.editAccount.isPasswordWindow = true;
                    this.editAccount.newpasswordSuccess = true;
                    this.editAccount.newPasswordMessage = response.data.text;
                    this.editAccount.saved = false,
                    this.editAccount.isLoading = false;
                    this.editAccount.newPassword =  response.data.result;
                    this.getAccounts();
                } else this.$root.prompt(response.data.text);
                this.editAccount.isLoading = false;
            }).catch(error => {
                let resDataError = Object.keys(error.response.data.errors);
                // if (resDataError.filter(key => key == 'userID').length) {
                //     this.createAccount.userIDEmpty = true;
                // }
                this.$root.prompt(error.response.data.message);
                this.editAccount.isLoading = false;
            });
            
        },
        getAccounts() {
        this.gettingAccounts =true;

            axios({
                method: 'GET',
                type: 'JSON',
                url: '/accounts/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.accounts = response.data.result;
                   
                } else this.$root.prompt(response.data.text);
            this.gettingAccounts = false;

            }).catch(error => {
                this.$root.prompt(error.response.data.message);
                this.gettingAccounts = false;
            });
        },
        formatDate,

    }
}
</script>
