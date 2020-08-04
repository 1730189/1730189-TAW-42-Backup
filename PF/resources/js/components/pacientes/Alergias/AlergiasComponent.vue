<template>
    <div class="main-panel">
        <nav-component :paginas="[ ['Pacientes', 'pacientes1'], ['Alergias', 'alergias1'] ]"></nav-component>

        <div class="content">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado de Alergias -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modalAlergia">
                            <i class="fa fa-plus-circle"></i>&nbsp;
                            <b>Registrar Alergia</b>
                        </button>
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de alergias de paciente</h4>
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
                                            <th> Descripción Alergia</th>
                                            <th> ¿Editar?</th>
                                            <th> ¿Eliminar?</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="alergia in alergias" :key="alergia.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="alergia.id"></td>
                                            <td v-text="alergia.descripcion"></td>

                                            <td align="center">
                                                <!-- EDITAR ALERGIAS PACIENTE-->
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalAlergia"
                                                        @click="cargarAlergia(alergia)">
                                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                                </button>
                                            </td>

                                            <td align="center">
                                                <!--ELIMINAR ALERGIAS -->
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEliminar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                                <!-- MODAL DE ELIMINAR ALERGIA  -->
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
                                                                <p> ¿Estas seguro de eliminar la alergia del
                                                                    paciente? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal"
                                                                        @click="clearFields('#modalEliminar')">
                                                                    Cerrar
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        @click="deleteAlergia(alergia)"
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
            <div class="modal fade" id="modalAlergia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-if="update == 0">Registrar Alergia</h4>
                            <h4 class="modal-title" v-if="update != 0">Editar Alergia</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="clearFields('#modalAlergia')">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" class="form-horizontal" data-parsley-validate>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Descripción de alergia</label>
                                    <div class="col-md-9">
                                        <input v-model="descripcion" type="text" id="descripcion" name="descripcion"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('descripcion')}"
                                               placeholder="Ej. Alergico al paracetamol"/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('descripcion')}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="clearFields('#modalAlergia')">
                                Cerrar
                            </button>
                            <button type="button" v-if="update == 0" class="btn btn-success" @click="addAlergia()">
                                Guardar
                            </button>
                            <button type="button" v-if="update != 0" class="btn btn-success" @click="updateAlergia()">
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

    export default {
        data() {
            return {
                descripcion: "",
                update: 0,

                paciente: [],
                alergias: [],
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
            getAlergiasPaciente(page) {
                let me = this;
                let id = `${this.$route.params.id}`;
                let url = `/pacientes/obtenerAlergiasPaciente/` + id + `?page=` + page;
                axios.get(url).then(function (response) {
                    me.paciente = response.data.paciente;
                    me.alergias = response.data.Alergias.data;
                    me.pagination = response.data.pagination;
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addAlergia() {
                let me = this;
                me.errors = new Errors();
                let url = "/pacientes/crearAlergiasPaciente";
                axios.post(url, {
                    descripcion: this.descripcion,
                    paciente: `${this.$route.params.id}`,
                }).then(function (response) {
                    me.getAlergiasPaciente(1);
                    me.clearFields("#modalAlergia");
                    toastr.success("Alergia registrada");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                    toastr.error("Registro erroneo de alergia");
                });
            },
            updateAlergia() {
                let me = this;
                let url = "/pacientes/actualizarAlergiasPaciente";
                me.errors = new Errors();
                axios.patch(url, {
                    id: this.update,
                    descripcion: this.descripcion,
                }).then(function (response) {
                    me.getAlergiasPaciente(me.pagination.current_page);
                    me.clearFields("#modalAlergia");
                    toastr.success("Actualizacion de alergia correcta");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                    toastr.error("Actualización de alergia erronea");
                });
            },
            deleteAlergia(data) {
                let id = data.id;
                let me = this;
                let url = "/pacientes/eliminarAlergiaPaciente/" + id;
                axios.delete(url).then(function (response) {
                    me.getAlergiasPaciente(1);
                    me.clearFields("#modalEliminar");
                    toastr.success("Eliminación correcta");
                }).catch(function (error) {
                    console.log(error);
                    toastr.error("Eliminación erronea");
                });
            },
            cargarAlergia(data) { //Cargar alergia en el modal
                this.update = data.id;
                let me = this;
                let url = "/pacientes/buscarAlergiaPaciente/" + this.update;
                axios.get(url).then(function (response) {
                    me.descripcion = response.data.descripcion;
                    console.log(response.data.descripcion);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.getAlergiasPaciente(page);
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
            this.getAlergiasPaciente(1);
            this.errors = new Errors();
        }
    };

    function desactivar() {
        document.getElementById('descripcion').classList.remove("has-error");
    }
</script>
