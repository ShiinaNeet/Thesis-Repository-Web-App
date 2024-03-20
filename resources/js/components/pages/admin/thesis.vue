<template>
    <div class="mx-5 mb-2 py-5 px-2.5 pb-2.5 bg-white rounded">
                
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
        itemSize="250px"
        class="w-full"
        >
            <template #headerAppend>
                <tr class="table-crud__slot " >
                    <th
                    v-for="key in Object.keys(createThesis.data)"
                    :key="key"
                    class="py-1 pr-1 "
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
                <div class="truncate text-wrap"
                >
                    {{ value }}
                </div>
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
                @click="editThesis.data = { ...rowData }, editThesis.modal = !editThesis.modal
                , console.log(editThesis.data)"
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
                class="p-2 flex flex-row"
                id="table-row-d h-1/2 w-2/3"
                >
                    <div class="w-1/2 max-w-1/2 h-full pr-3 rounded-lg flex flex-center justify-center">
                        <div>
                            <video class="h-1/2 w-full " v-if="rowData.video !== null" controls>
                                <source :src="rowData.video" type="video/mp4">       
                            </video> 
                            <div class="h-1/2 w-full flex flex-center justify-center">No video</div>
                        </div>
                           
                    </div>
                    <div class="w-1/2 max-w-1/2 p-3 h-full justify-center bg-blue-100 rounded-lg">
                        <h1 class="text-2xl text-wrap break-all">
                         Title: {{ rowData.title }}   
                        </h1>
                        <div class="w-fit py-3 pt-4 overflow-hidden">
                            <div class="flex lg:flex-row flex-col gap-1">
                                <h3 class="text-2xl">Author:</h3> 
                            <span v-for="authore in rowData.author" class="flex flex-wrap text-wrap items-center">
                                <VaBadge
                                        :text="authore.name"
                                        color="info"
                                        text-color="#812E9E"
                                    />
                            </span>
                            </div>
                        </div>
                        <h3>
                         Published Date: {{ formatDate(rowData.published_at,'MMM. Do YYYY', 'Invalid Date') }}   
                        </h3>
                        <VaDivider>
                            <span class="px-2 font-bold text-lg py-3">Abstract</span>
                        </VaDivider>
                        <div class="bg-white-500 w-full py-3 pt-4">
                            <p class="text-wrap break-all">
                                {{ rowData.abstract }}
                            </p>
                        </div>
                        <VaDivider>
                            <span class="px-2 py-3 font-bold text-lg">Tags</span>
                        </VaDivider>
                        <div class=" w-fit py-3 pt-4">
                            <div class="flex lg:flex-row flex-col gap-1">
                                <h2>Tags: </h2>
                                <span v-for="keywordList in rowData.keywords" class="flex items-center">
                                    <VaBadge
                                        :text="keywordList.keyword"
                                        color="warning"
                                        text-color="#812E9E"
                                    />
                                </span>
                            </div>
                        </div>
                        <VaDivider>
                            <span class="px-2 font-bold text-lg">Files</span>
                        </VaDivider>
                        <div>
                            <h2 class="pdf py-2">
                                PDF : <a 
                                            class="text-blue-500 underline py-2"
                                            :href="rowData.pdf">
                                                Download
                                            </a>
                            </h2>
                            
                        </div>
                        
                    </div>
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
            <div class="w-full p-5 flex flex-center justify-center">
                <div class="py-10">
                    <div class="va-title mb-3">
                    Add Thesis
                    </div>
                    <form action="POST">
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
                        <div class="select w-full">
                            <VaSelect
                            class="w-1/2"
                            requiredMark
                            v-model="createThesis.data.keywords"
                            label="Keywords to attach"
                            :options="keywordList"
                            text-by="keyword"
                            value-by="id"
                            multiple
                            searchable
                            clearable
                            clearable-icon="backspace"
                            @update:modelValue="createThesis.keywordsEmpty = false"
                            >
                                <template #content="{ value }">
                                    <va-chip
                                        v-for="(req, idx) in value"
                                        :key="idx"
                                        size="small"
                                        color="secondary"
                                        class="mr-1 mb-1"
                                        square
                                        closeable
                                        @update:modelValue="updateRequirementsArr('create', value)"
                                    >
                                        {{ req.keyword }}
                                    </va-chip>
                                </template>
                            </VaSelect>
                            <VaSelect
                            class="w-1/2"
                            requiredMark
                            v-model="createThesis.data.categories"
                            label="Category to attach"
                            :options="categoryList"
                            text-by="category"
                            value-by="id"
                            searchable
                            clearable
                            multiple
                            clearable-icon="backspace"
                            @update:modelValue="createThesis.categoryEmpty = false"
                            >
                                <template #content="{ value }">
                                    <va-chip
                                    v-for="(reqw, idx) in value"
                                    :key="idx"
                                    size="small"
                                    color="secondary"
                                    class="mr-1 mb-1"
                                    square
                                    closeable
                                    @update:modelValue="updateCategoryArr('create', value)"
                                    >
                                        {{ reqw.category }}
                                    </va-chip>
                                </template>
                            </VaSelect>
                            <VaSelect
                            class="w-full"
                            requiredMark
                            multiple
                            v-model="createThesis.data.authors"
                            label="Author"
                            :options="authorList"
                            text-by="name"
                            value-by="id"
                            searchable
                            clearable
                            clearable-icon="backspace"
                            @update:modelValue="createThesis.authorEmpty = false"
                            >
                                <template #content="{ value }">
                                    <va-chip
                                    v-for="(reqaa, idx) in value"
                                    :key="idx"
                                    size="small"
                                    color="secondary"
                                    class="mr-1 mb-1"
                                    square
                                    closeable
                                    @update:modelValue="updateAuthorsArr('create', idx)"
                                    >
                                        {{ reqaa.name }}
                                    </va-chip>
                                </template>
                            </VaSelect>
                        </div>
                        <VaTextarea
                        v-model="createThesis.data.abstract"
                        label="Abstract *"
                        class="w-full h-fit mb-2"
                        :autosize="true"
                        max-rows="7"
                        min-rows="7"
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
                        <input type="file" id="video" name="file"  ref="videoInput" accept="video/*" @onchange="uploadFile"/>
                        <input type="file" id="pdf" name="file"  ref="pdfInput" accept=".pdf"/>
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
                                    type="submit"
                                    >
                                        <p class="font-normal">Save</p>
                                    </va-button>
                                </div>
                            </div>
                    </form>
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
            <div class="w-full p-5 flex flex-center justify-center">
                <div class=" justify-center pt-10">
                    <div class="va-title mb-3">
                        Edit Thesis
                    </div>
                    <va-input
                    v-model="editThesis.data.title"
                    label="Title *"
                    class="w-full mb-2"
                    maxlength="120"
                    :rules="[(v) => v && v.length > 0 || 'The title field is required.']"
                    :error="editThesis.TitleEmpty"
                    :error-messages="'The title field is required.'"
                    @keyup="editThesis.TitleEmpty = false"
                    />
                    <div class="select w-full">
                        <!-- Error in EditThesis.data.keywords IDK WHY -->
                        <VaSelect
                        v-model="editThesis.data.keywords"
                        :options="keywordList"
                        text-by="keyword"
                        value-by="id"
                        requiredMark
                        class="w-1/2"
                        label="Keywords to attach"
                        multiple
                        searchable
                        clearable
                        clearable-icon="backspace"
                        :error="editThesis.keywordsEmpty"
                        :error-messages="'The requirement(s) field is required.'"
                        @update:modelValue="editThesis.keywordsEmpty = false"
                        >
                            <template #content="{ value }">
                                <va-chip
                                    v-for="(reqqs, idx) in value"
                                    :key="idx"
                                    size="small"
                                    color="secondary"
                                    class="mr-1 mb-1"
                                    square
                                    closeable
                                    @update:modelValue="updateRequirementsArr('save', idx), editThesis.key = 12"
                                >
                                    {{ reqqs.keyword }}
                                </va-chip>
                            </template>
                        </VaSelect>
                        <VaSelect
                        v-model="editThesis.data.categories"
                        :options="categoryList"
                        text-by="category"
                        value-by="id"
                        requiredMark
                        class="w-1/2"
                        label="Category to attach"
                        multiple
                        searchable
                        clearable-icon="backspace"
                        :error="editThesis.categoryEmpty"
                        :error-messages="'The category(s) field is required.'"
                        @update:modelValue="editThesis.categoryEmpty = false"
                        >
                            <template #content="{ value }">
                                <VaChip
                                v-for="(ww, idx) in value"
                                :key="idx"
                                size="small"
                                color="secondary"
                                class="mr-1 mb-1"
                                square
                                closeable
                                @update:modelValue="updateCategoryArr('save', idx), editThesis.cate = 12"
                                >
                               
                                {{ ww.category }}
                             
                                </VaChip>

                            </template>
                        </VaSelect>
                        <VaSelect
                        v-model="editThesis.data.authors"
                        :options="authorList"
                        text-by="name"
                        value-by="id"
                        requiredMark
                        class="w-full"
                        label="Author"
                        multiple
                        searchable
                        clearable
                        clearable-icon="backspace"
                        @update:modelValue="editThesis.authr = 1"
                        >
                            <template #content="{ value }">
                                <VaChip
                                v-for="(author, id) in value"
                                :key="idx"
                                size="small"
                                color="secondary"
                                class="mr-1 mb-1"
                                square
                                closeable
                                >
                                   {{ author.name }}
                                  
                                </VaChip>
                            </template>
                        </VaSelect>
                    </div>
                    <VaTextarea
                    v-model="editThesis.data.abstract"
                    label="Abstract *"
                    class="w-full mb-2"
                    :autosize="true"
                    max-rows="7"
                    min-rows="7"
                    :rules="[(v) => v && v.length > 0 || 'The abstract field is required.']"
                    :error="editThesis.abstractEmpty"
                    :error-messages="'The abstract field is required.'"
                    @keyup="editThesis.abstractEmpty = false"
                    />
                    <VaDateInput
                    v-model="editThesis.data.published_at"
                    label="Published date *"
                    preset="bordered"
                    class="w-full mb-2"
                    :format="formatDatePicker"
                    :rules="[(v) => v && v.length > 0 || 'The publish date field is required.']"
                    :error="editThesis.abstractEmpty"
                    :error-messages="'The publish date field is required.'"
                    @keyup="editThesis.publishedDateEmpty = false"
                    />
                    <input type="file" id="video"  ref="videoInput" accept="video/*" @onchange="uploadFile"> {{ editThesis.video }}</input>
                    <input type="file" id="pdf" name="file"  ref="pdfInput" accept=".pdf"/>
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
                
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.deleteModal"
    @cancel="editThesis.data = { ...createThesis.resetData }"
    noPadding
    >
        <template #content>
            <div class="w-full p-5">
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
            <div class="w-full p-5">
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
    //--va-form-element-default-width: 80%;

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
import { VaChip } from 'vuestic-ui/web-components';
const now = () => new Date();
const newThesis = {
    title:null,
    abstract:null,
    published_at: formatDate(now(),'yyyy-MM-dd'),
    id: null,
    categories:[], 
    keywords:[],
    authors:[],
};
console.log(now());
export default {
    data() {
        const keyconfig = {
            tblColumns: [
                { key: "title", label: "Title", width: "50%", sortable: true },
                { key: "abstract", label: "Abstract", width: "25%", sortable: false },
                { key: "published_at", label: "Published Date", width: "20%", sortable: true },
                { key: "id", label: "Action", width: "5%", sortable: false }
            ]
        };
        return {
            test: { keywords: {}, },
            files: [],
            uploadFiles: [],
            showProgress: false,
            keyconfig,
            thesisList: [],
            keywordList: [],
            categoryList: [],
            authorList: [],
            createThesis: {
                modal: false,
                TitleEmpty: false,
                AbstractEmpty: false,
                PublishedDateEmpty: false,
                keywordsEmpty: false,
                categoryEmpty: false,
                authorEmpty: false,
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
                keywordsEmpty: false,
                categoryEmpty: false,
                authorEmpty: false,
                PublishedDateEmpty: false,
                keywordsEmpty: false,
                saved: false,
                data: {},
                cate: 0,
                key: 0,
                authr:0,
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
    mounted() {
        this.getThesis();
        this.getKeywords();
        this.getCategory();
        this.getAuthor();
    },
    methods: {
        updateKeywordIds() {
            if (Array.isArray(this.editThesis.data.keywords)) {
            this.editThesis.data.keywords = this.editThesis.data.keywords.map(keywords => keywords.id);
            }
            
        },
        updateCategoryIds() {
            if (Array.isArray(this.editThesis.data.categories)) {
            this.editThesis.data.categories = this.editThesis.data.categories.map(categories => categories.id);
            }
           
        },
        updateAuthorIds() {
            console.log(this.editThesis.data.authors);
            if (Array.isArray(this.editThesis.data.authors)) {
            this.editThesis.data.authors = this.editThesis.data.authors.map(authors => authors.id);
            }
            
        },
        updateRequirementsArr(method, selectedKeywords) {
            if (method !== 'create' || method !== 'save') {
                method === 'create' ? (this.createThesis.data.keywords =
                    this.createThesis.data.keywords.filter((v) => v !== this.createThesis.data.keywords[selectedKeywords]))
                    : (method === 'save' && (  this.editThesis.data.authors =
                        this.editThesis.data.keywords.filter((v) => v !== this.editThesis.data.keywords[selectedKeywords])));
                   
            }
            else
                this.editThesis.key = 1;
                this.$root.prompt();
        },
        updateAuthorsArr(method, selectedAuthors) {
            if (method !== 'create' || method !== 'save') {
                if(method === 'create') {
                    this.createThesis.data.authors =
                        this.createThesis.data.authors.filter((v) => v !== this.createThesis.data.authors[selectedAuthors]);
                    console.log(this.createThesis.data.authors);
                 }
                 else{
                    
                    this.editThesis.data.authors = selectedAuthors.map(author => author.id);
                         this.editThesis.authr = 1;
                }
               
            }
            else
                this.$root.prompt();
        },
        updateCategoryArr(method, selectedCategories) {
            if (method !== 'create' || method !== 'save') {
                if(method === 'create') (this.createThesis.data.categories = this.createThesis.data.categories.filter((v) => v !== this.createThesis.data.category[idx]))
                else { 
                    if (method === 'save'){
                        this.editThesis.data.categories =
                        this.editThesis.data.categories.filter((v) => v !== this.editThesis.data.categories[selectedCategories]);
                        this.editThesis.cate = 1;
                    }
                }
               
            }
            else
                this.$root.prompt();
        },
        uploadFile(event) {
            const file = event.target.files[0];
        },
        handleButtonClick() {
            this.editThesis.saved = true;
            if (this.editThesis.data.deleted_at === null) {
                this.toggleHelpStatus();
            }
            else {
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
                }
                else
                    this.$root.prompt(response.data.text);
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
                }
                else
                    this.$root.prompt(response.data.text);
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
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        insertUpdateThesis(method) {
            if (method !== 'create' || method !== 'save') {
                let formData = new FormData();
                // Append other form data
                if (method == 'create') {
                    formData.append('title', this.createThesis.data.title);
                    formData.append('abstract', this.createThesis.data.abstract);
                    formData.append('published_at', this.createThesis.data.published_at);
                    let videofile = this.$refs.videoInput.files[0];
                    let pdffile = this.$refs.pdfInput.files[0];
                    formData.append('authors', JSON.stringify(this.createThesis.data.authors));
                    formData.append('categories', JSON.stringify(this.createThesis.data.categories));
                    formData.append('keywords', JSON.stringify(this.createThesis.data.keywords));
                    formData.append('video', videofile);
                    formData.append('pdf', pdffile);
                }
                else {
                    formData.append('id', this.editThesis.data.id);
                    formData.append('title', this.editThesis.data.title);
                    formData.append('abstract', this.editThesis.data.abstract);
                    let videofile = this.$refs.videoInput.files[0];
                    let pdffile = this.$refs.pdfInput.files[0];
                    var newDate = this.formatDate(this.editThesis.data.published_at, 'YYYY-MM-DD');
                    formData.append('published_at',newDate); 
                   
                    if(this.editThesis.key == 0)
                    {
                        this.updateKeywordIds();
                    }
                    if(this.editThesis.authr == 0)
                    {
                        this.updateAuthorIds();
                    }
                    if(this.editThesis.cate == 0)
                    {
                        this.updateCategoryIds();
                    }
                   
                    
                    formData.append('authors', JSON.stringify(this.editThesis.data.authors));
                    formData.append('categories', JSON.stringify(this.editThesis.data.categories));
                    formData.append('keywords', JSON.stringify(this.editThesis.data.keywords));
                    formData.append('video', videofile);
                    formData.append('pdf', pdffile);
                }
                axios({
                    method: 'POST',
                    url: '/thesis/save',
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData,
                }).then(response => {
                    if (response.data.status == 1) {
                        this.$root.prompt(response.data.text);
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    else {
                        this.editThesis.saved = false;
                        this.$root.prompt(response.data.message);
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    this.getThesis();
                }).catch(error => {
                    // this.$root.prompt(error.response.data.message);
                    this.editThesis.saved = false;
                    this.editThesis.modal = false;
                    this.getThesis();
                    let resDataError = Object.keys(error.response.data.errors);
                    if (resDataError.filter(key => key == 'message').length > 0) {
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    if (resDataError.filter(key => key == 'title').length) {
                        method === 'create' ? this.createThesis.TitleEmpty = true
                            : (method === 'save' && (this.editThesis.TitleEmpty = true));
                    }
                    if (resDataError.filter(key => key == 'abstract').length) {
                        method === 'create' ? this.createThesis.AbstractEmpty = true
                            : (method === 'save' && (this.editThesis.AbstractEmpty = true));
                    }
                    if (resDataError.filter(key => key == 'published_at').length) {
                        method === 'create' ? this.createThesis.PublishedDateEmpty = true
                            : (method === 'save' && (this.editThesis.PublishedDateEmpty = true));
                    }
                    method === 'create' ? this.createThesis.saved = false
                        : (method === 'save' && (this.editThesis.saved = false));
                    this.getThesis();    
                });
            }
            else
                this.$root.prompt();
        },
        getThesis() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/thesis/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.thesisList = response.data.result;
                    
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        getKeywords() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/keyword/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.keywordList = response.data.result;
                    console.log(this.keywordList);
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        getCategory() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/category/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.categoryList = response.data.result;
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        getAuthor() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/author/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.authorList = response.data.result;
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDatePicker(date) {
            return this.formatDate(date, 'YYYY-MM-DD', 'Invalid Date');
           
        },
        formatDate,
    },
    components: { VaChip }
}
</script>
