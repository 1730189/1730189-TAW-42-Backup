<style>
    .select-css {
        display: block;
        font-size: 16px;
        font-family: 'Arial', sans-serif;
        font-weight: 400;
        color: #444;
        line-height: 1.3;
        padding: .4em 1.4em .3em .8em;
        width: 400px;
        max-width: 100%;
        box-sizing: border-box;
        margin: 0;
        border: 1px solid #aaa;
        box-shadow: 0 1px 0 1px rgba(0, 0, 0, .03);
        border-radius: .3em;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
        linear-gradient(to bottom, #ffffff 0%, #f7f7f7 100%);
        background-repeat: no-repeat, repeat;
        background-position: right .7em top 50%, 0 0;
        background-size: .65em auto, 100%;
    }

    .select-css::-ms-expand {
        display: none;
    }

    .select-css:hover {
        border-color: #888;
    }

    .select-css:focus {
        border-color: #aaa;
        box-shadow: 0 0 1px 3px rgba(59, 153, 252, .7);
        box-shadow: 0 0 0 3px -moz-mac-focusring;
        color: #222;
        outline: none;
    }

    .select-css option {
        font-weight: normal;
    }

    #message {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
    }

    #inner-message {
        margin: 0 auto;
    }
</style>

<template>
   <div class="main-panel">
       <nav-component :paginas="[ ['Pacientes', 'pacientes1'] ]"></nav-component>
       <div class="content">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modalPaciente">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                            <b>Crear un nuevo paciente</b>
                        </button>
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div>
                                    <h4 class="card-title">Listado De Pacientes</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <tr>
                                            <th> Nombre completo</th>
                                            <th> Sexo</th>
                                            <th> Edad</th>
                                            <th> Peso</th>
                                            <th> Estatura</th>
                                            <th> Alergias</th>
                                            <th> Padecimientos</th>
                                            <th> Opciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="paciente in pacientes" :key="paciente.id">
                                            <!-------- INFORMACIÓN EN LA TABLA -------->
                                            <td v-text="paciente.nombre + ' ' + paciente.apellidos"></td>
                                            <td v-if="paciente.sexo == 'masculino'">Masculino</td>
                                            <td v-if="paciente.sexo == 'femenino'">Femenino</td>
                                            <td v-text="paciente.edad"></td>
                                            <td v-text="paciente.peso + ' kgs'"></td>
                                            <td v-text="paciente.altura + ' cms'"></td>

                                            <td>
                                                <!-- ALERGIAS DEL PACIENTE-->
                                                <router-link :to="{name: 'alergias1', params: { id: paciente.id }}"
                                                             class="btn btn-primary btn-sm">
                                                    Alergias
                                                </router-link>
                                            </td>

                                            <td>
                                                <!-- PADECIMIENTOS DEL PACIENTE-->
                                                <router-link
                                                    :to="{name: 'padecimientos1', params: { id: paciente.id }}"
                                                    class="btn btn-primary btn-sm">
                                                    Padecimientos
                                                </router-link>
                                            </td>

                                            <td>
                                                <!-- EDITAR PACIENTE-->
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#modalPaciente" @click="cargarPaciente(paciente)">
                                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                                </button>

                                                <!--ELIMINAR -->
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#modalEliminar"  @click="cargarPaciente(paciente)">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                                <!-- MODAL DE ELIMINAR  -->
                                                <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog"
                                                     aria-labelledby="myModalLabel" style="display: none;"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-danger" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">
                                                                    Eliminar paciente
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p> ¿Estas seguro de eliminar el paciente? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">
                                                                    Cerrar
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        @click="deletePaciente()"
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
            <div class="modal fade" id="modalPaciente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-if="update == 0">Agregar paciente</h4>
                            <h4 class="modal-title" v-if="update != 0">Edición de paciente</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="clearFields('#modalPaciente')">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="" class="form-horizontal" data-parsley-validate>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre</label>
                                    <div class="col-md-9">
                                        <input v-model="nombre" type="text" id="nombre" name="nombre"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('nombre')}" required/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('nombre')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Apellidos</label>
                                    <div class="col-md-9">
                                        <input v-model="apellidos" type="text" id="apellidos"
                                               name="apellidos"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('apellidos')}" required/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('apellidos')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Edad</label>
                                    <div class="col-md-9">
                                        <input v-model="edad" type="number" id="edad" name="edad"
                                               v-bind:class="{'form-control': true, 'has-error' : errors.has('edad')}" required/>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('edad')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Sexo</label>
                                    <div class="col-md-9">
                                        <select id="sexo" v-model="sexo" name="sexo"
                                                v-bind:class="{'select-css': true, 'has-error' : errors.has('sexo')}">
                                            <option disabled value="seleccionarSexo">Seleccione el sexo...</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="femenino">Femenino</option>
                                        </select>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('sexo')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="peso">Peso(kg)</label>
                                    <div class="input-group col-md-3">
                                        <div>
                                            <input v-model="peso" type="number" id="peso" name="peso"
                                                   class="form-control"
                                                   v-bind:class="{'form-control': true, 'has-error' : errors.has('peso')}" required="required"/>
                                        </div>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('peso')}}
                                        </div>
                                    </div>

                                    <label class="col-sm-3 col-form-label">Altura(cm)</label>
                                    <div class="input-group col-md-3">
                                        <div>
                                            <input v-model="altura" type="number" id="altura" name="altura"
                                                   v-bind:class="{'form-control': true, 'has-error' : errors.has('altura')}" required/>
                                        </div>
                                        <div class="invalide-feedback has-error2">
                                            {{errors.get('altura')}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="clearFields('#modalPaciente')">
                                Cerrar
                            </button>
                            <button type="submit" v-if="update == 0" class="btn btn-success" @click="addPaciente()">
                                Guardar
                            </button>
                            <button type="button" v-if="update != 0" class="btn btn-success" @click="updatePaciente()">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!------- FINAL DEL MODAL-------------------->
        </div>
        <!--  FINAL DEL MAIN      -->
    </div>
</template>

<!-------------------- FUNCIONES DE LOS BOTONES------------>
<script type="text/javascript">

    class Errors {
        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
                if (this.errors[field][0] === 'The selected sexo is invalid.') {
                    return 'Por favor seleccione el tipo de ' + field;
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
                nombre: null,
                apellidos: null,
                edad: null,
                sexo: 'seleccionarSexo',
                peso: null,
                altura: null,
                cant_alergias: null,
                cant_padecimientos: null,

                update: 0,
                pacientes: [],
                errors: new Errors(),

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
            getPacientes(page) { //Traer la informacion directa por medio de las rutas
                let me = this;
                let url = "/pacientes/obtenerPacientes?page=" + page;
                axios.get(url).then(function (response) {
                    me.pacientes = response.data.pacientes.data;
                    me.pagination = response.data.pagination;
                    // console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deletePaciente() { // Se elimina el paciente seleccionado por medio del id
                let me = this;
                let url = "/pacientes/eliminarPaciente/" + me.update;
                axios.delete(url).then(function (response) {
                    me.getPacientes();
                    me.clearFields('modalEliminar');
                }).catch(function (error) {
                    // console.log(error);
                });
            },
            addPaciente() { //Funcion para registrar nuevo paciente
                let me = this;
                let url = "/pacientes/agregarPaciente";
                axios.post(url, {
                    'nombre': me.nombre,
                    'apellidos': me.apellidos,
                    'edad': me.edad,
                    'sexo': me.sexo,
                    'peso': me.peso,
                    'altura': me.altura,
                }).then(function () {
                    me.getPacientes(1);
                    me.clearFields("#modalPaciente");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                });
            },
            updatePaciente() { //Funcion para actualizar paciente
                let me = this;
                let url = "/pacientes/editarPaciente";
                axios.patch(url, {
                    'id': me.update,
                    'nombre': me.nombre,
                    'apellidos': me.apellidos,
                    'edad': me.edad,
                    'sexo': me.sexo,
                    'peso': me.peso,
                    'altura': me.altura,
                }).then(function (response) {
                    me.getPacientes();
                    me.clearFields("#modalPaciente");
                }).catch(function (error) {
                    me.errors.record(error.response.data);
                });
            },
            cargarPaciente(data) { //Cargar paciente en el modal
                this.update = data.id;
                let me = this;
                let url = "/pacientes/buscarPaciente/" + this.update;
                axios.get(url).then(function (response) {
                    me.nombre = response.data.paciente.nombre;
                    me.apellidos = response.data.paciente.apellidos;
                    me.edad = response.data.paciente.edad;
                    me.sexo = response.data.paciente.sexo;
                    me.peso = response.data.paciente.peso;
                    me.altura = response.data.paciente.altura;

                    me.cant_alergias = response.data.alergias;
                    me.cant_padecimientos = response.data.padecimiento;
                }).catch(function (error) {
                    // console.log(error);
                });
            },
            clearFields(modal) {
                this.nombre = null;
                this.apellidos = null;
                this.edad = null;
                this.sexo = 'seleccionarSexo';
                this.peso = null;
                this.altura = null;

                this.errors = new Errors();
                this.update = 0;
                $(modal).modal("hide");
            },
            moverAlergia(data) {
                this.update = data.id;
                let me = this;
                let url = "/pacientes/buscarPaciente/" + this.update;
                axios.get(url).then(function (response) {
                    me.nombre = response.data.nombre;
                    me.apellidoP = response.data.apellidoPaterno;
                    me.apellidoM = response.data.apellidoMaterno;
                    me.edad = response.data.edad;
                    me.sexo = response.data.sexo;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.getPacientes(page);
            }
        },
        mounted() {
            this.getPacientes(1);
        }
    };

    function desactivar() {
        document.getElementById('nombre').classList.remove("has-error");
        document.getElementById('apellidos').classList.remove("has-error");
        document.getElementById('edad').classList.remove("has-error");
        document.getElementById('sexo').classList.remove("has-error");
        document.getElementById('peso').classList.remove("has-error");
        document.getElementById('altura').classList.remove("has-error");
    }
</script>
