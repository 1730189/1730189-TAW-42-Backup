<?php

	class Conexion{

		public static function conectar(){
			$link = new PDO("mysql:host=localhost;dbname=practica25052020-inventario", "root", "");
			return $link;
		}
	}
?>
