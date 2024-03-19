<template>
   <VaNavbar
    color="#282F69"
    class="h-24"
  >
    <template #left>
      <VaNavbarItem class="logo">
        Thesis Repository
      </VaNavbarItem>
    </template>
    <template #right>
      <div
      v-if="$root.auth"
      class="flex gap-0">
        <VaNavbarItem
         class="hidden sm:block bg-inherit justify-center items-center ">
          <VaButton
          class=""
          preset="secondary"
          hover-behavior="opacity"
          :hover-opacity="0.4"
          @click="$root.redirectToPage('/dashboard')"
          >
            <span class="text-white">Dashboard</span>
          </VaButton>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block">
          <VaButton
          v-if="$root.auth"
          class=""
          preset="secondary"
          hover-behavior="opacity"
          :hover-opacity="0.4"
          @click="$root.redirectToPage('/search')"
          >
            <span class="text-white">Thesis</span>
          </VaButton>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block">
          <VaButton
          v-if="$root.auth && $root.auth"
          class=""
          preset="secondary"
          hover-behavior="opacity"
          :hover-opacity="0.4"
          @click="$root.redirectToPage('/logout')"
          >
            <span class="text-white">Logout</span>
          </VaButton>
        </VaNavbarItem>
      </div>
    </template>
  </VaNavbar>
  
</template>

<script setup>
import { computed } from "vue";
import { useColors } from "vuestic-ui";

const { currentPresetName } = useColors();

const darkNavbarColors = computed(() => {
  if (currentPresetName.value === "light") {
    return {
      color: "#111111",
      textColor: "#BAFFC5",
    };
  } else {
    return {
      color: "#FBCAF6",
      textColor: "#481269",
    };
  }
});


</script>
<script>
export default {
  data() {
      return{

      }
      },
  methods:{
    logout(){
      axios({
        method: 'POST',
        type: 'JSON',
        url: '/logout',
        data: {
            userID: this.account.login.userId,
        }
      }).then(response => {
          if (response.data.status == 1) {
              setTimeout(() => {
                  window.location = response.data.redirect;
              }, this.loginDelay);
          } else {
            
          }
      }).catch(error => {
        console.log("test");
      
      });
    }
  }
}
</script>


<style scoped>
.logo {
  font-weight: 600;
  font-size: 1.5rem;
}
</style>
