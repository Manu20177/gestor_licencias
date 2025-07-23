<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class licenciaModel extends mainModel{

		public function add_licencia_model($data){
			$query=self::connect()->prepare("INSERT INTO licencias(empresa_id,tipo,fecha_inicio,fecha_fin,estado,observaciones,creado_por,creado_en)
			VALUES(:empresa_id,:tipo,:fecha_inicio,:fecha_fin,:estado,:observaciones,:creado_por,:creado_en)");
			
			$query->bindParam(":empresa_id", $data['empresa_id']);
			$query->bindParam(":tipo", $data['tipo']);
			$query->bindParam(":fecha_inicio", $data['fecha_inicio']);
			$query->bindParam(":fecha_fin", $data['fecha_fin']);
			$query->bindParam(":estado", $data['estado']);
			$query->bindParam(":observaciones", $data['observaciones']);
			$query->bindParam(":creado_por", $data['creado_por']);
			$query->bindParam(":creado_en", $data['creado_en']);
			$query->execute();

			return $query;
		}
		
		public function get_licencia_activa_model($empresa_id){
			$query=self::connect()->prepare("SELECT * FROM licencias WHERE empresa_id = :empresa_id AND estado = 'activa' ORDER BY fecha_fin DESC LIMIT 1");
			$query->bindParam(":empresa_id", $empresa_id);
			$query->execute();
			return $query;
		}

		public function check_licencia_key_model($licencia_key){
			$query = self::connect()->prepare("SELECT id FROM licencias WHERE clave = :clave LIMIT 1");
			$query->bindParam(":clave", $licencia_key);
			$query->execute();
			return $query;
		}


	}