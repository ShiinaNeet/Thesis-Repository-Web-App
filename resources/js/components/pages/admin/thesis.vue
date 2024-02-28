<template>
    <div class="mx-5 mb-2 px-2.5 pb-2.5 bg-white rounded">
        <va-data-table
        id="data-table"
        :items="thesisList"
        :columns="keyconfig.tblColumns"
        :per-page="$root.config.tblPerPage"
        :current-page="$root.config.tblCurrPage"
        no-data-html="No Thesis(s) to show"
        :filter="filter"
        @filtered="filtered = $event.items"
        animated
        >
            <template #headerAppend>
                <tr class="table-crud__slot">
                    <th
                    v-for="key in Object.keys(createThesis.data)"
                    :key="key"
                    class="py-1 pr-1"
                    >
                        <va-input
                        v-if="key.includes('title')"
                        v-model="filter"
                        placeholder="Search..."
                        class="w-full"
                        >
                            <template #appendInner>
                                <va-icon name="search" color="#C2C2C2" />
                            </template>
                        </va-input>
                    
                        <va-button
                        v-if="key.includes('id')"
                        preset="primary"
                        icon="post_add"
                        class="m-0 p-0 align-content w-full"
                        @click="createThesis.modal = !createThesis.modal"
                        >
                            Add
                        </va-button>
                    </th>
                </tr>
            </template>

            <template #cell(title)="{ value }">
                {{ value }}
            </template>
           
            <template #cell(published_at)="{ value }">
                {{ formatDate(value, 'MMM. Do YYYY', 'Invalid Date') }}
            </template>
            <template #cell(id)="{ rowData }">
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Edit"
                preset="plain"
                icon="edit"
                @click="editThesis.data = { ...rowData }, editThesis.modal = !editThesis.modal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Toggle Status"
                preset="plain"
                :icon="rowData.deleted_at ? 'lock' : 'lock_open'"
                
                @click="editThesis.data = { ...rowData }, editThesis.statusModal = !editThesis.statusModal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Delete"
                preset="plain"
                icon="delete"
                :disabled="rowData.deleted_at"
                @click="editThesis.data = { ...rowData }, editThesis.deleteModal = !editThesis.deleteModal"
                />
            </template>
            <template #cell(abstract)="{ row, isExpanded }">
                <va-button
                class="w-full"
                preset="secondary"
                size="small"
                :icon="isExpanded ? 'va-arrow-up': 'va-arrow-down'"
                @click="row.toggleRowDetails(), activePreviewRow = row"
                >
                    {{ !isExpanded ? 'Show': 'Hide' }}
                </va-button>
            </template>
            <template #expandableRow="{ rowData }">
                <div
                class="p-2"
                id="table-row-desc"
                >
                    <va-input
                    type="textarea"
                    :model-value="rowData.abstract"
                    placeholder="No description available"
                    readonly
                    autosize
                    outline
                    />
                </div>
            </template>
            <template #bodyAppend>
                <tr v-if="$root.tblPagination(thesisList)">
                    <td
                    id="pagination"
                    :colspan="keyconfig.tblColumns.length"
                    >
                        <div class="flex pt-[10px] select-none">
                            <va-pagination
                            class="justify-center"
                            v-model="$root.config.tblCurrPage"
                            :pages="filter == '' ? $root.tblPagination(thesisList) : (pages, $root.config.tblCurrPage = 1)"
                            input
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </va-data-table>
    </div>


    <va-modal
    v-model="createThesis.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Add Thesis
                </div>
                <va-input
                v-model="createThesis.data.title"
                label="Title *"
                class="w-full mb-2"
                maxlength="120"
                :rules="[(v) => v && v.length > 0 || 'The title field is required.']"
                :error="createThesis.TitleEmpty"
                :error-messages="'The title field is required.'"
                @keyup="createThesis.TitleEmpty = false"
                />
                <va-input
                v-model="createThesis.data.abstract"
                label="Abstract *"
                class="w-full mb-2"
                maxlength="500"
                :rules="[(v) => v && v.length > 0 || 'The abstract field is required.']"
                :error="createThesis.abstractEmpty"
                :error-messages="'The abstract field is required.'"
                @keyup="createThesis.AbstractEmpty = false"
                />
                <va-input
                v-model="createThesis.data.published_at"
                label="Publish date *"
                type="date"
                class="w-full mb-2"
                maxlength="500"
                :rules="[(v) => v && v.length > 0 || 'The publish date field is required.']"
                :error="createThesis.abstractEmpty"
                :error-messages="'The publish date field is required.'"
                @keyup="createThesis.PublishedDateEmpty = false"
                />
                <input type="file" id="video" name="filename" />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="
                            createThesis.data = { ...createThesis.resetData },
                            createThesis.TitleEmpty = false,
                            createThesis.AbstractEmptyEmpty = false,
                            createThesis.PublishedDateEmpty = false,
                            createThesis.saved = false,
                            createThesis.modal = !createThesis.modal
                        "
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="save"
                        :loading="createThesis.saved"
                        :disabled="createThesis.saved"
                        @click="createThesis.saved = true, insertUpdateThesis('create')"
                        >
                            <p class="font-normal">Save</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Edit Help
                </div>
                <va-input
                v-model="createThesis.data.title"
                label="Title *"
                class="w-full mb-2"
                maxlength="120"
                :rules="[(v) => v && v.length > 0 || 'The title field is required.']"
                :error="editThesis.TitleEmpty"
                :error-messages="'The title field is required.'"
                @keyup="editThesis.TitleEmpty = false"
                />
                <va-input
                v-model="editThesis.data.abstract"
                label="Abstract *"
                class="w-full mb-2"
                maxlength="500"
                :rules="[(v) => v && v.length > 0 || 'The abstract field is required.']"
                :error="editThesis.abstractEmpty"
                :error-messages="'The abstract field is required.'"
                @keyup="editThesis.abstractEmpty = false"
                />
                <va-input
                v-model="editThesis.data.published_at"
                label="Publish date *"
                type="date"
                class="w-full mb-2"
                maxlength="500"
                :rules="[(v) => v && v.length > 0 || 'The publish date field is required.']"
                :error="editThesis.abstractEmpty"
                :error-messages="'The publish date field is required.'"
                @keyup="editThesis.publishedDateEmpty = false"
                />
                
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="
                            editThesis.data = { ...createThesis.resetData },
                        
                            editThesis.TitleEmpty = false,
                            editThesis.abstractEmptyEmpty = false,
                            editThesis.publishedDateEmpty = false,
                            editThesis.saved = false,
                            editThesis.modal = !editThesis.modal
                        ">
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="save"
                        :loading="editThesis.saved"
                        :disabled="editThesis.saved"
                        @click="editThesis.saved = true, insertUpdateThesis('save')"
                        >
                            <p class="font-normal">Save</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.deleteModal"
    @cancel="editThesis.data = { ...createThesis.resetData }"
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Delete Thesis
                </div>
                <va-alert color="warning">
                    <template #icon>
                        <va-icon name="warning" />
                    </template>
                    This action is irreversible
                </va-alert>
                <va-input
                type="textarea"
                :model-value="editThesis.data.title"
                class="w-full mb-2 force-noresize"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editThesis.data = { ...createThesis.resetData }, editThesis.deleteModal = !editThesis.deleteModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="check"
                        :loading="editThesis.saved"
                        :disabled="editThesis.saved"
                        @click="editThesis.saved = true, deleteThesis()"
                        >
                            <p class="font-normal">Confirm</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.statusModal"
    @cancel="editThesis.data = { ...createThesis.resetData }"
    noPadding
    >
        <template #content>
            <div class="w-[410px] p-5">
                <div class="va-title mb-3">
                    Thesis Status
                </div>
                
                <va-input
                type="textarea"
                :model-value="editThesis.data.title"
                class="w-full mb-2 force-noresize"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editThesis.data = { ...createThesis.resetData }, editThesis.statusModal = !editThesis.statusModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        :icon="!editThesis.data.deleted_at ? 'lock' : 'lock_open'"
                        :loading="editThesis.saved"
                        :disabled="editThesis.saved"
                        @click="editThesis.saved = true,handleButtonClick()"
                        >
                            <p class="font-normal">{{ !editThesis.data.deleted_at ? "Deactivate" : "Activate" }}</p>
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
const now = () => new Date();
const newThesis = {
    title:null,
    abstract:null,
    published_at: formatDate(now()),
    id: null,
};
console.log(now());
export default {
    data () {
        const keyconfig = {
            tblColumns: [
                { key: "title", label: "Title", width: "50%", sortable: true },
                {key: "abstract", label: "Abstract", width: 125, sortable: false },
                {key: "published_at", label: "Published Date", width: 125, sortable: true },
                { key: "id", label: "Action", width: "5%", sortable: false }
            ]
        };

        return {
            keyconfig,
            thesisList: [],
            keyword: {},
            createThesis: {
                modal: false,
                TitleEmpty: false,
                AbstractEmpty: false,
                PublishedDateEmpty: false,
                saved: false,
                resetData: { ...newThesis },
                data: { ...newThesis }
            },
            editThesis: {
                modal: false,
                deleteModal: false,
                statusModal: false,
                TitleEmpty: false,
                AbstractEmpty: false,
                PublishedDateEmpty: false,
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
        this.getThesis();
    },
    methods: {
        handleButtonClick() {
            this.editThesis.saved = true;

            if (this.editThesis.data.deleted_at === null) {
                this.toggleHelpStatus();
            } else {
                this.enableHelpStatus();
            }
        },
        toggleHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/disable',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.statusModal = false;
                    this.editThesis.saved = false;

                    this.getThesis();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        enableHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/enable',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.statusModal = false;
                    this.editThesis.saved = false;

                    this.getThesis();
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        deleteThesis() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/delete',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.deleteModal = false;
                    this.editThesis.saved = false;

                    this.getThesis();

                    this.activePreviewRow && this.activePreviewRow.toggleRowDetails(false);
                    this.activePreviewRow = null;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        insertUpdateThesis(method) {
            if (method !== 'create' || method !== 'save') {
                let formData = new FormData();
                formData.append("video",this.)
                axios({
                    method: 'POST',
                    type: 'JSON',
                    url: '/thesis/save',
                    data: method === 'create' ? this.createThesis.data : (method === 'save' && this.editThesis.data)
                }).then(response => {
                    if (response.data.status == 1) {
                        this.$root.prompt(response.data.text);
                        method === 'create' ? (
                            this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,

                            this.createThesis.saved = false
                        )
                        : (
                            method === 'save' && (
                                this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                            
                                this.editThesis.saved = false
                            )
                        );

                        this.getThesis();
                    } else this.$root.prompt(response.data.text);
                }).catch(error => {
                    // this.$root.prompt(error.response.data.message);
                    let resDataError = Object.keys(error.response.data.errors);

                    if (resDataError.filter(key => key == 'title').length) {
                        method === 'create' ? this.createThesis.TitleEmpty = true
                        : (
                            method === 'save' && (this.editThesis.TitleEmpty = true)
                        );
                    }
                    if (resDataError.filter(key => key == 'abstract').length) {
                        method === 'create' ? this.createThesis.AbstractEmpty = true
                        : (
                            method === 'save' && (this.editThesis.AbstractEmpty = true)
                        );
                    }
                    if (resDataError.filter(key => key == 'published_at').length) {
                        method === 'create' ? this.createThesis.PublishedDateEmpty = true
                        : (
                            method === 'save' && (this.editThesis.PublishedDateEmpty = true)
                        );
                    }
                   

                    method === 'create' ? this.createThesis.saved = false
                    : (method === 'save' && (this.editThesis.saved = false));
                });
            } else this.$root.prompt();
        },
        getThesis() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/thesis/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.thesisList = response.data.result;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDate,
    },
    
}
</script>
