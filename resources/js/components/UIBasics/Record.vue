<template>
    <div class="bmk__records" v-if="this.records.length!=0">
        <div class="bmk__record__modify__mode">        
            <div class="button__modify" title="Records can only be reduced, other changes will not be processed">
                Modify items<edit-icon @click="modify()" size="2x" class="timer__button__icon edit__icon"></edit-icon>
            </div>
        </div>
        <div class="bmk__record" v-for="(record,i) in records_used" :key="i">
            <p class="record__date">{{record.date}}</p>
            <table class="record__unit" v-for="(hour,i) in record.hours.reverse()" :key="i">
                <tr class="record__line">
                    <th class="type__symbol" :class="[hour.working?'work':'break']">
                        <clock-icon v-if="hour.working" size="2x" class="record__icon"></clock-icon>
                        <coffee-icon v-else size="2x" :class="hour.kind"></coffee-icon>
                    </th>

                    <th class="record__content" :class="[hour.working?'wrk':'brk', hour.kind=='working'?'':hour.kind]">BEGINNING</th>

                    <th v-if="!toModify" :id="'hour' + hour.start_id" class="record__content" :class="[hour.working?'wrk':'brk', hour.kind=='working'?'':hour.kind]">{{hour.beginning}}</th>
                    <th v-else :id="'modify' + hour.start_id" class="modify__record">
                        <vue-timepicker lazy @change="changeStart(hour)" class="timepicker__record" v-model="hour.beginning" format="HH:mm:ss"></vue-timepicker>
                    </th>

                    <th class="record__content" :class="[hour.working?'wrk':'brk', hour.kind=='working'?'':hour.kind]">END</th>

                    <th v-if="!toModify" :id="'hour' + hour.end_id" class="record__content" :class="[hour.working?'wrk':'brk', hour.kind=='working'?'':hour.kind]">{{hour.end}}</th>
                    <th v-else :id="'modify' + hour.end_id" class="modify__record">
                        <vue-timepicker lazy @change="changeEnd(hour)" class="timepicker__record" v-model="hour.end" format="HH:mm:ss"></vue-timepicker>
                    </th>

                    <th class="record__content total__hours">{{hour.total}}</th>
                    <th title="Delete record">
                        <x-icon @click="del(hour)" size="2x" class="timer__button__icon delete__icon"></x-icon>
                    </th>
                </tr> 
          </table>
        </div> 
    </div>
    <div v-else class="norecords__message">
        No records to show
    </div>

</template>

<script lang="ts">

//import Record from '../models/Record';
import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
import { CoffeeIcon } from 'vue-feather-icons'
import { ClockIcon } from 'vue-feather-icons'
import {XIcon} from 'vue-feather-icons'
import { EditIcon } from 'vue-feather-icons'

// Main JS (in UMD format)
import VueTimepicker from 'vue2-timepicker'

// CSS
import 'vue2-timepicker/dist/VueTimepicker.css'

// Models 
import {ActivityList} from '../../models/ActivityList';

import axios from "axios";

import * as moment from 'moment';


@Component({
    filters: {
        formatZone(hour: any){
            var fecha = "1970-01-01 " + hour; 
            var date = moment(fecha);
            return date.add(2, 'hours').format("HH:mm:ss");
        }
    },
    
    components: {
        CoffeeIcon, ClockIcon, XIcon, EditIcon,
        VueTimepicker
       // RecordController
    }
})
export default class Record extends Vue {
    @Prop()
    records!: any[];

    @Prop()
    records_used!: any[];

    @Prop()
    activity!: ActivityList;

    toModify = false; 


    @Watch('records')
    changeRecordsList(){
        console.log("ADD NEW RECORD TO VIEW");
        //console.log(this.records);
    }

    @Watch('records_used')
    changeRecordsUsedList(){
        console.log("ADD NEW RECORD TO VIEW");
        //console.log(this.records);
    }

    modify(){
        this.toModify = !this.toModify;
        console.log(this.toModify);
        if(!this.toModify){
            console.log(this.toModify);
            window.location.href = "/timer";
        }

    }

    async changeStart(hour:any){
        console.log("CHANGE START HOUR");
        console.log(hour);
        if(hour.beginning<hour.end){
            const response = await axios.post('/postModifyStartRecord', {registro: hour});
            console.log(response);
        }

        //window.location.href = "/timer";

    }

    async changeEnd(hour:any){
        console.log("CHANGE END HOUR");
        console.log(hour);
        if(hour.end>hour.beginning){
            console.log("CALL postModifyEndRecord");
            const response = await axios.post('/postModifyEndRecord', {registro: hour});
            console.log(response);
        }

        //window.location.href = "/timer";
    }

    async del(hour: any){
        console.log("DELETE");
        console.log(hour);
        let info = {registro: hour}
        const response = await axios.post('/postDeleteRecord', info);
        if(response.data.msg!="Record deleted"){
            alert(response.data.msg);
        }
        window.location.href = '/timer'; 
    }
    

    mounted(){
        console.log("MOUNTED");
        //this.formatHoursInit();
        //this.records = this.records.reverse();
        //console.log(this.records);
    }


}
</script>
