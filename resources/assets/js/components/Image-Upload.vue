<template>
    <div>
        <form v-if="canUpdate" method="post" enctype="multipart/form-data">
            <input name="avatar" type="file" @change="change">
        </form>
        <img :src="avatar" alt="" style="width: 40px; height: 40px;">
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                'avatar' : '/storage' + this.user.avatar_path
            }
        },

        computed: {
            canUpdate(){
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            change(e){
                if(! e.target.files.length) return;

                let avatar = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(avatar);

                reader.onload = e => {
                    this.avatar = e.target.result;
                }
                this.persist(avatar);
            },
            persist(avatar){
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`,data)
                    .then(() => flash('Avatar Uploaded'));
            }
        }

    }
</script>

<style scoped>

</style>