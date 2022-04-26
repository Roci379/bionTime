<template>
    <div class="bmk__gant__week__container">
        <div v-for="(day, i) in this.getWeekDays()" :key="i">
            <gantday :daily="false" :dailyRecords="dailyRecords" :formattedDate="day"></gantday>
        </div>
    </div>
</template>

<script lang="ts">

import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
import GantDayHead from './GantDayHead.vue';

//import { Record2 } from "/home/roci379/fichaje/fichaje-boilerplate/bmk-boilerplate/resources/js/models/models";

import Record from '../../models/Record';

import * as moment from 'moment';

import { LoaderIcon } from 'vue-feather-icons'


@Component({
    components: {
        GantDayHead,

        LoaderIcon
    }
})
export default class GantWeek extends Vue {

    @Prop()
    dailyRecords!: any[];

    @Prop()
    date!: string;

    @Prop()
    today: Date = new Date();

    @Prop()
    formattedDate = (moment(this.today)).format('YYYY-MM-DD');

    defaultHourIni: number = 9;
    defaultHourFin: number = 22;

    @Watch('today')
    getWeekDays() {
        let days = [];
        let maxday = this.getDayOfWeek();
        console.log("DAY OF WEEK"); 
        console.log(maxday);
        let i = maxday;
        let day = this.today;
        let daybefore = moment(day);
        while(i>1){
            days.push(daybefore.format('YYYY-MM-DD'));
            daybefore = moment(day).subtract(1,'days');
            day = new Date(daybefore.format('YYYY-MM-DD'));
            i = i - 1;
        }

        days.push(daybefore.format('YYYY-MM-DD'));
        return days.reverse();
    }


    @Watch('today')
    getDayOfWeek(){
        let weekday = moment(this.today).day();
        return weekday;
    }

    @Watch('date')
    changeTodayValue(){
        console.log("DATE CHANGED");
        this.today = new Date(this.date);
        this.formattedDate = (moment(this.today)).format('YYYY-MM-DD');
        this.getWeekDays();
    }

    mounted(){
        
    }

}
</script>
