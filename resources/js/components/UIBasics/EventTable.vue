<template>
    <div class="eventTable" v-if="this.events.length!=0">
         <table class="std__table">
            <tr>
                <th class="std__table__title">EVENT</th>
                <th class="std__table__title">START</th>
                <th class="std__table__title">END</th>
            </tr>
             <tr v-for="(event,i) in events" :key="i">
                <td class="std__table__content">{{event.event_name}}</td>
                <td class="std__table__content">{{event.staring_date | toDate}}</td>
                <td class="std__table__content">{{event.ending_date | toDate}}</td>
            </tr>
        </table>
    </div>
    <div v-else class="noevents__message">
        No events to show
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';

import * as moment from 'moment';

@Component({
     filters: {
        toDate(element: any){
            return moment(element).format('YYYY-MM-DD');
        }
    },

    components: {
        
    }
})
export default class EventTable extends Vue {
    @Prop()
    events!: any [];

    mounted(){
        //TODO;
    }

}
</script>
