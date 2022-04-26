<template>
    <div class="upperMenu__tracker" id = "divTracker">
        <div class="tracker__elements">  
            <p class="timer__elements">HH</p>
            <p class="timer__elements">MM</p>
            <p class="timer__elements">SS</p>
        </div>
        <div class="tracker__values">
            <p class="timer__values">{{hours | pad00}}</p>
            <p class="timer__values">:</p>
            <p class="timer__values" >{{minutes | pad00}}</p>
            <p class="timer__values">:</p>
            <p class="timer__values" >{{seconds| pad00}}</p>
        </div> 
    </div>
</template>

<script lang="ts">

import {Component, Vue, Prop, Watch} from 'vue-property-decorator';

import * as moment from 'moment';

// Models
import {ActivityList} from '../../models/ActivityList';



@Component({
    filters: {
        pad00(n: number){
            return (n+'').padStart(2, '0');
        }
    }
})
export default class TimeTracker extends Vue {

    //@Prop()
    //running!: boolean; 

    @Prop()
    activity!: ActivityList;

    timerValue: string = this.activity.time;

    running: boolean = this.activity.status != 'stop';

    startMoment!: moment.Moment;

    id: any;

    hours = 0;
    minutes = 0;
    seconds = 0;
    diff = 0; 

    /* When mounted init counting seconds and change running value */
    mounted(){

        //console.log("TimerTraker mounted");
        this.initStartMoment();
        this.changeRunning();

    }

    /* Function to initialize tracker value */
    initStartMoment(){

        const [h, m, s] = this.timerValue.split(':');
        
        console.log("CONST");
        console.log(h);
        console.log(m);
        console.log(s);
        console.log(this.timerValue.split(':'));

        this.startMoment = moment();

        this.startMoment.add(-h, 'hours');
        this.startMoment.add(-m, 'minutes');
        this.startMoment.add(-s, 'seconds');

        //console.log("START MOMENT ");
        //console.log(this.startMoment);
    }


    /* Function to calculate difference between the moment
    when tracker has been initialized and actual moment */
    updateHMS(){

        (this.running)? this.diff = moment().diff(this.startMoment) : this.diff = 0; 
        //console.log("DIFF");
        //console.log(this.diff);
        //console.log(moment().diff(this.startMoment));
        const elapsedTime = moment.duration(this.diff);

        this.hours = elapsedTime.hours()
        this.minutes = elapsedTime.minutes()
        this.seconds = elapsedTime.seconds()

    }

    /* Update tracker every second */
    runTracker() {
        //console.log("Init runTracker");
        this.updateHMS();
        this.id = setInterval(() => {
            this.updateHMS();
        },1000);
    }

    /* Stop tracker if running is false */
    stopTracker() {
        this.timerValue = this.activity.time;
        this.initStartMoment();
        console.log("TIMER VALUE");
        console.log(this.timerValue);
        //this.initStartMoment();
        this.updateHMS();
        //console.log("STOP TRACKER"); 
        //console.log(this.timerValue);
        clearInterval(this.id);
    }


    
    @Watch('activity.time')
    changeTimerValue(){
        //console.log("ACTIVITY TIME HAS CHANGED IN TRACKER");
        this.timerValue = this.activity.time;
        this.initStartMoment();
    }

     @Watch('activity.status')
    changeRunning(){
        //console.log("ACTIVITY STATUS HAS CHANGED IN TRACKER");
        this.running = this.activity.status != 'stop';
        //this.initStartMoment();
        //console.log(this.running);
        this.changeTimerValue();
        this.changeDivColor();
        (this.running)? this.runTracker() : this.stopTracker();

    }


  /*
    @Watch('running')
    changeRunning() {
        (this.running)? this.runTracker() : this.stopTracker();
    }*/
    
    /*@Watch('timerValue')
    changeTimeValue() {
        console.log("Timer value ha cambiado - actualizando start moment");
        console.log(this.timerValue);
        // this.initStartMoment();
    }*/

/*


    timerSetup =  {
        partialTime: (this.running == true) ? this.timerValue : 0.00,
        lastEvent: moment().subtract(0, 'h')
    }

    elapsedTime = moment.duration(moment().diff(this.timerSetup.lastEvent))

    id: any;

    hours() {
        return moment.duration(this.timerSetup.partialTime, 'hours').hours() + this.elapsedTime.hours()
    }

    minutes() {
        return moment.duration(this.timerSetup.partialTime, 'hours').minutes() + this.elapsedTime.minutes()
    }

    seconds() {
        return moment.duration(this.timerSetup.partialTime, 'hours').seconds() + this.elapsedTime.seconds()
    }

    runTracker() {
        this.id = setInterval(() => {
            this.elapsedTime = moment.duration(moment().diff(this.timerSetup.lastEvent))
        },1000);
    }

    stopTracker() {
        console.log("stoppp");
        clearInterval(this.id);
    }

    @Watch('running')
    changeRunning() {
        (this.running)? this.runTracker() : this.stopTracker();
    }


    mounted(){
        //TODO;
        console.log("moment")
        console.log(moment().format('DD-MM-YYYY'))

        console.log("Total");
        console.log(moment.duration(this.timerSetup.partialTime, 'hours'));

        if(this.running) {
            this.runTracker()
        }
    }

*/

    changeDivColor(){
        console.log("CHANGE DIV COLOR");

        if(this.running){
            if(this.activity.status == 'start' || this.activity.status == 'unpause'){
                (<HTMLInputElement>document.getElementById("divTracker")).setAttribute('style', "border: 2px solid #97BC8C");
            }else{
                (<HTMLInputElement>document.getElementById("divTracker")).setAttribute('style', "border: 2px solid #DE9C87");
            }
            
        }else{
            (<HTMLInputElement>document.getElementById("divTracker")).setAttribute('style', "border: 2px solid #F5CDB7");
        }
    }
}
</script>
