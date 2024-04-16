<template>
    <navbar />
    <div class="text-black flex-center justify-center h-screen">
        <img 
        src="/access_denied_image.png" 
        alt="Access_Denied_Image"
        class="p-5 w-1/2 h-screen"
        />
    </div>
</template>


<script>
import navbar from './navbar.vue';

export default {
    data() {
        return {
            imageUrl: "public/access_denied_image.png"
        };
    },
    components:{
        navbar: navbar,
    }
};
</script>
