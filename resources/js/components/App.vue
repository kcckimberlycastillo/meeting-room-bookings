<template>
<div class="container">
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
      <router-link class="p-2 cursor-pointer" to='/'>Home</router-link> 
      <div class="navbar-nav" v-if="isLoggedIn && this.$route.meta.resource !== 'Auth'">
          <a class="nav-item nav-link cursor-pointer" @click="logout">Logout</a>
      </div>
      <div class="navbar-nav" v-if="!isLoggedIn && this.$route.meta.resource !== 'Auth'">
        <router-link class="p-2 cursor-pointer" to='/login'>Login</router-link> 
          <router-link class="p-2 cursor-pointer" to='/register'>Register</router-link>
      </div>
    </nav>
    
    <router-view></router-view>

</div>
</template>

<script>
import axios from 'axios'

export default {
    components:{
    },
    data() {
      return {
        isLoggedIn: JSON.parse(localStorage.getItem('userData')),
      }
    },
    methods: {
      logout(){
        axios.get('/sanctum/csrf-cookie').then(() => {
          axios.post('/api/logout')
          .then(response => {
            if(response.status === 200){
              localStorage.removeItem('userData')
              this.$router.push({ name: 'auth-login' })
            }else{
              console.log(response);
            }
          })
          .catch(function(error){
            console.log(error);
          })
        })
      }
    }
}
</script>
<style scoped>
  .cursor-pointer{
    cursor: pointer;
  }
</style>