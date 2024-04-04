<template>
    <div class="py-5 ">
        <div class="h-[250px] lg:w-full sm:w-full flex justify-center">
            <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3 flex items-center justify-center">
                <div class="w-full flex flex-col items-center">
                  
                    <div class="w-1/2 mb-3 flex-row">
                        <VaInput
                            v-model="searchQuery.title"
                            placeholder="Search here"
                            label="Title  "
                            inner-label
                            class="w-full max-w-md shadow-md py-2"
                            color="Info"
                            currentColor="Success"
                          
                        />
                        <VaInput
                            v-model="searchQuery.keyword"
                            placeholder="Search here"
                            label="Keyword  "
                            inner-label
                            class="w-full max-w-md shadow-md py-2"
                            color="Info"
                            currentColor="Success"
                           
                        />
                        <VaInput
                            v-model="searchQuery.author"
                            placeholder="Search here"
                            label="Author "
                            inner-label
                            class="w-full max-w-md shadow-md py-2"
                            color="Info"
                            currentColor="Success" 
                        />
                        <VaSelect
                        v-model="searchQuery.category"
                        placeholder="Search here"
                        :options="categoryList"
                        label="Category  "
                        inner-label
                        clearable
                        searchable
                        autocomplete
                        text-by="category"
                        value-by="category"
                        highlight-matched-text
                        clearable-icon="cancel"
                        class="w-full max-w-md shadow-md py-2"
                        color="Info"
                        currentColor="Success"
                        />
                        <div class="flex">
                            <VaButton
                            preset="secondary"
                            border-color="primary"
                            hover-behavior="opacity"
                            :hover-opacity="0.4"
                            icon-color="#ffffff50"
                            size="medium"
                            class="w-2/3 text-center transition-opacity shadow-md py-2 hover:-translate-y-1 hover:scale-170 duration-350 ease-in-out"
                            @click="searchThesis()"
                            >
                                SEARCH  
                            </VaButton>
                            <VaSelect
                            v-model="searchQuery.sort"
                            placeholder="A-Z"
                            :options="sortList"
                            label="Sort"
                            inner-label
                            clearable
                            searchable
                            autocomplete
                            text-by="name"
                            value-by="value"
                            highlight-matched-text
                            clearable-icon="cancel"
                            class="w-1/3 max-w-md shadow-md ps-1 "
                            color="Info"
                            currentColor="Success"
                            />
                        </div>
                       
                        <VaProgressBar  
                        :indeterminate="isloading" 
                        size="small" />
                    </div>
                    <!-- Can't find what you're looking for? -->
                    <div class="pb-5 text-center text-blue-600 hover:underline">
                        <span @click="$root.redirectToPage('/login')">Can't find what you're looking for?</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white-500 w-full h-full">
            <div class="flex flex-center justify-center flex-col ">
                <div 
                v-for="thesis in data.thesisList"
                class="mb-3 h-full w-1/2 shadow-2xl shadow-red-400 
                
                "
                :key="thesis.id"
                >
                    <VaCard
                    class="transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300 shadow-2xl shadow-red-400
                            translate-x-50 
                    "
                    >
                    <VaCardTitle>
                        <div class="flex w-full ">
                            <h2 
                            @click="selectThesis(thesis.id)"
                            lang="en"
                            class="break-words hyphens-auto text-2xl font-bold hover:underline text-black-700  text-center
                            hover:bg-slate-100 hover:text-blue hover:cursor-pointer w-full md:flex-grow text-wrap">
                            {{ thesis.title }}
                            </h2> 
                            <br/>
                        </div>
                        
                    </VaCardTitle>
                    <VaCardContent>
                        <div class="title">
                            <div class="w-fit pb-3 h-1/5">
                                <div class="">
                                    <div class="flex items-center"> <!-- Flex container for label and paragraph -->
                                        <label class="text-lg font-bold mr-2 flex-wrap"> <!-- Label -->
                                            Authors:  
                                        </label>
                                        <div class="flex flex-wrap whitespace-normal"> <!-- Flex container for author tags -->
                                            <span class="flex text-green-700 underline font-bold whitespace-pre" v-for="(author, index) in thesis.authors" :key="index">
                                                {{ ' '+ author.name }}
                                                <span v-if="index !== thesis.authors.length - 1">, </span> <!-- Add comma for all authors except the last one -->
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <!-- date here import formatdate here-->
                                <label
                                class="text-lg font-bold"
                                for="thesis.published_at">
                                    Published Date: 
                                </label>{{ formatDate(thesis.published_at, 'MMM. Do YYYY', 'Invalid Date')}}
                                </div>
                            </div>
                            <!-- Place tags here -->
                            <div class="w-fit pb-3">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-1">
                                    <div class="w-full md:w-auto flex-shrink-0 flex-center md:mr-2">
                                        <label class="justify-center flex-center whitespace-pre-line font-bold">Category:</label>
                                    </div> 
                                    <div class="w-full md:flex-grow flex flex-wrap">
                                        <div v-for="category in thesis.categories" class="mr-1 mb-2">
                                            <VaChip 
                                                square
                                                color="success"
                                                class="mr-2"
                                            >
                                                {{ category.category }}
                                            </VaChip>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-fit pb-3">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-1">
                                    <div class="w-full md:w-auto flex-shrink-0 flex-center md:mr-2">
                                        <label class="justify-center flex-center whitespace-pre-line font-bold">Keywords:</label>
                                    </div> 
                                    <div class="w-full md:flex-grow flex flex-wrap">
                                        <div v-for="keyword in thesis.keywords" class="mr-1 mb-2">
                                            <VaChip 
                                                square
                                                color="success"
                                                class="mr-2"
                                            >
                                                {{ keyword.keyword }}
                                            </VaChip>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div :key="thesis.id">
                            <div class="max-h-[150px] overflow-hidden">
                                <p class="text-gray-700 leading-relaxed text-sm md:text-base" v-show="!isAbstractOpen(thesis.id)">
                                    {{ getTruncatedAbstract(thesis.id) }}
                                </p>
                                <p class="text-gray-700 leading-relaxed text-sm md:text-base text-wrap text-justify" 
                                v-show="isAbstractOpen(thesis.id)" >
                                    {{ thesis.abstract }}
                                </p>
                                <button class="text-blue-500 hover:text-blue-700 focus:outline-none focus:underline mt-2 inline-block" 
                                @click="toggleAbstract(thesis.id)">
                                    {{ isAbstractOpen(thesis.id) ? 'Read less' : 'Read more' }}
                                </button>
                            </div>
                        </div>

                        
                        <div class="files flex sm:flex-row lg:grid lg:grid-cols-2 gap-5 pt-5 flex-wrap ">
                            <div>
                                <VaButton
                                :disabled="thesis.pdf === '' || thesis.pdf === null"
                                icon="download"
                                color="info"
                                :href="thesis.pdf"
                                class="hover:animate-bounce ease-in-out w-full"
                                >
                                    <span>
                                        {{ thesis.pdf === '' || thesis.pdf === null ? 'PDF Unvailable' :' Download PDF'}}
                                    </span>
                                </VaButton>
                            </div>
                            <VaButton
                            :disabled="thesis.video === '' || thesis.video === null"
                            icon="download"
                            color="info"
                            :href="thesis.video"
                            class="hover:animate-bounce ease-in-out w-full"
                            >
                                <span>
                                    {{ thesis.video === '' || thesis.video === null ? 'Video Unavailable' :' Download Video'}}
                                </span>
                            </VaButton>
                           
                        </div>
                        
                    </VaCardContent>
                </VaCard>
                </div>
                
            </div>
        </div>
    </div>

