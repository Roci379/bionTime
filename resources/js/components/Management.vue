<template>
    <div id="bmk__management">
        <div class="management__view">
            <leftbar :user="raw_user"></leftbar>
            
            <div class="management__content bmk__view__content">
                <div class="management__title">
                    <div class="help__link" @click="launchHelp">
                        <p>Help</p>
                        <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                    </div>
                    <div class="management__title__content"> 
                        <p class="management__text">management</p>
                        <users-icon size="1.5x" class="menuIcon"></users-icon>
                    </div>
                </div>

                <div class="managament__search">
                    <input type="text" class="management__input" id="inputSearch" @keyup="search" placeholder="Search by identifier or user...">
                </div>

                <div class="management__table">
                    <managementtable :users="users"></managementtable>
                </div>

                <div class="management__adduser">
                    <a href="/addnewuser">
                        <button class="management__adduser__button" type="submit">Add new user</button>
                    </a>
                </div>
            </div>
            <v-tour name="managementTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';

import User from '../models/User';
import axios from "axios";
import Leftbar from './UIBasics/Leftbar.vue';
import ManagementTable from './UIBasics/ManagementTable.vue';

import { UsersIcon } from 'vue-feather-icons';
import { SearchIcon } from 'vue-feather-icons';
import {HelpCircleIcon } from 'vue-feather-icons'
import {PlusIcon} from 'vue-feather-icons';




@Component({
    components: {
        
        Leftbar, ManagementTable,

        UsersIcon, SearchIcon, PlusIcon, HelpCircleIcon

    }
})
export default class Management extends Vue {

    @Prop(Object)
    raw_user!: {};

    @Prop()
    users!: [];

    @Prop()
    names!: any;

    dpitems: string[][] = [['TODAY', 'THIS WEEK', 'THIS MONTH'], ['Planned', 'Extra', 'Worked']]
    types = ['list', 'daily', 'weekly']

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
            target: '.profile',
            content: 'Here you can see your personal information.'
        },
        {
            target: '.management__input',
            content: 'Filter by identifier or user value.'
        },
        {
            target: '.management__table',
            content: 'Here you can see other users with no admin roles.'
        },
        {
            target: '.management__adduser__button',
            content: 'Click here to add new users.'
        }

    ]
    get user() {
        return new User(this.raw_user);
    }

    mounted() {
        //this.$tours['managementTour'].start();
        //this.loadProposals();
    }

    launchHelp(){
        this.$tours['managementTour'].start();
    }

    myFunction() {
        //TODO;
    }

    search(){

        var input, filter, table, tr, td0, td1, i, txtValue0, txtValue1;
        input = (<HTMLInputElement>document.getElementById("inputSearch"));
        filter = input.value.toUpperCase();
        //console.log(filter);
        table = (<HTMLInputElement>document.getElementById("managementsTable"));
        //console.log(table);
        tr = table.getElementsByTagName("tr");
        for(i=0; i<tr.length; i++){
            td0 = tr[i].getElementsByTagName("td")[0];
            td1 = tr[i].getElementsByTagName("td")[1];
            if(td0 || td1){
                txtValue0 = td0.textContent || td0.innerText;
                txtValue1 = td1.textContent || td1.innerText;
                if(txtValue0.toUpperCase().indexOf(filter)>-1 || txtValue1.toUpperCase().indexOf(filter)>-1){
                    tr[i].style.display="";
                }else{
                    tr[i].style.display = "none";
                }
            }
        }
    }


}


</script>

