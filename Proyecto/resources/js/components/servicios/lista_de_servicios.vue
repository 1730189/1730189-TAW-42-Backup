<template>
    <!-- Contenido Principal -->
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <!--            <li class="breadcrumb-item">Home</li>-->
            <!--            <li class="breadcrumb-item"><a href="#">Admin</a></li>-->
            <!--            <li class="breadcrumb-item active">Dashboard</li>-->
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                   
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalServicio">
                        <i class="fa fa-plus-circle"></i>&nbsp;  <b>Agregar </b>
                    </button>


                  
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                             <th>Acciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                           
                            
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="servicio in servicios" :key="servicio.id">
                          
                            <td>
                                <!-- EDITAR -->
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalServicio" @click="cargarServicio(servicio)">
                                    <i class="icon-pencil"></i>
                                </button>

                                <!--ELIMINAR -->
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modalEliminar">
                                    <i class="icon-trash"></i>
                                </button>

                                <!-- MODAL DE ELIMINAR  -->
                                <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-danger" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Eliminar servicio</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <a id="valor"></a>
                                            <div class="modal-body">
                                                <p>¿Estas seguro de eliminar el servicio?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Cerrar
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                        @click="deleteServicio(servicio.id)" data-dismiss="modal">Eliminar
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>

                                </div>
                            </td>
                            <!-------- VALORES DE LA TABLA-------->
                            <td v-text="servicio.nombre"></td>
                            <td v-text="servicio.descripcion"></td>
                           


                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>


        <!--MODAL PARA AGREGAR Y ACTUALIZAR-->
        <div class="modal fade" id="modalServicio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar servicio</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="clearFields('#modalServicio')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form-horizontal" data-parsley-validate>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Nombre</label>
                                <div class="col-md-9">
                                    <input v-model="nombre" type="text" id="nombre" name="nombre" class="form-control"
                                           placeholder="Nombre de servicio">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">descripcion</label>
                                <div class="col-md-9">
                                    <input v-model="descripcion" type="text" id="descripcion" name="descripcion"
                                           class="form-control"
                                           placeholder="Ingrese descripcion">
                                </div>
                            </div>



                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="clearFields('#modalServicio')">Cerrar
                        </button>
                        <button type="button" v-if="update == 0" class="btn btn-primary" @click="addServicio()">
                            Guardar
                        </button>
                        <button type="button" v-if="update != 0" class="btn btn-primary" @click="updateServicio()">
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!------- FINAL DEL MODAL-------------------->

    </main>
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
                servicios: []
            }
        },
        ////////////////////////// Se obtienen los resultados de la tabla
        methods: {
            getServicios() {
                let me = this;
                let url = '/servicios/obtenerServicios';
                axios.get(url).then(function (response) {
                    me.servicios = response.data;
                    console.log(me.servicios);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            ////////////////////////// Se elimina el servicio seleccionado
            deleteServicio(id) {
                let me = this;
                let url = '/servicios/eliminarServicio/' + id;
                axios.delete(url).then(function (response) {
                    me.getServicios();
                    $('modalEliminar').modal('hide');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            ///////////////////// CREA EL REGISTRO DEL servicio
            addServicio() {
                let me = this;
                let url = '/servicios/agregarServicio';
                axios.post(url, { 
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                   
                }).then(function (response) {
                    me.getServicios();   
                    me.clearFields('#modalServicio'); 
                }).catch(function (error) {
                    console.log(error);
                });
            },
            ///////////////////// ACTUALIZA EL REGISTRO DEL Servicio
            updateServicio() {
                let me = this;
                let url = '/servicios/editarServicio';
                axios.patch(url, { 
                    'id': this.update,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    
                 
                }).then(function (response) {
                    me.getServicios();    
                    me.clearFields('#modalServicio');  
                }).catch(function (error) {
                    console.log(error);
                });
            },
            //////////////////////7 SE BUSCA EL Servicio SELECCIONADO
            cargarServicio(data) { 
                this.update = data.id
                let me = this;
                let url = '/servicios/buscarServicio/' + this.update;
                axios.get(url).then(function (response) {
                    me.nombre = response.data.nombre;
                    me.descripcion = response.data.descripcion;
                   
             
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            clearFields(modal) {
                this.nombre = "";
                this.descripcion = "";
               
                this.update = 0;
                $(modal).modal('hide');
            },
        },
        mounted() {
            this.getServicios();
        }
    }
</script>
