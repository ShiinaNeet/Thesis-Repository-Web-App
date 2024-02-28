<template>
    <div class="mx-5 mb-2 px-2.5 pb-2.5 bg-white rounded">
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
        >
            <template #headerAppend>
                    <tr class="table-crud__slot">
                        <th
                        v-for="key in Object.keys(createAccount.data)"
                        :key="key"
                        class="py-1 pr-1"
                        >
                            <va-input
                            v-if="key.includes('userID') && !fmode"
                            v-model="filter"
                            placeholder="Search..."
                            >
                                <template #appendInner>
                                    <va-icon
                                    name="userID"
                                    title="Add Account"
                                    color="#C2C2C2"
                                    @click="fmode = 1"
                                    />
                                </template>
                            </va-input>
                            <va-input
                            v-if="key.includes('userID') && fmode"
                            v-model="createAccount.data[key]"
                            type="userID"
                            :placeholder="acc.createNew[$root.arrayFind(acc.createNew, item => item.key === key)].label + ' *'"
                            :error="createAccount.userIDEmpty"
                            @keyup="createAccount.userIDEmpty = false"
                            >
                                <template #appendInner>
                                    <va-icon
                                    name="search"
                                    title="Search Account"
                                    color="#154EC1"
                                    @click="fmode = 0"
                                    />
                                </template>
                            </va-input>
                            <va-select
                            class="font-normal"
                            v-if="key.includes('user_type') && fmode"
                            v-model="createAccount.data[key]"
                            :placeholder="acc.createNew[$root.arrayFind(acc.createNew, item => item.key === key)].label + ' *'"
                            :options="key.includes('user_type') && acc.types.filter(item => item.label !== 'Student')"
                            :error="createAccount.userTypeEmpty"
                            @update:modelValue="createAccount.userTypeEmpty = false"
                            :text-by="key.includes('user_type') && 'label'"
                            :value-by="key.includes('user_type') && 'value'"
                            clearable
                            clearable-icon="backspace"
                            />
                            <va-button
                            v-if="key.includes('id') && fmode"
                            preset="primary"
                            icon="person_add"
                            :loading="createAccount.saved"
                            :disabled="!createAccount.saved && (createAccount.data.userID == '' && createAccount.data.user_type == '')"
                            @click="createAccount.saved = true, insertUpdateAccount('create')"
                            >
                                Add
                            </va-button>
                        </th>
                    </tr>
            </template>
            <template #cell(user_type)="{ value }">
                {{ acc.types[value].label }}
            </template>
            <template #cell(deleted_at)="{ value }">
                <va-badge
                :text="value ? 'Inactive' : 'Active'"
                :color="value ? 'warning' : 'success'"
                />
            </template>
            <template #cell(created_at)="{ value }">
                {{ formatDate(value, 'MMM. Do YYYY', 'Invalid Date') }}
            </template>
            <template #cell(id)="{ rowData }">
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                :class="rowData.id === $root.auth.userID ? 'opacity-[0!important]' : ''"
                title="Edit"
                preset="plain"
                icon="edit"
                :disabled="rowData.deleted_at || rowData.id === $root.auth.userID ? true : false"
                @click="editAccount.data = { ...rowData }, editAccount.modal = !editAccount.modal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                :class="rowData.id === $root.auth.userID ? 'opacity-[0!important]' : ''"
                title="Toggle Status"
                preset="plain"
                :icon="rowData.deleted_at ? 'lock' : 'lock_open'"
                :disabled="rowData.deleted_at || rowData.id === $root.auth.userID ? true : false"
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
</template>



<script>



export default {
    data () {
        const acc = {
            tblColumns: [
                { key: "userID", label: "User ID", sortable: true },
                { key: "user_type", label: "Type", width: 180, sortable: false },
                { key: "deleted_at", label: "Status", width: 50, sortable: false },
                { key: "created_at", label: "Register Date", width: 125, sortable: true },
                { key: "id", label: "Action", width: 60, sortable: false }
            ],
            createNew: [
                { key: "userID", label: "UserID" },
                { key: "user_type", label: "Type" },
                { key: "deleted_at", label: "Status" },
                { key: "created_at", label: "Register Date" },
                { key: "id", label: "Action" },
            ],
            types: [
                { label: "Administrator", value: 0 },
                { label: "Librarian", value: 1 },
                { label: "AssistantLibrarian", value: 2 },
                { label: "Student", value: 3 }
            ]
        };

        return {
            acc,
            accounts: [],
            account: {},
            createAccount: {
                userIDEmpty: false,
                userTypeEmpty: false,
                saved: false,
            },
            editAccount: {
                modal: false,
                statusModal: false,
                saved: false,
                data: {}
            },
            filtered: null,
            filter: "",
            fmode: 1
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
        insertUpdateAccount(method) {
            if (method !== 'create' || method !== 'save') {
                axios({
                    method: 'POST',
                    type: 'JSON',
                    url: '/account/save',
                    data: method === 'create' ? this.createAccount.data : (method === 'save' && this.editAccount.data)
                }).then(response => {
                    if (response.data.status == 1) {
                        this.$root.prompt(response.data.text);
                        method === 'create' ? (
                            this.createAccount.data = { ...newAccount },
                            this.createAccount.saved = false
                        )
                        : (method === 'save' && (
                                this.editAccount.data = { ...newAccount },
                                this.editAccount.modal = false,
                                this.editAccount.saved = false
                            )
                        );

                        this.getAccounts();
                    } else this.$root.prompt(response.data.text);
                }).catch(error => {
                    if (method === 'create') {
                        let resDataError = Object.keys(error.response.data.errors);

                        if (resDataError.filter(key => key == 'userID').length) {
                            this.createAccount.userIDEmpty = true;
                        }
                        if (resDataError.filter(key => key == 'user_type').length) {
                            this.createAccount.userTypeEmpty = true;
                        }
                        this.$root.prompt(error.response.data.message);
                    }

                    method === 'create' ? this.createAccount.saved = false
                    : (method === 'save' && (this.editAccount.saved = false));
                });
            } else this.$root.prompt();
        },
        getAccounts() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/accounts/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.accounts = response.data.result;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        }
    }
}
</script>
