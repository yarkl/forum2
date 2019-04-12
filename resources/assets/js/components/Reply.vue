<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default' ">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name"
                       v-text="reply.owner.name">
                    </a> said {{ reply.created_at }}...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" ></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="cancelEdition">Cancel</button>
            </div>

            <div v-else v-html="body"></div>
        </div>

        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns',reply.thread)">
            <div>
                <button class="btn btn-xs mr-1" @click="allowEdit">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-xs mr-1" @click="markAsBest" v-show="!isBest">Mark as best</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        props: ['reply'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                oldBody: false,
                isBest: this.reply.isBest,
            };
        },

        created(){
            window.Event.$on('best-reply-selected',id => {
                this.isBest = (id === this.id)
            })
        },

        methods: {
            markAsBest() {
                this.isBest = true;
                axios.post('/api/replies/' + this.id + '/best');

                window.Event.$emit('best-reply-selected', this.id)
            },
            update() {
                var matches = this.body.match(/^[\s]+$/);

                if(Array.isArray(matches)){
                    console.log("Hello");
                    flash("You cant add empty reply","danger");
                    return false;
                }
                if(this.body === "" ){
                    flash("You cant add empty reply","danger");
                    return false;
                }
                axios.patch('/replies/' + this.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Updated!');
            },

            allowEdit()
            {
                this.editing = true;
                let regex = new RegExp(/<.+>(@.+)<\/a>/g);
                let match = regex.exec(this.body);
                console.log(match);
                if(match){
                    this.oldBody = this.body;
                    this.body = this.body.replace(match[0],match[1]);
                }
            },

            cancelEdition()
            {
                this.editing = false;
                this.body = this.oldBody;
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
            },
        }
    }
</script>
