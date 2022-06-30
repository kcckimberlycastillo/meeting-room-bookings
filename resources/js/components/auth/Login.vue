<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-8">

        <div class="card card-default">
          <div class="card-header"> <h5>Login</h5></div>
          <div class="card-body"> 
            <b-form>
              <b-form-group label-for="login-email">
                  <b-form-input
                    id="login-email"
                    v-model="email"
                    name="login-email"
                    placeholder="john@example.com"
                    class="form-control"
                  />
              </b-form-group>
              
              <b-form-group>
                <b-form-input
                  id="login-password"
                  type="password"
                  v-model="password"
                  class="form-control"
                  name="login-password"
                  placeholder="Enter Password"
                />
              </b-form-group>

              <b-button type="submit" variant="primary" block @click="login" size="lg">
                Login
              </b-button>
            </b-form>

            <b-card-text class="text-center mt-2 mb-1">
              <span>Don't have an account? </span>
              <b-link :to="{ name: 'auth-register' }">
                <span>Sign Up</span>
              </b-link>
            </b-card-text>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { useToast } from "vue-toastification"

export default {
  setup() {
    const toast = useToast();

    return {
        toast
    }
  },
  data(){
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login(){
      if(this.password.length > 0 && this.email){
        axios.get('/sanctum/csrf-cookie').then(response => {
          console.log(response.config.headers)
          axios.post('/api/auth/login', {
            email: this.email,
            password: this.password
          })
          .then(res => {
            if(res.status === 200){
              localStorage.setItem('userData', JSON.stringify(res.data.userData))
              this.$router.replace({name: 'bookings'})
            }
          })
          .catch(() => {
            this.notify("Your email or password is invalid or the account doesn't exists.")
          })
        })
      }
    },
    notify(title) {
        this.toast(title, {
          position: "top-right",
          timeout: 5000,
          closeOnClick: true,
          pauseOnFocusLoss: true,
          pauseOnHover: true,
          draggable: true,
          draggablePercent: 0.6,
          showCloseButtonOnHover: false,
          hideProgressBar: true,
          closeButton: "button",
          icon: true,
          rtl: false
        });
    },
  },
}
</script>
