<template>
    <div class="alert alert-flash" :class="'alert-' + level " role="alert" v-show="show" v-text="body">

    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: '',
                show: false,
                level: false
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message);
            }

            Event.$on(
                'flash', data => this.flash(data)
            );
        },

        methods: {
            flash(data) {
                this.body = data.message;
                this.show = true;
                this.level = data.level;
                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>