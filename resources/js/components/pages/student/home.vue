<template>
    <div class="py-5 ">
        <div class="h-[250px] px-55 lg:w-full sm:w-1/3 flex flex-center justify-center bg-slate-100">
            <div class="flex w-full justify-center flex-col">
                <div class="w-full flex flex-col ee">
                    <!-- Input field -->
                    <div class="relative w-full align-self-center flex flex-center flex-col">
                        <div>
                            <VaInput
                            v-model="data.search"
                            placeholder="Search here"
                            label="Search Thesis here"
                            inner-label
                            class="w-[500px]"
                            color="Info"
                            currentColor="Success"
                            
                        />
                        </div>
                        <div class="flex justify-end py-3 text-blue-600 hover:underline"
                        @click="$root.redirectToPage('/login')"
                        >
                           Can't find what you're looking for?
                        </div>
                    </div>
                    <!-- Paragraph positioned outside, aligned with the input -->
                    
                </div>
            </div>
        </div>
        <div class="bg-white-500 mx-5 w-full h-full">
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
                                            <span class="flex text-green-700 underline font-bold whitespace-pre" v-for="(author, index) in thesis.author" :key="index">
                                                {{ ' '+ author.name }}
                                                <span v-if="index !== thesis.author.length - 1">, </span> <!-- Add comma for all authors except the last one -->
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
                            <div class="w-fit pb-3 h-1/5">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-1">
                                    <div class="w-full md:w-auto flex-shrink-0 flex-center md:mr-2">
                                        <label class="justify-center flex-center whitespace-pre-line font-bold">Category:</label>
                                    </div> 
                                    <div class="w-full md:flex-grow flex flex-wrap">
                                        <div v-for="category in thesis.category" class="mr-1 mb-2">
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
                            <div class="w-fit pb-3 h-1/5">
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
                            Download video here
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
            data:{
                search: "",
                thesisList: [],
            }
        }
    },
    mounted(){
        this.getThesis();
        this.truncateAbstract();
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