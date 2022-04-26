<template>
    <div id="bmk__myprofile">
        <div class="bmk__view myprofile__view">
            <leftbar :user="raw_user"></leftbar>            
            <div class="myprofile__content bmk__view__content">
                <div class="user__image">
                    <img :src='this.src'>
                    <div class="white__triangle"></div>
                </div>
                <div class="green__line__img"></div>
                <div class="myprofile__userdata">
                    <div class="myprofile__userinfo">
                        <div class="myprofile__userinfo__unit" v-for="(name,key2) in keys_name" :key="key2">
                            <div v-for="(value, key) in raw_user" v-if="key==key2" class="userinfo__content">
                                <p class="key">{{name}}:  </p> 
                                <p class="value">{{value}}</p>
                            </div>         
                        </div>
                        <div class="myprofile__userinfo__unit">
                            <div class="userinfo__content">
                                <p class="key">Roles: </p>
                                <p class="value" style="text-transform:capitalize; text-align:right">
                                    {{this.roles}}
                                </p>
                            </div>
                        </div>      
                    </div>
                </div>
                <div class="myprofile__title">
                    <div class="myprofile__title__content"> 
                        <p class="myprofile__text">my profile</p>
                        <user-icon size="2.5x" class="menuItem"></user-icon>   
                    </div>
                </div>
                <main class="yield">
                    <a class="logout" style="cursor: pointer" @click.prevent="logout">Cerrar sesión</a>
                </main>
                <div class="help__link" @click="launchHelp">
                        <p>Help</p>
                        <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                </div>
            </div>
            <v-tour name="myprofileTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';

import User from '../models/User';
import axios from "axios";
import {UserIcon} from 'vue-feather-icons';
import {HelpCircleIcon } from 'vue-feather-icons'

import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';




@Component({
    components: {
        UserIcon, HelpCircleIcon,
        
        Leftbar, UpperMenu
    }
})
export default class MyProfile extends Vue {

    @Prop(Object)
    raw_user!: any;

    @Prop()
    functions!: any;

    src = "";

    roles = this.stringFunctions();

    dpitems: string[][] = [['TODAY', 'THIS WEEK', 'THIS MONTH'], ['Planned', 'Extra', 'Worked']]
    types = ['list', 'daily', 'weekly']

    keys_name = {id: 'identifier', first_name:'first name', last_name:'last name', email:'email', admin: 'admin'}
    

    steps = [
        {
            target: '#summary',
            content: 'Here you can access a summary of your daily activity.'
        },
        {
            target: '#timer',
            content: 'Here you can access your records.'
        },
        {
            target: '#calendar',
            content: 'Here you can see calendar of your events and other users events.'
        },
        {
            target: '#reports',
            content: 'Here you can see a summary of users records.'
        },
        {
            target: '#management',
            content: 'Here you can add, modify or delete other users.'
        },
        {
            target: '.myprofile__userinfo',
            content: 'Here is your information.'
        },
        {
            target: '.logout',
            content: 'Click here to log out.'
        }

    ]
    
    get user() {
        return new User(this.raw_user);
    }

    stringFunctions() {
        let string_functions = "";
        for(let element in this.functions){
            console.log(string_functions);
            string_functions +=this.functions[element].function + ", ";
        }
        string_functions = string_functions.substring(0, string_functions.length - 2);

        console.log(string_functions);
        return string_functions;
    }


    async logout() {
        await axios.post("/logout");
        location.href = '/';
    }
    
    mounted() {
        this.src = this.raw_user.profile_image.split("/");
        this.src = this.src[this.src.length -1];
        this.src = "/images/profileImages/"+this.src;
        console.log(this.src);
        //this.$tours['myprofileTour'].start();
        //this.loadProposals();
    }

    launchHelp(){
        this.$tours['myprofileTour'].start();
    }



}

</script>

