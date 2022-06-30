<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-8">

        <div class="card card-default">
          <div class="card-header"> <h5>Register</h5></div>
          <div class="card-body"> 
            <b-form>
              <b-form-group label-for="login-name">
                  <b-form-input
                    id="login-name"
                    v-model="name"
                    name="login-name"
                    placeholder="Enter FullName"
                    class="form-control"
                  />
              </b-form-group>

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

              <b-button type="submit" variant="primary" block @click="register" size="lg">
                Register
              </b-button>
            </b-form>

            <b-card-text class="text-center mt-2 mb-1">
              <span>Have an account? </span>
              <b-link :to="{ name: 'auth-login' }">
                <span>Login</span>
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
      name:'',
      email: '',
      password: ''
    }
  },
  methods: {
    register(){
      if(this.password.length > 0 && this.email){
        axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('/api/auth/register', {
            name: this.name,
            email: this.email,
            password: this.password
          })
          .then(res => {
            if(res.status === 201){
              this.notify(res.data.message)
              this.$router.replace({name: 'auth-login'})
            }
          })
          .catch((error) => {
            this.notify(error.response.data.message.length > 1 ? 'Invalid input!' : error.response.data.message.length )
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
  }
}
</script>
