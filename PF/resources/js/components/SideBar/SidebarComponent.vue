<template>
    <sidebar-admin v-if="id_rol === 4"></sidebar-admin>
    <sidebar-doctor v-else-if="id_rol === 1"></sidebar-doctor>
    <sidebar-asociado v-else-if="id_rol === 2"></sidebar-asociado>
    <sidebar-secretaria v-else-if="id_rol === 3"></sidebar-secretaria>
</template>

<script>
    export default {
        data() {
            return {
                id_rol: 0
            }
        },
        methods: {
            getUser(){
                let me = this;
                let url = '/usuarios/rol';
                axios.get(url).then(function (response) {
                    me.id_rol = response.data;
                }).catch(function (error) {
                    console.log(error + 'Error');
                });
            }
        },
        created() {
            this.getUser();
        }
    }
</script>
