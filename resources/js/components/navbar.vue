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
      class="flex gap-2">
        <VaNavbarItem
         class="hidden sm:block justify-center items-center ">
          <div
          v-if="$root.auth && $root.auth.userType == 3"
          :class="{'rounded bg-blue-600 active': $root.isActivePage('/dashboard')}"
          @click="$root.redirectToPage('/dashboard')"
          >
            <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Dashboard</span>
          </div>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block">
          <div
          v-if="$root.auth"
          :class="{'rounded bg-blue-600 active': $root.isActivePage('/search')}"
          @click="$root.redirectToPage('/search')"
          >
            <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Thesis</span>
          </div>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block">
          <div
          v-if="$root.auth && $root.auth.userType !== null"
          :class="{'rounded bg-blue-600 active': $root.isActivePage('/logout')}"
          @click="$root.redirectToPage('/logout')"
          >
            <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Logout</span>
          </div>
        </VaNavbarItem>
      </div>
    </template>
  </VaNavbar>
  
</template>

<script>
export default {
  data() {
      return{
        loginDelay: 100,
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
