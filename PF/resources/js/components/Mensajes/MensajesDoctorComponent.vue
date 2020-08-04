<style>

</style>

<template>
    <div class="main-panel">
        <nav-component :paginas="[ ['Mensajes', 'mensajesDoctor'] ]"></nav-component>

        <div class="content">
            <div class="container-fluid">
                <!-- Listado de mensajes -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modalMensaje" @click="getAsociados()">
                            <i class="fa fa-plus-circle"></i>&nbsp;
                            <b>Registrar nuevo mensaje para asociado</b>
                        </button>
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de mensajes</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th> Ver</th>
                                            <th> Asunto</th>
                                            <th> Doctor asociado</th>
                                            <th> Respuesta</th>
                                            <th> Eliminar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="revision in revisiones" :key="revision.id">
                                            <!-- Boton para ver los mensajes -->
                                            <td align="center">
                                                <!-- Ver mansajes-->
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalConversacion"
                                                        @click="cargarMensajes(revision)">
                                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                                </button>
                                            </td>

                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="revision.asunto"></td>
                                            <td v-text="revision.nombre + ' ' + revision.apellidos"></td>

                                            <td v-if="revision.respuesta === null">Sin respuesta</td>
                                            <td v-else="" v-model="revision.respuesta"></td>

                                            <td align="center">
                                                <!--ELIMINAR MENSAJE -->
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEliminar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                                <!-- MODAL DE ELIMINAR REVISION  -->
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
                                                                <p> ¿Está seguro de eliminar la conversación para las 2
                                                                    personas? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    Cerrar
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        @click="deleteRevision(revision)"
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
                                                v-bind:class="[ page == isActived ? 'active' : '']">
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

            <!--MODAL PARA AGREGAR Y ACTUALIZAR-->
            <div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear nuevo mensaje</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="clearFields('#modalMensaje')">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" class="form-horizontal" data-parsley-validate>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Asunto del mensaje:</label>
                                    <div class="col-md-9">
                                        <input v-model="asunto" type="text" id="asunto" name="asunto"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('asunto')}"/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('asunto')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Doctor Asociado</label>
                                    <div class="col-md-9">
                                        <select id="id_asociado" v-model="id_asociado" name="id_asociado"
                                                v-bind:class="{'select-css': true, 'has-error' : errors.has('doctor_asociado')}">
                                            <option disabled selected value="seleccionarAsociado">Seleccione doctor
                                                asociado
                                            </option>
                                            <option v-for="asociado in asociados" v-bind:value="asociado.id"
                                                    v-text="asociado.nombre + ' ' + asociado.apellidos"></option>
                                        </select>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('doctor_asociado')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Mensaje:</label>
                                    <div class="col-md-9">
                                        <input v-model="mensaje" type="text" id="mensaje" name="mensaje"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('mensaje')}"/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('mensaje')}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="clearFields('#modalMensaje')">
                                Cerrar
                            </button>
                            <button type="button" class="btn btn-success" @click="addRevision()">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!------- FINAL DEL MODAL-------------------->

            <!--MODAL de vista de conversaciones -->
            <div class="modal fade" id="modalConversacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="revisar.nombre_asociado"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="clearFields('#modalConversacion')">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Asunto del mensaje</label>
                                <div class="col-md-9">
                                    <input v-model="mensajes.asunto" class="col-md-9" type="text" name="asunto"
                                           disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Doctor Asociados</label>
                                <input v-model="mensajes.nombre + ' ' + mensajes.apellidos" class="col-md-9"
                                       type="text" name="nombre" disabled>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Mensaje:</label>
                                <div class="col-md-9">
                                    <input v-model="mensajes.mensaje" class="col-md-9" type="text" name="mensaje"
                                           disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Respuesta:</label>
                                <div class="col-md-9">
                                    <input v-model="mensajes.respuesta" class="col-md-9" type="text" name="mensaje"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="clearFields('#modalConversacion')">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!------- FINAL DEL MODAL-------------------->
        </div>
    </div>
</template>

<!-------------------- FUNCIONES DE LOS BOTONES------------>
<script>
    class Errors {
        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
                if (this.errors[field][0] === 'The selected doctor asociado is invalid.') {
                    return 'Por favor seleccione al doctor asociado';
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

    /////////////////////// Se inicializan variables
    export default {
        data() {
            return {
                asunto: "",
                no_leidos: "",

                id_doctor: "",
                id_asociado: "seleccionarAsociado",
                id_revision: 0,
                //Para realizacion de mensajes
                mensaje: '',

                mensajes: [],
                asociados: [],
                revisiones: [],
                revisar: [],
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
                offset: 3
            };
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
            getRevisiones(page) {
                let me = this;
                let url = `/mensajesDoctor/obtenerMensajes/?page=` + page;
                axios.get(url).then(function (response) {
                    me.revisiones = response.data.mensajes.data;
                    me.pagination = response.data.pagination;
                    me.getAsociados();
                    // console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getAsociados() {
                let me = this;
                let url = '/usuarios/obtenerAsociados/';
                axios.get(url).then(function (response) {
                    me.asociados = response.data;
                    // console.log(response.data.paciente);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addRevision() {
                let me = this;
                me.errors = new Errors();
                let url = "/mensajesDoctor/crearMensaje";
                axios.post(url, {
                    'asunto': me.asunto,
                    'doctor_asociado': me.id_asociado,
                    'mensaje': me.mensaje,
                }).then(function (response) {
                    me.getRevisiones(1);
                    me.clearFields("#modalMensaje");
                    toastr.success("Se ha enviado correctamente el mensaje", "Mensaje enviado");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                    toastr.error("No se ha enviado el mensaje", "Mensaje no enviado");
                });
            },
            deleteRevision(data) {
                let id = data.id;
                let me = this;
                let url = "/mensajesDoctor/borrarMensajes/" + id;
                axios.delete(url).then(function (response) {
                    me.getRevisiones(1);
                    me.clearFields("#modalEliminar");
                    toastr.success("Se ha eliminado la conversación", "Eliminación correcta");
                }).catch(function (error) {
                    console.log(error);
                    toastr.error("No se ha podido eliminar la conversación, intente de nuevo por favor.", "Eliminación erronea");
                });
            },
            cargarMensajes(data) { //Cargar los mensajes de la revision
                this.revisar = data;
                let me = this;
                let url = "/usuarios/obtenerRevision/" + me.revisar.id;
                axios.get(url).then(function (response) {
                    me.mensajes = response.data[0];
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.getAlergiasPaciente(page);
            },
            clearFields(modal) {
                this.asunto = "";
                this.no_leidos = "";
                this.usuario = "";
                this.usuario2 = "";

                this.id_doctor = "";
                this.id_asociado = "seleccionarAsociado";
                //Para realizacion de mensajes
                this.mensaje = '';

                this.mensajes = [];
                this.asociados = [];
                this.revisiones = [];
                this.revisar = [];

                $(modal).modal("hide");
                this.errors = new Errors();
                this.getRevisiones(this.pagination.current_page);
            }
        },
        mounted() {
            this.getRevisiones(1);
            this.errors = new Errors();
        }
    };
</script>
