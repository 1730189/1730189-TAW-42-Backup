<template>
    <div class="main-panel">
        <nav-component :paginas="[ ['Dashboard', 'dashboard1'] ]"></nav-component>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Pacientes en esta clinica</p>
                                <h3 class="card-title" v-text="pacientes"></h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return{
                usuario: [],
                pacientes: 0,
                citasR: 0,
                citasConsultadas: 0
            }
        },
        methods:{
            getInformacionPacientes(){
                let me = this;
                let url = "/pacientes/datos";
                axios.get(url).then(function (response) {
                    me.pacientes = response.data.cantPacientes;
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getInformacionCitasTotales(){
                let me = this;
                let url = '/citas/allCitasDoctor';
                axios.get(url).then(function (response) {
                    me.citasR = response.data.citasRegistradas;
                    me.citasConsultadas = response.data.citasConsultadas;
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        created() {
            this.getInformacionPacientes();
            this.getInformacionCitasTotales();
        }
    }

</script>
