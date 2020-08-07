/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'

Vue.use(VueRouter)


import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
import moment from 'moment'
Vue.component('pagination', require('laravel-vue-pagination'));
import JsonExcel from 'vue-json-excel'

Vue.component('downloadExcel', JsonExcel)
import Dropdown from 'vue-simple-search-dropdown';
Vue.component('Dropdown', Dropdown)

import UserLogin from './components/Login'
import ContactUs from './components/ContactUs'
import UserSignup from './components/Register'
import WorkPost from './components/WorkPost'
import ClaimWork from './components/ClaimWork'
import Package from './components/Package'
import Blog from './components/Blog'
import MyAccount from './components/MyAccount'
import Achievers from './components/Achievers'
import DebitRequest from './components/DebitRequest'
import MyWork from './components/MyWork'
import MyClaimedWork from './components/MyClaimedWork'
import ResetPassword from './components/ResetPassword'
import SubmitWork from './components/SubmitWork'

// const router = new VueRouter({
//     mode: 'history',
//     routes: [
//         {
//             path: '/',
//             name: 'home',
//             component: Home
//         },
//
//     ],
// });


const userapp = new Vue({
    el: '#user-app',
    components:{MyClaimedWork,ClaimWork,Blog,SubmitWork,ContactUs,UserLogin,UserSignup,WorkPost,Package,MyAccount,MyWork,DebitRequest,ResetPassword,Achievers},
    //router
});

Vue.filter('getStatus', function (value) {
    if(value==0){
        return "Pending"
    }else if(value==1){
        return "Progress"
    }else if(value==2){
        return "Deal"
    }else if(value==3){
        return "Completed"
    }else if(value==4){
        return "Delivered"
    }else if(value==5){
        return "Cancelled"
    }

});

Vue.filter('formatDate', function(value) {

    if (value) {

        return moment(String(value)).format('DD/MM/YYYY hh:mm A')

    }

});
Vue.filter('formatTime', function(value) {

    if (value) {

        return moment.unix(value).format("DD/MM/YYYY hh:mm A");

    }

});

Vue.filter('striphtml', function (value,limit) {
    var div = document.createElement("div");
    div.innerHTML = value;
    var text = div.textContent || div.innerText || "";
    text = text.substring(0, (limit - 3)) + '...';
    return text;
});


