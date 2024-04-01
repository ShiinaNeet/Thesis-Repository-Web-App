<template>
   <VaNavbar
    color="#282F69"
    class="h-24"
  >
    <template #left>
      <VaNavbarItem class="logo" >
        <div
        @click="$root.redirectToPage('/dashboard')"
        >
          Thesis Repository
        </div>
      </VaNavbarItem>
    </template>
    <template #right>
      <div
      v-if="$root.auth"
      class="flex gap-0">
        <VaNavbarItem
         class="hidden sm:block bg-inherit justify-center items-center ">
          <VaButton
          v-if="$root.auth && $root.auth.userType == 0"
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
          v-if="$root.auth && $root.auth.userType !== null"
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
