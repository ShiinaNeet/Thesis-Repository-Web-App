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
            <VaCardTitle>Active Thesis</VaCardTitle>
            <VaCardContent class="text-6xl">
                {{ thesis.active.count }}
            </VaCardContent>
            </VaCard>
        </div>
        <div class="flex flex-wrap gap-5 py-5">
            <VaCard :bordered="false">
            <VaCardTitle>Outdated Thesis</VaCardTitle>
            <VaCardContent class="text-6xl">
                {{ thesis.outdated.count }}
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
            },
            thesis:{
                active:{
                    count:0,
                },
                outdated:{
                    count:0,
                }
            }
        }
    }
}

</script>