<template>
    <div 
    class="px-10 flex flex-center justify-center bg-gray-400 text-white h-full"
    >
        <div v-if="thesis"
        class=" w-1/2 mx-auto justify-center h-full"
        >
            <div
            class="w-2/3 mx-auto justify-center px-5 bg-gray-800 shadow-xl h-full"
            >
                <div
                class="w-full flex-wrap py-10 px-5 h-full text-wraps"
                >
                    <div class="">
                        <h1 class="py-1 text-2xl text-center"> {{ thesis.title }}</h1>
                    </div>
                    <div>
                        <p class="pb-5 text-lg text-center">{{ formatDate(thesis.published_at, "MMMM do, YYYY" , "Invalid Date") }}</p>
                    </div>
                    <div>
                        <p class="text-lg text-justify flex-wrap py-3"> {{ thesis.abstract }}</p>
                    </div>
                    
                    <div class="text-white">
                        <VaList class="text-white">
                            <label class="text-lg" for="authors">Authors</label>
                            <ul 
                            class="text-white text-lg list-inside list-disc"
                            >
                            <li v-for="author in thesis.author">{{ author.name }}</li>
                            </ul>
                        </VaList>
                        <VaList class="text-white">
                            <label 
                            class="text-white text-lg py-5"
                            for="category">Category</label>
                            <ul 
                            class="text-white text-lg list-disc list-inside"
                            >
                                <VaChip 
                                v-for="category in thesis.category"
                                color="secondary"
                                class="mr-2"
                                size="large"
                                square
                                >
                                    {{ category.category }}
                                </VaChip>
                           </ul>
                        </VaList>
                        <div class="text-white">
                            <label
                            class="text-lg py-5"     
                            for="category">Tags</label>
                            <ul 
                            class="text-white text-lg list-disc list-inside "
                            >
                                <VaChip square
                                    v-for="keyword in thesis.keywords"
                                    
                                    color="secondary"
                                    class="mr-2"
                                    size="large"
                                    
                                >{{ keyword.keyword }}</VaChip>

                            </ul>
                        </div>
                    </div>
                    <div class="downloads py-5">
                        <div class="flex gap-2">
                            <VaButton
                            icon-right="arrow_forward"
                            icon-color="#ffffff50"
                            @click="openPdf()"
                            >
                            PDF
                            </VaButton>
                            <VaButton
                            icon-right="arrow_forward"
                            icon-color="#ffffff50"
                            @click="openVideo()"
                            >
                            Video
                            </VaButton>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="m-10">
                <div class="flex flex-center justify-center mx-auto">
                    <p class="text-3xl flex justify-center font-serif">
                        Something went wrong! Please go back!
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- pdf -->
    <VaModal
    v-model="isPdfOpen"
    hide-default-actions
    size="large"
    class="w-full"
    close-button
    >
        <div
        class="w-screen h-fit"
        >
            <iframe :src="thesis.pdf" class="w-screen h-screen"></iframe>
        </div>
       
    </VaModal>
    <!-- video -->
    <VaModal
    v-model="isVideoOpen"
    hide-default-actions
    class="w-full"
    >
        <div
        class="w-full h-fit"
        >
            <video class="w-fit h-fit" controls>
                <source :src="thesis.video" type="video/mp4">
            </video>
        </div>
       
    </VaModal>
</template>

<script>
import formatDate from '@/functions/formatdate.js';
export default {
    data () {
        return {
            thesis:{},
            selectedID: this.selectedThesisId,
            isVideoOpen: false,
            isPdfOpen: false,
        };
    },
    props: ['selectedThesisId'],
    mounted() {
        this.getThesis();
    },
    methods:{
        openPdf(){
            this.isPdfOpen = true;
            
        },
        openVideo(){
            this.isVideoOpen = true;
            
        },
        getThesis() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/thesisq/'+this.selectedID,
            }).then(response => {
                if (response.data.status == 1) {
                    this.thesis = response.data.result[0];
                    console.log(this.thesis);
                
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDate,
    },
   
}
</script>