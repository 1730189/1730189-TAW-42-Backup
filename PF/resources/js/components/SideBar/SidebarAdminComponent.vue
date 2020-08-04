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
                    Hospitalic
                </router-link>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li :class="[currentPage.includes('index') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'dashboard1'}">
                            <i class="material-icons">dashboard</i>
                            <p>Inicio</p>
                        </router-link>
                    </li>

                    <!-- Pacientes -->
                    <li :class="[currentPage.includes('pacientes') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'pacientes'}">
                            <i class="material-icons">person</i>
                            <p>Pacientes</p>
                        </router-link>
                    </li>

                    <!-- Citas -->
                    <li :class="[(currentPage.includes('citas') || currentPage.includes('agendarCita')) ? activeClass : '', 'nav-item']">
                        <a class="nav-link" data-toggle="collapse" href="#citasDrop" aria-expanded="true">
                            <i class="material-icons">date_range</i>
                            <p>Citas
                                <b class="caret"></b>
                            </p>
                        </a>

                        <div class="collapse show" id="citasDrop">
                            <ul class="nav">
                                <li :class="[(currentPage.includes('citas')) ? activeClass : '', 'nav-item']">
                                    <router-link class="nav-link" :to="{name: 'citas1'}">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <p>Ver citas</p>
                                    </router-link>
                                </li>

                                <li :class="[currentPage.includes('agendarCita') ? activeClass : '', 'nav-item']">
                                    <router-link class="nav-link" :to="{name: 'agendarCita1'}">
                                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                        <p>Registrar cita</p>
                                    </router-link>
                                </li>
                            </ul>
                        </div>
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
