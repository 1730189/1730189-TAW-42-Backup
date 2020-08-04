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
        <nav-component v-bind:paginas="[ ['Consultas', 'consulta1'], ['Consultar', 'addConsulta'] ]"></nav-component>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Listado de padecimientos de paciente</h4>
                                <button type="button" class="btn btn-info btn-sm"
                                        @click="guardarConsulta()">
                                    Registrar Consulta
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <label class="col-md-2">Num Cita:</label>
                                        <input v-model="informacionCita.id" type="text" class="col-md-1" disabled/>
                                        <label class="col-md-2">Fecha Cita:</label>
                                        <input v-model="informacionCita.fecha_cita" type="text" class="col-md-2" disabled/>
                                        <label class="col-md-2">Hora Cita:</label>
                                        <input v-model="informacionCita.hora_cita" type="text" class="col-md-2" disabled/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <label class="col-md-3">Asunto de la cita:</label>
                                        <input v-model="informacionCita.asunto" type="text" class="col-md-8" disabled/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <label class="col-md-3">Nombre Paciente:</label>
                                        <input v-model="paciente.nombre + ' ' + paciente.apellidos" type="text" class="col-md-8" disabled/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <label class="col-md-3">Servicio ofrecido:</label>
                                        <input v-model="nombre_servicio" type="text" class="col-md-8"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <label class="col-md-3">Precio consulta:</label>
                                        <input v-model="precio_input" type="number" class="col-md-8"/>
                                    </div>
                                </div>
                                <div class="row" v-if="update === -1">
                                    <div class="col-md-11">
                                        <label class="col-md-3">Descripción de padecimiento</label>
                                        <input v-model="padecimiento_input" type="text" name="descripcion"
                                               placeholder="Ej. Alergico al paracetamol" class="col-md-8"/>
                                        <button class="btn btn-success btn-just-icon" @click="addPadecimiento()">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-if="update !== -1">
                                    <div class="col-md-12">
                                        <label class="col-md-3">Descripción de padecimiento</label>
                                        <input v-model="padecimiento_input" type="text" name="descripcion"
                                               placeholder="Ej. Alergico al paracetamol" class="col-md-8"/>
                                        <button class="btn btn-success btn-just-icon" @click="editarPadecimiento()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-warning btn-just-icon" @click="cancelarActualizacion()">
                                            <span class="material-icons">clear</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Padecimientos de paciente</h4>
                                <button type="button" class="btn btn-info btn-sm"
                                        @click="guardarConsulta()">
                                    Registrar Consulta
                                </button>
                            </div>
                            <div class="card-body">
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
                                            <td v-text="(padecimiento.numero + 1)"></td>
                                            <td v-text="padecimiento.padecimiento"></td>

                                            <td align="center">
                                                <!-- EDITAR PADECIMIENTOS PACIENTE-->
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                        @click="cargarPadecimiento(padecimiento)">
                                                    <span class="material-icons">edit</span>
                                                </button>

                                                <!--ELIMINAR PADECIMIENTOS -->
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        @click="eliminarPadecimiento(padecimiento)">
                                                        <span class="material-icons">
                                                            delete_forever
                                                        </span>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
        props: ['cita'],

        data() {
            return {
                padecimiento_input: "",
                precio_input: 0,
                nombre_servicio: "Consulta regular",

                informacionCita: [],
                nuevosPadecimientos: [],
                citas: [],
                paciente: [],

                cantPadecimientos: 0,
                update: -1,
                padecimientos: [],

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
            getInformacionCita() {
                let me = this;
                let url = '/citas/buscarCita/' + `${this.$route.params.slug}`;
                axios.get(url).then(function (response) {
                    me.informacionCita = response.data;
                    console.log(response.data);
                    me.getPaciente();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getPaciente(){
                let me = this;
                let url = "/pacientes/buscarPaciente/" + me.informacionCita.id_paciente;
                axios.get(url).then(function (response) {
                    me.paciente = response.data.paciente;
                }).catch(function (error) {
                    // console.log(error);
                });
            },
            guardarConsulta() {
                let me = this;

                if(me.nombre_servicio != "" && me.nombre_servicio != null){
                    if(me.precio_input != "" && me.precio_input != 0){
                        let url = '/consultas/crearConsulta';
                        axios.post(url, {
                            'servicio': me.nombre_servicio,
                            'id_cita': me.informacionCita.id,
                            'precio': me.precio_input
                        }).then(function (response) {
                            toastr.success('Consulta creada correctamente.', 'Consulta creada');
                        }).catch(function (error) {
                            return toastr.error('No se pudo generar la consulta.', 'Creación fallida');
                        });

                        //Guardar los Padecimientos para el paciente
                        if(me.padecimientos.length != 0){
                            let url = "/Pacientes/crearPadecimientosPaciente";
                            for(let i = 0; i < me.padecimientos.length; i++){
                                axios.post(url, {
                                    'descripcion':  this.padecimientos[i].padecimiento,
                                    'paciente': this.informacionCita.id_paciente ,
                                }).then(function (response) {
                                    window.location.href = '/consultas';
                                }).catch(function (error) {
                                    console.log(error);
                                });
                            }
                        }
                    }else{
                        return toastr.error('No puedes dejar vacio el precio del servicio', 'Error al registrar consulta');
                    }
                }else{
                    return toastr.error('No puedes dejar vacio el nombre del servicio', 'Error al registrar consulta');
                }

            },
            addPadecimiento() {
                let me = this;
                if (me.padecimiento_input == "") {
                    return toastr.error("No se puede registrar un padecimiento vacío", "Error en el padecimiento");
                }
                me.padecimientos.push({
                    "numero": (me.cantPadecimientos),
                    "padecimiento": me.padecimiento_input
                });
                me.padecimiento_input = "";
                me.cantPadecimientos++;
                return toastr.success("Padecimiento en lista correctamente.", "Agregado correctamente");
            },
            editarPadecimiento() {
                let me = this;
                if (me.padecimiento_input == "") {
                    return toastr.error("No se puede actualizar a un padecimiento vacío", "Error en el padecimiento");
                }
                me.padecimientos[me.update].padecimiento = me.padecimiento_input;
                me.update = -1;
                me.padecimiento_input = "";
                return toastr.success("Padecimiento en lista correctamente.", "Agregado correctamente");
            },
            cargarPadecimiento(data) {
                let me = this;
                me.update = data.numero;
                me.padecimiento_input = data.padecimiento;
            },
            cancelarActualizacion() {
                let me = this;
                me.update = -1;
                me.padecimiento_input = "";
            },
            eliminarPadecimiento(data) {
                let me = this;
                let nuevos = [];
                let contador = 0;
                for (let i = 0; i < me.padecimientos.length; i++) {
                    if (me.padecimientos[i].numero != data.numero) {
                        nuevos.push({
                            "numero": contador,
                            "padecimiento": me.padecimientos[i].padecimiento
                        });
                        contador++;
                    }
                }
                me.padecimientos = nuevos;
                me.cantPadecimientos--;
                console.log(me.padecimientos);

                return toastr.success("El padecimiento se ha eliminado correctamente", "Eliminacion correcta");
            },
            clearFields() {
                let me = this;
                me.update = -1;
                me.cantPadecimientos = 0;
                me.padecimiento_input = "";
                me.precio_input = 0;
                me.nombre_servicio = "Consulta regular";
            },
            cambiarPagina(page) {
                this.pagination.current_page = page;
                this.getInformacionCitaDia(page);
            },
            limpiarFecha(fecha) {
                let datos = fecha.split('T');
                return datos[0];
            }
        },
        created() {
            this.getInformacionCita();
        }
    }
</script>
