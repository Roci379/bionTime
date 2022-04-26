<template>
    <div id="bmk__timer">
        <div class="bmk__view timer__view">
            <leftbar :user="raw_user"></leftbar>
            <uppermenu class="timerbar__menu" :activity = "activity" :hours="hours" :laststate="laststate" :records="records"> 
            </uppermenu>

            <div class="timer__content bmk__view__content" :class="[timeMode]">
                <div class="records__ddlist" :class="timeMode">
                    
                    <div v-if="timeMode=='daily' || timeMode=='weekly'" class="dailyTimer__day">
                        <button @click="changeDate(-1)" class="dailyTimer__button" id="buttonLeft">
                            <chevron-left-icon size="2x"></chevron-left-icon>
                        </button>

                        <!--p v-if="timeMode=='daily'" class="dailyTimer__text" id="timerDate">Today</p-->
                        <!--p v-if="timeMode=='weekly'" class="dailyTimer__text" id="timerWeek">This week</p-->
                        <p class="dailyTimer__text" id="timerDate">Click to change date</p>

                        <button @click="changeDate(1)" class="dailyTimer__button" id="buttonRight">
                            <chevron-right-icon size="2x"></chevron-right-icon>
                        </button>
                    </div>

                    <div class="help__link" @click="launchHelp">
                        <p>Help</p>
                        <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                    </div>
                    
                    <select @change="changeDate(0)" name="recordSelector" class="record__selector" id="recordsSelector" v-model="timeMode">
                        <option class="recordType" v-for="(typ,i) in types" :key="i" :value="typ.name">
                            {{typ.name}}
                        </option>
                    </select>
                </div>

                <div v-if="timeMode=='list'" class="timeline">
                    <timeline :timeStages="time_stages"></timeline>
                </div>

                <div v-if="timeMode=='list'" class="timer__records">
                    <record :records="records.slice(0,4)" :activity="activity" :records_used="formatZoneRecords(records).slice(0,4)"></record>
                </div>

                <div v-if="timeMode=='daily'" class="gant__daily">
                    <gantday :daily="true" :dailyRecords="daily_records" :formattedDate="formattedDate"></gantday>
                </div>

                <div v-if="timeMode=='weekly'" class="gant__weekly">
                    <gantweek :dailyRecords="daily_records" :date="weeklyDate"></gantweek>
                </div>
            </div>
            <v-tour name="timerTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue, Watch} from 'vue-property-decorator';

// Models 
import {ActivityList} from '../models/ActivityList';
import User from '../models/User';

// Other
import * as moment from 'moment';
import axios from "axios";

// Components
import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';
// import Record from './UIBasics/Record.vue';
import GantDay from './UIBasics/GantDay.vue';
import GantWeek from './UIBasics/GantWeek.vue';
// import HolidayCalendar from './UIBasics/HolidayCalendar.vue';

// Icons
import { ChevronLeftIcon } from 'vue-feather-icons';
import { ChevronRightIcon } from 'vue-feather-icons';
import {HelpCircleIcon } from 'vue-feather-icons'


// import {PlusIcon} from 'vue-feather-icons';




@Component({
    components: {

        Leftbar, UpperMenu,

        GantDay, GantWeek,

        ChevronLeftIcon, ChevronRightIcon, HelpCircleIcon

    }
})
export default class Timer extends Vue {

    // user logged
    @Prop()
    raw_user!: any;

    // list of records
    @Prop()
    records!: any[];

    // hours worked
    @Prop()
    hours!: any[];

    // percentages of time worked
    @Prop()
    time_stages!: any[];

    // records for timer daily view
    @Prop()
    daily_records!: any[];

    // state of last record
    @Prop()
    laststate!: string;


    steps = [
        {
            target: '.timerbar__menu',
            content: `Here you can register new records and see a summary of them.`
        },
        {
            target: '.upperMenu__hours',
            content: 'Here you can select the daily, weekly or monthly summary.'
        },
        {
            target: '.upperMenu__tracker',
            content: 'Here you will see how much time you have been working or in a break.'
        },
        {
            target: '.timer__stop',
            content: 'Click here to stop working.'
        },
        {
            target: '.timer__playpause',
            content: 'CLick here to start working or take a break.'
        },
        {
            target: '.timer__delete',
            content: 'Click here to log out.'
        },
        {
            target: '.record__selector',
            content: 'Here you can change the view of records.'
        },
        {
            target: '.timeline',
            content: 'Here is a summary of the type percentages of your records.'
        },
        {
            target: '.bmk__record__modify__mode',
            content: 'Click here to modify any record.'
        },
        {
            target: '#summary',
            content: 'Here you can access a summary of your daily activity.'
        },
        {
            target: '#calendar',
            content: 'Here you can access calendars of your events and other users events.'
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
        }
       

    ]
    // date of today
    today: Date = new Date();

    //today's date formatted
    formattedDate = (moment(this.today)).format('YYYY-MM-DD');

    // object activityList for timer view
    activity: ActivityList = new ActivityList();

    // kind of timer view selected
    timeMode: string = 'list';

    // day selected
    thisDay:number = 0;
    day:number = 0; 
    selectedDate = "Today";

    // week selected
    week:number = 0; 
    selectedWeek = "This week";
    weeklyDate = this.formattedDate; 
    suma = -3; 

    dateValue = "";

