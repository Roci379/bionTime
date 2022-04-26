<template>
    <div class="gant__day__head">
        <span>{{gantdate | numberDay}}</span>
        <div>
            <p>{{gantdate | weekDay}}</p>
            <data v-if="duration">{{duration | normalizeDuration}}</data>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';
import * as moment from 'moment';



@Component({
    components: {
    },
    filters: { 
        numberDay(date: Date) { return moment(date).format('D') }, 
        weekDay(date: Date) { return moment(date).format('dddd') }, 
    }
})
export default class GantDayHead extends Vue {
    @Prop()
    gantdate!: Date

    @Prop()
    duration?: number

    mounted(){
        //TODO;
    }

}
</script>
