<template>
    <div class="bmk__timer__comp">
        <div @click="stop" class="timer__circle timer__stop">
            <square-icon size="1.5x" class="timer__button__icon"></square-icon>
        </div>
         <div @click="playPause" class="timer__circle timer__playpause__comp">
            <play-icon v-if="myPlay" size="4x" class="timer__button__icon" style="margin-left:6px"></play-icon>
            <pause-icon v-else size="4x" class="timer__button__icon"></pause-icon>
        </div>
         <div @click="reset" class="timer__circle timer__delete">
            <x-icon size="1.5x" class="timer__button__icon"></x-icon>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';

import { PlayIcon } from 'vue-feather-icons'
import { PauseIcon } from 'vue-feather-icons'
import { SquareIcon } from 'vue-feather-icons'
import { XIcon } from 'vue-feather-icons'

@Component({
    components: {
        PlayIcon, SquareIcon, XIcon, PauseIcon
    }
})
export default class TimerButtonCompacted extends Vue {
    
    @Prop()

    play! :boolean; 

    myPlay = this.play; 

    mounted(){
        //TODO;
    }

    stop() {
        console.log("Stop!")
        this.myPlay = true; 
    }
    playPause() {
        console.log("Play!")
        if(this.myPlay){
            this.myPlay = false;
        }else{
            this.myPlay = true;
        }
    }
    reset() {
        console.log("Delete!")
        this.myPlay = true; 

    }
    
}
</script>
