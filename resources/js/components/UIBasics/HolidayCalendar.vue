<template>
    <div class="bmk__holiday__container">
        <div class="bmk__holiday">
            <header>
                <span></span>
                <div class="days">
                    <div v-for="(day, i) in daysRendered()" :key="i">
                        <gantdayhead :gantdate="day"></gantdayhead>
                    </div>
                </div>
            </header>
            <main :style="'grid-template-rows: repeat('+holidaysByUsers().length+', auto)'">
                <div v-for="(user, i) in holidaysByUsers()" :key="i" class="user__record">
                    <div class="user__record__yaxis">
                        <p>{{user.userId}}</p>
                    </div>
                    <div class="user__record__content">
                        <div v-for="(day, j) in daysRendered()" :key="j" class="record__day" :class="{'free': isFree(user, day), 'medical': isMedical(user,day), 'other': isOther(user,day)}">
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
import * as moment from 'moment';

import GantDayHead from './GantDayHead.vue';

import Holidays from '../../models/Holidays';

interface userHolidays {
  userId: string,
  periods: Holidays[]
}

@Component({
    components: {
    }
})
export default class HolidayCalendar extends Vue {

    @Prop()
    events!: any[];

    @Prop()
    filtered!: any[];

    @Prop()
    filter!: boolean; 


    usersHolidays: Holidays[] = [];
    eventos: any[] = [];
    /*
        new Holidays({
            user_id: 'George Russell',
            startingDate: new Date(2021, 4, 16),
            endDate: new Date(2021, 4, 17)
        }),
        new Holidays({
            user_id: 'George Russell',
            startingDate: new Date(2021, 4, 20),
            endDate: new Date(2021, 4, 22)
        }),
        new Holidays({
            user_id: 'Nikita Mazepin',
            startingDate: new Date(2021, 4, 20),
            endDate: new Date(2021, 4, 22)
        }),
        new Holidays({
            user_id: 'Pierre Gasly',
            startingDate: new Date(2021, 4, 20),
            endDate: new Date(2021, 4, 22)
        }),
        new Holidays({
            user_id: 'Pierre Gasly',
            startingDate: new Date(2021, 5, 1),
            endDate: new Date(2021, 5, 6)
        }),
        new Holidays({
            user_id: 'Esteban Ocon',
            startingDate: new Date(2021, 4, 26),
            endDate: new Date(2021, 4, 28)
        }),
        new Holidays({
            user_id: 'Esteban Ocon',
            startingDate: new Date(2021, 5, 10),
            endDate: new Date(2021, 5, 19)
        }),
        new Holidays({
            user_id: 'Esteban Ocon',
            startingDate: new Date(2021, 5, 27),
            endDate: new Date(2021, 5, 28)
        }),
        new Holidays({
            user_id: 'Daniel Ricciardo',
            startingDate: new Date(2021, 5, 18),
            endDate: new Date(2021, 5, 19)
        }),
        new Holidays({
            user_id: 'Daniel Ricciardo',
            startingDate: new Date(2021,4, 16),
            endDate: new Date(2021, 4, 19)
        }),
    ]*/

    
    transform(){
        if(this.filter){
            this.eventos = this.filtered; 
            console.log("FILTERED");
        }else{
            console.log("NOT FILTERED");
            this.eventos = this.events;
        }
        console.log(this.eventos);
        this.usersHolidays = [];
        for(let i = 0; i<this.eventos.length; i++){
            console.log(i);
            let year = (moment(this.eventos[i].startingDate).format("YYYY"));
            let month = (moment(this.eventos[i].startingDate).format("MM"));
            let day = (moment(this.eventos[i].startingDate).format("DD"));
            let year2 = (moment(this.eventos[i].endingDate).format("YYYY"));
            let month2 = (moment(this.eventos[i].endingDate).format("MM"));
            let day2 = (moment(this.eventos[i].endingDate).format("DD"));
            let holiday = new Holidays({
                user_id: this.eventos[i].user_id,
                startingDate: new Date(parseInt(year,10), parseInt(month,10)-1, parseInt(day,10)),
                endDate: new Date(parseInt(year2,10), parseInt(month2,10)-1, parseInt(day2,10)),
                type: this.eventos[i].type,
            })
            this.usersHolidays[i] = holiday; 
        }
        //console.log(usHolidays);
        console.log(this.usersHolidays);
    }

    daysRendered(): moment.Moment[] {
        let days = [];
        let today = moment(new Date());
        let start = today.clone().startOf('week');
        let end = today.clone().endOf('week');

        for(let i = 0; i < end.diff(start, 'days') + 1; i++) {
            days.push(moment(start).add(i, 'days'))
        }

        //console.log("Days");
        //console.log(days);
        return days;
    }
    
    holidaysByUsers() {
        this.transform();
        let byUsers: userHolidays[] = [];
        //console.log(this.usersHolidays.length);
        this.usersHolidays.forEach((hol) => {
            let ixUser = byUsers.findIndex((hbu) => hbu.userId == hol.userId)
            //console.log("IX USER");
            //console.log(ixUser);
            if(ixUser == -1) {
                let arrayOfHolidays = []
                let newUser: userHolidays = {
                    userId: hol.userId,
                    periods: []
                }
                newUser.periods.push(hol)
                byUsers.push(newUser)
            } else {
                byUsers[ixUser].periods.push(hol)
            }
        });
        return byUsers;
    }

    isFree(user: userHolidays, day: moment.Moment) {
        let isFree= false;
        let typ = 'other';
        user.periods.forEach((period) => {
            //console.log(period.startingDate)
            //console.log(moment().isSameOrAfter(moment(period.startingDate)))
            if (day.isSameOrAfter(moment(period.startingDate)) && day.isSameOrBefore(moment(period.endDate))) {
                //console.log("offf");
                if(period.type == "free"){
                    isFree = true; 
                }
                return
            } 
        });

        return isFree;
    }

     isMedical(user: userHolidays, day: moment.Moment) {
        let isMed= false;
        let typ = 'other';
        user.periods.forEach((period) => {
            //console.log(period.startingDate)
            //console.log(moment().isSameOrAfter(moment(period.startingDate)))
            if (day.isSameOrAfter(moment(period.startingDate)) && day.isSameOrBefore(moment(period.endDate))) {
                //console.log("offf");
                if(period.type == "medical"){
                    isMed = true; 
                }
                return
            } 
        });

        return isMed;
    }

     isOther(user: userHolidays, day: moment.Moment) {
        let isOther= false;
        let typ = 'other';
        user.periods.forEach((period) => {
            //console.log(period.startingDate)
            //console.log(moment().isSameOrAfter(moment(period.startingDate)))
            if (day.isSameOrAfter(moment(period.startingDate)) && day.isSameOrBefore(moment(period.endDate))) {
                //console.log("offf");
                if(period.type == "other"){
                    isOther = true; 
                }
                return
            } 
        });

        return isOther;
    }


    mounted(){
        this.transform();
    }

}
</script>
