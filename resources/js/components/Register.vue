<template>
    <div id="register">
        <form @submit.prevent="submit" id="login-wrapper" class="form center" style="display: flex; flex-direction: column;">

            <div class="form-line">
                <div class="form-element">
                    <label for="first_name" class="label subtitle-light">
                        First name
                    </label>

                    <input id="first_name" name="first_name" type="text" v-model="login.first_name"/>
                    <div v-if="errors && errors.first_name" class="text-danger">{{ errors.first_name[0] }}</div>

                </div>
                <div class="form-element">
                    <label for="last_name" class="label subtitle-light">
                        Last Name
                    </label>

                    <input id="last_name" name="last_name" type="text" v-model="login.last_name"/>
                    <div v-if="errors && errors.last_name" class="text-danger">{{ errors.last_name[0] }}</div>

                </div>
            </div>
            <div class="form-line">
                <div class="form-element">
                    <label class="label subtitle-light" for="phone">Phone</label>
                    <input id="phone" class="input" name="phone" type="text" v-model="login.phone"/>
                    <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                </div>
            </div>
            <div class="form-line">
                <div class="form-element">
                    <label class="label subtitle-light" for="email">Email</label>
                    <input id="email" class="input" name="email" type="email" v-model="login.email"/>
                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                </div>
            </div>

            <div class="form-line">
                <div class="form-element">
                    <label class="label subtitle-light" for="password">Password</label>
                    <input id="password" class="input" name="password" type="password" v-model="login.password"/>
                    <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                </div>
                <div class="form-element">
                    <label class="label subtitle-light" for="password_confirmation">Password confirmation</label>
                    <input id="password_confirmation" class="input" name="password" type="password"
                           v-model="login.password_confirmation"/>
                    <div v-if="errors && errors.password_confirmation" class="text-danger">
                        {{ errors.password_confirmation[0] }}
                    </div>
                </div>
            </div>
            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <button type="submit">Register</button>
            </div>
        </form>
        <a href="/login">Already registered? Log in</a>
    </div>

</template>

<script lang="ts">

import {Component, Vue} from 'vue-property-decorator';
import axios from "axios";

@Component({})
export default class Register extends Vue {

    csrf_element: Element | null = document.querySelector('meta[name="csrf-token"]');
    login: object = {};
    errors: object = {};

    get csrf(): string | null {
        return this.csrf_element == null ? null : this.csrf_element.getAttribute('content');
    }

    
    async submit() {
        this.errors = {};
        /*
        try {
            await axios.post('/register', this.login);
            window.location.href = '/dashboard';

        } catch (error) {
            if ((error as Error).response.status === 422) {
                this.errors = (error as Error).response.data.errors || {};
            }
        }*/
    }
}
</script>
