<?php if($_SESSION['userType']=="Administrador"): ?>

<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-business zmdi-hc-fw"></i> Empresas <small>(Detalle)</small></h1>
	</div>
	<p class="lead">
		En esta sección puede ver la información detallada de la empresa seleccionada, y editar o eliminar si tiene permisos.
	</p>
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  	<a href="<?php echo SERVERURL; ?>empresas/" class="btn btn-info">
	  		<i class="zmdi zmdi-plus"></i> Nueva Empresa
	  	</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>empresaslist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> Lista de Empresas
	  		</a>
	  	</li>
	</ul>
</div>

<?php  
	require_once "./controllers/empresaController.php";
	$insEmpresa = new empresaController();

	// Obtener ID de la empresa desde URL, ej: empresadetalle/5/
	$url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
	$empresa_id = isset($url[2]) ? intval($url[2]) : 0;

	// Lógica para eliminar empresa (si se usa aquí)
	if(isset($_POST['empresa_id_delete'])){
		echo $insEmpresa->delete_empresa_controller($_POST['empresa_id_delete']);
	}

	// Obtener datos de la empresa
	$empresaData = $insEmpresa->get_empresa_by_id_controller($empresa_id);

	if(!$empresaData){
		echo '<div class="alert alert-warning text-center">Empresa no encontrada.</div>';
	} else {
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="zmdi zmdi-info-outline"></i> Detalle de la Empresa</h3>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-bordered">
						<tr>
							<th>Nombre</th>
							<td><?php echo htmlspecialchars($empresaData['nombre']); ?></td>
						</tr>
						<tr>
							<th>RUC</th>
							<td><?php echo htmlspecialchars($empresaData['ruc']); ?></td>
						</tr>
						<tr>
							<th>Dirección</th>
							<td><?php echo htmlspecialchars($empresaData['direccion']); ?></td>
						</tr>
						<tr>
							<th>Teléfono</th>
							<td><?php echo htmlspecialchars($empresaData['telefono_contacto']); ?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><?php echo htmlspecialchars($empresaData['correo_contacto']); ?></td>
						</tr>
						<tr>
							<th>Observaciones</th>
							<td><?php echo nl2br(htmlspecialchars($empresaData['observaciones'] ?? '')); ?></td>
						</tr>
					</table>

					<div class="text-center">
						<a href="<?php echo SERVERURL.'empresaedit/'.$empresa_id.'/'; ?>" class="btn btn-primary">
							<i class="zmdi zmdi-edit"></i> Editar Empresa
						</a>
						<form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar esta empresa?');">
							<input type="hidden" name="empresa_id_delete" value="<?php echo $empresa_id; ?>">
							<button type="submit" class="btn btn-danger">
								<i class="zmdi zmdi-delete"></i> Eliminar Empresa
							</button>
						</form>
						<a href="<?php echo SERVERURL.'empresaslist/'; ?>" class="btn btn-default">
							<i class="zmdi zmdi-arrow-left"></i> Volver a la lista
						</a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php
	} // Fin else empresaData

else:
	$logout2 = new loginController();
	echo $logout2->login_session_force_destroy_controller(); 
endif;
?>
