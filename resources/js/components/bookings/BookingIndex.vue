<template>
    <section>
        <b-row class="mt-5 mb-1">
            <h4>Welcome, {{ user }}!</h4>
            <b-col sm="12" md="12" lg="12" class="header-items">
                <h1 class="header-title">List of Bookings</h1>
            </b-col>
            <b-col sm="12" md="12" lg="12">
                <b-button variant="primary" v-if="isLoggedIn" @click="modalShow = true">Book a room</b-button>
            </b-col>
        </b-row>
    </section>
      
    <div v-if="rows && rows.length > 0">
        <b-col sm="12" md="4" lg="4">
        <!-- search input -->
            <div class="demo-vertical-spacing">
            <b-form-group>
                <!-- basic search -->
                <b-input-group class="input-group-merge search-textbox">
                    <b-input-group-prepend is-text>
                        <vue-feather type="search"></vue-feather>
                    </b-input-group-prepend>
                    <b-form-input
                        placeholder="Search"
                        v-model="searchTerm"
                        type="text"
                        class="d-inline-block"
                    />
                </b-input-group>
            </b-form-group>
            </div>
        </b-col>
        <b-card>
            <vue-good-table
                :columns="columns"
                :rows="rows"
                :search-options="{ enabled: true, externalQuery: searchTerm }"
                :select-options="{ enabled: isLoggedIn ? true : false }"
                :pagination-options="{ enabled: true, perPage: pageLength }"
                styleClass="vgt-table bordered striped"
                compactMode
                :sort-options="{
                    enabled: true,
                    initialSortBy: {field: 'booking_date', type: 'asc'}
                }"
                :v-on:selected-rows-change="isLoggedIn ? selectionChanged : ''"
            >
                <template #selected-row-actions>
                    <b-button variant="secondary" class="mr-2" v-on:click="showEditModal = true" v-if="showEdit">Edit</b-button>
                    <b-button variant="secondary" v-on:click="confirmDelete()">Delete</b-button>
                </template>
            </vue-good-table>
        </b-card>
    </div>
    <div class="misc-inner p-2 p-sm-3" v-else>
        <div class="w-100 text-center">
        <vue-feather type="search"></vue-feather>
        <h2 class="mb-1">No results found</h2>
        <p class="mb-2">We couldn't find what you are looking for.</p>
        </div>
    </div>
    
    <b-modal 
        v-model="modalShow"
        hide-footer
        title="Create new Booking"
    >
        <b-row>
            <b-col sm="12" md="12" lg="12">
                <b-form-group label="Room" label-for="room">
                    <v-select
                        v-model="formData.room" 
                        label="room_name"
                        :options="meetingRooms"
                        class="rounded-pill bg-light-secondary"
                    />
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="6">
                <b-form-group
                    label-for="start-booking"
                    label="Start of booking:"
                >
                    <div class="form-label-group">
                        <b-input-group class="mb-1" >
                            <Datepicker v-model="formData.startBookingDate">
                                <template #time-picker-overlay="{ hours, minutes, setHours, setMinutes }">
                                    <div class="time-picker-overlay">
                                    <span>Hour:</span>
                                    <select class="select-input" :value="hours" @change="setHours(+$event.target.value)">
                                        <option v-for="h in hoursArray" :key="h.value" :value="h.value">{{ h.text }}</option>
                                    </select>
                                    <span>Minutes:</span>
                                    <select class="select-input" :value="minutes" @change="setMinutes(+$event.target.value)">
                                        <option v-for="m in minutesArray" :key="m.value" :value="m.value">{{ m.text }}</option>
                                    </select>
                                    </div>
                                </template>
                            </Datepicker>
                        </b-input-group>
                    </div>
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="6">
                <b-form-group
                    label-for="end-booking"
                    label="End of booking:"
                >
                    <div class="form-label-group">
                        <b-input-group class="mb-1" >
                            <Datepicker v-model="formData.endBookingDate">
                                <template #time-picker-overlay="{ hours, minutes, setHours, setMinutes }">
                                    <div class="time-picker-overlay">
                                    <span>Hour:</span>
                                    <select class="select-input" :value="hours" @change="setHours(+$event.target.value)">
                                        <option v-for="h in hoursArray" :key="h.value" :value="h.value">{{ h.text }}</option>
                                    </select>
                                    <span>Minutes:</span>
                                    <select class="select-input" :value="minutes" @change="setMinutes(+$event.target.value)">
                                        <option v-for="m in minutesArray" :key="m.value" :value="m.value">{{ m.text }}</option>
                                    </select>
                                    </div>
                                </template>
                            </Datepicker>
                        </b-input-group>
                    </div>
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="12">
                <small v-if="errorMsg" class="text-danger text-center">{{errorMsg}}</small>
            </b-col>
            <b-col sm="12" md="12" lg="12">
                <small v-if="successMsg" class="text-danger text-center">{{successMsg}}</small>
            </b-col>
        </b-row>
        <b-row class="text-right">
            <b-col sm="12" md="12" lg="12">
                <b-button variant="primary" style="float:right" v-if="showButton" @click="save">Save</b-button>
            </b-col>
        </b-row>
    </b-modal>

    <b-modal 
        v-model="showEditModal"
        hide-footer
        title="Edit Booking"
    >
        <b-row>
            <b-col sm="12" md="12" lg="12">
                <b-form-group label="Room" label-for="room">
                    <v-select
                        v-model="formData.room" 
                        label="room_name"
                        :options="meetingRooms"
                        class="rounded-pill bg-light-secondary"
                    />
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="6">
                <b-form-group
                    label-for="start-booking"
                    label="Start of booking:"
                >
                    <div class="form-label-group">
                        <b-input-group class="mb-1" >
                            <Datepicker v-model="formData.startBookingDate">
                                <template #time-picker-overlay="{ hours, minutes, setHours, setMinutes }">
                                    <div class="time-picker-overlay">
                                    <span>Hour:</span>
                                    <select class="select-input" :value="hours" @change="setHours(+$event.target.value)">
                                        <option v-for="h in hoursArray" :key="h.value" :value="h.value">{{ h.text }}</option>
                                    </select>
                                    <span>Minutes:</span>
                                    <select class="select-input" :value="minutes" @change="setMinutes(+$event.target.value)">
                                        <option v-for="m in minutesArray" :key="m.value" :value="m.value">{{ m.text }}</option>
                                    </select>
                                    </div>
                                </template>
                            </Datepicker>
                        </b-input-group>
                    </div>
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="6">
                <b-form-group
                    label-for="end-booking"
                    label="End of booking:"
                >
                    <div class="form-label-group">
                        <b-input-group class="mb-1" >
                            <Datepicker v-model="formData.endBookingDate">
                                <template #time-picker-overlay="{ hours, minutes, setHours, setMinutes }">
                                    <div class="time-picker-overlay">
                                    <span>Hour:</span>
                                    <select class="select-input" :value="hours" @change="setHours(+$event.target.value)">
                                        <option v-for="h in hoursArray" :key="h.value" :value="h.value">{{ h.text }}</option>
                                    </select>
                                    <span>Minutes:</span>
                                    <select class="select-input" :value="minutes" @change="setMinutes(+$event.target.value)">
                                        <option v-for="m in minutesArray" :key="m.value" :value="m.value">{{ m.text }}</option>
                                    </select>
                                    </div>
                                </template>
                            </Datepicker>
                        </b-input-group>
                    </div>
                </b-form-group>
            </b-col>
            <b-col sm="12" md="12" lg="12">
                <small v-if="errorMsg" class="text-danger text-center">{{errorMsg}}</small>
            </b-col>
            <b-col sm="12" md="12" lg="12">
                <small v-if="successMsg" class="text-danger text-center">{{successMsg}}</small>
            </b-col>
        </b-row>
        <b-row class="text-right">
            <b-col sm="12" md="12" lg="12">
                <b-button variant="primary" style="float:right" v-if="showButton" @click="update">Update</b-button>
            </b-col>
        </b-row>
    </b-modal>
