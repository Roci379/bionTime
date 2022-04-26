<template>
    <div class="bmk__timer">
        <div>
            <div @click="stop" data-title="Stop registering" class="timer__circle timer__stop">
                <square-icon size="1.5x" class="timer__button__icon"></square-icon>
            </div>
            <div @click="playPause" class="timer__circle timer__playpause">
                <div data-title="Start working / Take a break">
                <play-icon v-if="play"  size="4x" class="timer__button__icon" style="margin-left:6px"></play-icon>
                <pause-icon v-else size="4x" class="timer__button__icon"></pause-icon>
                </div>
            </div>
            <div @click="reset" data-title="Logout" class="timer__circle timer__delete">
                <log-out-icon size="1.5x" class="timer__button__icon"></log-out-icon>
            </div>
        </div>
        <p> {{this.error}} </p>
    </div>
</template>

<script lang="ts">

import {Component, Vue, Prop, Watch} from 'vue-property-decorator';

import axios from "axios";

// Icons
import { PlayIcon } from 'vue-feather-icons'
import { PauseIcon } from 'vue-feather-icons'
import { SquareIcon } from 'vue-feather-icons'
import { XIcon } from 'vue-feather-icons'
import { LogOutIcon } from 'vue-feather-icons'


// Models
// import {WorkActivity} from '../../javidemo/models';
import {ActivityList} from '../../models/ActivityList';


@Component({
    filters: {
        formatDate(d: Date|null){
            if(!d) return '';
            return d.toLocaleString();
        }
    }, 
    components: {
        PlayIcon, SquareIcon, XIcon, PauseIcon, LogOutIcon
    }
})

export default class TimerButton extends Vue {
    
    //@Prop({default:false})
    //playing! :boolean; 

    // object ActivityList
    @Prop()
    activity!: ActivityList;

    // state of last record
    @Prop()
    laststate!: string;

    // records of timer list view
    @Prop()
    records!: any[];


    error:string = "";

    // work = new WorkActivity();
    // activity: ActivityList = new ActivityList();

    // Variable that is true if triangle is seen
    // and false if pause icon is seen
    play = this.laststate == 'stop' || this.laststate == 'pause'; 

    //
    start = true; 

    // For avoid mutating warning
    lastRecordState = this.laststate; 


    mounted(){
        //TODO;
    }

    /* Function that executes when clicking stop button */
    async stop() {

        // create 'stop' record only if pause icon seen
        // This means that user is recording time working
        // So it can be stopped or paused
        if(!this.play){

            // Function to post info to data element of ActivityList
            await this.activity.stop(); 
            this.error = "";
        
            //const inputData = {recordState: 'stop'}
            //const response = await axios.post('/postRecord', inputData);
            //const data = response.data;

            //console.log("STOP");
            //console.log(data);

            // changed state of last record to stop
            this.lastRecordState = 'stop'; 
            // Triangle set
            this.play = true; 
            console.log("STOPPED");
            console.log("PLAY VALUE: ");
            console.log(this.play);
            // this.$emit('onplaychange', this.activity, true );
        }else{
            this.error = "Can't stop a pause record.";
            console.log("TRIED TO STOP");
            console.log("PLAY VALUE: ");
            console.log(this.play);
        }

    }

    /* Function that executes when clicking play-pause button */
    /* Need to track if it is play or pause */
    async playPause() {

        //this.work.start();

        // PLAY 
        if(this.play){ // triangle icon seen, set pause
            this.play = false;
            // Function to post info to data element

            // create 'start' record or 'unpause' record
            if(this.lastRecordState == 'stop'){
                await this.activity.start(); 
                this.error = "";
                //const inputData = {recordState: 'start'}
                //const response = await axios.post('/postRecord', inputData);
                //const data = response.data;

                //console.log("START");
                //console.log(data);
                console.log("PLAY VALUE: ");
                console.log(this.play);
                this.lastRecordState = 'start';
            }
            if(this.lastRecordState == 'pause'){
                await this.activity.unpause(); 
                this.error = ""; 
                //const inputData = {recordState: 'unpause'}
                //const response = await axios.post('/postRecord', inputData);
                //const data = response.data;

                //console.log("UNPAUSE");
                //console.log(data);

                console.log("PLAY VALUE: ");
                console.log(this.play);
                this.lastRecordState = 'unpause';
            }
           
        // PAUSE 
        }else{
            this.play = true; // pause icon seen, set triangle icon
            // create 'pause' record
            await this.activity.pause(); 
            this.error = "";
            //const inputData = {recordState: 'pause'}
            //const response = await axios.post('/postRecord', inputData);
            //const data = response.data;

            //console.log("PAUSE");
            //console.log(data);
            console.log("PLAY VALUE: ");
            console.log(this.play);
            this.lastRecordState = 'pause';

        }

        // this.$emit('onplaychange', this.activity, false)
    }

    async reset() {
        this.error =""; 
        console.log("Cerrar sesión");
        await axios.post("/logout");
        location.href = '/';
    }
    
}
</script>
