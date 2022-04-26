<template>
    <div id="bmk__mycalendar">
        <div class="bmk__view mycalendar__view">
            <leftbar :user="raw_user"></leftbar>
            <calendarbar :title="'my calendar'" :events="events"></calendarbar>

            <div class="mycalendar__content bmk__view__content">
                <div class="mycalendar__months">
                    <vc-calendar
                        :attributes='attrs'
                        :columns="$screens({ default: 1, sm:1, md:2, lg: 3, xl:4 })"
                        :rows="$screens({ default: 1, lg: 2 })"
                        :is-expanded="$screens({ default: true, lg: false })">
                    </vc-calendar>
                </div>
                
                <div class="mycalendar__options">
                    <div class="select__event__visibility">
                        <label class="label__visibility">Select own events visibility</label>
                        <input @click="changeEventVisibility" v-if="this.raw_user.event_visibility==true" checked class="button__visibility" type="checkbox"></input>
                        <input @click="changeEventVisibility" v-else class="button__visibility" type="checkbox"></input>

                         <div class="help__link" @click="launchHelp">
                            <p>Help</p>
                            <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                        </div>
                    </div>

                    

                    <div class="mycalendar__button__zone">
                        <button @click="changeVisibility" data-title="Ask for a day" class="mycalendar__button"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-plus" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </button>    
                    </div>
                                                   
                </div>
            </div>
            
            <div id="addVacation" class="mycalendar__add__vacation">
                <div class="exit__button">
                    <x-circle-icon @click="close" size="4x" class="vacation__exit__icon"></x-circle-icon>
                </div>
                <div class="vacation__title">
                    <p class="vacation__title__text">ask for free period</p>
                </div>
                <div class="form-line addvacation__form__line">
                    <div class="form-element addvacation__form__element">
                        <label for="event__name" class="addvacation__form__label">
                            Event name: 
                        </label>
                        <input id="event__name" class="addvacation__form__input" name="event__name" type="text"/>
                    </div>
                    <div class="form-element addvacation__form__element">
                        <label for="event__type" class="addvacation__form__label">
                            Event type: 
                        </label>
                        <select name="typeSelector" class="type__selector" id="typeSelector">
                            <option class="eventType">free day</option>
                            <option class="eventType">medical leave</option>
                            <option class="eventType">other</option>
                        </select>
                    </div>
                    <div class="form-element addvacation__form__element">
                        <label for="event__from" class="addvacation__form__label">
                                From:
                        </label>
                        <input @change="checkDisponibility" class="addvacation__form__date" type="date" id="start" name="trip-start"
                        value=this.today
                        min=this.today max=this.maxDate>
                    </div>
                    <div class="form-element addvacation__form__element">
                        <label for="event__to" class="addvacation__form__label">
                            To:
                        </label>
                        <input @change="checkDisponibility" class="addvacation__form__date" type="date" id="end" name="trip-end"
                        value=this.today
                        min=this.today max=this.maxDate>
                    </div>
                    <div class="addvacation__error__message"> {{this.message}} </div> 
                </div>
                <div class="addvacation__button__container">
                    <button @click="submit" type="submit" class="addvacation__button">Add event</button>
                </div>
            </div>
            <v-tour name="mycalendarTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>

<script lang="ts">


import {Component, Prop, Vue} from 'vue-property-decorator';

import User from '../models/User';
import axios from "axios";

// Icons 
import {PlusIcon} from 'vue-feather-icons';
import { XCircleIcon } from 'vue-feather-icons'
import {HelpCircleIcon } from 'vue-feather-icons'


import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';

import * as moment from 'moment';

@Component({
    components: {
        PlusIcon, XCircleIcon, HelpCircleIcon,
        
        Leftbar, UpperMenu
    }
})
export default class MyCalendar extends Vue {

    @Prop(Object)
    raw_user!: {};

    @Prop()
    events!: any[];

    @Prop()
    others!: any[];

    @Prop()
    disponibility!: any[];

    dpitems: string[][] = [['TODAY', 'THIS WEEK', 'THIS MONTH'], ['Planned', 'Extra', 'Worked']]
    types = ['list', 'daily', 'weekly']

    today = moment().format("YYYY-MM-DD");
    maxDate = moment().add(1,'y').format("YYYY-MM-DD");
    
    message = ""; 

