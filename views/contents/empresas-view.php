<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-city zmdi-hc-fw"></i> Empresas <small>(Registro)</small></h1>
    </div>
    <p class="lead">
        Bienvenido a la sección de empresas, aquí podrás registrar nuevas empresas. Los campos marcados con * son obligatorios.
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
            <a href="<?php echo SERVERURL; ?>empresalist/" class="btn btn-success">
                <i class="zmdi zmdi-format-list-bulleted"></i> Lista de Empresas
            </a>
        </li>
    </ul>
</div>

<?php 
    require_once "./controllers/empresaController.php";
    $insEmpresa = new empresaController();

    if(isset($_POST['nombre']) && isset($_POST['ruc'])){
        echo $insEmpresa->add_empresa_controller();
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Nueva Empresa</h3>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" autocomplete="off">
                        <fieldset>
                            <legend><i class="zmdi zmdi-city-alt"></i> Datos de la Empresa</legend><br>
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Nombre de la Empresa *</label>
                                            <input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.,&()\- ]{1,100}" maxlength="100" class="form-control" type="text" name="nombre" value="<?php if(isset($_POST['nombre'])){ echo htmlspecialchars($_POST['nombre']); } ?>" required="">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">RUC *</label>
                                            <input pattern="[0-9]{13}" maxlength="13" class="form-control" type="text" name="ruc" value="<?php if(isset($_POST['ruc'])){ echo htmlspecialchars($_POST['ruc']); } ?>" required="">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Dirección</label>
                                            <input maxlength="150" class="form-control" type="text" name="direccion" value="<?php if(isset($_POST['direccion'])){ echo htmlspecialchars($_POST['direccion']); } ?>">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Teléfono</label>
                                            <input pattern="[0-9]{7,15}" maxlength="15" class="form-control" type="text" name="telefono" value="<?php if(isset($_POST['telefono'])){ echo htmlspecialchars($_POST['telefono']); } ?>">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Correo Electrónico</label>
                                            <input class="form-control" type="email" name="email" value="<?php if(isset($_POST['email'])){ echo htmlspecialchars($_POST['email']); } ?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                        <p class="text-center">
                            <button type="submit" class="btn btn-info btn-raised btn-sm">
                                <i class="zmdi zmdi-floppy"></i> Guardar Empresa
                            </button>
                        </p>
                    </form>
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
