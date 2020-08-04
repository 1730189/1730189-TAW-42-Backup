<style>
    .disponible {
        color: green;
    }

    .ocupado {
        color: red;
    }

    .invisible {
        background:transparent;
        border:none;
        color:transparent;
    }
</style>

<template>
    <div class="main-panel">
        <nav-component v-bind:paginas="[ ['Consultas', 'consulta1'] ]"></nav-component>
        <div class="content">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado de citas para consultar -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de todas las citas para consulta</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class=" text-primary">
                                        <tr>
                                            <th> Fecha Cita</th>
                                            <th> Hora Cita</th>
                                            <th> Asunto</th>
                                            <th> Fecha Registro</th>
                                            <th> Paciente</th>
                                            <th> Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="cita in citas" :key="cita.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="cita.fecha_cita"></td>
                                            <td v-text="cita.hora_cita"></td>
                                            <td v-text="cita.asunto"></td>
                                            <td v-text="limpiarFecha(cita.created_at)"></td>
                                            <td v-text="cita.nombre_paciente"></td>

                                            <td align="center">
                                                <router-link class="btn btn-outline-info btn-sm" :to="{name: 'addConsulta', params: { slug: cita.id }}">
                                                    <p>Consultar</p>
                                                </router-link>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation example" v-if="pagination.total > 0">
                                        <ul class="pagination">
                                            <li class="page-item" v-if="pagination.current_page > 1">
                                                <a class="page-link" href="#"
                                                   @click.prevent="cambiarPagina(pagination.current_page - 1)">
                                                    <span>Atrás</span>
                                                </a>
                                            </li>
                                            <li class="page-item" v-for="page in pagesNumber"
                                                v-bind:class="[ page === isActived ? 'active' : '']">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)">
                                                    <span v-text="page"></span>
                                                </a>
                                            </li>
                                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                                <a class="page-link" href="#"
                                                   @click.prevent="cambiarPagina(pagination.current_page + 1)">
                                                    <span>Siguiente</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
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
            return {
                informacion: [],
                citas: [],

                horas_disponibles: [],

                fecha_actual: "",
                hora_actual: "",

                //Datos de la cita
                fecha_registro: "",
                id_paciente: "seleccionarPaciente",
                asunto: "",
                fecha_cita: '',
                update: 0,

                //Datos para la paginacion
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                busqueda: false,
            }
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            getInformacionCitaDia(page) {
                let me = this;
                let url = '/consultas/citasHoy?page=' + page;
                axios.get(url).then(function (response) {
                    me.citas = response.data.citas.data;
                    me.pagination = response.data.pagination;
                    console.log(response.data.citas.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getPacientesDoctor() {
                let me = this;
                let url = "/citas/obtenerAllPacientes";
                axios.get(url).then(function (response) {
                    me.pacientes = response.data;
                    // console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cambioCalendario() {

            },
            cargarCita(data) {
                let me = this;
                me.update = data.id;
                let url = "/citas/buscarCita/" + me.update;
                axios.get(url).then(function (response) {
                    me.id_paciente = response.data.id_paciente;
                    me.asunto = response.data.asunto;
                    me.fecha_cita = response.data.fecha_cita;
                    me.cargarHoras();
                    me.id_doctor = response.data.id_medico_asignado;
                    me.hora_cita = response.data.hora_cita;
                    me.precio = response.data.precio;
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            clearFields(modal) {
                let me = this;
                me.id_cita = "";
                me.fecha_registro = "";
                me.id_paciente = "seleccionarPaciente";
                me.asunto = "";
                me.fecha_cita = "";
                me.update = 0;
                me.hora_cita = "seleccionarHora";
                me.precio = 60;
                $(modal).modal("hide");
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.getInformacionCitaDia(page);
            },
            limpiarFecha(fecha){
                return limpieza(fecha);
            }
        },
        created() {
            this.getInformacionCitaDia(1);
            this.getPacientesDoctor();
        }
    }

    function limpieza(fecha) {
        let datos = fecha.split('T');
        return datos[0];
    }
</script>
