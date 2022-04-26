<template>
    <div class="upperMenu" :class="[expanded?'exp':'comp']">
        <div class="upperMenu__content">
            <div class="upperMenu__hours">
                <div class="hours__timerType">
                    <select @change="changeContent" name="hoursSelector" class="hours__selector" id="hoursSelector">
                        <option class="timerItem" v-for="(option,i) in dpitems.items" :key="i">
                            {{option}}
                        </option>
                    </select>
                </div>

                <div class="hours__status">
                    <div v-if="selected=='TODAY'" class="hour__status__content" v-for="(hour, key) in hours[0].today[0]" :key="key">
                        <p class="hourTypes">{{key}}: </p>
                        <p class="hourTypes">{{hour}} </p>
                    </div>   
                    <div v-if="selected=='THIS WEEK'" class="hour__status__content" v-for="(hour, key) in hours[0].week[0]" :key="key">
                        <p class="hourTypes">{{key}}: </p>
                        <p class="hourTypes">{{hour}} </p>
                    </div>  
                    <div v-if="selected=='THIS MONTH'" class="hour__status__content" v-for="(hour, key) in hours[0].month[0]" :key="key">
                        <p class="hourTypes">{{key}}: </p>
                        <p class="hourTypes">{{hour}} </p>
                    </div>  
                </div>
            </div>

            <timetracker :activity="activity" :running="!play"></timetracker>

            <div class="upperMenu__button">
                <timerbutton :activity="activity" :laststate="laststate" :records="records"> <!--@onplaychange="changePlay"-->
                </timerbutton>
            </div>
        </div>
        <div @click="expand" class="upperMenu__arrow">
            <arrow :expanded = "expanded"></arrow>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop, Watch} from 'vue-property-decorator';

// Components
import Timer from './TimerButton.vue';
// import TimerComp from './TimerButtonCompacted.vue';
import Arrow from './Arrow.vue';

import {ActivityList} from '../../models/ActivityList';


@Component({
    filters: {
        correctHour(hour:any){
            return hour>=10 ? hour : "0" + hour;
        }
    },

    components: {
        Timer, Arrow,

    }
})
export default class UpperMenu extends Vue {
    //@Prop()
    //play!: boolean; 

    // hours worked (day, week or month)
    @Prop()
    hours!: any[];

    // state of last record
    @Prop()
    laststate!: string;

    // records of timer list view
    @Prop()
    records!: any[];

    // activityList object
    @Prop()
    activity!: any; 

    // state of upperMenu
    expanded = true; 

    // selected value of drop down list
    selected: string = "TODAY"; 

    // state: string = "init";

    timerValue: string = "00:00:00";

    // True if triangle icon set in timer button
    play = this.laststate == 'stop' || this.laststate == 'pause';
 


    /* Drop Down List Options */
    dpitems = {
        items: ['TODAY', 'THIS WEEK', 'THIS MONTH']
    }

    /*
    async changePlay(value: ActivityList, stop: boolean) {
        await value.reload();
        if(value){
            console.log("TIMER VALUE");
            console.log(value);
            this.timerValue = value.time;
            console.log(this.timerValue);
            //this.$emit('changeplayview', value);
        } 
    }*/


    @Watch('activity.records')
    changeRecordsValue(){
        //console.log("ACTIVITY RECORDS HAS CHANGED IN TIMER BUTTON");
        //this.records = this.activity.records;
    }

    // get value of drop down list selected
    changeContent(){
        this.selected = (<HTMLInputElement>document.getElementById("hoursSelector")).value;
        //console.log(this.selected);
    }


    mounted(){
        //TODO;
    }

    // change value of variable expand if click in arrow
    expand(){
        //console.log("Click en arrow");
        if(this.expanded){
            this.expanded = false;
        }else{
            this.expanded = true; 
        }
    }

}
</script>
