<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name"
                       v-text="data.owner.name">
                    </a> said {{ data.created_at }}...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
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

        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-xs mr-1" @click="allowEdit">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                oldBody: false
            };
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },

        methods: {
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
                axios.patch('/replies/' + this.data.id, {
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
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            },
        }
    }
</script>
