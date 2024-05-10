<template>
    <div class="py-5 ">
        <div class="h-[250px] lg:w-full sm:w-full flex justify-center">
            <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3 flex items-center justify-center">
                <div class="w-full flex flex-col items-center m-5">
                    <div class="w-1/2 max-sm:w-full pb-2 pt-5 mb-3 flex-row ">
                        <VaInput v-model="searchQuery.title" placeholder="Search here" label="Title  " inner-label
                            class="w-full max-w-md shadow-md py-2" color="Info" currentColor="Success" />
                        <VaSelect v-model="searchQuery.keyword" placeholder="Search here" label="Keyword" :options="keywordList" inner-label clearable searchable autocomplete text-by="keyword" value-by="keyword" highlight-matched-text clearable-icon="cancel" multiple
                            class="w-full max-w-md shadow-md py-2" color="Info" currentColor="Success" />
                        <VaSelect v-model="searchQuery.author" placeholder="Search here" label="Author " :options="authorList" inner-label clearable searchable autocomplete text-by="name" value-by="name" highlight-matched-text clearable-icon="cancel" multiple
                        class="w-full max-w-md shadow-md py-2" color="Info" currentColor="Success" />
                        <VaSelect v-model="searchQuery.category" placeholder="Search here" :options="categoryList" multiple
                            label="Category  " inner-label clearable searchable autocomplete text-by="category"
                            value-by="category" highlight-matched-text clearable-icon="cancel"
                            class="w-full max-w-md shadow-md py-2" color="Info" currentColor="Success" />
                        <div class="flex max-sm:w-full">
                            <VaButton preset="secondary" border-color="primary" hover-behavior="opacity"
                                :hover-opacity="0.4" icon-color="#ffffff50" size="medium"
                                class="w-2/3 text-center transition-opacity shadow-md py-2 hover:-translate-y-1 hover:scale-170 duration-350 ease-in-out"
                                @click="searchThesis()">
                                SEARCH
                            </VaButton>
                            <VaSelect v-model="searchQuery.sort" placeholder="A-Z" :options="sortList"
                                label="Sort  " inner-label clearable searchable autocomplete
                                text-by="name" value-by="value" highlight-matched-text clearable-icon="cancel"
                                class="w-1/3 max-w-md shadow-md ps-1 " color="Info" currentColor="Success" />
                        </div>

                        <VaProgressBar :indeterminate="isloading" size="small" />
                    </div>
                    <!-- Can't find what you're looking for? -->
                    
                    <span class="pb-5 text-start">The search for Author, Keywords and Category are Case Sensitive</span>
                </div>
            </div>
        </div>

        <div class="bg-white-500 w-full h-full pt-5 ">
            <div class="flex flex-center justify-center flex-col p-5 ">
                <div v-for="thesis in paginate(data.thesisList)" class="mb-3 xs:mb-10 h-full w-1/2 max-sm:w-full shadow-2xl shadow-red-400" 
                :key="thesis.id">
             
                    <VaCard class="transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300 shadow-2xl shadow-red-400
                    translate-x-50 py-5">
                   
                        <VaCardTitle>
                                <div class="flex w-full ">
                                    <VaPopover message="Click to view">
                                        
                                        <h2 @click="selectThesis(thesis.id)" lang="en" class="break-words hyphens-auto text-2xl font-bold hover:underline text-black-700  text-center hover:bg-slate-100 hover:text-blue hover:cursor-pointer w-full md:flex-grow text-wrap">
                                            {{ thesis.title }}
                                        </h2>
                                        <br />
                                    </VaPopover>
                                </div>
                        </VaCardTitle>
                        <VaCardContent>
                            <VaPopover message="Click the title to view more">
                                <div class="title">
                                    <div class="w-fit pb-3 h-1/5">
                                        <div class="flex sm:flex-col sm:self-center lg:items-start">
                                            <div class="flex text-lg font-bold flex-wrap whitespace-pre">Author:
                                                <span
                                                v-if="thesis.authors && thesis.authors.length !== 0"
                                                    class="flex text-green-700 whitespace-pre text-base flex-center justify-center"
                                                    v-for="(author, index) in thesis.authors" :key="index">
                                                    {{ '' + author.name }}
                                                
                                                    <span v-if="index !== thesis.authors.length - 1">, </span>
                                                </span>
                                                <span v-else>
                                                    No Author
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <!-- date here import formatdate here-->
                                            <label class="text-lg font-bold" for="thesis.published_at">
                                                Published Date:
                                            </label>{{ formatDate(thesis.published_at, 'MMM. Do, YYYY', 'Invalid Date') }}
                                        </div>
                                    </div>
                                    <!-- Place tags here -->
                                    <div class="w-fit pb-3">
                                        <div class="flex flex-col md:flex-row justify-center md:items-start gap-1">
                                            <div class="w-full sm:w-auto flex-shrink-0 flex-center md:mr-2">
                                                <label
                                                    class="justify-center flex-center whitespace-pre-line font-bold">Category:</label>
                                            </div>
                                            <div class="w-full md:flex-grow flex flex-wrap">
                                                <div v-for="category in thesis.categories"
                                                    class="mr-1 mb-2 flex flex-center justify-center">
                                                    <VaChip square color="success" class="mr-2">
                                                        {{ category.category }}
                                                    </VaChip>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-fit pb-3">
                                        <div class="flex flex-col md:flex-row items-center md:items-start gap-1">
                                            <div class="w-full md:w-auto flex-shrink-0 flex-center md:mr-2">
                                                <label
                                                    class="justify-center flex-center whitespace-pre-line font-bold">Keywords:</label>
                                            </div>
                                            <div class="w-full md:flex-grow flex flex-wrap">
                                                <div v-for="keyword in thesis.keywords"
                                                    class="mr-1 mb-2 flex flex-center justify-center">
                                                    <VaChip square color="success" class="mr-2">
                                                        {{ keyword.keyword }}
                                                    </VaChip>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div :key="thesis.id">
                                    <div class="max-h-[150px] overflow-hidden text-justify">
                                        <p class="text-gray-700 leading-relaxed text-sm md:text-base"
                                            v-show="!isAbstractOpen(thesis.id)">
                                            {{ getTruncatedAbstract(thesis.id) }}
                                        </p>
                                        <p class="text-gray-700 leading-relaxed text-sm md:text-base text-wrap "
                                            v-show="isAbstractOpen(thesis.id)">
                                            {{ thesis.abstract }}
                                        </p>
                                        <button
                                            class="text-blue-500 hover:text-blue-700 focus:outline-none focus:underline mt-2 inline-block"
                                            @click="toggleAbstract(thesis.id)">
                                            {{ isAbstractOpen(thesis.id) ? 'Read less' : 'Read more' }}
                                        </button>
                                    </div>
                                </div>


                                <div v-if="false"
                                    class="files flex sm:flex-row lg:grid lg:grid-cols-2 gap-5 pt-5 flex-wrap ">
                                    <div>
                                        <VaButton :disabled="thesis.pdf === '' || thesis.pdf === null" icon="download"
                                            color="info" :href="thesis.pdf" class="hover:animate-bounce ease-in-out w-full">
                                            <span>
                                                {{ thesis.pdf === '' || thesis.pdf === null ? 'PDF Unvailable' : ' Download PDF'}}
                                            </span>
                                        </VaButton>
                                    </div>
                                    <VaButton :disabled="thesis.video === '' || thesis.video === null" icon="download"
                                        color="info" :href="thesis.video" class="hover:animate-bounce ease-in-out w-full">
                                        <span>
                                            {{ thesis.video === '' || thesis.video === null ? 'Video Unavailable' : 'Download Video'}}
                                        </span>
                                    </VaButton>

                                </div>
                            </VaPopover>
                        </VaCardContent>
                 
                    </VaCard>
                </div>
                <div v-if="data.thesisList == null || data.thesisList.length === 0"
                class="py-5 max-sm:py-10"
                >
                    <h1>No thesis found.</h1>
                </div>
                <div class="cl">
                    <va-pagination
                    class="justify-center"
                    v-model="$root.config.tblCurrPage"
                    :pages="filter == '' ? $root.tblPagination(data.thesisList) : (setPages, $root.config.tblCurrPage = 1)"
                    input
                    />
                </div>
               
                <!-- <div class="flex gap-2 py-5">
                    <VaButton v-if="pages.length !== 1" @click="decrementPage" :disabled="$root.config.tblCurrPage == 1"
                        preset="secondary" hover-behavior="opacity" :hover-opacity="0.4" icon="arrow_back_ios" class="transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300
                            translate-x-50  w-full" />

                    <VaButton v-for="pageNumber in pages.slice($root.config.tblCurrPage - 1, $root.config.tblCurrPage+3)" @click="$root.config.tblCurrPage = pageNumber"
                        :color="$root.config.tblCurrPage == pageNumber ? 'Focus' : 'BackgroundElement'" class="transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300
                            translate-x-50  w-full" :key="pageNumber">
                        <span :class="$root.config.tblCurrPage == pageNumber ? 'text-white' : 'text-black'">
                            {{ pageNumber }}
                        </span>
                    </VaButton>
                    <VaButton v-if="$root.config.tblCurrPage < pages.length || !pages.length == 1" @click="$root.config.tblCurrPage++" preset="secondary"
                        hover-behavior="opacity" :hover-opacity="0.4" icon="arrow_forward_ios" class="transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300
                            translate-x-50  w-full" />
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
import formatDate from '@/functions/formatdate.js';

