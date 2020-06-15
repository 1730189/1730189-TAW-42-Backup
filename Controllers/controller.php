<?php

class MvcController{

	public static function plantilla(){
		include "views/template.php";
	}

	public static function enlacesPaginasController(){
		if (isset($_GET['action'])) {
			$enlaces = $_GET['action'];
		} else {
			$enlaces = 'index';
		}
		//Es el momento en que el controlador invoca el modelo enlacesPaginaModel para que muestre el listado de paginas
		$respuesta = Paginas::enlacesPaginasModel($enlaces);
		include $respuesta;
	}

	public static function registroUsuarioController(){
		if (isset($_POST['usuarioRegistro'])) {
			// Recibe a través del método post el name (html) de usuario, password y email, se almacenan los datos en una variable o propiedad de tipo array asociativo con sus respectivas propiedades (usuario, password, email)

			$datosController = array("nusuario" => $_POST["nusuariotxt"],
									"ausuario" => $_POST["ausuariotxt"],
									"usuario" => $_POST["usuariotxt"],
									"contra" => $_POST["ncontratxt"],
									"email" => $_POST["nemailtxt"]);

			//Se le dice al modelo models/crud.php (Datos::registroUsuariosModel), que en modelo Datos el método registroUsuariosModel reciba en sus parametros los valores de $datosController y el nombre de la tabla de la base de datos a la cual debe de conectarse (usuarios)
			$respuesta = Datos::registroUsuariosModel($datosController, "users");

			if ($respuesta == "success") {
				header("location:index.php?action=ok");
			} else {
				header("location:index.php");
			}
		}
	}

	public static function ingresoUsuarioController(){
		if (isset($_POST["txtUser"]) && isset($_POST["txtPassword"])) {
			$datosController = array("user"=>$_POST["txtUser"], "password"=>$_POST["txtPassword"]);
			$respuesta = Datos::ingresoUserModel($datosController, "users");
			//Validar la respuesta modelo para ver si el usuario es correcto
			//if ($respuesta["usuario"] == $_POST["txtUser"] && password_verify($_POST["txtPassword"], $respuesta["contrasena"])) {
			if($respuesta["usuario"]==$_POST["txtUser"] && $respuesta["contrasena"]==$_POST["txtPassword"]){
				session_start();
				$_SESSION["validar"] = true;
				$_SESSION["nombre_usuario"] = $respuesta["nombre_usuario"];
				$_SESSION["id"] = $respuesta["id"];
				header("location:index.php?action=tablero");
			} else {
				header("location:index.php?action=fallo&res=fallo");
			}
		}
	}

	public static function vistaUserController(){
		$respuesta = Datos::vistaUserModel("users");
		foreach ($respuesta as $row => $item) {
			echo '
				<tr>
					<td>
						<a href="index.php?action=usuarios&idUserEditar='.$item["id"].'" class="btn btn-warning btn-sm btn-icon" title="Editar" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
					</td>
					<td>
						<a href="index.php?action=usuarios&idBorrar='.$item["id"].'" class="btn btn-warning btn-sm btn-icon" title="Elimianr" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
					</td>
					<td>'.$item["firstname"].'</td>
					<td>'.$item["lastname"].'</td>
					<td>'.$item["user_name"].'</td>
					<td>'.$item["user_email"].'</td>
					<td>'.$item["date_added"].'</td>
				</tr>
			';
		}
	}

