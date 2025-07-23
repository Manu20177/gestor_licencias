<?php
	if($actionsRequired){
		require_once "../models/licenciaModel.php";
	}else{ 
		require_once "./models/licenciaModel.php";
	}

	class licenciasController extends licenciaModel{

		/* === Controlador para agregar nueva licencia === */
		public function add_licencia_controller() {
			// Validar y limpiar datos
			$empresa = self::clean_string($_POST['empresa_id']); // asumo que es empresa_id, no nombre
			$licencia_key = self::clean_string($_POST['licencia_key']);
			$tipo = self::clean_string($_POST['tipo']);
			$estado = self::clean_string($_POST['estado']);
			$observaciones = self::clean_string($_POST['observacion']);
			$fecha_inicio = $_POST['fecha_inicio'];
			$creado_por=$_SESSION['userKey'];


			// Validación básica
			if(empty($empresa) || empty($licencia_key) || empty($tipo) || empty($fecha_inicio)){
				$alert = [
					"title"=>"Faltan campos obligatorios",
					"text"=>"Por favor completa todos los campos requeridos.",
					"type"=>"error"
				];
				return self::sweet_alert_single($alert);
			}

			// Verificar si ya existe esa licencia_key
			$check = self::check_licencia_key_model($licencia_key);
			if($check->rowCount() > 0){
				$alert = [
					"title"=>"Licencia duplicada",
					"text"=>"La clave de licencia ya existe.",
					"type"=>"error"
				];
				return self::sweet_alert_single($alert);
			}

			// Calcular fecha_fin según tipo
			$fecha_fin = null;
			if($tipo !== 'permanente' && $tipo !== 'demo'){
				try {
					$fechaObj = new DateTime($fecha_inicio);
					switch($tipo){
						case 'mensual':
							$fechaObj->modify('+1 month');
							break;
						case 'trimestral':
							$fechaObj->modify('+3 months');
							break;
						case 'anual':
							$fechaObj->modify('+1 year');
							break;
						default:
							// En caso que llegue algo no esperado
							$fechaObj = null;
							break;
					}
					if($fechaObj) $fecha_fin = $fechaObj->format('Y-m-d');
				} catch(Exception $e){
					// Manejar error si fecha_inicio no es válida
					$alert = [
						"title"=>"Error en fecha",
						"text"=>"La fecha de inicio no es válida.",
						"type"=>"error"
					];
					return self::sweet_alert_single($alert);
				}
			}

			// Preparar datos para el modelo
			$datosLicencia = [
				"empresa_id" => $empresa,
				"tipo" => $tipo,
				"fecha_inicio" => $fecha_inicio,
				"fecha_fin" => $fecha_fin,
				"estado" => $estado,
				"observaciones" => $observaciones,
				"creado_por" => $creado_por,
			];

			// Insertar en BD
			$save = self::add_licencia_model($datosLicencia);

			if($save->rowCount() >= 1){
				$alert = [
					"title"=>"Licencia registrada",
					"text"=>"La licencia se registró correctamente.",
					"type"=>"success"
				];
			} else {
				$alert = [
					"title"=>"Error",
					"text"=>"No se pudo registrar la licencia. Intenta nuevamente.",
					"type"=>"error"
				];
			}

			return self::sweet_alert_single($alert);
		}

		public function pagination_licencia_controller($pagina, $registrosPorPagina) {
			$pagina = intval($pagina);
			$inicio = ($pagina > 0) ? ($pagina - 1) * $registrosPorPagina : 0;

			$conexion = self::connect();

			// Total de registros
			$totalQuery = $conexion->query("SELECT COUNT(*) AS total FROM licencias");
			$totalReg = $totalQuery->fetch()['total'];
			$totalPaginas = ceil($totalReg / $registrosPorPagina);

			// Consulta principal con JOIN
			$sql = "SELECT l.*, e.nombre AS empresa_nombre, e.ruc 
					FROM licencias l 
					INNER JOIN empresas e ON l.empresa_id = e.id 
					ORDER BY l.fecha_inicio DESC 
					LIMIT $inicio, $registrosPorPagina";
			
			$consulta = $conexion->query($sql);
			$tabla = '';

			$tabla .= '
			<table class="table table-hover text-center">
				<thead>
					<tr>
						<th>#</th>
						<th>Empresa</th>
						<th>RUC</th>
						<th>Tipo</th>
						<th>Estado</th>
						<th>Inicio</th>
						<th>Fin</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
			';

			if ($consulta->rowCount() > 0) {
				$contador = $inicio + 1;
				foreach ($consulta as $fila) {
					$estado = '';
					switch ($fila['estado']) {
						case 'activa':
							$estado = '<span class="label label-success">Activa</span>';
							break;
						case 'suspendida':
							$estado = '<span class="label label-warning">Suspendida</span>';
							break;
						case 'vencida':
							$estado = '<span class="label label-danger">Vencida</span>';
							break;
						default:
							$estado = '<span class="label label-default">'.$fila['estado'].'</span>';
					}

					$tabla .= '
					<tr>
						<td>'.$contador.'</td>
						<td>'.$fila['empresa_nombre'].'</td>
						<td>'.$fila['ruc'].'</td>
						<td>'.ucfirst($fila['tipo']).'</td>
						<td>'.$estado.'</td>
						<td>'.$fila['fecha_inicio'].'</td>
						<td>'.($fila['fecha_fin'] ?? 'Permanente').'</td>
						<td>
							<a href="'.SERVERURL.'licenciadetalle/'.$fila['id'].'/" class="btn btn-info btn-raised btn-xs" title="Ver detalles">
								<i class="zmdi zmdi-eye"></i>
							</a>
						</td>
					</tr>
					';
					$contador++;
				}
			} else {
				$tabla .= '
				<tr>
					<td colspan="8">No hay licencias registradas aún.</td>
				</tr>
				';
			}

			$tabla .= '</tbody></table>';

			// Paginación
			if ($totalPaginas > 1) {
				$tabla .= '<nav class="text-center"><ul class="pagination pagination-sm">';
				for ($i = 1; $i <= $totalPaginas; $i++) {
					$active = ($i == $pagina) ? 'active' : '';
					$tabla .= '<li class="'.$active.'"><a href="'.SERVERURL.'licenciaslist/'.$i.'/">'.$i.'</a></li>';
				}
				$tabla .= '</ul></nav>';
			}

			return $tabla;
		}



		/* Controlador para destruir sesion - Controller to destroy session*/
		public function login_session_destroy_controller() {
            if (!isset($_POST['token'])) {
                echo '
                    <script>
                        window.location.href = "'.SERVERURL.'login/";
                    </script>
                ';
                exit();
            }
        
            $token = $_POST['token'];
        
            if (isset($_SESSION['userToken'], $_SESSION['userName']) &&
                $_SESSION['userToken'] === $token) {
        
                // Cerrar sesión si coincide el token
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_unset();
                    session_destroy();
                }
            }
        
            echo '
                <script>
                    window.location.href = "'.SERVERURL.'login/";
                </script>
            ';
            exit();
        }


	
	}