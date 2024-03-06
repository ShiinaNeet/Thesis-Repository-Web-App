<template>
    <div 
    class="m-10 flex "
    >
        <div v-if="Object.keys(thesis).length !== 0">
            <h1>{{ thesis.title }}</h1>
            <p><strong>Abstract:</strong> {{ thesis[0].abstract }}</p>
            <p><strong>Published at:</strong> {{ thesis.published_at }}</p>
            <p v-if="thesis[0].author && thesis.author.length > 0"><strong>Author:</strong> {{ thesis[0].author.name }}</p>
            <p v-if="thesis[0].category && thesis.category.length > 0"><strong>Category:</strong> {{ thesis[0].category.category }}</p>
            <p v-if="thesis[0].keywords && thesis.keywords.length > 0"><strong>Keywords:</strong> {{ getKeywords(thesis.keywords) }}</p>
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
</template>

<script>

export default {
    data () {
        return {
            thesis:[],
        };
    },
    props: ['selectedThesisId'],
    mounted() {
        this.getThesis();
    },
    methods:{
        getThesis() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/thesisq/'+this.selectedThesisId,
            }).then(response => {
                if (response.data.status == 1) {
                    this.thesis = response.data.result;
                    console.log(this.thesis);
                
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
    } 
}
</script>