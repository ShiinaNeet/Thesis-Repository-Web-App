<template>
    <div class="h-screen p-5 bg-slate-200">
        <div class="flex flex-wrap gap-5">
            <VaCard :bordered="false">
                <VaCardTitle>
                    Users
                </VaCardTitle>
                <VaCardContent class="text-6xl">
                    {{ users.count }}
                </VaCardContent>
            </VaCard>

            <VaCard :bordered="false">
            <VaCardTitle>Thesis</VaCardTitle>
            <VaCardContent class="text-6xl">
                {{ thesis.active.count }}
            </VaCardContent>
            </VaCard>

            <VaCard :bordered="false">
            <VaCardTitle>Outdated Thesis</VaCardTitle>
            <VaCardContent class="text-6xl">
                {{ thesis.outdated.count }}
            </VaCardContent>
            </VaCard>
        </div>
        <div class="flex flex-wrap gap-5 py-5">
            <VaCard :bordered="false">
            <VaCardTitle>Authors</VaCardTitle>
            <VaCardContent class="text-6xl">
                {{ authors.count }}
            </VaCardContent>
            </VaCard>
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
                    this.thesis.active.count = response.data.result.thesis;
                }).catch(error => {
                    console.log("error: ". error);
                });
        },
    }
}

</script>