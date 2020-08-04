<template>
    <div class="main-panel">
        <nav-component :paginas="[ ['Dashboard', 'dashboard0'] ]"></nav-component>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Ganancias(hoy)</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Ganancias(mes)</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Medicamentos vendidos(hoy)</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Medicamentos vendidos(mes)</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Citas de hoy</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Citas(mes)</p>
                                <h3 class="card-title">...</h3>
                            </div>
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <p class="card-category">Cita más cercana</p>
                                <h3 class="card-title">...</h3>
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
                let url = "/Pacientes/totalAdmin";
                axios.get(url).then(function (response) {
                    me.pacientes = response.data;
                    // console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getInformacionCitasTotales(){
                let me = this;
                let url = "/citas/todasCitas";
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
