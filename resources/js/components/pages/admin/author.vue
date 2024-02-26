<template>
    <div class="mx-5 mb-2 px-2.5 pb-2.5 bg-white rounded">
        <va-data-table
        id="data-table"
        :items="authorList"
        :columns="keyconfig.tblColumns"
        :per-page="$root.config.tblPerPage"
        :current-page="$root.config.tblCurrPage"
        no-data-html="No Keyword(s) to show"
        :filter="filter"
        @filtered="filtered = $event.items"
        animated
        >
            <template #headerAppend>
                <tr class="table-crud__slot">
                    <th
                    v-for="column in keyconfig.tblColumns"
                    :key="column.key"
                    :style="{ width: column.width }"
                    class="py-1 pr-1"
                    >
                        <div v-if="column.key === 'name'">
                            <va-input
                                v-model="filter"
                                placeholder="Search..."
                                class="w-full"
                            >
                                <template #appendInner>
                                    <va-icon name="search" color="#C2C2C2" />
                                </template>
                            </va-input>
                        </div>
                        <div v-else-if="column.key === 'id'" class="flex justify-end">
                            <va-button
                                preset="primary"
                                icon="post_add"
                                class="flex"
                                @click="createKeyword.modal = !createKeyword.modal"
                            >
                                Add
                            </va-button>
                        </div>
                    </th>
                </tr>
            </template>

            <template #cell(name)="{ value }">
                {{ value }}
            </template>
           
            <template #cell(created_at)="{ value }">
                {{ formatDate(value, 'MMM. Do YYYY', 'Invalid Date') }}
            </template>
            <template #cell(id)="{ rowData }">
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Edit"
                preset="plain"
                icon="edit"
                @click="editKeyword.data = { ...rowData }, editKeyword.modal = !editKeyword.modal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Toggle Status"
                preset="plain"
                :icon="rowData.deleted_at ? 'lock' : 'lock_open'"
                
                @click="editKeyword.data = { ...rowData }, editKeyword.statusModal = !editKeyword.statusModal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Delete"
                preset="plain"
                icon="delete"
                :disabled="rowData.deleted_at"
                @click="editKeyword.data = { ...rowData }, editKeyword.deleteModal = !editKeyword.deleteModal"
                />
            </template>

            <template #bodyAppend>
                <tr v-if="$root.tblPagination(authorList)">
                    <td
                    id="pagination"
                    :colspan="keyconfig.tblColumns.length"
                    >
                        <div class="flex pt-[10px] select-none">
                            <va-pagination
                            class="justify-center"
                            v-model="$root.config.tblCurrPage"
                            :pages="filter == '' ? $root.tblPagination(authorList) : (pages, $root.config.tblCurrPage = 1)"
                            input
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </va-data-table>
    </div>


    <va-modal
    v-model="createKeyword.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Add author
                </div>
                <va-input
                v-model="createKeyword.data.name"
                label="author *"
                class="w-full mb-2"
                maxlength="120"
                :rules="[(v) => v && v.length > 0 || 'The author field is required.']"
                :error="createKeyword.keywordEmpty"
                :error-messages="'The author field is required.'"
                @keyup="createKeyword.keywordEmpty = false"
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="
                            createKeyword.data = { ...createKeyword.resetData },
                            createKeyword.keywordEmpty = false,
                            createKeyword.saved = false,
                            createKeyword.modal = !createKeyword.modal
                        "
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="save"
                        :loading="createKeyword.saved"
                        :disabled="createKeyword.saved"
                        @click="createKeyword.saved = true, insertUpdateKeyword('create')"
                        >
                            <p class="font-normal">Save</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editKeyword.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Edit Help
                </div>
                <va-input
                v-model="editKeyword.data.author"
                label="author *"
                class="w-full mb-2"
                maxlength="120"
                :rules="[(v) => v && v.length > 0 || 'The keyword field is required.']"
                :error="editKeyword.keywordEmpty"
                @keyup="editKeyword.keywordEmpty = false"
                />
                
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="
                            editKeyword.data = { ...createKeyword.resetData },
                        
                            editKeyword.keywordEmpty = false,
                            editKeyword.saved = false,
                            editKeyword.modal = !editKeyword.modal
                        ">
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="save"
                        :loading="editKeyword.saved"
                        :disabled="editKeyword.saved"
                        @click="editKeyword.saved = true, insertUpdateKeyword('save')"
                        >
                            <p class="font-normal">Save</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editKeyword.deleteModal"
    @cancel="editKeyword.data = { ...createKeyword.resetData }"
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Delete author
                </div>
                <va-alert color="warning">
                    <template #icon>
                        <va-icon name="warning" />
                    </template>
                    This action is irreversible
                </va-alert>
                <va-input
                type="textarea"
                :model-value="editKeyword.data.author"
                class="w-full mb-2 force-noresize"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editKeyword.data = { ...createKeyword.resetData }, editKeyword.deleteModal = !editKeyword.deleteModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="check"
                        :loading="editKeyword.saved"
                        :disabled="editKeyword.saved"
                        @click="editKeyword.saved = true, deleteKeyword()"
                        >
                            <p class="font-normal">Confirm</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editKeyword.statusModal"
    @cancel="editKeyword.data = { ...createKeyword.resetData }"
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    author Status
                </div>
                
                <va-input
                type="textarea"
                :model-value="editKeyword.data.author"
                class="w-full mb-2 force-noresize"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editKeyword.data = { ...createKeyword.resetData }, editKeyword.statusModal = !editKeyword.statusModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        :icon="!editKeyword.data.deleted_at ? 'lock' : 'lock_open'"
                        :loading="editKeyword.saved"
                        :disabled="editKeyword.saved"
                        @click="editKeyword.saved = true,handleButtonClick()"
                        >
                            <p class="font-normal">{{ !editKeyword.data.deleted_at ? "Deactivate" : "Activate" }}</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>
