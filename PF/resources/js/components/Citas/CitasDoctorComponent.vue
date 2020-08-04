<link rel='stylesheet' href='resources/assets/css/fullcalendar.min.css'/>

<style>
    .disponible {
        color: green;
    }

    .ocupado {
        color: red;
    }

    .invisible {
        background: transparent;
        border: none;
        color: transparent;
    }
</style>

<template>
    <div class="main-panel">
        <nav-component v-bind:paginas="[ ['Citas', 'citas1'] ]"></nav-component>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-info card-header-icon">
                                <p class="card-category">Citas dia de hoy</p>
                                <h3 class="card-title" v-text="informacion.cantCitasHoy"></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ejemplo de tabla Listado de Alergias -->
                <div class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de todas las citas</h4>
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modalCita">
                                    <i class="fa fa-plus-circle"></i>&nbsp;
                                    <b>Agendar nueva cita</b>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class=" text-primary">
                                        <tr>
                                            <th> Asunto</th>
                                            <th> Fecha Cita</th>
                                            <th> Hora Cita</th>
                                            <th> Paciente</th>
                                            <th> Estado</th>
                                            <th> Fecha Registro</th>
                                            <th> Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="cita in citas" :key="cita.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="cita.asunto"></td>
                                            <td v-text="cita.fecha_cita"></td>
                                            <td v-text="cita.hora_cita"></td>
                                            <td v-text="cita.fecha_creacion"></td>
                                            <td v-text="cita.paciente"></td>

                                            <td v-if="consultada.length !== 0" v-for="consulta in consultada" :key="consulta.id">
                                                <span v-if="cita.id === consulta.id">Consultada</span>
                                                <span v-else="">Sin consultar</span>
                                            </td>
                                            <td v-if="consultada.length === 0">
                                                <span>No Consultada</span>
                                            </td>

                                            <td align="center">
                                                <!-- EDITAR CITA PACIENTE-->
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalCita"
                                                        @click="cargarCita(cita)">
                                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                                </button>

                                                <!--ELIMINAR CITA -->
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        data-toggle="modal" data-target="#modalEliminar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                                <!-- MODAL DE ELIMINAR CITA  -->
                                                <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog"
                                                     aria-labelledby="myModalLabel" style="display: none;"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-danger" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">
                                                                    Eliminar Alergia
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <a id="valor"></a>
                                                            <div class="modal-body">
                                                                <p> ¿Estas seguro de eliminar permanentemente la
                                                                    cita? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    Cerrar
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        @click="deleteCita(cita)"
                                                                        data-dismiss="modal">
                                                                    Eliminar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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

                <!--  MODAL AGREGAR / EDITAR CITA  -->
                <div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-if="update == 0">Registro de Cita</h4>
                                <h4 class="modal-title" v-if="update != 0">Edición de Cita</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        @click="clearFields('#modalCita')">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" class="form-horizontal" data-parsley-validate>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Asunto de cita</label>
                                        <div class="col-md-9">
                                            <input v-model="asunto" type="text" id="asunto" name="asunto"
                                                   v-bind:class="{'form-control': true, 'has-error' : errors.has('asunto')}"/>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('asunto')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Paciente</label>
                                        <div class="col-md-9">
                                            <select id="id_paciente" v-model="id_paciente" name="id_paciente"
                                                    v-bind:class="{'select-css': true, 'has-error' : errors.has('id_paciente')}">
                                                <option disabled selected value="seleccionarPaciente">Seleccione el
                                                    paciente
                                                </option>
                                                <option v-for="paciente in pacientes" v-bind:value="paciente.id"
                                                        v-text="paciente.nombre + ' ' + paciente.apellidos"></option>
                                            </select>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('id_paciente')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Fecha de cita</label>
                                        <div class="col-md-3">
                                            <datetime type="date" v-model="fecha_cita" id="fecha_cita"
                                                      format="yyyy-MM-dd" value-zone="America/Mexico_City"
                                                      zone="America/Mexico_City" :min-datetime="fecha_actual">
                                                <template slot="button-cancel">
                                                    <p>Cancelar</p>
                                                </template>
                                                <template slot="button-confirm">
                                                    <p>Aceptar</p>
                                                </template>
                                            </datetime>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('fecha_cita')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Hora de la cita</label>
                                        <div class="col-md-3">
                                            <select id="hora_cita" v-model="hora_cita" name="hora_cita"
                                                    :disabled="cargarHoras()"
                                                    v-bind:class="{'select-css': true, 'has-error' : errors.has('hora_cita')}">
                                                <option disabled selected value="seleccionarHora">Seleccione hora
                                                    disponible
                                                </option>
                                                <option v-for="hora in horas_disponibles" v-bind:value="hora.hora"
                                                        v-text="hora.hora" :disabled="hora.estado == 0"
                                                        v-bind:class="{'disponible' : hora.estado, 'ocupado' : !hora.estado}"></option>
                                            </select>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('hora_cita')}}
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" @click="clearFields('#modalCita')">
                                    Cerrar
                                </button>
                                <button type="button" v-if="update == 0" class="btn btn-success" @click="addCita()">
                                    Guardar
                                </button>
                                <button type="button" v-if="update != 0" class="btn btn-success" @click="updateCita()">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!------- FINAL DEL MODAL-------------------->

            </div>
        </div>
    </div>
