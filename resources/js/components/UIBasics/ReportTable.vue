<template>
    <div class="reportTable">
         <table class="rprt__table" id="reportsTable">
            <thead>
                <tr>
                    <th class="rprt__table__title">IDENTIFIER</th>
                    <th class="rprt__table__title">USER</th>
                    <th class="rprt__table__title">DATE</th>
                    <th class="rprt__table__title">BEGIN</th>
                    <th class="rprt__table__title">END</th>
                    <th class="rprt__table__title">ACTIVE</th>
                    <th class="rprt__table__title">TOTAL</th>  
                </tr>
            </thead>
            <tbody v-if="user[0].records!='No records'" v-for="user in info">
                <tr v-for="record in user[0].records">
                    <td class="rprt__table__content">{{user[0].id}}</td>
                    <td class="rprt__table__content">{{user[0].name}}</td>
                    <td class="rprt__table__content">{{record.date}}</td>
                    <td class="rprt__table__content">
                        <tr style="text-align:center;" v-for="hour in record.hours">
                            {{hour.beginning}}
                        </tr>                        
                    </td>
                    <td class="rprt__table__content">
                        <tr style="text-align:center;" v-for="hour in record.hours">
                            {{hour.end}}
                        </tr>
                    </td>
                    <td class="rprt__table__content">
                        <tr style="text-align:center;" v-for="hour in record.hours">
                            {{hour | filterWork}}
                        </tr>
                    </td>
                    <td class="rprt__table__content">
                        <tr style="text-align:center;" v-for="hour in record.hours">
                            {{hour.total}}
                        </tr>
                    </td>
                </tr>
            </tbody>   
            <tbody v-else>
                <tr>
                    <td class="rprt__table__content">{{user[0].id}}</td>
                    <td class="rprt__table__content">{{user[0].name}}</td>
                    <td class="rprt__table__content">No records</td>
                    <td class="rprt__table__content">No records</td>
                    <td class="rprt__table__content">No records</td>
                    <td class="rprt__table__content">No records</td>
                    <td class="rprt__table__content">No records</td>
                </tr>
            </tbody>         
        </table>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';



@Component({
    filters: {
        filterWork(element: any){
            if(element.working){
                return element.total;
            }
            return "00:00:00";
        },

        filterTotal(element: any){
            if(!element.working){
                return element.total;
            }
            return "00:00:00";
        }
    },

    components: {
        
    }
})
export default class EventTable extends Vue {
    @Prop()
    events!: string [];

    @Prop()
    info!: any;

    iden: string = this.info[0][0].id; 

    name: string = this.info[0][0].name;

    records = this.info[0][0].records;
    mounted(){
        //TODO;
    }

}
</script>