</template>

<style lang="scss" scoped>
.table-crud {
    --va-form-element-default-width: 80%;

    .va-input {
        display: block;
    }

    &__slot {
        th {
            vertical-align: middle;
            width: max-content;
        }
    }
}
</style>

<script>

import formatDate from '@/functions/formatdate.js';

const newKeyword = {
    name: null,
    id: null,
};

export default {
    data () {
        const keyconfig = {
            tblColumns: [
                { key: "name", label: "Author", width: "50%", sortable: true },
                {key: "created_at", label: "Created On", width: 125, sortable: true },
                { key: "id", label: "Action", width: 60, sortable: false }
            ]
        };

        return {
            keyconfig,
            authorList: [],
            author: {},
            createKeyword: {
                modal: false,
                keywordEmpty: false,
                saved: false,
                resetData: { ...newKeyword },
                data: { ...newKeyword }
            },
            editKeyword: {
                modal: false,
                deleteModal: false,
                statusModal: false,
                keywordEmpty: false,
                saved: false,
                data: {}
            },
            activePreviewRow: null,
            filtered: null,
            filter: ""
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
        this.getKeywords();
    },
    methods: {
        handleButtonClick() {
            this.editKeyword.saved = true;

            if (this.editKeyword.data.deleted_at === null) {
                this.toggleHelpStatus();
            } else {
                this.enableHelpStatus();
            }
        },
        toggleHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/author/disable',
                data: { id: this.editKeyword.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editKeyword.data = { ...newKeyword };
                    this.editKeyword.statusModal = false;
                    this.editKeyword.saved = false;

                    this.getKeywords();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        enableHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/author/enable',
                data: { id: this.editKeyword.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editKeyword.data = { ...newKeyword };
                    this.editKeyword.statusModal = false;
                    this.editKeyword.saved = false;

                    this.getKeywords();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        deleteKeyword() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/author/delete',
                data: { id: this.editKeyword.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editKeyword.data = { ...newKeyword };
                    this.editKeyword.deleteModal = false;
                    this.editKeyword.saved = false;

                    this.getKeywords();

                    this.activePreviewRow && this.activePreviewRow.toggleRowDetails(false);
                    this.activePreviewRow = null;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        insertUpdateKeyword(method) {
            if (method !== 'create' || method !== 'save') {
                axios({
                    method: 'POST',
                    type: 'JSON',
                    url: '/author/save',
                    data: method === 'create' ? this.createKeyword.data : (method === 'save' && this.editKeyword.data)
                }).then(response => {
                    if (response.data.status == 1) {
                        this.$root.prompt(response.data.text);
                        method === 'create' ? (
                            this.createKeyword.data = { ...newKeyword },
                            this.createKeyword.modal = false,
                            this.createKeyword.keywordEmpty = false,
                    
                            this.createKeyword.saved = false
                        )
                        : (
                            method === 'save' && (
                                this.editKeyword.data = {},
                                this.editKeyword.modal = false,
                                this.editKeyword.keywordEmpty = false,
                            
                                this.editKeyword.saved = false
                            )
                        );

                        this.getKeywords();
                    } else this.$root.prompt(response.data.text);
                }).catch(error => {
                    // this.$root.prompt(error.response.data.message);
                    let resDataError = Object.keys(error.response.data.errors);

                    if (resDataError.filter(key => key == 'name').length) {
                        method === 'create' ? this.createKeyword.keywordEmpty = true
                        : (
                            method === 'save' && (this.editKeyword.keywordEmpty = true)
                        );
                    }
                   

                    method === 'create' ? this.createKeyword.saved = false
                    : (method === 'save' && (this.editKeyword.saved = false));
                });
            } else this.$root.prompt();
        },
        getKeywords() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/author/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.authorList = response.data.result;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDate,
    },
    
}
</script>
