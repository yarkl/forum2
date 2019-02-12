<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="Have something to say?"
                          rows="5"
                          required
                          v-model="body"></textarea>
            </div>

            <button type="submit"
                    class="btn btn-default"
                    @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this
            discussion.
        </p>
    </div>
</template>

<script>

    import 'at.js';
    import 'jquery.caret';


    export default {

        data() {
            return {
                body: ''
            };
        },



        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })

                    .catch(error => window.flash(error.response.data,'danger'))

                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted.');
                        this.$emit('created', data);
                    });
            }
        },
        mounted(){
            $('#body').atwho({
              at: "@",
              limit: 5,
              //displayTimeout: 1000,
              maxLen: 40,
              callbacks: {
                remoteFilter: function(query, callback) {
                  $.getJSON("/users", {name: query}, function(data) {
                    callback(data)
                  });
                }
              }
            });
        }
    }
</script>