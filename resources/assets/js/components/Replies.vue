<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>
        <paginator :dataSet="dataSet" @changed="fetch"></paginator>
        <new-reply  @created="add"></new-reply>
    </div>
</template>


<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection';
    import page from '../mixins/page';

    export default {

        components: { Reply, NewReply },

        mixins: [collection,page],


        data() {
            return { dataSet: false }
        },

        created(){
            this.fetch()
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (! page) {
                    page = this.getPage();
                }

                return `/api/${location.pathname.substr(1)}?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            },

        }
    }
</script>

<style scoped>

</style>