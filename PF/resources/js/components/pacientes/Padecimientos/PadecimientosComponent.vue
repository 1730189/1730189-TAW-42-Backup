<template>
    <div class="main-panel">
        <nav-component v-bind:paginas="[ ['Pacientes', 'pacientes1'], ['Padecimientos', 'padecimientos1']]"></nav-component>

        <div class="content">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado de Padecimientos -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de padecimientos de paciente</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2">Nombre completo:</label>
                                        <input v-model="paciente.nombre + ' ' + paciente.apellidos" type="text" class="col-md-8" disabled/>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-2">Sexo:</label>
                                        <input v-if="paciente.sexo == 'masculino'" value="Masculino" type="text" class="col-md-3" disabled/>
                                        <input v-if="paciente.sexo == 'femenino'" value="Femenino" type="text" class="col-md-3" disabled/>

                                        <label class="col-md-2">Edad:</label>
                                        <input v-model="paciente.edad" type="text" class="col-md-1" disabled/>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-2">Peso:</label>
                                        <input v-model="paciente.peso" type="text" class="col-md-1" disabled/>

                                        <label class="col-md-2">Estatura:</label>
                                        <input v-model="paciente.altura" type="text" class="col-md-1" disabled/>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th> ID</th>
                                            <th> Descripción Padecimiento</th>
                                            <th> Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="padecimiento in padecimientos" :key="padecimiento.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="padecimiento.id"></td>
                                            <td v-text="padecimiento.descripcion"></td>

                                            <td align="center">
                                                <!-- EDITAR PADECIMIENTOS PACIENTE-->
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalPadecimiento"
                                                        @click="cargarPadecimiento(padecimiento)">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </button>

                                                <!--ELIMINAR PADECIMIENTOS -->
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEliminar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                                <!-- MODAL DE ELIMINAR PADECIMIENTO  -->
                                                <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog"
                                                     aria-labelledby="myModalLabel" style="display: none;"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-danger" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">
                                                                    Eliminar Padecimiento
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <a id="valor"></a>
                                                            <div class="modal-body">
                                                                <p> ¿Estas seguro de eliminar el padecimiento del
                                                                    paciente? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    Cerrar
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        @click="eliminarPadecimiento(padecimiento)"
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
            <div class="modal fade" id="modalPadecimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-if="update == 0">Registrar Padecimiento</h4>
                            <h4 class="modal-title" v-if="update != 0">Editar Padecimiento</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="clearFields('#modalPadecimiento')">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" class="form-horizontal" data-parsley-validate>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Descripción de padecimiento</label>
                                    <div class="col-md-9">
                                        <input v-model="descripcion" type="text" id="descripcion" name="descripcion"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('descripcion')}"/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('descripcion')}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="clearFields('#modalPadecimiento')">
                                Cerrar
                            </button>
                            <button type="button" v-if="update == 0" class="btn btn-success" @click="addPadecimiento()">
                                Guardar
                            </button>
                            <button type="button" v-if="update != 0" class="btn btn-success" @click="actualizarPadecimiento()">
                                Actualizar
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
    function desactivar() {
        document.getElementById('descripcion').classList.remove("has-error");
    }

    class Errors {
        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
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
                descripcion: "",
                update: 0,

                paciente: [],
                padecimientos: [],
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
            obtenerPadecimientosPaciente(page) {
                let me = this;
                let id = `${this.$route.params.id}`;
                let url = `/padecimiento/obtener/` + id + `?page=` + page;
                axios.get(url).then(function (response) {
                    me.paciente = response.data.paciente;
                    me.padecimientos = response.data.padecimientos.data;
                    me.pagination = response.data.pagination;
                    console.log(response.data.pagination);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addPadecimiento() {
                let me = this;
                me.errors = new Errors();
                let url = "/padecimiento/crear";
                axios.post(url, {
                    descripcion: this.descripcion,
                    paciente: `${this.$route.params.id}`,
                }).then(function (response) {
                    me.obtenerPadecimientosPaciente(1);
                    me.clearFields("#modalPadecimiento");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                });
            },
            actualizarPadecimiento() {
                let me = this;
                let url = "/padecimiento/actualizarPadecimiento";
                me.errors = new Errors();
                axios.patch(url, {
                    'id': this.update,
                    'descripcion': this.descripcion,
                }).then(function (response) {
                    me.obtenerPadecimientosPaciente(me.pagination.current_page);
                    me.clearFields("#modalPadecimiento");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                });
            },
            eliminarPadecimiento(data) {
                let id = data.id;
                let me = this;
                let url = "/padecimiento/eliminar/" + id;
                axios.delete(url).then(function (response) {
                    me.obtenerPadecimientosPaciente(1);
                    me.clearFields("#modalEliminar");
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cargarPadecimiento(data) { //Cargar padecimiento en el modal
                this.update = data.id;
                let me = this;
                let url = "/pacientes/buscarPadecimientoPaciente/" + this.update;
                axios.get(url).then(function (response) {
                    me.descripcion = response.data.descripcion;
                    console.log(response.data.descripcion);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.obtenerPadecimientosPaciente(page);
            },
            clearFields(modal) {
                this.descripcion = "";
                this.update = 0;
                $(modal).modal("hide");
                this.errors = new Errors();
                desactivar();
            }
        },
        mounted() {
            this.obtenerPadecimientosPaciente(1);
            this.errors = new Errors();
        }
    };
</script>