</template>

<script>
import { VueGoodTable } from 'vue-good-table-next'
import axios from 'axios'
import vSelect from 'vue-select'
import { ValidationProvider } from 'vee-validate'
import 'vue-select/dist/vue-select.css';
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import moment from 'moment'
import { useToast } from "vue-toastification"

export default {
components: {
    VueGoodTable,
    ValidationProvider,
    Datepicker,
    moment,
    vSelect
},
setup() {
    const toast = useToast();

    const hoursArray = [];
    for (let i = 8; i < 19; i++) {
        hoursArray.push({ text: i < 10 ? `0${i}` : i, value: i });
    }

    const minutesArray = []
    minutesArray.push({ text: '00', value: '00' }, { text: 30, value: 30 });
    
    return {
        hoursArray,
        minutesArray,
        toast
    }
},
data() {
    return {
        rows: [],
        searchTerm: '',
        currentPage: 1,
        perPage: 15,
        pageLength: 15,
        columns: [
            { label: 'Meeting Room', field: 'meeting_room.room_name' },
            { label: 'Booked By', field: 'user.name' },
            { label: 'Date', field: 'booking_date' },
            { label: 'Booking Time From', field: 'booking_time_from' },
            { label: 'Booking Time To', field: 'booking_time_to' }
        ],
        modalShow: false,
        meetingRooms: [],
        formData: {
            room: '',
            startBookingDate: new Date(),
            endBookingDate: new Date(),
        },
        errorMsg: '',
        bookingTime: [],
        showButton: true,
        successMsg: '',
        showEdit: true,
        selectedRows: [],
        showEditModal: false,
        bookingId: 0,
        isLoggedIn: JSON.parse(localStorage.getItem('userData')),
        user: JSON.parse(localStorage.getItem('userData'))?.name ?? 'Guest'
    }
},
created() {
    this.getData()
},
methods: {
    async getData() {
        try {
                const csrf = await axios.get('/sanctum/csrf-cookie')

                if(csrf.config.headers){
                    const bookings = await axios.get('/api/booking-app/get-bookings')
                    const rooms = await axios.get('/api/booking-app/rooms')
                    
                    if (bookings.status === 200) {
                        this.rows = bookings.data
                    }

                    if (rooms.status === 200) {
                        this.meetingRooms = rooms.data
                    }
                }
        } catch (err) {
          console.log(err)
        }
    },
    validateHours () {
        let isValid = true
        let startDate = moment(this.formData.startBookingDate).format('YYYY-MM-DD')
        let endDate = moment(this.formData.endBookingDate).format('YYYY-MM-DD')

        //check if date is the same
        if(!moment(startDate).isSame(endDate)){
            isValid = false
        }
        
        //check if start time is not greater end time
        if(moment(this.formData.startBookingDate).isAfter(this.formData.endBookingDate)){
            isValid = false
        }
        
        return isValid
    },
    async save(){
        try {
            const response = await axios.post('/api/booking-app/save', {
                roomId: this.formData.room.id,
                bookingDate: moment(this.formData.endBookingDate).format('YYYY-MM-DD'),
                startTime: moment(this.formData.startBookingDate).format('hh:mm A'),
                endTime: moment(this.formData.endBookingDate).format('hh:mm A'),
                user: this.isLoggedIn.id
            })

            if (response.status === 200) {
                this.getData()
                this.notify(response.data.message)
                this.modalShow = false

            }
        } catch (err) {
          this.errorMsg = err.response.data.message
        }
    },
    async update(){
        try {
            const response = await axios.post(`/api/booking-app/update/${this.bookingId}`, {
                roomId: this.formData.room.id,
                bookingDate: moment(this.formData.endBookingDate).format('YYYY-MM-DD'),
                startTime: moment(this.formData.startBookingDate).format('hh:mm A'),
                endTime: moment(this.formData.endBookingDate).format('hh:mm A'),
                user: this.isLoggedIn.id
            })

            if (response.status === 201) {
                this.getData()
                this.notify(response.data.message)
                this.showEditModal = false

            }
        } catch (err) {
          this.errorMsg = err.response.data.message
        }
    },
    selectionChanged(params) {
        this.showEdit = true

        if(params.selectedRows.length !== 0){
            this.selectedRows = params.selectedRows

            let data = JSON.parse(JSON.stringify(params.selectedRows))
            let startTime = `${moment(data[0].booking_date + ' '+ data[0].booking_time_from).format('HH:mm')}`
            let endTime = `${moment(data[0].booking_date + ' '+ data[0].booking_time_to).format('HH:mm')}`
            this.bookingId = data[0].id

            this.formData.room = this.meetingRooms.find((item) => item.id === data[0].meeting_room.id)
            this.formData.startBookingDate = `${moment(data[0].booking_date).format('MM/DD/YYYY')}, ${startTime}`
            this.formData.endBookingDate = `${moment(data[0].booking_date).format('MM/DD/YYYY')}, ${endTime}`

        }else if(params.selectedRows.length > 1){
            this.showEdit = false
        }
    },
    confirmDelete() {
      this.$swal({
        title: 'Delete booking',
        text: 'Are you sure you want to delete this booking?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn bg-light-secondary ml-1'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.value === true) {
            axios.post('/api/booking-app/delete', {rows: this.selectedRows, user: this.isLoggedIn.id}).then((response) => {
            if (response.status === 200) {
                this.getData()
                this.notify(response.data.message)
            }
          })
        }
      })
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
watch: {
    formData: {
      handler: function () {
        let isOperatingHoursValid = true
        this.errorMsg = ''
        this.successMsg = ''
        this.showButton = true
        
        isOperatingHoursValid = this.validateHours()
        
        if(!isOperatingHoursValid){
            this.showButton = false
            this.errorMsg = 'Invalid date and time! Please check again.'
        }
        
        //check if the booking time is not greater than 1 hour
        if(parseInt(moment.utc(moment(this.formData.endBookingDate,"DD/MM/YYYY HH:mm").diff(moment(this.formData.startBookingDate,"DD/MM/YYYY HH:mm"))).format("HH:mm") > 1)){
            this.showButton = false
            this.errorMsg = 'Booking time is 30 minute or 1 hour long only!'
        }
      },
      deep: true
    },
}

}
</script>
<style>
    .red-color {
        color: red;
    }

    .time-picker-overlay {
        display: flex;
        height: 100%;
        flex-direction: column;
    }

    .select-input{
        margin: 5px 3px;
        padding: 5px;
        width: 100px;
        border: 1px solid #ddd !important;
        outline: none;
    }

    .dp__overlay_container{
        margin-left: 10px;
    }
</style>