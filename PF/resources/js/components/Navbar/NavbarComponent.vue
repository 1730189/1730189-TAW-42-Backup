<style>
    .backgroundNav{
        background-color: #757980;
    }

    .icono{
        color: #555555;
    }

    .texto{
        color: #898989;
    }
</style>
<template>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top ">
        <div class="container-fluid">
            <div class="navbar-wrapper" v-if="rutas.length > 1">
                <div v-for="ruta in rutas">
                    <router-link :to="{name: ruta.ruta}" style="color: black"   v-if="ruta === rutas[rutas.length - 1]"  v-text="ruta.nombre"></router-link>
                    <router-link :to="{name: ruta.ruta}" class="btn btn-info btn-sm" v-if="ruta !== rutas[rutas.length - 1]">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </router-link>
                </div>
            </div>
            <div class="navbar-wrapper" v-if="rutas.length === 1">
                <div v-for="ruta in rutas">
                    <router-link :to="{name: ruta.ruta}" style="color: black"  v-text="ruta.nombre"></router-link>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell" style="color: black;" aria-hidden="true"></i>
                            <span class="notification" v-text="notificaciones.cantidad">5</span>
                            <p class="d-lg-none d-md-block">
                                Algunos mensajes
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">No hay notificaciones</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle" style="color: black;" aria-hidden="true"></i>
                            <p class="d-lg-none d-md-block">
                                Cuenta
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                            <div class="dropdown-divider"></div>
                            <router-link href="logout" :to="{name: 'salir'}"class="dropdown-item"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar Sesion</router-link>
<!--                            <a </a>-->
<!--                            <a class="dropdown-item" @onClick="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar Sesión</a>-->
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    function capitalize(word) {
        return word[0].toUpperCase() + word.slice(1);
    }

    export default {
        props: {
            paginas: {
                type: Array
            }
        },

        data(){
            return {
                notificaciones: [],
                vinculos: [],
                rutas: []
            }
        },
        created() {
                for(var i=0; i<this.paginas.length; i++){
                this.rutas.push({
                    'nombre': this.paginas[i][0],
                    'ruta': this.paginas[i][1]
                });
            }
        }
    }
</script>
