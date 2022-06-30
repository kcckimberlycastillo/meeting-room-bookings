//require('./bootstrap');

import { createApp } from 'vue'
import BootstrapVue3 from 'bootstrap-vue-3'
import VueGoodTablePlugin from 'vue-good-table-next'
import VueFeather from 'vue-feather'
import Datepicker from 'vue3-datepicker'
import VueSweetalert2 from 'vue-sweetalert2'
import Toast from "vue-toastification"

// import the styles 
import 'vue-good-table-next/dist/vue-good-table-next.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css'
import 'sweetalert2/dist/sweetalert2.min.css'
import "vue-toastification/dist/index.css"

import App from './components/App.vue'
import router from './router'

const app = createApp(App)
app.use(router)
app.use(BootstrapVue3)
app.use(VueGoodTablePlugin)
app.use(VueSweetalert2)
app.use(Toast, {
  hideProgressBar: true,
  closeOnClick: false,
  closeButton: false,
  icon: false,
  timeout: 3000,
  transition: 'Vue-Toastification__fade',
})
app.component(VueFeather.name, VueFeather)
app.component('datepicker', Datepicker)
app.mount('#app')
