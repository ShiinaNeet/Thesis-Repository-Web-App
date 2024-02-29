<template>
    <div class="py-5 h-screen w-screen">
        <div class="h-1/3 px-55 w-full flex flex-center justify-center bg-slate-100">
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
        <div class="bg-red-500 mx-5 w-full">
            <div class="wewe">
                <VaCard
                square
                outlined
                v-for="thesis in thesisList"
                class="mb-3"
                >
                    <VaCardTitle><h2>{{ thesis.title }}</h2></VaCardTitle>
                    <VaCardContent>
                        <div>
                            <!-- Place tags here -->
                            <div v-for="tags in thesis.tags">
                                <VaBadge
                                text="tags.name"
                                color="success"
                                class="mr-2"
                                />
                            </div>
                            
                            {{ thesis.tags }}
                        </div>
                        <div >
                            <!-- Abstract here -->
                            {{ thesis.abstract }}
                        </div>
                        <div class="files ">
                            <a :href="thesis.pdf">Download PDF here</a> <br/>
                            <a :href="thesis.video">Download PDF here</a>
                        </div>
                        <div >
                            <!-- date here import formatdate here-->
                            {{ thesis.published_at }}
                        </div>
                    </VaCardContent>
                </VaCard>
            </div>
        </div>
    </div>

</template>


<script>

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
    methods:{
        getThesis(){
            axios({
                method: 'GET',
                url: '/thesis/get',
                type: 'JSON',
            }).then(response => {
                if(response.data.status == 1)
                {
                    this.thesisList = response.data.result;
                    console.log(this.thesisList);
                }
            })
        }
    },
    components:{

    },
}
</script>