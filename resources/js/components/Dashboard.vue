<template>
    <div id="bmk__dashboard">
        <div class="bmk__view dashboard__view">
            <leftbar :user="raw_user"></leftbar>
            <uppermenu class="timerbar__menu" :activity="activity" :hours="hours" :laststate ="laststate"></uppermenu>          
            <div class="dashboard__content bmk__view__content">
                <div class="donut-charts today__chart">
                    <p>TIME WORKING TODAY</p>
                    <DoughnutChart
                        :percent="todaypercent"
                        :foregroundColor="tdcolor"
                        :backgroundColor="bgcolor"
                        :strokeWidth="12"
                        :radius="90"
                        :width="300"
                        :height="300"
                        :visibleValue="true"
                        :customText="''"
                        :customTextColor="'#665D69'"
                        :customTextSize="12"
                    />
                </div>
                <div class="donut-charts week__chart">
                    <p>TIME WORKING THIS WEEK</p>
                    <DoughnutChart
                        :percent="weekpercent"
                        :foregroundColor="wkcolor"
                        :backgroundColor="bgcolor"
                        :strokeWidth="12"
                        :radius="80"
                        :width="300"
                        :height="300"
                        :visibleValue="true"
                        :customText="''"
                        :customTextColor="'#665D69'"
                        :customTextSize="12"
                    />
                </div>

                <div class="event__table">
                    <eventtable :events="events"></eventtable>
                </div>
                <div class="help__link" @click="launchHelp">
                    <p>Help</p>
                    <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                </div>
            </div>
             <v-tour name="dashboardTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue, Watch} from 'vue-property-decorator';

// Models
import {ActivityList} from '../models/ActivityList';
import User from '../models/User';


//Components
import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';
import EventTable from './UIBasics/EventTable.vue';

// Icons
import {HelpCircleIcon } from 'vue-feather-icons'



// @ts-ignore
import DoughnutChart from 'vue-doughnut-chart/src/DoughnutChart.vue';



@Component({
    components: {
        HelpCircleIcon,
        
        Leftbar, UpperMenu, 
        
        DoughnutChart,

        EventTable
    }
})
export default class Dashboard extends Vue {


    @Prop(Object)
    raw_user!: {};

    @Prop()
    hours!: any[];

    @Prop()
    percentages!: any[];

    @Prop()
    laststate!: string;

    @Prop()
    events!: any[];


    // Steps for help guide
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
            target: '#timer',
            content: 'Here you can access your records.'
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
        },
        {
            target: '.today__chart',  // We're using document.querySelector() under the hood
            content: `Here you can find the percentage of hours you've worked today`
        },
        {
            target: '.week__chart',
            content: `Here you can find the percentage of hours you've worked this week`
        },
        {
            target: '.event__table',
            content: 'And here you can see a summary of next events of your calendar.',
            params: {
              placement: 'top' // Any valid Popper.js placement. See https://popper.js.org/popper-documentation.html#Popper.placements
            }
        }
       

    ]
  
    get user() {
        return new User(this.raw_user);
    }


    // object activityList for timer view
    activity: ActivityList = new ActivityList();



    /* Donut Chart */

    bgcolor: string = "#DADADA"
    tdcolor: string = "#71897b"
    wkcolor: string = "#DE9C87"

    todaypercent: number = (this.percentages[0]['today'] > 100.0) ? 100 : this.percentages[0]['today'].toFixed(2);
    weekpercent: number = (this.percentages[0]['week'] > 100.0) ? 100 : this.percentages[0]['week'].toFixed(2);


    @Watch('activity.records')
    reload(){
        window.location.href = "/dashboard";
    }

    launchHelp(){
        this.$tours['dashboardTour'].start();
    }

    async mounted() {
        console.log("dashboard mounted executed");
        await this.activity.reload();

    }

}

</script>

