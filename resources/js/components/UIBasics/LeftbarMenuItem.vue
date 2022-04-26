<template>
    <a :href="link"">
        {{name}}
        <slot>
        </slot>
        
    </a>
</template>

<script lang="ts">

import {Vue, Component, Prop} from 'vue-property-decorator';

@Component({})
export default class LeftbarMenuItem extends Vue {
    @Prop()
    link!: string

    @Prop()
    name!: string


}
</script>