export default {
    data() {
        return {
            
            pageNumber: null,
            perPage: 3,
            pages: [],
            showFullAbstract: false,
            truncatedAbstract: '',
            isloading: false,
            searchQuery: {
                title: null,
                keyword: null,
                author: null,
                category: null,
                sort: null,
            },
            authorList:[],
            categoryList: [],
            keywordList:[],
            data: {
                search: "",
                thesisList: [],
                expandedThesisId: null,
            },
            sortList: [
                {
                    name: 'Created Date',
                    value: '3'
                },
                {
                    name: 'Ascending',
                    value: '1'
                },
                {
                    name: 'Descending',
                    value: '2'
                },
            ],
            filtered: null,
            filter: "",

        }
    },
    created() {
        this.getThesis();
        this.getCategory();
        this.getKeywords();
        this.getAuthor();
    },
    computed: {
    },
    watch: {
        'data.thesisList'() {
            this.setPages();
        }
    },
    methods: {
        decrementPage() {
            if (this.$root.config.tblCurrPage !== 1) {
                this.$root.config.tblCurrPage--; //
            }
            if (this.$root.config.tblCurrPage === 0) {
                return;
            }
        },
        setPages() {
            let numberOfPages = Math.ceil(this.data.thesisList.length / this.$root.config.tblPerPage);
            for (let index = 1; index <= numberOfPages; index++) {
                this.pages.push(index);
            }
        },
        paginate(thesis) {
            let page = this.$root.config.tblCurrPage;
            let perPage = this.$root.config.tblPerPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);

            return thesis.slice(from, to);
        },
        toggleAbstract(thesisId) {
            if (this.data.expandedThesisId === thesisId) {
                this.data.expandedThesisId = null;
            } else {
                this.data.expandedThesisId = thesisId;
            }
        },
        isAbstractOpen(thesisId) {
            return this.data.expandedThesisId === thesisId;
        },

        getThesis() {
            this.isloading = true;
            axios({
                method: 'GET',
                url: '/thesis/get',
                type: 'JSON',
            }).then(response => {
                if (response.data.status == 1) {
                    this.data.thesisList = response.data.result;

                } this.isloading = false;
            })
        },
        getTruncatedAbstract(thesisId) {
            if (!Array.isArray(this.data.thesisList)) {
                return '';
            }
            const thesis = this.data.thesisList.find(item => item.id === thesisId);
            if (!thesis) return '';
            return thesis.abstract.slice(0, 150);
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
        getKeywords() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/keyword/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.keywordList = response.data.result;
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
        searchThesis() {
            this.isloading = true;
            axios({
                method: 'POST',
                url: '/thesis/search',
                type: 'JSON',
                data: this.searchQuery,
            }).then(response => {
                if (response.data.status == 1) {
                    if (Array.isArray(response.data.result)) {
                        this.data.thesisList = response.data.result;
                        this.pages = []
                        this.page = 1;
                        this.perPage = 4
                    } else {
                    
                        this.data.thesisList = Object.values(response.data.result);
                        this.pages = [];
                        this.page = 1;
                        this.perPage = 4;

                        // if (this.data.thesisList.length > 1) {
                        //     this.page++;
                        // }
                    }
                    if(response.data.result.length === 0){
                        this.getThesis();
                       
                    }
                    this.$root.config.tblCurrPage = 1;
                }
                this.isloading = false;
            }).catch(error => {
                console.log("Error: ".error);
                this.isloading = false;
            });
        },
        selectThesis(id) {
            this.$emit('thesisSelected', id);

        },
        formatDate,
    },
    components: {

    },
}
</script>