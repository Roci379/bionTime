<template>
    <div class="arrowButton">
        <div class="arrowMenu__button">
            <chevron-up-icon v-if="expanded" size="7x" class="arrowIcon"></chevron-up-icon>
            <chevron-down-icon v-else size="7x" class="arrowIcon"></chevron-down-icon>
        </div>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator';
import { ChevronUpIcon } from 'vue-feather-icons'
import { ChevronDownIcon } from 'vue-feather-icons'



@Component({
    components: {
        ChevronUpIcon, ChevronDownIcon
    }
})
export default class Arrow extends Vue {
    @Prop()

    expanded! : boolean; 

    mounted(){
        //TODO;
    }

}
</script>