    // Options for dropdown list of upper menu
    dpitems: string[][] = [['TODAY', 'THIS WEEK', 'THIS MONTH'], ['Planned', 'Extra', 'Worked']]
       types = [
            {name: 'list', route: "/timer"},
            {name: 'daily', route: "/timerdaily"},
            {name: 'weekly', route: "/timerweekly"}
            ]

   
    
    @Watch('activity.records')
    changeRecordsValue(){
        console.log("ACTIVITY RECORDS HAS CHANGED IN TIMER BUTTON");
        this.records = this.activity.records;
        window.location.href = "/timer";

    }


    @Watch('activity.daily_records')
    changeDailyRecordsVale(){
        console.log("ACTIVITY DAILY RECORDS HAS CHANGED IN TIMER BUTTON");
        this.daily_records = this.activity.daily_records;
    }

    formatDailyRecords(dailyList: any){
        for(let x=0; x<dailyList.length; x++){
            let groupedDate = dailyList[x];
            for(var clave in groupedDate){
                let registros = groupedDate[clave];
                for(let y=0; y<registros.length; y++){
                    console.log(registros[y].timestamp);
                    var date = moment(registros[y].timestamp);
                    dailyList[x][clave][y].timestamp = date.add(2, 'hours').format("YYYY-MM-DD HH:mm:ss");
                }
            }
        }
        return dailyList;
    }
    formatZoneRecords(recordsList:any){
        for(let x=0; x<recordsList.length; x++){
            let hours = recordsList[x]['hours'];
            for (let y=0; y<hours.length; y++){
                //console.log("FORMAT BEGINNING");
                recordsList[x]['hours'][y].beginning = this.formatZone(recordsList[x]['hours'][y].beginning);
                recordsList[x]['hours'][y].end = this.formatZone(recordsList[x]['hours'][y].end);

                //console.log(recordsList[x]);
            }
        }

        return recordsList;
    }

    formatZone(hour: any){
        var fecha = "1970-01-01 " + hour; 
        var date = moment(fecha);
        return date.add(2, 'hours').format("HH:mm:ss");
    }



    changeDate(arrow: number){

        if(arrow == 0){
            this.changeValueTimer();
        }

        if(this.timeMode == "daily"){
            this.day = this.day + arrow; 
            console.log(this.day);
            if(this.day>=0){
                this.day = 0; 
                (<HTMLInputElement>document.getElementById("buttonRight")).setAttribute('style', "border: 2px solid lightgrey; color: lightgrey");
            }
            else{
                (<HTMLInputElement>document.getElementById("buttonRight")).setAttribute('style', "border: 2px solid #97BC8C; color: #97BC8C");

            }

            this.formattedDate = (moment(this.today).add(this.day,'d')).format('YYYY-MM-DD');
            this.selectedDate = (moment(this.today).add(this.day, 'd')).format('DD MMMM');

            switch(this.day){
                case 0: (<HTMLInputElement>document.getElementById("timerDate")).textContent = "Today"; break;
                case -1: (<HTMLInputElement>document.getElementById("timerDate")).textContent ="Yesterday"; break;
                default: (<HTMLInputElement>document.getElementById("timerDate")).textContent = this.selectedDate; break;
            }
        }

        if(this.timeMode == "weekly"){
            this.week = this.week + arrow; 

            if (this.week >= 0){
                this.week = 0; 
                this.weeklyDate = (moment(this.today).add(this.week, 'w')).format('YYYY-MM-DD'); 
                (<HTMLInputElement>document.getElementById("buttonRight")).setAttribute('style', "border: 2px solid lightgrey; color: lightgrey");
            }
            else{
                (<HTMLInputElement>document.getElementById("buttonRight")).setAttribute('style', "border: 2px solid #97BC8C; color: #97BC8C");

            } 

            if(this.week<0){
                //console.log("THIS WEEK < 0");
                this.suma = 7*this.week + 6 - moment(this.today).day(); 
                console.log("SUMA");
                console.log(this.suma);
                this.weeklyDate = (moment(this.today).add((this.suma),'d')).format('YYYY-MM-DD');
            } 

            this.selectedWeek = (moment(this.today).add(this.suma - 5, 'd')).format('DD MMMM');
            this.selectedWeek = "Week of " + this.selectedWeek;

            switch(this.week){
                case 0: (<HTMLInputElement>document.getElementById("timerDate")).textContent = "This week"; break;
                case -1: (<HTMLInputElement>document.getElementById("timerDate")).textContent ="Last week"; break;
                default: (<HTMLInputElement>document.getElementById("timerDate")).textContent = this.selectedWeek; break;
            }
        }

    }


    @Watch('timeMode')
    changeValueTimer(){

        console.log("CHANGE TIMEMODE");
        console.log(this.timeMode);

        if(this.timeMode=='list'){
            window.location.reload();
        }
        if(this.timeMode=='daily'){
            console.log("DAILY");
            (<HTMLInputElement>document.getElementById("timerDate")).textContent = "Today";

        }

        if(this.timeMode=='weekly'){
            console.log("WEEKLY");
            (<HTMLInputElement>document.getElementById("timerDate")).textContent = "This week";

        }
    }
    get user() {
        return new User(this.raw_user);
    }


    launchHelp(){
        this.$tours['timerTour'].start();
    }

    async mounted() {
        await this.activity.reload();
        //this.formatDailyRecords(this.daily_records);
        //this.$tours['timerTour'].start();
    }


}

</script>

