<template>
    <div class=" px-5 bg-slate-200">
        <div class="flex flex-wrap items-stretch gap-5 py-5 w-auto">
            <div class="flex w-full">
                    <h1>Thesis</h1> <br/>
            </div>
            <div class="thesis flex flex-wrap items-stretch gap-5 pb-3 w-auto">
                
                <div class="shadow-2xl shadow-indigo-500/40 w-fit min-w-1/3">
                    <VaCard :bordered="false" color="Primary" gradient>
                        <VaCardTitle>
                            Users
                        </VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ users.count }}
                        </VaCardContent>
                    </VaCard>
                </div>
                <div class="shadow-2xl shadow-indigo-500/40 w-fit">
                    <VaCard :bordered="false" color="Danger" gradient>
                        <VaCardTitle>Thesis</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ thesis.active.count }}
                        </VaCardContent>
                    </VaCard>
                </div>
                <div class="shadow-2xl shadow-indigo-500/40 w-fit">
                    <VaCard :bordered="false" color="Warning" gradient>
                        <VaCardTitle>Outdated Thesis (Less than 3 Years)</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ thesis.outdated.count }}
                        </VaCardContent>
                    </VaCard>
                </div>
            </div>
        </div>
        
        <div class="Resource flex flex-wrap items-stretch gap-5 pb-3 w-auto">
            <div class="flex w-full">
                    <h1>Resource</h1> <br/>
            </div>
            <div class="Resources flex flex-wrap items-stretch gap-5 pb-5 w-auto">
                <div class="shadow-2xl shadow-indigo-500/40 w-fit min-w-1/3">
                <VaCard :bordered="false" color="Primary" gradient>
                    <VaCardTitle >Authors</VaCardTitle>
                    <VaCardContent class="text-6xl">
                        {{ authors.count }}
                    </VaCardContent>
                </VaCard>
                </div>
                <div class="shadow-2xl shadow-indigo-500/40 w-fit text-black">
                    <VaCard :bordered="false" color="Danger" gradient>
                        <VaCardTitle class="">Keywords</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ keywords.count }}
                        </VaCardContent>
                    </VaCard>
                </div>
                <div class="shadow-2xl shadow-indigo-500/40 w-fit">
                    <VaCard :bordered="false" color="Warning" gradient>
                        <VaCardTitle>Category</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ categories.count }}
                        </VaCardContent>
                    </VaCard>
                </div>
            </div>
        </div>
        <div class="Database flex flex-wrap items-stretch gap-5 pb-3 w-auto">
            <div class="flex w-full">
                    <h1>Database</h1> <br/>
            </div>
            <div class="Resources flex flex-wrap items-stretch gap-5 pb-5 w-auto">
                <div class="shadow-2xl shadow-indigo-500/40 w-fit text-black">
                    <VaCard :bordered="false" color="Danger" gradient>
                        <VaCardTitle class="">PDF Storage Size</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ storage.pdf + ' MB'}}
                        </VaCardContent>
                    </VaCard>
                </div>
                <div class="shadow-2xl shadow-indigo-500/40 w-fit">
                    <VaCard :bordered="false" color="Warning" gradient>
                        <VaCardTitle>Videos Storage Size</VaCardTitle>
                        <VaCardContent class="text-6xl">
                            {{ storage.videos + ' MB'}}
                        </VaCardContent>
                    </VaCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default{
    data(){
        return{
            users:{
                count:0,
                data:[],
            },
            thesis:{
                active:{
                    count:0,
                },
                outdated:{
                    count:0,
                }
            },
            authors:{
                count:0,
            },
            keywords:{
                count:0,
            },
            categories:{
                count:0,
            },
            storage:{
                pdf: null,
                videos: null,
            }
            
        }
    },
    mounted(){
        this.getDashboardData();
    },
    methods:{
        getDashboardData(){
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/dashboard/get',
                }).then(response => {
                    this.users.count = response.data.result.users;
                    this.authors.count = response.data.result.authors;
                    this.keywords.count = response.data.result.keywords;
                    this.categories.count = response.data.result.categories;
                    this.thesis.active.count = response.data.result.thesis;
                    this.thesis.outdated.count = response.data.result.thesisoutdated;
                    this.storage.pdf = response.data.result.pdf;
                    this.storage.videos = response.data.result.video;
                 
                }).catch(error => {
                    console.log("error: ". error);
                });
        },
    }
}

</script>