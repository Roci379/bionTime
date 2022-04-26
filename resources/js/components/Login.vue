<template>

    <div class="login__layout" id="login">

        <div class="login__img__container">
            <img id ="logoImg" src="/images/clock.png">
        </div>

        <div class="login__form__container">
            <form @submit.prevent="submit" id="login-wrapper" class="form center">
                <img class="logoApp" src="/images/logoBION2.png">
                
                <div class="form_container">
                    <div class="form-line">
                        <div class="form-element">
                            <label for="email" class="formLabel">Mail:</label>
                            <br>
                            <input id="email" class="input" name="email" type="email" v-model="login.email"/>
                            <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

                        </div>
                    </div>
                    <div class="form-line">
                        <div class="form-element">
                            <label class="formLabel" for="password">Password:</label>
                            <br>
                            <input id="password" class="input" name="password" type="password" v-model="login.password"/>
                            <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                        </div>
                    </div>
                    <div class="form-line">
                        <div class="form-element">
                            <label class="formLabel">Remember user
                                <input name="remember" type="checkbox" v-model="login.remember"/>
                            </label>
                        </div>
                    </div>
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                        <button class="logButton" type="submit">Log in</button>
                    </div>
                </div>
            </form>

            <div class="help__link" @click="launchHelp">
                <p>Help</p>
                <help-circle-icon size="1.5x" class="custom-class"></help-circle-icon>
            </div>
            <v-tour name="loginTour" :steps="this.steps"></v-tour>

            <!--a href="/register">Register test</a-->
        </div>
    </div>
</template>

<script lang="ts">

import {Component, Vue} from 'vue-property-decorator';
import axios from "axios";

// Icons
import {HelpCircleIcon } from 'vue-feather-icons'


@Component({
    components: {
        HelpCircleIcon
    }
})
export default class Login extends Vue {

    login: object = {};
    errors: object = {};

    steps = [
        {
            target: '#email',  // We're using document.querySelector() under the hood
            content: `Email of user already registered`
        },
        {
            target: '#password',
            content: 'Introduce your password.'
        },
        {
            target: '.logButton',
            content: 'Click Log In button when data introduced.',
            params: {
              placement: 'top' // Any valid Popper.js placement. See https://popper.js.org/popper-documentation.html#Popper.placements
            }
        }
    ]

    submit() {
        this.errors = {};
        axios.post('/login', this.login).then(response => {
            window.location.href = '/dashboard';
        }).catch(error => {
            this.errors = error.response.data.errors || {};

        });
    }

    launchHelp(){
        this.$tours['loginTour'].start();
    }

    mounted(){
        // this.$tours['loginTour'].start();
    }
}
</script>
