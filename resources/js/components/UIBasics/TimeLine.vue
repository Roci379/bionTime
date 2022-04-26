<template>
    <div class="timeline__container" :style="{'grid-template-columns': getMyWidths()}">

        <div v-for="(stage, i) in timeStages" :key="i" class="timeline__stage" :class="'timeline__' + stage.id">
            <p class="timeline__title" :class="'title__' + stage.id">{{stage.name}}</p>
            <div class="timeline__line"></div>
        </div>
    </div>  

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';




@Component({
    components: {
    }
})
export default class TimeLine extends Vue {

    @Prop()
    timeStages!: any[];

    lines = ['worked', 'break', 'not worked']

    

    getMyWidths() {
        //const totalHours = this.timeStages.reduce((acc, stage) => acc + stage.duration, 0)

        /*const percentages = this.timeStages.map((stage) => {
            return (stage.duration / totalHours) * 100 
        })
        */

        let percString = "";
        /*
        percentages.forEach((perc) => {
            percString = percString + perc + "fr "
        })
        */


        this.timeStages.forEach((perc) => {
            percString = percString + perc.duration + "fr "
        })

        //console.log(percString)
        return percString;

    }
    mounted(){
        //TODO;
    }

}
</script>
