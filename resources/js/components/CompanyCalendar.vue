<template>
    <div id="bmk__companycalendar">
        <div class="bmk__view companycalendar__view">
            <leftbar :user="raw_user"></leftbar>
            <div class="companycalendar__upbar">
                <div class="companycalendar__legend">
                    <div class="companycalendar__element" v-for="(element,i) in lengend" :key="i">
                        <div class="color" v-bind:style="{ background: element.color}"></div>
                        <p class="value">{{element.value}}.</p>
                    </div>
                </div>
                <div class="companycalendar__title">
                    <div class="companycalendar__title__content"> 
                        <calendar-icon size="2x" class="menuIcon"></calendar-icon>
                        <p class="companycalendar__text">COMPANY CALENDAR</p>
                    </div>
                </div>

                <!--div class="companycalendar__dropdown">
                    <select name="calendarSelector" class="calendar__selector" id="calendarSelector">
                        <option class="recordType" v-for="(typ,i) in types" :key="i" :value="typ.name">
                            {{typ.name}}
                        </option>
                    </select>
                </div-->
                <div class="companycalendar__incompatibilities">
                    <button @click="filter" class="companycalendar__incompatibilities__button" id="incomp_button" type="submit">show department holidays</button>
                </div>

                <div class="help__link" @click="launchHelp">
                    <p>Help</p>
                    <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                </div>
               
            </div>
             
            <div class="companycalendar__content bmk__view__content">
                <holidaycalendar :events="events" :filtered="filtered" :filter = "toFilter"></holidaycalendar>
            </div>
            <v-tour name="companycalendarTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';

import User from '../models/User';
import axios from "axios";

import {PlusIcon} from 'vue-feather-icons';
import { CalendarIcon } from 'vue-feather-icons';
import {HelpCircleIcon } from 'vue-feather-icons'


import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';




@Component({
    components: {
        PlusIcon, 
        
        Leftbar, UpperMenu, HelpCircleIcon,

        CalendarIcon
    }
})
export default class CompanyCalendar extends Vue {

    @Prop(Object)
    raw_user!: {};

    @Prop()
    events!: any[];

    filtered: any[] = [];

    toFilter = false; 


    lengend = [
            {color: "#97BC8C", value: "Medical leave"}, 
            {color: "#F5CDB7", value: "Free day"},
            {color: "#ECDFBF", value: "Other"}
    ]

    types = [
        {name: 'week'},
        {name: 'fortnight'},
        {name: 'month'}
    ]



    steps = [
        {
            target: '#summary',
            content: 'Here you can access a summary of your daily activity.'
        },
        {
            target: '#timer',
            content: 'Here you can access your records.'
        },
        {
            target: '#reports',
            content: 'Here you can see a summary of user records.'
        },
        {
            target: '#management',
            content: 'Here you can add, modify and delete other users.'
        },
        {
            target: '.profile',
            content: 'Here you can see your personal information.'
        },
        {
            target: '.companycalendar__incompatibilities__button',
            content: 'Select only events of your department.'
        },
        {
            target: '.bmk__holiday',
            content: 'Here you can see the events of other users.'
        }

    ]
  

    get user() {
        return new User(this.raw_user);
    }

    
    filter(){
        if(this.toFilter==false){
            this.toFilter = true; 
            for(let i = 0; i<this.events.length; i++){
                let other_roles = this.events[i].roles;
                let my_roles = this.events[i].user_role; 
                for(let j = 0; j<other_roles.length; j++){
                    let role = other_roles[j].function_id; 
                    let user = other_roles[j].user_id; 
                    for(let k = 0; k<my_roles.length; k++){
                        let myrole = my_roles[k].function_id; 
                        let myuser = my_roles[k].user_id; 
                        if(myrole == role){
                            this.filtered.push(this.events[i]);
                        }

                    }
                }
            }
            console.log(this.filtered);
            document.getElementById("incomp_button")!.innerText= "show all";
        }else{
            this.toFilter = false; 
            document.getElementById("incomp_button")!.innerText= "show department holidays";
            window.location.href = '/ccalendar';

        }
        return this.filtered;
    }
    
    launchHelp(){
        this.$tours['companycalendarTour'].start();
    }
    

    mounted() {

        //this.$tours['companycalendarTour'].start();

        //this.loadProposals();
    }


}

</script>

