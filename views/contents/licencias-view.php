<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-key zmdi-hc-fw"></i> Licencias <small>Gestor</small></h1>
    </div>
    <p class="lead">
        Aquí puedes registrar nuevas licencias para las empresas. Las licencias pueden ser temporales o permanentes.
    </p>
</div>

<div class="container-fluid">
    <ul class="breadcrumb breadcrumb-tabs">
        <li class="active">
            <a href="<?php echo SERVERURL; ?>licencia/" class="btn btn-info">
                <i class="zmdi zmdi-plus"></i> Nueva Licencia
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>licencialist/" class="btn btn-success">
                <i class="zmdi zmdi-format-list-bulleted"></i> Lista de Licencias
            </a>
        </li>
    </ul>
</div>

<?php 
    require_once "./controllers/licenciasController.php";
    $insLicencia = new licenciasController();

    if(isset($_POST['empresa_id']) && isset($_POST['licencia_key'])){
        echo $insLicencia->add_licencia_controller();
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Nueva Licencia</h3>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" autocomplete="off">
                        <fieldset>
                            <legend><i class="zmdi zmdi-case"></i> Datos de Licencia</legend>
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Select Empresa -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Empresa *</label>
                                            <select name="empresa_id" class="form-control" required>
                                                <option value="" disabled selected>Seleccione una empresa</option>
                                                <?php 
                                                    // Carga empresas de la DB
                                                    $empresas = $insLicencia->execute_single_query("SELECT id, nombre FROM empresas ORDER BY nombre ASC");
                                                    foreach ($empresas as $empresa) {
                                                        $selected = (isset($_POST['empresa_id']) && $_POST['empresa_id'] == $empresa['id']) ? 'selected' : '';
                                                        echo '<option value="'.$empresa['id'].'" '.$selected.'>'.$empresa['nombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Clave de Licencia -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Clave de Licencia *</label>
                                            <input class="form-control" type="text" name="licencia_key" required maxlength="100" 
                                            value="<?php echo isset($_POST['licencia_key']) ? htmlspecialchars($_POST['licencia_key']) : ''; ?>">
                                        </div>
                                    </div>

                                    <!-- Tipo -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Tipo de Licencia *</label>
                                            <select name="tipo" class="form-control" required>
                                                <?php
                                                    $tipos = ['demo'=>'Demo', 'mensual'=>'Mensual', 'trimestral'=>'Trimestral', 'anual'=>'Anual', 'permanente'=>'Permanente'];
                                                    $tipo_sel = isset($_POST['tipo']) ? $_POST['tipo'] : '';
                                                    foreach($tipos as $val => $label){
                                                        $sel = ($tipo_sel == $val) ? 'selected' : '';
                                                        echo "<option value='$val' $sel>$label</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Estado *</label>
                                            <select name="estado" class="form-control" required>
                                                <?php
                                                    $estados = ['activa'=>'Activa', 'suspendida'=>'Suspendida'];
                                                    $estado_sel = isset($_POST['estado']) ? $_POST['estado'] : '';
                                                    foreach($estados as $val => $label){
                                                        $sel = ($estado_sel == $val) ? 'selected' : '';
                                                        echo "<option value='$val' $sel>$label</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Fecha Inicio -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Fecha Inicio *</label>
                                            <input class="form-control" type="date" name="fecha_inicio" required
                                            value="<?php echo isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : ''; ?>">
                                        </div>
                                    </div>

                                    <!-- Fecha Fin -->
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Fecha Fin</label>
                                            <input class="form-control" type="date" name="fecha_fin"
                                            value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : ''; ?>">
                                        </div>
                                    </div>
									 <div class="col-xs-12 col-sm-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Observaciones</label>
                                            <textarea class="form-control" name="observacion"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <p class="text-center">
                            <button type="submit" class="btn btn-info btn-raised btn-sm">
                                <i class="zmdi zmdi-floppy"></i> Guardar Licencia
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const tipoSelect = document.querySelector('select[name="tipo"]');
    const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
    const fechaFinInput = document.querySelector('input[name="fecha_fin"]');

    function calcularFechaFin() {
        const tipo = tipoSelect.value;
        const fechaInicio = fechaInicioInput.value;
        if(!fechaInicio) return;

        let fechaFin = null;
        const fecha = new Date(fechaInicio);

        switch(tipo) {
            case 'mensual':
                fecha.setMonth(fecha.getMonth() + 1);
                break;
            case 'trimestral':
                fecha.setMonth(fecha.getMonth() + 3);
                break;
            case 'anual':
                fecha.setFullYear(fecha.getFullYear() + 1);
                break;
            case 'demo':
            case 'permanente':
                fechaFinInput.value = '';
                fechaFinInput.disabled = true;
                return;
        }

        fechaFinInput.disabled = false;
        const y = fecha.getFullYear();
        const m = ('0'+(fecha.getMonth()+1)).slice(-2);
        const d = ('0'+fecha.getDate()).slice(-2);
        fechaFinInput.value = `${y}-${m}-${d}`;
    }

    tipoSelect.addEventListener('change', calcularFechaFin);
    fechaInicioInput.addEventListener('change', calcularFechaFin);
    calcularFechaFin();
});
</script>

<?php 
else:
    $logout2 = new loginController();
    echo $logout2->login_session_force_destroy_controller(); 
endif;
?>
