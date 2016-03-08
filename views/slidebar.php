
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <?php
            echo $this->load->view('login/user_logo', '', TRUE);
            ?>
        </div>
        
        <ul class="sidebar-menu">
            <li class="header">LOG&Iacute;STICA</li>
            <li> <a href="<?= base_url('logistica/ordengasto') ?>"><i class="glyphicon glyphicon-plus"></i> Nueva Orden Gasto</a> </li>
            <li> <a class="active" href="<?= base_url('logistica/ordengasto/#') ?>"><i class="glyphicon glyphicon-list"></i> Reporte de Gastos</a> </li>
            <li> <a href="<?= base_url('logistica/transferencia/#') ?>"><i class="glyphicon glyphicon-plus"></i> Transferencia</a> </li>
            <li> <a class="active" href="<?= base_url('logistica/transferencia/#') ?>"><i class="glyphicon glyphicon-list"></i> Reporte de Transferencias</a> </li>
            <li> <a class="" href="<?= base_url('logistica/presupuesto/index') ?>"><i class="glyphicon glyphicon-th"></i> Presupuesto</a> </li>
<!--             <li> <a class="" href="<?= base_url('') ?>"><i class="glyphicon glyphicon-th-list"></i> Reporte Formulario 125</a> </li>
              <li> <a class="" href="<?= base_url('') ?>"><i class="glyphicon glyphicon-th-list"></i> Formulario Nro 1</a> </li>-->
        </ul>
    </section>
</aside>
<?php $css = array( 
//    base_url('resources/css//print_format.css'),
//    base_url('medicos/css/form_002.css')
//    base_url('resources/css/solicitudes_informes.css')
);
echo csslink($css);
?>