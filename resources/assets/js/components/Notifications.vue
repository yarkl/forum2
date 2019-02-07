<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
            Notifications <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a href="#" @click.prevent="notificationId = notification.id">{{notification.data.message}}</a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "Notifications",

        data(){
            return {
                notifications : false,
                notificationId : false
            }
        },

        watch:{
            notificationId(){
                window.axios.delete('/profiles/' + window.App.user.name + '/notifications/' + this.notificationId);
                let elem = this.notifications.find((element) => {
                    return element.id === this.notificationId;
                });
                window.location.href = elem.data.link;
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