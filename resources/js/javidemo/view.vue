<template>

    <div>

        <h1>Clock demo</h1>

        <b> Status: {{ work.status }} </b>

        <button @click="work.start()"> Start </button>
        <button @click="work.stop()"> Stop </button>

        <br>


        There are {{ work.records.length }} register(s)



        <ul>
            <li v-for="r in work.records" :key="r.init.toString()">
                {{ r.init | formatDate }} -> {{ r.end | formatDate }}
            </li>
        </ul>
       

      
    </div>


</template>

<script lang="ts">

import {Component, Vue, Watch} from 'vue-property-decorator';
import axios from "axios";

import {WorkActivity} from '../javidemo/models';


@Component({
    filters: {
        formatDate(d: Date|null){
            if(!d) return '';
            return d.toLocaleString();
        }
    }
})
export default class JaviDemo extends Vue {


    work = new WorkActivity();
    
}


</script>
