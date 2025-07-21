<?php 
	class viewsModel{
		public function get_views_model($views){
			if(
				$views=="home" ||
				$views=="dashboard" ||
				$views=="admin" ||
				$views=="adminlist" ||
				$views=="admininfo" ||
				$views=="account" ||
				$views=="student" ||
				$views=="studentlist" ||
				$views=="studentinfo" ||		
				
				$views=="correo" ||	
				$views=="correolist" ||	
				$views=="correosview" ||
				$views=="backup" ||	
				

							
				$views=="search"
			){
				if(is_file("./views/contents/".$views."-view.php")){
					$contents="./views/contents/".$views."-view.php";
				}else{
					$contents="login";
				}
			}elseif($views=="index"){
				$contents="login";
			}elseif($views=="login"){
				$contents="login";
			}elseif($views=="registro"){
				$contents="registro";
			}else{
				$contents="login";
			}
			return $contents;
		}
	}