	public static function registrarUserController(){
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-primary">
				<div class="card card-primary">
					<div class="card-header">
						<h4><b>Registro</b> de usuarios</h4>
					</div>
					<div class="card-body">
						<form method="POST" action="index.php?action=usuarios">
							<div class="form-group">
								<label for="nusuariotxt">Nombre: </label>
								<input class="form-control" type="text" name="nusuariotxt" id="nusuariotxt" placeholder="Ingrese el nombre" required>
							</div>
							<div class="form-group">
								<label for="ausuariotxt">Apellido: </label>
								<input class="form-control" type="text" name="ausuariotxt" id="ausuariotxt" placeholder="Ingrese el apellido" required>
							</div>
							<div class="form-group">
								<label for="usuariostxt">Usuario: </label>
								<input class="form-control" type="text" name="usuariotxt" id="usuariotxt" placeholder="Ingrese el usuario" required>
							</div>
							<div class="form-group">
								<label for="ucontratxt">Contraseña: </label>
								<input class="form-control" type="password" name="ucontratxt" id="ucontratxt" placeholder="Ingrese la contraseña" required>
							</div>
							<div class="form-group">
								<label for="uemailtxt">Correo electrónico: </label>
								<input class="form-control" type="email" name="uemailtxt" id="uemailtxt" placeholder="Ingrese el correo" required>
							</div>
							<button class="btn btn-primary" type="submit">Enviar</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function insertarUserController(){
		if (isset($_POST["nusuariotxt"])) {
			//Encriptar la contraseña
			$_POST["ucontratxt"] = password_hash($_POST["ucontratxt"], PASSWORD_DEFAULT);

			//Almacenar en un array los valores de los text del método "registrarUserController"
			$datosController = array("nusuario"=>$_POST["nusuariotxt"], "ausuario"=>$_POST["ausuariotxt"], "usuario"=>$_POST["usuariotxt"], "contra"=>$_POST["ucontratxt"],"email"=>$_POST["uemailtxt"]);

			//Se envía los datos al modelo
			$respuesta = Datos::insertarUserModel($datosController, "users");

			if ($respuesta == "success") {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-succes alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-check"></i>
							Exito
						</h5>
						Usuario agregado con exito.
					</div>
				</div>
				';
			} else {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-danger alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-ban"></i>
							Error!
						</h5>
						Se ha producido un error al momento de agregar al usuario, trate de nuevo
					</div>
				</div>
				';
			}
		}
	}

	public static function editarUserController() {
		$datosController = $_GET["idUserEditar"];
		//envío de datos al mododelo
		$respuesta = Datos::editarUserModel($datosController,"users");
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-warning">
				<div class="card-header">
					<h4><b>Editor</b> de Usuarios</h4>
				</div>
				<div class="card-body">
					<form method="post" action="index.php?action=usuarios">
						<div class="form-group">
							<input type="hidden" name="idUserEditar" class="form-control" value="<?php echo $respuesta["id"]; ?>" required>
						</div>
						<div class="form-group">
							<label for="nusuariotxtEditar">Nombre: </label>
							<input class="form-control" type="text" name="nusuariotxtEditar" id="nusuariotxtEditar" placeholder="Ingrese el nuevo nombre" value="<?php echo $respuesta["nusuario"]; ?>" required>
						</div>
						<div class="form-group">
							<label for="ausuariotxtEditar">Apellido: </label>
							<input class="form-control" type="text" name="ausuariotxtEditar" id="ausuariotxtEditar" placeholder="Ingrese el nuevo apellido" value="<?php echo $respuesta["ausuario"]; ?>" required>
						</div>
						<div class="form-group">
							<label for="usuariotxtEditar">Usuario: </label>
							<input class="form-control" type="text" name="usuariotxtEditar" id="usuariotxtEditar" placeholder="Ingrese el nuevo usuario" value="<?php echo $respuesta["usuario"]; ?>" required>
						</div>
						<div class="form-group">
							<label for="contratxtEditar">Contraseña: </label>
							<input class="form-control" type="password" name="contratxtEditar" id="contratxtEditar" placeholder="Ingrese la nueva contraseña" required>
						</div>
						<div class="form-group">
							<label for="uemailtxtEditar">Correo Electrónico: </label>
							<input class="form-control" type="email" name="uemailtxtEditar" id="uemailtxtEditar" placeholder="Ingrese el nuevo correo electrónico" value="<?php echo $respuesta["email"]; ?>" required>
						</div>
						<button class="btn btn-primary" type="submit">Editar</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	public static function actualizarUserController() {
		if (isset($_POST["nusuariotxtEditar"])) {
			$_POST["contratxtEditar"] = password_hash($_POST["contratxtEditar"], PASSWORD_DEFAULT);

			$datosController = array(
				"id" => $_POST["idUserEditar"],
				"nusuario" => $_POST["nusuariotxtEditar"],
				"ausuario" => $_POST["ausuariotxtEditar"],
				"usuario" =>$_POST["usuariotxtEditar"],
				"contra" => $_POST["contratxtEditar"],
				"email" => $_POST["uemailtxtEditar"]);

			$respuesta = Datos::actualizarUserModel($datosController,"users");

			if ($respuesta == "success") {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-succes alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-check"></i>
							Exito
						</h5>
						Usuario editado con exito.
					</div>
				</div>
				';
			} else {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-danger alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-ban"></i>
							Error!
						</h5>
						Se ha producido un error al momento de editar al usuario, trate de nuevo
					</div>
				</div>
				';
			}

		}
	}

	public static function eliminarUserController() {
		if (isset($_GET["idBorrar"])) {
			$datosController = $_GET["idBorrar"];

			$respuesta = Datos::eliminarUserModel($datosController, "users");

			if ($respuesta == "success") {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-succes alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-check"></i>
							Exito
						</h5>
						Usuario eliminado con exito.
					</div>
				</div>
				';
			} else {
				echo '
				<div class="col-md-6 mt-3">
					<div class="alert alert-danger alert-dismissible">
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
						<h5>
							<i class="icon fas fa-ban"></i>
							Error!
						</h5>
						Se ha producido un error al momento de eliminar al usuario, trate de nuevo
					</div>
				</div>
				';
			}
		}
	}

	public static function contarFilas(){

    	$respuesta_users =Datos::contarFilasModel("users");
		echo '
	        <div class="col-lg-3 col-6">
				<div class="small-box bg-info">
					<div class="inner">
						<h3>'.$respuesta_users["filas"].'</h3>
						<p>Total de Usuarios</p>
					</div>
					<div class="icon">
						<i class="far fa-address-card"></i>
					</div>
					<a class="small-box-footer" href="index.php?action=usuarios">Más <i class="fas fa-arrow-circle-right"></i></a>
				</div>
	        </div>';
    	$respuesta_products =Datos::contarFilasModel("products");
		echo '
	        <div class="col-lg-3 col-6">
				<div class="small-box bg-info">
					<div class="inner">
						<h3>'.$respuesta_products["filas"].'</h3>
						<p>Total de Productos</p>
					</div>
					<div class="icon">
						<i class="far fa-address-card"></i>
					</div>
					<a class="small-box-footer" href="index.php?action=productos">Más <i class="fas fa-arrow-circle-right"></i></a>
				</div>
	        </div>';
    	$respuesta_category =Datos::contarFilasModel("categories");
		echo '
	        <div class="col-lg-3 col-6">
				<div class="small-box bg-info">
					<div class="inner">
						<h3>'.$respuesta_category["filas"].'</h3>
						<p>Total de Categorias</p>
					</div>
					<div class="icon">
						<i class="far fa-address-card"></i>
					</div>
					<a class="small-box-footer" href="index.php?action=categorias">Más <i class="fas fa-arrow-circle-right"></i></a>
				</div>
	        </div>';

    }

    public static function vistaProductsController(){
		$respuesta = Datos::vistaProductsModel("products");
		foreach ($respuesta as $row => $item) {
			echo '
				<tr>
					<td>
						<a href="index.php?action=productos&idProductEditar='.$item["id"].'"
						class="btn btn-warning btn-sm btn-icon" title="Editar" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
					</td>
					<td>
						<a href="index.php?action=productos&idBorrar='.$item["id"].'"
						class="btn btn-warning btn-sm btn-icon" title="Elimianr" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
					</td>
					<td>'.$item["codigo"].'</td>
					<td>'.$item["producto"].'</td>
					<td>'.$item["precio"].'</td>
					<td>'.$item["stock"].'</td>
					<td>'.$item["categoria"].'</td>
					<td><a href="index.php?action=productos&idProductAdd='.$item["id"].'"
					class="btn btn-warning btn_sm btn-icon" title="Agregar Stock" data-toggle="tooltip"><i class="fa fa-edit"></i></a></td>
					<td><a href="index.php?action=productos&idProductDel='.$item["id"].'"
					class="btn btn-warning btn_sm btn-icon" title="Quitar de Stock" data-toggle="tooltip"><i class="fa fa-edit"></i></a></td>
				</tr>
			';
		}
	}

	public static function registrarProductController() {
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-primary">
				<div class="card-header">
					<h4><b>Registro</b> de productos</h4>
				</div>
				<div class="card-body">
					<form method="POST" action="index.php?action=productos">
						<div class="form-group">
							<label for="codigotxt">Código: </label>
							<input class="form-control" name="codigotxt" id="codigotxt" placeholder="Código del producto" type="text" required>
						</div>
						<div class="form-group">
							<label for="codigotxt">Nombre: </label>
							<input class="form-control" name="nombretxt" id="nombretxt" placeholder="Nombre del producto" type="text" required>
						</div>
						<div class="form-group">
							<label for="codigotxt">Precio: </label>
							<input class="form-control" name="preciotxt" id="preciotxt" placeholder="Precio del producto" type="number" min="1" required>
						</div>
						<div class="form-group">
							<label for="codigotxt">Stock: </label>
							<input class="form-control" name="stocktxt" id="stocktxt" placeholder="Código del producto" type="number" min="1" required>
						</div>
						<div class="form-group">
							<label for="codigotxt">Categoría: </label>
							<select name="categoria" id="categoria" class="form-control">
								<?php
									$respuesta_categoria = Datos::obtenerCategoryModel("categories");
									foreach ($respuesta_categoria as $row => $item) {
								?>
										<option value="<?php echo $item["id"]; ?>"> <?php echo $item["categoria"]; ?></option>
								<?php } ?>
							</select>
						</div>
						<button class="btn btn-primary" type="submit">Agregar</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/*-- Esta funcion permite insertar productos llamando al modelo que se encuentra en elarchivo crud de modelos confirma con un isset que la caja de texto del codigo este llena y procede a llenar en una variable llamada datos controller este arreglo se manda como parametro aligual que elnombre de la tabla,una vez se obtiene una respuesta de la funcion del modelo de inserccion tenemos que checar si la respuesta fue afirmativa hubo un error y mostrara los respectivas alerta,para insertar datos en la tabla de historial se tiene que mandar a un modelollamado ultimoproductmodel este traera el ultimo dato insertado que es el id del producto que se manda en elarray de datosController2 junto al nombre de la tabla asi insertando los datos en la tabla historial --*/

	public static function insertarProductController() {
		if (isset($_POST["codigotxt"])) {

			$datosController = array(
				"codigo" => $_POST["codigotxt"],
				"precio" => $_POST["preciotxt"],
				"stock" => $_POST["stocktxt"],
				"categoria" => $_POST["categoria"],
				"nombre" => $_POST["nombretxt"]);

			$respuesta = Datos::insertarProductsModel($datosController, "products");

			if ($respuesta == "success") {
				$respuesta3 = Datos::ultimoProductsModel("products");

				$datosController2 = array(
					"user" => $_SESSION["id"],
					"cantidad" => $_POST["stocktxt"],
					"producto"=>$respuesta3["id"],
					"note"=>$_SESSION["nombre_usuario"]."agrego/compro");
					//,"reference"=>$_POST["referenciatxt"]);
				$respuesta2 = Datos::insertarHistorialModel($datosController2, "historial");
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Éxito!
							</h5>
							Producto agregado con éxito
						</div>
					</div> ';
			} else {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Error!
							</h5>
							 Se ha producido un error al momento de agregar el producto, trate de nuevo.
						</div>
					</div> ';
			}
		}
	}

public static function editarProductoController(){
		$datosController = $_GET["idProductEditar"];
		$respuesta = Datos::editarProductsModel($datosController, "products");
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-warning">
				<div class="card-header">
					<h4><b>Editor</b> de productos</h4>
				</div>
				<div class="card-body">
					<form method="post" action="index.php?action=inventario">
						<div class="form-group">
							<input type="hidden" name="idProductEditar" class="form-control" value="<?php echo $respuesta["id"]; ?>">
						</div>
						<div class="form-group">
							<label for="codigotxtEditar">Código: </label>
							<input class="form-control" name="codigotxtEditar" id="codigotxtEditar" type="text" value="<?php echo $respuesta["codigo"]; ?>" required placeholder="Codigo de producto">
						</div>
						<div class="form-group">
							<label for="nombretxtEditar">Nombre: </label>
							<input class="form-control" name="nombretxtEditar" id="nombretxtEditar" type="text" value="<?php echo $respuesta["nombre"]; ?>" required placeholder="Nombre de producto">
						</div>
						<div class="form-group">
							<label for="preciotxtEditar">Precio: </label>
							<input class="form-control" name="preciotxtEditar" id="preciotxtEditar" type="number" min="1" value="<?php echo $respuesta["precio"]; ?>" required placeholder="Precio de producto">
						</div>
						<div class="form-group">
							<label for="stocktxtEditar">Stock: </label>
							<input class="form-control" name="stocktxtEditar" id="stocktxtEditar" type="text" value="<?php echo $respuesta["stock"]; ?>" required placeholder="Stock de producto">
						</div>

						<div class="form-group">
							<label>Categoría</label>
							<select name="categoriaEditar" id="categoriaEditar" class="form-control">
								<?php
									$respuesta_categoria = Datos::obtenerCategoryModel("categories");
									foreach ($respuesta_categoria as $row => $item) {
								 ?>
								 	<option value="<?php echo $item["id"]; ?>"><?php echo $item["categoria"]; ?></option>
								 <?php
									}
								  ?>
							</select>
						</div>
						<button class="btn btn-primary" type="submit">Editar</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/*-- Esta funcion permite actualizar los datos en la tabla productos a partir del metodo form anterior mandando atravez del modelo del crud a traves del arreglo y con la variable respuesta mandamos dichos datos porque se llama al modelo actualizarproductsmodel si en el modelo se realizo correctamente entonces mandara una alerta decorrecto y pasara allenar dichos datos en elmodelo de insertar historial model en caso contrario no se hara nada y mostrara mensaje de error --*/

	public static function actualizarProductController() {
		if (isset($_POST["codigotxtEditar"])) {
			$datosController = array(
				"id"=>$_POST["idProductEditar"],
				"codigo"=>$_POST["codigotxtEditar"],
				"precio"=>$_POST["preciotxtEditar"],
				"stock"=>$_POST["stocktxtEditar"],
				"categoria"=>$_POST["categoriaEditar"],
				"nombre"=>$_POST["nombretxtEditar"]);
			$respuesta = Datos::actualizarProductsModel($datosController, "products");
			if ($respuesta == "success") {
				$datosController2 = array(
					"user" => $_SESSION["id"],
					"cantidad" => $_POST["stocktxtEditar"],
					"producto" => $_POST["idProductEditar"],
					"note"=>$_SESSION["nombre_usuario"]."agrego/compro",
					"reference" => $_POST["referenciatxtEditar"]);
				$respuesta2 = Datos::insertarHistorialModel($datosController2, "historial");
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Éxito!
							</h5>
							Producto actualizado con éxito
						</div>
					</div> ';
			} else {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Error!
							</h5>
							Error al actualizar el producto
						</div>
					</div> ';
			}
		}
	}

	//Esta funcion permite eliminar datos a partir del id seleccionado en la tabla a traves del boton mandando el id al modelo y la tabla una vez se borra mostrara un mensaje de error o de correcto dependiendo del caso
	public static function eliminarProductController(){
		if(isset($_GET["idBorrar"])){
    		$datosController=$_GET["idBorrar"];
    		$respuesta=Datos::eliminarProductsModel($datosController,"products");
    		if($respuesta=="success"){
    			echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-success alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-check"></i>
								¡Exito!
							</h5>
							Producto eliminado con exito.
						</div>
					</div>
				';
   			}else{
   				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
								¡Error!
							</h5>
							Se ha producido un error al momento de eliminar el Producto, trate de nuevo.
						</div>
					</div>
				';
   			}
    	}
	}


	public static function addProductController(){
		$datosController=$_GET["idProductAdd"];
		$respuesta=Datos::editarProductsModel($datosController, "products");
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-warning">
				<div class="card-header">
					<h4><b>Agregar</b> stock al producto</h4>
				</div>

				<div class="card-body">
					<form method="post" action="index.php?action=productos">
						<div class="form-group">
							<input type="hidden" name="idProductAdd" class="form-control" value="<?php echo $respuesta ["id"]; ?>" required>
						</div>

					<div class="form-group">
						<label for="codigotxtEditar">Stock:</label>
						<input class="form-control" name="addstocktxt" id="addstocktxt" type="number"
						min="1" value="1" required placeholder="Stock del producto">
					</div>

					<div class="form-group">
						<label for="referenciatxtadd">Motivo:</label>
						<input class="form-control" name="refereciatxtadd" id="refereciatxtadd" type="text"
						required placeholder="Referencia del producto">
					</div>

					<button class="btn btn-primary" type="submit">Realizar Cambio</button>
					</form>
				</div>
			</div>
		</div
	<?php
	}

	public static function actualizarStockController(){
		if(isset($_POST["addstocktxt"])){
			$datosController=array("id"=>$_POST["idProductAdd"], "stock"=>$_POST["addstocktxt"]);
			$respuesta=Datos::pushProductsModel($datosController, "products");
			if($respuesta=="success"){
				$datosController2=array("user"=>$_SESSION["id"], "cantidad"=>$_POST["addstocktxt"], "producto"=>$_POST["idProductAdd"], "note"=>$_SESSION["nombre_usuario"]."agrego/compro", "reference"=>$_POST["referenciatxtadd"]);
				$respuesta2=Datos::insertarHistorialModel($datosController2, "historial");
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-success alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-check"></i>
								¡Exito!
							</h5>
							Stock actualizado con exito.
						</div>
					</div>
				';
				}else {
					echo '
						<div class="col-md-6 mt-3">
							<div class="alert alert-danger alert-dismissible">
								<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
									<h5>
										<i class="icon fas fa-ban"></i>
										¡Error!
									</h5>
									Se ha producido un error al momento de modificar el stock del producto, trate de nuevo.
							</div>
						</div>
	        		';
				}
			}
		}
	//Esta funcion actualiza y llama al modelo de la tabla producto a su vez inserta una nueva fila a la tabla Historial, si el update sale correcto y elimina los productos del stock entonces insertara la actualizacion en la tabla Historial, si todo sale bien mostrara un mensaje de errror o de correcto dependiendo de la respuesta
	public static function actualizar2StockController(){
		if(isset($_POST["delstocktxt"])){
			$datosController=array("id"=>$_POST["idProductDel"], "stock"=>$_POST["delstocktxt"]);
			$respuesta=Datos::pullProductsModel($datosController, "products");
			if($respuesta=="success"){
				$datosController2=array("user"=>$_SESSION["id"], "cantidad"=>$_POST["delstocktxt"], "producto"=>$_POST["idProductDel"], "note"=>$_SESSION["nombre_usuario"]."quito", "reference"=>$_POST["referenciatxtdel"]);
				$respuesta2=Datos::insertarHistorialModel($datosController2, "historial");
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-success alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-check"></i>
								¡Exito!
							</h5>
							Stock modificado con exito.
						</div>
					</div>
				';
				}else {
					echo '
						<div class="col-md-6 mt-3">
							<div class="alert alert-danger alert-dismissible">
								<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
									<h5>
										<i class="icon fas fa-ban"></i>
										¡Error!
									</h5>
									Se ha producido un error al momento de modificar el stock del producto, trate de nuevo.
							</div>
						</div>
	        		';
				}
			}
		}

	//Esta funcion permite quitar productos al stock a traves del boton y un formulario para agregar dicha cantidad al producto se llama al modelo correspondiente para asi pasar al controlador que actualiza dicho modelo
	public static function delProductController(){
		$datosController=$_GET["idProductDel"];
		$respuesta=Datos::editarProductsModel($datosController, "products");
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-warning">
				<div class="card-header">
					<h4><b>Eliminar</b> stock al producto</h4>
				</div>

				<div class="card-body">
					<form method="post" action="index.php?action=inventario">
						<div class="form-group">
							<input type="hidden" name="idProductDel" class="form-control" value="<?php echo $respuesta ["id"]; ?>" required>
						</div>

					<div class="form-group">
						<label for="codigotxtEditar">Stock:</label>
						<input class="form-control" name="delstocktxt" id="delstocktxt" type="number" min="1" max="<?php echo $respuesta ["stock"]; ?>" value="<?php echo $respuesta ["stock"]; ?>" required placeholder="Stock del producto">
					</div>

					<div class="form-group">
						<label for="referenciatxtdel">Motivo:</label>
						<input class="form-control" name="referenciatxtdel" id="referenciatxtdel" type="text" required placeholder="Referencia del producto">
					</div>

					<button class="btn btn-primary" type="submit">Realizar Cambio</button>
					</form>
				</div>
			</div>
		</div
	<?php
	}

	public static function vistaCategoriasController(){
			$respuesta= Datos::vistaCategoriesModel("categories");

			foreach ($respuesta as $row => $item) {
			echo '
				<tr>
				<td>
				<a href="index.php?action=categorias&idCategoryEditar='.$item["idc"].'" class="btn btn-warning btn-sm btn-icon" title="Editar" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
				</td>
					<td>
				<a href="index.php?action=categorias&idBorrar='.$item["idc"].'" class="btn btn-warning btn-sm btn-icon" title="Editar" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
				</td>
					<td>'.$item["idc"].'</td>
					<td>'.$item["ncategoria"].'</td>
					<td>'.$item["dcategoria"].'</td>
					<td>'.$item["fcategoria"].'</td>

				</tr>
			';		}

	}

	public static function registrarCategoryController(){
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-primary">
				<div class="card-header">
					<h><b>Registro</b>de categorias</h>
				</div>

				<div class="card-body">
					<form method="post" action="index.php?action=categorias">
						<div class="form-group">
							<label for="ncategoriatxt">Nombre de la categoria</label>
							<input class="form-control" type="text" name="ncategoriatxt" id="ncategoriatxt" placeholder="Ingrese el nombre de la categoria">
						</div>
						<div class="form-group">
							<label for="dcategoriatxt">Descripcion de la categoria</label>
							<input class="form-control" type="text" name="dcategoriatxt" id="dcategoriatxt" placeholder="Ingrese la descripcion de la categoria">
						</div>
						<button class="btn btn-primary" type="submit">Agregar</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	public static function vistaHistorialController(){
		$respuesta= Datos::vistaHistorialModel("historial");
		foreach ($respuesta as $row => $item) {
			echo '
				<tr>
					<td>'.$item["usuario"].'</td>
					<td>'.$item["producto"].'</td>
					<td>'.$item["nota"].'</td>
					<td>'.$item["cantidad"].'</td>
					<td>'.$item["referencia"].'</td>
					<td>'.$item["fecha"].'</td>
				</tr>
			';
		}
	}

	public static function insertarCategoryController(){
		if (isset($_POST["ncategoriatxt"]) && isset($_POST["dcategoriatxt"])) {
			$datosController = array("nombre_categoria"=>$_POST["ncategoriatxt"], "descripcion_categoria"=>$_POST["dcategoriatxt"]);
			$respuesta = Datos::insertarCategoryModel($datosController, "categories");
			if ($respuesta == "success") {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-succes alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Éxito!
							</h5>
							Categoria agregada con éxito
						</div>
					</div> ';
			} else {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Error!
							</h5>
							Error al agregar la categoria
						</div>
					</div> ';
			}
		}
	}

	/* Este controlador funciona para mostrar un formulario al usuario cargando los datos del producto que desea editar mediante el uso de un modelp */
	public static function editarCategoryController(){
		$datosController = $_GET["idCategoryEditar"];
		$respuesta = Datos::editarCategoryModel($datosController, "categories");
		?>
		<div class="col-md-6 mt-3">
			<div class="card card-warning">
				<div class="card-header">
					<h4><b>Editor</b> de categorias</h4>
				</div>
				<div class="card-body">
					<form method="POST" action="index.php?action=categorias">
						<div class="form-group">
							<input type="hidden" name="idCategoryEditar" class="form-control" value="<?php echo $respuesta["id"] ?>">
						</div>
						<div class="form-group">
							<label for="ncategoriatxt">Nombre de categoria</label>
							<input class="form-control" type="text" name="ncategoriatxtEditar" id="ncategoriatxt" placeholder="Ingrese el nombre de la categoria" value="<?php echo $respuesta["nombre_categoria"] ?>">
						</div>
						<div class="form-group">
							<label for="dcategoriatxt">Descripcion de categoria</label>
							<input class="form-control" type="text" name="dcategoriatxtEditar" id="dcategoriatxt" placeholder="Ingrese la descripcion de la categoria" value="<?php echo $respuesta["descripcion_categoria"] ?>">
						</div>
						<button class="btn btn-primary" type="submit">Editar</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	public static function actualizarCategoryController(){
		if (isset($_POST["ncategoriatxtEditar"]) && isset($_POST["dcategoriatxtEditar"])) {
			$datosController = array("id"=>$_POST["idCategoryEditar"], "nombre_categoria"=>$_POST["ncategoriatxtEditar"], "descripcion_categoria"=>$_POST["dcategoriatxtEditar"]);
			$respuesta = Datos::actualizarCategoryModel($datosController, "categories");
			if ($respuesta == "success") {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Éxito!
							</h5>
							Categoria editada con éxito
						</div>
					</div> ';
			} else {
				echo '
					<div class="col-md-6 mt-3">
						<div class="alert alert-danger alert-dismissible">
							<button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
							<h5>
								<i class="icon fas fa-ban"></i>
									¡Error!
							</h5>
							Error al editar la categoria
						</div>
					</div> ';
			}
		}
	}

public static function eliminarCategoryController(){
        if (isset($_GET["idBorrar"])) {
            $datosController = $_GET["idBorrar"];
            $respuesta = Datos::eliminarCategoryModel($datosController, "categories");
            if ($respuesta == "success") {
                echo '
                    <div class="col-md-6 mt-3">
                        <div class="alert alert-success alert-dismissible">
                            <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5>
                                <i class="icon fas fa-ban"></i>
                                    ¡Éxito!
                            </h5>
                            Categoria eliminada con éxito
                        </div>
                    </div> ';
            } else {
                echo '
                    <div class="col-md-6 mt-3">
                        <div class="alert alert-danger alert-dismissible">
                            <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5>
                                <i class="icon fas fa-ban"></i>
                                    ¡Error!
                            </h5>
                            Error al eliminar la categoria
                        </div>
                    </div> ';
            }
        }
    }
}

?>