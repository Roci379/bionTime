<template>
    <div class="managementTable">
         <table class="std__table management__std__table" id="managementsTable">
            <tr>
                <th class="std__table__title" v-for="column in tablecontent.columns">{{column}}</th>
            </tr>
             <tr v-for="user in tablecontent.uss">
                <td class="std__table__content" v-for="cont in user">{{cont}}</td>
                <edit-icon @click="modify(user.identifier)" size="2x" class="management__button__icon edit__icon"></edit-icon>
                <x-icon @click="del(user.identifier)" size="2x" class="management__button__icon delete__icon"></x-icon>
            </tr>
            
        </table>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';

// Icons 
import {XIcon} from 'vue-feather-icons'
import { EditIcon } from 'vue-feather-icons'


import axios from "axios";

@Component({
    components: {
        XIcon, EditIcon
    }
})
export default class ManagementTable extends Vue {

    @Prop()
    users!: [];


    @Prop()
    tablecontent = {
        columns: ["identifier", "user", "email", "phone"],
        // "department", "role", "country", "timezone"],
        uss: this.users,
    }

    //iden = {id: 0};

    async modify(id: any){
        //this.iden.id = id; 
        console.log("Modificar usuario con identificador:", id);
        window.location.href= '/modifyUser?id='+id;
        console.log(window.location.href);
        //const response = await axios.post('/postModifyUser', this.iden);
    }

    async del(id: any){
        //this.iden.id = id; 
        console.log("Borrar usuario: ", id);
        const response = await axios.post('/postDeleteUser', {iden: id});
        const data = response.data; 
        console.log("DELETED");
        console.log(data);
        window.location.href = '/management';


    }

    mounted(){
        //TODO;
    }

}
</script>
