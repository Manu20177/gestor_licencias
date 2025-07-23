<?php if($_SESSION['userType'] == "Administrador"): ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-store zmdi-hc-fw"></i> Empresas <small>(Listado)</small></h1>
	</div>
	<p class="lead">
		En esta sección puede ver el listado de todas las empresas registradas en el sistema, puede actualizar datos o eliminar una empresa cuando lo desee.
	</p>
</div>
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li class="active">
	  	<a href="<?php echo SERVERURL; ?>empresas/" class="btn btn-info">
	  		<i class="zmdi zmdi-plus"></i> Nueva Empresa
	  	</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>empresaslist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> Lista
	  		</a>
	  	</li>
	</ul>
</div>

<?php  
	require_once "./controllers/empresaController.php";
	$insEmpresa = new empresaController();

	// Si recibes formulario para eliminar empresa (ejemplo)
	// if(isset($_POST['empresa_id_delete'])){
	// 	echo $insEmpresa->delete_empresa_controller($_POST['empresa_id_delete']);
	// }
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
	  		<div class="panel panel-success">
			  	<div class="panel-heading">
			    	<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> Lista de Empresas</h3>
			  	</div>
			  	<div class="panel-body">
					<div class="table-responsive">
						<?php
							// Llamar método para paginación (puedes pasar página y registros por página)
							echo $insEmpresa->pagination_empresa_controller($_GET['page'] ?? 1, 10);
						?>
					</div>
			  	</div>
			</div>
		</div>
	</div>
</div>

<?php 
else:
	$logout2 = new loginController();
    echo $logout2->login_session_force_destroy_controller(); 
endif;
?>
