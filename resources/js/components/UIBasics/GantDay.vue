<template>
    <div class="bmk__gant__container">
        <div class="bmk__gant bmk__gant--simple" :class="{'bmk__gant--no_records': this.getRecords().length == 0}">
            <header>
                <span></span>
                <gantdayhead :gantdate="new Date(formattedDate)" :duration="wholeDuration()"></gantdayhead>
            </header>

            <main v-if="this.getRecords().length>0" :style="'grid-template-rows: repeat('+hoursContent().length+', auto)'">
                <div v-for="(hour, i) in hoursContent()" class="gant__record" :key="i">
                    <div v-if="daily" class="gant__record__yaxis">
                        <p>{{hour.hour | formatZone }}</p>
                    </div>
                    <div class="gant__record__content" :class="(hour.eventsCurrentHour.length == 0)? 'record__content--' + hour.hourInitialState : ''" :style="getMyHeights(hour.eventsCurrentHour)">
                        <div class="gant__subrecord" :class="'gant__subrecord--' + hour.hourInitialState">
                        </div>
                        <div v-for="(subrecord, j) in hour.eventsCurrentHour" :key="j" class="gant__subrecord" :class="'gant__subrecord--' + subrecord.record.type">
                            <data v-if="subrecord.record.type != 'stop'">
                                {{subrecord.record.duration | normalizeDuration(true)}} 
                                <span>
                                    ({{ subrecord.record.timestamp | normalizeTimestamp(true) | formatZone}} {{getEndTime(subrecord.record.timestamp)}})
                                </span>
                            </data>
                        </div>
                    </div>
                </div>
            </main>

            <div v-if="this.getRecords().length==0" class="bmk__empty__record">
                <meh-icon size="5x"></meh-icon>
                <p class="bmk__empty__record__text">There are no records for this date.</p>
            </div>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';
import GantDayHead from './GantDayHead.vue';

import Record from '../../models/Record';
//import { Record2 } from "/home/roci379/fichaje/fichaje-boilerplate/bmk-boilerplate/resources/js/models/models";

import * as moment from 'moment';

import { LoaderIcon } from 'vue-feather-icons'
import { MehIcon } from 'vue-feather-icons'


@Component({
    filters: {
        formatZone(hour: any){
            var fecha = "1970-01-01 " + hour; 
            var date = moment(fecha);
            return date.add(2, 'hours').format("HH:mm");
        }
    },
    components: {
        GantDayHead,

        LoaderIcon, MehIcon
    }
})
export default class GantDay extends Vue {

    @Prop()
    dailyRecords!: any[];

    @Prop()
    formattedDate!: string;

    @Prop()
    daily!: boolean;

    /*@Prop()
    today: Date = new Date();

    @Prop()
    formattedDate = (moment(this.today)).format('YYYY-MM-DD');
    */

    defaultHourIni: number = 9;
    defaultHourFin: number = 22;

    getRecords(): Record[] {
        let recs: Array<Record>;
        recs = [];

        //console.log("FORMATED DATE");
        //console.log(this.formattedDate);
        for (let element of this.dailyRecords) {
            if(element.hasOwnProperty(this.formattedDate)){
                recs = element[this.formattedDate];
            }
        }

        return recs; 
    }


    recordsDuration(): Record[] {
        
        let recs = this.getRecords();

        let tempRecords = recs.map((rec, i) => {
            let duration = 0;
            if(i < recs.length - 1) {
                let curr = moment(rec.timestamp);
                let next = moment(recs[i + 1].timestamp)
                duration = next.diff(curr, 'hours', true);
            }

            rec.duration = duration

            return rec
        })

        //console.log("TEMP RECORDS");
        //console.log(tempRecords);

        return tempRecords;
    }


    wholeDuration():number {

        let totalDuration = this.recordsDuration().reduce((a, b) => {
            let dur = 0;
            let includeTypes = ['start', 'unpause'] 
            if(b.duration && includeTypes.includes(b.type)) dur = b.duration;
            return a + dur
        }, 0)
        return totalDuration;
    }

    hoursAxis(): string[] {
        let hours = []
        let hourIni = this.defaultHourIni;
        let hourFin = this.defaultHourFin;
        
        let recs = this.getRecords();

        if(recs.length > 1) {
            hourIni = parseInt(moment(recs[0].timestamp).format('H'));
            hourFin = parseInt(moment(recs[recs.length - 1].timestamp).format('H'));
        } 
        for(let i = hourIni; i < hourFin + 2; i++) {
            let tempHour;
            (i < 9)? tempHour = 0 + i : tempHour = i;
            tempHour = tempHour + ':00';
            hours.push(tempHour);
        }
        return hours
    }

    hoursContent() {
        let hourInitialState = '';
        let temp = '';
        let hoursContent = this.hoursAxis().map((hour, i) => {
            let eventsCurrentHour = this.recordsDuration().filter((rec) => parseInt(moment(rec.timestamp).format('H')) == parseInt(hour.substring(0, hour.indexOf(":")))).map((record, j) => {
                let position = (parseInt(moment(record.timestamp).format('m')) / 60) * 100;
                return {
                    record,
                    position
                }
            })
            if(eventsCurrentHour.length > 0) hourInitialState = eventsCurrentHour[eventsCurrentHour.length - 1].record.type

            return {
                hour,
                hourInitialState,
                eventsCurrentHour
            }
        });

        if(parseInt(moment(this.recordsDuration()[0].timestamp).format('m')) != 0) {
            hoursContent[0].hourInitialState = ''
        }

        return hoursContent
    }

    getEndTime(recDate:Date) {

        let recs = this.getRecords();

        let end = '';
        let ixRec = recs.findIndex((rec) => recDate == rec.timestamp);
        if(ixRec < recs.length - 1) {
            end = ' - ' + moment(recs[ixRec + 1].timestamp).add(2,'hours').format('HH:mm')
        } 
        return end
    }

    getMyHeights(ev:any) {
        console.log(ev);
        let rows = 'grid-template-rows: ';
        if (ev.length == 0) rows = '1fr';
        else {
            /*
            let prev = 0;
            ev.forEach((ev:any) => {
                rows = rows + (Math.floor(ev.position) - prev) + 'fr ';
                console.log("ROWS");
                console.log(rows);
                prev = Math.floor(ev.position);
            });
            */
            let suma = 0; 
            ev.forEach((ev:any)=>{
                suma = suma + ev.position; 
            });
            console.log(suma);

            let prev = 0;

            ev.forEach((ev:any)=>{

                rows = rows + Math.floor((ev.position - prev)*800/60)/100 + 'fr '; 
                //rows = rows + Math.floor(ev.position*400/suma)/100 + 'fr ';
                //console.log("ROWS");
                //console.log(rows);
                prev = ev.position; 
            });
            
            rows = rows + Math.floor((60 - prev)*800/60)/100 + 'fr; '; 
            //console.log(rows);
            //rows = rows + (30 - prev) + 'fr;';
        }

        return rows;
    }


    mounted(){
    }

}
</script>
