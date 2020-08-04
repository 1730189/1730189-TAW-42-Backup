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
             data-image="public/material/img/sidebar-1.jpg">
            <div class="logo">
                <router-link :to="{name: 'mensajesAsociado'}" class="simple-text logo-normal">
                    Hospitalic
                </router-link>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li :class="[currentPage.includes('mensajes') ? activeClass : '', 'nav-item']">
                        <router-link class="nav-link" :to="{name: 'mensajesAsociado'}">
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
