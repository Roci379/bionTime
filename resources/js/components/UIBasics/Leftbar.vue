<template>
    <aside class="leftbar__menu">
        <div class="logo__img__container">
            <img id="logoImg" src="/images/logoBION2.png">
        </div>

        <div class="options__container">
            <ul class="leftMenu">
                <li v-for="(item, i) in items" :key="i" class="menuItem">
                    <leftbaritem :link="item.route" :name="item.name" :id="item.name">
                        <menu-icon v-if="item.name=='summary'" size="1.5x" class="menuIcon"></menu-icon>
                        <clock-icon v-if="item.name=='timer'" size="1.5x" class="menuIcon"></clock-icon>
                        <calendar-icon v-if="item.name=='calendar'" size="1.5x" class="menuIcon"></calendar-icon>
                        <bar-chart-2-icon v-if="item.name=='reports'" size="1.5x" class="menuIcon"></bar-chart-2-icon>
                        <users-icon v-if="item.name=='management'" size="1.5x" class="menuIcon"></users-icon>
                        <div class="calendar__items" v-if="item.name=='calendar'">
                            <a class="calendar__item" :href="'/calendar'">My calendar</a>
                            <a class="calendar__item" :href="'/ccalendar'">Company</a>
                        </div>
                    </leftbaritem>
                </li>
            </ul>
        </div>
        <div class="menuItem myProfile">
            <a :href="'/myprofile'" class="profile">my profile</a>
            <img :src='this.src' class="profile__image">
        </div> 
        <v-tour name="leftbarTour" :steps="this.steps"></v-tour>
    </aside>
</template>



<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';
import { ClockIcon } from 'vue-feather-icons';
import { CalendarIcon } from 'vue-feather-icons';
import { MenuIcon } from 'vue-feather-icons';
import { BarChart2Icon } from 'vue-feather-icons';
import { UsersIcon } from 'vue-feather-icons';
             

@Component({
    components: {
    ClockIcon, CalendarIcon, BarChart2Icon, MenuIcon, UsersIcon
  }
})
export default class Leftbar extends Vue {

    @Prop(Object)
    user!: any;

    src ="";
    items = [
            {name: "summary", route: "/dashboard"},
            {name: "timer", route: "/timer"},
            {name: "calendar", route: "/calendar"}, 
            {name: "reports", route: "/reports"},
            {name: "management", route: "/management"}
            ]  
     
    calendar_types = [{name: 'my calendar', route: '/calendar'}, {name: 'company', route: '/ccalendar'}] 

    mounted(){
        console.log(this.user.admin);
        if(this.user.admin==false){
            this.items = [
            {name: "summary", route: "/dashboard"},
            {name: "timer", route: "/timer"},
            {name: "calendar", route: "/calendar"}
            ] 
        }

        this.src = this.user.profile_image.split("/");
        this.src = this.src[this.src.length -1];
        this.src = "/images/profileImages/"+this.src;
        console.log(this.src);


    }
}
</script>
