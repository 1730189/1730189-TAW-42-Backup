<template>
    <div class="content">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <button
                                type="button"
                                class="btn btn-success"
                                data-toggle="modal"
                                data-target="#modalAlergia"
                            >
                                <i class="fa fa-plus-circle"></i>&nbsp;
                                <b>Agregar</b>
                            </button>
                            <h4 class="card-title ">Listado De Alergias</h4>
                            <p class="card-category">
                                Alergias registradas en la base de datos.
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-sm"
                                >
                                    <thead>
                                        <tr>
                                            <th>Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="alergia in alergias"
                                            :key="alergia.id"
                                        >
                                            <td>
                                                <!-- EDITAR -->
                                                <button
                                                    type="button"
                                                    class="btn btn-primary btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalAlergia"
                                                    @click="
                                                        cargarAlergia(alergia)
                                                    "
                                                >
                                                    <span class="material-icons"
                                                        >edit</span
                                                    >
                                                </button>

                                                <!--ELIMINAR -->
                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalEliminar"
                                                >
                                                    <span
                                                        class="material-icons"
                                                    >
                                                        delete_forever
                                                    </span>
                                                </button>

                                                <!-- MODAL DE ELIMINAR  -->
                                                <div
                                                    class="modal fade"
                                                    id="modalEliminar"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="myModalLabel"
                                                    style="display: none;"
                                                    aria-hidden="true"
                                                >
                                                    <div
                                                        class="modal-dialog modal-danger"
                                                        role="document"
                                                    >
                                                        <div
                                                            class="modal-content"
                                                        >
                                                            <div
                                                                class="modal-header"
                                                            >
                                                                <h4
                                                                    class="modal-title"
                                                                >
                                                                    Eliminar
                                                                    alergia
                                                                </h4>
                                                                <button
                                                                    type="button"
                                                                    class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close"
                                                                >
                                                                    <span
                                                                        aria-hidden="true"
                                                                        >×</span
                                                                    >
                                                                </button>
                                                            </div>
                                                            <a id="valor"></a>
                                                            <div
                                                                class="modal-body"
                                                            >
                                                                <p>
                                                                    ¿Estas
                                                                    seguro de
                                                                    eliminar el
                                                                    alergia?
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="modal-footer"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-secondary"
                                                                    data-dismiss="modal"
                                                                >
                                                                    Cerrar
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-danger"
                                                                    @click="
                                                                        deleteAlergia(
                                                                            alergia.id
                                                                        )
                                                                    "
                                                                    data-dismiss="modal"
                                                                >
                                                                    Eliminar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-------- VALORES DE LA TABLA-------->
                                            <td v-text="alergia.nombre"></td>
                                            <td
                                                v-text="alergia.descripcion"
                                            ></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--MODAL PARA AGREGAR Y ACTUALIZAR-->
        <div
            class="modal fade"
            id="modalAlergia"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel"
            style="display: none;"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar alergia</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="clearFields('#modalAlergia')"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form
                            action=""
                            class="form-horizontal"
                            data-parsley-validate
                        >
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"
                                    >Nombre</label
                                >
                                <div class="col-md-9">
                                    <input
                                        v-model="nombre"
                                        type="text"
                                        id="nombre"
                                        name="nombre"
                                        class="form-control"
                                        placeholder="Nombre de alergia"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"
                                    >descripcion</label
                                >
                                <div class="col-md-9">
                                    <input
                                        v-model="descripcion"
                                        type="text"
                                        id="descripcion"
                                        name="descripcion"
                                        class="form-control"
                                        placeholder="Ingrese descripcion"
                                    />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="clearFields('#modalAlergia')"
                        >
                            Cerrar
                        </button>
                        <button
                            type="button"
                            v-if="update == 0"
                            class="btn btn-primary"
                            @click="addAlergia()"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="update != 0"
                            class="btn btn-primary"
                            @click="updateAlergia()"
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!------- FINAL DEL MODAL-------------------->
    </div>
</template>

<!-------------------- FUNCIONES DE LOS BOTONES------------>
<script>
/////////////////////// Se inicializan variables
export default {
    data() {
        return {
            nombre: "",
            descripcion: "",

            update: 0,
            alergias: []
        };
    },
    ////////////////////////// Se obtienen los resultados de la tabla
    methods: {
        getAlergias() {
            let me = this;
            let url = "/alergias/obtenerAlergias";
            axios
                .get(url)
                .then(function(response) {
                    me.alergias = response.data;
                    console.log(me.alergias);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        ////////////////////////// Se elimina el alergia seleccionado
        deleteAlergia(id) {
            let me = this;
            let url = "/alergias/eliminarAlergia/" + id;
            axios
                .delete(url)
                .then(function(response) {
                    me.getAlergias();
                    $("modalEliminar").modal("hide");
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        ///////////////////// CREA EL REGISTRO DEL alergia
        addAlergia() {
            let me = this;
            let url = "/alergias/agregarAlergia";
            axios
                .post(url, {
                    nombre: this.nombre,
                    descripcion: this.descripcion
                })
                .then(function(response) {
                    me.getAlergias();
                    me.clearFields("#modalAlergia");
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        ///////////////////// ACTUALIZA EL REGISTRO DEL Alergia
        updateAlergia() {
            let me = this;
            let url = "/alergias/editarAlergia";
            axios
                .patch(url, {
                    id: this.update,
                    nombre: this.nombre,
                    descripcion: this.descripcion
                })
                .then(function(response) {
                    me.getAlergias();
                    me.clearFields("#modalAlergia");
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //////////////////////7 SE BUSCA EL Alergia SELECCIONADO
        cargarAlergia(data) {
            this.update = data.id;
            let me = this;
            let url = "/alergias/buscarAlergia/" + this.update;
            axios
                .get(url)
                .then(function(response) {
                    me.nombre = response.data.nombre;
                    me.descripcion = response.data.descripcion;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        clearFields(modal) {
            this.nombre = "";
            this.descripcion = "";

            this.update = 0;
            $(modal).modal("hide");
        }
    },
    mounted() {
        this.getAlergias();
    }
};
</script>
