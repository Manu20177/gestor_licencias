<?php if ($_SESSION['userType'] == "Administrador"): ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-key zmdi-hc-fw"></i> Licencias <small>Administración</small></h1>
    </div>
    <p class="lead">
        En esta sección puedes ver las licencias activas o vencidas por empresa, su tipo, duración y estado actual.
    </p>
</div>

<div class="container-fluid">
    <ul class="breadcrumb breadcrumb-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>licencias/" class="btn btn-info">
                <i class="zmdi zmdi-plus"></i> Nueva Licencia
            </a>
        </li>
        <li class="active">
            <a href="<?php echo SERVERURL; ?>licenciaslist/" class="btn btn-success">
                <i class="zmdi zmdi-format-list-bulleted"></i> Lista de Licencias
            </a>
        </li>
    </ul>
</div>

<?php
    require_once "./controllers/licenciasController.php";
    $licenciaCtrl = new licenciasController();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> Licencias Registradas</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php
                            $page = explode("/", $_GET['views']);
                            echo $licenciaCtrl->pagination_licencia_controller($page[1], 10);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else:
    $logout2 = new loginController();
    echo $logout2->login_session_force_destroy_controller();
endif; ?>
