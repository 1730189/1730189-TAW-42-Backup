<link rel='stylesheet' href='resources/assets/css/fullcalendar.min.css'/>

<style>
    .disponible {
        color: green;
    }

    .ocupado {
        color: red;
    }
</style>

<template>
    <div class="main-panel">
        <nav-component v-bind:paginas="[ ['Citas', 'citas2'] ]"></nav-component>
        <div class="content">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado de Alergias -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modalCita">
                            <i class="fa fa-plus-circle"></i>&nbsp;
                            <b>Agendar nueva cita</b>
                        </button>
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de todas las citas</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th> Fecha Cita</th>
                                            <th> Hora Cita</th>
                                            <th> Asunto</th>
                                            <th> Fecha Registro</th>
                                            <th> Paciente</th>
                                            <th> Doctor</th>
                                            <th> Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="cita in citas" :key="cita.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="cita.fecha_cita"></td>
                                            <td v-text="cita.hora_cita"></td>
                                            <td v-text="cita.asunto"></td>
                                            <td v-text="cita.fecha_creacion"></td>
                                            <td v-text="cita.paciente"></td>
                                            <td v-text="cita.doctor"></td>

                                            <td v-for="consulta in consultada" :key="consulta.id">
                                                <span v-if="cita.id === consulta.id">Consultada</span>
                                                <span v-else="">No Consultada</span>
                                            </td>

                                            <td align="center">
                                                <!-- EDITAR CITA-->
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
                                                                <p> ¿Estas seguro de eliminar la cita permanentemente? </p>
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
                                        <label class="col-sm-3 col-form-label">Doctor para cita</label>
                                        <div class="col-md-9">
                                            <select id="id_doctor" v-model="id_doctor" name="id_doctor"
                                                    v-bind:class="{'select-css': true, 'has-error' : errors.has('id_doctor')}">
                                                <option disabled value="seleccioneDoctor">Seleccione el doctor
                                                </option>
                                                <option v-for="doctor in doctores" v-bind:value="doctor.id"
                                                        v-text="doctor.nombre + ' ' + doctor.apellidoPaterno + ' ' + doctor.apellidoMaterno"></option>
                                            </select>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('id_doctor')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Asunto de cita</label>
                                        <div class="col-md-9">
                                            <input v-model="asunto" type="text" id="asunto" name="asunto"
                                                   v-bind:class="{'form-control': true, 'has-error' : errors.has('asunto')}"
                                                   placeholder="Ej. Cita normal"/>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('asunto')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Paciente</label>
                                        <div class="col-md-9">
                                            <select id="id_paciente" v-model="id_paciente" name="id_paciente"
                                                    v-bind:class="{'select-css': true, 'has-error' : errors.has('id_paciente')}">
                                                <option disabled value="seleccionarPaciente">Seleccione el paciente
                                                </option>
                                                <option v-for="paciente in pacientes" v-bind:value="paciente.id"
                                                        v-text="paciente.nombre + ' ' + paciente.apellidoPaterno + ' ' + paciente.apellidoMaterno"></option>
                                            </select>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('id_paciente')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fecha de cita</label>
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

                                        <label class="col-sm-2 col-form-label">Hora de la cita</label>
                                        <div class="col-md-3">
                                            <select id="hora_cita" v-model="hora_cita" name="hora_cita"
                                                    :disabled="cargarHoras()"
                                                    v-bind:class="{'select-css': true, 'has-error' : errors.has('hora_cita')}">
                                                <option disabled selected value="seleccionarHora">Seleccione hora disponible
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

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Precio</label>
                                        <div class="col-md-9">
                                            <input v-model="precio" type="text" id="precio" name="precio"
                                                   v-bind:class="{'form-control': true, 'has-error' : errors.has('precio')}"
                                                   placeholder="Ej. 85.00"/>
                                            <div class="invalide-feedback has-error2">
                                                {{errors.get('precio')}}
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

                <!--  MODAL AGREGAR / EDITAR CITA  -->
                <div class="modal fade" id="modalBusquedaCita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Búsqueda de Cita</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        @click="clearFields('#modalBusquedaCita')">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" class="form-horizontal" data-parsley-validate>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Doctor</label>
                                        <div class="col-md-9">
                                            <select id="id_doctorBusqueda" v-model="id_doctor" name="id_doctorBusqueda" class="select-css">
                                                <option disabled selected value="seleccioneDoctor">Seleccione al doctor
                                                </option>
                                                <option v-for="doctor in doctores" v-bind:value="doctor.id"
                                                        v-text="doctor.nombre + ' ' + doctor.apellidoPaterno + ' ' + doctor.apellidoMaterno"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Asunto de cita</label>
                                        <div class="col-md-9">
                                            <input v-model="asunto" type="text" id="asuntoBusqueda" name="asunto"
                                                   placeholder="Ej. Revision Rayos X"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Paciente</label>
                                        <div class="col-md-9">
                                            <select id="id_pacienteBusqueda" v-model="id_paciente" name="id_pacienteBusqueda" class="select-css">
                                                <option disabled selected value="seleccionarPaciente">Seleccione el paciente
                                                </option>
                                                <option v-for="paciente in pacientes" v-bind:value="paciente.id"
                                                        v-text="paciente.nombre + ' ' + paciente.apellidoPaterno + ' ' + paciente.apellidoMaterno"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fecha de cita</label>
                                        <div class="col-md-3">
                                            <datetime type="date" v-model="fecha_citaB" id="fecha_citaBusqueda"
                                                      format="yyyy-MM-dd" value-zone="America/Mexico_City"
                                                      zone="America/Mexico_City" :min-datetime="fecha_actual">
                                                <template slot="button-cancel">
                                                    <p>Cancelar</p>
                                                </template>
                                                <template slot="button-confirm">
                                                    <p>Aceptar</p>
                                                </template>
                                            </datetime>
                                        </div>

                                        <label class="col-sm-3 col-form-label">Fecha de Final</label>
                                        <div class="col-md-3">
                                            <datetime type="date" v-model="fecha_final" id="fecha_finalBusqueda"
                                                      format="yyyy-MM-dd" value-zone="America/Mexico_City"
                                                      zone="America/Mexico_City" :min-datetime="fecha_citaB" :disabled="fecha_citaB == ''">
                                                <template slot="button-cancel">
                                                    <p>Cancelar</p>
                                                </template>
                                                <template slot="button-confirm">
                                                    <p>Aceptar</p>
                                                </template>
                                            </datetime>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" @click="clearFields('#modalBusquedaCita')">
                                    Cerrar
                                </button>
                                <button type="button" class="btn btn-success" @click="busquedaCita(1)">
                                    Empezar Búsqueda
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!------- FINAL DEL MODAL BUSCAR-------------------->

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
                doctores: [],

                horas_disponibles: [],

                fecha_actual: "",
                hora_actual: "",
                fecha_citaB: "",
                fecha_final: "",
                busqueda: false,

                //Datos de la cita
                id_paciente: "seleccionarPaciente",
                asunto: "",
                id_doctor: "seleccioneDoctor",
                fecha_cita: '',
                update: 0,
                hora_cita: "",
                precio: 60,
                errors: new Errors(),

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
            getInformacionCitaConsultorio(page) {
                let me = this;
                let url = '/citas/obtenerCitasConsultorio?page=' + page;

                axios.get(url).then(function (response) {
                    me.citas = response.data.citas.data;
                    me.consultada = response.data.citasConsultadas;
                    me.pagination = response.data.pagination;
                    me.fecha_actual = response.data.fechaActual;
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDoctoresConsultorio() {
                let me = this;
                let url = '/citas/obtenerDoctores';
                axios.get(url).then(function (response) {
                    me.doctores = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getPacientesDoctor() {
                let me = this;
                let url = "/citas/obtenerAllPacientes/";
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
                let url = '/citas/crearCitaSecretaria';

                axios.post(url, {
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_cita),
                    'hora_cita': me.hora_cita,
                    'id_paciente': me.id_paciente,
                    'id_doctor': me.id_doctor,
                }).then(function (response) {
                    me.getInformacionCitaConsultorio(1);
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
                let url = '/citas/editarCitaSecretaria';
                axios.patch(url, {
                    'id': me.update,
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_cita),
                    'hora_cita': me.hora_cita,
                    'id_paciente': me.id_paciente,
                    'id_doctor': me.id_doctor,
                    'precio': me.precio,
                }).then(function (response) {
                    me.getInformacionCitaConsultorio(1);
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
                let url = '/citas/eliminar/' + cita_id;
                axios.delete(url).then(function (response) {
                    me.getInformacionCitaConsultorio(1);
                    me.getPacientesDoctor();
                    me.getDoctoresConsultorio();
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
                    me.id_doctor = response.data.id_medico_asignado;
                    me.hora_cita = response.data.hora_cita;
                    me.precio = response.data.precio;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cargarHoras() {
                let me = this;
                if(me.fecha_cita == ''){
                    return true;
                }
                let url = '/citas/horasDisponibles/' + limpiarFecha(me.fecha_cita) + '/' + me.id_doctor;
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
                me.id_paciente = "seleccionarPaciente";
                me.asunto = "";
                me.fecha_cita = "";
                me.update = 0;
                me.id_doctor = "seleccioneDoctor";
                me.hora_cita = "";
                me.precio = 60;
                me.fecha_final = "";
                me.fecha_citaB = "";
                me.horas_disponibles = [];
                $(modal).modal("hide");
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                if(this.busqueda){
                    this.busquedaCita(page);
                }else{
                    this.getInformacionCitaConsultorio(page);
                }
            },
            busquedaCita(page){
                let me = this;
                let url = '/citas/buscarCitaConsultorio?page=' + page;

                axios.post(url, {
                    'asunto': me.asunto,
                    'fecha_cita': limpiarFecha(me.fecha_citaB),
                    'fecha_final': limpiarFecha(me.fecha_final),
                    'id_paciente': me.id_paciente,
                    'id_doctor': me.id_doctor
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
            reiniciarBusqueda(){
                this.clearFields();
                this.busqueda = false;
                this.getInformacionCitaConsultorio(1);
                this.getPacientesDoctor();
            }
        },
        created() {
            this.getInformacionCitaConsultorio(1);
            this.getPacientesDoctor();
            this.getDoctoresConsultorio();
        }
    }

    function limpiarFecha(fecha){
        var datos = fecha.split('T');
        return datos[0];
    }

</script>
