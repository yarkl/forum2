<template>
    <button :class="classes" @click="subscribe">{{ name }}</button>
</template>

<script>
    export default {
        name: "SubscribeButton",
        props:['active'],

        data(){
            return {
                'name': this.active ? this.name = "Unsubscribe" : this.name = "Subscribe"
            }
        },

        computed:{
            classes(){
                return ['btn',this.active == true ? "btn-default" : "btn-primary"];
            }
        },
        watch:{
            active(){
                this.active ? this.name = "Unsubscribe" : this.name = "Subscribe";
            }
        },

        methods:{
            subscribe()
            {
                axios[this.active == true ? 'delete' : 'post'](location.pathname + "/subscribe",{
                    'user_id':window.App.user.id
                });
                this.active = !this.active;
            }
        }
    }
</script>

<style scoped>

</style>