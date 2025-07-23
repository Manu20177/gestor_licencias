<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class empresaModel extends mainModel{

		/* === Modelo para agregar nueva empresa === */
		public function add_empresa_model($data) {
			$query = self::connect()->prepare("INSERT INTO empresas (nombre, ruc, direccion, correo_contacto, telefono_contacto, creado_por)
				VALUES (:nombre, :ruc, :direccion, :email, :telefono, :creado_por)");

			$query->bindParam(":nombre", $data['nombre']);
			$query->bindParam(":ruc", $data['ruc']);
			$query->bindParam(":direccion", $data['direccion']);
			$query->bindParam(":telefono", $data['telefono']);
			$query->bindParam(":email", $data['email']);
			$query->bindParam(":creado_por", $data['creado_por']);
			$query->execute();

			return $query;
		}

		/* === Modelo para verificar si existe empresa por RUC === */
		public function check_empresa_ruc_model($ruc) {
			$query = self::connect()->prepare("SELECT id FROM empresas WHERE ruc = :ruc LIMIT 1");
			$query->bindParam(":ruc", $ruc);
			$query->execute();
			return $query;
		}

		/* === Modelo para obtener total de empresas === */
		public function get_total_empresas_model() {
			$query = self::connect()->query("SELECT COUNT(*) AS total FROM empresas");
			$total = $query->fetch();
			return (int)$total['total'];
		}

		/* === Modelo para obtener empresas paginadas === */
		public function get_empresas_model($inicio, $registrosPorPagina) {
			$query = self::connect()->prepare("SELECT * FROM empresas ORDER BY nombre ASC LIMIT :inicio, :limite");
			$query->bindParam(':inicio', $inicio, PDO::PARAM_INT);
			$query->bindParam(':limite', $registrosPorPagina, PDO::PARAM_INT);
			$query->execute();
			return $query;
		}
		public function get_empresa_by_id_model($id){
			$query = self::connect()->prepare("SELECT * FROM empresas WHERE id = :id LIMIT 1");
			$query->bindParam(":id", $id);
			$query->execute();
			return $query;
		}




	}