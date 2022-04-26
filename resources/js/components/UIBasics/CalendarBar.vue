<template>
    <div class="calendarBar">
        <div class="calendarBar__content">
            <div class="calendarBar__legend">
                <div class="calendarBar__legend__element" v-for="(element,i) in lengend" :key="i">
                    <div class="color" v-bind:style="{ background: element.color}"></div>
                    <p class="value">{{element.value}}.</p>
                </div>
            </div> 
            <div class="calendarBar__title">
                <div class="calendarBar__title__content"> 
                    <calendar-icon size="1.5x" class="menuIcon"></calendar-icon>
                    <p class="calendarBar__text">{{title}}</p>
                </div>
            </div>

            <div class="calendarBar__events">
                <p class="calendarBar__events__title">next events:</p>
                <p class="calendarBar__events__event" v-for="(event,i) in events.reverse().slice(0,3)" :key="i">
                 {{event.event_name}}:  From {{event.staring_date | toDate}} To {{event.ending_date | toDate}}
                </p>
            </div>

        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';
import { CalendarIcon } from 'vue-feather-icons';
import { ChevronLeftIcon } from 'vue-feather-icons';
import { ChevronRightIcon } from 'vue-feather-icons';

import * as moment from 'moment';


@Component({
    filters: {
        toDate(element: any){
            return moment(element).format('DD MMMM YYYY');
        }
    },

    components: {
        CalendarIcon,

        ChevronLeftIcon, ChevronRightIcon
    }
})
export default class CalendarBar extends Vue {
    @Prop()
    title!: string;

    @Prop()
    events!: any[];


     lengend = [
            {color: "#97BC8C", value: "Medical leave"}, 
            {color: "#F5CDB7", value: "Free day"},
            {color: "#ECDFBF", value: "Other"}
    ]


    mounted(){
        //TODO;
    }


}
</script>
