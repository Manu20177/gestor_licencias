<?php
	if($actionsRequired){
		require_once "../models/empresaModel.php";
	}else{ 
		require_once "./models/empresaModel.php";
	}

	class empresaController extends empresaModel{

		 /* === Controlador para agregar nueva empresa === */
		public function add_empresa_controller() {
			// Validar y limpiar datos recibidos por POST
			$nombre = self::clean_string($_POST['nombre'] ?? '');
			$ruc = self::clean_string($_POST['ruc'] ?? '');
			$direccion = self::clean_string($_POST['direccion'] ?? '');
			$telefono = self::clean_string($_POST['telefono'] ?? '');
			$email = self::clean_string($_POST['email'] ?? '');
			$creado_por = $_SESSION['userKey'] ?? 'system'; // o el usuario en sesión
			$creado_en = date('Y-m-d H:i:s');

			// Validación básica
			if(empty($nombre) || empty($ruc)) {
				return self::sweet_alert_single([
					"title" => "Campos obligatorios",
					"text" => "Por favor completa el nombre y RUC.",
					"type" => "error"
				]);
			}

			// Verificar si empresa ya existe por RUC
			$check = self::check_empresa_ruc_model($ruc);
			if($check->rowCount() > 0){
				return self::sweet_alert_single([
					"title" => "Empresa duplicada",
					"text" => "Ya existe una empresa con ese RUC.",
					"type" => "error"
				]);
			}

			// Preparar datos para el modelo
			$datosEmpresa = [
				"nombre" => $nombre,
				"ruc" => $ruc,
				"direccion" => $direccion,
				"telefono" => $telefono,
				"email" => $email,
				"creado_por" => $creado_por,
				"creado_en" => $creado_en
			];

			// Insertar empresa
			$save = self::add_empresa_model($datosEmpresa);

			if($save->rowCount() >= 1){
				return self::sweet_alert_single([
					"title" => "Empresa registrada",
					"text" => "La empresa se registró correctamente.",
					"type" => "success"
				]);
			} else {
				return self::sweet_alert_single([
					"title" => "Error",
					"text" => "No se pudo registrar la empresa. Intenta nuevamente.",
					"type" => "error"
				]);
			}
		}

		/* === Controlador para paginar empresas === */
		public function pagination_empresa_controller($pagina, $registrosPorPagina) {
			// NOTA: Ya no usaremos $pagina ni $registrosPorPagina para la paginación manual,
			// sino que traeremos TODO para que DataTables lo pagine

			$conexion = self::connect();

			// Traer todas las empresas ordenadas por nombre (puedes limitar si hay muchas)
			$sql = "SELECT * FROM empresas ORDER BY nombre ASC";
			$consulta = $conexion->query($sql);

			$tabla = '
			<table id="tabla-global" class="table table-hover table-striped text-center">
				<thead>
					<tr>
						<th>#</th>
						<th>Nombre</th>
						<th>RUC</th>
						<th>Dirección</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
			';

			if ($consulta->rowCount() > 0) {
				$contador = 1;
				foreach ($consulta as $fila) {
					$tabla .= '
					<tr>
						<td>'.$contador.'</td>
						<td>'.$fila['nombre'].'</td>
						<td>'.$fila['ruc'].'</td>
						<td>'.$fila['direccion'].'</td>
						<td>'.$fila['telefono_contacto'].'</td>
						<td>'.$fila['correo_contacto'].'</td>
						<td>
							<a href="'.SERVERURL.'empresadetalle/'.$fila['id'].'/" class="btn btn-info btn-raised btn-xs" title="Ver detalles">
								<i class="zmdi zmdi-eye"></i>
							</a>
							<form method="POST" style="display:inline;">
								<input type="hidden" name="empresa_id_delete" value="'.$fila['id'].'">
								<button type="submit" class="btn btn-danger btn-raised btn-xs" title="Eliminar" onclick="return confirm(\'¿Estás seguro de eliminar esta empresa?\')">
									<i class="zmdi zmdi-delete"></i>
								</button>
							</form>
						</td>
					</tr>
					';
					$contador++;
				}
			} else {
				$tabla .= '
				<tr>
					<td colspan="7">No hay empresas registradas aún.</td>
				</tr>
				';
			}

			$tabla .= '
				</tbody>
			</table>
			';

			// Ya no retornamos paginación manual para evitar conflictos con DataTables

			return $tabla;
		}

		public function get_empresa_by_id_controller($id){
			$id = intval($id);
			if($id <= 0) return false;

			$empresa = self::get_empresa_by_id_model($id);
			$num = $empresa->rowCount();
			// debug
			// echo "Filas encontradas: $num";
			if($num == 1){
				return $empresa->fetch();
			}else{
				return false;
			}
		}
		public function delete_empresa_controller($empresa_id) {
			$empresa_id = intval($empresa_id);
			if($empresa_id <= 0) {
				$alert = [
					"title" => "ID inválido",
					"text" => "El ID de la empresa no es válido.",
					"type" => "error"
				];
				return self::sweet_alert_single($alert);
			}

			// Primero, podrías verificar si la empresa existe (opcional)
			$empresa = self::get_empresa_by_id_model($empresa_id);
			if($empresa->rowCount() == 0) {
				$alert = [
					"title" => "Empresa no encontrada",
					"text" => "No se encontró ninguna empresa con ese ID.",
					"type" => "error"
				];
				return self::sweet_alert_single($alert);
			}

			// Preparar y ejecutar la eliminación
			$query = self::connect()->prepare("DELETE FROM empresas WHERE id = :id");
			$query->bindParam(":id", $empresa_id);
			$query->execute();

			if($query->rowCount() > 0) {
				$alert = [
					"title" => "Empresa eliminada",
					"text" => "La empresa fue eliminada correctamente.",
					"type" => "success"
				];
			} else {
				$alert = [
					"title" => "Error",
					"text" => "No se pudo eliminar la empresa. Intente nuevamente.",
					"type" => "error"
				];
			}

			return self::sweet_alert_single($alert);
		}







	
	}