</template>

<style>
.hover:text-transparent {
    visibility: hidden;
}
</style>

<script>
import formatDate from '@/functions/formatdate.js';
import { ref, computed, onMounted } from 'vue'

export default{
    data(){
        return{
            showFullAbstract: false,
            truncatedAbstract: '',
            isloading: false,
            searchQuery:{
                title: null,
                keyword: null,
                author: null,
                category: null,
                sort: null,
            },
            categoryList: [],
            data:{
                search: "",
                thesisList: [],
                expandedThesisId: null,
            },
            sortList:[
                {
                    name: 'A-Z',
                    value: '0'
                },
                {
                    name: 'a-Z',
                    value: '1'
                },
                {
                    name: 'Ascending',
                    value: '2'
                },
                {
                    name: 'Descending',
                    value: '3'
                },
            ],

        }
    },
    mounted(){
        this.getThesis();
        this.getCategory();
    },
    computed(){
        this.getThesis();
        this.getTruncatedAbstract();
    },
    methods:{
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
        getTruncatedAbstract(thesisId) {
            if (!Array.isArray(this.data.thesisList)) {
                return '';
            }
            const thesis = this.data.thesisList.find(item => item.id === thesisId);
            if (!thesis) return '';
            return thesis.abstract.slice(0, 150);
        },
        getThesis(){
            this.isloading = true;
            axios({
                method: 'GET',
                url: '/thesis/get',
                type: 'JSON',
            }).then(response => {
                if(response.data.status == 1)
                {
                    this.data.thesisList = response.data.result;
                    
                }this.isloading = false;
            })
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
        searchThesis(){
            this.isloading = true;
            axios({
                method: 'POST',
                url: '/thesis/search',
                type: 'JSON',
                data:  this.searchQuery ,
            }).then(response => {
                if(response.data.status == 1)
                {
                    this.data.thesisList = response.data.result;
                    
                }
                this.isloading = false;
            }).catch(error => {
                console.log("Error: " . error);
                this.isloading = false;
            });
        },
        selectThesis(id) {
            this.$emit('thesisSelected', id);
            console.log(id) // Emit event with selected ID
        },
        formatDate,
    },
    components:{

    },
}
</script>