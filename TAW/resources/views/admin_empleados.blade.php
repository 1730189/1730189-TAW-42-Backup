@extends('layout.patron');
@section ('titulo', 'Administracion de Empleados');
@section ('contenido');
	<!-- Codigo HTML puro para mostrar el listado de empleados -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page_title">
				<div class="title_left">
					<h3>Administracion de Empleados </h3>
				</div>
			</div>
		</div>
		<div class="clearfix">
			<div class="col-md-12 col-sm-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Listado de Empleados</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">
									<table id="datatable-keytable" class="table table-striped table-bordered" style="width: 100%">
										<thead>
											<tr>
												<th>Nombres</th>
												<th>Apellidos</th>
												<th>Cedula</th>
												<th>Email</th>
												<th>Lugar de Nacimiento</th>
												<th>Sexo</th>
												<th>Estado Civil</th>
												<th>Telefono</th>
											</tr>
										</thead>
										<tbody>
											@foreach($empleado as $empleados)
											<tr>
												_create_empleados_table
												<td>{{empleado->nombre}}</td>
												<td>{{empleado->apellidos}}</td>
												<td>{{empleado->cedula}}</td>
												<td>{{empleado->email}}</td>
												<td>{{empleado->lugar_nacimiento}}</td>
												<td>{{empleado->sexo}}</td>
												<td>{{empleado->estado_civil}}</td>
												<td>{{empleado->telefono}}</td>
												<!--Agregar columna para editar y eliminar registro-->
												<td>
													<div style="display: flex;">
														<a href="{{url('empleados/'.$empleado->$id.'/edit')}}" class="btn btn_secondary"><i class="fas fa_edit"></i></a>
														<form action="{{url('empleados/'.$empleado->id)}}" method="POST">
															{{csrf_field()}}
															{{method_field('DELETE')}}
															<button class="btn btn_danger"> <i class="fas fa-trash"></i></button>
														</form>
													</div>
												</td>
											</tr>
											@endforeach
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
@endsection
