<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        name: "SubscribeButton",
        props:['active'],

        computed:{
            classes(){
                return ['btn',this.active == true ? "btn-default" : "btn-primary"];
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