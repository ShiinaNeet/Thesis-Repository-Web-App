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
                { key: "email", label: "E-mail Address" },
                { key: "user_type", label: "Type" },
                { key: "deleted_at", label: "Status" },
                { key: "created_at", label: "Register Date" },
                { key: "id", label: "Action" },
            ],
            types: [
                { label: "Student", value: 0 },
                { label: "Instructor", value: 1 },
                { label: "Secretary", value: 2 },
                { label: "Administrator", value: 3 }
            ]
        };

        return {
            acc,
            accounts: [],
            account: {},
            createAccount: {
                emailEmpty: false,
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

                        if (resDataError.filter(key => key == 'email').length) {
                            this.createAccount.emailEmpty = true;
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
