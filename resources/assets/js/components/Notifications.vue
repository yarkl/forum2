<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
            Notifications <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link">{{notification.data.message}}</a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "Notifications",

        data(){
            return {
                notifications : false
            }
        },

        created(){
            window.axios.get('/profiles/' + window.App.user.name + '/notifications').
                then(response => this.notifications = response.data);
        },


    }
</script>

<style scoped>

</style>