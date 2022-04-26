/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from "vue";
import * as moment from 'moment';
moment.locale('es');
import VueRouter from "vue-router";

import VCalendar from 'v-calendar';
require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);
const router = new VueRouter({});

//Vue.use(VCalendar);
Vue.use(VCalendar, {
    componentPrefix: 'vc',
});


import * as VueTour from 'vue-tour';
require('vue-tour/dist/vue-tour.css');

Vue.use(VueTour)


Vue.filter('normalizeDuration', function (duration: number, noseconds?: boolean) {
    function addPaddingZeros(num: number) {
        if(num < 9) return '0' + num;
        else return num
    }
    let result = '';
    if(noseconds) {
        result = addPaddingZeros(moment.duration(duration, 'hours').hours()) + ':' + addPaddingZeros(moment.duration(duration, 'hours').minutes());
    } else {
        result = addPaddingZeros(moment.duration(duration, 'hours').hours()) + ':' + addPaddingZeros(moment.duration(duration, 'hours').minutes()) + ':' + addPaddingZeros(moment.duration(duration, 'hours').seconds());
    }
    
    return result
})

Vue.filter('normalizeTimestamp', function (timestamp: Date, noseconds?: boolean) {
    let format = 'HH:mm:ss';
    if(noseconds) format = 'HH:mm';
    return moment(timestamp).format(format)
})



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('main-menu', require('./components/MainMenu.vue').default);
Vue.component('register', require('./components/Register.vue').default);

// Views
Vue.component('login', require('./components/Login.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('timer', require('./components/Timer.vue').default);
Vue.component('mycalendar', require('./components/MyCalendar.vue').default);
Vue.component('companycalendar', require('./components/CompanyCalendar.vue').default);
Vue.component('reports', require('./components/Reports.vue').default);
Vue.component('management', require('./components/Management.vue').default);
Vue.component('addnewuser', require('./components/AddNewUser.vue').default);
Vue.component('modifyuser', require('./components/ModifyUser.vue').default);
Vue.component('myprofile', require('./components/MyProfile.vue').default);



// UIBasics
Vue.component('leftbar', require('./components/UIBasics/Leftbar.vue').default);
Vue.component('leftbaritem', require('./components/UIBasics/LeftbarMenuItem.vue').default);
Vue.component('uppermenu', require('./components/UIBasics/UpperMenu.vue').default);
Vue.component('timerbutton', require('./components/UIBasics/TimerButton.vue').default);
Vue.component('timerbuttoncompacted', require('./components/UIBasics/TimerButtonCompacted.vue').default);
Vue.component('arrow', require('./components/UIBasics/Arrow.vue').default);


// Dashboard
Vue.component('eventtable', require('./components/UIBasics/EventTable.vue').default);

// Timer
Vue.component('timeline', require('./components/UIBasics/TimeLine.vue').default);
Vue.component('record', require('./components/UIBasics/Record.vue').default);
Vue.component('timetracker', require('./components/UIBasics/TimeTracker.vue').default);
Vue.component('gantday', require('./components/UIBasics/GantDay.vue').default);
Vue.component('gantdayhead', require('./components/UIBasics/GantDayHead.vue').default);
Vue.component('gantweek', require('./components/UIBasics/GantWeek.vue').default);

//Calendar
Vue.component('holidaycalendar', require('./components/UIBasics/HolidayCalendar.vue').default);
Vue.component('calendarbar', require('./components/UIBasics/CalendarBar.vue').default);

// Management
Vue.component('managementtable', require('./components/UIBasics/ManagementTable.vue').default); 

// Reports
Vue.component('reporttable', require('./components/UIBasics/ReportTable.vue').default);

Vue.component('javidemo', require('./javidemo/view.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    router,
});

/*
new Vue({
    render: h => h(Login)
}).$mount('#app')*/


