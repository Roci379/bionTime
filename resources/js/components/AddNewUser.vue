<template>
    <div id="bmk__addnewuser">
        <div class="addnewuser__view">
            <leftbar :user="raw_user"></leftbar>
            
            <div class="addnewuser__content bmk__view__content">
                <div class="addnewuser__title">
                    <div class="addnewuser__title__content"> 
                        <users-icon size="1.5x" class="menuIcon"></users-icon>
                        <p class="addnewuser__text">new user data</p>
                    </div>
                </div>

                <form action="/postImage" method="post" enctype="multipart/form-data" class="addnewuser__form">
                    <div class="form__data">
                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label for="first_name" class="addnewuser__form__label">
                                    First name:
                                </label>

                                <input id="first_name" class="addnewuser__input" name="first_name" type="text" v-model="login.first_name"/>
                                <div v-if="errors && errors.first_name" class="text-danger">{{ errors.first_name[0] }}</div>
                            </div>
                            <div class="form-element addnewuser__form__element">
                                <label for="last_name" class="addnewuser__form__label">
                                    Last Name:
                                </label>

                                <input id="last_name" class="addnewuser__input"  name="last_name" type="text" v-model="login.last_name"/>
                                <div v-if="errors && errors.last_name" class="text-danger">{{ errors.last_name[0] }}</div>

                            </div>
                        </div>
                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="phone">Phone:</label>
                                <input id="phone" class="input addnewuser__input"  name="phone" type="text" v-model="login.phone"/>
                                <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                            </div>
                        </div>
                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="email">Email:</label>
                                <input id="email" class="input addnewuser__input" name="email" type="email" v-model="login.email"/>
                                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                            </div>
                        </div>
                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="password">Password:</label>
                                <input id="password" class="input addnewuser__input" name="password" type="password" v-model="login.password"/>
                                <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                            </div>
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="password_confirmation">Password confirmation:</label>
                                <input id="password_confirmation" class="input addnewuser__input" name="password_conf" type="password"
                                    v-model="login.password_confirmation"/>
                                <div v-if="errors && errors.password_confirmation" class="text-danger">
                                    {{ errors.password_confirmation[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="adduser__errormsg"> {{this.msg}} </p>

                    <div class="form__roles"> 
                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="admin">Admin:</label>
                                <input id="admin" class="input addnewuser__input" name="admin" type="checkbox"/>
                            </div>
                        </div>

                        <div class="form-line addnewuser__form__line">
                            <div class="form-element addnewuser__form__element">
                                <label class="addnewuser__form__label" for="role">Roles:</label>
                            </div>
                            <div class="addnewuser__form__roles" v-for="(value,key) in names">
                                <label for="role" class="label__roles">{{value.name}}</label>
                                <input :id="value.name" :name="value.name" class="input addnewuser__input" :class="value.name" type="checkbox"/>
                                </input>
                            </div>
                        </div>
                    </div>

                    <div class="addnewuser__img" id="div__img">
                        <input @change="getImg" type="file" ref="file" id="img" name="file" accept="image/*" value="Select image" size="15px">
                        <img id="profile_img" src="#" alt="your image" />
                    </div>
                    <div class="addnewuser__button__container">
                        <button @click="submit" type="submit"  class="addnewuser__button">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';
import User from '../models/User';
import axios from "axios";

import Leftbar from './UIBasics/Leftbar.vue';
import { UsersIcon } from 'vue-feather-icons';



@Component({
    components: {
        Leftbar,
        UsersIcon
    }
})
export default class AddNewUser extends Vue {
 
    @Prop(Object)
    raw_user!: {};

    @Prop()
    names!: any;

    @Prop()
    msg!: any; 


    csrf_element: Element | null = document.querySelector('meta[name="csrf-token"]');
    login: object = {};    
    errors: object = {};

    get csrf(): string | null {
        return this.csrf_element == null ? null : this.csrf_element.getAttribute('content');
    }

    async submit() {
        console.log("SUBMIT USER");
        //window.location.href = '/management';
    }

    getImg(){
        console.log("GET IMG");
        let image = (<HTMLInputElement>document.getElementById("img"))!.files![0].name;
        const file = (<HTMLInputElement>document.getElementById("img"))!.files![0];
        if (file) {
            (<HTMLInputElement>document.getElementById("profile_img")).src = URL.createObjectURL(file);
            (<HTMLInputElement>document.getElementById("div__img")).setAttribute('style', "background-color: white; color: #DE9C87");

        }

    }
    

}


</script>

