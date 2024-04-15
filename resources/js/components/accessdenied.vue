<template>
    <navbar />
    <div class="text-black flex-center justify-center h-screen">
        <h1 class="text-bold text-2xl">Access Denied</h1>
        <template>
          
            <img src="/public/images/access_denied_image.png" alt="Image" width="500" height="500"/>
        </template>
    </div>
</template>


<script>
import navbar from './navbar.vue';
import formatDate from "@/functions/formatdate.js";
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
