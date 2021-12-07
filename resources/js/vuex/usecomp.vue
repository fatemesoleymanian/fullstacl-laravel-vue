<template>
    <div>
        <div class="content">
            <div class="container-fluid">
        <h1>
            i show all components
        </h1>
                <h2>
                 counter is :{{ counter }}
                </h2>
            </div>
            <div>
            <comp-a></comp-a>
            </div>
            <div><comp-b></comp-b></div>
            <div><comp-c></comp-c></div>
            <Button type="info" @click="changeCounter">Change Counter</Button>
        </div>

    </div>
</template>

<script>
import compA from "./compA";
import compB from "./compB";
import compC from "./compC";
import {mapGetters} from "vuex";
export default {
    name: "usecomp",
    computed:{
      ...mapGetters({
          'counter':'getCounter'
      })
    },
    components:{
        compA,
        compB,
        compC
    },
    methods:{
        changeCounter()
        {
            this.$store.commit('ChangeTheCounter',1)
        }
    }

}
</script>

<style scoped>

</style>