    attrs = [
            {
            key: 'today',
            highlight: 
            {
                style: {background: '#DE9C87'},
                contentStyle: {color: 'white'},
            },
            
            dates: new Date(),
            },
    ];


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
            target: '.calendarBar',  // We're using document.querySelector() under the hood
            content: `Here you can find the legend of event types and your next events`
        },
        {
            target: '.mycalendar__months',
            content: 'Here you can see your events.'
        },
        {
            target: '.select__event__visibility',
            content: 'Here you can change if others can see your events.'
        },
        {
            target: '.mycalendar__button',
            content: 'Here you can ask for other event.'
        }

    ]
  

        
    get user() {
        return new User(this.raw_user);
    }


    checkOtherUsersEvents(start:string, end:string){
        console.log("START TRIED");
        console.log(start);
        console.log("END TRIED");
        console.log(end);

        let disponible = true; 

        for(let us of this.disponibility){
            for(let ev of us.events){
                let start2 = (moment(new Date(ev.staring_date)).format('YYYY-MM-DD'));
                let end2 = (moment(new Date(ev.ending_date)).format('YYYY-MM-DD'));

                if(end>start2 && end2>start){
                    disponible = false; 
                }
            }
        }
        

        return disponible; 
    }

    checkDisponibility(){

        // Start selected
        let start = (<HTMLInputElement>document.getElementById("start")).value;
        // End selected
        let end = (<HTMLInputElement>document.getElementById("end")).value;  
        // Date of today
        let td = (moment(new Date())).format('YYYY-MM-DD');

        // If start is before today
        let startBefore = start!="" && start<td; 
        // If end is before today
        let endBefore = end!="" && end<td; 
        // If start is after end 
        let endBeforeStart = start!="" && start>end && end!="";

        if(startBefore || endBefore || endBeforeStart){
            console.log("START OR END BEFORE TODAY");
            this.message = "Dates must be after actual date!";
            if(startBefore){
                console.log("START BEFORE TODAY");
                (<HTMLInputElement>document.getElementById("start")).value = "";
            }
            if(endBefore){
                console.log("END BEFORE TODAY");
                (<HTMLInputElement>document.getElementById("end")).value = "";
            }

            if (endBeforeStart){
                this.message = "End date must be after Start date!";
                (<HTMLInputElement>document.getElementById("start")).value = "";
            }
        
        }else{
            if(start!="" && end!=""){
                let disponible = this.checkOtherUsersEvents(start,end);
                if(disponible){
                    console.log("DATES OK");
                    this.message = "";
                }else{
                    this.message = "A similar user has an event near to these dates!";
                }
            }
        }

    }


    async submit(){

        const inputData = {staring_date: this.getStart(), ending_date: this.getEnd(), event_name: this.getName(), event_type: this.getType()}
        console.log(inputData);
        const response = await axios.post('/postEvent', inputData);
        const data = response.data; 
        console.log("ADDED EVENT");
        console.log(data);
        window.location.href = '/calendar';
    }

    async changeEventVisibility(){
        console.log("CHANGE EVENT VISIBILITY");
        //this.raw_user.event_visibility = !this.raw_user.event_visibility;
        //console.log(this.raw_user.event_visibility);
        const response = await axios.post('/postModifyEventVisibility');
        //window.location.href = '/calendar';
    }


    getName(){
        let name = (<HTMLInputElement>document.getElementById("event__name")).value; 
        return name;
    }

    getType(){
        let type = (<HTMLInputElement>document.getElementById("typeSelector")).value;
        switch(type){
            case "free day": return "free"; break;
            case "medical leave": return "medical"; break; 
            default: return type;
        }
    }

    getStart(){
        let start = (<HTMLInputElement>document.getElementById("start")).value;
        return moment(start).format("DD-MM-YYYY");
    }

     getEnd(){
        let end = (<HTMLInputElement>document.getElementById("end")).value;
        return moment(end).format("DD-MM-YYYY");
    }

    changeVisibility(){
        (<HTMLInputElement>document.getElementById("addVacation")).style.visibility= "visible";
    }

    close(){
        (<HTMLInputElement>document.getElementById("addVacation")).style.visibility= "hidden";
    }


  

    mounted() {

        console.log("MOUNTED");

        let ev_json:any; 
        let color = '';

        for(let ev of this.events){
            console.log(this.events);
            console.log(ev);
            switch(ev.event_type){
                case('free'): color = '#F5CDB7'; break; 
                case('medical'): color = '#97BC8C'; break; 
                case('other'): color = '#ECDFBF'; break; 
                default: color = '#ECDFBF'; break; 
            }

            ev_json = {
                    key: ev.event_name,
                    highlight: 
                    {
                        style: {background: color},
                        contentStyle: {color: 'white'},
                    },
                    
                    popover: {
                        label: "Evento: " + ev.event_name,
                        visibility: 'focus'
                    },  

                    dates: {start: new Date(ev.staring_date), end: new Date(ev.ending_date)},
                    },
            this.attrs.push(ev_json);
        }

        for(let ev of this.others){
            console.log(this.others);
            console.log(ev);
            ev_json = {
                    key: ev.user,
                    dot: 
                    {
                        style: {background: 'blue'},
                    },
                    popover: {
                        label: "User: " + ev.user + " " + "Event type: " + ev.type,
                        visibility: 'focus'
                    },     

                    dates: {start: new Date(ev.start), end: new Date(ev.end)},
                    },
            this.attrs.push(ev_json);
        }

        //this.$tours['mycalendarTour'].start();


    }

    launchHelp(){
        this.$tours['mycalendarTour'].start();
    }



}

</script>

