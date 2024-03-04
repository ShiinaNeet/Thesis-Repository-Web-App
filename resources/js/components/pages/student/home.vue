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
                        <h2 class="text-2xl font-blod underline text-blue-700">{{ thesis.title }}</h2> <br/>
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
                                            <span class="flex text-green-700 underline font-bold" v-for="(author, index) in thesis.author" :key="index">
                                                {{ ''+ author.name }}
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
                                <div class="flex lg:flex-row flex-col gap-1">
                                    <div v-for="keyword in thesis.keywords">
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
                        <div class="max-h-[150px] truncate w-full flex text-wrap break-all py-3 ">
                            <!-- Abstract here -->
                            <p class="text-wrap break-all">  {{  thesis.abstract  +thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract+thesis.abstract}}</p>
                        </div>
                        <div class="files flex flex-center w-fit gap-5 ">
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
            data:{
                search: "",
                thesisList: [],
            }
        }
    },
    mounted(){
        this.getThesis();
    },
    computed(){
        this.getThesis();
    },
    methods:{
        getThesis(){
            axios({
                method: 'GET',
                url: '/thesis/get',
                type: 'JSON',
            }).then(response => {
                if(response.data.status == 1)
                {
                    this.data.thesisList = response.data.result;
                    console.log(this.thesisList);
                }
            })
        },
        formatDate,
    },
    components:{

    },
}
</script>