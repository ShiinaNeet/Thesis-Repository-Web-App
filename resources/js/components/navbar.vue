<template>
   <VaNavbar
    color="#282F69"
    class="h-24"
  >
    <template #left>
      <VaNavbarItem class="logo" >
        <div
        @click="$root.auth && $root.auth.userType === 3 ? null : $root.redirectToPage('/dashboard')"
        >
          Thesis Repository
        </div>
      </VaNavbarItem>
    </template>
    <template #right>
      <div
      v-if="$root.auth && $root.auth.userType"
      class="flex gap-2">
        <VaNavbarItem
         class="hidden sm:block justify-center items-center ">
          <div
          v-if="$root.auth && $root.auth.userType !== 3"
          :class="{'rounded bg-blue-600 active': $root.isActivePage('/dashboard')}"
          @click="$root.redirectToPage('/dashboard')"
          >
            <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Dashboard</span>
          </div>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block">
          <div
          v-if="$root.auth "
          :class="{'rounded bg-blue-600 active': $root.isActivePage('/search') || $root.isActivePage('/sdashboard')}"
          @click="$root.redirectToPage('/search')"
          >
            <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Thesis</span>
          </div>
        </VaNavbarItem>
        <VaNavbarItem class="hidden sm:block mr-3 max-sm:m-0">
          <div
          v-if="$root.auth && $root.auth.userType !== null"
          >
            <VaDropdown>
              <template #anchor>
                <span class="flex flex-col px-3 py-3 rounded hover:bg-blue-600">
                  <p v-for="type in types" class="text-large">
                    {{ $root.auth.userType === type.value ? type.label : '' }}
                  </p>
                </span>
                
              </template>

              <VaDropdownContent>
                <VaButton
                class="text-white rounded hover:bg-blue-600 m-0"
                preset="secondary"
                hover-behavior="opacity"
                :hover-opacity="0.4"
                @click="$root.redirectToPage('/logout')"
                > 
                  <span>Logout</span> 
                </VaButton>
              </VaDropdownContent>
            </VaDropdown>
          </div>
          <div
          v-else
          >
            <VaDropdown>
              <template #anchor>
                <div>
                  <span class="text-white rounded bg-inherit py-3 px-3 hover:bg-blue-600">Hello, Guest!</span>
                </div>
              </template>

              <VaDropdownContent>
                <VaButton
                class="text-white rounded hover:bg-blue-600 m-0"
                preset="secondary"
                hover-behavior="opacity"
                :hover-opacity="0.4"
                @click="$root.redirectToPage('/login')"
                > 
                  <span>Login</span> 
                </VaButton>
              </VaDropdownContent>
            </VaDropdown>
          </div>
        </VaNavbarItem>
      </div>
    </template>
  </VaNavbar>
  
</template>

<script>
import { VaButton } from 'vuestic-ui/web-components';

export default {
  data() {
      return{
        loginDelay: 100,
        usertype: "Guest",
        types: [
                { label: "Administrator", value: 0, color: "warning" },
                { label: "Assistant Librarian", value: 1, color: "primary" },
                { label: "Librarian", value: 2, color: "info"},
                { label: "Student", value: 3, color: "success" }
            ]
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
        console.log(error);
      
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
