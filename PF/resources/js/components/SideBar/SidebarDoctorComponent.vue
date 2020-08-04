<style>
    .router-link-active {
        opacity: 1;
        visibility: visible;
        border-left-color: #4DB6AC;
        margin: 10px;
        transition: all 0.25s;
    }

    body{
        margin: 0;
        font-family: "Nunito", sans-serif;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        text-align: left;
        background-color: #EEEEEE;
    }
</style>

<template>
    <div class="app-body">
        <div class="sidebar" data-color="blue" >
            <div class="logo">
                <router-link :to="{name: 'dashboard1'}" class="simple-text logo-normal">
                    Servicios medicos
                </router-link>
            </div>
            <div class="sidebar-wrapper" id="sidebar-wrapper">
                <ul class="nav">
                    <li :class="[currentPage.includes('index') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'dashboard1'}">
                            <i class="fa fa-th-large" aria-hidden="true"></i>
                            <p>Inicio</p>
                        </router-link>
                    </li>

                    <!-- Pacientes -->
                    <li :class="[currentPage.includes('pacientes') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'pacientes1'}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <p>Pacientes</p>
                        </router-link>
                    </li>

                    <!-- Consultas -->
                    <li :class="[currentPage.includes('consultas') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'consulta1'}">
                            <i class="fa fa-user-md" aria-hidden="true"></i>
                            <p>Consultas</p>
                        </router-link>
                    </li>

                    <!-- Citas -->
                    <li :class="[(currentPage.includes('citas')) ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'citas1'}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <p>Citas</p>
                        </router-link>
                    </li>

                    <!-- Mensajes -->
                    <li :class="[currentPage.includes('mensajesDoctor') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'mensajesDoctor'}">
                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                            <p>Mensajes</p>
                        </router-link>
                    </li>
                </ul>
            </div>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>

        <transition name="slide-fade" mode="out-in">
            <router-view></router-view>
        </transition>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                activeClass: 'active'
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        computed: {
            currentPage() {
                return this.$route.path;
            }
        }
    }
</script>