</template>

<script>
    class Errors {
        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
                if (this.errors[field][0] === 'The selected id paciente is invalid.') {
                    return 'Por favor seleccione al paciente';
                }
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors.errors;
        }

        has(field) {
            if (this.errors[field]) {
                return true;
            } else {
                return false;
            }
        }
    }

    export default {
        data() {
            return {
                informacion: [],
                citas: [],
                consultada: [],
                pacientes: [],

                horas_disponibles: [],

                fecha_actual: "",
                hora_actual: "",

                //Datos de la cita
                fecha_registro: "",
                id_paciente: "seleccionarPaciente",
                asunto: "",
                fecha_cita: '',
                update: 0,
                hora_cita: "seleccionarHora",
                fecha_final: "",
                errors: new Errors(),
                fecha_citaB: "",

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
            getInformacionCita(page) {
                let me = this;
                let url = '/citas/obtenerCitas?page=' + page;
                axios.get(url).then(function (response) {
                    me.citas = response.data.citas.data;
                    me.consultada = response.data.citasConsultadas;
                    me.pagination = response.data.pagination;
                    me.fecha_actual = response.data.fechaActual;
                    me.informacion = response.data.informacion;
                    console.log(response.data);
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
            addCita() {
                let me = this;
                me.errors = new Errors();
                let url = '/citas/crearCitaDoctor';

                axios.post(url, {
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_cita),
                    'hora_cita': me.hora_cita,
                    'id_paciente': me.id_paciente,
                }).then(function (response) {
                    me.getInformacionCita(1);
                    me.clearFields("#modalCita");
                    toastr.success("Se ha agendado la cita correctamente", "Cita agendada");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                    toastr.error("Error al agendar la cita", "Cita no agendada");
                });
            },
            updateCita() {
                let me = this;
                me.errors = new Errors();
                let url = '/citas/editarCitaDoctor';
                axios.patch(url, {
                    'id': me.update,
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_cita),
                    'hora_cita': me.hora_cita,
                    'id_paciente': me.id_paciente,
                }).then(function (response) {
                    me.getInformacionCita(me.pagination.current_page);
                    me.clearFields("#modalCita");
                    toastr.success("Se ha actualizado la cita correctamente", "Cita actualizada");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                    toastr.error("Error al actualizar la cita", "Cita no actualizada");
                });
            },
            deleteCita(data) {
                let me = this;
                let cita_id = data.id;
                let url = '/citas/eliminarCita/' + cita_id;
                axios.delete(url).then(function (response) {
                    me.getInformacionCita(1);
                    $("modalEliminar").modal("hide");
                    toastr.success("Se ha eliminado correctamente la cita", "Eliminación correcta");
                }).catch(function (error) {
                    // console.log(error);
                    toastr.error("No se ha podido eliminar la cita", "Eliminación erronea");
                });
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
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cargarHoras() {
                let me = this;
                if (me.fecha_cita == '') {
                    return true;
                }
                let url = '/citas/horasDisponibles/' + limpiarFecha(me.fecha_cita);
                axios.get(url).then(function (response) {
                    me.horas_disponibles = response.data;
                    // console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
                return false;
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
                me.fecha_final = "";
                me.fecha_citaB = "";
                me.horas_disponibles = [];
                $(modal).modal("hide");
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                if (this.busqueda) {
                    this.busquedaCita(page);
                } else {
                    this.getInformacionCita(page);
                }
            },
            busquedaCita(page) {
                let me = this;
                let url = '/citas/buscarCitaDoctor?page=' + page;

                axios.post(url, {
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_citaB),
                    'fecha_final': limpiarFecha(me.fecha_final),
                    'id_paciente': me.id_paciente
                }).then(function (response) {
                    me.citas = response.data.citas.data;
                    me.pagination = response.data.pagination;
                    me.busqueda = true;
                    me.clearFields("#modalBusquedaCita");
                    toastr.success("Resultados de busqueda", "Resultados de busqueda");
                }).catch(function (error) {
                    toastr.error("Error al buscar", "Busqueda fallida");
                });
            },
            reiniciarBusqueda() {
                this.clearFields();
                this.busqueda = false;
                this.getInformacionCita(1);
                this.getPacientesDoctor();
            }
        },
        created() {
            this.getInformacionCita(1);
            this.getPacientesDoctor();
        }
    }

    function limpiarFecha(fecha) {
        var datos = fecha.split('T');
        return datos[0];
    }

</script>
