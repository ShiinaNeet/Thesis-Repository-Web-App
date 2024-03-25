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
                            class="w-full max-w-md"
                            color="Info"
                            currentColor="Success"
                          
                        />
                        <VaInput
                            v-model="searchQuery.keyword"
                            placeholder="Search here"
                            label="Keyword  "
                            inner-label
                            class="w-full max-w-md"
                            color="Info"
                            currentColor="Success"
                           
                        />
                        <VaInput
                            v-model="searchQuery.author"
                            placeholder="Search here"
                            label="Author "
                            inner-label
                            class="w-full max-w-md"
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
                        class="w-full max-w-md"
                        color="Info"
                        currentColor="Success"
                        />
                        <VaButton
                        preset="secondary"
                        hover-behavior="opacity"
                        :hover-opacity="0.4"
                        icon-color="#ffffff50"
                        size="medium"
                        class="w-full text-center"
                        @click="searchThesis()"
                        >
                            SEARCH  
                        </VaButton>
                        
                    </div>
                    <!-- Can't find what you're looking for? -->
                    <div class="text-center text-blue-600 hover:underline">
                        <span @click="$root.redirectToPage('/login')">Can't find what you're looking for?</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white-500 w-full h-full">
            <div class="flex flex-center justify-center flex-col">
                <VaCard
                square
                outlined
                v-for="thesis in data.thesisList"
                class="mb-3 h-full w-1/2 "
                >
                    <VaCardTitle>
                        <div class="flex w-full ">
                            <h2 
                            @click="selectThesis(thesis.id)"
                            lang="en"
                            class="break-words hyphens-auto text-2xl font-bold underline text-blue-700 hover:bg-slate-400 hover:text-white w-full md:flex-grow text-wrap">
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
                                        <label class="text-lg font-bold mr-2"> <!-- Label -->
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
                        
                        <div>
                            <div class="max-h-[150px] overflow-hidden">
                            <p class="text-gray-700 leading-relaxed text-sm md:text-base" v-show="!showFullAbstract">
                                {{ truncatedAbstract }}
                            </p>
                            <p class="text-gray-700 leading-relaxed text-sm md:text-base text-wrap text-justify" v-show="showFullAbstract" ref="fullAbstract">
                                {{ thesis.abstract  }}
                            </p>
                            <button 
                                class="text-blue-500 hover:text-blue-700 focus:outline-none focus:underline mt-2 inline-block"
                                @click="toggleAbstract"
                            >
                                {{ showFullAbstract ? 'Read less' : 'Read more' }}
                            </button>
                            </div>
                        </div>
                        
                        <div class="files flex flex-center w-fit gap-5 pt-5 ">
                            <VaButton
                            :disabled="thesis.pdf === ''"
                            icon="download"
                            color="info"
                            :href="thesis.pdf"
                            >
                            Download PDF here
                            </VaButton>
                            <VaButton
                            :disabled="thesis.video === ''"
                            icon="download"
                            color="info"
                            :href="thesis.video"
                            >
                            Download Video here
                            </VaButton>
                           
                        </div>
                        
                    </VaCardContent>
                </VaCard>
            </div>
        </div>
    </div>

</template>


<script>
import formatDate from '@/functions/formatdate.js';

export default{
    data(){
        return{
            showFullAbstract: false,
            truncatedAbstract: '',
            searchQuery:{
                title: null,
                keyword: null,
                author: null,
                category: null,
            },
            categoryList: [],
            data:{
                search: "",
                thesisList: [],
            }
        }
    },
    mounted(){
        this.getThesis();
        this.truncateAbstract();
        this.getCategory();
    },
    computed(){
        this.getThesis();
    },
    methods:{
        toggleAbstract() {
            this.showFullAbstract = !this.showFullAbstract;
        },
        findTruncationIndex(text) {
            // Binary search to find the index at which the abstract should be truncated
            let start = 0;
            let end = text.length - 1;
            while (start <= end) {
                const mid = Math.floor((start + end) / 2);
                this.$refs.fullAbstract.textContent = text.substring(0, mid);
                if (this.$refs.fullAbstract.clientHeight <= 150) {
                start = mid + 1;
                } else {
                end = mid - 1;
                }
            }
            return end;
        },
        truncateAbstract() {
            const abstractElement = this.$refs.fullAbstract;
            if (abstractElement) {
                // Get the original abstract text
                const originalAbstract = abstractElement.textContent.trim();
                // Check if the original abstract exceeds 150px in height
                if (abstractElement.clientHeight > 150) {
                // Truncate the abstract and append ellipsis
                this.truncatedAbstract = originalAbstract.substring(0, this.findTruncationIndex(originalAbstract)) + '...';
                } else {
                // If the abstract doesn't need truncation, display the original text
                this.truncatedAbstract = originalAbstract;
                }
            }
        },
        getThesis(){
            axios({
                method: 'GET',
                url: '/thesis/get',
                type: 'JSON',
            }).then(response => {
                if(response.data.status == 1)
                {
                    this.data.thesisList = response.data.result;
                    
                }
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
            }).catch(error => {
                console.log("Error: " . error);
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